<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.interactions', ['team' => $currentTeam]) }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $interaction->subject ?? __('No subject') }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Interaction details') }}</p>
            </div>
        </div>
        <x-primary-button :href="route('dashboard.interactions.edit', ['team' => $currentTeam, 'interaction' => $interaction])" icon="edit">
            {{ __('Edit') }}
        </x-primary-button>
    </div>

    {{-- Interaction details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Interaction Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Subject') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->subject ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="$interaction->status->badgeColor()" :label="$interaction->status->getLabel()" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="$interaction->type->badgeColor()" :label="$interaction->type->getLabel()" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Category') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->category?->getLabel() ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Channel') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->channel?->getLabel() ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Direction') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->direction?->getLabel() ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($interaction->customer)
                            <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $interaction->customer]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $interaction->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Contact') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $interaction->contact?->full_name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $interaction->user?->name ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Interaction Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $interaction->interaction_date?->format('Y-m-d H:i') ?? '-' }}
                    </dd>
                </div>
                @if($interaction->duration)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Duration') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->duration }} {{ __('minutes') }}</dd>
                    </div>
                @endif
                @if($interaction->description)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $interaction->description }}</dd>
                    </div>
                @endif
                @if($interaction->next_action)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Next Action') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $interaction->next_action }}
                            @if($interaction->next_action_date)
                                <span class="text-gray-500 dark:text-gray-400">({{ $interaction->next_action_date->format('Y-m-d') }})</span>
                            @endif
                        </dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->created_at->format('Y-m-d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $interaction->updated_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
