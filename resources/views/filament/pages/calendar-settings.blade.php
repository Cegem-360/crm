<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Connection Status --}}
        <x-filament::section>
            <x-slot name="heading">{{ __('Connection Status') }}</x-slot>

            <div class="flex items-center gap-4">
                @if ($isConnected)
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-check-circle class="h-6 w-6 text-success-500" />
                        <span class="text-sm font-medium text-success-600 dark:text-success-400">
                            {{ __('Google Calendar is connected') }}
                        </span>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-x-circle class="h-6 w-6 text-danger-500" />
                        <span class="text-sm font-medium text-danger-600 dark:text-danger-400">
                            {{ __('Google Calendar is not connected') }}
                        </span>
                    </div>
                @endif
            </div>
        </x-filament::section>

        {{-- Sync Settings --}}
        @if ($isConnected)
            <x-filament::section>
                <x-slot name="heading">{{ __('Sync Settings') }}</x-slot>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-950 dark:text-white">
                                {{ __('Auto-sync to Google Calendar') }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Automatically create calendar events when tasks or interactions are created or updated.') }}
                            </p>
                        </div>

                        <x-filament::button
                            wire:click="toggleSync"
                            :color="$syncEnabled ? 'success' : 'gray'"
                            size="sm"
                        >
                            {{ $syncEnabled ? __('Enabled') : __('Disabled') }}
                        </x-filament::button>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <strong>{{ __('Calendar ID') }}:</strong> {{ $calendarId }}
                        </p>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">{{ __('What gets synced') }}</x-slot>

                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-primary-500" />
                        <span>{{ __('Tasks — synced as all-day events on their due date') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-chat-bubble-left-right class="h-5 w-5 text-primary-500" />
                        <span>{{ __('Interactions — synced as timed events based on interaction date and duration') }}</span>
                    </div>
                </div>
            </x-filament::section>
        @else
            <x-filament::section>
                <x-slot name="heading">{{ __('Get Started') }}</x-slot>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Connect your Google Calendar to automatically sync your CRM tasks and interactions. Click the "Connect Google Calendar" button above to get started.') }}
                </p>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
