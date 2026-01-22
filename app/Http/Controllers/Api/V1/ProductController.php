<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
            ->when($request->filled('search'), fn (Builder $query) => $query
                ->where('name', 'like', sprintf('%%%s%%', $request->search))
                ->orWhere('sku', 'like', sprintf('%%%s%%', $request->search)))
            ->when($request->filled('category_id'), fn (Builder $query) => $query
                ->where('category_id', $request->category_id))
            ->when($request->boolean('include_inactive'), fn (Builder $query): Builder => $query, fn (Builder $query) => $query
                ->where('is_active', true))
            ->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    public function store(Request $request): ProductResource
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
            'team_id' => ['sometimes', 'exists:teams,id'],
        ]);

        // Set team_id from request or user's first team
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

    public function update(Request $request, Product $product): ProductResource
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'sku' => ['sometimes', 'string', 'max:255', 'unique:products,sku,'.$product->id],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'unit_price' => ['sometimes', 'numeric', 'min:0'],
            'tax_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $product->update($validated);

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
