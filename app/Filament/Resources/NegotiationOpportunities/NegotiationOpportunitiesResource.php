<?php

declare(strict_types=1);

namespace App\Filament\Resources\NegotiationOpportunities;

use App\Enums\NavigationGroup;
use App\Filament\Resources\NegotiationOpportunities\Pages\ManageNegotiationOpportunities;
use App\Filament\Resources\NegotiationOpportunities\Tables\NegotiationOpportunitiesTable;
use App\Models\Opportunity;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class NegotiationOpportunitiesResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static ?int $navigationSort = 13;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Negotiations');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Negotiation Opportunity');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Negotiation Opportunities');
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return NegotiationOpportunitiesTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageNegotiationOpportunities::route('/'),
        ];
    }
}
