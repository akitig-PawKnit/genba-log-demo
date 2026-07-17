<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Worker;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('management.dashboard', [
            'activeWorkerCount' => Worker::query()
                ->where('is_active', true)
                ->count(),

            'activeSiteCount' => Site::query()
                ->where('is_active', true)
                ->count(),
        ]);
    }
}
