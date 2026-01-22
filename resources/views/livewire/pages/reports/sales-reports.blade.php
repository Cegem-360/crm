<div>
    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Sales Reports') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Overview of sales performance, opportunities, and pipeline') }}</p>
    </div>

    {{-- Stats Overview --}}
    <div class="mb-6">
        @livewire(\App\Filament\Widgets\SalesStatsOverview::class)
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @livewire(\App\Filament\Widgets\SalesFunnelChart::class)
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @livewire(\App\Filament\Widgets\OpportunityValueChart::class)
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @livewire(\App\Filament\Widgets\QuoteStatusChart::class)
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @livewire(\App\Filament\Widgets\MonthlySalesChart::class)
        </div>
    </div>
</div>
