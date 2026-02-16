<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class OpportunityController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Opportunity::class);

        $opportunities = Opportunity::query()
            ->when($request->filled('search'), fn (Builder $query): Builder => $query->where(function (Builder $query) use ($request): void {
                $query->where('title', 'like', sprintf('%%%s%%', $request->search));
            }))
            ->when($request->filled('stage'), fn (Builder $query): Builder => $query->where('stage', $request->stage))
            ->when($request->filled('customer_id'), fn (Builder $query): Builder => $query->where('customer_id', $request->customer_id))
            ->when($request->filled('assigned_to'), fn (Builder $query): Builder => $query->where('assigned_to', $request->assigned_to))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return OpportunityResource::collection($opportunities);
    }

    public function show(Opportunity $opportunity): OpportunityResource
    {
        $this->authorize('view', $opportunity);

        return new OpportunityResource($opportunity->load(['customer', 'assignedUser', 'quotes']));
    }

    public function destroy(Opportunity $opportunity): JsonResponse
    {
        $this->authorize('delete', $opportunity);

        $opportunity->delete();

        return response()->json(['message' => 'Opportunity deleted successfully']);
    }
}
