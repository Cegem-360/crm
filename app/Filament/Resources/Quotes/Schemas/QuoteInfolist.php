<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Schemas;

use App\Models\Quote;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class QuoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('opportunity.title')
                    ->label(__('Opportunity'))
                    ->placeholder(__('-')),
                TextEntry::make('quote_number'),
                TextEntry::make('issue_date')
                    ->date(),
                TextEntry::make('valid_until')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->numeric(),
                TextEntry::make('tax_amount')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('notes')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Quote $record): bool => $record->trashed()),
            ]);
    }
}
