<div>
    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Customer Reports') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Customer analytics, top customers, and growth trends') }}</p>
    </div>

    {{-- Stats Overview --}}
    <div class="mb-6">
        @livewire(\App\Filament\Widgets\CustomerStatsOverview::class)
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @livewire(\App\Filament\Widgets\TopCustomersChart::class)
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @livewire(\App\Filament\Widgets\CustomerTypeChart::class)
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
            @livewire(\App\Filament\Widgets\NewCustomersChart::class)
        </div>
    </div>
</div>
