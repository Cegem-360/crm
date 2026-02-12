<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Widgets\OrderCountChart;
use App\Filament\Widgets\OrderRevenueChart;
use App\Filament\Widgets\OrderStatsOverview;
use App\Filament\Widgets\OrderStatusChart;
use Filament\Pages\Page;
use Override;
use UnitEnum;

final class OrderReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Order Reports';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return 'Order Reports';
    }

    #[Override]
    public function getTitle(): string
    {
        return self::$title;
    }

    #[Override]
    public function getFooterWidgetsColumns(): int
    {
        return 2;
    }

    /**
     * @return array<class-string>
     */
    #[Override]
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    #[Override]
    protected function getFooterWidgets(): array
    {
        return [
            OrderStatusChart::class,
            OrderCountChart::class,
            OrderRevenueChart::class,
        ];
    }
}
