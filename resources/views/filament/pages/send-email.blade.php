<x-filament-panels::page>
    <form wire:submit="send">
        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-x-3">
            <x-filament::button type="submit" color="primary">
                Send Email
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
