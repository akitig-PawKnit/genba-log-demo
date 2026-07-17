<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendance;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $worker = Worker::query()
            ->whereKey($request->session()->get('worker_id'))
            ->where('is_active', true)
            ->firstOrFail();

        $todayAttendance = DailyAttendance::query()
            ->with(['workReports.site'])
            ->where('worker_id', $worker->id)
            ->whereDate('work_date', today())
            ->first();

        return view('worker.home', [
            'worker' => $worker,
            'todayAttendance' => $todayAttendance,
        ]);
    }
}
