<?php

declare(strict_types=1);

namespace App\Filament\Resources\Complaints\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ComplaintInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('order.id')
                    ->label(__('Order'))
                    ->placeholder(__('-')),
                TextEntry::make('reported_by')
                    ->numeric(),
                TextEntry::make('assigned_to')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('severity'),
                TextEntry::make('status'),
                TextEntry::make('resolution')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('reported_at')
                    ->dateTime(),
                TextEntry::make('resolved_at')
                    ->dateTime()
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
