<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class OrderCountChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    #[Override]
    public function getHeading(): string
    {
        return __('Orders Count (Last 12 Months)');
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
                DB::raw('COUNT(*) as count')
            )
            ->where('order_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Orders'),
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
