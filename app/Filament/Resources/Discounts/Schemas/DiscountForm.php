<?php

declare(strict_types=1);

namespace App\Filament\Resources\Discounts\Schemas;

use App\Enums\DiscountType;
use App\Enums\DiscountValueType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder(__('e.g., Summer Sale 2024')),
                Select::make('type')
                    ->required()
                    ->default(DiscountType::Custom)
                    ->enum(DiscountType::class)
                    ->options(DiscountType::class),
                Select::make('value_type')
                    ->required()
                    ->default(DiscountValueType::Percentage)
                    ->enum(DiscountValueType::class)
                    ->options(DiscountValueType::class),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText(__('Percentage or fixed amount depending on value type')),
                TextInput::make('min_quantity')
                    ->numeric()
                    ->placeholder(__('0'))
                    ->helperText(__('Minimum quantity to apply discount')),
                TextInput::make('min_value')
                    ->numeric()
                    ->placeholder(__('0'))
                    ->helperText(__('Minimum order value to apply discount')),
                DatePicker::make('valid_from'),
                DatePicker::make('valid_until'),
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name'),
                Select::make('product_id')
                    ->label(__('Product'))
                    ->relationship('product', 'name'),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
                Textarea::make('description')
                    ->placeholder(__('Description of the discount...'))
                    ->columnSpanFull(),
            ]);
    }
}
