<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomFields;

use App\Enums\NavigationGroup;
use App\Filament\Resources\CustomFields\Pages\CreateCustomField;
use App\Filament\Resources\CustomFields\Pages\EditCustomField;
use App\Filament\Resources\CustomFields\Pages\ListCustomFields;
use App\Filament\Resources\CustomFields\Schemas\CustomFieldForm;
use App\Filament\Resources\CustomFields\Tables\CustomFieldsTable;
use App\Models\CustomField;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Override;
use UnitEnum;

final class CustomFieldResource extends Resource
{
    protected static ?string $model = CustomField::class;

    protected static bool $isScopedToTenant = false;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?int $navigationSort = 3;

    #[Override]
    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return CustomFieldForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return CustomFieldsTable::configure($table);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListCustomFields::route('/'),
            'create' => CreateCustomField::route('/create'),
            'edit' => EditCustomField::route('/{record}/edit'),
        ];
    }

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Custom Fields');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Custom Field');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Custom Fields');
    }
}
