<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuotationSendedOpportunities;

use App\Enums\NavigationGroup;
use App\Filament\Resources\QuotationSendedOpportunities\Pages\ManageQuotationSendedOpportunities;
use App\Filament\Resources\QuotationSendedOpportunities\Tables\QuotationSendedOpportunitiesTable;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class QuotationSendedOpportunitiesResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?int $navigationSort = 5;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Quotation Sent');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Quotation Sent Opportunity');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Quotation Sent Opportunities');
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return QuotationSendedOpportunitiesTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageQuotationSendedOpportunities::route('/'),
        ];
    }
}
