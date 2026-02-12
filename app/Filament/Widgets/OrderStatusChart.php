<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class OrderStatusChart extends ChartWidget
{
    /**
     * @var array<string, string>
     */
    private const array STATUS_COLORS = [
        'pending' => 'rgba(245, 158, 11, 0.8)',
        'confirmed' => 'rgba(59, 130, 246, 0.8)',
        'processing' => 'rgba(139, 92, 246, 0.8)',
        'shipped' => 'rgba(20, 184, 166, 0.8)',
        'delivered' => 'rgba(16, 185, 129, 0.8)',
        'cancelled' => 'rgba(239, 68, 68, 0.8)',
    ];

    protected ?string $heading = 'Orders by Status';

    protected ?string $maxHeight = '300px';

    #[Override]
    protected function getData(): array
    {
        $data = Order::query()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $labels = [];
        $values = [];
        $colors = [];

        foreach (OrderStatus::cases() as $status) {
            $labels[] = ucfirst($status->value);
            $values[] = $data->get($status->value)?->count ?? 0;
            $colors[] = self::STATUS_COLORS[$status->value] ?? 'rgba(156, 163, 175, 0.8)';
        }

        return [
            'datasets' => [
                [
                    'data' => $values,
                    'backgroundColor' => $colors,
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
