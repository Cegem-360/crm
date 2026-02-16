@php
    $items = $getChainItems();
    $hasAnyRelated = collect($items)->whereNotNull('number')->count() > 1;
@endphp

@if ($hasAnyRelated)
    <div class="flex flex-wrap items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-white/10 dark:bg-white/5">
        <span class="mr-1 text-xs font-medium text-gray-500 dark:text-gray-400">
            {{ __('Document Chain') }}:
        </span>

        @foreach ($items as $index => $item)
            @if ($item['number'])
                @if ($index > 0 && collect($items)->slice(0, $index)->whereNotNull('number')->isNotEmpty())
                    <x-filament::icon icon="heroicon-m-arrow-right" class="h-4 w-4 text-gray-400 dark:text-gray-500" />
                @endif

                @if ($item['current'])
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700 ring-1 ring-primary-200 dark:bg-primary-500/10 dark:text-primary-400 dark:ring-primary-500/30">
                        <span>{{ $item['label'] }}:</span>
                        <span>{{ $item['number'] }}</span>
                    </span>
                @elseif ($item['url'])
                    <a
                        href="{{ $item['url'] }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-2.5 py-1 text-xs font-medium text-gray-700 ring-1 ring-gray-200 transition hover:bg-gray-100 hover:text-primary-600 dark:bg-white/5 dark:text-gray-300 dark:ring-white/10 dark:hover:bg-white/10 dark:hover:text-primary-400"
                    >
                        <span>{{ $item['label'] }}:</span>
                        <span>{{ $item['number'] }}</span>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white px-2.5 py-1 text-xs text-gray-500 ring-1 ring-gray-200 dark:bg-white/5 dark:text-gray-400 dark:ring-white/10">
                        <span>{{ $item['label'] }}:</span>
                        <span>{{ $item['number'] }}</span>
                    </span>
                @endif
            @endif
        @endforeach
    </div>
@endif
