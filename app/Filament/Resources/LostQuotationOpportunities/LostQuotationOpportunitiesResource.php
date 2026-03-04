<?php

declare(strict_types=1);

namespace App\Filament\Resources\LostQuotationOpportunities;

use App\Enums\NavigationGroup;
use App\Filament\Resources\LostQuotationOpportunities\Pages\ManageLostQuotationOpportunities;
use App\Filament\Resources\LostQuotationOpportunities\Tables\LostQuotationOpportunitiesTable;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class LostQuotationOpportunitiesResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-x-circle';

    protected static ?int $navigationSort = 7;

    protected static bool $shouldRegisterNavigation = false;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Lost Quotation');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Lost Quotation Opportunity');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Lost Quotation Opportunities');
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return LostQuotationOpportunitiesTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageLostQuotationOpportunities::route('/'),
        ];
    }
}
