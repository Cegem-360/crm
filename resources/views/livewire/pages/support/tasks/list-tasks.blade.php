<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Tasks') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage and track support tasks') }}</p>
        </div>
        <div class="flex items-center gap-2">
            {{ $this->importAction }}
            {{ $this->exportAction }}
            <x-primary-button :href="route('dashboard.tasks.create', ['team' => $currentTeam])" icon="plus">
                {{ __('New Task') }}
            </x-primary-button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search tasks...')" :value="$search" />

            <x-filter-select wire:model.live="status" width="sm:w-40">
                <option value="">{{ __('All statuses') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="in_progress">{{ __('In Progress') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="cancelled">{{ __('Cancelled') }}</option>
            </x-filter-select>

            <x-filter-select wire:model.live="priority" width="sm:w-36">
                <option value="">{{ __('All priorities') }}</option>
                <option value="low">{{ __('Low') }}</option>
                <option value="medium">{{ __('Medium') }}</option>
                <option value="high">{{ __('High') }}</option>
                <option value="urgent">{{ __('Urgent') }}</option>
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
                        <x-table-header>{{ __('Assigned To') }}</x-table-header>
                        <x-sortable-header field="priority" :$sortBy :$sortDir>{{ __('Priority') }}</x-sortable-header>
                        <x-sortable-header field="status" :$sortBy :$sortDir>{{ __('Status') }}</x-sortable-header>
                        <x-sortable-header field="due_date" :$sortBy :$sortDir>{{ __('Due Date') }}</x-sortable-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tasks as $task)
                        <tr wire:key="task-{{ $task->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.tasks.view', ['team' => $currentTeam, 'task' => $task]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $task->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($task->customer)
                                    <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $task->customer]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $task->customer->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($task->assignedUser)
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $task->assignedUser->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">{{ __('Unassigned') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="match($task->priority) { 'low' => 'gray', 'medium' => 'blue', 'high' => 'orange', 'urgent' => 'red', default => 'gray' }" :label="ucfirst($task->priority)" />
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="match($task->status) { 'pending' => 'yellow', 'in_progress' => 'blue', 'completed' => 'green', 'cancelled' => 'gray', default => 'gray' }" :label="ucfirst(str_replace('_', ' ', $task->status))" />
                            </td>
                            <td class="px-6 py-4">
                                @if($task->due_date)
                                    @php
                                        $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
                                    @endphp
                                    <span class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $task->due_date->format('Y-m-d') }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.tasks.view', ['team' => $currentTeam, 'task' => $task])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.tasks.edit', ['team' => $currentTeam, 'task' => $task])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No tasks found') }}</p>
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
        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$tasks" />

    <x-filament-actions::modals />
</div>
