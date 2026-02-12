<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OpportunityStage;
use App\Enums\QuoteStatus;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Override;

final class SalesStatsOverview extends BaseWidget
{
    /**
     * @return array<Stat>
     */
    #[Override]
    protected function getStats(): array
    {
        $totalOpportunities = Opportunity::query()->count();
        $activeOpportunities = Opportunity::query()
            ->whereIn('stage', OpportunityStage::getActiveStages())
            ->count();

        $pipelineValue = Opportunity::query()
            ->whereIn('stage', OpportunityStage::getActiveStages())
            ->sum('value');

        $wonDealsValue = Order::query()->sum('total');

        $totalQuotes = Quote::query()->count();
        $acceptedQuotes = Quote::query()
            ->where('status', QuoteStatus::Accepted)
            ->count();

        $conversionRate = $totalQuotes > 0
            ? round(($acceptedQuotes / $totalQuotes) * 100, 1)
            : 0;

        return [
            Stat::make('Active Opportunities', Number::format($activeOpportunities))
                ->description($totalOpportunities.' total opportunities')
                ->icon('heroicon-o-funnel')
                ->color('primary'),

            Stat::make('Pipeline Value', Number::currency($pipelineValue, 'HUF'))
                ->description('Active opportunities value')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make('Won Deals Value', Number::currency($wonDealsValue, 'HUF'))
                ->description('Total order value')
                ->icon('heroicon-o-trophy')
                ->color('success'),

            Stat::make('Quote Conversion Rate', $conversionRate.'%')
                ->description(sprintf('%d of %d quotes accepted', $acceptedQuotes, $totalQuotes))
                ->icon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }
}
