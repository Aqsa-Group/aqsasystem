<div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
    <table class="w-full text-sm text-right border-collapse">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">تاریخ</th>
                <th class="p-2 border">کارمند</th>
                <th class="p-2 border">مبلغ</th>
                <th class="p-2 border">توضیحات</th>
            </tr>
        </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
    @forelse($withdrawals as $index => $withdrawal)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
            <td class="p-2 border">{{ $index + 1 }}</td>
            <td class="p-2 border">
                {{ \Morilog\Jalali\Jalalian::fromDateTime($withdrawal->created_at)->format('Y/m/d H:i') }}
            </td>
            <td class="p-2 border">{{ $withdrawal->staff->name ?? '---' }}</td>
            <td class="p-2 border text-red-600 font-bold">{{ number_format($withdrawal->amount ?? 0) }}</td>
            <td class="p-2 border">{{ $withdrawal->description ?? '---' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center py-6 text-gray-400">هیچ برداشتی ثبت نشده است.</td>
        </tr>
    @endforelse
</tbody>

    </table>
</div>
