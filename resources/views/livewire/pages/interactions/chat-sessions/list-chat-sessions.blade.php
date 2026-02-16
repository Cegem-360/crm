<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Chat Sessions') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your chat sessions') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.chat-sessions.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Chat Session') }}
        </x-primary-button>
    </div>

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
