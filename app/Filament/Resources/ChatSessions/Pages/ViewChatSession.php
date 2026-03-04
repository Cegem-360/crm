<?php

declare(strict_types=1);

namespace App\Filament\Resources\ChatSessions\Pages;

use App\Filament\Resources\ChatSessions\ChatSessionResource;
use App\Models\User;
use App\Services\ChatService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Override;

final class ViewChatSession extends ViewRecord
{
    protected static string $resource = ChatSessionResource::class;

    #[Override]
    public function getView(): string
    {
        return 'filament.resources.chat-sessions.pages.view-chat-session';
    }

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('assign')
                ->label(__('Assign to Me'))
                ->icon('heroicon-o-user-plus')
                ->color(Color::Blue)
                ->visible(fn (): bool => $this->record->user_id === null)
                ->requiresConfirmation()
                ->action(function (): void {
                    $chatService = resolve(ChatService::class);
                    $chatService->assignSession($this->record, Auth::user());

                    Notification::make()
                        ->title(__('Session Assigned'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['user_id']);
                }),
            Action::make('transfer')
                ->label(__('Transfer'))
                ->icon('heroicon-o-arrow-path')
                ->color(Color::Orange)
                ->visible(fn (): bool => $this->record->user_id !== null && $this->record->status->value === 'active')
                ->form([
                    Select::make('new_user_id')
                        ->label(__('Transfer to Agent'))
                        ->options(fn (): array => User::query()->pluck('name', 'id')->all())
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $chatService = resolve(ChatService::class);
                    $newUser = User::query()->find($data['new_user_id']);
                    $chatService->transferSession($this->record, $newUser);

                    Notification::make()
                        ->title(__('Session Transferred'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['user_id']);
                }),
            Action::make('close')
                ->label(__('Close Session'))
                ->icon('heroicon-o-x-circle')
                ->color(Color::Red)
                ->visible(fn (): bool => $this->record->status->value === 'active')
                ->requiresConfirmation()
                ->modalDescription(__('Are you sure you want to close this chat session?'))
                ->action(function (): void {
                    $chatService = resolve(ChatService::class);
                    $chatService->closeSession($this->record);

                    Notification::make()
                        ->title(__('Session Closed'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'ended_at']);
                }),
        ];
    }
}
