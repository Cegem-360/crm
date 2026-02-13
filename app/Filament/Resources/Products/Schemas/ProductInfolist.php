<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('sku')
                    ->label(__('SKU')),
                TextEntry::make('description')
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('category.name')
                    ->label(__('Category'))
                    ->placeholder(__('-')),
                TextEntry::make('unit_price')
                    ->numeric(),
                TextEntry::make('tax_rate')
                    ->numeric(),
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
                    ->visible(fn (Product $record): bool => $record->trashed()),
            ]);
    }
}
