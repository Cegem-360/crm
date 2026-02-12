<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Invoices') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your invoices') }}</p>
        </div>
        <a href="{{ route('dashboard.invoices.create', ['team' => $currentTeam]) }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('New Invoice') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        wire:keydown.escape="$set('search', '')"
                        placeholder="{{ __('Search invoices...') }}"
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    >
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <x-filter-select wire:model.live="status">
                <option value="">{{ __('All statuses') }}</option>
                @foreach($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}">{{ $statusOption->getLabel() }}</option>
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
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('invoice_number')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Invoice #') }}
                                @if($sortBy === 'invoice_number')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Customer') }}</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('total')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Total') }}
                                @if($sortBy === 'total')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('status')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Status') }}
                                @if($sortBy === 'status')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('issue_date')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Issue Date') }}
                                @if($sortBy === 'issue_date')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('due_date')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Due Date') }}
                                @if($sortBy === 'due_date')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($invoices as $invoice)
                        <tr wire:key="invoice-{{ $invoice->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.invoices.view', ['team' => $currentTeam, 'invoice' => $invoice]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($invoice->customer)
                                    <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $invoice->customer]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $invoice->customer->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($invoice->total, 0, ',', ' ') }} Ft</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'active' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'paid' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$invoice->status->value] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $invoice->status->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($invoice->issue_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->issue_date->format('Y-m-d') }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($invoice->due_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400 {{ $invoice->due_date->isPast() && $invoice->status->value !== 'paid' ? 'text-red-500 dark:text-red-400' : '' }}">
                                        {{ $invoice->due_date->format('Y-m-d') }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('dashboard.invoices.view', ['team' => $currentTeam, 'invoice' => $invoice]) }}" wire:navigate class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" title="{{ __('View') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('dashboard.invoices.edit', ['team' => $currentTeam, 'invoice' => $invoice]) }}" wire:navigate class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="{{ __('Edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No invoices found') }}</p>
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
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
        {{ __('Showing') }} {{ $invoices->firstItem() ?? 0 }} {{ __('to') }} {{ $invoices->lastItem() ?? 0 }} {{ __('of') }} {{ $invoices->total() }} {{ __('results') }}
    </div>
</div>
