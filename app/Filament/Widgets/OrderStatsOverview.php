<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Override;

final class OrderStatsOverview extends BaseWidget
{
    /**
     * @return array<Stat>
     */
    #[Override]
    protected function getStats(): array
    {
        $totalOrders = Order::query()->count();
        $totalRevenue = Order::query()->sum('total');
        $averageOrderValue = Order::query()->avg('total') ?? 0;
        $totalDiscount = Order::query()->sum('discount_amount');

        return [
            Stat::make('Total Orders', Number::format($totalOrders))
                ->description('All orders')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Total Revenue', Number::currency($totalRevenue, 'HUF'))
                ->description('Gross revenue')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Average Order Value', Number::currency($averageOrderValue, 'HUF'))
                ->description('Per order average')
                ->icon('heroicon-o-calculator')
                ->color('warning'),

            Stat::make('Total Discounts', Number::currency($totalDiscount, 'HUF'))
                ->description('Discounts given')
                ->icon('heroicon-o-tag')
                ->color('danger'),
        ];
    }
}
