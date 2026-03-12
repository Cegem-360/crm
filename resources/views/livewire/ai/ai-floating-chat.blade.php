<div
    x-data="{ open: @entangle('isOpen') }"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3"
>
    {{-- Chat Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="w-96 h-[32rem] flex flex-col rounded-2xl shadow-2xl border border-gray-200/70 dark:border-gray-700/60 bg-white dark:bg-gray-900 overflow-hidden"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-linear-to-r from-primary-600 to-primary-700 text-white shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold leading-tight">{{ __('AI Assistant') }}</p>
                    <p class="text-xs text-white/70 leading-tight">{{ __('Powered by CRM data') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button wire:click="startNewConversation" title="{{ __('New conversation') }}"
                    class="p-1.5 rounded-lg hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
                <button @click="open = false" title="{{ __('Close') }}"
                    class="p-1.5 rounded-lg hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div
            id="floating-chat-messages"
            class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-gray-50 dark:bg-gray-900/50"
            x-data="{
                scrollToBottom() {
                    this.$el.scrollTop = this.$el.scrollHeight;
                }
            }"
            x-init="scrollToBottom()"
            @floating-chat-scroll-bottom.window="scrollToBottom()"
        >
            @forelse ($messages as $index => $msg)
                <div @class([
                    'flex',
                    'justify-end' => $msg['role'] === 'user',
                    'justify-start' => $msg['role'] !== 'user',
                ]) wire:key="fchat-msg-{{ $index }}">
                    <div @class([
                        'flex items-end gap-1.5 max-w-[85%]',
                        'flex-row-reverse' => $msg['role'] === 'user',
                    ])>
                        @if ($msg['role'] !== 'user')
                            <div class="shrink-0 w-6 h-6 rounded-full bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-sm mb-0.5">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                </svg>
                            </div>
                        @endif
                        <div @class([
                            'px-3 py-2 rounded-2xl text-sm',
                            'bg-primary-600 text-white rounded-br-sm' => $msg['role'] === 'user',
                            'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700/60 rounded-bl-sm shadow-sm' => $msg['role'] !== 'user',
                        ])>
                            @if ($msg['role'] === 'assistant')
                                <div class="prose dark:prose-invert prose-sm max-w-none prose-p:my-1 prose-ul:my-1 prose-li:my-0">{!! str($msg['content'])->markdown()->sanitizeHtml() !!}</div>
                            @else
                                <p class="whitespace-pre-wrap wrap-break-word">{{ $msg['content'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center py-8">
                    <div class="w-10 h-10 rounded-xl bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-md mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('CRM AI Assistant') }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Ask about customers, tasks, or opportunities.') }}</p>
                </div>
            @endforelse

            {{-- Streaming indicator --}}
            @if ($isLoading)
                <div class="flex justify-start">
                    <div class="flex items-end gap-1.5">
                        <div class="shrink-0 w-6 h-6 rounded-full bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-sm mb-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                        </div>
                        <div class="px-3 py-2.5 rounded-2xl rounded-bl-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 shadow-sm">
                            <div id="floating-chat-stream-target" wire:stream="floating-chat-stream-target"
                                class="prose dark:prose-invert prose-sm max-w-none text-gray-900 dark:text-gray-100 prose-p:my-1">
                                <span class="inline-flex gap-1 items-center">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></span>
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input --}}
        <div class="px-3 py-2.5 bg-white dark:bg-gray-900 border-t border-gray-200/80 dark:border-gray-700/60 shrink-0">
            <form wire:submit="sendMessage" class="flex items-end gap-2">
                <textarea
                    wire:model="message"
                    rows="1"
                    placeholder="{{ __('Ask about customers, tasks...') }}"
                    class="flex-1 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 resize-none bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-shadow"
                    wire:loading.attr="disabled" wire:target="sendMessage"
                    @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"
                    style="max-height: 100px; overflow-y: auto;"
                    x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 100) + 'px';"
                ></textarea>
                <button type="submit"
                    class="shrink-0 w-9 h-9 flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors disabled:opacity-40 disabled:cursor-not-allowed shadow-sm"
                    wire:loading.attr="disabled" wire:target="sendMessage">
                    <svg wire:loading.remove wire:target="sendMessage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    <svg wire:loading wire:target="sendMessage" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Toggle Button --}}
    <button
        wire:click="toggle"
        class="w-14 h-14 rounded-full bg-primary-600 hover:bg-primary-700 text-white shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center relative"
        :title="open ? '{{ __('Close AI Assistant') }}' : '{{ __('Open AI Assistant') }}'"
    >
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
        </svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
