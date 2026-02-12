<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Chat Sessions') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('View and manage customer chat sessions') }}</p>
        </div>
        <x-primary-button :href="route('dashboard.chat-sessions.create', ['team' => $currentTeam])" icon="plus">
            {{ __('New Chat') }}
        </x-primary-button>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            {{-- Search --}}
            <x-search-input :placeholder="__('Search chat sessions...')" :value="$search" />

            <x-filter-select wire:model.live="status" width="sm:w-40">
                <option value="">{{ __('All statuses') }}</option>
                @foreach($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}">{{ ucfirst($statusOption->value) }}</option>
                @endforeach
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
                        <x-table-header>{{ __('Customer') }}</x-table-header>
                        <x-table-header>{{ __('Agent') }}</x-table-header>
                        <x-table-header>{{ __('Messages') }}</x-table-header>
                        <x-sortable-header field="status" :$sortBy :$sortDir>{{ __('Status') }}</x-sortable-header>
                        <x-table-header>{{ __('Rating') }}</x-table-header>
                        <x-sortable-header field="last_message_at" :$sortBy :$sortDir>{{ __('Last Message') }}</x-sortable-header>
                        <x-table-header align="right">{{ __('Actions') }}</x-table-header>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($chatSessions as $session)
                        <tr wire:key="chat-{{ $session->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                @if($session->customer)
                                    <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $session->customer]) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $session->customer->name }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">{{ __('Guest') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($session->user)
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $session->user->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">{{ __('Unassigned') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $session->messages_count }}
                                    </span>
                                    @if($session->unread_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ $session->unread_count }} {{ __('unread') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :color="$session->status->badgeColor()" :label="ucfirst($session->status->value)" />
                            </td>
                            <td class="px-6 py-4">
                                @if($session->rating)
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $session->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($session->last_message_at)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $session->last_message_at->diffForHumans() }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <x-action-button :href="route('dashboard.chat-sessions.view', ['team' => $currentTeam, 'chatSession' => $session])" icon="view" :title="__('View')" />
                                    <x-action-button :href="route('dashboard.chat-sessions.edit', ['team' => $currentTeam, 'chatSession' => $session])" icon="edit" :title="__('Edit')" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No chat sessions found') }}</p>
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
        @if($chatSessions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $chatSessions->links() }}
            </div>
        @endif
    </div>

    {{-- Results info --}}
    <x-results-info :paginator="$chatSessions" />
</div>
