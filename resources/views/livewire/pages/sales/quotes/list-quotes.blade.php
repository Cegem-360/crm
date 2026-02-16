<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Quotes') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your sales quotes') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.quotes.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Quote') }}
        </x-primary-button>
    </div>

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
