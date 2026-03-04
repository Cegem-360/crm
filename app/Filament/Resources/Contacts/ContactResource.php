<?php

declare(strict_types=1);

namespace App\Filament\Resources\Contacts;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Contacts\Pages\CreateContact;
use App\Filament\Resources\Contacts\Pages\EditContact;
use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Filament\Resources\Contacts\Pages\ViewContact;
use App\Filament\Resources\Contacts\Schemas\ContactForm;
use App\Filament\Resources\Contacts\Schemas\ContactInfolist;
use App\Filament\Resources\Contacts\Tables\ContactsTable;
use App\Models\CustomerContact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class ContactResource extends Resource
{
    protected static ?string $model = CustomerContact::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Customers;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 2;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Contacts');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Contact');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Contacts');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return ContactInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return ContactsTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListContacts::route('/'),
            'create' => CreateContact::route('/create'),
            'view' => ViewContact::route('/{record}'),
            'edit' => EditContact::route('/{record}/edit'),
        ];
    }
}
