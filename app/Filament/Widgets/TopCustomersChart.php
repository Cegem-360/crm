<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Override;
use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class TopCustomersChart extends ChartWidget
{
    protected ?string $heading = 'Top 10 Customers by Revenue';

    protected ?string $maxHeight = '300px';

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
                    'label' => 'Revenue (HUF)',
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
