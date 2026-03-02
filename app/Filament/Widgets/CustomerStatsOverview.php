<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Override;

final class CustomerStatsOverview extends BaseWidget
{
    /**
     * @return array<Stat>
     */
    #[Override]
    protected function getStats(): array
    {
        $teamId = Filament::getTenant()?->getKey();

        $totalCustomers = Customer::query()->where('team_id', $teamId)->count();
        $activeCustomers = Customer::query()->where('team_id', $teamId)->where('is_active', true)->count();
        $customersWithOrders = Order::query()->where('team_id', $teamId)->distinct('customer_id')->count('customer_id');

        $avgCustomerValue = (float) (DB::query()
            ->select(DB::raw('AVG(customer_total) as avg_value'))
            ->fromSub(
                Order::query()
                    ->where('team_id', $teamId)
                    ->select('customer_id', DB::raw('SUM(total) as customer_total'))
                    ->groupBy('customer_id'),
                'customer_totals'
            )
            ->value('avg_value') ?? 0);

        return [
            Stat::make(__('Total Customers'), Number::format($totalCustomers))
                ->description(__('All customers'))
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make(__('Active Customers'), Number::format($activeCustomers))
                ->description(__('Currently active'))
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(__('Customers with Orders'), Number::format($customersWithOrders))
                ->description(__('Have placed orders'))
                ->icon('heroicon-o-shopping-bag')
                ->color('info'),

            Stat::make(__('Avg. Customer Value'), Number::currency($avgCustomerValue, 'HUF'))
                ->description(__('Average lifetime value'))
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
        ];
    }
}
