<?php

use App\Http\Controllers\Management\AuthController;
use App\Http\Controllers\Management\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('manage')
    ->name('management.')
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/login', [AuthController::class, 'create'])
                ->name('login');

            Route::post('/login', [AuthController::class, 'store'])
                ->name('login.store');
        });

        Route::middleware('auth')->group(function () {
            Route::get('/', DashboardController::class)
                ->name('dashboard');

            Route::post('/logout', [AuthController::class, 'destroy'])
                ->name('logout');
        });
    });
