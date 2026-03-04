<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailTemplates;

use App\Enums\NavigationGroup;
use App\Filament\Resources\EmailTemplates\Pages\CreateEmailTemplate;
use App\Filament\Resources\EmailTemplates\Pages\EditEmailTemplate;
use App\Filament\Resources\EmailTemplates\Pages\ListEmailTemplates;
use App\Filament\Resources\EmailTemplates\Schemas\EmailTemplateForm;
use App\Filament\Resources\EmailTemplates\Tables\EmailTemplatesTable;
use App\Models\EmailTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Activities;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Email Templates');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Email Template');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Email Templates');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return EmailTemplateForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return EmailTemplatesTable::configure($table);
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
            'index' => ListEmailTemplates::route('/'),
            'create' => CreateEmailTemplate::route('/create'),
            'edit' => EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
