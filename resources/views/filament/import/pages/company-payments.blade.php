<x-filament-panels::page>
    <h1 class="text-2xl font-bold mb-6">💳 گزارش رسیدهای شرکت‌ها</h1>

    {{-- فیلتر بر اساس شرکت --}}
    <div class="mb-6">
        <select wire:model="selectedCompany" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700">
            <option value="">انتخاب شرکت</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-gray-300">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border px-4 py-3">نام شرکت</th>
                    <th class="border px-4 py-3">مبلغ قرضه</th>
                    <th class="border px-4 py-3">مبلغ رسید</th>
                    <th class="border px-4 py-3">باقی‌مانده</th>
                    <th class="border px-4 py-3">ارز</th>
                    <th class="border px-4 py-3">تاریخ و ساعت</th>

                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="bg-white hover:bg-gray-50">
                     
                        <td class="border px-4 py-2">{{ $payment->company->name }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($payment->total_debt, 2) }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($payment->paid_amount, 2) }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($payment->remaining, 2) }}</td>
                        <td class="border px-4 py-2">{{ $payment->currency }}</td>
                           <td class="border px-4 py-2">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($payment->created_at)->format('%Y/%m/%d H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="border px-4 py-3 text-center text-gray-500" colspan="6">
                            هیچ رسیدی یافت نشد
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
