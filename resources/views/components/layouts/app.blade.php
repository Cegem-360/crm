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
        @vite(['resources/js/app.js'])

    </head>

    <body class="antialiased" x-data="{ mobileMenuOpen: false }">
        <x-layouts.navbar />

        {{ $slot }}

        @livewire('notifications')

        @filamentScripts

    </body>

</html>
