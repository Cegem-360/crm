<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetUserTeamsRequest;
use App\Http\Requests\Api\TeamCreateRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class TeamController extends Controller
{
    public function create(TeamCreateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $team = Team::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
        ]);

        $userAttached = false;

        if (isset($validated['user_email'])) {
            $user = User::query()->where('email', $validated['user_email'])->first();
            if ($user) {
                $user->teams()->attach($team);
                $userAttached = true;
            }
        }

        Log::info('Team created', [
            'team_id' => $team->id,
            'name' => $team->name,
            'slug' => $team->slug,
            'user_email' => $userAttached ? $validated['user_email'] : null,
        ]);

        return response()->json([
            'message' => 'Team created successfully',
            'team_id' => $team->id,
        ], 201);
    }

    public function getUserTeams(GetUserTeamsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->where('email', $validated['user_email'])->first();

        $teams = $user->teams()->get(['teams.id']);

        return response()->json([
            'teams' => $teams,
        ], 200);
    }
}
