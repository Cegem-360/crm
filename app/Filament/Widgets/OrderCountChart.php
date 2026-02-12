<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Override;
use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class OrderCountChart extends ChartWidget
{
    protected ?string $heading = 'Orders Count (Last 12 Months)';

    protected ?string $maxHeight = '300px';

    #[Override]
    protected function getData(): array
    {
        $data = Order::query()
            ->select(
                DB::raw("strftime('%Y-%m', order_date) as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('order_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => ChartColors::PRIMARY,
                    'borderColor' => ChartColors::PRIMARY_BORDER,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
