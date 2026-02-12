<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Override;
use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class ProductSalesChart extends ChartWidget
{
    protected ?string $heading = 'Product Sales Trend (Last 12 Months)';

    protected ?string $maxHeight = '300px';

    #[Override]
    protected function getData(): array
    {
        $data = OrderItem::query()
            ->select(
                DB::raw("strftime('%Y-%m', orders.order_date) as month"),
                DB::raw('SUM(order_items.total) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (HUF)',
                    'data' => $data->pluck('revenue')->toArray(),
                    'borderColor' => ChartColors::PRIMARY_BORDER,
                    'backgroundColor' => ChartColors::PRIMARY_FILL,
                    'fill' => true,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Quantity',
                    'data' => $data->pluck('quantity')->toArray(),
                    'borderColor' => ChartColors::SUCCESS_BORDER,
                    'backgroundColor' => ChartColors::SUCCESS_FILL,
                    'fill' => true,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
