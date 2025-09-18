<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">🛒 گزارش خریدها</h1>

    {{-- فیلتر بر اساس شرکت --}}
    <div class="mb-6">
       {{-- <select wire:model="selectedCompany" class="...">
    <option value="">انتخاب شرکت</option>
    @foreach($companies as $company)
        <option value="{{ $company->id }}">{{ $company->name }}</option>
    @endforeach
</select> --}}

    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-gray-300">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border px-4 py-3">تاریخ و ساعت</th>
                    <th class="border px-4 py-3">نام شرکت</th>
                    <th class="border px-4 py-3">نام جنس</th>
                    <th class="border px-4 py-3">واحد</th>
                    <th class="border px-4 py-3">قیمت فی دانه</th>
                    <th class="border px-4 py-3">تعداد خریده شده</th>
                    <th class="border px-4 py-3">نوع ارز</th>
                    <th class="border px-4 py-3">مبلغ خرید</th>
                    <th class="border px-4 py-3">مبلغ رسید</th>
                    <th class="border px-4 py-3">باقی‌مانده</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($buys as $buy)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border px-4 py-2 text-center">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($buy->created_at)->format('%Y/%m/%d H:i:s') }}
                        </td>
<td class="border px-4 py-2">{{ $buy->company?->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $buy->name }}</td>
                        <td class="border px-4 py-2 text-center">{{ $buy->unit }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($buy->price, 2) }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($buy->all_exist_number) }}</td>
                        <td class="border px-4 py-2 text-center">{{ $buy->currency === 'USD' ? 'دالر' : 'افغانی' }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($buy->amount, 2) }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($buy->paid, 2) }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($buy->remaining, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="border px-4 py-3 text-center text-gray-500" colspan="10">
                            هیچ خریدی یافت نشد
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
