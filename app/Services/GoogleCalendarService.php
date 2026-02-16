<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GoogleCalendarToken;
use App\Models\Interaction;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendarApi;
use Google\Service\Calendar\Event as GoogleEvent;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoogleCalendarService
{
    private ?GoogleClient $client = null;

    public function getAuthUrl(string $state = ''): string
    {
        $client = $this->buildClient();
        $client->setState($state);

        return $client->createAuthUrl();
    }

    public function handleCallback(string $code, User $user, Team $team): GoogleCalendarToken
    {
        $client = $this->buildClient();
        $tokenData = $client->fetchAccessTokenWithAuthCode($code);

        return GoogleCalendarToken::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'team_id' => $team->id,
            ],
            [
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_at' => isset($tokenData['expires_in'])
                    ? Date::now()->addSeconds((int) $tokenData['expires_in'])
                    : null,
            ],
        );
    }

    public function disconnect(User $user, Team $team): void
    {
        GoogleCalendarToken::query()
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->delete();
    }

    public function isConnected(User $user, Team $team): bool
    {
        return GoogleCalendarToken::query()
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->exists();
    }

    public function getToken(User $user, Team $team): ?GoogleCalendarToken
    {
        return GoogleCalendarToken::query()
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->first();
    }

    public function syncTaskToCalendar(Task $task, User $user, Team $team): ?string
    {
        $token = $this->getToken($user, $team);

        if (! $token || ! $token->sync_enabled) {
            return null;
        }

        try {
            $calendarApi = $this->getCalendarApi($token);
            $event = $this->buildTaskEvent($task);

            if (filled($task->calendar_event_id)) {
                $calendarApi->events->update($token->calendar_id, $task->calendar_event_id, $event);

                return $task->calendar_event_id;
            }

            $createdEvent = $calendarApi->events->insert($token->calendar_id, $event);

            return $createdEvent->getId();
        } catch (Throwable $throwable) {
            Log::error('Google Calendar sync failed for task', [
                'task_id' => $task->id,
                'error' => $throwable->getMessage(),
            ]);

            return null;
        }
    }

    public function syncInteractionToCalendar(Interaction $interaction, User $user, Team $team): ?string
    {
        $token = $this->getToken($user, $team);

        if (! $token || ! $token->sync_enabled) {
            return null;
        }

        try {
            $calendarApi = $this->getCalendarApi($token);
            $event = $this->buildInteractionEvent($interaction);

            if (filled($interaction->calendar_event_id)) {
                $calendarApi->events->update($token->calendar_id, $interaction->calendar_event_id, $event);

                return $interaction->calendar_event_id;
            }

            $createdEvent = $calendarApi->events->insert($token->calendar_id, $event);

            return $createdEvent->getId();
        } catch (Throwable $throwable) {
            Log::error('Google Calendar sync failed for interaction', [
                'interaction_id' => $interaction->id,
                'error' => $throwable->getMessage(),
            ]);

            return null;
        }
    }

    public function deleteCalendarEvent(string $calendarEventId, User $user, Team $team): void
    {
        $token = $this->getToken($user, $team);

        if (! $token || ! $token->sync_enabled) {
            return;
        }

        try {
            $calendarApi = $this->getCalendarApi($token);
            $calendarApi->events->delete($token->calendar_id, $calendarEventId);
        } catch (Throwable $throwable) {
            Log::error('Google Calendar event deletion failed', [
                'calendar_event_id' => $calendarEventId,
                'error' => $throwable->getMessage(),
            ]);
        }
    }

    private function buildClient(): GoogleClient
    {
        if ($this->client instanceof GoogleClient) {
            return $this->client;
        }

        $this->client = new GoogleClient;
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->setScopes(config('services.google.scopes'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        return $this->client;
    }

    private function getCalendarApi(GoogleCalendarToken $token): GoogleCalendarApi
    {
        $client = $this->buildClient();
        $client->setAccessToken($token->access_token);

        if ($token->isExpired() && filled($token->refresh_token)) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($token->refresh_token);

            $token->update([
                'access_token' => $newToken['access_token'],
                'expires_at' => isset($newToken['expires_in'])
                    ? Date::now()->addSeconds((int) $newToken['expires_in'])
                    : null,
            ]);

            $client->setAccessToken($newToken['access_token']);
        }

        return new GoogleCalendarApi($client);
    }

    private function buildTaskEvent(Task $task): GoogleEvent
    {
        $event = new GoogleEvent;
        $event->setSummary('[Task] '.$task->title);

        $description = '';
        if (filled($task->description)) {
            $description .= $task->description."\n\n";
        }

        $description .= sprintf('Priority: %s%s', $task->priority, PHP_EOL);
        $description .= sprintf('Status: %s%s', $task->status, PHP_EOL);
        if ($task->customer) {
            $description .= sprintf('Customer: %s%s', $task->customer->name, PHP_EOL);
        }

        $event->setDescription($description);

        if ($task->due_date) {
            $startDate = new EventDateTime;
            $startDate->setDate($task->due_date->format('Y-m-d'));
            $startDate->setTimeZone('Europe/Budapest');
            $event->setStart($startDate);

            $endDate = new EventDateTime;
            $endDate->setDate($task->due_date->format('Y-m-d'));
            $endDate->setTimeZone('Europe/Budapest');
            $event->setEnd($endDate);
        } else {
            $now = new EventDateTime;
            $now->setDate(now()->format('Y-m-d'));
            $now->setTimeZone('Europe/Budapest');
            $event->setStart($now);
            $event->setEnd($now);
        }

        return $event;
    }

    private function buildInteractionEvent(Interaction $interaction): GoogleEvent
    {
        $event = new GoogleEvent;
        $event->setSummary(sprintf('[%s] %s', $interaction->type?->value, $interaction->subject));

        $description = '';
        if (filled($interaction->description)) {
            $description .= $interaction->description."\n\n";
        }

        if ($interaction->customer) {
            $description .= sprintf('Customer: %s%s', $interaction->customer->name, PHP_EOL);
        }

        if (filled($interaction->next_action)) {
            $description .= sprintf('Next Action: %s%s', $interaction->next_action, PHP_EOL);
        }

        $event->setDescription($description);

        $interactionDate = $interaction->interaction_date ?? now();
        $durationMinutes = $interaction->duration ?? 30;

        $start = new EventDateTime;
        $start->setDateTime($interactionDate->toRfc3339String());
        $start->setTimeZone('Europe/Budapest');

        $event->setStart($start);

        $end = new EventDateTime;
        $end->setDateTime($interactionDate->copy()->addMinutes($durationMinutes)->toRfc3339String());
        $end->setTimeZone('Europe/Budapest');

        $event->setEnd($end);

        return $event;
    }
}
