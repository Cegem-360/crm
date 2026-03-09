<x-filament-panels::page>
    @vite('resources/js/app.js')
    <div class="space-y-6">
        {{-- Ticket Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::section>
                <div class="text-center">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $record->ticket_number }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Ticket Number') }}</div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-center">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $record->status->getLabel() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-center">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $record->priority->getLabel() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Priority') }}</div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-center">
                    <div class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $record->messages->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Messages') }}</div>
                </div>
            </x-filament::section>
        </div>

        {{-- Ticket Details --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-filament::section>
                <x-slot name="heading">
                    {{ __('Ticket Details') }}
                </x-slot>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Subject') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $record->subject }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $record->description }}</dd>
                    </div>
                    @if($record->category)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Category') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $record->category->getLabel() }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $record->created_at->format('Y.m.d. H:i:s') }}
                            <span class="text-gray-500 dark:text-gray-400">({{ $record->created_at->diffForHumans() }})</span>
                        </dd>
                    </div>
                    @if($record->resolved_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Resolved At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $record->resolved_at->format('Y.m.d. H:i:s') }}
                            </dd>
                        </div>
                    @endif
                    @if($record->closed_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Closed At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $record->closed_at->format('Y.m.d. H:i:s') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    {{ __('People') }}
                </x-slot>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created By') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($record->user)
                                <div class="font-semibold">{{ $record->user->name }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $record->user->email }}</div>
                            @else
                                <span class="text-gray-400">{{ __('Unknown') }}</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Assigned To') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($record->assignedUser)
                                <div class="font-semibold">{{ $record->assignedUser->name }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $record->assignedUser->email }}</div>
                            @else
                                <span class="text-orange-600 dark:text-orange-400">{{ __('Unassigned') }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-filament::section>
        </div>

        {{-- Live Messaging Interface --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ __('Messages') }}
            </x-slot>

            @livewire('support.ticket-messages', ['ticket' => $record])
        </x-filament::section>
    </div>
</x-filament-panels::page>
