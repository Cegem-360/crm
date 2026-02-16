<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.orders', ['team' => $currentTeam]) }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Order details') }}</p>
            </div>
        </div>
        <x-primary-button :href="route('dashboard.orders.edit', ['team' => $currentTeam, 'order' => $order])" icon="edit">
            {{ __('Edit') }}
        </x-primary-button>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        {{ $this->infolist }}
    </div>

    <x-filament-actions::modals />
</div>
