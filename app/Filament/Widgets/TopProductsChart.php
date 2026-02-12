<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Override;
use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top 10 Products by Quantity';

    protected ?string $maxHeight = '300px';

    #[Override]
    protected function getData(): array
    {
        $data = OrderItem::query()
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Quantity Sold',
                    'data' => $data->pluck('total_quantity')->toArray(),
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
