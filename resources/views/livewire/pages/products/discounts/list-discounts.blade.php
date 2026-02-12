<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Discounts') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your discount rules and promotions') }}</p>
        </div>
        <div class="flex items-center gap-2">
            {{ $this->importAction }}
            {{ $this->exportAction }}
            <x-primary-button :href="route('dashboard.discounts.create', ['team' => $currentTeam])" icon="plus">
                {{ __('New Discount') }}
            </x-primary-button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search discounts...')" :value="$search" />

            <x-filter-select wire:model.live="type">
                <option value="">{{ __('All types') }}</option>
                @foreach($types as $typeOption)
                    <option value="{{ $typeOption->value }}">{{ $typeOption->getLabel() }}</option>
                @endforeach
            </x-filter-select>

            <x-filter-select wire:model.live="active" width="sm:w-36">
                <option value="">{{ __('All') }}</option>
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Inactive') }}</option>
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
                        <x-sortable-header field="name" :$sortBy :$sortDir>{{ __('Name') }}</x-sortable-header>
                        <x-sortable-header field="type" :$sortBy :$sortDir>{{ __('Type') }}</x-sortable-header>
                        <x-sortable-header field="value" :$sortBy :$sortDir>{{ __('Value') }}</x-sortable-header>
                        <x-table-header>{{ __('Customer / Product') }}</x-table-header>
                        <x-table-header>{{ __('Valid Period') }}</x-table-header>
                        <x-table-header>{{ __('Status') }}</x-table-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($discounts as $discount)
                        <tr wire:key="discount-{{ $discount->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.discounts.view', ['team' => $currentTeam, 'discount' => $discount]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $discount->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="$discount->type->badgeColor()" :label="$discount->type->getLabel()" />
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($discount->value_type->value === 'percentage')
                                        {{ $discount->value }}%
                                    @else
                                        {{ Number::currency($discount->value, 'HUF', 'hu', 0) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    @if($discount->customer)
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            <span class="text-gray-400">{{ __('Customer:') }}</span> {{ $discount->customer->name }}
                                        </span>
                                    @endif
                                    @if($discount->product)
                                        <span class="text-sm text-gray-600 dark:text-gray-300">
                                            <span class="text-gray-400">{{ __('Product:') }}</span> {{ $discount->product->name }}
                                        </span>
                                    @endif
                                    @if(!$discount->customer && !$discount->product)
                                        <span class="text-sm text-gray-400 dark:text-gray-500">{{ __('Global') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($discount->valid_from || $discount->valid_until)
                                        {{ $discount->valid_from?->format('Y-m-d') ?? '-' }}
                                        <span class="mx-1">{{ __('to') }}</span>
                                        {{ $discount->valid_until?->format('Y-m-d') ?? '-' }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">{{ __('No limit') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="$discount->is_active ? 'green' : 'gray'" :label="$discount->is_active ? __('Active') : __('Inactive')" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.discounts.view', ['team' => $currentTeam, 'discount' => $discount])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.discounts.edit', ['team' => $currentTeam, 'discount' => $discount])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No discounts found') }}</p>
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
        @if($discounts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $discounts->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$discounts" />

    <x-filament-actions::modals />
</div>
