<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use App\Enums\CustomFieldModel;
use App\Filament\Concerns\HasCustomFieldsSchema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CustomerForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('unique_identifier')
                    ->default(fn (): string => 'CUST-'.Str::upper(Str::random(8)))
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->required()
                    ->options(CustomerType::class)
                    ->default(CustomerType::Individual),
                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('tax_number'),
                        TextInput::make('registration_number'),
                        TextInput::make('email')
                            ->email(),
                    ]),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->required(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Customer),
            ]);
    }
}
