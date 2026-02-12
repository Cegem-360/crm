<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OpportunityStage;
use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class OpportunityValueChart extends ChartWidget
{
    /**
     * @var array<string, string>
     */
    private const array STAGE_COLORS = [
        'lead' => 'rgba(156, 163, 175, 0.8)',
        'qualified' => 'rgba(59, 130, 246, 0.8)',
        'proposal' => 'rgba(245, 158, 11, 0.8)',
        'negotiation' => 'rgba(139, 92, 246, 0.8)',
        'sended_quotation' => 'rgba(16, 185, 129, 0.8)',
        'lost_quotation' => 'rgba(239, 68, 68, 0.8)',
    ];

    protected ?string $heading = 'Pipeline Value by Stage';

    protected ?string $maxHeight = '300px';

    #[Override]
    protected function getData(): array
    {
        $data = Opportunity::query()
            ->select('stage', DB::raw('SUM(value) as total_value'))
            ->groupBy('stage')
            ->get()
            ->keyBy('stage');

        $labels = [];
        $values = [];
        $colors = [];

        foreach (OpportunityStage::cases() as $stage) {
            $value = (float) ($data->get($stage->value)?->total_value ?? 0);
            if ($value > 0) {
                $labels[] = $stage->getLabel();
                $values[] = $value;
                $colors[] = self::STAGE_COLORS[$stage->value] ?? 'rgba(156, 163, 175, 0.8)';
            }
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
