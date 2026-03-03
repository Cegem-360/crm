@props(['route', 'icon', 'iconColor' => '', 'team'])

<li>
    <a href="{{ route($route, ['team' => $team]) }}"
        {{ $attributes }}
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs($route) ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
        </svg>
        {{ $slot }}
    </a>
</li>
