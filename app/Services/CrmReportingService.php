<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\OpportunityStage;
use App\Enums\OrderStatus;
use App\Enums\QuoteStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use Illuminate\Support\Facades\Date;

final class CrmReportingService
{
    /**
     * @return array<string, mixed>
     */
    public function getKpis(): array
    {
        return [
            'customers' => [
                'total' => Customer::query()->count(),
                'active' => Customer::query()->where('is_active', true)->count(),
            ],
            'opportunities' => [
                'active' => Opportunity::query()->whereIn('stage', OpportunityStage::getActiveStages())->count(),
                'pipeline_value' => (float) Opportunity::query()->whereIn('stage', OpportunityStage::getActiveStages())->sum('value'),
                'won_value' => (float) Opportunity::query()->where('stage', OpportunityStage::SentQuotation)->sum('value'),
            ],
            'quotes' => [
                'total' => Quote::query()->count(),
                'accepted' => Quote::query()->where('status', QuoteStatus::Accepted)->count(),
                'conversion_rate' => $this->calculateQuoteConversionRate(),
            ],
            'orders' => [
                'total' => Order::query()->count(),
                'total_revenue' => (float) Order::query()->sum('total'),
                'average_value' => (float) Order::query()->avg('total'),
            ],
            'invoices' => [
                'total' => Invoice::query()->count(),
                'paid' => Invoice::query()->where('status', InvoiceStatus::Paid)->count(),
                'overdue' => Invoice::query()->where('status', InvoiceStatus::Overdue)->count(),
                'outstanding_amount' => (float) Invoice::query()
                    ->whereNotIn('status', [InvoiceStatus::Paid, InvoiceStatus::Cancelled])
                    ->sum('total'),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getPipelineSummary(): array
    {
        $stages = [];

        foreach (OpportunityStage::cases() as $stage) {
            $query = Opportunity::query()->where('stage', $stage);
            $stages[] = [
                'stage' => $stage->value,
                'label' => $stage->getLabel(),
                'count' => $query->count(),
                'value' => (float) $query->sum('value'),
                'avg_probability' => (float) $query->avg('probability'),
            ];
        }

        return [
            'stages' => $stages,
            'total_pipeline_value' => (float) Opportunity::query()->whereIn('stage', OpportunityStage::getActiveStages())->sum('value'),
            'weighted_value' => (float) Opportunity::query()
                ->whereIn('stage', OpportunityStage::getActiveStages())
                ->selectRaw('SUM(value * probability / 100) as weighted')
                ->value('weighted'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getRevenueForecast(int $months = 6): array
    {
        $forecast = [];
        $now = Date::now();

        for ($i = 0; $i < $months; $i++) {
            $month = $now->copy()->addMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $expectedRevenue = (float) Opportunity::query()
                ->whereIn('stage', OpportunityStage::getActiveStages())
                ->whereBetween('expected_close_date', [$startOfMonth, $endOfMonth])
                ->selectRaw('SUM(value * probability / 100) as weighted')
                ->value('weighted');

            $confirmedRevenue = (float) Order::query()
                ->whereIn('status', [OrderStatus::Confirmed, OrderStatus::Processing])
                ->whereBetween('order_date', [$startOfMonth, $endOfMonth])
                ->sum('total');

            $forecast[] = [
                'month' => $month->format('Y-m'),
                'label' => $month->translatedFormat('F Y'),
                'expected_revenue' => $expectedRevenue,
                'confirmed_revenue' => $confirmedRevenue,
                'total_forecast' => $expectedRevenue + $confirmedRevenue,
            ];
        }

        return [
            'forecast' => $forecast,
            'generated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getMonthlySalesTrend(int $months = 12): array
    {
        $trend = [];
        $now = Date::now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $trend[] = [
                'month' => $month->format('Y-m'),
                'label' => $month->translatedFormat('F Y'),
                'orders_count' => Order::query()->whereBetween('order_date', [$startOfMonth, $endOfMonth])->count(),
                'orders_revenue' => (float) Order::query()->whereBetween('order_date', [$startOfMonth, $endOfMonth])->sum('total'),
                'quotes_count' => Quote::query()->whereBetween('issue_date', [$startOfMonth, $endOfMonth])->count(),
                'invoices_total' => (float) Invoice::query()->whereBetween('issue_date', [$startOfMonth, $endOfMonth])->sum('total'),
            ];
        }

        return ['trend' => $trend];
    }

    private function calculateQuoteConversionRate(): float
    {
        $totalQuotes = Quote::query()->count();

        if ($totalQuotes === 0) {
            return 0.0;
        }

        $acceptedQuotes = Quote::query()->where('status', QuoteStatus::Accepted)->count();

        return round(($acceptedQuotes / $totalQuotes) * 100, 2);
    }
}
