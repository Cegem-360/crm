<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupportTickets\Pages;

use App\Enums\SupportTicketStatus;
use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Override;

final class ViewSupportTicket extends ViewRecord
{
    protected static string $resource = SupportTicketResource::class;

    #[Override]
    public function getView(): string
    {
        return 'filament.resources.support-tickets.pages.view-support-ticket';
    }

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('assignToMe')
                ->label(__('Assign to Me'))
                ->icon('heroicon-o-user-plus')
                ->color(Color::Blue)
                ->visible(fn (): bool => $this->record->assigned_to === null)
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update([
                        'assigned_to' => Auth::id(),
                        'status' => SupportTicketStatus::InProgress,
                    ]);

                    Notification::make()
                        ->title(__('Ticket Assigned'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['assigned_to', 'status']);
                }),
            Action::make('transfer')
                ->label(__('Transfer'))
                ->icon('heroicon-o-arrow-path')
                ->color(Color::Orange)
                ->visible(fn (): bool => $this->record->assigned_to !== null
                    && ! in_array($this->record->status, [SupportTicketStatus::Closed, SupportTicketStatus::Resolved]))
                ->schema([
                    Select::make('new_assigned_to')
                        ->label(__('Transfer to'))
                        ->options(fn (): array => User::query()->pluck('name', 'id')->all())
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update(['assigned_to' => $data['new_assigned_to']]);

                    Notification::make()
                        ->title(__('Ticket Transferred'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['assigned_to']);
                }),
            Action::make('resolve')
                ->label(__('Resolve'))
                ->icon('heroicon-o-check-circle')
                ->color(Color::Green)
                ->visible(fn (): bool => ! in_array($this->record->status, [SupportTicketStatus::Resolved, SupportTicketStatus::Closed]))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update([
                        'status' => SupportTicketStatus::Resolved,
                        'resolved_at' => now(),
                    ]);

                    Notification::make()
                        ->title(__('Ticket Resolved'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'resolved_at']);
                }),
            Action::make('close')
                ->label(__('Close Ticket'))
                ->icon('heroicon-o-x-circle')
                ->color(Color::Red)
                ->visible(fn (): bool => $this->record->status !== SupportTicketStatus::Closed)
                ->requiresConfirmation()
                ->modalDescription(__('Are you sure you want to close this ticket?'))
                ->action(function (): void {
                    $this->record->update([
                        'status' => SupportTicketStatus::Closed,
                        'closed_at' => now(),
                    ]);

                    Notification::make()
                        ->title(__('Ticket Closed'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'closed_at']);
                }),
        ];
    }
}
