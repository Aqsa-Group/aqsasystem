<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">📥 گزارش افزودن به صندوق</h1>


    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">مقدار</th>
                    <th class="p-2 border">ارز</th>
                    <th class="p-2 border">توضیح</th>
                    <th class="p-2 border">تاریخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($records as $index => $record)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border">{{ $index + 1 }}</td>
                        <td class="p-2 border text-right font-mono">{{ number_format($record['amount']) }}</td>
                        <td class="p-2 border">
                            {{
                                match($record['currency']) {
                                    'AFN' => 'افغانی',
                                    'USD' => 'دالر',
                                    default => $record['currency'],
                                }
                            }}
                        </td>
                        <td class="p-2 border">{{ $record['description'] }}</td>
                        <td class="p-2 border">{{ \Morilog\Jalali\Jalalian::fromDateTime($record['created_at'])->format('Y/m/d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-400">هیچ رکوردی یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
