<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.discounts', ['team' => $currentTeam]) }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $discount->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Discount details') }}</p>
            </div>
        </div>
        <x-primary-button :href="route('dashboard.discounts.edit', ['team' => $currentTeam, 'discount' => $discount])" icon="edit">
            {{ __('Edit') }}
        </x-primary-button>
    </div>

    {{-- Discount details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Discount Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="$discount->is_active ? 'green' : 'gray'" :label="$discount->is_active ? __('Active') : __('Inactive')" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</dt>
                    <dd class="mt-1">
                        <x-status-badge :color="$discount->type->badgeColor()" :label="$discount->type->getLabel()" />
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Value') }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                        @if($discount->value_type->value === 'percentage')
                            {{ $discount->value }}%
                        @else
                            {{ number_format($discount->value, 0, ',', ' ') }} Ft
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($discount->customer)
                            <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $discount->customer]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $discount->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Product') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($discount->product)
                            <a href="{{ route('dashboard.products.view', ['team' => $currentTeam, 'product' => $discount->product]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $discount->product->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                @if($discount->min_quantity)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Minimum Quantity') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->min_quantity }}</dd>
                    </div>
                @endif
                @if($discount->min_value)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Minimum Value') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ number_format($discount->min_value, 0, ',', ' ') }} Ft</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Valid From') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->valid_from?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Valid Until') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->valid_until?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                @if($discount->description)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->description }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->created_at->format('Y-m-d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discount->updated_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
