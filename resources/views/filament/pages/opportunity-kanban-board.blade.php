<x-filament-panels::page>
    <div x-data="{
        draggingId: null,
        dragOverStage: null,
        startDrag(event, id) {
            this.draggingId = id;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', id);
            event.target.classList.add('opacity-50');
        },
        endDrag(event) {
            event.target.classList.remove('opacity-50');
            this.draggingId = null;
            this.dragOverStage = null;
        },
        dragOver(event, stage) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            this.dragOverStage = stage;
        },
        dragLeave(event, stage) {
            if (this.dragOverStage === stage) {
                this.dragOverStage = null;
            }
        },
        drop(event, stage) {
            event.preventDefault();
            const id = parseInt(event.dataTransfer.getData('text/plain'));
            this.dragOverStage = null;
            this.draggingId = null;
            if (id) {
                $wire.moveOpportunity(id, stage);
            }
        },
    }" class="flex gap-4 overflow-x-auto pb-4">
        @foreach ($this->getStageEnums() as $stage)
            <div class="min-w-70 flex-1 rounded-xl bg-gray-50 dark:bg-white/5"
                x-on:dragover="dragOver($event, '{{ $stage->value }}')"
                x-on:dragleave="dragLeave($event, '{{ $stage->value }}')" x-on:drop="drop($event, '{{ $stage->value }}')"
                x-bind:class="dragOverStage === '{{ $stage->value }}' ?
                    'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-500/10' : ''">
                {{-- Column Header --}}
                <div class="flex items-center justify-between rounded-t-xl px-3 py-3">
                    <div class="flex items-center gap-2">
                        <span class="{{ $this->getStageColor($stage->value) }} inline-block h-3 w-3 rounded-full"></span>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $this->getStageLabel($stage->value) }}
                        </h3>
                    </div>
                    <span
                        class="inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">
                        {{ count($stages[$stage->value] ?? []) }}
                    </span>
                </div>

                {{-- Cards Container --}}
                <div class="space-y-2 px-2 pb-2" style="min-height: 100px;">
                    @forelse ($stages[$stage->value] ?? [] as $opportunity)
                        <div draggable="true" x-on:dragstart="startDrag($event, {{ $opportunity['id'] }})"
                            x-on:dragend="endDrag($event)"
                            class="cursor-grab rounded-lg border border-gray-200 bg-white p-3 shadow-sm transition-shadow hover:shadow-md active:cursor-grabbing dark:border-white/10 dark:bg-white/5">
                            <div class="mb-2 flex items-start justify-between gap-2">
                                <a href="{{ route('filament.admin.resources.lead-opportunities.edit', ['record' => $opportunity['id'], 'tenant' => \Filament\Facades\Filament::getTenant()]) }}"
                                    class="text-sm font-medium text-gray-900 hover:text-primary-600 dark:text-white dark:hover:text-primary-400">
                                    {{ $opportunity['title'] }}
                                </a>
                            </div>

                            @if (!empty($opportunity['customer']))
                                <p class="mb-1 text-xs text-gray-500 dark:text-gray-400">
                                    <x-filament::icon icon="heroicon-m-building-office" class="mr-1 inline h-3 w-3" />
                                    {{ $opportunity['customer']['name'] ?? '' }}
                                </p>
                            @endif

                            <div class="mt-2 flex items-center justify-between">
                                @if ($opportunity['value'])
                                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format((float) $opportunity['value'], 0, ',', ' ') }} Ft
                                    </span>
                                @else
                                    <span></span>
                                @endif

                                @if ($opportunity['probability'])
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-1.5 py-0.5 text-xs text-gray-600 dark:bg-white/10 dark:text-gray-400">
                                        {{ $opportunity['probability'] }}%
                                    </span>
                                @endif
                            </div>

                            @if (!empty($opportunity['assigned_user']))
                                <div class="mt-2 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                    <x-filament::icon icon="heroicon-m-user" class="h-3 w-3" />
                                    {{ $opportunity['assigned_user']['name'] ?? '' }}
                                </div>
                            @endif

                            @if ($opportunity['expected_close_date'])
                                <div class="mt-1 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                    <x-filament::icon icon="heroicon-m-calendar" class="h-3 w-3" />
                                    {{ \Carbon\Carbon::parse($opportunity['expected_close_date'])->format('Y.m.d') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-gray-400 dark:text-gray-500">
                            {{ __('No opportunities') }}
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
