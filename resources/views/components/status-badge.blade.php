@props(['color', 'label'])

@php
    $classes = match ($color) {
        'green' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        'red' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        'orange' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
        'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
        'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
