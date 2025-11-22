<div class="px-5">
    <!-- دکمه‌های دسته‌بندی -->
    <div class="grid grid-cols-1 w-[1200px] md:grid-cols-4 lg:grid-cols-4 gap-3  mb-5">
        <button wire:click="selectCategory('customers')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مالی
            مشتریان</button>
        <button wire:click="selectCategory('accounts')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارش حسابات و
            صندوق</button>
        <button wire:click="selectCategory('transactions')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات تراکنش های
            معاملات</button>
        <button wire:click="selectCategory('management')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مدیریتی و
            تحلیلی</button>
    </div>


    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <!-- select زیرشاخه -->
        @if($selectedCategory)
        <div class="mb-5 relative w-full ">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none ">
                <option value="">انتخاب زیرشاخه</option>
                @foreach($subCategories[$selectedCategory] as $sub)
                <option value="{{ $sub }}">{{ $sub }}</option>
                @endforeach
            </select>

            <!-- آیکون سفارشی -->
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓" class="w-4 h-4">
            </div>
        </div>
        @endif


        <!-- نمایش محتوای زیرشاخه -->
        @if($selectedSubCategory)
        @switch($selectedSubCategory)
        @case('گزارش بیلانس مشتریان')

        <!-- جدول گزارش بیلانس مشتریان -->
        <div class="overflow-x-auto w-full mt-4">
            <div class="max-h-[600px] overflow-y-auto">
                <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                    <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                        <tr>
                            <th class="px-4 py-4 font-bold w-16">
                                <span class="border border-white px-2 py-1 rounded-lg">#</span>
                            </th>
                            <th class="px-4 py-4 font-bold">نمبرحساب</th>
                            <th class="px-4 py-4 font-bold">نام حساب</th>
                            <th class="px-4 py-4 font-bold">آخرین تاریخ</th>
                            <th class="px-4 py-4 font-bold">دالر</th>
                            <th class="px-4 py-4 font-bold">افغانی</th>
                            <th class="px-4 py-4 font-bold">تومان</th>
                            <th class="px-4 py-4 font-bold">کلدار</th>
                            <th class="px-4 py-4 font-bold">یورو</th>
                            <th class="px-4 py-4 font-bold">درهم</th>
                            <th class="px-4 py-4 font-bold">لیره</th>
                            <th class="px-4 py-4 font-bold">یوان</th>
                            @php
                            $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                            $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                            @endphp
                            <th class="px-4 py-4 font-bold">بیلانس به {{ $sourceCurrency }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $index => $report)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 rounded-lg">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">{{
                                $report['account_number'] }}</td>
                            <td class="px-4 py-4">{{ $report['fullname'] }}</td>
                            <td class="px-3 py-4">
                                {{ $report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') :
                                '-' }}
                            </td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['usd'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['afn'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['irr'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['pkr'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['eur'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['aed'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['try'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 text-left">{{ number_format($report['balances']['cny'] ?? 0, 2) }}</td>
                            <td class="px-4 py-4 font-medium text-left">{{ number_format($report['total_balance'], 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                                هیچ داده‌ای یافت نشد
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

      <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      </div>
    @break


    @case('پرداخت‌های مشتری')
    <div class="border p-5 rounded bg-green-50">
        <h3 class="font-bold text-lg mb-3">{{ $selectedSubCategory }}</h3>
        <p>{{ $reportData }}</p>
    </div>
    @break

    @case('صورتحساب‌ها')
    <div class="border p-5 rounded bg-yellow-50">
        <h3 class="font-bold text-lg mb-3">{{ $selectedSubCategory }}</h3>
        <p>{{ $reportData }}</p>
    </div>
    @break

    @case('گزارش صندوق')
    <div class="border p-5 rounded bg-purple-50">
        <h3 class="font-bold text-lg mb-3">{{ $selectedSubCategory }}</h3>
        <p>{{ $reportData }}</p>
    </div>
    @break

    @default
    <div class="border p-5 rounded bg-gray-50">
        <h3 class="font-bold text-lg mb-3">{{ $selectedSubCategory }}</h3>
        <p>{{ $reportData }}</p>
    </div>
    @endswitch

    @endif



</div>