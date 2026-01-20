<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Widgets\OrderCountChart;
use App\Filament\Widgets\OrderRevenueChart;
use App\Filament\Widgets\OrderStatsOverview;
use App\Filament\Widgets\OrderStatusChart;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

final class OrderReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Order Reports';

    public static function getNavigationLabel(): string
    {
        return 'Order Reports';
    }

    public function getTitle(): string|Htmlable
    {
        return self::$title ?? 'Order Reports';
    }

    /**
     * @return int|array<string, int>
     */
    public function getFooterWidgetsColumns(): int|array
    {
        return 2;
    }

    /**
     * @return array<class-string>
     */
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getFooterWidgets(): array
    {
        return [
            OrderStatusChart::class,
            OrderCountChart::class,
            OrderRevenueChart::class,
        ];
    }
}
