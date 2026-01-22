<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OpportunityStage;
use App\Models\Opportunity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

final class SalesFunnelChart extends ChartWidget
{
    /**
     * @var array<string, string>
     */
    private const STAGE_COLORS = [
        'lead' => 'rgba(156, 163, 175, 0.8)',
        'qualified' => 'rgba(59, 130, 246, 0.8)',
        'proposal' => 'rgba(245, 158, 11, 0.8)',
        'negotiation' => 'rgba(139, 92, 246, 0.8)',
        'sended_quotation' => 'rgba(16, 185, 129, 0.8)',
        'lost_quotation' => 'rgba(239, 68, 68, 0.8)',
    ];

    protected ?string $heading = 'Sales Funnel';

    protected ?string $maxHeight = '350px';

    protected function getData(): array
    {
        $data = Opportunity::query()
            ->select('stage', DB::raw('COUNT(*) as count'), DB::raw('SUM(value) as total_value'))
            ->groupBy('stage')
            ->get()
            ->keyBy('stage');

        $labels = [];
        $counts = [];
        $values = [];
        $colors = [];

        // Order stages in funnel order (top to bottom)
        $funnelOrder = [
            OpportunityStage::Lead,
            OpportunityStage::Qualified,
            OpportunityStage::Proposal,
            OpportunityStage::Negotiation,
            OpportunityStage::SendedQuotation,
            OpportunityStage::LostQuotation,
        ];

        foreach ($funnelOrder as $stage) {
            $labels[] = $stage->getLabel();
            $counts[] = (int) ($data->get($stage->value)?->count ?? 0);
            $values[] = (float) ($data->get($stage->value)?->total_value ?? 0);
            $colors[] = self::STAGE_COLORS[$stage->value] ?? 'rgba(156, 163, 175, 0.8)';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Opportunities',
                    'data' => $counts,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
