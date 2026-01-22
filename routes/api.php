<?php

declare(strict_types=1);

use App\Http\Controllers\Api\SyncPasswordController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserSyncController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

// API V1 Routes
Route::prefix('v1')->name('api.v1.')->group(function (): void {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function (): void {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthController::class, 'me'])->name('me');

        // Customer routes
        Route::apiResource('customers', CustomerController::class);

        // Product routes
        Route::apiResource('products', ProductController::class);
    });
});

Route::middleware('api.key')->group(function (): void {
    Route::post('/create-user', [UserSyncController::class, 'create']);
    Route::post('/sync-user', [UserSyncController::class, 'sync']);
    Route::post('/create-team', [TeamController::class, 'create']);
    Route::post('/toggle-user-active', [UserSyncController::class, 'toggleActive']);
    Route::get('/user-teams', [TeamController::class, 'getUserTeams']);
    Route::post('/sync-password', SyncPasswordController::class);
});
