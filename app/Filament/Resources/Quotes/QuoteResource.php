<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes;

use Override;
use App\Enums\NavigationGroup;
use App\Filament\Resources\Quotes\Pages\CreateQuote;
use App\Filament\Resources\Quotes\Pages\EditQuote;
use App\Filament\Resources\Quotes\Pages\ListQuotes;
use App\Filament\Resources\Quotes\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\Quotes\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\Quotes\Schemas\QuoteForm;
use App\Filament\Resources\Quotes\Tables\QuotesTable;
use App\Models\Quote;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

final class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return QuoteForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return QuotesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            OrdersRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListQuotes::route('/'),
            'create' => CreateQuote::route('/create'),
            'edit' => EditQuote::route('/{record}/edit'),
        ];
    }

    #[Override]
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
