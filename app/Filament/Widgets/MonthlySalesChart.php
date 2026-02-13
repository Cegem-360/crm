<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Quote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Override;

final class MonthlySalesChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Monthly Sales Trend');
    }

    #[Override]
    protected function getData(): array
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Date::now()->subMonths($i));
        }

        $driver = DB::connection()->getDriverName();
        $orderYearExpression = $driver === 'sqlite'
            ? "CAST(strftime('%Y', order_date) AS INTEGER)"
            : 'YEAR(order_date)';
        $orderMonthExpression = $driver === 'sqlite'
            ? "CAST(strftime('%m', order_date) AS INTEGER)"
            : 'MONTH(order_date)';
        $quoteYearExpression = $driver === 'sqlite'
            ? "CAST(strftime('%Y', issue_date) AS INTEGER)"
            : 'YEAR(issue_date)';
        $quoteMonthExpression = $driver === 'sqlite'
            ? "CAST(strftime('%m', issue_date) AS INTEGER)"
            : 'MONTH(issue_date)';

        $orderData = Order::query()
            ->select(
                DB::raw($orderYearExpression.' as year'),
                DB::raw($orderMonthExpression.' as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('order_date', '>=', Date::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item): string => $item->year.'-'.$item->month);

        $quoteData = Quote::query()
            ->select(
                DB::raw($quoteYearExpression.' as year'),
                DB::raw($quoteMonthExpression.' as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('issue_date', '>=', Date::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item): string => $item->year.'-'.$item->month);

        $labels = [];
        $orderValues = [];
        $quoteValues = [];

        foreach ($months as $month) {
            $key = $month->year.'-'.$month->month;
            $labels[] = $month->format('M Y');
            $orderValues[] = (float) ($orderData->get($key)?->total ?? 0);
            $quoteValues[] = (float) ($quoteData->get($key)?->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => __('Orders'),
                    'data' => $orderValues,
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => __('Quotes'),
                    'data' => $quoteValues,
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
