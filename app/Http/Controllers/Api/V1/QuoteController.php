<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class QuoteController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Quote::class);

        $quotes = Quote::query()
            ->when($request->filled('search'), fn (Builder $query): Builder => $query->where(function (Builder $query) use ($request): void {
                $query->where('quote_number', 'like', sprintf('%%%s%%', $request->search));
            }))
            ->when($request->filled('status'), fn (Builder $query): Builder => $query->where('status', $request->status))
            ->when($request->filled('customer_id'), fn (Builder $query): Builder => $query->where('customer_id', $request->customer_id))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return QuoteResource::collection($quotes);
    }

    public function show(Quote $quote): QuoteResource
    {
        $this->authorize('view', $quote);

        return new QuoteResource($quote->load(['customer', 'opportunity', 'items']));
    }

    public function destroy(Quote $quote): JsonResponse
    {
        $this->authorize('delete', $quote);

        $quote->delete();

        return response()->json(['message' => 'Quote deleted successfully']);
    }
}
