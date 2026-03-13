<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchOrders implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for orders (rendelések) in the CRM by order number, customer name, or status. Returns matching orders with their details and amounts.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? null;
        $status = $request['status'] ?? null;

        $builder = Order::query()->with(['customer', 'orderItems']);

        if ($query) {
            $builder->where(function ($builder) use ($query): void {
                $builder->where('order_number', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%")
                    ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$query}%"));
            });
        }

        if ($status) {
            $builder->where('status', $status);
        }

        $orders = $builder->latest('order_date')->limit(8)->get();

        if ($orders->isEmpty()) {
            return 'No orders found matching the given criteria.';
        }

        return $orders->map(function (Order $order): string {
            $lines = [
                "**{$order->order_number}** — {$order->status->getLabel()}",
            ];

            if ($order->customer) {
                $lines[] = "Customer: {$order->customer->name}";
            }

            $lines[] = 'Total: '.number_format((float) $order->total, 2);
            $lines[] = "Order date: {$order->order_date->format('Y-m-d')}";
            $lines[] = "Items: {$order->orderItems->count()}";

            if ($order->notes) {
                $lines[] = 'Notes: '.str($order->notes)->limit(120);
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Search term for order number, customer name, or notes'),
            'status' => $schema->string()->enum(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->description('Filter by order status'),
        ];
    }
}
