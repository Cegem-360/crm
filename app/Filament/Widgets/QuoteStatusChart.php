<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\QuoteStatus;
use App\Models\Quote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class QuoteStatusChart extends ChartWidget
{
    /**
     * @var array<string, string>
     */
    private const STATUS_COLORS = [
        'draft' => 'rgba(156, 163, 175, 0.8)',
        'sent' => 'rgba(59, 130, 246, 0.8)',
        'accepted' => 'rgba(16, 185, 129, 0.8)',
        'rejected' => 'rgba(239, 68, 68, 0.8)',
        'expired' => 'rgba(245, 158, 11, 0.8)',
    ];

    protected ?string $heading = 'Quotes by Status';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Quote::query()
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total_value'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $labels = [];
        $counts = [];
        $colors = [];

        foreach (QuoteStatus::cases() as $status) {
            $labels[] = ucfirst($status->value);
            $counts[] = (int) ($data->get($status->value)?->count ?? 0);
            $colors[] = self::STATUS_COLORS[$status->value] ?? 'rgba(156, 163, 175, 0.8)';
        }

        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
