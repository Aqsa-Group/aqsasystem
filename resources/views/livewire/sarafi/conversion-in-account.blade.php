<div>
    <div class="pl-10 pr-10 mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black dark:text-white">تبدیل ارز در حساب مشتری</h1>
            <h1 class="text-[#8C8C8C] dark:text-white text-[18px]">صفحه تبدیل ارز در حساب مشتری</h1>
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
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">

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
                    class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#5474BB] text-white">

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
                <div class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px]
        dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900
        bg-gradient-to-b from-[#11BEC7] to-[#6371D0] text-white">

                    @php
                    /* =========================
                    تبدیل کد ارز به نام فارسی
                    ========================== */
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

                    $totalCashUsd = 0;
                    $totalBankUsd = 0;

                    /* =========================
                    نرخ‌های خرید نقدی
                    ========================== */
                    $exchangeRatesCash = [
                    'افغانی' => $latestProfitRate->afn_buy_cash ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_cash ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_cash ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_cash ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_cash ?? 44,
                    'لیره' => $latestProfitRate->try_buy_cash ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_cash ?? 43,
                    'روپیه' => $latestProfitRate->inr_buy_cash ?? 7.14,
                    ];

                    /* =========================
                    نرخ‌های خرید بانکی
                    ========================== */
                    $exchangeRatesBank = [
                    'افغانی' => $latestProfitRate->afn_buy_bank ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_bank ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_bank ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_bank ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_bank ?? 44,
                    'لیره' => $latestProfitRate->try_buy_bank ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_bank ?? 43,
                    'روپیه' => $latestProfitRate->inr_buy_bank ?? 7.14,
                    ];

                    /* =========================
                    محاسبه موجودی نقدی
                    ========================== */
                    foreach ($customerCashBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalCashUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                    $totalCashUsd += $balance / $exchangeRatesCash[$currency];
                    }
                    }

                    /* =========================
                    محاسبه موجودی بانکی
                    ========================== */
                    foreach ($customerBankBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalBankUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesBank[$currency]) && $exchangeRatesBank[$currency] > 0) {
                    $totalBankUsd += $balance / $exchangeRatesBank[$currency];
                    }
                    }

                    $grandTotalUsd = $totalCashUsd + $totalBankUsd;
                    @endphp

                    <h1 class="text-[24px] text-white">
                        خلاصه بیلانس به {{ $sourceCurrency }}
                    </h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold text-left" dir="ltr">
                                {{ number_format($totalCashUsd, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold text-left" dir="ltr">
                                {{ number_format($totalBankUsd, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px] text-left" dir="ltr">
                                {{ number_format($grandTotalUsd, 2) }}
                            </span>
                        </div>
                    </div>

                    <button wire:click="showReport" wire:loading.attr="disabled" class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800
                   hover:shadow-md transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>نمایش گزارش</span>
                        <span wire:loading>در حال انتقال...</span>
                    </button>

                </div>
            </div>
            @endif

        </div>
        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            <!-- فرم تبدیل ارز -->
            <div class="flex flex-col dark:bg-black dark:border-white dark:border bg-[#F5F5F5] mx-auto w-[420px] lg:w-[534px] mb-6 p-[12px] h-fit rounded-[12px] space-y-2"
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">حساب
                                مشتری</label>
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
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
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
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    ارز خرید
                                    @else
                                    ارز خرید
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <select wire:model="from_currency"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    مبلغ خرید
                                    @else
                                    مبلغ خرید
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="buy_amount" placeholder="0"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                </div>
                                @if($withdrawalAmountInWords)
                                <div class="mt-2 text-sm text-gray-600 dark:text-white">
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
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    ارز فروش
                                    @else
                                    ارز فروش
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <select wire:model="to_currency"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    @if($transactionType === 'خرید')
                                    نرخ خرید
                                    @else
                                    نرخ فروش
                                    @endif
                                </label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="currency_rate" placeholder="0.0000"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                    @error('currency_rate')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if($currencyRateInWords)
                                <div class="mt-2 text-sm text-gray-600 dark:text-white">
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                @if($transactionType === 'خرید')
                                مبلغ فروش
                                @else
                                مبلغ فروش
                                @endif
                            </label>
                            <div class="relative w-full">
                                <input type="text" wire:model="sell_amount" placeholder="0"
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 bg-gray-100"
                                    readonly />
                            </div>
                            @if($receivedAmountInWords)
                            <div class="mt-2 text-sm text-gray-600 dark:text-white">
                                <strong></strong> {{ $receivedAmountInWords }}
                            </div>
                            @endif
                            @error('sell_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- تاریخ -->
                        <div class="flex-1 relative">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="transaction_date" placeholder="1404/4/20"
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">توسط
                                (برداشت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_sender" placeholder="نام مسئول برداشت"
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                @error('by_sender')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- توسط دریافت -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">توسط
                                (دریافت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_receiver" placeholder="نام مسئول دریافت"
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">زون
                                برداشت</label>
                            <select wire:model="zone_sender"
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">زون
                                دریافت</label>
                            <select wire:model="zone_receiver"
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شرح
                                تراکنش</label>
                            <textarea wire:model="description" rows="3" placeholder="شرح کامل تبدیل ارز..."
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 resize-none"></textarea>
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
            <div class="flex-1 flex flex-col dark:bg-black dark:border dark:border-white  bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]  w-[440px] mb-5 md:w-[930px] lg:w-[150px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-full ">
                            <input type="text" wire:model.live="search" wire:keydown.debounce.500ms="search"
                                class="border dark:bg-black dark:border-white dark:placeholder:text-white border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام،...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 dark:hidden">
                            <svg width="24" height="24" viewBox="0 0 24 24"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

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
                                <tr class="text-black dark:text-white border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium w-32">
                                        <div class="truncate" title="{{ $conversion->customer->fullname ?? '-' }}">
                                            {{ $conversion->customer->fullname ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-white">
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
                                            <div class="text-gray-500 dark:text-white text-[16px] mt-1">
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
                                                    class="w-7 h-7 dark:hidden" alt="Edit">

                                                <svg width="22" height="22" class="hidden dark:block"
                                                    viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.082 1.83325H8.2487C3.66536 1.83325 1.83203 3.66659 1.83203 8.24992V13.7499C1.83203 18.3333 3.66536 20.1666 8.2487 20.1666H13.7487C18.332 20.1666 20.1654 18.3333 20.1654 13.7499V11.9166"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M14.7027 2.76832L7.4794 9.99165C7.2044 10.2667 6.9294 10.8075 6.8744 11.2017L6.48023 13.9608C6.33357 14.96 7.0394 15.6567 8.03857 15.5192L10.7977 15.125C11.1827 15.07 11.7236 14.795 12.0077 14.52L19.2311 7.29665C20.4777 6.04999 21.0644 4.60165 19.2311 2.76832C17.3977 0.934987 15.9494 1.52165 14.7027 2.76832Z"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M13.668 3.8042C14.2821 5.99503 15.9963 7.7092 18.1963 8.33253"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>

                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8 dark:hidden" alt="Delete">
                                                <svg width="24" height="24" class="hidden dark:block"
                                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M18.8484 9.13989L18.1984 19.2099C18.0884 20.7799 17.9984 21.9999 15.2084 21.9999H8.78844C5.99844 21.9999 5.90844 20.7799 5.79844 19.2099L5.14844 9.13989"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M10.3281 16.5H13.6581" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.5 12.5H14.5" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>

                                            <button wire:click="printTransaction({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                                title="پرینت PDF">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10 dark:hidden" alt="Print">
                                                <svg width="30" class="hidden dark:block" height="30"
                                                    viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                                        fill="white" />
                                                </svg>

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