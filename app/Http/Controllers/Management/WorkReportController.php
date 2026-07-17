<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\WorkReport;
use Illuminate\View\View;

class WorkReportController extends Controller
{
    public function show(WorkReport $workReport): View
    {
        $workReport->load([
            'dailyAttendance.worker',
            'site',
            'photo',
        ]);

        return view('management.work-reports.show', [
            'workReport' => $workReport,
        ]);
    }
}
