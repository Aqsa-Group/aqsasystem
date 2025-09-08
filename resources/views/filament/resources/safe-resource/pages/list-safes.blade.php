<x-filament::page>
    <div class="overflow-x-auto rounded-xl shadow-md border border-gray-300 dark:border-gray-600 w-full">
        <table class="w-full min-w-full text-sm text-right text-gray-800 dark:text-gray-100 bg-white dark:bg-[#1e1e2a] border-collapse">
            {{-- سرتیتر جدول --}}
            <thead class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white dark:from-[#3b3b4f] dark:to-[#4b4b5e]">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">نوع مصرف</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">افغانی</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">دالر</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">یورو</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">تومان</th>
                </tr>
            </thead>

            {{-- بدنه جدول --}}
            <tbody>
                @php
                    $total_af = $total_us = $total_er = $total_ir = 0;
                @endphp

                @foreach ($rows as $row)
                    @php
                        $total_af += $row['af'];
                        $total_us += $row['us'];
                        $total_er += $row['er'];
                        $total_ir += $row['ir'];
                    @endphp
                    <tr class="bg-white dark:bg-[#2a2a3a] hover:bg-gray-100 dark:hover:bg-[#3a3a4a] transition duration-200 border-b border-gray-200 dark:border-gray-600">
                        <td class="px-6 py-3 font-medium text-indigo-800 dark:text-gray-200 whitespace-nowrap">{{ $row['type'] }}</td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right">{{ number_format($row['af']) }}</td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right">{{ number_format($row['us']) }}</td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right">{{ number_format($row['er']) }}</td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right">{{ number_format($row['ir']) }}</td>
                    </tr>
                @endforeach

                {{-- جمع کل --}}
                <tr class="bg-indigo-100 dark:bg-[#444455] font-bold border-t border-indigo-300 dark:border-gray-500 text-indigo-900 dark:text-gray-100">
                    <td class="px-6 py-4 text-center">جمع کل</td>
                    <td class="px-6 py-4 text-right">{{ number_format($total_af) }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($total_us) }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($total_er) }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($total_ir) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-filament::page>
