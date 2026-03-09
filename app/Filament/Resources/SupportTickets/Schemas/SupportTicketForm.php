<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupportTickets\Schemas;

use App\Enums\SupportTicketCategory;
use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class SupportTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('subject')
                    ->required()
                    ->placeholder(__('e.g., Cannot access reports'))
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->placeholder(__('Describe the issue in detail...'))
                    ->rows(4)
                    ->columnSpanFull(),
                Select::make('category')
                    ->options(SupportTicketCategory::class)
                    ->enum(SupportTicketCategory::class)
                    ->default(SupportTicketCategory::General),
                Select::make('priority')
                    ->options(SupportTicketPriority::class)
                    ->enum(SupportTicketPriority::class)
                    ->required()
                    ->default(SupportTicketPriority::Normal),
                Select::make('status')
                    ->options(SupportTicketStatus::class)
                    ->enum(SupportTicketStatus::class)
                    ->required()
                    ->default(SupportTicketStatus::Open),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->default(static fn (): ?int => auth()->id()),
                Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
