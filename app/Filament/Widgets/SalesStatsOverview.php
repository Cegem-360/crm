<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OpportunityStage;
use App\Enums\QuoteStatus;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use Filament\Facades\Filament;
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
        $teamId = Filament::getTenant()?->getKey();

        $totalOpportunities = Opportunity::query()->where('team_id', $teamId)->count();
        $activeOpportunities = Opportunity::query()
            ->where('team_id', $teamId)
            ->whereIn('stage', OpportunityStage::getActiveStages())
            ->count();

        $pipelineValue = (float) Opportunity::query()
            ->where('team_id', $teamId)
            ->whereIn('stage', OpportunityStage::getActiveStages())
            ->sum('value');

        $wonDealsValue = (float) Order::query()->where('team_id', $teamId)->sum('total');

        $totalQuotes = Quote::query()->where('team_id', $teamId)->count();
        $acceptedQuotes = Quote::query()
            ->where('team_id', $teamId)
            ->where('status', QuoteStatus::Accepted)
            ->count();

        $conversionRate = $totalQuotes > 0
            ? round(($acceptedQuotes / $totalQuotes) * 100, 1)
            : 0;

        return [
            Stat::make(__('Active Opportunities'), Number::format($activeOpportunities))
                ->description($totalOpportunities.' '.__('total opportunities'))
                ->icon('heroicon-o-funnel')
                ->color('primary'),

            Stat::make(__('Pipeline Value'), Number::currency($pipelineValue, 'HUF'))
                ->description(__('Active opportunities value'))
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make(__('Won Deals Value'), Number::currency($wonDealsValue, 'HUF'))
                ->description(__('Total order value'))
                ->icon('heroicon-o-trophy')
                ->color('success'),

            Stat::make(__('Quote Conversion Rate'), $conversionRate.'%')
                ->description(sprintf(__('%d of %d quotes accepted'), $acceptedQuotes, $totalQuotes))
                ->icon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }
}
