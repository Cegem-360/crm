<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
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
        $totalProducts = Product::query()->count();
        $activeProducts = Product::query()->where('is_active', true)->count();
        $averagePrice = Product::query()->where('is_active', true)->avg('unit_price') ?? 0;
        $totalSold = OrderItem::query()->sum('quantity');

        return [
            Stat::make('Total Products', Number::format($totalProducts))
                ->description('All products in catalog')
                ->icon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Active Products', Number::format($activeProducts))
                ->description('Available for sale')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Average Price', Number::currency($averagePrice, 'HUF'))
                ->description('Active product average')
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),

            Stat::make('Total Sold', Number::format($totalSold))
                ->description('Total items sold')
                ->icon('heroicon-o-shopping-cart')
                ->color('info'),
        ];
    }
}
