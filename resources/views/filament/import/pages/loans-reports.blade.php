<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">💳 گزارش قرضه‌ها</h1>

    <!-- فرم فیلتر -->
    <div class="mb-4 flex flex-wrap gap-2">
        <input 
            type="text" 
            wire:model.live="customer_name"
            placeholder="نام مشتری..." 
            class="border rounded-lg px-3 py-1 text-sm"
        >

        <select wire:model.live="type" class="border rounded-lg px-3 py-1 text-sm">
            <option value="">همه نوع</option>
            <option value="بردگی">بردگی</option>
            <option value="رسید">رسید</option>
        </select>

     
    </div>

    @include('filament.import.pages.partials.loans-table')

    @push('scripts')
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
