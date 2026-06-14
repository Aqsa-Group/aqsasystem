<x-filament-panels::page>
    <div class="space-y-6">
        <!-- هدر -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">گزارش قرضه‌ها</h1>
        </div>

        <!-- کارت‌های خلاصه -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">کل قرض‌ها</p>
                <p class="text-2xl font-bold mt-1 text-blue-600 dark:text-blue-400">
                    {{ number_format($this->getSummaryStats()['total_borrowed']) }}
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">کل رسیدها</p>
                <p class="text-2xl font-bold mt-1 text-green-600 dark:text-green-400">
                    {{ number_format($this->getSummaryStats()['total_receipts']) }}
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">باقی‌مانده کل</p>
                <p class="text-2xl font-bold mt-1 text-red-600 dark:text-red-400">
                    {{ number_format($this->getSummaryStats()['total_remaining']) }}
                </p>
            </div>
        </div>

        <!-- فرم فیلتر -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">فیلتر گزارشات</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">نام مشتری</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.500ms="customer_name"
                        placeholder="جستجو بر اساس نام..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">نوع تراکنش</label>
                    <select 
                        wire:model.live="type" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                        <option value="">همه نوع‌ها</option>
                        <option value="برد">برد (قرض)</option>
                        <option value="رسید">رسید (پرداخت)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">نوع ارز</label>
                    <select 
                        wire:model.live="currency" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                        <option value="">همه ارزها</option>
                        <option value="USD">دلار</option>
                        <option value="AFN">افغانی</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">تاریخ</label>
                    <input 
                        type="text" 
                        wire:model.live="date"
                        placeholder="انتخاب تاریخ..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition datepicker"
                        id="datepicker"
                        autocomplete="off"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">از تاریخ</label>
                    <input 
                        type="text" 
                        wire:model.live="startDate"
                        placeholder="از تاریخ..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition datepicker"
                        id="startDate"
                        autocomplete="off"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">تا تاریخ</label>
                    <input 
                        type="text" 
                        wire:model.live="endDate"
                        placeholder="تا تاریخ..." 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition datepicker"
                        id="endDate"
                        autocomplete="off"
                    >
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <button 
                    wire:click="resetFilters"
                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm transition border border-gray-300 dark:border-gray-600"
                >
                    حذف فیلترها
                </button>
                
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($loans) }} رکورد یافت شد
                </span>
            </div>
        </div>

        <!-- جدول داده‌ها -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold text-center">#</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">تاریخ</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">نوع</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">ارز</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">نام مشتری</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">شرح</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">مبلغ قرضه</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">رسید</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">باقی‌مانده</th>
                            <th class="px-4 py-3 text-gray-600 dark:text-gray-300 font-semibold">تاریخ ثبت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($loans as $index => $loan)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition {{ $loan['type'] === 'برد' ? 'bg-red-50/30 dark:bg-red-900/10' : 'bg-green-50/30 dark:bg-green-900/10' }}">
                                <td class="px-4 py-3 text-center text-gray-500">{{ $loop->iteration }}</td>
                                
                                <td class="px-4 py-3">
                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d') }}
                                </td>
                                
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $loan['type'] === 'برد' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' }}">
                                        {{ $loan['type'] }}
                                    </span>
                                </td>
                                
                                <td class="px-4 py-3 font-medium">{{ $loan['currency'] }}</td>
                                
                                <td class="px-4 py-3 font-medium">{{ $loan['customer']['name'] }}</td>
                                
                                <td class="px-4 py-3 text-gray-500 max-w-[200px] truncate" title="{{ $loan['description'] ?? '' }}">
                                    {{ $loan['description'] ?? '---' }}
                                </td>
                                
                                <td class="px-4 py-3 font-bold {{ $loan['type'] === 'برد' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                                    {{ $loan['type'] === 'برد' ? number_format($loan['amount']) : '---' }}
                                </td>
                                
                                <td class="px-4 py-3 font-bold {{ $loan['type'] === 'رسید' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                                    {{ $loan['type'] === 'رسید' ? number_format($loan['loan_recipt']) : '---' }}
                                </td>
                                
                                <td class="px-4 py-3 font-bold {{ ($loan['reminded'] ?? 0) > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    {{ number_format($loan['reminded'] ?? 0) }}
                                </td>
                                
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($loan['created_at'])->format('Y/m/d H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-gray-400 dark:text-gray-500">
                                    هیچ قرضه‌ای با این فیلترها یافت نشد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    @if(count($loans) > 0)
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                            <tr class="font-bold text-sm">
                                <td colspan="6" class="px-4 py-3 text-right">جمع کل:</td>
                                <td class="px-4 py-3 text-blue-600 dark:text-blue-400">
                                    {{ number_format(collect($loans)->where('type', 'برد')->sum('amount')) }}
                                </td>
                                <td class="px-4 py-3 text-green-600 dark:text-green-400">
                                    {{ number_format(collect($loans)->where('type', 'رسید')->sum('loan_recipt')) }}
                                </td>
                                <td class="px-4 py-3 text-red-600 dark:text-red-400">
                                    {{ number_format(collect($loans)->where('type', 'برد')->sum('amount') - collect($loans)->where('type', 'رسید')->sum('loan_recipt')) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const datepickerConfig = {
                time: false,
                format: 'YYYY-MM-DD',
                autoClose: true,
                position: 'auto',
            };

            jalaliDatepicker.startWatch({
                ...datepickerConfig,
                targetDocumentSelector: '#datepicker',
            });

            jalaliDatepicker.startWatch({
                ...datepickerConfig,
                targetDocumentSelector: '#startDate',
            });

            jalaliDatepicker.startWatch({
                ...datepickerConfig,
                targetDocumentSelector: '#endDate',
            });

            document.querySelectorAll('.datepicker').forEach(input => {
                input.addEventListener('change', function() {
                    this.dispatchEvent(new Event('input', { bubbles: true }));
                });
            });
        });
    </script>
    @endpush
</x-filament-panels::page>