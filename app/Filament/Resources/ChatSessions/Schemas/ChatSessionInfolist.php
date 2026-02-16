<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChatSessions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ChatSessionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer'))
                    ->placeholder(__('-')),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('user.name')
                    ->label(__('Agent'))
                    ->placeholder(__('Unassigned')),
                TextEntry::make('priority')
                    ->badge()
                    ->placeholder(__('-')),
                TextEntry::make('started_at')
                    ->label(__('Started At'))
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('ended_at')
                    ->label(__('Ended At'))
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('last_message_at')
                    ->label(__('Last Message'))
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('unread_count')
                    ->label(__('Unread Messages'))
                    ->numeric()
                    ->placeholder('0'),
                TextEntry::make('rating')
                    ->label(__('Rating'))
                    ->formatStateUsing(fn ($state): string => $state ? $state.'/5' : '-')
                    ->placeholder(__('-')),
                TextEntry::make('notes')
                    ->label(__('Notes'))
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
            ]);
    }
}
