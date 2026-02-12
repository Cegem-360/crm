@props(['field', 'sortBy', 'sortDir'])

<th class="px-6 py-3 text-left">
    <button wire:click="sort('{{ $field }}')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
        {{ $slot }}
        @if($sortBy === $field)
            <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
        @endif
    </button>
</th>
