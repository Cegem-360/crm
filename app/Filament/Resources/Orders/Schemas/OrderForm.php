<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\CustomFieldModel;
use App\Enums\OrderStatus;
use App\Filament\Concerns\HasCustomFieldsSchema;
use App\Filament\Schemas\Components\DocumentChain;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class OrderForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DocumentChain::make()
                    ->columnSpanFull(),
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('quote_id')
                    ->label(__('Quote'))
                    ->relationship('quote', 'quote_number')
                    ->searchable()
                    ->preload(),
                TextInput::make('order_number')
                    ->label(__('Order Number'))
                    ->disabled()
                    ->dehydrated()
                    ->placeholder(__('Auto-generated')),
                DatePicker::make('order_date')
                    ->label(__('Order Date'))
                    ->required(),
                Select::make('status')
                    ->required()
                    ->default(OrderStatus::Pending)
                    ->options(OrderStatus::class),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                TextInput::make('discount_amount')
                    ->label(__('Total Discount'))
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                TextInput::make('tax_amount')
                    ->label(__('Tax Amount'))
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                TextInput::make('total')
                    ->label(__('Grand Total'))
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                Textarea::make('notes')
                    ->placeholder(__('Payment terms, delivery notes, special instructions...'))
                    ->columnSpanFull(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Order),
            ]);
    }
}
