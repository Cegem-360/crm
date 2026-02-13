<?php

declare(strict_types=1);

namespace App\Filament\Resources\Interactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class InteractionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('user.name')
                    ->label(__('User')),
                TextEntry::make('type'),
                TextEntry::make('subject'),
                TextEntry::make('description')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('interaction_date')
                    ->dateTime(),
                TextEntry::make('duration')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('next_action')
                    ->placeholder(__('-')),
                TextEntry::make('next_action_date')
                    ->date()
                    ->placeholder(__('-')),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
            ]);
    }
}
