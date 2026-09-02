<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VerificationApiController;
use App\Http\Controllers\UsabilityController;

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
