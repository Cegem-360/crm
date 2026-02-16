<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class GoogleCalendarController extends Controller
{
    public function redirect(Request $request, GoogleCalendarService $calendarService): RedirectResponse
    {
        $teamSlug = $request->query('team');

        $state = $teamSlug ? base64_encode(json_encode(['team' => $teamSlug])) : '';

        return redirect()->away($calendarService->getAuthUrl($state));
    }

    public function callback(Request $request, GoogleCalendarService $calendarService): RedirectResponse
    {
        $code = $request->query('code');

        if (! $code) {
            return to_route('filament.admin.pages.calendar-settings')
                ->with('error', __('Google Calendar authorization failed.'));
        }

        /** @var User $user */
        $user = $request->user();

        $state = $request->query('state');
        $teamSlug = null;

        if ($state) {
            $decoded = json_decode(base64_decode($state), true);
            $teamSlug = $decoded['team'] ?? null;
        }

        $team = $teamSlug
            ? Team::query()->where('slug', $teamSlug)->first()
            : $user->teams()->first();

        if (! $team) {
            return to_route('filament.admin.pages.calendar-settings')
                ->with('error', __('Team not found.'));
        }

        $calendarService->handleCallback($code, $user, $team);

        return redirect()->to(
            route('filament.admin.pages.calendar-settings', ['tenant' => $team->slug])
        )->with('success', __('Google Calendar connected successfully!'));
    }
}
