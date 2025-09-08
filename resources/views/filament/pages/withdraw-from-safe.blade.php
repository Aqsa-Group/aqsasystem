
<x-filament::page>
    <form wire:submit.prevent="withdraw" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit" color="success" class="mt-4">
            ثبت برداشت
        </x-filament::button>
    </form>
</x-filament::page>
