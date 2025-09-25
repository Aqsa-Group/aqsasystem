<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-2">💳 گزارش قرضه‌ها</h1>

    {{-- <!-- دکمه دانلود PDF -->
    <div class="mb-4 flex justify-between items-center">
        <button 
            wire:click="exportPdf"
            wire:loading.attr="disabled"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
        >
            <span wire:loading.remove>📥 دانلود PDF</span>
            <span wire:loading>در حال تولید...</span>
        </button>
    </div> --}}

    <!-- فرم فیلتر -->
    <div class="mb-6 dark:bg-gray-800 rounded-lg  p-4">
        <h3 class="text-lg font-medium mb-3 text-gray-700 dark:text-gray-300">فیلتر گزارشات</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-400">نام مشتری</label>
                <input 
                    type="text" 
                    wire:model.live="customer_name"
                    placeholder="جستجو بر اساس نام..." 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-400">نوع تراکنش</label>
                <select wire:model.live="type" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">همه نوع‌ها</option>
                    <option value="بردگی">بردگی</option>
                    <option value="رسید">رسید</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-400">نوع ارز</label>
                <select wire:model.live="currency" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">همه ارزها</option>
                    <option value="دالر">دالر</option>
                    <option value="افغانی">افغانی</option>
                </select>
            </div>

         
        </div>
    </div>

    <!-- جدول قرضه‌ها -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">تاریخ</th>
                    <th class="p-2 border">نوع</th>
                    <th class="p-2 border">ارز</th>
                    <th class="p-2 border">نام مشتری</th>
                    <th class="p-2 border">مبلغ قرضه</th>
                    <th class="p-2 border">رسید</th>
                    <th class="p-2 border">باقی‌مانده</th>
                    <th class="p-2 border">ثبت شده در</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($loans as $index => $loan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ \Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d') }}</td>
                        <td class="p-2 border">{{ $loan['type'] }}</td>
                        <td class="p-2 border">{{ $loan['currency'] }}</td>
                        <td class="p-2 border">{{ $loan['customer']['name'] }}</td>
                        <td class="p-2 border text-blue-600 font-bold">{{ number_format($loan['amount'] ?? 0) }}</td>
                        <td class="p-2 border text-green-600 font-bold">{{ number_format($loan['loan_recipt'] ?? 0) }}</td>
                        <td class="p-2 border text-red-600 font-bold">{{ number_format($loan['reminded'] ?? 0) }}</td>
                        <td class="p-2 border">{{ \Morilog\Jalali\Jalalian::fromDateTime($loan['created_at'])->format('Y/m/d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-400">هیچ قرضه‌ای ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
