<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchProducts implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for products (termékek), product categories (termékkategóriák), and discounts (kedvezmények) in the CRM by name, SKU, or category. Returns matching products with pricing and category info.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $activeOnly = $request['active_only'] ?? false;

        $builder = Product::query()->with(['category']);

        if ($query) {
            $builder->where(function ($builder) use ($query): void {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$query}%"));
            });
        }

        if ($activeOnly) {
            $builder->where('is_active', true);
        }

        $products = $builder->orderBy('name')->limit(10)->get();

        if ($products->isEmpty()) {
            return 'No products found matching the given criteria.';
        }

        return $products->map(function (Product $product): string {
            $lines = [
                "**{$product->name}**".($product->sku ? " (SKU: {$product->sku})" : ''),
                'Price: '.number_format((float) $product->unit_price, 2),
                'Status: '.($product->is_active ? 'Active' : 'Inactive'),
            ];

            if ($product->category) {
                $lines[] = "Category: {$product->category->name}";
            }

            if ($product->tax_rate) {
                $lines[] = "Tax rate: {$product->tax_rate}%";
            }

            if ($product->description) {
                $lines[] = 'Description: '.str($product->description)->limit(120);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for product name, SKU, description, or category name'),
            'active_only' => $schema->boolean()->description('If true, only return active products'),
        ];
    }
}
