<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.tasks', ['team' => $currentTeam]) }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $task->title }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Task details') }}</p>
            </div>
        </div>
        <x-primary-button :href="route('dashboard.tasks.edit', ['team' => $currentTeam, 'task' => $task])" icon="edit">
            {{ __('Edit') }}
        </x-primary-button>
    </div>

    {{-- Task details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Task Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Title') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="match($task->status) { 'pending' => 'yellow', 'in_progress' => 'blue', 'completed' => 'green', 'cancelled' => 'gray', default => 'gray' }" :label="ucfirst(str_replace('_', ' ', $task->status))" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Priority') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="match($task->priority) { 'low' => 'gray', 'medium' => 'blue', 'high' => 'orange', 'urgent' => 'red', default => 'gray' }" :label="ucfirst($task->priority)" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($task->customer)
                            <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $task->customer]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $task->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Assigned To') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $task->assignedUser?->name ?? __('Unassigned') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Assigned By') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $task->assigner?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</dt>
                    <dd class="mt-1 text-sm {{ $task->due_date?->isPast() && $task->status !== 'completed' ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-900 dark:text-white' }}">
                        {{ $task->due_date?->format('Y-m-d') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Completed At') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $task->completed_at?->format('Y-m-d H:i') ?? '-' }}
                    </dd>
                </div>
                @if($task->description)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $task->description }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->created_at->format('Y-m-d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $task->updated_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
