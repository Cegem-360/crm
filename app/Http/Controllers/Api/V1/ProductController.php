<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::query()
            ->with('category')
            ->when($request->filled('search'), fn (Builder $query): Builder => $query->where(function (Builder $query) use ($request): void {
                $query->where('name', 'like', sprintf('%%%s%%', $request->search))
                    ->orWhere('sku', 'like', sprintf('%%%s%%', $request->search));
            }))
            ->when($request->filled('category_id'), fn (Builder $query): Builder => $query->where('category_id', $request->category_id))
            ->unless($request->boolean('include_inactive'), fn (Builder $query): Builder => $query->where('is_active', true))
            ->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $validated = $request->validated();

        if (! isset($validated['team_id'])) {
            $validated['team_id'] = $request->user()->teams()->first()?->id;
        }

        $product = Product::query()->create($validated);

        return new ProductResource($product->load('category'));
    }

    public function show(Product $product): ProductResource
    {
        $this->authorize('view', $product);

        return new ProductResource($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());

        return new ProductResource($product->fresh()->load('category'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
