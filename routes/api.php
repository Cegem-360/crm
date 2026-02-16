<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CrmReportingController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\OpportunityController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\QuoteController;
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

        // CRM Entity routes
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('opportunities', OpportunityController::class)->only(['index', 'show', 'destroy']);
        Route::apiResource('quotes', QuoteController::class)->only(['index', 'show', 'destroy']);
        Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::apiResource('invoices', InvoiceController::class)->only(['index', 'show', 'destroy']);

        // CRM Reporting / KPI endpoints (Kontrolling integration)
        Route::prefix('crm')->name('crm.')->group(function (): void {
            Route::get('/kpis', [CrmReportingController::class, 'kpis'])->name('kpis');
            Route::get('/pipeline-summary', [CrmReportingController::class, 'pipelineSummary'])->name('pipeline-summary');
            Route::get('/revenue-forecast', [CrmReportingController::class, 'revenueForecast'])->name('revenue-forecast');
            Route::get('/sales-trend', [CrmReportingController::class, 'salesTrend'])->name('sales-trend');
        });
    });
});
