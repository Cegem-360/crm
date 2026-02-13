<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class TopCustomersChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Top 10 Customers by Revenue');
    }

    #[Override]
    protected function getData(): array
    {
        $data = Order::query()
            ->select('customers.name', DB::raw('SUM(orders.total) as revenue'))
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Revenue (HUF)'),
                    'data' => $data->pluck('revenue')->toArray(),
                    'backgroundColor' => ChartColors::DEFAULT,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
