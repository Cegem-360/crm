<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChatMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ChatMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('chatSession.id')
                    ->label(__('Chat session')),
                TextEntry::make('sender_type'),
                TextEntry::make('sender_id')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('message')
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
