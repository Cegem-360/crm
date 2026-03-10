<div x-data="{ sidebarOpen: @entangle('showSidebar') }"
    class="flex h-[calc(100vh-12rem)] min-h-96 rounded-lg overflow-hidden bg-white dark:bg-gray-900 shadow-xl border border-gray-200/70 dark:border-gray-700/60">
    {{-- Sidebar - Previous Conversations --}}
    <div
        class="hidden md:flex flex-col border-r border-gray-200/80 dark:border-gray-700/60 bg-gray-50 dark:bg-gray-800 overflow-hidden transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'w-64' : 'w-0 border-r-0'">
            {{-- Sidebar Header --}}
            <div
                class="flex items-center justify-between px-3 py-2.5 border-b border-gray-200/80 dark:border-gray-700/60">
                <span
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Conversations') }}</span>
                <button wire:click="startNewConversation" title="{{ __('New Conversation') }}"
                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200/60 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>

            {{-- Token Usage --}}
            @php($tokenUsage = $this->tokenUsage)
            @if ($tokenUsage['limit'] > 0)
                <div class="px-3 py-2.5 border-b border-gray-200/80 dark:border-gray-700/60">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Monthly tokens') }}</span>
                        <span
                            class="text-xs font-medium {{ $tokenUsage['percentage'] >= 90 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ number_format($tokenUsage['used']) }} / {{ number_format($tokenUsage['limit']) }}
                        </span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all {{ $tokenUsage['percentage'] >= 90 ? 'bg-red-500' : ($tokenUsage['percentage'] >= 70 ? 'bg-yellow-500' : 'bg-primary-500') }}"
                            style="width: {{ $tokenUsage['percentage'] }}%"></div>
                    </div>
                </div>
            @endif

            {{-- Conversation List --}}
            <div class="flex-1 overflow-y-auto">
                @forelse ($this->conversations as $conversation)
                    <button wire:click="loadConversation('{{ $conversation->id }}')"
                        wire:key="conv-{{ $conversation->id }}"
                        class="group w-full flex items-center gap-2 px-3 py-2.5 text-left text-sm transition-colors
                            {{ $conversationId === $conversation->id
                                ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300'
                                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>
                        <span class="truncate flex-1">{{ $conversation->title ?: __('Untitled') }}</span>
                        <button wire:click.stop="deleteConversation('{{ $conversation->id }}')"
                            class="hidden group-hover:block p-0.5 rounded text-gray-400 hover:text-red-500 transition-colors"
                            title="{{ __('Delete') }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </button>
                @empty
                    <div class="px-3 py-6 text-center">
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('No previous conversations') }}</p>
                    </div>
                @endforelse
            </div>
    </div>

    {{-- Main Chat Area --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- macOS Title Bar --}}
        <div
            class="flex items-center px-4 py-2.5 bg-linear-to-b from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900 border-b border-gray-200/80 dark:border-gray-700/60 select-none">
            {{-- Toggle Sidebar + Traffic Lights --}}
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" title="{{ __('Toggle sidebar') }}"
                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200/60 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <button wire:click="startNewConversation" title="{{ __('New Conversation') }}"
                        class="group relative w-3 h-3 rounded-full bg-[#FF5F57] shadow-[inset_0_0_0_0.5px_rgba(0,0,0,0.12)] hover:bg-[#FF5F57]/90 transition-colors">
                        <svg class="absolute inset-0 w-3 h-3 text-[#4D0000] opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M3.5 3.5l5 5M8.5 3.5l-5 5" />
                        </svg>
                    </button>
                    <div class="w-3 h-3 rounded-full bg-[#FEBC2E] shadow-[inset_0_0_0_0.5px_rgba(0,0,0,0.12)]"></div>
                    <div class="w-3 h-3 rounded-full bg-[#28C840] shadow-[inset_0_0_0_0.5px_rgba(0,0,0,0.12)]"></div>
                </div>
            </div>

            {{-- Window Title --}}
            <div class="flex-1 text-center">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('AI Assistant') }}</span>
            </div>

            {{-- Model Selector --}}
            <div class="flex items-center">
                <select wire:model.live="selectedModel"
                    class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-transparent border-0 focus:ring-0 cursor-pointer pr-6 py-0">
                    @foreach (\App\Livewire\Ai\AiChatInterface::availableModels() as $modelValue => $modelLabel)
                        <option value="{{ $modelValue }}">{{ $modelLabel }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Messages --}}
        <div id="ai-chat-messages" class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-white dark:bg-gray-900"
            x-data="{
                scrollToBottom() {
                    this.$el.scrollTop = this.$el.scrollHeight;
                }
            }" x-init="scrollToBottom()" @scroll-to-bottom.window="scrollToBottom()">

            @if (count($messages) === 0)
                <div class="flex flex-col items-center justify-center h-full text-center py-12">
                    <div
                        class="w-14 h-14 rounded-2xl bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('AI Assistant') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                        {{ __('Ask me anything about your CRM, customers, or tasks.') }}</p>
                </div>
            @endif

            @foreach ($messages as $index => $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                    wire:key="ai-msg-{{ $index }}">
                    <div
                        class="flex items-start gap-2.5 max-w-2xl {{ $msg['role'] === 'user' ? 'flex-row-reverse' : '' }}">
                        {{-- Avatar --}}
                        @if ($msg['role'] === 'user')
                            <div
                                class="shrink-0 w-7 h-7 rounded-full bg-primary-600 flex items-center justify-center text-white text-xs font-medium shadow-sm">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                            </div>
                        @else
                            <div
                                class="shrink-0 w-7 h-7 rounded-full bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-sm">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                </svg>
                            </div>
                        @endif

                        {{-- Message Bubble --}}
                        <div class="flex flex-col {{ $msg['role'] === 'user' ? 'items-end' : 'items-start' }}">
                            <div
                                class="px-3.5 py-2 rounded-2xl {{ $msg['role'] === 'user'
                                    ? 'bg-primary-600 text-white rounded-tr-md'
                                    : 'bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-tl-md border border-gray-200 dark:border-gray-700/60' }}">
                                @if ($msg['role'] === 'assistant')
                                    <div class="prose dark:prose-invert prose-sm max-w-none">{!! str($msg['content'])->markdown()->sanitizeHtml() !!}
                                    </div>
                                @else
                                    <p class="text-sm whitespace-pre-wrap wrap-break-word">{{ $msg['content'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Streaming / Loading indicator --}}
            @if ($isLoading)
                <div class="flex justify-start">
                    <div class="flex items-start gap-2.5 max-w-2xl">
                        <div
                            class="shrink-0 w-7 h-7 rounded-full bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-sm">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                        </div>
                        <div class="flex flex-col items-start">
                            <div
                                class="px-3.5 py-2 rounded-2xl rounded-tl-md bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60">
                                <div id="ai-stream-target" wire:stream="ai-stream-target"
                                    class="prose dark:prose-invert prose-sm max-w-none text-gray-900 dark:text-gray-100"
                                    x-data="{ observer: null }" x-init="observer = new MutationObserver(() => {
                                        $el.closest('#ai-chat-messages')?.scrollTo({ top: $el.closest('#ai-chat-messages').scrollHeight, behavior: 'smooth' });
                                    });
                                    observer.observe($el, { childList: true, characterData: true, subtree: true });"
                                    x-destroy="observer?.disconnect()">
                                    <span class="inline-flex gap-1">
                                        <span
                                            class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce"
                                            style="animation-delay: 0ms;"></span>
                                        <span
                                            class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce"
                                            style="animation-delay: 150ms;"></span>
                                        <span
                                            class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce"
                                            style="animation-delay: 300ms;"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Message Input --}}
        <div
            class="px-4 py-3 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-200/80 dark:border-gray-700/60">
            <form wire:submit="sendMessage" class="flex items-end gap-3">
                <div class="flex-1">
                    <textarea wire:model="message" rows="2" placeholder="{{ __('Ask the AI assistant...') }}"
                        class="w-full px-3.5 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 resize-none bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm transition-shadow"
                        @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"
                        @if ($isLoading) disabled @endif></textarea>
                </div>
                <button type="submit"
                    class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed shadow-sm flex items-center gap-2 text-sm font-medium"
                    wire:loading.attr="disabled" wire:target="sendMessage"
                    @if ($isLoading) disabled @endif>
                    <svg wire:loading.remove wire:target="sendMessage" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    <svg wire:loading wire:target="sendMessage" class="w-4 h-4 animate-spin" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="sendMessage">{{ __('Send') }}</span>
                    <span wire:loading wire:target="sendMessage">{{ __('Sending...') }}</span>
                </button>
            </form>
            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                {{ __('Press Enter to send, Shift+Enter for new line') }}
            </p>
        </div>
    </div>
</div>
