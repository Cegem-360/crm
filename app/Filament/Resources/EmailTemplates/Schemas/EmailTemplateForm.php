<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailTemplates\Schemas;

use App\Enums\EmailTemplateCategory;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Template Name')
                    ->required()
                    ->placeholder('pl. Üdvözlő email'),
                Select::make('category')
                    ->label('Category')
                    ->options(EmailTemplateCategory::class)
                    ->default(EmailTemplateCategory::Sales)
                    ->required(),
                TextInput::make('subject')
                    ->label('Email Subject')
                    ->required()
                    ->placeholder('pl. Üdvözöljük a cégünknél!'),
                RichEditor::make('body')
                    ->label('Email Body')
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Használható változók: {customer_name}, {contact_name}, {user_name}'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
