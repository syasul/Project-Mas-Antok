<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportExportController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    // Security Report & Telemetry Export Routes
    Route::get('/reports/incident-pdf', [ReportExportController::class, 'exportPdf'])->name('reports.incident-pdf');
    Route::get('/reports/sensors-csv', [ReportExportController::class, 'exportSensorCsv'])->name('reports.sensors-csv');
    Route::get('/reports/decisions-csv', [ReportExportController::class, 'exportDecisionCsv'])->name('reports.decisions-csv');
});
