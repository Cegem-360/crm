<div class="flex h-[600px] flex-col bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden">
    {{-- Chat Header --}}
    <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 dark:from-purple-700 dark:to-purple-800 text-white">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold">{{ __('AI Assistant') }}</h2>
                <p class="text-sm text-purple-200">{{ __('Powered by AI') }}</p>
            </div>
        </div>
        <button wire:click="startNewConversation"
            class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-colors text-sm font-medium"
            title="{{ __('New Conversation') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
    </div>

    {{-- Messages --}}
    <div id="ai-chat-messages" class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gray-50 dark:bg-gray-800"
        x-data="{
            scrollToBottom() {
                this.$el.scrollTop = this.$el.scrollHeight;
            }
        }" x-init="scrollToBottom()" @scroll-to-bottom.window="scrollToBottom()">

        @if (count($messages) === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-purple-300 dark:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('AI Assistant') }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Ask me anything about your CRM, customers, or tasks.') }}</p>
            </div>
        @endif

        @foreach ($messages as $index => $msg)
            <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}"
                wire:key="ai-msg-{{ $index }}">
                <div class="flex items-end space-x-2 max-w-2xl {{ $msg['role'] === 'user' ? 'flex-row-reverse space-x-reverse' : '' }}">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $msg['role'] === 'user' ? 'bg-blue-600' : 'bg-purple-600' }} flex items-center justify-center text-white text-xs font-bold">
                        @if ($msg['role'] === 'user')
                            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                        @else
                            AI
                        @endif
                    </div>

                    {{-- Message Bubble --}}
                    <div class="flex flex-col {{ $msg['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div class="px-4 py-3 rounded-2xl {{ $msg['role'] === 'user'
                            ? 'bg-blue-600 text-white rounded-br-none'
                            : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-none shadow-md' }}">
                            @if ($msg['role'] === 'assistant')
                                <div class="prose dark:prose-invert prose-sm max-w-none">{!! str($msg['content'])->markdown() !!}</div>
                            @else
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $msg['content'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Loading indicator --}}
        @if ($isLoading)
            <div class="flex justify-start">
                <div class="flex items-end space-x-2">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white text-xs font-bold">
                        AI
                    </div>
                    <div class="px-4 py-3 rounded-2xl bg-white dark:bg-gray-700 rounded-bl-none shadow-md">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Message Input --}}
    <div class="px-6 py-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
        <form wire:submit="sendMessage" class="flex items-end space-x-3">
            <div class="flex-1">
                <textarea wire:model="message" rows="2" placeholder="{{ __('Ask the AI assistant...') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                    @keydown.enter.prevent="if(!$event.shiftKey) { $wire.sendMessage(); }"
                    @if($isLoading) disabled @endif></textarea>
                @error('message')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                wire:loading.attr="disabled" wire:target="sendMessage"
                @if($isLoading) disabled @endif>
                <span wire:loading.remove wire:target="sendMessage">{{ __('Send') }}</span>
                <span wire:loading wire:target="sendMessage">{{ __('Thinking...') }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </form>
        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            {{ __('Press Enter to send, Shift+Enter for new line') }}
        </div>
    </div>
</div>
