<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">💰 گزارش ترانزکشن‌ها</h1>

    
    <div class="mb-6 flex flex-wrap gap-3 items-center">
    <select wire:model.live="transaction_type" 
            class="border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                   focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">
        <option value="">همه تراکنش‌ها</option>
        <option value="رسید">رسید</option>
        <option value="برداشت">برداشت</option>
    </select>

    <input type="text" wire:model.live="sarafi_name" placeholder="نام صرافی..." 
           class="border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                  focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">

    <input type="text" wire:model.live="user_name" placeholder="نام مشتری / کارمند / متفرقه..." 
           class="border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                  focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">

   
</div>


    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">نوع تراکنش</th>
                    <th class="p-2 border">نام مشتری / کارمند / متفرقه</th>
                    <th class="p-2 border">صرافی</th>
                    <th class="p-2 border">مقدار</th>
                    <th class="p-2 border">ارز</th>
                    <th class="p-2 border">تاریخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($transactions as $index => $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $transaction['type'] }}</td>
                        <td class="p-2 border">
                            {{ $transaction['customer']['name'] ?? $transaction['staff']['name'] ?? $transaction['person'] ?? '---' }}
                        </td>
                        <td class="p-2 border">
                            {{ $transaction['sarafi']['name'] ?? '---' }}
                        </td>
                        <td class="p-2 border text-right font-mono {{ $transaction['type'] === 'رسید' ? 'text-green-600 dark:text-green-400' : ($transaction['type'] === 'برداشت' ? 'text-red-600 dark:text-red-400' : '') }}">
                            {{ number_format($transaction['amount'] ?? 0) }}
                        </td>
                                <td class="p-2 border">
                                {{
                                    match($transaction['currency'] ?? '') {
                                        'AFN' => 'افغانی',
                                        'USD' => 'دالر',
                                        'CNY' => 'ین چین',
                                        'EUR' => 'یورو',
                                        'IRR' => 'تومان',
                                        'PRK' => 'کلدار',
                                        default => $transaction['currency'] ?? '---'
                                    }
                                }}
                            </td>
                        <td class="p-2 border">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($transaction['created_at'])->format('Y/m/d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400">هیچ تراکنشی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
