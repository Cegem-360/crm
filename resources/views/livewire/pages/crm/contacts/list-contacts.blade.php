<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Contacts') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your customer contacts') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.contacts.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Contact') }}
        </x-primary-button>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search contacts...')" :value="$search" />

            <x-filter-select wire:model.live="primary">
                <option value="">{{ __('All contacts') }}</option>
                <option value="yes">{{ __('Primary only') }}</option>
                <option value="no">{{ __('Non-primary only') }}</option>
            </x-filter-select>

            <x-per-page-select />
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <x-sortable-header field="name" :$sortBy :$sortDir>{{ __('Name') }}</x-sortable-header>
                        <x-table-header>{{ __('Customer') }}</x-table-header>
                        <x-sortable-header field="email" :$sortBy :$sortDir>{{ __('Email') }}</x-sortable-header>
                        <x-table-header>{{ __('Phone') }}</x-table-header>
                        <x-sortable-header field="position" :$sortBy :$sortDir>{{ __('Position') }}</x-sortable-header>
                        <x-sortable-header field="is_primary" :$sortBy :$sortDir>{{ __('Primary') }}</x-sortable-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($contacts as $contact)
                        <tr wire:key="contact-{{ $contact->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('dashboard.contacts.view', ['team' => $currentTeam, 'contact' => $contact]) }}" wire:navigate class="flex items-center gap-3 group text-left">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 dark:text-purple-400 font-semibold text-sm shrink-0">
                                        {{ strtoupper(substr($contact->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 truncate">
                                            {{ $contact->name }}
                                        </p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($contact->customer)
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $contact->customer->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $contact->email }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($contact->phone)
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $contact->phone }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($contact->position)
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $contact->position }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($contact->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                        {{ __('Yes') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-400">
                                        {{ __('No') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.contacts.view', ['team' => $currentTeam, 'contact' => $contact])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.contacts.edit', ['team' => $currentTeam, 'contact' => $contact])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No contacts found') }}</p>
                                    @if($search)
                                        <button wire:click="$set('search', '')" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ __('Clear search') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($contacts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$contacts" />

</div>
