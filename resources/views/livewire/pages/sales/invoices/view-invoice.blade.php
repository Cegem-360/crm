<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.invoices') }}" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ $invoice->invoice_number }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Invoice details') }}</p>
            </div>
        </div>
        <a href="{{ route('dashboard.invoices.edit', $invoice) }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ __('Edit') }}
        </a>
    </div>

    {{-- Invoice details --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Invoice Information') }}</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Invoice Number') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Customer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($invoice->customer)
                            <a href="{{ route('dashboard.customers.view', $invoice->customer) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $invoice->customer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Order') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($invoice->order)
                            <a href="{{ route('dashboard.orders.view', $invoice->order) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $invoice->order->order_number }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($invoice->total, 0, ',', ' ') }} Ft</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                    <dd class="mt-1">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                'active' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                'paid' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$invoice->status->value] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $invoice->status->getLabel() }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Issue Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->issue_date?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Due Date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $invoice->due_date?->isPast() && $invoice->status->value !== 'paid' ? 'text-red-500 dark:text-red-400' : '' }}">
                        {{ $invoice->due_date?->format('Y-m-d') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->created_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Related payments --}}
    @if($invoice->payments->count() > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Payments') }} ({{ $invoice->payments->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Method') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->payment_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white text-right">{{ number_format($payment->amount, 0, ',', ' ') }} Ft</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $payment->payment_method ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
