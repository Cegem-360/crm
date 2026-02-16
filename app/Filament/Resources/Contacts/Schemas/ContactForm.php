<?php

declare(strict_types=1);

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->placeholder(__('e.g., John Smith')),
                TextInput::make('email')
                    ->email()
                    ->placeholder(__('email@example.com')),
                TextInput::make('phone')
                    ->tel()
                    ->placeholder(__('+36 XX XXX XXXX')),
                TextInput::make('position')
                    ->placeholder(__('e.g., Sales Manager')),
                Toggle::make('is_primary')
                    ->label(__('Primary contact'))
                    ->default(false),
            ]);
    }
}
