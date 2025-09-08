<x-filament-panels::page>
    <div class="space-y-8 p-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">📊 گزارشات  فروش، قرضه ، برداشت ها ، صندوق</h1>

        {{-- بخش فروش --}}
        <section>
            <h2 class="text-xl font-semibold mb-3">فروش‌ها</h2>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                <table class="w-full text-sm text-right border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">شماره فاکتور</th>
                            <th class="p-2 border">تاریخ</th>
                            <th class="p-2 border">نوع فروش</th>
                            <th class="p-2 border">نام خریدار</th>
                            <th class="p-2 border">مجموع فاکتور (افغانی)</th>
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
        </section>
        
        {{-- بخش قرضه‌ها --}}
        <section>
            <h2 class="text-xl font-semibold mt-10 mb-3">قرضه‌ها (وام‌ها)</h2>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                <table class="w-full text-sm text-right border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">تاریخ</th>
                            <th class="p-2 border">نام مشتری</th>
                            <th class="p-2 border">مبلغ قرضه (افغانی)</th>
                            <th class="p-2 border">باقیمانده</th>
                            <th class="p-2 border">وضعیت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($loans as $index => $loan)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="p-2 border">{{ $index + 1 }}</td>
                                <td class="p-2 border">
                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d') }}
                                </td>
                                <td class="p-2 border">{{ $loan['customer']['name'] ?? '---' }}</td>
                                <td class="p-2 border text-blue-600 font-bold">{{ number_format($loan['amount'] ?? 0) }}</td>
                                <td class="p-2 border text-red-600 font-bold">{{ number_format($loan['reminded'] ?? 0) }}</td>
                                <td class="p-2 border">
                                    @if(($loan['remained'] ?? 0) > 0)
                                        <span class="text-red-600 font-semibold">باقی‌مانده</span>
                                    @else
                                        <span class="text-green-600 font-semibold">تسویه شده</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400">هیچ قرضه‌ای ثبت نشده است.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- بخش برداشت‌ها --}}
      {{-- بخش برداشت‌ها --}}
<section>
    <h2 class="text-xl font-semibold mt-10 mb-3">برداشت‌ها</h2>
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">تاریخ</th>
                    <th class="p-2 border">نوع برداشت</th>
                    <th class="p-2 border">نام کارمند</th>
                    <th class="p-2 border">مبلغ برداشت (افغانی)</th>
                    <th class="p-2 border">توضیحات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($withdrawals as $index => $withdrawal)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        {{-- شماره ردیف --}}
                        <td class="p-2 border">{{ $index + 1 }}</td>

                        {{-- تاریخ --}}
                        <td class="p-2 border">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($withdrawal['created_at'])->format('Y/m/d H:i') }}
                        </td>
                        

                        {{-- نوع برداشت --}}
                        <td class="p-2 border">
                            @switch($withdrawal->type)
                                @case('electricity') برق @break
                                @case('rent') کرایه @break
                                @case('water') مالیه آب @break
                                @case('food') غذا @break
                                @case('salary') معاش کارمند @break
                                @case('other') متفرقه @break

                                @default ---
                            @endswitch
                        </td>

                        {{-- نام کارمند --}}
                        <td class="p-2 border">
                            {{ $withdrawal->type === 'salary' && $withdrawal->staff ? $withdrawal->staff->name : '---' }}
                        </td>

                        {{-- مبلغ برداشت --}}
                        <td class="p-2 border text-red-600 font-bold">
                            {{ number_format($withdrawal->amount ?? 0) }}
                        </td>

                        {{-- توضیحات --}}
                        <td class="p-2 border">{{ $withdrawal->description ?? '---' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400">
                            هیچ برداشتی ثبت نشده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

        {{-- بخش صندوق --}}
        <section >
            <h2 class="text-xl w-full font-semibold mt-10 mb-3">خلاصه صندوق</h2>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 max-w-full">
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">مجموع کل صندوق:</span>
                    <span class="font-bold text-blue-600">{{ number_format($safeSummary['total'] ?? 0) }} افغانی</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">درآمد امروز:</span>
                    <span class="font-bold text-green-600">{{ number_format($safeSummary['today'] ?? 0) }} افغانی</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">آخرین بروزرسانی:</span>
                    <span>
                        @if($safeSummary['last_update'])
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($safeSummary['last_update'])->format('Y/m/d H:i') }}
                        @else
                            نامشخص
                        @endif
                    </span>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
