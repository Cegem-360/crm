<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            app()->instance('current_team', $tenant);

            User::addGlobalScope(
                'tenant',
                static fn (Builder $query): Builder => $query->whereHas(
                    'teams',
                    static fn (Builder $query): Builder => $query->where('teams.id', $tenant->getKey()),
                ),
            );
        }

        return $next($request);
    }
}
