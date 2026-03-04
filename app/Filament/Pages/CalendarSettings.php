<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Models\GoogleCalendarToken;
use App\Models\Team;
use App\Models\User;
use App\Services\GoogleCalendarService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Override;
use UnitEnum;

final class CalendarSettings extends Page
{
    public bool $isConnected = false;

    public bool $syncEnabled = false;

    public ?string $calendarId = null;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.calendar-settings';

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('Calendar Settings');
    }

    #[Override]
    public function getTitle(): string
    {
        return __('Google Calendar Integration');
    }

    #[Override]
    public function getSubheading(): ?string
    {
        return __('Connect your Google Calendar to sync tasks and interactions.');
    }

    public function mount(): void
    {
        $token = $this->getCalendarToken();

        $this->isConnected = $token instanceof GoogleCalendarToken;
        $this->syncEnabled = $token?->sync_enabled ?? false;
        $this->calendarId = $token?->calendar_id ?? 'primary';
    }

    public function toggleSync(): void
    {
        $token = $this->getCalendarToken();

        if (! $token instanceof GoogleCalendarToken) {
            return;
        }

        $token->update(['sync_enabled' => ! $token->sync_enabled]);
        $this->syncEnabled = $token->fresh()->sync_enabled;

        Notification::make()
            ->title($this->syncEnabled ? __('Calendar sync enabled') : __('Calendar sync disabled'))
            ->success()
            ->send();
    }

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('connect')
                ->label(__('Connect Google Calendar'))
                ->icon('heroicon-o-link')
                ->color('primary')
                ->visible(fn (): bool => ! $this->isConnected)
                ->url(fn (): string => $this->getConnectUrl()),

            Action::make('disconnect')
                ->label(__('Disconnect Google Calendar'))
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->visible(fn (): bool => $this->isConnected)
                ->requiresConfirmation()
                ->modalHeading(__('Disconnect Google Calendar'))
                ->modalDescription(__('Are you sure you want to disconnect your Google Calendar? Existing calendar events will not be removed.'))
                ->action($this->disconnect(...)),
        ];
    }

    private function disconnect(): void
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Team $team */
        $team = Filament::getTenant();

        resolve(GoogleCalendarService::class)->disconnect($user, $team);

        $this->isConnected = false;
        $this->syncEnabled = false;

        Notification::make()
            ->title(__('Google Calendar disconnected'))
            ->success()
            ->send();
    }

    private function getConnectUrl(): string
    {
        /** @var Team $team */
        $team = Filament::getTenant();

        return route('google.redirect', ['team' => $team->slug]);
    }

    private function getCalendarToken(): ?GoogleCalendarToken
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Team|null $team */
        $team = Filament::getTenant();

        if (! $team) {
            return null;
        }

        return GoogleCalendarToken::query()
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->first();
    }
}
