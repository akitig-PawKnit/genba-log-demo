<?php

namespace App\Http\Controllers\Worker;

use App\Enums\AttendanceStatus;
use App\Enums\WorkRole;
use App\Enums\WorkShift;
use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\StoreWorkReportRequest;
use App\Models\DailyAttendance;
use App\Models\Site;
use App\Models\Worker;
use App\Models\WorkerRate;
use App\Models\WorkReport;
use App\Services\WorkCostCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class WorkReportController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $worker = $this->currentWorker($request);

        $todayAttendance = DailyAttendance::query()
            ->where('worker_id', $worker->id)
            ->whereDate('work_date', today())
            ->first();

        if ($todayAttendance?->status === AttendanceStatus::Off) {
            return redirect()
                ->route('worker.home')
                ->withErrors([
                    'attendance' => '本日は休みとして登録されています。先に登録を取り消してください。',
                ]);
        }

        /*
         * 初版では一日一現場に制限する。
         * DB構造は将来の複数現場登録に対応している。
         */
        if ($todayAttendance?->workReports()->exists()) {
            return redirect()
                ->route('worker.home')
                ->withErrors([
                    'attendance' => '本日の出勤内容はすでに登録されています。',
                ]);
        }

        $sites = Site::query()
            ->where('is_active', true)
            ->where(function ($query): void {
                $query
                    ->whereNull('starts_on')
                    ->orWhereDate('starts_on', '<=', today());
            })
            ->where(function ($query): void {
                $query
                    ->whereNull('ended_on')
                    ->orWhereDate('ended_on', '>=', today());
            })
            ->orderBy('name')
            ->get();

        return view('worker.work-reports.create', [
            'worker' => $worker,
            'sites' => $sites,
        ]);
    }

    public function store(
        StoreWorkReportRequest $request,
        WorkCostCalculator $calculator,
    ): RedirectResponse {
        $worker = $this->currentWorker($request);
        $validated = $request->validated();

        if (
            (int) $validated['other_cost'] > 0
            && blank($validated['other_cost_note'] ?? null)
        ) {
            throw ValidationException::withMessages([
                'other_cost_note' => 'その他経費がある場合は内容を入力してください。',
            ]);
        }

        $workDate = today();

        $workerRate = WorkerRate::query()
            ->where('worker_id', $worker->id)
            ->whereDate('effective_from', '<=', $workDate)
            ->where(function ($query) use ($workDate): void {
                $query
                    ->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $workDate);
            })
            ->orderByDesc('effective_from')
            ->first();

        if (! $workerRate) {
            throw ValidationException::withMessages([
                'site_id' => '作業員単価が設定されていません。管理者へ確認してください。',
            ]);
        }

        $workShift = WorkShift::from($validated['work_shift']);
        $workRole = WorkRole::from($validated['work_role']);

        $costs = $calculator->calculate(
            dailyRate: $workerRate->daily_rate,
            laborUnits: (float) $validated['labor_units'],
            workShift: $workShift,
            overtimeHours: (float) $validated['overtime_hours'],
            highwayCost: (int) $validated['highway_cost'],
            parkingCost: (int) $validated['parking_cost'],
            otherCost: (int) $validated['other_cost'],
        );

        /** @var WorkReport $workReport */
        $workReport = DB::transaction(function () use (
            $worker,
            $validated,
            $workShift,
            $workRole,
            $costs,
            $workDate,
        ): WorkReport {
            $attendance = DailyAttendance::query()
                ->lockForUpdate()
                ->firstOrCreate(
                    [
                        'worker_id' => $worker->id,
                        'work_date' => $workDate->toDateString(),
                    ],
                    [
                        'status' => AttendanceStatus::Worked,
                        'submitted_by_user_id' => null,
                        'updated_by_user_id' => null,
                        'submitted_at' => now(),
                    ],
                );

            if ($attendance->status === AttendanceStatus::Off) {
                throw ValidationException::withMessages([
                    'attendance' => '本日は休みとして登録されています。',
                ]);
            }

            if ($attendance->workReports()->exists()) {
                throw ValidationException::withMessages([
                    'attendance' => '本日の出勤内容はすでに登録されています。',
                ]);
            }

            $attendance->update([
                'status' => AttendanceStatus::Worked,
                'submitted_at' => now(),
            ]);

            return $attendance->workReports()->create([
                'site_id' => $validated['site_id'],

                'labor_units' => $validated['labor_units'],
                'work_shift' => $workShift,
                'work_role' => $workRole,
                'overtime_hours' => $validated['overtime_hours'],

                'highway_cost' => $validated['highway_cost'],
                'parking_cost' => $validated['parking_cost'],
                'other_cost' => $validated['other_cost'],
                'other_cost_note' => $validated['other_cost_note'] ?? null,
                'notes' => $validated['notes'] ?? null,

                ...$costs,
            ]);
        });

        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo');
            $storedPath = null;

            try {
                $storedPath = $request
                    ->image('photo')
                    ->orient()
                    ->resize(width: 1600)
                    ->toWebp()
                    ->quality(80)
                    ->storePublicly(
                        'work-report-photos/'.$workDate->format('Y/m'),
                        'public',
                    );

                $workReport->photo()->create([
                    'file_path' => $storedPath,
                    'original_name' => $uploadedPhoto->getClientOriginalName(),
                    'mime_type' => Storage::disk('public')->mimeType($storedPath)
                        ?: 'image/webp',
                    'file_size' => Storage::disk('public')->size($storedPath),
                    'width' => null,
                    'height' => null,

                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null,
                    'location_accuracy' => $validated['location_accuracy'] ?? null,
                    'location_captured_at' => $validated['location_captured_at'] ?? null,

                    'uploaded_at' => now(),
                ]);
            } catch (Throwable $exception) {
                if ($storedPath !== null) {
                    Storage::disk('public')->delete($storedPath);
                }

                report($exception);

                return redirect()
                    ->route('worker.home')
                    ->withErrors([
                        'photo' => '出勤内容は登録されましたが、写真の保存に失敗しました。',
                    ]);
            }
        }

        return redirect()
            ->route('worker.home')
            ->with('success', '本日の出勤内容を登録しました。');
    }

    private function currentWorker(Request $request): Worker
    {
        return Worker::query()
            ->whereKey($request->session()->get('worker_id'))
            ->where('is_active', true)
            ->firstOrFail();
    }
}
