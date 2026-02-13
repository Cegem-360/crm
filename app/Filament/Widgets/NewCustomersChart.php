<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ChartColors;
use App\Models\Customer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class NewCustomersChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('New Customers (Last 12 Months)');
    }

    #[Override]
    protected function getData(): array
    {
        $data = Customer::query()
            ->select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('New Customers'),
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => ChartColors::PRIMARY_BORDER,
                    'backgroundColor' => ChartColors::PRIMARY_FILL,
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
