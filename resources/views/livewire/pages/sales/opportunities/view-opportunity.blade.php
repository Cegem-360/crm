<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.opportunities', ['team' => $currentTeam]) }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $opportunity->title }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Opportunity details') }}</p>
            </div>
        </div>
        <x-primary-button :href="route('dashboard.opportunities.edit', ['team' => $currentTeam, 'opportunity' => $opportunity])" icon="edit">
            {{ __('Edit') }}
        </x-primary-button>
    </div>

    {{-- Opportunity details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Opportunity Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Title') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $opportunity->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($opportunity->customer)
                            <a href="{{ route('dashboard.customers.view', ['team' => $currentTeam, 'customer' => $opportunity->customer]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $opportunity->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Value') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ Number::currency($opportunity->value, 'HUF', 'hu', 0) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Probability') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $opportunity->probability }}%</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Stage') }}</dt>
                    <dd class="mt-1">
                        @php
                            $stageColors = [
                                'lead' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                'qualified' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'proposal' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'negotiation' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                'sended_quotation' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'lost_quotation' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stageColors[$opportunity->stage->value] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $opportunity->stage->getLabel() }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Expected Close Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $opportunity->expected_close_date?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Assigned User') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $opportunity->assignedUser?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $opportunity->created_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Related quotes --}}
    @if($opportunity->quotes->count() > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Quotes') }} ({{ $opportunity->quotes->count() }})</h2>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($opportunity->quotes as $quote)
                    <li>
                        <a href="{{ route('dashboard.quotes.view', ['team' => $currentTeam, 'quote' => $quote]) }}" wire:navigate class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote->quote_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Number::currency($quote->total, 'HUF', 'hu', 0) }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quote->status->value === 'accepted' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-400' }}">
                                {{ ucfirst($quote->status->value) }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
