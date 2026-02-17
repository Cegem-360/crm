<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Schemas;

use App\Filament\Schemas\Components\DocumentChain;
use App\Models\Quote;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

final class QuoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DocumentChain::make()
                    ->columnSpanFull(),
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

                Section::make(__('Line Items'))
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->table([
                                TableColumn::make(__('Product')),
                                TableColumn::make(__('Description')),
                                TableColumn::make(__('Quantity'))
                                    ->alignment(Alignment::End),
                                TableColumn::make(__('Unit Price'))
                                    ->alignment(Alignment::End),
                                TableColumn::make(__('Discount %'))
                                    ->alignment(Alignment::End),
                                TableColumn::make(__('Discount'))
                                    ->alignment(Alignment::End),
                                TableColumn::make(__('Tax %'))
                                    ->alignment(Alignment::End),
                                TableColumn::make(__('Total'))
                                    ->alignment(Alignment::End),
                            ])
                            ->schema([
                                TextEntry::make('product.name')
                                    ->placeholder(__('-')),
                                TextEntry::make('description'),
                                TextEntry::make('quantity')
                                    ->numeric(),
                                TextEntry::make('unit_price')
                                    ->numeric()
                                    ->suffix(' Ft'),
                                TextEntry::make('discount_percent')
                                    ->numeric()
                                    ->suffix('%'),
                                TextEntry::make('discount_amount')
                                    ->numeric()
                                    ->suffix(' Ft'),
                                TextEntry::make('tax_rate')
                                    ->numeric()
                                    ->suffix('%'),
                                TextEntry::make('total')
                                    ->numeric()
                                    ->suffix(' Ft'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Totals'))
                    ->schema([
                        TextEntry::make('subtotal')
                            ->numeric()
                            ->suffix(' Ft'),
                        TextEntry::make('discount_amount')
                            ->label(__('Total Discount'))
                            ->numeric()
                            ->suffix(' Ft'),
                        TextEntry::make('tax_amount')
                            ->label(__('Tax Amount'))
                            ->numeric()
                            ->suffix(' Ft'),
                        TextEntry::make('total')
                            ->label(__('Grand Total'))
                            ->numeric()
                            ->suffix(' Ft'),
                    ])
                    ->columns(4),

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
