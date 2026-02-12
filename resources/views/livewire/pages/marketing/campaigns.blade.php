<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Campaigns') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your marketing campaigns') }}</p>
        </div>
        <x-primary-button href="/admin/campaigns/create" icon="plus">
            {{ __('New Campaign') }}
        </x-primary-button>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search campaigns...')" :value="$search" />

            <x-filter-select wire:model.live="status" width="sm:w-40">
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
                        <x-sortable-header field="name" :$sortBy :$sortDir>{{ __('Name') }}</x-sortable-header>
                        <x-sortable-header field="status" :$sortBy :$sortDir>{{ __('Status') }}</x-sortable-header>
                        <x-table-header>{{ __('Responses') }}</x-table-header>
                        <x-sortable-header field="start_date" :$sortBy :$sortDir>{{ __('Start Date') }}</x-sortable-header>
                        <x-sortable-header field="end_date" :$sortBy :$sortDir>{{ __('End Date') }}</x-sortable-header>
                        <x-table-header>{{ __('Created By') }}</x-table-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($campaigns as $campaign)
                        <tr wire:key="campaign-{{ $campaign->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="/admin/campaigns/{{ $campaign->id }}/edit" class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $campaign->name }}
                                </a>
                                @if($campaign->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate max-w-xs">{{ Str::limit($campaign->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="match($campaign->status) { 'draft' => 'gray', 'active' => 'green', 'paused' => 'yellow', 'completed' => 'blue', 'cancelled' => 'red', default => 'gray' }" :label="ucfirst($campaign->status)" />
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400">
                                    {{ $campaign->responses_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($campaign->start_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $campaign->start_date->format('Y-m-d') }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($campaign->end_date)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $campaign->end_date->format('Y-m-d') }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($campaign->creator)
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $campaign->creator->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button href="/admin/campaigns/{{ $campaign->id }}/edit" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No campaigns found') }}</p>
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
        @if($campaigns->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$campaigns" />
</div>
