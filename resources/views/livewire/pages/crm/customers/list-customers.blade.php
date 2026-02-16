<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Customers') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your customer database') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.customers.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Customer') }}
        </x-primary-button>
    </div>

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
