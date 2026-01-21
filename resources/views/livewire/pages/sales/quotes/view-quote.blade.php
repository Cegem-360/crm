<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.quotes') }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $quote->quote_number }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Quote details') }}</p>
            </div>
        </div>
        <a href="{{ route('dashboard.quotes.edit', $quote) }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('Edit') }}
        </a>
    </div>

    {{-- Quote details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Quote Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quote Number') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quote->quote_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($quote->customer)
                            <a href="{{ route('dashboard.customers.view', $quote->customer) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $quote->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Opportunity') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($quote->opportunity)
                            <a href="{{ route('dashboard.opportunities.view', $quote->opportunity) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $quote->opportunity->title }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($quote->total, 0, ',', ' ') }} Ft</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'accepted' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                'expired' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$quote->status->value] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($quote->status->value) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Valid Until') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quote->valid_until?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quote->created_at->format('Y-m-d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quote->updated_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Quote items --}}
    @if($quote->items->count() > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Items') }} ({{ $quote->items->count() }})</h2>
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
                        @foreach($quote->items as $item)
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
</div>
