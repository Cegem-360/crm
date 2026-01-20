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
                    Új: Automatikus lead-scoring AI-val
                </div>

                {{-- H1 --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-gray-900 mb-6 font-heading leading-tight">
                    Kezelje ügyfeleit az első kapcsolattól a lezárt üzletig
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Minden ügyfél, ajánlat és kommunikáció egy helyen. Automatikus emlékeztetők, értékesítési pipeline és részletes előzmények – hogy csapata a lezárásra koncentrálhasson, ne az adminisztrációra.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="/admin" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-xl">
                        Próbálja ki 14 napig ingyen
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-indigo-700 bg-white border-2 border-indigo-200 rounded-full hover:bg-indigo-50 transition-colors">
                        Demó kérése
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Bankkártya nélkül indíthat
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        5 perc alatt beüzemelhető
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Magyar nyelvű támogatás
                    </span>
                </div>
            </div>

            {{-- Hero Image/Dashboard Preview --}}
            <div class="mt-16 relative">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 max-w-5xl mx-auto">
                    {{-- Dashboard mockup --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 min-h-[300px] lg:min-h-[400px]">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">Aktív leadek</div>
                                <div class="text-2xl font-bold text-gray-900">147</div>
                                <div class="text-xs text-green-600">+12% vs. előző hónap</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">Nyitott ajánlatok</div>
                                <div class="text-2xl font-bold text-gray-900">23</div>
                                <div class="text-xs text-green-600">+8% vs. előző hónap</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">Havi bevétel</div>
                                <div class="text-2xl font-bold text-gray-900">24M Ft</div>
                                <div class="text-xs text-green-600">+18% vs. előző hónap</div>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <div class="text-sm text-gray-500 mb-1">Konverziós ráta</div>
                                <div class="text-2xl font-bold text-gray-900">32%</div>
                                <div class="text-xs text-green-600">+5% vs. előző hónap</div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-medium text-gray-900">Értékesítési pipeline</span>
                                <span class="text-sm text-gray-500">Összesen: 89,4M Ft</span>
                            </div>
                            <div class="flex gap-2 h-8">
                                <div class="bg-indigo-200 rounded flex-1 flex items-center justify-center text-xs font-medium text-indigo-800">Lead</div>
                                <div class="bg-indigo-300 rounded flex-1 flex items-center justify-center text-xs font-medium text-indigo-800">Kapcsolat</div>
                                <div class="bg-indigo-400 rounded flex-1 flex items-center justify-center text-xs font-medium text-white">Ajánlat</div>
                                <div class="bg-indigo-500 rounded flex-1 flex items-center justify-center text-xs font-medium text-white">Tárgyalás</div>
                                <div class="bg-indigo-600 rounded flex-1 flex items-center justify-center text-xs font-medium text-white">Lezárva</div>
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
                            <div class="text-sm font-medium text-gray-900">Üzlet lezárva!</div>
                            <div class="text-xs text-gray-500">+2.4M Ft bevétel</div>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block absolute -right-4 top-1/2 bg-white rounded-lg shadow-lg p-3 border border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">Emlékeztető</div>
                            <div class="text-xs text-gray-500">Hívja vissza Kovács Pétert</div>
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
                    Ismeri ezeket a problémákat?
                </h2>
                <p class="text-lg text-gray-600">Az értékesítés nem kell, hogy káosz legyen</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                {{-- Problem 1 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-8 border border-orange-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Elveszett ügyféladatok</h3>
                    <p class="text-gray-600">
                        Az információk Excel-táblákban, e-mailekben és jegyzetfüzetekben szétszórva. Senki sem tudja, mikor beszéltek utoljára az ügyféllel.
                    </p>
                </div>

                {{-- Problem 2 --}}
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 border border-blue-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Lemaradt követések</h3>
                    <p class="text-gray-600">
                        Elfelejtett visszahívások, elmaradt ajánlatküldések. Az érdeklődők közben a versenytársakhoz fordulnak.
                    </p>
                </div>

                {{-- Problem 3 --}}
                <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-8 border border-green-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Nincs átláthatóság</h3>
                    <p class="text-gray-600">
                        A vezetőség nem látja, hol tartanak az értékesítők. A havi meetingeken találgatás van, nem adatok alapján döntenek.
                    </p>
                </div>
            </div>

            {{-- Solution --}}
            <div class="bg-indigo-50 rounded-2xl p-8 lg:p-12 border border-indigo-100 text-center max-w-4xl mx-auto">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">A Cégem360 CRM mindezt megoldja</h3>
                <p class="text-lg text-gray-600 mb-6">
                    Egyetlen felületen látja az összes ügyfelet, ajánlatot és kommunikációt. Automatikus emlékeztetők gondoskodnak róla, hogy senki ne maradjon ki. Valós idejű riportok mutatják, merre tart az értékesítés.
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <span class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Központi ügyféladatbázis
                    </span>
                    <span class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Automatikus emlékeztetők
                    </span>
                    <span class="flex items-center gap-2 text-gray-700">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Vezetői dashboard
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
                    Minden, amire szüksége van az ügyfélkezeléshez
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Átfogó funkciók az értékesítési folyamatok optimalizálásához és az ügyfélkapcsolatok kezeléséhez.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: Contact Management --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Kapcsolatkezelés</h3>
                    <p class="text-gray-600 mb-4">
                        Teljes körű ügyfél- és kapcsolattartó nyilvántartás. Minden adat egy helyen, könnyen kereshető formában.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Cég és kontakt adatbázis
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Kapcsolati térkép
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Ügyfélosztályozás és címkézés
                        </li>
                    </ul>
                </div>

                {{-- Feature 2: Lead Management --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Lead-kezelés és scoring</h3>
                    <p class="text-gray-600 mb-4">
                        Automatikus lead-minősítés és prioritás meghatározás. Tudja, melyik érdeklődőre érdemes időt fordítani.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Automatikus lead-scoring
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Lead-forrás követés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Konverziós elemzés
                        </li>
                    </ul>
                </div>

                {{-- Feature 3: Sales Pipeline --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Értékesítési pipeline</h3>
                    <p class="text-gray-600 mb-4">
                        Vizuális pipeline az üzletek státuszával. Drag-and-drop kezelés, előrejelzés és szűk keresztmetszetek azonosítása.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Kanban nézet
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Bevétel előrejelzés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Testreszabható szakaszok
                        </li>
                    </ul>
                </div>

                {{-- Feature 4: Communication History --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Kommunikációs előzmények</h3>
                    <p class="text-gray-600 mb-4">
                        Minden e-mail, hívás és jegyzet automatikusan az ügyfélhez rendelve. Teljes előzmény egy kattintásra.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            E-mail integráció
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Hívásnapló
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Jegyzetek és fájlok
                        </li>
                    </ul>
                </div>

                {{-- Feature 5: Automatic Reminders --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Automatikus emlékeztetők</h3>
                    <p class="text-gray-600 mb-4">
                        Soha többé elfelejtett visszahívás. A rendszer automatikusan emlékeztet a feladatokra és határidőkre.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Feladat emlékeztetők
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Follow-up automatizálás
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            E-mail és push értesítések
                        </li>
                    </ul>
                </div>

                {{-- Feature 6: Reports --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Riportok és elemzések</h3>
                    <p class="text-gray-600 mb-4">
                        Valós idejű dashboard-ok és részletes riportok. Lássa, mi működik és mi nem – adatok alapján döntsön.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Értékesítői teljesítmény
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Konverziós tölcsér
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Exportálható riportok
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
                    Összekapcsolható a meglévő eszközeivel
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    A Cégem360 CRM zökkenőmentesen együttműködik a már használt e-mail, naptár és marketing rendszereivel. Nem kell váltania – csak összekötni.
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
                    E-mail, naptár, számlázás és marketing rendszerek – egy helyen összekötve
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
                    Amit ügyfeleink elértek a Cégem360 CRM-mel
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Átlagos javulás 6 hónap használat után, ipari és B2B ügyfeleink körében.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6 max-w-5xl mx-auto">
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">+40%</div>
                    <div class="text-sm text-gray-600">Lezárt üzletek száma</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">-30%</div>
                    <div class="text-sm text-gray-600">Értékesítési ciklus hossza</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">+25%</div>
                    <div class="text-sm text-gray-600">Lead-konverzió</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">+35%</div>
                    <div class="text-sm text-gray-600">Ügyfél-visszatérés</div>
                </div>
                <div class="bg-white rounded-xl p-6 text-center shadow-sm border border-gray-100 col-span-2 lg:col-span-1">
                    <div class="text-3xl lg:text-4xl font-bold text-indigo-600 mb-2">-50%</div>
                    <div class="text-sm text-gray-600">Admin-idő csökkenése</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 6. TESTIMONIALS SECTION --}}
    {{-- ==================== --}}
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

    {{-- ==================== --}}
    {{-- 7. PRICING SECTION --}}
    {{-- ==================== --}}
    <section id="arak" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Válassza ki az Önhöz illő csomagot
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Minden csomag 14 napos ingyenes próbaidőszakkal indul. Bankkártya nélkül.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Starter --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Starter</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">4 900 Ft</span>
                        <span class="text-gray-500">/felhasználó/hó</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Kisebb csapatoknak és induló vállalkozásoknak.</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            1 000 kontakt
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Alap pipeline
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            E-mail integráció
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Alap riportok
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            E-mail támogatás
                        </li>
                    </ul>

                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-indigo-600 border-2 border-indigo-200 rounded-full hover:bg-indigo-50 transition-colors">
                        Ingyenes próba
                    </a>
                </div>

                {{-- Professional --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-indigo-500 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="inline-block px-4 py-1 bg-indigo-500 text-white text-xs font-semibold rounded-full">
                            Legnépszerűbb
                        </span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Professional</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">9 900 Ft</span>
                        <span class="text-gray-500">/felhasználó/hó</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Növekvő csapatoknak fejlett funkciókkal.</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>Korlátlan kontakt</strong>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>Automatizálás</strong>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <strong>Lead-scoring</strong>
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Részletes riportok
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Telefonos támogatás
                        </li>
                    </ul>

                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-colors">
                        Ingyenes próba
                    </a>
                </div>

                {{-- Enterprise --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Enterprise</h3>
                    <div class="mb-4">
                        <span class="text-2xl font-bold text-gray-900">Egyedi árazás</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-6">Nagyvállalatoknak dedikált támogatással.</p>

                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Minden Professional funkció
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Dedikált support
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Custom integrációk
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            SLA garancia
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            On-premise lehetőség
                        </li>
                    </ul>

                    <a href="#" class="block w-full py-3 text-center text-sm font-medium text-indigo-600 border-2 border-indigo-200 rounded-full hover:bg-indigo-50 transition-colors">
                        Ajánlat kérése
                    </a>
                </div>
            </div>

            {{-- Trust badges --}}
            <div class="mt-12 text-center">
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        14 napos ingyenes próbaidőszak
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Bármikor lemondható
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Magyar nyelvű számlázás
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
                    Gyakran ismételt kérdések
                </h2>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                {{-- FAQ 1 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 1 ? null : 1"
                    >
                        <span class="font-medium text-gray-900">Mennyi idő alatt üzemelhető be a rendszer?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            A Cégem360 CRM 5 perc alatt üzembe helyezhető. Regisztráció után azonnal használhatja az alapfunkciókat. Az e-mail integráció és az adatok importálása további 15-30 percet vesz igénybe.
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 2 ? null : 2"
                    >
                        <span class="font-medium text-gray-900">Importálhatom a meglévő ügyfél adataimat?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, Excel és CSV fájlokból egyszerűen importálhat. A rendszer automatikusan felismeri az oszlopokat és segít a megfeleltetésben.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 3 ? null : 3"
                    >
                        <span class="font-medium text-gray-900">Működik mobilon is?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, a Cégem360 CRM teljes mértékben reszponzív, így telefonról és tabletről is kényelmesen használható.
                        </div>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 4 ? null : 4"
                    >
                        <span class="font-medium text-gray-900">Biztonságban vannak az adataim?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, az adatok EU-n belüli szervereken tárolódnak, GDPR-kompatibilis módon. SSL titkosítás és rendszeres biztonsági mentések.
                        </div>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 5 ? null : 5"
                    >
                        <span class="font-medium text-gray-900">Összeköthető a meglévő rendszereimmel?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, Gmail, Outlook, Google Calendar, Mailchimp, Billingo és Számlázz.hu integrációk elérhetők. Enterprise csomagban egyedi API integrációk is.
                        </div>
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 6 ? null : 6"
                    >
                        <span class="font-medium text-gray-900">Mi történik, ha felmondok?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 6" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Bármikor, indoklás nélkül lemondhatja. 30 napig még elérheti az adatait, teljes export lehetőséggel.
                        </div>
                    </div>
                </div>

                {{-- FAQ 7 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 7 ? null : 7"
                    >
                        <span class="font-medium text-gray-900">Kapok segítséget a bevezetéshez?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 7" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, magyar nyelvű támogatás minden csomaghoz. Professional és Enterprise: személyes onboarding konzultáció.
                        </div>
                    </div>
                </div>

                {{-- FAQ 8 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <button
                        class="w-full px-6 py-4 text-left flex items-center justify-between"
                        @click="openFaq = openFaq === 8 ? null : 8"
                    >
                        <span class="font-medium text-gray-900">Használhatom a többi Cégem360 modullal együtt?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': openFaq === 8 }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 8" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, zökkenőmentes integráció a Kontrolling, Beszerzés, Értékesítés, Gyártásirányítás és Automatizálás modulokkal.
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
                Készen áll az ügyfélkezelés új szintjére?
            </h2>
            <p class="text-lg text-indigo-100 mb-8">
                Indítsa el 14 napos ingyenes próbaidőszakát most, és fedezze fel, hogyan növelheti az értékesítési hatékonyságot a Cégem360 CRM-mel.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="/admin" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-indigo-600 bg-white rounded-full hover:bg-indigo-50 transition-colors shadow-lg">
                    Ingyenes próba indítása
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-colors">
                    Demó időpontot kérek
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-indigo-100">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    5 perc alatt beüzemelhető
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Bankkártya nélkül indíthat
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Magyar nyelvű támogatás
                </span>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-layouts.footer />
</x-layouts.app>
