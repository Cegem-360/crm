<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Products') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your product catalog') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.products.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Product') }}
        </x-primary-button>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search products...')" :value="$search" />

            <x-filter-select wire:model.live="category">
                <option value="">{{ __('All categories') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </x-filter-select>

            <x-filter-select wire:model.live="active" width="sm:w-36">
                <option value="">{{ __('All') }}</option>
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Inactive') }}</option>
            </x-filter-select>

            <x-per-page-select />
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <x-sortable-header field="name" :$sortBy :$sortDir>{{ __('Name') }}</x-sortable-header>
                        <x-sortable-header field="sku" :$sortBy :$sortDir>{{ __('SKU') }}</x-sortable-header>
                        <x-table-header>{{ __('Category') }}</x-table-header>
                        <x-sortable-header field="unit_price" :$sortBy :$sortDir>{{ __('Price') }}</x-sortable-header>
                        <x-table-header>{{ __('Status') }}</x-table-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr wire:key="product-{{ $product->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.products.view', ['team' => $currentTeam, 'product' => $product]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $product->sku }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->category)
                                    <a href="{{ route('dashboard.product-categories.view', ['team' => $currentTeam, 'productCategory' => $product->category]) }}" wire:navigate class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400 hover:bg-indigo-200 dark:hover:bg-indigo-900/50">
                                        {{ $product->category->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($product->unit_price, 0, ',', ' ') }} Ft</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                        {{ __('Inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.products.view', ['team' => $currentTeam, 'product' => $product])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.products.edit', ['team' => $currentTeam, 'product' => $product])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No products found') }}</p>
                                    @if($search)
                                        <button wire:click="$set('search', '')" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ __('Clear search') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$products" />
</div>
