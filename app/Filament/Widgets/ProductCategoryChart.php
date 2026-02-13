<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class ProductCategoryChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Products by Category');
    }

    #[Override]
    protected function getData(): array
    {
        $data = Product::query()
            ->select('product_categories.name', DB::raw('COUNT(products.id) as count'))
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->groupBy('product_categories.id', 'product_categories.name')
            ->orderByDesc('count')
            ->get();

        $labels = $data->pluck('name')->map(fn ($name) => $name ?? __('Uncategorized'))->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => array_slice(ChartColors::DEFAULT, 0, 8),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
