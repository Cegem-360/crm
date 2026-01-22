<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Campaign;
use App\Models\Company;
use App\Models\Customer;
use App\Models\EmailTemplate;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\WorkflowConfig;
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
            $models = [
                Customer::class,
                Company::class,
                Product::class,
                ProductCategory::class,
                EmailTemplate::class,
                Campaign::class,
                WorkflowConfig::class,
            ];

            foreach ($models as $model) {
                $model::addGlobalScope(
                    'team',
                    fn ($query) => $query->where('team_id', $tenant->id),
                );
            }
        }

        return $next($request);
    }
}
