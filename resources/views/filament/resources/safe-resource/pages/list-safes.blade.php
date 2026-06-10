<x-filament::page>
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">

                {{-- Header --}}
                <thead>
                    <tr class="bg-primary-600 dark:bg-primary-700 text-white">
                        <th class="px-6 py-4 font-bold text-right">
                            نوع مصرف
                        </th>
                        <th class="px-6 py-4 font-bold text-center">
                            افغانی
                        </th>
                        <th class="px-6 py-4 font-bold text-center">
                            دالر
                        </th>
                        <th class="px-6 py-4 font-bold text-center">
                            یورو
                        </th>
                        <th class="px-6 py-4 font-bold text-center">
                            تومان
                        </th>
                    </tr>
                </thead>

                {{-- Body --}}
                <tbody class="bg-white dark:bg-gray-900">
                    @php
                    $total_af = $total_us = $total_er = $total_ir = 0;
                    @endphp

                    @foreach ($rows as $index => $row)
                    @php
                    $total_af += $row['af'];
                    $total_us += $row['us'];
                    $total_er += $row['er'];
                    $total_ir += $row['ir'];
                    @endphp

                    <tr class="
                dark:!text-white
                border-b border-gray-200 dark:border-gray-700
                {{ $index % 2 == 0
                ? 'bg-white dark:bg-gray-900'
                : 'bg-gray-50 dark:bg-gray-800/60' }}
                                                       ">
                        <td class="px-6 py-4 font-semibold text-primary-700 dark:!text-white">
                            {{ $row['type'] }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:!text-white">
                            {{ number_format($row['af']) }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:!text-white">
                            {{ number_format($row['us']) }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:!text-white">
                            {{ number_format($row['er']) }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:!text-white">
                            {{ number_format($row['ir']) }}
                        </td>
                    </tr>
                    @endforeach

                    {{-- Total Row --}}
                    <tr
                        class="bg-primary-100 dark:bg-primary-950 border-t-2 border-primary-300 dark:border-primary-800">
                        <td class="px-6 py-5 font-bold text-primary-800 dark:text-primary-200">
                            جمع کل
                        </td>

                        <td class="px-6 py-5 text-right font-bold text-primary-800 dark:text-primary-200">
                            {{ number_format($total_af) }}
                        </td>

                        <td class="px-6 py-5 text-right font-bold text-primary-800 dark:text-primary-200">
                            {{ number_format($total_us) }}
                        </td>

                        <td class="px-6 py-5 text-right font-bold text-primary-800 dark:text-primary-200">
                            {{ number_format($total_er) }}
                        </td>

                        <td class="px-6 py-5 text-right font-bold text-primary-800 dark:text-primary-200">
                            {{ number_format($total_ir) }}
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</x-filament::page>