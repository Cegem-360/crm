<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.orders', ['team' => $currentTeam]) }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Order details') }}</p>
            </div>
        </div>
        <x-primary-button :href="route('dashboard.orders.edit', ['team' => $currentTeam, 'order' => $order])" icon="edit">
            {{ __('Edit') }}
        </x-primary-button>
    </div>

    {{-- Order details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Order Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Order Number') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->order_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($order->customer)
                            <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $order->customer]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $order->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quote') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($order->quote)
                            <a href="{{ route('dashboard.quotes.view', ['team' => $currentTeam, 'quote' => $order->quote]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $order->quote->quote_number }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($order->total, 0, ',', ' ') }} Ft</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="$order->status->badgeColor()" :label="$order->status->getLabel()" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Order Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->order_date?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('Y-m-d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->updated_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Order items --}}
    @if($order->orderItems->count() > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Order Items') }} ({{ $order->orderItems->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Product') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Quantity') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Unit Price') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->product?->name ?? $item->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right">{{ number_format($item->unit_price, 0, ',', ' ') }} Ft</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white text-right">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }} Ft</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Related invoices --}}
    @if($order->invoices->count() > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Invoices') }} ({{ $order->invoices->count() }})</h2>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($order->invoices as $invoice)
                    <li>
                        <a href="{{ route('dashboard.invoices.view', ['team' => $currentTeam, 'invoice' => $invoice]) }}" wire:navigate class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($invoice->total, 0, ',', ' ') }} Ft</p>
                            </div>
                            <x-status-badge :color="$invoice->status->badgeColor()" :label="$invoice->status->getLabel()" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
