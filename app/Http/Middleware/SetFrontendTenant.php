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
use App\Models\Team;
use App\Models\WorkflowConfig;
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

        // If the team is still a string (slug), resolve it manually
        if (is_string($team)) {
            $team = Team::where('slug', $team)->first();

            if (! $team) {
                abort(404);
            }
        }

        if (! $team instanceof Team) {
            abort(404);
        }

        // Verify user has access to this team
        $user = Auth::user();
        if (! $user || ! $user->teams()->whereKey($team->id)->exists()) {
            abort(403);
        }

        // Store current tenant in request for easy access
        $request->attributes->set('current_team', $team);

        // Share current team with all views
        View::share('currentTeam', $team);

        // Apply global scopes
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
                fn ($query) => $query->where($query->qualifyColumn('team_id'), $team->id),
            );
        }

        return $next($request);
    }
}
