<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Opportunities') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your sales opportunities') }}</p>
        </div>
        <div class="flex items-center gap-2">
            {{ $this->importAction }}
            {{ $this->exportAction }}
            <x-primary-button :href="route('dashboard.opportunities.create', ['team' => $currentTeam])" icon="plus">
                {{ __('New Opportunity') }}
            </x-primary-button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search opportunities...')" :value="$search" />

            <x-filter-select wire:model.live="stage">
                <option value="">{{ __('All stages') }}</option>
                @foreach($stages as $stageOption)
                    <option value="{{ $stageOption->value }}">{{ $stageOption->getLabel() }}</option>
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
                        <x-sortable-header field="title" :$sortBy :$sortDir>{{ __('Title') }}</x-sortable-header>
                        <x-table-header>{{ __('Customer') }}</x-table-header>
                        <x-sortable-header field="value" :$sortBy :$sortDir>{{ __('Value') }}</x-sortable-header>
                        <x-sortable-header field="probability" :$sortBy :$sortDir>{{ __('Probability') }}</x-sortable-header>
                        <x-sortable-header field="stage" :$sortBy :$sortDir>{{ __('Stage') }}</x-sortable-header>
                        <x-sortable-header field="expected_close_date" :$sortBy :$sortDir>{{ __('Expected Close') }}</x-sortable-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($opportunities as $opportunity)
                        <tr wire:key="opportunity-{{ $opportunity->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.opportunities.view', ['team' => $currentTeam, 'opportunity' => $opportunity]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $opportunity->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($opportunity->customer)
                                    <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $opportunity->customer]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $opportunity->customer->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $opportunity->value !== null ? Number::currency($opportunity->value, 'HUF', 'hu', 0) : '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $opportunity->probability }}%</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $stageColors = [
                                        'lead' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'qualified' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'proposal' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'negotiation' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                        'sended_quotation' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'lost_quotation' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stageColors[$opportunity->stage->value] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $opportunity->stage->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($opportunity->expected_close_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $opportunity->expected_close_date->format('Y-m-d') }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.opportunities.view', ['team' => $currentTeam, 'opportunity' => $opportunity])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.opportunities.edit', ['team' => $currentTeam, 'opportunity' => $opportunity])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No opportunities found') }}</p>
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
        @if($opportunities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $opportunities->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$opportunities" />

    <x-filament-actions::modals />
</div>
