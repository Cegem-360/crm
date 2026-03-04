<?php

declare(strict_types=1);

namespace App\Filament\Resources\QualifiedOpportunities;

use App\Enums\NavigationGroup;
use App\Filament\Resources\QualifiedOpportunities\Pages\ManageQualifiedOpportunities;
use App\Filament\Resources\QualifiedOpportunities\Tables\QualifiedOpportunitiesTable;
use App\Models\Opportunity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class QualifiedOpportunitiesResource extends Resource
{
    protected static ?string $model = Opportunity::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static ?int $navigationSort = 2;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Qualified');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Qualified Opportunity');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Qualified Opportunities');
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return QualifiedOpportunitiesTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageQualifiedOpportunities::route('/'),
        ];
    }
}
