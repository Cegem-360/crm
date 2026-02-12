@props(['paginator'])

<div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
    {{ __('Showing') }} {{ $paginator->firstItem() ?? 0 }} {{ __('to') }} {{ $paginator->lastItem() ?? 0 }} {{ __('of') }} {{ $paginator->total() }} {{ __('results') }}
</div>
