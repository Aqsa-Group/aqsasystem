<div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
    <table class="w-full text-sm text-right border-collapse">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">شماره فاکتور</th>
                <th class="p-2 border">تاریخ</th>
                <th class="p-2 border">نوع فروش</th>
                <th class="p-2 border">نام خریدار</th>
                <th class="p-2 border">مجموع فاکتور</th>
                <th class="p-2 border">مبلغ رسید</th>
                <th class="p-2 border">باقیمانده</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($sales as $index => $sale)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="p-2 border">{{ $index + 1 }}</td>
                    <td class="p-2 border">{{ $sale['invoice_number'] ?? '---' }}</td>
                    <td class="p-2 border">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($sale['created_at'])->format('Y/m/d H:i') }}
                    </td>
                    <td class="p-2 border">{{ $sale['sale_type'] == 'wholesale' ? 'عمده' : 'پرچون' }}</td>
                    <td class="p-2 border">{{ $sale['buyer_name'] ?? '---' }}</td>
                    <td class="p-2 border text-blue-600 font-bold">{{ number_format($sale['total_price'] ?? 0) }}</td>
                    <td class="p-2 border text-green-600 font-bold">{{ number_format($sale['received_amount'] ?? 0) }}</td>
                    <td class="p-2 border text-red-600 font-bold">{{ number_format($sale['remaining_amount'] ?? 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">هیچ فروشی ثبت نشده است.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
