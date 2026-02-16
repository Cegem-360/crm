<?php

declare(strict_types=1);

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label(__('E-mail'))
                    ->placeholder(__('-')),
                TextEntry::make('phone')
                    ->placeholder(__('-')),
                TextEntry::make('position')
                    ->placeholder(__('-')),
                IconEntry::make('is_primary')
                    ->label(__('Primary contact'))
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
            ]);
    }
}
