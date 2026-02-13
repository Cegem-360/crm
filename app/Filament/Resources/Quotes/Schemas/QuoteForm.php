<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Schemas;

use App\Enums\CustomFieldModel;
use App\Enums\QuoteStatus;
use App\Filament\Concerns\HasCustomFieldsSchema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class QuoteForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('opportunity_id')
                    ->label(__('Opportunity'))
                    ->relationship('opportunity', 'title'),
                TextInput::make('quote_number')
                    ->required(),
                DatePicker::make('issue_date')
                    ->required(),
                DatePicker::make('valid_until')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->default(QuoteStatus::Draft)
                    ->options(QuoteStatus::class),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('notes')
                    ->columnSpanFull(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Quote),
            ]);
    }
}
