@props(['align' => 'left'])

<th class="px-6 py-3 text-{{ $align }}">
    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $slot }}</span>
</th>
