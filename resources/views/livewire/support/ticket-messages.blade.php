<div class="flex h-[500px] flex-col bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden"
    wire:poll.15s="refreshMessages">
    {{-- Messages --}}
    <div id="ticket-messages" class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50 dark:bg-gray-800"
        x-data="{
            scrollToBottom() {
                this.$el.scrollTop = this.$el.scrollHeight;
            }
        }" x-init="scrollToBottom()" @scroll-to-bottom.window="scrollToBottom()">

        @forelse($ticket->messages->sortBy('created_at') as $message)
            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}"
                wire:key="ticket-msg-{{ $message->id }}">
                <div
                    class="flex items-end space-x-2 max-w-2xl {{ $message->user_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                    {{-- Avatar --}}
                    <div
                        class="flex-shrink-0 w-8 h-8 rounded-full {{ $message->user_id === auth()->id() ? 'bg-blue-600' : 'bg-gray-400' }} flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($message->user?->name ?? 'U', 0, 1)) }}
                    </div>

                    {{-- Message Bubble --}}
                    <div class="flex flex-col {{ $message->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        @if ($message->is_internal_note)
                            <div class="px-4 py-3 rounded-2xl bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 border border-yellow-300 dark:border-yellow-700">
                                <div class="flex items-center space-x-1 mb-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold">{{ __('Internal Note') }}</span>
                                </div>
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $message->body }}</p>
                            </div>
                        @else
                            <div
                                class="px-4 py-3 rounded-2xl {{ $message->user_id === auth()->id()
                                    ? 'bg-blue-600 text-white rounded-br-none'
                                    : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-none shadow-md' }}">
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $message->body }}</p>
                            </div>
                        @endif
                        <div class="flex items-center space-x-2 mt-1 px-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $message->user?->name }} &middot; {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                    </path>
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No messages yet') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Start the conversation below') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Message Input --}}
    <div class="px-6 py-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
        <form wire:submit="sendMessage" class="space-y-3">
            <div class="flex items-end space-x-3">
                <div class="flex-1">
                    <textarea wire:model.live.debounce.300ms="body" rows="2" placeholder="{{ __('Type your message...') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                        @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"></textarea>
                    @error('body')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                    wire:loading.attr="disabled" wire:target="sendMessage">
                    <span wire:loading.remove wire:target="sendMessage">{{ __('Send') }}</span>
                    <span wire:loading wire:target="sendMessage">{{ __('Sending...') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <input type="checkbox" wire:model="isInternalNote"
                        class="rounded border-gray-300 dark:border-gray-600 text-yellow-500 focus:ring-yellow-500">
                    <span>{{ __('Internal note (not visible to customer)') }}</span>
                </label>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Press Enter to send, Shift+Enter for new line') }}
                </span>
            </div>
        </form>
    </div>
</div>

@script
    <script>
        const ticketId = {{ $ticket->id }};

        if (window.Echo) {
            window.Echo.private(`support.ticket.${ticketId}`)
                .listen('.ticket.message.sent', (e) => {
                    console.log('New ticket message received via Echo:', e);
                })
                .error((error) => {
                    console.error('Echo subscription error:', error);
                });
        }

        window.addEventListener('message-sent', () => {
            setTimeout(() => {
                const el = document.getElementById('ticket-messages');
                if (el) {
                    el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
                }
            }, 100);
        });

        window.addEventListener('scroll-to-bottom', () => {
            setTimeout(() => {
                const el = document.getElementById('ticket-messages');
                if (el) {
                    el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
                }
            }, 100);
        });
    </script>
@endscript
