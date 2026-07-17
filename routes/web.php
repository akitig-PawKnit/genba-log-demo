<?php

use App\Http\Controllers\Management\AuthController as ManagementAuthController;
use App\Http\Controllers\Management\DashboardController as ManagementDashboardController;
use App\Http\Controllers\Management\WorkReportController as ManagementWorkReportController;
use App\Http\Controllers\Worker\AuthController as WorkerAuthController;
use App\Http\Controllers\Worker\DailyAttendanceController;
use App\Http\Controllers\Worker\HomeController as WorkerHomeController;
use App\Http\Controllers\Worker\WorkReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('manage')
    ->name('management.')
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get(
                '/login',
                [ManagementAuthController::class, 'create']
            )->name('login');

            Route::post(
                '/login',
                [ManagementAuthController::class, 'store']
            )->name('login.store');
        });

        Route::middleware('auth')->group(function () {
            Route::get(
                '/',
                ManagementDashboardController::class
            )->name('dashboard');

            Route::get(
                '/work-reports/{workReport}',
                [ManagementWorkReportController::class, 'show']
            )->name('work-reports.show');

            Route::post(
                '/logout',
                [ManagementAuthController::class, 'destroy']
            )->name('logout');
        });
    });

Route::prefix('worker')
    ->name('worker.')
    ->group(function () {
        Route::get(
            '/login',
            [WorkerAuthController::class, 'create']
        )->name('login');

        Route::post(
            '/login',
            [WorkerAuthController::class, 'store']
        )->name('login.store');

        Route::middleware('worker.auth')->group(function () {
            Route::get(
                '/',
                WorkerHomeController::class
            )->name('home');

            Route::post(
                '/attendance/off',
                [DailyAttendanceController::class, 'storeOff']
            )->name('attendance.off');

            Route::delete(
                '/attendance/today',
                [DailyAttendanceController::class, 'destroyToday']
            )->name('attendance.destroy-today');

            Route::get(
                '/work-reports/create',
                [WorkReportController::class, 'create']
            )->name('work-reports.create');

            Route::post(
                '/work-reports',
                [WorkReportController::class, 'store']
            )->name('work-reports.store');

            Route::post(
                '/logout',
                [WorkerAuthController::class, 'destroy']
            )->name('logout');
        });
    });
