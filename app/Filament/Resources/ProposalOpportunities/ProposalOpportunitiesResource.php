<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProposalOpportunities;

use App\Enums\NavigationGroup;
use App\Filament\Resources\ProposalOpportunities\Pages\ManageProposalOpportunities;
use App\Filament\Resources\ProposalOpportunities\Tables\ProposalOpportunitiesTable;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class ProposalOpportunitiesResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Proposals');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Proposal Opportunity');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Proposal Opportunities');
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return ProposalOpportunitiesTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageProposalOpportunities::route('/'),
        ];
    }
}
