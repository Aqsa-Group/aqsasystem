<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">💸 گزارش برداشت‌ها</h1>

    <!-- فیلتر -->
    <div class="mb-4 flex flex-wrap gap-2">
        <select wire:model.live="type" class="border rounded-lg px-3 py-1 text-sm">
            <option value="">انتخاب کنید</option>
            <option value="electricity">برق</option>
            <option value="rent">کرایه</option>
            <option value="water">مالیه</option>
            <option value="food">غذا</option>
            <option value="salary">معاش کارمند</option>
            <option value="transportation">بارچلانی چین</option>
            <option value="other">متفرقه</option>
        </select>

        <input 
            type="text" 
            wire:model.live="staff_name"
            placeholder="نام کارمند..." 
            class="border rounded-lg px-3 py-1 text-sm"
        >
     
    </div>

    @include('filament.import.pages.partials.withdrawals-table')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">

        <script>
            jalaliDatepicker.startWatch({
                time: false,
                format: 'YYYY-MM-DD', // به شکل 1404-06-23 خروجی میده
            });
        </script>
    @endpush
</x-filament-panels::page>
