<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\OrderItem;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class TopProductsChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    #[Override]
    public function getHeading(): string
    {
        return __('Top 10 Products by Quantity');
    }

    #[Override]
    protected function getData(): array
    {
        $teamId = Filament::getTenant()?->getKey();

        $data = OrderItem::query()
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.team_id', $teamId)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Quantity Sold'),
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
