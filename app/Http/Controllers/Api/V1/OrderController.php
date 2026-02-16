<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::query()
            ->when($request->filled('search'), fn (Builder $query): Builder => $query->where(function (Builder $query) use ($request): void {
                $query->where('order_number', 'like', sprintf('%%%s%%', $request->search));
            }))
            ->when($request->filled('status'), fn (Builder $query): Builder => $query->where('status', $request->status))
            ->when($request->filled('customer_id'), fn (Builder $query): Builder => $query->where('customer_id', $request->customer_id))
            ->latest('order_date')
            ->paginate($request->integer('per_page', 15));

        return OrderResource::collection($orders);
    }

    public function show(Order $order): OrderResource
    {
        $this->authorize('view', $order);

        return new OrderResource($order->load(['customer', 'quote', 'orderItems']));
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
