<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\BugReport;
use App\Models\Campaign;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Company;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Discount;
use App\Models\EmailTemplate;
use App\Models\Interaction;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Quote;
use App\Models\Task;
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
                BugReport::class,
                Campaign::class,
                ChatMessage::class,
                ChatSession::class,
                Company::class,
                Complaint::class,
                Customer::class,
                CustomerContact::class,
                Discount::class,
                EmailTemplate::class,
                Interaction::class,
                Invoice::class,
                Opportunity::class,
                Order::class,
                Product::class,
                ProductCategory::class,
                Quote::class,
                Task::class,
                WorkflowConfig::class,
            ];

            foreach ($models as $model) {
                $model::addGlobalScope(
                    'team',
                    fn ($query) => $query->where($query->qualifyColumn('team_id'), $tenant->id),
                );
            }
        }

        return $next($request);
    }
}
