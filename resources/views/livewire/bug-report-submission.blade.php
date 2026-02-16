<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        @if($submitted)
            {{-- Success Message --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-6">
                    <svg class="h-10 w-10 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Bug Report Submitted Successfully!') }}</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    {{ __('Thank you for reporting this issue. Our team will investigate and fix it as soon as possible.') }}
                </p>
                <button
                    wire:click="$set('submitted', false)"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    {{ __('Submit Another Report') }}
                </button>
            </div>
        @else
            {{-- Bug Report Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-orange-600 to-orange-700 dark:from-orange-700 dark:to-orange-800 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">{{ __('Report a Bug') }}</h1>
                    <p class="mt-2 text-orange-100">{{ __('Help us improve by reporting any issues you encounter.') }}</p>
                </div>

                {{-- Form --}}
                <form wire:submit="submit" class="px-8 py-6 space-y-6">
                    {{-- Bug Details Section --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                            {{ __('Bug Details') }}
                        </h3>

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Title') }} <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="title"
                                wire:model="title"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="{{ __('Brief summary of the bug') }}">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Description') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="description"
                                wire:model="description"
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                                placeholder="{{ __('Steps to reproduce, expected vs actual behavior...') }}"></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Minimum 10 characters') }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Severity --}}
                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Severity') }} <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="severity"
                                    wire:model="severity"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="low">{{ __('Low') }} - {{ __('Minor issue, cosmetic') }}</option>
                                    <option value="medium" selected>{{ __('Medium') }} - {{ __('Functionality affected') }}</option>
                                    <option value="high">{{ __('High') }} - {{ __('Major feature broken') }}</option>
                                    <option value="critical">{{ __('Critical') }} - {{ __('System unusable') }}</option>
                                </select>
                                @error('severity')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- URL --}}
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Page URL') }}
                                </label>
                                <input
                                    type="text"
                                    id="url"
                                    wire:model="url"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                    placeholder="{{ __('Page URL where the bug occurred') }}">
                                @error('url')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Screenshots --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Screenshots') }}
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="screenshots" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-1 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF ({{ __('max. 5MB each') }})</p>
                                    </div>
                                    <input id="screenshots" type="file" wire:model="screenshots" class="hidden" multiple accept="image/*">
                                </label>
                            </div>
                            @error('screenshots.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror

                            {{-- Preview uploaded files --}}
                            @if (count($screenshots) > 0)
                                <div class="mt-3 grid grid-cols-3 gap-2">
                                    @foreach($screenshots as $screenshot)
                                        <div class="relative">
                                            <img src="{{ $screenshot->temporaryUrl() }}" class="rounded-lg h-24 w-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Hidden browser info field --}}
                    <input type="hidden" wire:model="browserInfo" x-data x-init="$wire.set('browserInfo', navigator.userAgent)">

                    {{-- Submit Button --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="text-red-500">*</span> {{ __('Required fields') }}
                        </p>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <span wire:loading.remove wire:target="submit">{{ __('Submit Bug Report') }}</span>
                            <span wire:loading wire:target="submit">{{ __('Submitting...') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info Box --}}
            <div class="mt-6 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-orange-600 dark:text-orange-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-orange-800 dark:text-orange-300">{{ __('Tips for a good bug report') }}</h3>
                        <div class="mt-2 text-sm text-orange-700 dark:text-orange-400">
                            <ul class="list-disc list-inside space-y-1">
                                <li>{{ __('Describe the steps to reproduce the bug') }}</li>
                                <li>{{ __('Include what you expected to happen vs what actually happened') }}</li>
                                <li>{{ __('Attach screenshots if possible') }}</li>
                                <li>{{ __('Note the page URL where the bug occurred') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
