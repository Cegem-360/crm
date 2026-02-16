<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Schemas;

use App\Enums\CustomFieldModel;
use App\Enums\QuoteStatus;
use App\Filament\Concerns\HasCustomFieldsSchema;
use App\Filament\Schemas\Components\DocumentChain;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

final class QuoteForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DocumentChain::make()
                    ->columnSpanFull(),
                Section::make(__('Quote Details'))
                    ->schema([
                        Select::make('customer_id')
                            ->label(__('Customer'))
                            ->relationship('customer', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('opportunity_id')
                            ->label(__('Opportunity'))
                            ->relationship('opportunity', 'title')
                            ->searchable()
                            ->preload(),
                        TextInput::make('quote_number')
                            ->label(__('Quote Number'))
                            ->disabled()
                            ->dehydrated()
                            ->placeholder(__('Auto-generated')),
                        DatePicker::make('issue_date')
                            ->label(__('Issue Date'))
                            ->required(),
                        DatePicker::make('valid_until')
                            ->label(__('Valid Until'))
                            ->required(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->required()
                            ->default(QuoteStatus::Draft)
                            ->options(QuoteStatus::class),
                    ])
                    ->columns(2),

                Section::make(__('Line Items'))
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('Product'))
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $product = Product::find($state);

                                        if (! $product) {
                                            return;
                                        }

                                        $set('description', $product->name);
                                        $set('unit_price', $product->unit_price);
                                        $set('tax_rate', $product->tax_rate);
                                        self::calculateItemTotals($get, $set);
                                    })
                                    ->columnSpan(3),
                                TextInput::make('description')
                                    ->label(__('Description'))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(3),
                                TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->required()
                                    ->numeric()
                                    ->default(1)
                                    ->live()
                                    ->minValue(0.01)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateItemTotals($get, $set)),
                                TextInput::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->required()
                                    ->numeric()
                                    ->prefix('Ft')
                                    ->default(0)
                                    ->live()
                                    ->minValue(0)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateItemTotals($get, $set)),
                                TextInput::make('discount_percent')
                                    ->label(__('Discount %'))
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(0)
                                    ->live()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateItemTotals($get, $set)),
                                TextInput::make('discount_amount')
                                    ->label(__('Discount'))
                                    ->readOnly()
                                    ->numeric()
                                    ->prefix('Ft')
                                    ->default(0),
                                TextInput::make('tax_rate')
                                    ->label(__('Tax %'))
                                    ->required()
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(27)
                                    ->live()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateItemTotals($get, $set)),
                                TextInput::make('total')
                                    ->label(__('Total'))
                                    ->readOnly()
                                    ->numeric()
                                    ->prefix('Ft')
                                    ->default(0),
                            ])
                            ->columns(6)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add Item'))
                            ->cloneable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                self::updateQuoteTotals($get('items') ?? [], $set);
                            })
                            ->itemLabel(fn (array $state): ?string => ($state['description'] ?? '') !== ''
                                ? $state['description'].' - '.($state['quantity'] ?? 0).' x '.number_format((float) ($state['unit_price'] ?? 0), 0, ',', '.').' Ft'
                                : null)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Totals'))
                    ->schema([
                        TextInput::make('subtotal')
                            ->label(__('Subtotal'))
                            ->readOnly()
                            ->numeric()
                            ->prefix('Ft')
                            ->default(0),
                        TextInput::make('discount_amount')
                            ->label(__('Total Discount'))
                            ->readOnly()
                            ->numeric()
                            ->prefix('Ft')
                            ->default(0),
                        TextInput::make('tax_amount')
                            ->label(__('Tax Amount'))
                            ->readOnly()
                            ->numeric()
                            ->prefix('Ft')
                            ->default(0),
                        TextInput::make('total')
                            ->label(__('Grand Total'))
                            ->readOnly()
                            ->numeric()
                            ->prefix('Ft')
                            ->default(0),
                    ])
                    ->columns(4),

                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->columnSpanFull(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Quote),
            ]);
    }

    private static function calculateItemTotals(Get $get, Set $set): void
    {
        $quantity = (float) ($get('quantity') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);
        $discountPercent = (float) ($get('discount_percent') ?? 0);
        $taxRate = (float) ($get('tax_rate') ?? 0);

        $lineTotal = $quantity * $unitPrice;
        $discountAmount = $lineTotal * ($discountPercent / 100);
        $taxableAmount = $lineTotal - $discountAmount;
        $total = $taxableAmount + ($taxableAmount * ($taxRate / 100));

        $set('discount_amount', number_format($discountAmount, 2, '.', ''));
        $set('total', number_format($total, 2, '.', ''));

        // Recalculate quote-level totals from all items
        $allItems = $get('../../items') ?? [];
        self::updateQuoteTotalsFromItems($allItems, $get, $set);
    }

    private static function updateQuoteTotalsFromItems(array $items, Get $get, Set $set): void
    {
        $subtotal = 0;
        $totalDiscount = 0;
        $totalTax = 0;

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $discountPercent = (float) ($item['discount_percent'] ?? 0);
            $taxRate = (float) ($item['tax_rate'] ?? 0);

            $lineTotal = $quantity * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $taxableAmount = $lineTotal - $discountAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);

            $subtotal += $lineTotal;
            $totalDiscount += $discountAmount;
            $totalTax += $taxAmount;
        }

        $grandTotal = $subtotal - $totalDiscount + $totalTax;

        $set('../../subtotal', number_format($subtotal, 2, '.', ''));
        $set('../../discount_amount', number_format($totalDiscount, 2, '.', ''));
        $set('../../tax_amount', number_format($totalTax, 2, '.', ''));
        $set('../../total', number_format($grandTotal, 2, '.', ''));
    }

    /**
     * Called from Repeater's afterStateUpdated (add/remove/reorder items).
     */
    private static function updateQuoteTotals(array $items, Set $set): void
    {
        $subtotal = 0;
        $totalDiscount = 0;
        $totalTax = 0;

        foreach ($items as $item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) ($item['unit_price'] ?? 0);
            $discountPercent = (float) ($item['discount_percent'] ?? 0);
            $taxRate = (float) ($item['tax_rate'] ?? 0);

            $lineTotal = $quantity * $unitPrice;
            $discountAmount = $lineTotal * ($discountPercent / 100);
            $taxableAmount = $lineTotal - $discountAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);

            $subtotal += $lineTotal;
            $totalDiscount += $discountAmount;
            $totalTax += $taxAmount;
        }

        $grandTotal = $subtotal - $totalDiscount + $totalTax;

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('discount_amount', number_format($totalDiscount, 2, '.', ''));
        $set('tax_amount', number_format($totalTax, 2, '.', ''));
        $set('total', number_format($grandTotal, 2, '.', ''));
    }
}
