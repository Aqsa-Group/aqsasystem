{{-- resources/views/filament/pages/sale-report.blade.php --}}
<x-filament::page>
    <x-filament::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="2"
    />

    {{ $this->table }}

    <x-filament-actions::modals />
</x-filament::page>