<?php

declare(strict_types=1);

namespace App\Filament\Resources\Discounts\Schemas;

use App\Models\Discount;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class DiscountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('type'),
                TextEntry::make('value_type'),
                TextEntry::make('value')
                    ->numeric(),
                TextEntry::make('min_quantity')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('min_value')
                    ->numeric()
                    ->placeholder(__('-')),
                TextEntry::make('valid_from')
                    ->date()
                    ->placeholder(__('-')),
                TextEntry::make('valid_until')
                    ->date()
                    ->placeholder(__('-')),
                TextEntry::make('customer.name')
                    ->label(__('Customer'))
                    ->placeholder(__('-')),
                TextEntry::make('product.name')
                    ->label(__('Product'))
                    ->placeholder(__('-')),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('description')
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
                    ->visible(fn (Discount $record): bool => $record->trashed()),
            ]);
    }
}
