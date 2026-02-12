@props(['width' => 'sm:w-48'])

<div class="{{ $width }}">
    <select {{ $attributes->merge(['class' => 'w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500']) }}>
        {{ $slot }}
    </select>
</div>
