<x-language-switcher />

{{-- Quick add button - New Customer --}}
@if ($tenant = filament()->getTenant())
    <a href="{{ \App\Filament\Resources\Customers\CustomerResource::getUrl('create', tenant: $tenant) }}"
        class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
        <x-filament::icon icon="heroicon-o-plus" class="h-4 w-4" />
        {{ __('New Customer') }}
    </a>
@endif
