<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChatSessions\Schemas;

use App\Enums\ChatSessionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ChatSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Session details'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('customer_id')
                                    ->label('Customer')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('user_id')
                                    ->label('Assigned agent')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('status')
                                    ->options(ChatSessionStatus::class)
                                    ->default(ChatSessionStatus::Active->value)
                                    ->required(),
                                Select::make('priority')
                                    ->options([
                                        'low' => __('Low'),
                                        'normal' => __('Normal'),
                                        'high' => __('High'),
                                        'urgent' => __('Urgent'),
                                    ])
                                    ->default('normal')
                                    ->required(),
                                TextInput::make('rating')
                                    ->label('Customer rating')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->suffix('/5')
                                    ->nullable(),
                            ]),
                    ]),
                Section::make(__('Timestamps'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DateTimePicker::make('started_at')
                                    ->default(now())
                                    ->required()
                                    ->seconds(false),
                                DateTimePicker::make('ended_at')
                                    ->nullable()
                                    ->seconds(false),
                                DateTimePicker::make('last_message_at')
                                    ->label('Last message')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->seconds(false),
                            ]),
                    ])
                    ->collapsible(),
                Section::make(__('Additional information'))
                    ->schema([
                        Placeholder::make('unread_count')
                            ->label('Unread messages')
                            ->content(fn ($record) => $record?->unread_count ?? 0),
                        Textarea::make('notes')
                            ->label('Internal notes')
                            ->rows(4)
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->collapsible(),
            ]);
    }
}
