<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/favicon.ico" sizes="any">

        <title>{{ $title ?? config('app.name') . ' – Ügyfélkapcsolat-kezelés és értékesítési pipeline' }}</title>
        <meta name="description" content="{{ $description ?? 'Kövesse nyomon ügyfeleit az első megkeresésétől a szerződéskötésig. CRM rendszer ipari cégeknek: lead-kezelés, pipeline, automatikus emlékeztetők. Próbálja ki 14 napig ingyen!' }}">

        {{-- Open Graph --}}
        <meta property="og:title" content="{{ $ogTitle ?? config('app.name') . ' – Ügyfélkapcsolat-kezelés' }}">
        <meta property="og:description" content="{{ $ogDescription ?? 'Értékesítési pipeline, lead-kezelés, automatizálás – egy helyen. +40% több lezárt üzlet.' }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('images/crm-og-image.png') }}">
        <meta property="og:url" content="{{ url()->current() }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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

        @livewire('notifications')

        @filamentScripts

    </body>

</html>
