<x-filament::page>
    {{ $this->form }}

    <div class="mt-4">
        <x-filament::button wire:click="convert" color="primary">
            تبدیل کن
        </x-filament::button>
    </div>
</x-filament::page>
