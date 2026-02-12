<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Complaints') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Track and resolve customer complaints') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.complaints.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Complaint') }}
        </x-primary-button>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search complaints...')" :value="$search" />

            <x-filter-select wire:model.live="status" width="sm:w-40">
                <option value="">{{ __('All statuses') }}</option>
                @foreach($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}">{{ $statusOption->getLabel() }}</option>
                @endforeach
            </x-filter-select>

            <x-filter-select wire:model.live="severity" width="sm:w-36">
                <option value="">{{ __('All severities') }}</option>
                @foreach($severities as $severityOption)
                    <option value="{{ $severityOption->value }}">{{ $severityOption->getLabel() }}</option>
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
                        <x-table-header>{{ __('Order') }}</x-table-header>
                        <x-sortable-header field="severity" :$sortBy :$sortDir>{{ __('Severity') }}</x-sortable-header>
                        <x-sortable-header field="status" :$sortBy :$sortDir>{{ __('Status') }}</x-sortable-header>
                        <x-sortable-header field="reported_at" :$sortBy :$sortDir>{{ __('Reported') }}</x-sortable-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($complaints as $complaint)
                        <tr wire:key="complaint-{{ $complaint->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.complaints.view', ['team' => $currentTeam, 'complaint' => $complaint]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $complaint->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($complaint->customer)
                                    <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $complaint->customer]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $complaint->customer->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($complaint->order)
                                    <a href="{{ route('dashboard.orders.view', ['team' => $currentTeam, 'order' => $complaint->order]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $complaint->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="$complaint->severity->badgeColor()" :label="$complaint->severity->getLabel()" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="$complaint->status->badgeColor()" :label="$complaint->status->getLabel()" />
                            </td>
                            <td class="px-6 py-4">
                                @if($complaint->reported_at)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $complaint->reported_at->format('Y-m-d H:i') }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.complaints.view', ['team' => $currentTeam, 'complaint' => $complaint])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.complaints.edit', ['team' => $currentTeam, 'complaint' => $complaint])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No complaints found') }}</p>
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
        @if($complaints->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$complaints" />
</div>
