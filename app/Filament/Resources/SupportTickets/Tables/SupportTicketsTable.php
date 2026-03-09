<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupportTickets\Tables;

use App\Enums\SupportTicketPriority;
use App\Enums\SupportTicketStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class SupportTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('priority')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->sortable(),
                TextColumn::make('messages_count')
                    ->counts('messages')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(SupportTicketStatus::class),
                SelectFilter::make('priority')
                    ->options(SupportTicketPriority::class),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
