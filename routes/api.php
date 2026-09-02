<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GatewayController;
use App\Http\Controllers\DashboardApiController;
use App\Http\Controllers\ReportExportController;

Route::post('/gateway/receive', [GatewayController::class, 'receive']);
Route::post('/gateway/toggle-ddos', [GatewayController::class, 'toggleDdos']);

Route::prefix('/dashboard')->group(function () {
    Route::get('/status', [DashboardApiController::class, 'status']);
    Route::post('/toggle-server-state', [DashboardApiController::class, 'toggleServerState']);
    Route::get('/logs', [DashboardApiController::class, 'logs']);
    Route::get('/decisions', [DashboardApiController::class, 'decisions']);
    Route::post('/trigger-mock-event', [DashboardApiController::class, 'triggerMockEvent']);
    Route::post('/send-telegram-alert', [ReportExportController::class, 'sendTelegramAlert']);
});
