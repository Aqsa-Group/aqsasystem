<div>
    <div class="pl-10 pr-10 mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black">تبدیل ارز در حساب مشتری</h1>
            <h1 class="text-[#8C8C8C] text-[18px]">صفحه تبدیل ارز در حساب مشتری</h1>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <!-- پیام‌های سیستم -->
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('message') }}
                </h2>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#DC2626] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('error') }}
                </h2>
            </div>
        </div>
        @endif


        {{-- کارت‌های ارزها با اسکرول افقی --}}
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 -mt-5">



            @if($selectedCustomer)
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">

                    {{-- عکس مشتری --}}
                    <div x-data="{ 
    showLargeImage: false, 
    largeImageSrc: '',
    customerName: '{{ addslashes($selectedCustomer->fullname) }}',
    customerPhone: '{{ addslashes($selectedCustomer->phone ?? '') }}'
}">

                        {{-- عکس مشتری --}}
                        @if($selectedCustomer->image)
                        <div class="flex justify-center mb-2">
                            <img src="{{ Storage::url($selectedCustomer->image) }}"
                                alt="{{ $selectedCustomer->fullname }}" class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer 
                   hover:scale-105 transition-transform duration-200 hover:shadow-lg"
                                @click="showLargeImage = true; largeImageSrc = '{{ Storage::url($selectedCustomer->image) }}'"
                                onerror="this.onerror=null; this.src='{{ asset('assets/web.jpg') }}'"
                                title="برای بزرگنمایی کلیک کنید">
                        </div>
                        @else
                        <div class="flex justify-center mb-2">
                            <img src="{{ asset('assets/web.jpg') }}" alt="{{ $selectedCustomer->fullname }}" class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer 
                   hover:scale-105 transition-transform duration-200 hover:shadow-lg"
                                @click="showLargeImage = true; largeImageSrc = '{{ asset('assets/web.jpg') }}'"
                                title="برای بزرگنمایی کلیک کنید">
                        </div>
                        @endif

                        {{-- مودال نمایش عکس بزرگ --}}
                        <div x-show="showLargeImage" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 p-4"
                            @click.away="showLargeImage = false" @keydown.escape.window="showLargeImage = false"
                            style="display: none;">

                            <div class="relative w-full max-w-5xl">

                                {{-- دکمه بستن --}}
                                <button @click="showLargeImage = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 
                           text-3xl z-10 transition-colors duration-200 p-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                {{-- عکس بزرگ --}}
                                <div class="flex justify-center">
                                    <img :src="largeImageSrc" :alt="customerName" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl 
                            border-4 border-white/20">
                                </div>

                                {{-- اطلاعات مشتری --}}
                                <div class="mt-6 text-center text-white">
                                    <p class="text-2xl font-bold mb-2" x-text="customerName"></p>

                                    <template x-if="customerPhone">
                                        <p class="text-lg text-gray-300" x-text="customerPhone"></p>
                                    </template>

                                    {{-- دکمه‌های عملیات --}}
                                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                                        {{-- دکمه دانلود --}}
                                        <a :href="largeImageSrc"
                                            :download="customerName + '_' + new Date().toISOString().split('T')[0] + '.jpg'"
                                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg 
                              transition-colors duration-200 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            دانلود
                                        </a>


                                        {{-- دکمه بستن --}}
                                        <button @click="showLargeImage = false" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg 
                                   transition-colors duration-200">
                                            بستن
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    {{-- نام مشتری --}}
                    <h1 class="text-[20px] text-white text-center font-bold truncate"
                        title="{{ $selectedCustomer->fullname }}">
                        {{ $selectedCustomer->fullname }}
                    </h1>

                    {{-- شماره تماس --}}
                    @if($selectedCustomer->phone)
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left">{{ $selectedCustomer->phone }}</span>
                    </div>
                    @endif

                    {{-- شماره حساب --}}
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left">{{ $selectedCustomer->account_number
                            }}</span>
                    </div>

                </div>
            </div>
            @endif

            @foreach ($currencies as $currencyItem)
            @php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            @endphp

            {{-- نمایش تمام کارت‌ها حتی با موجودی صفر --}}
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div
                    class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] bg-gradient-to-b from-[#2563EB] to-[#5474BB] text-white">

                    <h1 class="text-[24px] text-white">{{ $currencyName }}</h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold text-left" dir="ltr">{{ number_format($cashBalance) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold text-left" dir="ltr">{{ number_format($bankBalance) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px] text-left" dir="ltr">{{ number_format($totalBalance)
                                }}</span>
                        </div>
                    </div>

                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800 hover:shadow-md transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>نمایش گزارش</span>
                        <span wire:loading>
                            در حال انتقال...
                        </span>
                    </button>
                </div>
            </div>
            @endforeach

            {{-- کارت خلاصه بیلانس به دالر --}}
            @if($selectedCustomerId)
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div
                    class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] bg-gradient-to-b from-[#11BEC7] to-[#6371D0] text-white">

                    @php
                    // تابع تبدیل کد ارز به نام فارسی
                    function getPersianCurrencyName($currencyCode) {
                    $currencyMap = [
                    'afn' => 'افغانی',
                    'usd' => 'دالر',
                    'irr' => 'تومان',
                    'eur' => 'یورو',
                    'pkr' => 'کلدار',
                    'aed' => 'درهم',
                    'try' => 'لیره',
                    'cny' => 'یوان',
                    'gbp' => 'پوند',
                    'jpy' => 'ین',
                    'sar' => 'ریال سعودی',
                    'inr' => 'روپیه',
                    ];

                    $currencyCode = strtolower($currencyCode ?? 'usd');
                    return $currencyMap[$currencyCode] ?? $currencyCode;
                    }

                    $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                    $sourceCurrency = getPersianCurrencyName($latestProfitRate->source_currency ?? 'usd');
                    @endphp

                    <h1 class="text-[24px] text-white">خلاصه بیلانس به {{ $sourceCurrency }}</h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        @php
                        $totalCashUsd = 0;
                        $totalBankUsd = 0;
                        $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();

                        // تعریف نرخ‌های خرید نقدی
                        $exchangeRatesCash = [
                        'افغانی' => $latestProfitRate->afn_buy_cash ?? 66.20,
                        'دالر' => $latestProfitRate->usd_buy_cash ?? 1,
                        'تومان' => $latestProfitRate->irr_buy_cash ?? 110000.00,
                        'یورو' => $latestProfitRate->eur_buy_cash ?? 70.00,
                        'کلدار' => $latestProfitRate->pkr_buy_cash ?? 32.00,
                        'درهم' => $latestProfitRate->aed_buy_cash ?? 44.00,
                        'لیره' => $latestProfitRate->try_buy_cash ?? 60.00,
                        'یوان' => $latestProfitRate->cny_buy_cash ?? 43.00,
                        'روپیه' => $latestProfitRate->inr_buy_cash ?? 7.14,
                        ];

                        // تعریف نرخ‌های خرید بانکی
                        $exchangeRatesBank = [
                        'افغانی' => $latestProfitRate->afn_buy_bank ?? 66.20,
                        'دالر' => $latestProfitRate->usd_buy_bank ?? 1,
                        'تومان' => $latestProfitRate->irr_buy_bank ?? 110000.00,
                        'یورو' => $latestProfitRate->eur_buy_bank ?? 70.00,
                        'کلدار' => $latestProfitRate->pkr_buy_bank ?? 32.00,
                        'درهم' => $latestProfitRate->aed_buy_bank ?? 44.00,
                        'لیره' => $latestProfitRate->try_buy_bank ?? 60.00,
                        'یوان' => $latestProfitRate->cny_buy_bank ?? 43.00,
                        'روپیه' => $latestProfitRate->inr_buy_bank ?? 7.14,
                        ];

                        // محاسبه موجودی نقدی به دالر با استفاده از نرخ خرید نقدی
                        foreach($customerCashBalances as $currency => $balance) {
                        if(isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                        $totalCashUsd += $balance / $exchangeRatesCash[$currency];
                        }
                        }

                        // محاسبه موجودی بانکی به دالر با استفاده از نرخ خرید بانکی
                        foreach($customerBankBalances as $currency => $balance) {
                        if(isset($exchangeRatesBank[$currency]) && $exchangeRatesBank[$currency] > 0) {
                        $totalBankUsd += $balance / $exchangeRatesBank[$currency];
                        }
                        }
                        $grandTotalUsd = $totalCashUsd + $totalBankUsd;
                        @endphp

                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold text-left" dir="ltr">{{ number_format($totalCashUsd, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold text-left" dir="ltr">{{ number_format($totalBankUsd, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px] text-left" dir="ltr">{{ number_format($grandTotalUsd, 2)
                                }}</span>
                        </div>
                    </div>

                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800 hover:shadow-md transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>نمایش گزارش</span>
                        <span wire:loading>
                            در حال انتقال...
                        </span>
                    </button>
                </div>
            </div>
            @endif
        </div>
        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            <!-- فرم تبدیل ارز -->
            <div class="flex flex-col bg-[#F5F5F5] mx-auto w-[420px] lg:w-[534px] mb-6 p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- هدر فرم -->
                <div
                    class="flex flex-row justify-between p-[20px] border border-[#8C8C8C] rounded-[12px] flex-wrap items-center">
                    <p class="flex justify-between items-center text-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/pencil.svg') }}" alt="" class="h-6 w-6">
                        <span class="vazir font-semibold">فورم تبدیل ارز در حساب</span>
                    </p>

                    <button wire:click="toggleTransactionType" class="rounded-[8px] p-[10px] text-white vazir px-12 font-semibold transition-colors duration-500 ease-in-out
                        {{ $transactionType === 'خرید' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                        {{ $transactionType === 'خرید' ? 'خرید' : 'فروش' }}
                    </button>
                    <button wire:click="toggleAccountType" class="rounded-[8px] p-[10px] text-white vazir px-12 font-semibold transition-colors duration-500 ease-in-out
                        {{ $accountType === 'نقدی' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                        {{ $accountType === 'نقدی' ? 'نقدی' : 'بانکی' }}
                    </button>

                </div>

                <!-- فرم اصلی -->
                <form wire:submit.prevent="submitConversion">
                    <!-- انتخاب حساب -->
                    <div class="mt-2">
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب مشتری</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: @entangle('selectedAccount'),
                                customers: @js($customers),
                                init() {
                                    this.updateDisplay();
                                    
                                    $wire.on('edit-mode-activated', (data) => {
                                        this.selectedId = data.selectedAccount;
                                        this.searchValue = data.selectedCustomer;
                                        setTimeout(() => {
                                            this.updateDisplay();
                                        }, 100);
                                    });
                                    
                                    $wire.on('transactionTypeToggled', () => {
                                        setTimeout(() => {
                                            this.updateDisplay();
                                        }, 100);
                                    });
                                },
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.selectAccount(selected.id);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('selectedAccount', null);
                                    }
                                },
                                updateDisplay() {
                                    if (this.selectedId) {
                                        const selected = this.customers.find(c => c.id == this.selectedId);
                                        if (selected) {
                                            this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        }
                                    }
                                }
                            }" x-init="init()" class="relative w-full">
                                <input list="customersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب مشتری..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="customersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
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

                    <!-- بخش تبدیل ارز -->
                    <div class="mt-4 rounded-[12px]">
                        <div class="mt-2 flex flex-col lg:flex-row gap-3">
                            <!-- ارز مبدا -->
                            <div class="lg:w-[191px]">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    ارز خرید
                                    @else
                                    ارز فروش
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <select wire:model="from_currency"
                                        class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                        <option value="">انتخاب ارز</option>
                                        @foreach ($currencies as $c)
                                        <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                            class="w-4 h-4">
                                    </div>
                                </div>
                                @error('from_currency')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- مبلغ خرید -->
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    مبلغ خرید
                                    @else
                                    مبلغ فروش
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="buy_amount" placeholder="0"
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                </div>
                                @if($withdrawalAmountInWords)
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong></strong> {{ $withdrawalAmountInWords }}
                                </div>
                                @endif
                                @error('buy_amount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- بخش دریافت -->
                    <div class="mt-4 rounded-[12px]">
                        <div class="mt-2 flex flex-col lg:flex-row gap-3">
                            <!-- ارز مقصد -->
                            <div class="lg:w-[191px]">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    ارز فروش
                                    @else
                                    ارز خرید
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <select wire:model="to_currency"
                                        class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                        <option value="">انتخاب ارز</option>
                                        @foreach ($currencies as $c)
                                        <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                            class="w-4 h-4">
                                    </div>
                                </div>
                                @error('to_currency')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- نرخ ارز -->
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    نرخ فروش
                                    @else
                                    نرخ خرید
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="currency_rate" placeholder="0.0000"
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                    @error('currency_rate')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if($currencyRateInWords)
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong></strong> {{ $currencyRateInWords }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- مبلغ فروش و تاریخ -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- مبلغ فروش -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">
                                @if($transactionType === 'خرید')
                                مبلغ فروش
                                @else
                                مبلغ خرید
                                @endif
                            </label>
                            <div class="relative w-full">
                                <input type="text" wire:model="sell_amount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 bg-gray-100"
                                    readonly />
                            </div>
                            @if($receivedAmountInWords)
                            <div class="mt-2 text-sm text-gray-600">
                                <strong></strong> {{ $receivedAmountInWords }}
                            </div>
                            @endif
                            @error('sell_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- تاریخ -->
                        <div class="flex-1 relative">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="transaction_date" placeholder="1404/4/20"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                <svg class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                    <path
                                        d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                        stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <path
                                        d="M15.6947 13.7H15.7037M15.6947 16.7H15.7037M11.9955 13.7H12.0045M11.9955 16.7H12.0045M8.29431 13.7H8.30329M8.29431 16.7H8.30329"
                                        stroke="#8C8C8C" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                @error('transaction_date')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات مسئولین -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- توسط برداشت -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (برداشت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_sender" placeholder="نام مسئول برداشت"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                @error('by_sender')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- توسط دریافت -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (دریافت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_receiver" placeholder="نام مسئول دریافت"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                @error('by_receiver')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- زون‌ها -->
                    {{-- زون برداشت و دریافت --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون برداشت</label>
                            <select wire:model="zone_sender"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب زون</option>
                                @foreach($zones as $zone)
                                <option value="{{ $zone }}">{{ $zone }}</option>
                                @endforeach
                            </select>
                            @error('zone_sender')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون دریافت</label>
                            <select wire:model="zone_receiver"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب زون</option>
                                @foreach($zones as $zone)
                                <option value="{{ $zone }}">{{ $zone }}</option>
                                @endforeach
                            </select>
                            @error('zone_receiver')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- شرح تراکنش -->
                    <div class="mt-3">
                        <div class="w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح تراکنش</label>
                            <textarea wire:model="description" rows="3" placeholder="شرح کامل تبدیل ارز..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 resize-none"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- دکمه‌های نهایی -->
                    <div class="flex flex-wrap justify-center items-center gap-4 py-4 text-center">
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-[#2563EB] text-[14px] vazir font-semibold rounded-[8px] px-16 py-4 text-white hover:bg-blue-700 transition disabled:opacity-50">
                            @if($editingConversionId)
                            <span wire:loading.remove>ویرایش تبدیل ارز</span>
                            @else
                            <span wire:loading.remove>ثبت تبدیل ارز</span>
                            @endif
                            <span wire:loading>در حال ثبت...</span>
                        </button>

                        <button type="button" wire:click="resetForm" wire:loading.attr="disabled"
                            class="bg-[#DD2424] text-[14px] vazir font-semibold rounded-[8px] px-16 py-4 text-white hover:bg-red-700 transition">
                            @if($editingConversionId) انصراف از ویرایش @else انصراف @endif
                        </button>
                    </div>

                </form>
            </div>

            <!-- جدول تراکنش‌های تبدیل ارز -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]  w-[440px] mb-5 md:w-[930px] lg:w-[150px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-full ">
                            <input type="text" wire:model.live="search" wire:keydown.debounce.500ms="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام،...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            @if($search)
                            <button wire:click="$set('search', '')"
                                class="absolute left-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- جدول -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-full">
                        <table class="w-[890px] text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead
                                class="bg-[#2B65E5] text-white text-[14px] w-full md:text-[16px] vazir h-[50px] md:h-[60px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-2 py-3 font-bold w-12">#</th>
                                    <th class="px-2 py-3 font-bold w-32">مشتری</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ ارز برداشت</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ ارز دریافت</th>
                                    <th class="px-2 py-3 font-bold w-24">نرخ ارز</th>
                                    <th class="px-2 py-3 font-bold w-36 text-center">توضیحات</th>
                                    <th class="px-2 py-3 font-bold w-28">تاریخ</th>
                                    <th class="px-2 py-3 font-bold w-32 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversionTransactions as $key => $conversion)
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium w-32">
                                        <div class="truncate" title="{{ $conversion->customer->fullname ?? '-' }}">
                                            {{ $conversion->customer->fullname ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $conversion->customer->account_number ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 vazir text-[13px] md:text-[16px] font-medium w-52">
                                        <div class="text-left">
                                            <span class="">{{ number_format($conversion->buy_amount) }} ({{
                                                $this->getCurrencyName($conversion->from_currency) }})</span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[16px] w-44">
                                        <div class="text-left">
                                            <span class="">{{ number_format($conversion->sell_amount) }} ({{
                                                $this->getCurrencyName($conversion->to_currency) }})</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px] w-44">
                                        {{ number_format($conversion->currency_rate, 2) }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[13px] md:text-[18px] font-medium w-36">
                                        <div class="text-right truncate" title="{{ $conversion->description }}">
                                            {{ Str::limit($conversion->description, 35) }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium text-[16px]">
                                                {{ explode(' ', $conversion->transaction_date)[0] }}
                                            </div>
                                            <div class="text-gray-500 text-[16px] mt-1">
                                                {{ \Carbon\Carbon::parse($conversion->created_at)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center w-32">
                                        <div class="flex justify-center gap-2">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="editConversion({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-blue-100"
                                                title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-7 h-7" alt="Delete">
                                            </button>

                                            <button wire:click="printTransaction({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                                title="پرینت PDF">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-9 h-9" alt="Print">
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 vazir text-[14px]">
                                        هیچ تراکنش تبدیلی یافت نشد.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- صفحه‌بندی -->
                @if($conversionTransactions->hasPages())
                <div class="mt-4 px-4">
                    {{ $conversionTransactions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- مودال تأیید حذف -->
    @if ($confirmDeleteId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="{{ asset('assets/sarafi/all_icon/close.svg') }}" alt="بستن" class="w-4 h-4">
            </button>

            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف تراکنش تبدیل ارز</h1>
            <hr class="bg-[#E1DED3] mt-4 mx-4">
            <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این تراکنش را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                    خیر
                </button>
                <button wire:click="deleteConversion"
                    class="px-12 py-3 bg-[#2563EB] text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                    بلی
                </button>
            </div>
        </div>
    </div>
    @endif

    <style>
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #f9fafb;
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
            margin-top: 20px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 10px;
            margin: 0 16px 10px 16px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        input[list]::-moz-list-button {
            display: none !important;
        }

        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }
    </style>
</div>