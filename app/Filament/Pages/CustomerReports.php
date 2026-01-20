<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Widgets\CustomerStatsOverview;
use App\Filament\Widgets\CustomerTypeChart;
use App\Filament\Widgets\NewCustomersChart;
use App\Filament\Widgets\TopCustomersChart;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

final class CustomerReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Customer Reports';

    public static function getNavigationLabel(): string
    {
        return 'Customer Reports';
    }

    public function getTitle(): string|Htmlable
    {
        return self::$title ?? 'Customer Reports';
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
            CustomerStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getFooterWidgets(): array
    {
        return [
            TopCustomersChart::class,
            CustomerTypeChart::class,
            NewCustomersChart::class,
        ];
    }
}
