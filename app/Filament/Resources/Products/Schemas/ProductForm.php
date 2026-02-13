<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\CustomFieldModel;
use App\Filament\Concerns\HasCustomFieldsSchema;
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
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('sku')
                    ->label(__('SKU'))
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name'),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax_rate')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->required(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Product),
            ]);
    }
}
