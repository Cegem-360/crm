<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Orders') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your sales orders') }}</p>
        </div>
        <div class="flex items-center gap-2">
            {{ $this->importAction }}
            {{ $this->exportAction }}
            <x-primary-button :href="route('dashboard.orders.create', ['team' => $currentTeam])" icon="plus">
                {{ __('New Order') }}
            </x-primary-button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search orders...')" :value="$search" />

            <x-filter-select wire:model.live="status">
                <option value="">{{ __('All statuses') }}</option>
                @foreach($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}">{{ ucfirst($statusOption->value) }}</option>
                @endforeach
            </x-filter-select>

            <x-per-page-select />
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <x-sortable-header field="order_number" :$sortBy :$sortDir>{{ __('Order #') }}</x-sortable-header>
                        <x-table-header>{{ __('Customer') }}</x-table-header>
                        <x-sortable-header field="total" :$sortBy :$sortDir>{{ __('Total') }}</x-sortable-header>
                        <x-table-header>{{ __('Items') }}</x-table-header>
                        <x-sortable-header field="status" :$sortBy :$sortDir>{{ __('Status') }}</x-sortable-header>
                        <x-sortable-header field="order_date" :$sortBy :$sortDir>{{ __('Order Date') }}</x-sortable-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr wire:key="order-{{ $order->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.orders.view', ['team' => $currentTeam, 'order' => $order]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->customer)
                                    <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $order->customer]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $order->customer->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ Number::currency($order->total, 'HUF', 'hu', 0) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ $order->order_items_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="$order->status->badgeColor()" :label="$order->status->getLabel()" />
                            </td>
                            <td class="px-6 py-4">
                                @if($order->order_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $order->order_date->format('Y-m-d') }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.orders.view', ['team' => $currentTeam, 'order' => $order])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.orders.edit', ['team' => $currentTeam, 'order' => $order])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No orders found') }}</p>
                                    @if($search)
                                        <button wire:click="$set('search', '')" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ __('Clear search') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$orders" />

    <x-filament-actions::modals />
</div>
