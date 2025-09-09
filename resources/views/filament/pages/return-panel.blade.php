<x-filament-panels::page>
    <div class="space-y-6 p-4">

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-3 border border-gray-200 dark:border-gray-700">
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <label class="block text-sm mb-1 dark:text-gray-200">شماره فاکتور</label>
                    <input wire:model.defer="invoiceNumber"  wire:keydown.enter="loadSale"
                   type="number"
                           class="w-md border rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
               
                <x-filament::button wire:click="loadSale" color="info" class="px-4 py-2 rounded-lg">
                    <div class="flex  gap-3">
   
                جستجو فاکتور
                                           <x-heroicon-o-magnifying-glass class="w-5 h-5"/>

                    </div>
                </x-filament::button>
                        </div>
               

                  <div class="flex gap-3 justify-end">
                    <x-filament::button wire:click="submitReturn" color="success" class="px-4 py-2 rounded-lg">
                               <div class="flex  gap-3">
   
                 ثبت برگشتی
                                           <x-heroicon-o-arrow-path class="w-5 h-5"/>

                    </div>
                    </x-filament::button>
                </div>
            </div>
        </div>

        @if($sale)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm dark:text-gray-300">
                        فاکتور: {{ $sale->invoice_number }} |
                        نوع فروش: {{ $sale->sale_type === 'wholesale' ? 'عمده' : 'پرچون' }}
                    </div>
                    <div class="text-sm dark:text-gray-300">
                        مبلغ فعلی فاکتور: <span class="font-bold">{{ number_format($sale->total_price) }}</span> افغانی
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-right">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="p-2">نام جنس</th>
                                <th class="p-2">واحد</th>
                                <th class="p-2">قیمت فروش</th>
                                <th class="p-2">تعداد فروخته‌شده</th>
                                <th class="p-2">تعداد برگشتی</th>
                                <th class="p-2">مجموع برگشتی</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($rows as $i => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="p-2">{{ $row['name'] }}</td>
                                    <td class="p-2">{{ $row['unit'] }}</td>
                                    <td class="p-2">{{ number_format($row['sale_price']) }}</td>
                                    <td class="p-2">{{ $row['sold_qty'] }}</td>
                                    <td class="p-2">
                                        <input type="number" min="0" max="{{ $row['sold_qty'] }}"
                                               wire:model.lazy="rows.{{ $i }}.qty"
                                               class="w-24 border rounded-lg px-2 py-1 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                                    </td>
                                    <td class="p-2 font-semibold text-blue-600 dark:text-blue-400">
                                        {{ number_format($row['total']) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 justify-between">
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <span class="text-sm dark:text-gray-200">مجموع برگشتی</span>
                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalReturn) }} افغانی</span>
                    </div>
                

              
            </div>
        @endif
    </div>
</x-filament-panels::page>
