<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->saved(fn (?string $state): bool => filled($state)),

                TextInput::make('webhook_url')
                    ->label('Webhook URL')
                    ->url()
                    ->placeholder('https://example.com/api/webhooks/...'),
                TextInput::make('webhook_secret')
                    ->label(__('Webhook secret key'))
                    ->password()
                    ->revealable()
                    ->helperText(__('Secret key used for HMAC signing')),
            ]);
    }
}
