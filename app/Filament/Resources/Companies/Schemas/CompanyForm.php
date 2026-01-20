<?php

declare(strict_types=1);

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('tax_number')
                    ->label('Tax Number'),
                TextInput::make('registration_number')
                    ->label('Registration Number'),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
            ]);
    }
}
