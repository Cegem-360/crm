<aside
    class="fixed inset-y-0 left-0 z-50 w-60 bg-[#292F4C] text-white flex flex-col transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
    :class="{
        'lg:-translate-x-full': !sidebarOpen,
        'lg:translate-x-0': sidebarOpen,
        '-translate-x-full': !mobileMenuOpen,
        'translate-x-0': mobileMenuOpen
    }">

    {{-- Logo --}}
    <div class="h-16 flex items-center px-4 border-b border-white/10">
        <a href="{{ route('dashboard.dashboard', ['team' => $currentTeam]) }}" class="flex items-center gap-2">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}"
                class="h-8 brightness-0 invert">
            <span class="text-sm font-semibold text-indigo-400">CRM</span>
        </a>
    </div>

    {{-- Current Team --}}
    @if (isset($currentTeam))
        <div class="px-3 py-3 border-b border-white/10">
            <div class="flex items-center gap-2 px-3 py-2">
                <div class="w-6 h-6 rounded bg-indigo-500 flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr($currentTeam->name, 0, 1)) }}
                </div>
                <span class="text-sm font-medium truncate">{{ $currentTeam->name }}</span>
            </div>
        </div>
    @endif

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">

        {{-- Home --}}
        <x-layouts.sidebar-nav-group :title="__('Home')">
            <x-layouts.sidebar-nav-item route="dashboard.dashboard" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" :team="$currentTeam">
                {{ __('Dashboard') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Customers --}}
        <x-layouts.sidebar-nav-group :title="__('Customers')">
            <x-layouts.sidebar-nav-item route="dashboard.customers" icon-color="text-indigo-400" icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" :team="$currentTeam">
                {{ __('Customers') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.contacts" icon-color="text-indigo-400" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" :team="$currentTeam">
                {{ __('Contacts') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Sales --}}
        <x-layouts.sidebar-nav-group :title="__('Sales')">
            <x-layouts.sidebar-nav-item route="dashboard.opportunities" icon-color="text-green-400" icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" :team="$currentTeam">
                {{ __('Opportunities') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.quotes" icon-color="text-green-400" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" :team="$currentTeam">
                {{ __('Quotes') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.orders" icon-color="text-green-400" icon="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" :team="$currentTeam">
                {{ __('Orders') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.invoices" icon-color="text-green-400" icon="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" :team="$currentTeam">
                {{ __('Invoices') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Products --}}
        <x-layouts.sidebar-nav-group :title="__('Products')">
            <x-layouts.sidebar-nav-item route="dashboard.products" icon-color="text-purple-400" icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" :team="$currentTeam">
                {{ __('Products') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.product-categories" icon-color="text-purple-400" icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" :team="$currentTeam">
                {{ __('Categories') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.discounts" icon-color="text-purple-400" icon="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" :team="$currentTeam">
                {{ __('Discounts') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Marketing --}}
        <x-layouts.sidebar-nav-group :title="__('Marketing')">
            <x-layouts.sidebar-nav-item route="dashboard.campaigns" icon-color="text-orange-400" icon="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" :team="$currentTeam">
                {{ __('Campaigns') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Activities --}}
        <x-layouts.sidebar-nav-group :title="__('Activities')">
            <x-layouts.sidebar-nav-item route="dashboard.tasks" icon-color="text-cyan-400" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" :team="$currentTeam">
                {{ __('Tasks') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.interactions" icon-color="text-cyan-400" icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" :team="$currentTeam" title="{{ __('Log calls, meetings, and emails') }}">
                {{ __('Interactions') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.chat-sessions" icon-color="text-cyan-400" icon="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" :team="$currentTeam" title="{{ __('Real-time customer conversations') }}">
                {{ __('Chat Sessions') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Support --}}
        <x-layouts.sidebar-nav-group :title="__('Support')">
            <x-layouts.sidebar-nav-item route="dashboard.complaints" icon-color="text-blue-400" icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" :team="$currentTeam">
                {{ __('Complaints') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Reports --}}
        <x-layouts.sidebar-nav-group :title="__('Reports')">
            <x-layouts.sidebar-nav-item route="dashboard.reports.sales" icon-color="text-emerald-400" icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" :team="$currentTeam">
                {{ __('Sales Reports') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.reports.orders" icon-color="text-emerald-400" icon="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" :team="$currentTeam">
                {{ __('Order Reports') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.reports.products" icon-color="text-emerald-400" icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" :team="$currentTeam">
                {{ __('Product Reports') }}
            </x-layouts.sidebar-nav-item>
            <x-layouts.sidebar-nav-item route="dashboard.reports.customers" icon-color="text-emerald-400" icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" :team="$currentTeam">
                {{ __('Customer Reports') }}
            </x-layouts.sidebar-nav-item>
        </x-layouts.sidebar-nav-group>

        {{-- Quick Links --}}
        <x-layouts.sidebar-nav-group :title="__('Quick Links')">
            <li>
                <a href="https://cegem360.hu/modules" target="_blank"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Cegem360 Modulok
                    <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </li>
        </x-layouts.sidebar-nav-group>

    </nav>

</aside>
