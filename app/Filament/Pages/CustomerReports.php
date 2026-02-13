<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Widgets\CustomerStatsOverview;
use App\Filament\Widgets\CustomerTypeChart;
use App\Filament\Widgets\NewCustomersChart;
use App\Filament\Widgets\TopCustomersChart;
use Filament\Pages\Page;
use Override;
use UnitEnum;

final class CustomerReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Customer Reports';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Customer Reports');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('Customer Reports');
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
            CustomerStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    #[Override]
    protected function getFooterWidgets(): array
    {
        return [
            TopCustomersChart::class,
            CustomerTypeChart::class,
            NewCustomersChart::class,
        ];
    }
}
