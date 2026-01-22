<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Quote;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class MonthlySalesChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Sales Trend';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i));
        }

        $orderData = Order::query()
            ->select(
                DB::raw('YEAR(order_date) as year'),
                DB::raw('MONTH(order_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('order_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item) => $item->year.'-'.$item->month);

        $quoteData = Quote::query()
            ->select(
                DB::raw('YEAR(issue_date) as year'),
                DB::raw('MONTH(issue_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('issue_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(fn ($item) => $item->year.'-'.$item->month);

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
                    'label' => 'Orders',
                    'data' => $orderValues,
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Quotes',
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
