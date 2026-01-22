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
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

final class SalesReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Sales Reports';

    public static function getNavigationLabel(): string
    {
        return 'Sales Reports';
    }

    public function getTitle(): string|Htmlable
    {
        return self::$title;
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
            SalesStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
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
