<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

final class SetFrontendTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $team = $request->route('team');

        if (is_string($team)) {
            $team = Team::query()->where('slug', $team)->first();
            abort_unless($team, 404);
        }

        abort_unless($team instanceof Team, 404);

        $user = Auth::user();
        abort_if(! $user || ! $user->teams()->whereKey($team->id)->exists(), 403);

        $request->attributes->set('current_team', $team);
        app()->instance('current_team', $team);

        View::share('currentTeam', $team);

        return $next($request);
    }
}
