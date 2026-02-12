<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OpportunityStage;
use App\Models\Opportunity;
use Filament\Support\RawJs;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Override;

final class SalesFunnelChart extends ApexChartWidget
{
    protected static ?string $chartId = 'salesFunnelChart';

    protected static ?string $heading = 'Sales Funnel';

    /**
     * @return array<string, mixed>
     */
    #[Override]
    protected function getOptions(): array
    {
        $stageCounts = Opportunity::query()
            ->selectRaw('stage, COUNT(*) as count')
            ->groupBy('stage')
            ->pluck('count', 'stage');

        $data = [];
        $categories = [];
        $colors = [
            'lead' => '#9ca3af',
            'qualified' => '#3b82f6',
            'proposal' => '#f59e0b',
            'negotiation' => '#8b5cf6',
        ];
        $chartColors = [];

        foreach (OpportunityStage::getActiveStages() as $stage) {
            $categories[] = $stage->getLabel();
            $data[] = (int) ($stageCounts->get($stage->value) ?? 0);
            $chartColors[] = $colors[$stage->value] ?? '#9ca3af';
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Opportunities',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 0,
                    'horizontal' => true,
                    'distributed' => true,
                    'barHeight' => '80%',
                    'isFunnel' => true,
                ],
            ],
            'colors' => $chartColors,
            'legend' => [
                'show' => false,
            ],
        ];
    }

    protected function extraJsOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            dataLabels: {
                enabled: true,
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ': ' + val
                },
                dropShadow: {
                    enabled: true
                },
            }
        }
        JS);
    }
}
