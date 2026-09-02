<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CpsDashboardController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Primary HCD Real-Time Face Authentication Dashboard
    Route::get('/', [CpsDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [CpsDashboardController::class, 'index'])->name('cps.dashboard');

    // Usability Testing & SUS Evaluation Pages
    Route::get('/usability/sus', [CpsDashboardController::class, 'susForm'])->name('usability.sus');
    Route::get('/usability/results', [CpsDashboardController::class, 'usabilityResults'])->name('usability.results');
});
