<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupportTickets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class SupportTicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Ticket Details'))
                    ->columns(2)
                    ->components([
                        TextEntry::make('ticket_number')
                            ->copyable(),
                        TextEntry::make('subject')
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        TextEntry::make('category')
                            ->badge(),
                        TextEntry::make('priority')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('user.name'),
                        TextEntry::make('assignedUser.name'),
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('resolved_at')
                            ->dateTime(),
                        TextEntry::make('closed_at')
                            ->dateTime(),
                    ]),
            ]);
    }
}
