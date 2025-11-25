<div class="px-5">
    <!-- دکمه‌های دسته‌بندی -->
    <div class="w-[1200px]">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-3 mb-5">
            <button wire:click="selectCategory('customers')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارشات مالی مشتریان
            </button>
            <button wire:click="selectCategory('accounts')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارش حسابات و صندوق
            </button>
            <button wire:click="selectCategory('transactions')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارشات تراکنش های معاملات
            </button>
            <button wire:click="selectCategory('management')"
                class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px] hover:bg-blue-700 transition-colors">
                گزارشات مدیریتی و تحلیلی
            </button>
        </div>
    </div>

    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        
        <!-- select زیرشاخه -->
        @if($selectedCategory)
        <div class="mb-5 relative w-full">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                    <tr class="border-b hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 rounded-lg bg-gray-100">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $report['account_number'] }}
                                        </td>
                                        <td class="px-4 py-4">{{ $report['fullname'] }}</td>
                                        <td class="px-3 py-4">
                                            {{ $report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') : '-' }}
                                        </td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['usd'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['afn'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['irr'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['pkr'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['eur'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['aed'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['try'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 text-left">{{ number_format($report['balances']['cny'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-4 font-medium text-left">{{ number_format($report['total_balance'], 2) }}</td>
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
                    <div class="w-full rounded-[16px] bg-[#2563EB] p-4 mb-6">
                        <div class="flex-1">
                            <div class="relative w-full" x-data="{
                                    searchValue: '',
                                    searchQuery: '',
                                    selectedIds: @entangle('selectedAccounts'),
                                    customers: @js($customers->toArray()),
                                    isOpen: false,
                                    selectedCustomers: [],
                                    get filteredCustomers() {
                                        if (this.searchQuery === '') return this.customers;
                                        const query = this.searchQuery.toLowerCase();
                                        return this.customers.filter(customer =>
                                            customer.fullname.toLowerCase().includes(query) ||
                                            customer.account_number.toLowerCase().includes(query)
                                        );
                                    },
                                    init() {
                                        this.selectedCustomers = this.customers.filter(customer => 
                                            this.selectedIds.includes(customer.id)
                                        );
                                        this.updateDisplay();
                                    },
                                    toggleCustomer(customer) {
                                        const index = this.selectedCustomers.findIndex(c => c.id === customer.id);
                                        if (index > -1) {
                                            this.selectedCustomers.splice(index, 1);
                                        } else {
                                            this.selectedCustomers.push(customer);
                                        }

                                        this.selectedIds = this.selectedCustomers.map(c => c.id);
                                        this.updateDisplay();
                                        $wire.set('selectedAccounts', this.selectedIds);
                                    },
                                    isSelected(customerId) {
                                        return this.selectedCustomers.some(c => c.id === customerId);
                                    },
                                    updateDisplay() {
                                        this.searchValue = this.selectedCustomers.length
                                            ? this.selectedCustomers.map(c => `${c.account_number} - ${c.fullname}`).join(', ')
                                            : '';
                                    },
                                    clearAll() {
                                        this.selectedCustomers = [];
                                        this.selectedIds = [];
                                        this.searchQuery = '';
                                        this.updateDisplay();
                                        $wire.set('selectedAccounts', []);
                                    }
                                }" x-init="init()" @click.outside="isOpen = false">

                                <!-- Input Field -->
                                <div @click="isOpen = true" class="relative">
                                    <input type="text" x-model="searchValue" placeholder="انتخاب مشتری"
                                        class="w-full h-[60px] p-3 pr-10 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 cursor-pointer text-white placeholder-white"
                                        readonly>

                                    <!-- Dropdown Arrow -->
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': isOpen}" class="transition-transform">
                                            <path
                                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                                stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>

                                    <!-- Clear Button -->
                                    <template x-if="selectedCustomers.length > 0">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer"
                                            @click.stop="clearAll()">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- Dropdown Menu -->
                                <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
                                    @click.stop>

                                    <!-- Search Box inside Dropdown -->
                                    <div class="sticky top-0 bg-white p-2 border-b">
                                        <input type="text" x-model="searchQuery" placeholder="جستجوی مشتری..."
                                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <!-- Customers List -->
                                    <template x-for="customer in filteredCustomers" :key="customer.id">
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer border-b transition-colors">
                                            <!-- Checkbox سمت چپ -->
                                            <input type="checkbox" :checked="isSelected(customer.id)"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                @click.stop="toggleCustomer(customer)">

                                            <!-- Customer Info -->
                                            <div class="flex-1 text-right mr-3">
                                                <div class="font-medium text-gray-900" x-text="customer.fullname"></div>
                                                <div class="text-sm text-gray-500"
                                                    x-text="'شماره حساب: ' + customer.account_number"></div>
                                            </div>
                                        </label>
                                    </template>

                                    <!-- No Results -->
                                    <div x-show="filteredCustomers.length === 0" class="px-3 py-2 text-gray-500 text-center">
                                        مشتری یافت نشد
                                    </div>
                                </div>

                                @error('selectedAccounts')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h1 class="mt-5 mr-4 text-xl font-bold text-gray-800">گزارش خلاصه بیلانس مشتریان انتخاب شده</h1>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 p-4 mb-8">
                        @foreach($totalBalances as $currencyCode => $data)
                        <div
                            class="flex flex-col bg-white justify-center items-center gap-3 rounded-xl py-6 px-4 shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                            <p class="text-gray-500 text-sm font-medium">{{ $data['currency_name'] }}</p>
                            <p class="text-2xl font-bold text-gray-800 times">
                                {{ number_format($data['total']) }}
                            </p>
                            <p class="text-gray-400 text-xs font-medium uppercase">
                                @switch($currencyCode)
                                    @case('usd') USD @break
                                    @case('afn') AFN @break
                                    @case('irr') IRR @break
                                    @case('eur') EUR @break
                                    @case('pkr') PKR @break
                                    @case('aed') AED @break
                                    @case('try') TRY @break
                                    @case('cny') CNY @break
                                    @default {{ $currencyCode }}
                                @endswitch
                            </p>
                        </div>
                        @endforeach
                    </div>

                    <!-- نمودار میله‌ای -->
                    <div class=" p-6  mb-8">
                        <svg width="100%" height="350" viewBox="0 0 1000 350" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="barGradient" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#153885" />
                                    <stop offset="100%" stop-color="#2563EB" />
                                </linearGradient>
                            </defs>

                            <!-- محور عمودی سمت چپ برای جدا کردن مقادیر -->
                            <line x1="146" y1="30" x2="146" y2="280" stroke="#94a3b8" stroke-width="2" />
                            
                            <!-- محور افقی -->
                            <line x1="100" y1="280" x2="950" y2="280" stroke="#94a3b8" stroke-width="2" />

                            <!-- مقادیر عمودی سمت چپ (چپ‌چین شده) -->
                            <text x="90" y="285" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b">0</text>
                            <text x="90" y="235" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b">{{
                                number_format($maxValue * 0.2, 0) }}</text>
                            <text x="90" y="185" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b">{{
                                number_format($maxValue * 0.4, 0) }}</text>
                            <text x="90" y="135" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b">{{
                                number_format($maxValue * 0.6, 0) }}</text>
                            <text x="90" y="85" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b">{{
                                number_format($maxValue * 0.8, 0) }}</text>
                            <text x="90" y="35" font-family="Arial" font-size="12" text-anchor="end" fill="#64748b">{{
                                number_format($maxValue, 0) }}</text>

                            <!-- خطوط راهنمای افقی -->
                            <line x1="100" y1="280" x2="950" y2="280" stroke="#e2e8f0" stroke-width="1" />
                            <line x1="100" y1="230" x2="950" y2="230" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                            <line x1="100" y1="180" x2="950" y2="180" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                            <line x1="100" y1="130" x2="950" y2="130" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                            <line x1="100" y1="80" x2="950" y2="80" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />
                            <line x1="100" y1="30" x2="950" y2="30" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4" />

                            <!-- میله‌های نمودار -->
                            @php
                            $chartHeight = 250; // 280 - 30
                            $barWidth = 50;
                            $spacing = 30;
                            $startX = 140; // افزایش یافته تا از خط جداکننده فاصله بگیرد
                            @endphp

                            @foreach($chartData as $index => $item)
                            @php
                            $barHeight = $maxValue > 0 ? ($item['value'] / $maxValue) * $chartHeight : 0;
                            $x = $startX + ($index * ($barWidth + $spacing));
                            $y = 280 - $barHeight;

                            // فرمت مقدار برای نمایش
                            $displayValue = $item['value'] >= 1000000
                            ? number_format($item['value'] / 1000000, 1) . 'M'
                            : ($item['value'] >= 1000
                            ? number_format($item['value'] / 1000, 1) . 'K'
                            : number_format($item['value'], 0));
                            @endphp

                            <!-- میله -->
                            <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barWidth }}" height="{{ $barHeight }}" fill="url(#barGradient)"
                                rx="2" />

                            <!-- مقدار بالای میله -->
                            <text x="{{ $x + $barWidth / 2 }}" y="{{ $y - 10 }}" font-family="Arial" font-size="12" text-anchor="middle"
                                fill="#374151">
                                {{ $displayValue }}
                            </text>

                            <!-- نام ارز زیر میله -->
                            <text x="{{ $x + $barWidth / 2 }}" y="300" font-family="Arial" font-size="12" text-anchor="middle"
                                fill="#374151">
                                {{ $item['currency'] }}
                            </text>
                            @endforeach
                        </svg>
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

    <!-- بخش گزارش بیلانس مشتریان (کارت‌ها و نمودار دایره‌ای) -->
    @if($selectedSubCategory == 'گزارش بیلانس مشتریان')
    <div class="flex-1 flex flex-col bg-[#F5F5F5] h-fit p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="flex w-full flex-col lg:flex-row">
            <div class="lg:w-1/2 mb-6 lg:mb-0">
                <div class="flex-1">
                    <div class="relative w-full lg:w-[589px]">
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
                                        $wire.call('selectCustomer', selected.id);
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
                @if($selectedCustomerId)
                    @php
                        $hasNonZeroBalance = false;
                        foreach($selectedCustomerBalance as $balance) {
                            if (abs($balance['balance']) > 0.001) {
                                $hasNonZeroBalance = true;
                                break;
                            }
                        }
                    @endphp

                    @if($hasNonZeroBalance)
                    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 mt-6 w-full lg:w-[589px]">
                        <div class="space-y-4">
                            <div
                                class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-[#2563EB] text-white text-[16px] rounded-[12px]">
                                <p class="vazir font-bold">ارزش کل موجودی</p>
                                <p class="vazir font-bold">
                                    @php
                                    $totalUSD = 0;
                                    foreach($selectedCustomerBalance as $balance) {
                                        if (abs($balance['balance']) > 0.001) {
                                            $totalUSD += $balance['balance_usd'];
                                        }
                                    }
                                    @endphp
                                    {{ number_format($totalUSD, 2) }}
                                    <span>دالر</span>
                                </p>
                            </div>

                            @foreach($selectedCustomerBalance as $currencyCode => $data)
                                @if(abs($data['balance']) > 0.001)
                                <div
                                    class="w-full h-[79px] flex flex-col md:flex-row items-center justify-between p-6 bg-transparent border border-[#2563EB] text-black text-[16px] rounded-[12px] hover:bg-blue-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $this->getCurrencyColor($currencyCode) }}">
                                        </div>
                                        <span class="vazir font-bold">{{ $data['currency_name'] }}</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="vazir font-bold">{{ number_format($data['balance'], 2) }}</p>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 vazir">این مشتری موجودی ندارد</p>
                        <!-- اطلاعات دیباگ -->
                        <div class="mt-4 p-3 bg-gray-100 rounded text-xs text-gray-600">
                            <p>Customer ID: {{ $selectedCustomerId }}</p>
                            <p>Balances Count: {{ count($selectedCustomerBalance) }}</p>
                        </div>
                    </div>
                    @endif
                @else
                <div class="text-center py-8">
                    <p class="text-gray-500 vazir">لطفاً یک مشتری انتخاب کنید</p>
                </div>
                @endif
            </div>

            <!-- بخش نمودار SVG -->
            @if($selectedCustomerId && count($currencyPercentages) > 0)
            <div class="lg:w-1/2 mt-6 lg:mt-0 flex justify-center">
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
                    <div class="relative w-full max-w-[400px] h-[350px] mx-auto">
                        <svg width="100%" height="100%" viewBox="0 0 40 40">
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
                    <div class="absolute top-9 right-4 align-middle flex flex-col justify-center items-start gap-4">
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

    <!-- بخش کارت‌های مشتریان برای گزارش خلاصه -->
    @if($selectedSubCategory == 'گزارش خلاصه بیلانس مشتریان' && !empty($selectedCustomersData))
    <div class="mt-8 bg-[#F5F5F5] p-5 rounded-[16px] mb-5"  style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <h2 class="text-xl font-bold text-gray-800 mb-6">کارت‌های موجودی مشتریان انتخاب شده</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($selectedCustomersData as $customer)
            @php
            // محاسبه کل موجودی و فیلتر ارزهای دارای موجودی
            $customerTotalUSD = 0;
            $customerCurrenciesWithBalance = [];

            foreach($customer['balances'] as $currencyCode => $balanceData) {
                if (abs($balanceData['balance']) > 0.001) {
                    $balanceUSD = $this->convertToUSD($balanceData['balance'], $currencyCode);
                    $customerTotalUSD += $balanceUSD;
                    $customerCurrenciesWithBalance[$currencyCode] = [
                        'balance' => $balanceData['balance'],
                        'currency_name' => $balanceData['currency_name'],
                        'color' => $this->getCurrencyColor($currencyCode)
                    ];
                }
            }
            @endphp

            <!-- کارت مشتری -->
            <div class="w-full">
                <div class="space-y-4">
                    <!-- هدر کارت -->
                    <div
                        class="w-full h-[60px] flex flex-col md:flex-row items-center justify-between p-4 bg-[#2563EB] text-white text-[14px] rounded-[12px]">
                        <p class="vazir font-bold truncate" title="{{ $customer['name'] }}">{{ $customer['name'] }}</p>
                        <p class="vazir font-bold text-sm whitespace-nowrap">
                            {{ number_format($customerTotalUSD, 2) }} <span class="text-xs">دالر</span>
                        </p>
                    </div>

                    <!-- موجودی‌های ارزی -->
                    @if(count($customerCurrenciesWithBalance) > 0)
                        @foreach($customerCurrenciesWithBalance as $currencyCode => $data)
                        <div
                            class="w-full h-[50px] flex flex-col md:flex-row items-center justify-between p-4 bg-transparent border border-[#2563EB] text-black text-[14px] rounded-[12px] hover:bg-blue-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $data['color'] }}"></div>
                                <span class="vazir font-bold text-sm">{{ $data['currency_name'] }}</span>
                            </div>
                            <div class="text-left">
                                <p class="vazir font-bold text-sm">{{ number_format($data['balance'], 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div
                            class="w-full h-[50px] flex items-center justify-center p-4 bg-transparent border border-gray-300 text-gray-500 text-[14px] rounded-[12px]">
                            <span class="vazir text-sm">بدون موجودی</span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>