<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;
use Override;

final class ProductStatsOverview extends BaseWidget
{
    /**
     * @return array<Stat>
     */
    #[Override]
    protected function getStats(): array
    {
        $teamId = Filament::getTenant()?->getKey();

        $totalProducts = Product::query()->where('team_id', $teamId)->count();
        $activeProducts = Product::query()->where('team_id', $teamId)->where('is_active', true)->count();
        $averagePrice = (float) (Product::query()->where('team_id', $teamId)->where('is_active', true)->avg('unit_price') ?? 0);
        $totalSold = (float) OrderItem::query()
            ->whereHas('order', static fn (Builder $query): Builder => $query->where('team_id', $teamId))
            ->sum('quantity');

        return [
            Stat::make(__('Total Products'), Number::format($totalProducts))
                ->description(__('All products in catalog'))
                ->icon('heroicon-o-cube')
                ->color('primary'),

            Stat::make(__('Active Products'), Number::format($activeProducts))
                ->description(__('Available for sale'))
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(__('Average Price'), Number::currency($averagePrice, 'HUF'))
                ->description(__('Active product average'))
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make(__('Total Sold'), Number::format($totalSold))
                ->description(__('Total items sold'))
                ->icon('heroicon-o-shopping-cart')
                ->color('info'),
        ];
    }
}
