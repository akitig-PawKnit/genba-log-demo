<?php

namespace App\Http\Controllers\Worker;

use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyAttendanceController extends Controller
{
    public function storeOff(Request $request): RedirectResponse
    {
        $worker = Worker::query()
            ->whereKey($request->session()->get('worker_id'))
            ->where('is_active', true)
            ->firstOrFail();

        DB::transaction(function () use ($worker): void {
            $attendance = DailyAttendance::query()
                ->firstOrNew([
                    'worker_id' => $worker->id,
                    'work_date' => today()->toDateString(),
                ]);

            /*
             * すでに出勤明細がある場合は、
             * 誤って「休み」に変更できないようにする。
             */
            if (
                $attendance->exists
                && $attendance->workReports()->exists()
            ) {
                abort(422, 'すでに出勤内容が登録されています。');
            }

            $attendance->fill([
                'status' => AttendanceStatus::Off,
                'submitted_by_user_id' => null,
                'updated_by_user_id' => null,
                'submitted_at' => now(),
            ]);

            $attendance->save();
        });

        return redirect()
            ->route('worker.home')
            ->with('success', '本日は休みとして登録しました。');
    }

    public function destroyToday(Request $request): RedirectResponse
    {
        $worker = Worker::query()
            ->whereKey($request->session()->get('worker_id'))
            ->where('is_active', true)
            ->firstOrFail();

        $attendance = DailyAttendance::query()
            ->where('worker_id', $worker->id)
            ->whereDate('work_date', today())
            ->firstOrFail();

        /*
         * 作業員本人による修正は当日のみ。
         * 出勤明細がある場合は、後で専用編集処理を作る。
         */
        if ($attendance->workReports()->exists()) {
            return back()->withErrors([
                'attendance' => '出勤内容の修正画面から変更してください。',
            ]);
        }

        $attendance->delete();

        return redirect()
            ->route('worker.home')
            ->with('success', '本日の登録を取り消しました。');
    }
}
