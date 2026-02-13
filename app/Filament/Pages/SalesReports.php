<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Widgets\MonthlySalesChart;
use App\Filament\Widgets\OpportunityValueChart;
use App\Filament\Widgets\QuoteStatusChart;
use App\Filament\Widgets\SalesFunnelChart;
use App\Filament\Widgets\SalesStatsOverview;
use Filament\Pages\Page;
use Override;
use UnitEnum;

final class SalesReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Sales Reports';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Sales Reports');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('Sales Reports');
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
            SalesStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    #[Override]
    protected function getFooterWidgets(): array
    {
        return [
            SalesFunnelChart::class,
            OpportunityValueChart::class,
            QuoteStatusChart::class,
            MonthlySalesChart::class,
        ];
    }
}
