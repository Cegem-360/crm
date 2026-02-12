<div>
    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Dashboard') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Overview of your CRM data') }}</p>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Customers --}}
        <a href="{{ route('dashboard.customers', ['team' => $currentTeam]) }}"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customers') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($customersCount) }}
                    </p>
                </div>
                <div
                    class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/50 transition">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <span
                    class="text-green-600 dark:text-green-400 font-medium">{{ number_format($activeCustomersCount) }}</span>
                {{ __('active') }}
            </p>
        </a>

        {{-- Companies --}}
        <a href="{{ route('dashboard.companies', ['team' => $currentTeam]) }}"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Companies') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($companiesCount) }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('Total companies') }}</p>
        </a>

        {{-- Contacts --}}
        <a href="{{ route('dashboard.contacts', ['team' => $currentTeam]) }}"
            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Contacts') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($contactsCount) }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('Total contacts') }}</p>
        </a>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quick Actions') }}</p>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('dashboard.customers.create', ['team' => $currentTeam]) }}"
                    class="block w-full px-4 py-2 text-sm text-center text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                    {{ __('New Customer') }}
                </a>
                <a href="{{ route('dashboard.companies.create', ['team' => $currentTeam]) }}"
                    class="block w-full px-4 py-2 text-sm text-center text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-lg transition">
                    {{ __('New Company') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Recent activity section placeholder --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Customers --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Customers') }}</h2>
            </div>
            <div class="p-6">
                @php
                    $recentCustomers = \App\Models\Customer::latest()->take(5)->get();
                @endphp
                @if ($recentCustomers->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($recentCustomers as $customer)
                            <li class="py-3 first:pt-0 last:pb-0">
                                <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $customer]) }}"
                                    class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 -mx-3 px-3 py-2 rounded-lg transition">
                                    <div
                                        class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $customer->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $customer->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if ($customer->is_active)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('Active') }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No customers yet') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Recent Companies --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Companies') }}</h2>
            </div>
            <div class="p-6">
                @php
                    $recentCompanies = \App\Models\Company::latest()->take(5)->get();
                @endphp
                @if ($recentCompanies->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($recentCompanies as $company)
                            <li class="py-3 first:pt-0 last:pb-0">
                                <a href="{{ route('dashboard.companies.view', ['team' => $currentTeam, 'company' => $company]) }}"
                                    class="flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 -mx-3 px-3 py-2 rounded-lg transition">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-semibold text-sm">
                                        {{ strtoupper(substr($company->name, 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $company->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $company->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No companies yet') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
