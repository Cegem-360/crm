<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CrmReportingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CrmReportingController extends Controller
{
    public function __construct(private readonly CrmReportingService $reportingService) {}

    public function kpis(): JsonResponse
    {
        return response()->json([
            'data' => $this->reportingService->getKpis(),
        ]);
    }

    public function pipelineSummary(): JsonResponse
    {
        return response()->json([
            'data' => $this->reportingService->getPipelineSummary(),
        ]);
    }

    public function revenueForecast(Request $request): JsonResponse
    {
        $months = $request->integer('months', 6);

        return response()->json([
            'data' => $this->reportingService->getRevenueForecast($months),
        ]);
    }

    public function salesTrend(Request $request): JsonResponse
    {
        $months = $request->integer('months', 12);

        return response()->json([
            'data' => $this->reportingService->getMonthlySalesTrend($months),
        ]);
    }
}
