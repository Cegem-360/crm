<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupportTickets;

use App\Enums\NavigationGroup;
use App\Filament\Resources\SupportTickets\Pages\CreateSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\EditSupportTicket;
use App\Filament\Resources\SupportTickets\Pages\ListSupportTickets;
use App\Filament\Resources\SupportTickets\Pages\ViewSupportTicket;
use App\Filament\Resources\SupportTickets\Schemas\SupportTicketForm;
use App\Filament\Resources\SupportTickets\Schemas\SupportTicketInfolist;
use App\Filament\Resources\SupportTickets\Tables\SupportTicketsTable;
use App\Models\SupportTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Support;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Support Tickets');
    }

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Support Ticket');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Support Tickets');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return SupportTicketForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return SupportTicketInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return SupportTicketsTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSupportTickets::route('/'),
            'create' => CreateSupportTicket::route('/create'),
            'view' => ViewSupportTicket::route('/{record}'),
            'edit' => EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
