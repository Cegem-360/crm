<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Schemas\Components\DocumentChain;
use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DocumentChain::make()
                    ->columnSpanFull(),
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('quote.quote_number')
                    ->label(__('Quote'))
                    ->placeholder(__('-')),
                TextEntry::make('order_number'),
                TextEntry::make('order_date')
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
                    ->visible(fn (Order $record): bool => $record->trashed()),
            ]);
    }
}
