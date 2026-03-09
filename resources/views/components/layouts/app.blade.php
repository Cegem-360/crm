<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/favicon.ico" sizes="any">

        <title>{{ $title ?? config('app.name') . ' – ' . __('Customer relationship management and sales pipeline') }}
        </title>
        <meta name="description"
            content="{{ $description ?? __('Track your customers from first contact to closing the deal. CRM system for businesses: lead management, pipeline, automatic reminders. Try it free for 14 days!') }}">

        {{-- Open Graph --}}
        <meta property="og:title"
            content="{{ $ogTitle ?? config('app.name') . ' – ' . __('Customer relationship management') }}">
        <meta property="og:description"
            content="{{ $ogDescription ?? __('Sales pipeline, lead management, automation – all in one place. +40% more closed deals.') }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('images/crm-og-image.png') }}">
        <meta property="og:url" content="{{ url()->current() }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
            rel="stylesheet">

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

    <body class="antialiased" x-data="{ mobileMenuOpen: false }">
        <x-layouts.navbar />

        {{ $slot }}

        {{-- Floating Bug Report Button --}}
        @auth
            <a href="{{ route('filament.admin.resources.bug-reports.create', ['tenant' => auth()->user()->teams()->first()?->slug]) }}"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-2 rounded-full bg-orange-600 px-4 py-3 text-sm font-medium text-white shadow-lg transition-all hover:bg-orange-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                title="{{ __('Report a Bug') }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                    </path>
                </svg>
                <span class="hidden sm:inline">{{ __('Report a Bug') }}</span>
            </a>
        @endauth

        @livewire('notifications')

        @filamentScripts

    </body>

</html>
