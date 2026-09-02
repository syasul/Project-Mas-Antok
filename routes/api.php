<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VerificationApiController;
use App\Http\Controllers\UsabilityController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\DashboardApiController;
use App\Http\Controllers\ReportExportController;

/*
|--------------------------------------------------------------------------
| CPS Face Verification & Real-Time WebSocket/SSE Ingestion Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/verifications')->group(function () {
    Route::post('/', [VerificationApiController::class, 'receive']);
    Route::get('/', [VerificationApiController::class, 'index']);
    Route::get('/latest', [VerificationApiController::class, 'latest']);
    Route::get('/stats', [VerificationApiController::class, 'stats']);
    Route::get('/stream', [VerificationApiController::class, 'stream']);
    Route::post('/simulate', [VerificationApiController::class, 'simulate']);
    Route::post('/{id}/manual-action', [VerificationApiController::class, 'manualAction']);
});

/*
|--------------------------------------------------------------------------
| Usability Testing & SUS Evaluation API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/usability')->group(function () {
    Route::post('/session/start', [UsabilityController::class, 'startSession']);
    Route::post('/session/finish', [UsabilityController::class, 'finishSession']);
    Route::post('/sus/submit', [UsabilityController::class, 'submitSus']);
    Route::get('/stats', [UsabilityController::class, 'getStats']);
});

/*
|--------------------------------------------------------------------------
| Command Center & Security Gateway Legacy Routes
|--------------------------------------------------------------------------
*/
Route::post('/gateway/receive', [GatewayController::class, 'receive']);
Route::post('/gateway/toggle-ddos', [GatewayController::class, 'toggleDdos']);

Route::prefix('/dashboard')->group(function () {
    Route::get('/status', [DashboardApiController::class, 'status']);
    Route::get('/stream', [DashboardApiController::class, 'stream']);
    Route::post('/toggle-server-state', [DashboardApiController::class, 'toggleServerState']);
    Route::get('/logs', [DashboardApiController::class, 'logs']);
    Route::get('/decisions', [DashboardApiController::class, 'decisions']);
    Route::post('/trigger-mock-event', [DashboardApiController::class, 'triggerMockEvent']);
    Route::post('/send-telegram-alert', [ReportExportController::class, 'sendTelegramAlert']);
});
