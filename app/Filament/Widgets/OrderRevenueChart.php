<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class OrderRevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Trend (Last 12 Months)';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Order::query()
            ->select(
                DB::raw("strftime('%Y-%m', order_date) as month"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('SUM(discount_amount) as discounts')
            )
            ->where('order_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (HUF)',
                    'data' => $data->pluck('revenue')->toArray(),
                    'borderColor' => ChartColors::SUCCESS_BORDER,
                    'backgroundColor' => ChartColors::SUCCESS_FILL,
                    'fill' => true,
                ],
                [
                    'label' => 'Discounts (HUF)',
                    'data' => $data->pluck('discounts')->toArray(),
                    'borderColor' => ChartColors::DANGER_BORDER,
                    'backgroundColor' => ChartColors::DANGER_FILL,
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
