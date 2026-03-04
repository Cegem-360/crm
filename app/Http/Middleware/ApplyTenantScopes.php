<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
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
        }

        return $next($request);
    }
}
