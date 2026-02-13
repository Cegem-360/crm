<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('unique_identifier'),
                TextEntry::make('name'),
                TextEntry::make('type'),
                TextEntry::make('company.name')
                    ->label(__('Company'))
                    ->placeholder(__('-')),
                TextEntry::make('phone')
                    ->placeholder(__('-')),
                TextEntry::make('notes')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Customer $record): bool => $record->trashed()),
            ]);
    }
}
