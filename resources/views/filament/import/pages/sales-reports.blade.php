<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">🛒 گزارش فروشات</h1>

    <!-- فرم فیلتر -->
    <div class="mb-4 flex flex-wrap gap-2">
        <input 
            type="text" 
            wire:model.live="buyer_name"
            placeholder="نام خریدار..." 
            class="border rounded-lg px-3 py-1 text-sm"
        >

        <select wire:model.live="sale_type" class="border rounded-lg px-3 py-1 text-sm">
            <option value="">همه نوع فروش</option>
            <option value="wholesale">عمده</option>
            <option value="retail">پرچون</option>
        </select>

      
    </div>

    @include('filament.import.pages.partials.sales-table')

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@easepick/bundle@1.2.1/dist/index.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">

    <script>
        jalaliDatepicker.startWatch({
            time: false,
            format: 'YYYY-MM-DD',
        });
    </script>
    @endpush
</x-filament-panels::page>
