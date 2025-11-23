<div class="px-5">
    <!-- دکمه‌های دسته‌بندی -->
    <div class="grid grid-cols-1 w-[1200px] md:grid-cols-4 lg:grid-cols-4 gap-3 mb-5">
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
        <div class="mb-5 relative w-full">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none">
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


        <!-- بخش جدول -->
        <div class="overflow-x-auto w-full mt-4 mb-8">
            <div class="flex flex-col max-h-[600px] overflow-y-auto">
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


        @break

        @case('گزارش خلاصه بیلانس مشتریان')
          <div>
            سلام
          </div>
        @break

        @default
        <div class="border p-5 rounded bg-gray-50">
            <h3 class="font-bold text-lg mb-3">{{ $selectedSubCategory }}</h3>
            <p>این گزارش در حال توسعه می‌باشد</p>
        </div>
        @endswitch
        @endif
    </div>




    @if($selectedSubCategory == 'گزارش بیلانس مشتریان')
    <!-- بخش انتخاب مشتری و نمایش نمودار (در یک div جداگانه) -->
    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="flex w-full">
            <div class="md:w-1/2">
                <div class="flex-1">
                    <div class="relative w-[589px]">
                        <div x-data="{
                                searchValue: '',
                                selectedId: @entangle('selectedAccount'),
                                customers: @js($customers->toArray()),
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.selectCustomer(selected.id);
                                        $wire.set('search', selected.fullname);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('selectedAccount', null);
                                        $wire.set('search', '');
                                    }
                                },
                                updateDisplay() {
                                    const selected = this.customers.find(c => c.id === this.selectedId);
                                    this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                }
                            }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                            class="relative w-full">
                            <input list="customersList" x-model="searchValue" @change="handleSelect"
                                placeholder="جستجو یا انتخاب حساب..."
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                autocomplete="off">
                            <datalist id="customersList">
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->account_number }} - {{ $customer->fullname }}">
                                    @endforeach
                            </datalist>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                            </div>
                        </div>
                        @error('selectedAccount')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- نمایش موجودی‌ها -->
                @if($selectedCustomerId && count($currencyPercentages) > 0)
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 mt-6 w-[589px]">
                    <div class="space-y-4">
                        <div
                            class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-[#2563EB] text-white text-[16px] rounded-[12px]">
                            <p class="vazir font-bold">ارزش کل موجودی</p>
                            <p class="vazir font-bold">
                                @php
                                $totalUSD = 0;
                                foreach($selectedCustomerBalance as $balance) {
                                $totalUSD += $balance['balance_usd'];
                                }
                                @endphp
                                {{ number_format($totalUSD, 2) }}
                                <span>دالر</span>
                            </p>
                        </div>

                        @foreach($currencyPercentages as $currencyCode => $data)
                        <div
                            class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-transparent border border-[#2563EB] text-black text-[16px] rounded-[12px]">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $data['color'] }}">
                                </div>
                                <span class="vazir font-bold">{{ $data['currency_name'] }}</span>
                            </div>
                            <div class="text-left">
                                <p class="vazir font-bold">{{ number_format($data['balance'], 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @elseif($selectedCustomerId)
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">این مشتری موجودی ندارد</p>
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">لطفاً یک مشتری انتخاب کنید</p>
                </div>
                @endif
            </div>

            <!-- بخش نمودار SVG -->
            @if($selectedCustomerId && count($currencyPercentages) > 0)
            <div class="md:w-1/2 mt-6">
                @php
                $chartData = [
                'series' => [],
                'labels' => [],
                'colors' => [],
                'total' => 0
                ];

                foreach($currencyPercentages as $currency) {
                $chartData['series'][] = $currency['percentage'];
                $chartData['labels'][] = $currency['currency_name'];
                $chartData['colors'][] = $currency['color'];
                $chartData['total'] += $currency['percentage'];
                }

                $radius = 80;
                $circumference = 2 * 3.1416 * $radius;
                $currentOffset = 0;
                @endphp

                <div class="p-6 relative">
                    <div class="relative w-[300px] h-[300px] mx-auto">
                        <svg width="400" height="400" viewBox="0 0 40 40">
                            <!-- تعریف گرادینت‌ها -->
                            <defs>
                                @foreach($chartData['colors'] as $index => $color)
                                @php
                                $lighterColor = $this->lightenColor($color, 30);
                                $darkerColor = $this->darkenColor($color, 20);
                                @endphp
                                <linearGradient id="gradient-{{ $index }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="{{ $lighterColor }}" />
                                    <stop offset="100%" stop-color="{{ $darkerColor }}" />
                                </linearGradient>

                                <radialGradient id="radial-gradient-{{ $index }}" cx="50%" cy="50%" r="50%" fx="50%"
                                    fy="50%">
                                    <stop offset="0%" stop-color="{{ $lighterColor }}" />
                                    <stop offset="70%" stop-color="{{ $color }}" />
                                    <stop offset="100%" stop-color="{{ $darkerColor }}" />
                                </radialGradient>
                                @endforeach
                            </defs>

                            @php
                            $total = array_sum($chartData['series']);
                            $startAngle = 0;
                            $radius = 20;
                            @endphp

                            @foreach ($chartData['series'] as $index => $value)
                            @php
                            $percentage = ($value / $total) * 100;
                            $angle = ($value / $total) * 360;

                            $x1 = 20 + $radius * cos(deg2rad($startAngle));
                            $y1 = 20 + $radius * sin(deg2rad($startAngle));

                            $endAngle = $startAngle + $angle;
                            $x2 = 20 + $radius * cos(deg2rad($endAngle));
                            $y2 = 20 + $radius * sin(deg2rad($endAngle));

                            $largeArc = ($angle > 180) ? 1 : 0;
                            $path = "M20,20 L$x1,$y1 A$radius,$radius 0 $largeArc,1 $x2,$y2 Z";

                            $midAngle = $startAngle + $angle / 2;
                            $textX = 20 + ($radius * 0.55) * cos(deg2rad($midAngle));
                            $textY = 20 + ($radius * 0.55) * sin(deg2rad($midAngle));
                            @endphp

                            <path d="{{ $path }}" fill="url(#radial-gradient-{{ $index }})" stroke="white"
                                stroke-width="0.3"></path>

                            <text x="{{ $textX }}" y="{{ $textY }}" font-size="2.7" fill="white" text-anchor="middle"
                                alignment-baseline="middle"
                                style="font-weight: bold; text-shadow: 0px 0px 3px rgba(0,0,0,0.5);">
                                {{ round($percentage, 1) }}%
                            </text>

                            @php
                            $startAngle += $angle;
                            @endphp
                            @endforeach
                        </svg>
                    </div>

                    <!-- لیبل‌ها کنار چارت -->
                    <div class="absolute top-9 align-middle flex flex-col justify-center items-center gap-6">
                        @foreach($chartData['labels'] as $index => $label)
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 rounded-full shadow-sm"
                                style="background: linear-gradient(135deg, {{ $this->lightenColor($chartData['colors'][$index], 30) }}, {{ $this->darkenColor($chartData['colors'][$index], 20) }});">
                            </div>
                            <span class="text-sm vazir text-gray-700">
                                {{ $label }} ({{ round($chartData['series'][$index], 1) }}%)
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif


 </div>
</div>