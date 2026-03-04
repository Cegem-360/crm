<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\CustomFieldModel;
use App\Filament\Concerns\HasCustomFieldsSchema;
use App\Models\TeamSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class ProductForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->placeholder(__('e.g., Premium Service Package'))
                    ->required(),
                TextInput::make('sku')
                    ->label(__('SKU'))
                    ->placeholder(__('e.g., PROD-001'))
                    ->required(),
                Textarea::make('description')
                    ->placeholder(__('Features, specifications, details...'))
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('unit_price')
                    ->label(__('Unit Price'))
                    ->required()
                    ->numeric()
                    ->prefix(fn (): string => TeamSetting::currentCurrency())
                    ->default(0),
                TextInput::make('tax_rate')
                    ->label(__('Tax Rate'))
                    ->helperText(__('Standard rate: 27%'))
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->default(27),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Product),
            ]);
    }
}
