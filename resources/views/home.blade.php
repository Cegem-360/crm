<x-layouts.app>
    {{-- ==================== --}}
    {{-- 1. HERO SECTION --}}
    {{-- ==================== --}}
    <section class="bg-gradient-to-b from-indigo-50 to-white pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ __('New: Automatic lead scoring with AI') }}
                </div>

                {{-- H1 --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-gray-900 mb-6 font-heading leading-tight">
                    {{ __('Manage your customers from first contact to closed deal') }}
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    {{ __('All customers, quotes, and communications in one place. Automatic reminders, sales pipeline, and detailed history – so your team can focus on closing, not administration.') }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="https://cegem360.eu/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-xl">
                        {{ __('Get started') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="https://cegem360.eu/kapcsolat" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-indigo-700 bg-white border-2 border-indigo-200 rounded-full hover:bg-indigo-50 transition-colors">
                        {{ __('Request a demo') }}
                    </a>
                    <a href="/login" class="inline-flex items-center justify-center gap-2 text-base font-medium text-indigo-600 transition-colors hover:text-indigo-800">
                        {{ __('Log in to the app') }} →
                    </a>
                </div>
            </div>

            {{-- Hero Image/Dashboard Preview --}}
            <div class="mt-16 relative">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 max-w-5xl mx-auto">
                    {{-- Dashboard mockup --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 min-h-[300px] lg:min-h-[400px]">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">{{ __('Active leads') }}</div>
                                <div class="text-2xl font-bold text-gray-900">147</div>
                                <div class="text-xs text-green-600">{{ __(':percent vs. last month', ['percent' => '+12%']) }}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">{{ __('Open quotes') }}</div>
                                <div class="text-2xl font-bold text-gray-900">23</div>
                                <div class="text-xs text-green-600">{{ __(':percent vs. last month', ['percent' => '+8%']) }}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">{{ __('Monthly revenue') }}</div>
                                <div class="text-2xl font-bold text-gray-900">24M Ft</div>
                                <div class="text-xs text-green-600">{{ __(':percent vs. last month', ['percent' => '+18%']) }}</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">{{ __('Conversion rate') }}</div>
                                <div class="text-2xl font-bold text-gray-900">32%</div>
                                <div class="text-xs text-green-600">{{ __(':percent vs. last month', ['percent' => '+5%']) }}</div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-medium text-gray-900">{{ __('Sales pipeline') }}</span>
                                <span class="text-sm text-gray-500">{{ __('Total: 89.4M HUF') }}</span>
                            </div>
                            <div class="flex gap-2 h-8">
                                <div class="bg-indigo-200 rounded flex-1 flex items-center justify-center text-xs font-medium text-indigo-800">{{ __('Lead') }}</div>
                                <div class="bg-indigo-300 rounded flex-1 flex items-center justify-center text-xs font-medium text-indigo-800">{{ __('Contact') }}</div>
                                <div class="bg-indigo-400 rounded flex-1 flex items-center justify-center text-xs font-medium text-white">{{ __('Quote') }}</div>
                                <div class="bg-indigo-500 rounded flex-1 flex items-center justify-center text-xs font-medium text-white">{{ __('Negotiation') }}</div>
                                <div class="bg-indigo-600 rounded flex-1 flex items-center justify-center text-xs font-medium text-white">{{ __('Closed') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating notifications --}}
                <div class="hidden lg:block absolute -left-4 top-1/3 bg-white rounded-lg shadow-lg p-3 border border-gray-100 animate-pulse">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ __('Deal closed!') }}</div>
                            <div class="text-xs text-gray-500">{{ __(':amount revenue', ['amount' => '+2.4M Ft']) }}</div>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block absolute -right-4 top-1/2 bg-white rounded-lg shadow-lg p-3 border border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ __('Reminder') }}</div>
                            <div class="text-xs text-gray-500">{{ __('Call back Kovács Péter') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 2. PROBLEM-SOLUTION SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Do you recognize these problems?') }}
                </h2>
                <p class="text-lg text-gray-600">{{ __('Sales doesn\'t have to be chaos') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                {{-- Problem 1 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-8 border border-orange-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Lost customer data') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Information scattered across Excel sheets, emails, and notebooks. Nobody knows when they last spoke with the customer.') }}
                    </p>
                </div>

                {{-- Problem 2 --}}
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 border border-blue-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Missed follow-ups') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Forgotten callbacks, missed quote submissions. Meanwhile, prospects turn to competitors.') }}
                    </p>
                </div>

                {{-- Problem 3 --}}
                <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-8 border border-green-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('No visibility') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Management can\'t see where salespeople stand. Monthly meetings involve guesswork, not data-driven decisions.') }}
                    </p>
                </div>
            </div>

            {{-- Solution --}}
            <div class="bg-indigo-50 rounded-2xl p-8 lg:p-12 border border-indigo-100 text-center max-w-4xl mx-auto">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">{{ __('Cégem360 CRM solves all of this') }}</h3>
                <p class="text-lg text-gray-600 mb-6">
                    {{ __('See all customers, quotes, and communications on a single interface. Automatic reminders ensure nobody is left behind. Real-time reports show where sales are headed.') }}
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <span class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ __('Central customer database') }}
                    </span>
                    <span class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ __('Automatic reminders') }}
                    </span>
                    <span class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ __('Management dashboard') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 3. FEATURES SECTION --}}
    {{-- ==================== --}}
    <section id="funkciok" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Everything you need for customer management') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Comprehensive features for optimizing sales processes and managing customer relationships.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: Contact Management --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Contact management') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('Complete customer and contact registry. All data in one place, easily searchable.') }}
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Company and contact database') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Relationship map') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Customer classification and tagging') }}
                        </li>
                    </ul>
                </div>

                {{-- Feature 2: Lead Management --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Lead management and scoring') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('Automatic lead qualification and priority assignment. Know which prospects are worth your time.') }}
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Automatic lead scoring') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Lead source tracking') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Conversion analysis') }}
                        </li>
                    </ul>
                </div>

                {{-- Feature 3: Sales Pipeline --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Sales pipeline') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('Visual pipeline with deal statuses. Drag-and-drop management, forecasting, and bottleneck identification.') }}
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Kanban view') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Revenue forecasting') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Customizable stages') }}
                        </li>
                    </ul>
                </div>

                {{-- Feature 4: Communication History --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Communication history') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('Every email, call, and note automatically linked to the customer. Full history in one click.') }}
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Email integration') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Call log') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Notes and files') }}
                        </li>
                    </ul>
                </div>

                {{-- Feature 5: Automatic Reminders --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Automatic reminders') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('Never again a forgotten callback. The system automatically reminds you of tasks and deadlines.') }}
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Task reminders') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Follow-up automation') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Email and push notifications') }}
                        </li>
                    </ul>
                </div>

                {{-- Feature 6: Reports --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Reports and analytics') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('Real-time dashboards and detailed reports. See what works and what doesn\'t – make data-driven decisions.') }}
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Sales rep performance') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Conversion funnel') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Exportable reports') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 4. INTEGRATIONS SECTION --}}
    {{-- ==================== --}}
    <section id="integraciok" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Connects with your existing tools') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Cégem360 CRM works seamlessly with your existing email, calendar, and marketing systems. No need to switch – just connect.') }}
                </p>
            </div>

            <div class="rounded-2xl bg-gray-50 p-8 lg:p-12 max-w-3xl mx-auto">
                <div class="grid grid-cols-3 gap-6">
                    {{-- Gmail --}}
                    <div class="flex h-24 lg:h-28 items-center justify-center rounded-xl bg-white p-6 shadow-sm">
                        <img src="{{ Vite::asset('resources/images/integrations/gmail.svg') }}" alt="Gmail" class="h-12 lg:h-14 w-auto">
                    </div>
                    {{-- Outlook --}}
                    <div class="flex h-24 lg:h-28 items-center justify-center rounded-xl bg-white p-6 shadow-sm">
                        <img src="{{ Vite::asset('resources/images/integrations/outlook.svg') }}" alt="Outlook" class="h-12 lg:h-14 w-auto">
                    </div>
                    {{-- Google Calendar --}}
                    <div class="flex h-24 lg:h-28 items-center justify-center rounded-xl bg-white p-6 shadow-sm">
                        <img src="{{ Vite::asset('resources/images/integrations/google-calendar.svg') }}" alt="Google Calendar" class="h-12 lg:h-14 w-auto">
                    </div>
                    {{-- Billingo --}}
                    <div class="flex h-24 lg:h-28 items-center justify-center rounded-xl bg-white p-6 shadow-sm">
                        <img src="{{ Vite::asset('resources/images/integrations/billingo.svg') }}" alt="Billingo" class="h-8 lg:h-10 w-auto">
                    </div>
                    {{-- Számlázz.hu --}}
                    <div class="flex h-24 lg:h-28 items-center justify-center rounded-xl bg-white p-6 shadow-sm">
                        <img src="{{ Vite::asset('resources/images/integrations/szamlazzhu.svg') }}" alt="Számlázz.hu" class="h-8 lg:h-10 w-auto">
                    </div>
                    {{-- Mailchimp --}}
                    <div class="flex h-24 lg:h-28 items-center justify-center rounded-xl bg-white p-6 shadow-sm">
                        <img src="{{ Vite::asset('resources/images/integrations/mailchimp.svg') }}" alt="Mailchimp" class="h-8 lg:h-10 w-auto">
                    </div>
                </div>
                <p class="text-center text-sm text-gray-500 mt-8">
                    {{ __('Email, calendar, invoicing, and marketing systems – connected in one place') }}
                </p>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 5. RESULTS SECTION --}}
    {{-- ==================== --}}
    <section id="eredmenyek" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('What our customers achieved with Cégem360 CRM') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Average improvement after 6 months of use, among our industrial and B2B customers.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6 max-w-5xl mx-auto">
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">+40%</div>
                    <div class="text-sm text-gray-600">{{ __('Number of closed deals') }}</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">-30%</div>
                    <div class="text-sm text-gray-600">{{ __('Sales cycle length') }}</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">+25%</div>
                    <div class="text-sm text-gray-600">{{ __('Lead conversion') }}</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">+35%</div>
                    <div class="text-sm text-gray-600">{{ __('Customer retention') }}</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100 col-span-2 lg:col-span-1">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">-50%</div>
                    <div class="text-sm text-gray-600">{{ __('Admin time reduction') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 6. TESTIMONIALS SECTION (hidden) --}}
    {{-- ==================== --}}
    @if(false)
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Amit ügyfeleink mondanak
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Testimonial 1 --}}
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">
                        "Korábban Excelben vezettem az ügyfeleket. Most egy kattintással látom, kivel mikor beszéltünk, és mi a következő lépés. Nem felejtek el senkit."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold">
                            KA
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Kovács Anna</div>
                            <div class="text-sm text-gray-500">Értékesítési vezető, B2B szolgáltató</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 2 --}}
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">
                        "Az értékesítőim végre látják, hol tartanak az üzleteik. A havi meetingeken nem találgatunk, hanem adatokra támaszkodunk."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-semibold">
                            SP
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Szabó Péter</div>
                            <div class="text-sm text-gray-500">Kereskedelmi igazgató, IT cég</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 3 --}}
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">
                        "A lead-scoring funkció megmutatta, melyik érdeklődőre érdemes több időt szánni. Az értékesítési hatékonyságunk 40%-kal nőtt."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-semibold">
                            NM
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Nagy Márton</div>
                            <div class="text-sm text-gray-500">Ügyvezető, Ipari beszállító</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ==================== --}}
    {{-- 7. PRICING SECTION --}}
    {{-- ==================== --}}
    <section id="arak" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Choose the plan that fits you') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Transparent pricing, no hidden costs. Cancel anytime.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Starter --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Starter</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">4 900 Ft</span>
                        <span class="text-gray-500">{{ __('/user/month') }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">{{ __('For small teams and startups.') }}</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('1,000 contacts') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Basic pipeline') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Email integration') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Basic reports') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Email support') }}
                        </li>
                    </ul>

                    <a href="https://cegem360.eu/register" class="block w-full py-3 text-center text-sm font-medium text-indigo-600 border-2 border-indigo-200 rounded-full hover:bg-indigo-50 transition-colors">
                        {{ __('Get started') }}
                    </a>
                </div>

                {{-- Professional --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-indigo-500 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="inline-block px-4 py-1 bg-indigo-500 text-white text-xs font-semibold rounded-full">
                            {{ __('Most popular') }}
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Professional</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">9 900 Ft</span>
                        <span class="text-gray-500">{{ __('/user/month') }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">{{ __('For growing teams with advanced features.') }}</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>{{ __('Unlimited contacts') }}</strong>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>{{ __('Automation') }}</strong>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>{{ __('Lead scoring') }}</strong>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Detailed reports') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Phone support') }}
                        </li>
                    </ul>

                    <a href="https://cegem360.eu/register" class="block w-full py-3 text-center text-sm font-medium text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-colors">
                        {{ __('Get started') }}
                    </a>
                </div>

                {{-- Enterprise --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Enterprise</h3>
                    <div class="mb-4">
                        <span class="text-2xl font-bold text-gray-900">{{ __('Custom pricing') }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">{{ __('For enterprises with dedicated support.') }}</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('All Professional features') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Dedicated support') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('Custom integrations') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('SLA guarantee') }}
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ __('On-premise option') }}
                        </li>
                    </ul>

                    <a href="https://cegem360.eu/kapcsolat" class="block w-full py-3 text-center text-sm font-medium text-indigo-600 border-2 border-indigo-200 rounded-full hover:bg-indigo-50 transition-colors">
                        {{ __('Request a quote') }}
                    </a>
                </div>
            </div>

            {{-- Trust badges --}}
            <div class="mt-12 text-center">
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ __('Cancel anytime') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ __('Hungarian language invoicing') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 8. FAQ SECTION --}}
    {{-- ==================== --}}
    <section id="gyik" class="py-16 lg:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Frequently asked questions') }}
                </h2>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                {{-- FAQ 1 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 1 ? null : 1"
                    >
                        <span class="font-medium text-gray-900">{{ __('How quickly can the system be set up?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Cégem360 CRM can be set up in 5 minutes. After registration, you can immediately use the basic features. Email integration and data import take an additional 15-30 minutes.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 2 ? null : 2"
                    >
                        <span class="font-medium text-gray-900">{{ __('Can I import my existing customer data?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Yes, you can easily import from Excel and CSV files. The system automatically recognizes columns and helps with mapping.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 3 ? null : 3"
                    >
                        <span class="font-medium text-gray-900">{{ __('Does it work on mobile?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Yes, Cégem360 CRM is fully responsive, so it can be comfortably used from phones and tablets.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 4 ? null : 4"
                    >
                        <span class="font-medium text-gray-900">{{ __('Is my data safe?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Yes, data is stored on EU servers in a GDPR-compliant manner. SSL encryption and regular backups.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 5 ? null : 5"
                    >
                        <span class="font-medium text-gray-900">{{ __('Can it connect with my existing systems?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Yes, Gmail, Outlook, Google Calendar, Mailchimp, Billingo, and Számlázz.hu integrations are available. Enterprise plan also includes custom API integrations.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 6 ? null : 6"
                    >
                        <span class="font-medium text-gray-900">{{ __('What happens if I cancel?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 6" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('You can cancel anytime without explanation. Your data remains accessible for 30 days with full export capability.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 7 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 7 ? null : 7"
                    >
                        <span class="font-medium text-gray-900">{{ __('Do I get help with implementation?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 7" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Yes, Hungarian language support for all plans. Professional and Enterprise: personal onboarding consultation.') }}
                        </div>
                    </div>
                </div>

                {{-- FAQ 8 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 8 ? null : 8"
                    >
                        <span class="font-medium text-gray-900">{{ __('Can I use it together with other Cégem360 modules?') }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 8 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 8" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            {{ __('Yes, seamless integration with the Controlling, Procurement, Sales, Manufacturing, and Automation modules.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 9. CTA SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-indigo-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-semibold text-white mb-4 font-heading">
                {{ __('Ready for the next level of customer management?') }}
            </h2>
            <p class="text-lg text-indigo-100 mb-8">
                {{ __('Discover how Cégem360 helps your business grow. No long-term commitment.') }}
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="https://cegem360.eu/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-indigo-600 bg-white rounded-full hover:bg-indigo-50 transition-colors shadow-lg">
                    {{ __('Get started') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="https://cegem360.eu/kapcsolat" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-colors">
                    {{ __('Book an appointment') }}
                </a>
                <a href="/login" class="inline-flex items-center justify-center gap-2 text-base font-medium text-indigo-100 transition-colors hover:text-white">
                    {{ __('Log in to the app') }} →
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-layouts.footer />
</x-layouts.app>
