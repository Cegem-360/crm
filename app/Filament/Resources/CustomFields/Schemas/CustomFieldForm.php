<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomFields\Schemas;

use App\Enums\CustomFieldModel;
use App\Enums\CustomFieldType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

final class CustomFieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Field Definition'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->unique(ignoreRecord: true)
                            ->helperText(__('Leave empty to auto-generate from name'))
                            ->maxLength(255),
                        Select::make('model_type')
                            ->label(__('Applies To'))
                            ->options(CustomFieldModel::class)
                            ->required()
                            ->native(false),
                        Select::make('type')
                            ->label(__('Field Type'))
                            ->options(CustomFieldType::class)
                            ->required()
                            ->live()
                            ->native(false),
                        TagsInput::make('options')
                            ->label(__('Dropdown Options'))
                            ->placeholder(__('Add option and press Enter'))
                            ->visible(fn (Get $get): bool => $get('type') === CustomFieldType::Select->value || $get('type') === CustomFieldType::Select)
                            ->helperText(__('Enter each option and press Enter')),
                        Textarea::make('description')
                            ->label(__('Help Text'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make(__('Display Settings'))
                    ->schema([
                        TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0)
                            ->helperText(__('Lower numbers appear first')),
                        Grid::make(4)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label(__('Active'))
                                    ->default(true),
                                Toggle::make('is_visible_in_form')
                                    ->label(__('Show in Forms'))
                                    ->default(true),
                                Toggle::make('is_visible_in_table')
                                    ->label(__('Show in Tables'))
                                    ->default(false),
                                Toggle::make('is_visible_in_infolist')
                                    ->label(__('Show in Detail View'))
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}
