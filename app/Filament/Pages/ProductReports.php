<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Widgets\ProductCategoryChart;
use App\Filament\Widgets\ProductSalesChart;
use App\Filament\Widgets\ProductStatsOverview;
use App\Filament\Widgets\TopProductsChart;
use Filament\Pages\Page;
use Override;
use UnitEnum;

final class ProductReports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Reports;

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Product Reports';

    #[Override]
    public static function getNavigationLabel(): string
    {
        return 'Product Reports';
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
            ProductStatsOverview::class,
        ];
    }

    /**
     * @return array<class-string>
     */
    #[Override]
    protected function getFooterWidgets(): array
    {
        return [
            TopProductsChart::class,
            ProductCategoryChart::class,
            ProductSalesChart::class,
        ];
    }
}
