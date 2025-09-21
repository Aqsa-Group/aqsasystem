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
                    <td class="p-2 border">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d') }}
                    </td>
                    <td class="p-2 border">{{ $loan['type'] ?? '---' }}</td>
                    <td class="p-2 border">{{ $loan['currency'] ?? '---' }}</td>
                    <td class="p-2 border">{{ $loan['customer']['name'] ?? '---' }}</td>
                    <td class="p-2 border text-blue-600 font-bold">{{ number_format($loan['amount'] ?? 0) }}</td>
                    <td class="p-2 border text-green-600 font-bold">{{ number_format($loan['loan_recipt'] ?? 0) }}</td>
                    <td class="p-2 border text-red-600 font-bold">{{ number_format($loan['reminded'] ?? 0) }}</td>
                    <td class="p-2 border">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($loan['created_at'])->format('Y/m/d H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">هیچ قرضه‌ای ثبت نشده است.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
