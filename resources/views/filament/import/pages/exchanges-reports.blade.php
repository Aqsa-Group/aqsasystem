<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">💱 گزارش تبدیل ارز</h1>

    <!-- فرم فیلتر -->
    <div class="mb-4 flex flex-wrap gap-2">
           <select wire:model.live="type" class="border rounded-lg px-3 py-1 text-sm">
            <option value="">همه نوع تبادل</option>
            <option value="تبدیل ارز در صرافی">تبدیل ارز در صرافی</option>
            <option value="تبدیل ارز دوکان">تبدیل ارز دوکان</option>
            <option value="تبدیل ارز در حساب مشتری">تبدیل ارز در حساب مشتری</option>
            <option value="تبدیل ارز در حساب کارمند">تبدیل ارز در حساب کارمند</option>
            <option value="تبدیل ارز در حساب متفرقه">تبدیل ارز در حساب متفرقه</option>
        </select>
        <input type="text" wire:model.live="user_name" placeholder="نام صرافی / مشتری / کارمند / متفرقه..." class="border rounded-lg px-3 py-1 text-sm">
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">نوع</th>
                    <th class="p-2 border">از ارز</th>
                    <th class="p-2 border">به ارز</th>
                    <th class="p-2 border">مبلغ</th>
                    <th class="p-2 border">قیمت روز</th>
                    <th class="p-2 border">مجموع</th>
                    <th class="p-2 border">صرافی / مشتری / کارمند / متفرقه</th>
                    <th class="p-2 border">تاریخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($exchanges as $index => $exchange)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $exchange['type'] }}</td>
                          <td class="p-2 border">
                        {{
                            match($exchange['from'] ?? '') {
                                'AFN' => 'افغانی',
                                'USD' => 'دالر',
                                'CNY' => 'ین چین',
                                'EUR' => 'یورو',
                                'IRR' => 'تومان',
                                'PRK' => 'کلدار',
                                default => $exchange['from'] ?? '---'
                            }
                        }}
                    </td>
                    <td class="p-2 border">
                        {{
                            match($exchange['to'] ?? '') {
                                'AFN' => 'افغانی',
                                'USD' => 'دالر',
                                'CNY' => 'ین چین',
                                'EUR' => 'یورو',
                                'IRR' => 'تومان',
                                'PRK' => 'کلدار',
                                default => $exchange['to'] ?? '---'
                            }
                        }}
                    </td>

                        <td class="p-2 border">{{ number_format($exchange['amount'] ?? 0) }}</td>
                        <td class="p-2 border">{{ number_format($exchange['today_price'] ?? 0) }}</td>
                        <td class="p-2 border">{{ number_format($exchange['total'] ?? 0) }}</td>
                        <td class="p-2 border">{{ $exchange['sarafi']['name'] ?? $exchange['customer']['name'] ?? $exchange['staff']['name'] ?? $exchange['person'] ?? '---' }}</td>
                        <td class="p-2 border">{{ \Morilog\Jalali\Jalalian::fromDateTime($exchange['created_at'])->format('Y/m/d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-400">هیچ تبادل ارزی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
