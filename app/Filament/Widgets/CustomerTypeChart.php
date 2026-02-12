<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\CustomerType;
use App\Models\Customer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Override;

final class CustomerTypeChart extends ChartWidget
{
    /**
     * @var array<string, string>
     */
    private const array TYPE_COLORS = [
        'individual' => 'rgba(59, 130, 246, 0.8)',
        'company' => 'rgba(16, 185, 129, 0.8)',
    ];

    protected ?string $heading = 'Customers by Type';

    protected ?string $maxHeight = '300px';

    #[Override]
    protected function getData(): array
    {
        $data = Customer::query()
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $labels = [];
        $values = [];
        $colors = [];

        foreach (CustomerType::cases() as $type) {
            $labels[] = ucfirst($type->value);
            $values[] = $data->get($type->value)?->count ?? 0;
            $colors[] = self::TYPE_COLORS[$type->value] ?? 'rgba(156, 163, 175, 0.8)';
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
        return 'pie';
    }
}
