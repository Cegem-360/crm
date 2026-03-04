<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities;

use App\Enums\NavigationGroup;
use App\Filament\Resources\LeadOpportunities\Pages\CreateLeadOpportunity;
use App\Filament\Resources\LeadOpportunities\Pages\EditLeadOpportunity;
use App\Filament\Resources\LeadOpportunities\Pages\ListLeadOpportunities;
use App\Filament\Resources\LeadOpportunities\Pages\ViewLeadOpportunity;
use App\Filament\Resources\LeadOpportunities\RelationManagers\QuotesRelationManager;
use App\Filament\Resources\LeadOpportunities\Schemas\LeadOpportunityForm;
use App\Filament\Resources\LeadOpportunities\Schemas\OpportunityInfolist;
use App\Filament\Resources\LeadOpportunities\Tables\LeadOpportunitiesTable;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class LeadOpportunitiesResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Opportunities');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Opportunity');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Opportunities');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return LeadOpportunityForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return OpportunityInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return LeadOpportunitiesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            QuotesRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListLeadOpportunities::route('/'),
            'create' => CreateLeadOpportunity::route('/create'),
            'view' => ViewLeadOpportunity::route('/{record}'),
            'edit' => EditLeadOpportunity::route('/{record}/edit'),
        ];
    }
}
