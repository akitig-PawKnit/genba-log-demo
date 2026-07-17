<?php

namespace App\Http\Controllers\Management;

use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use App\Models\Site;
use App\Models\Worker;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $workers = Worker::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        $todayAttendances = DailyAttendance::query()
            ->with([
                'workReports.site',
                'workReports.photo',
            ])
            ->whereDate('work_date', today())
            ->get()
            ->keyBy('worker_id');

        $todayRows = $workers->map(function (Worker $worker) use (
            $todayAttendances
        ): array {
            $attendance = $todayAttendances->get($worker->id);

            if (! $attendance) {
                return [
                    'worker' => $worker,
                    'attendance' => null,
                    'report' => null,
                    'status' => 'missing',
                    'status_label' => '未入力',
                ];
            }

            if ($attendance->status === AttendanceStatus::Off) {
                return [
                    'worker' => $worker,
                    'attendance' => $attendance,
                    'report' => null,
                    'status' => 'off',
                    'status_label' => '休み',
                ];
            }

            $report = $attendance->workReports->first();

            return [
                'worker' => $worker,
                'attendance' => $attendance,
                'report' => $report,
                'status' => 'worked',
                'status_label' => '出勤済み',
            ];
        });

        return view('management.dashboard', [
            'activeWorkerCount' => $workers->count(),

            'activeSiteCount' => Site::query()
                ->where('is_active', true)
                ->count(),

            'workedCount' => $todayRows
                ->where('status', 'worked')
                ->count(),

            'offCount' => $todayRows
                ->where('status', 'off')
                ->count(),

            'missingCount' => $todayRows
                ->where('status', 'missing')
                ->count(),

            'todayRows' => $todayRows,
        ]);
    }
}
