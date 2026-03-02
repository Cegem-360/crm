<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class OrderRevenueChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    #[Override]
    public function getHeading(): string
    {
        return __('Revenue Trend (Last 12 Months)');
    }

    #[Override]
    protected function getData(): array
    {
        $teamId = Filament::getTenant()?->getKey();

        $monthExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', order_date)"
            : "DATE_FORMAT(order_date, '%Y-%m')";

        $data = Order::query()
            ->where('team_id', $teamId)
            ->select(
                DB::raw($monthExpression.' as month'),
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
                    'label' => __('Revenue (HUF)'),
                    'data' => $data->pluck('revenue')->toArray(),
                    'borderColor' => ChartColors::SUCCESS_BORDER,
                    'backgroundColor' => ChartColors::SUCCESS_FILL,
                    'fill' => true,
                ],
                [
                    'label' => __('Discounts (HUF)'),
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
