<div>
    <div class="container mx-auto ">
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

        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3">
            @if($withdrawalCustomer)
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#20559c] to-[#3065b5] text-white">

                    {{-- عکس مشتری --}}
                    <div x-data="{ 
                            showLargeImage: false, 
                            largeImageSrc: '',
                            customerName: '{{ $withdrawalCustomer->fullname }}',
                            customerPhone: '{{ $withdrawalCustomer->phone ?? '' }}'
                        }">

                        {{-- عکس مشتری --}}
                        @if($withdrawalCustomer->image)
                        <div class="flex justify-center mb-2">
                            <img src="{{ Storage::url($withdrawalCustomer->image) }}"
                                alt="{{ $withdrawalCustomer->fullname }}"
                                class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform duration-200"
                                @click="showLargeImage = true; largeImageSrc = '{{ Storage::url($withdrawalCustomer->image) }}'"
                                onerror="this.onerror=null; this.src='{{ asset('assets/web.jpg') }}'">
                        </div>
                        @else
                        <div class="flex justify-center mb-2">
                            <img src="{{ asset('assets/web.jpg') }}"
                                alt="dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900{{ $withdrawalCustomer->fullname }}"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform duration-200"
                                @click="showLargeImage = true; largeImageSrc = '{{ asset('assets/web.jpg') }}'">
                        </div>
                        @endif

                        {{-- مودال نمایش عکس بزرگ --}}
                        <div x-show="showLargeImage" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4"
                            @click.away="showLargeImage = false" @keydown.escape.window="showLargeImage = false"
                            style="display: none;">

                            <div class="relative max-w-4xl max-h-[90vh] w-full">

                                {{-- دکمه بستن --}}
                                <button @click="showLargeImage = false"
                                    class="absolute -top-12 right-0 text-white hover:text-gray-300 text-3xl z-10 transition-colors duration-200">
                                    ✕
                                </button>

                                {{-- عکس بزرگ --}}
                                <div class="flex justify-center">
                                    <img :src="largeImageSrc" :alt="customerName"
                                        class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-2xl">
                                </div>

                                {{-- اطلاعات مشتری زیر عکس --}}
                                <div class="mt-6 text-center text-white">
                                    <p class="text-2xl font-bold mb-2" x-text="customerName"></p>
                                    <template x-if="customerPhone">
                                        <p class="text-lg text-gray-300" x-text="customerPhone"></p>
                                    </template>

                                    {{-- دکمه دانلود --}}
                                    <div class="mt-6 flex justify-center gap-4">
                                        <a :href="largeImageSrc"
                                            :download="customerName + '_' + new Date().toISOString().split('T')[0] + '.jpg'"
                                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            دانلود عکس
                                        </a>

                                        <button @click="showLargeImage = false"
                                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                            بستن
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    {{-- نام مشتری --}}
                    <h1 class="text-[20px] text-white text-center font-bold truncate"
                        title="{{ $withdrawalCustomer->fullname }}">
                        {{ $withdrawalCustomer->fullname }}
                    </h1>

                    {{-- شماره تماس --}}
                    @if($withdrawalCustomer->phone)
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left">{{ $withdrawalCustomer->phone }}</span>
                    </div>
                    @endif

                    {{-- شماره حساب --}}
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left">{{ $withdrawalCustomer->account_number
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
            @if($withdrawalCustomerId)
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
            {{-- فرم تراکنش --}}
            <div class="flex flex-col mx-auto dark:bg-black dark:border-white dark:border bg-[#F5F5F5] w-[420px] lg:w-[534px] p-[10px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div
                    class="flex flex-row justify-between p-[20px] border border-[#8C8C8C] rounded-[12px] flex-wrap items-center">
                    <p class="flex justify-between items-center text-center gap-1">
                        <img src="{{ asset('assets/sarafi/all_icon/pencil.svg') }}" alt="" class="h-6 w-6">
                        <span class="vazir font-semibold">فورم انتفال بین حسابات</span>
                    </p>
                    <button class="bg-[#DD2424] rounded-[8px] p-[10px] text-white vazir font-semibold">توقف
                        پیامک</button>
                    <button wire:click="toggleTransactionType" class="rounded-[8px] p-[10px] text-white vazir font-semibold transition-colors duration-500 ease-in-out
                         {{ $transactionType === 'باتفاوت' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                        {{ $transactionType === 'باتفاوت' ? 'باتفاوت کمیشن' : 'بدون تفاوت کمیشن' }}
                    </button>

                </div>

                {{-- فرم --}}
                <form wire:submit.prevent="submitConversion" class="space-y-6">

                    {{-- حساب برداشت و دریافت --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-2">
                        {{-- حساب برداشت --}}
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">حساب
                                مبدا</label>
                            <div x-data="{
                                            searchValue: '',
                                            selectedId: @entangle('withdrawalAccount'),
                                            customers: @js($customers),
                                            init() {
                                                this.updateDisplay();
                                                $wire.on('edit-mode-activated', (data) => {
                                                    this.selectedId = data.withdrawalAccount;
                                                    this.searchValue = data.withdrawalCustomer;
                                                    setTimeout(() => this.updateDisplay(), 100);
                                                });
                                                $wire.on('accountsSwapped', () => setTimeout(() => this.updateDisplay(), 100));
                                            },
                                            handleSelect(event) {
                                                const selected = this.customers.find(
                                                    c => event.target.value === `${c.account_number} - ${c.fullname}`
                                                );
                                                if (selected) {
                                                    this.selectedId = selected.id;
                                                    this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                                    $wire.selectWithdrawalAccount(selected.id);
                                                } else {
                                                    this.selectedId = null;
                                                    this.searchValue = '';
                                                    $wire.set('withdrawalAccount', null);
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
                                <input list="withdrawalCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب بردگی..."
                                    class="w-full dark:placeholder:text-white dark:bg-black dark:border-white  h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="withdrawalCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        @endforeach
                                </datalist>
                                @if(empty($withdrawalAccount))

                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                        class="dark:hidden">
                                    <svg width="24" class="hidden dark:block" height="24" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </div>
                                @endif
                            </div>
                            @error('withdrawalAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- حساب دریافت --}}
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">حساب
                                مقصد</label>
                            <div x-data="{
                                            searchValue: '',
                                            selectedId: @entangle('depositAccount'),
                                            customers: @js($customers),
                                            init() {
                                                this.updateDisplay();
                                                $wire.on('edit-mode-activated', (data) => {
                                                    this.selectedId = data.depositAccount;
                                                    this.searchValue = data.depositCustomer;
                                                    setTimeout(() => this.updateDisplay(), 100);
                                                });
                                                $wire.on('accountsSwapped', () => setTimeout(() => this.updateDisplay(), 100));
                                            },
                                            handleSelect(event) {
                                                const selected = this.customers.find(
                                                    c => event.target.value === `${c.account_number} - ${c.fullname}`
                                                );
                                                if (selected) {
                                                    this.selectedId = selected.id;
                                                    this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                                    $wire.selectDepositAccount(selected.id);
                                                } else {
                                                    this.selectedId = null;
                                                    this.searchValue = '';
                                                    $wire.set('depositAccount', null);
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
                                <input list="depositCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب رسیدگی..."
                                    class="w-full dark:text-white dark:bg-black dark:border-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="depositCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        @endforeach
                                </datalist>
                                @if (empty($depositAccount))


                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                        class="dark:hidden">
                                    <svg width="24" class="hidden dark:block" height="24" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </div>
                                @endif
                            </div>
                            @error('depositAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">از
                                حساب</label>
                            <select wire:model="from_account"
                                class="w-full dark:border-white dark:bg-black dark:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                            @error('from_account')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">به
                                حساب</label>
                            <select wire:model="to_account"
                                class="w-full dark:border-white dark:text-white dark:bg-black  h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                            @error('to_account')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    {{-- بخش مبالغ --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        {{-- مبلغ اصلی --}}
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مبلغ
                                پول</label>
                            <input type="text" wire:model.live="withdrawal_amount" placeholder="0"
                                class="w-full dark:text-white dark:bg-black dark:border-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                            @if ($withdrawalAmountInWords)
                            <div class="mt-2 text-sm dark:text-white text-gray-600">{{ $withdrawalAmountInWords }}</div>
                            @endif
                            @error('withdrawal_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- مبلغ دریافت --}}
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مبلغ قابل
                                انتقال
                            </label>
                            <input type="text" wire:model="transferable_amount" placeholder=""
                                class="w-full dark:text-white dark:bg-black dark:border-white  h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-gray-100 focus:ring-2 focus:ring-blue-500" />
                            @if ($receivedAmountInWords)
                            <div class="mt-2 text-sm dark:text-white text-gray-600">{{ $receivedAmountInWords }}</div>
                            @endif
                        </div>

                        {{-- فیلدهای مربوط به کمیشن --}}
                        @if ($transactionType === 'باتفاوت')
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white  text-black mb-1 vazir">مبلغ
                                کمیشن</label>
                            <input type="text" wire:model="commission_amount" placeholder="0" readonly dir="ltr"
                                class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white  h-[60px] p-3 text-left rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                            @error('commission_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>



                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">حساب
                                کمیشن</label>
                            <div x-data="{
                                        searchValue: '',
                                        selectedId: @entangle('commissionAccount'),
                                        customers: @js($customers),
                                        init() {
                                            this.updateDisplay();
                                            $wire.on('edit-mode-activated', (data) => {
                                                this.selectedId = data.commissionAccount;
                                                this.searchValue = data.commissionCustomer;
                                                setTimeout(() => this.updateDisplay(), 100);
                                            });
                                        },
                                        handleSelect(event) {
                                            const selected = this.customers.find(
                                                c => event.target.value === `${c.account_number} - ${c.fullname}`
                                            );
                                            if (selected) {
                                                this.selectedId = selected.id;
                                                this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                                $wire.set('commissionAccount', selected.id);
                                            } else {
                                                this.selectedId = null;
                                                this.searchValue = '';
                                                $wire.set('commissionAccount', null);
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
                                <input list="commissionCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="حساب دریافت کمیشن"
                                    class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="commissionCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        @endforeach
                                </datalist>
                                @if(empty($commissionAccount))
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                                @endif
                            </div>
                            @error('commissionAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        
                        @endif

                        {{-- ارز --}}
                        <div>
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">ارز</label>
                            <select wire:model="currency"
                                class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب ارز</option>
                                @foreach ($currencies as $c)
                                <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                @endforeach
                            </select>
                            @error('from_currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>


                              <div class="relative" x-data="persianDatePicker()" x-init="init()">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>

                            <!-- Input field -->
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 cursor-pointer"
                                readonly />

                            <!-- Custom Date Picker Modal -->
                            <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                                aria-modal="true" style="display: none;" :style="isOpen ? 'display: block;' : ''">

                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <!-- Background overlay -->
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <!-- Modal panel -->
                                    <div
                                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center space-x-2">
                                                    <button @click="prevYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="prevMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <button @click="toggleMonthSelector()" type="button"
                                                        class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                        <span x-text="monthsAfghan[currentMonth]"></span>
                                                    </button>
                                                    <button @click="toggleYearSelector()" type="button"
                                                        class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                        <span x-text="currentYear"></span>
                                                    </button>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <button @click="nextMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button @click="nextYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button @click="closePicker()" type="button"
                                                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Month Selector -->
                                            <div x-show="showMonthSelector" x-transition>
                                                <div class="grid grid-cols-3 gap-2 mb-4">
                                                    <template x-for="(month, index) in monthsAfghan" :key="index">
                                                        <button @click="selectMonth(index)" :class="{
                                        'bg-blue-500 text-white': currentMonth === index,
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !== index
                                    }" class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="month"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Year Selector -->
                                            <div x-show="showYearSelector" x-transition>
                                                <div class="flex items-center justify-between mb-4">
                                                    <button @click="prevYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                    </button>
                                                    <span class="text-lg font-bold text-gray-800 dark:text-white">
                                                        <span x-text="yearRange.start"></span> - <span
                                                            x-text="yearRange.end"></span>
                                                    </span>
                                                    <button @click="nextYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-4 gap-2 mb-4">
                                                    <template x-for="year in yearRange.years" :key="year">
                                                        <button @click="selectYear(year)" :class="{
                                        'bg-blue-500 text-white': currentYear === year,
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !== year
                                    }" class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="year"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Calendar View -->
                                            <div x-show="!showMonthSelector && !showYearSelector" x-transition>
                                                <!-- Week Days -->
                                                <div class="grid grid-cols-7 gap-1 mb-2">
                                                    <template x-for="day in weekDaysAfghan" :key="day">
                                                        <div
                                                            class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 py-1">
                                                            <span x-text="day"></span>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Days Grid -->
                                                <div class="grid grid-cols-7 gap-1">
                                                    <template x-for="day in calendarDays" :key="day.key">
                                                        <button @click="selectDate(day.day)" :class="{
                                        'bg-blue-500 text-white hover:bg-blue-600': day.isSelected,
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day.isToday && !day.isSelected,
                                        'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700': !day.isToday && !day.isSelected && !day.isOtherMonth,
                                        'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800': day.isOtherMonth,
                                        'cursor-not-allowed opacity-50': day.isDisabled
                                    }" class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-medium transition-colors"
                                                            :disabled="day.isDisabled" type="button">
                                                            <span x-text="day.day"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Footer -->
                                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-between items-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                                        <span
                                                            x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"></span>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button @click="setToday()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                                            امروز
                                                        </button>
                                                        <button @click="clearDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                                            پاک کردن
                                                        </button>
                                                        <button @click="applyDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            تأیید
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('date')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <script>
                            function persianDatePicker() {
                                            return {
                                                isOpen: false,
                                                showMonthSelector: false,
                                                showYearSelector: false,
                                                displayDate: '',
                                                currentYear: 1403,
                                                currentMonth: 0,
                                                selectedDate: null,
                                                yearRange: {
                                                    start: 1400,
                                                    end: 1410,
                                                    years: []
                                                },
                                                
                                                // ماه‌های افغانی
                                                monthsAfghan: [
                                                    'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
                                                    'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
                                                ],
                                                
                                                // روزهای هفته (شنبه شروع می‌شود)
                                                weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
                                                
                                                // روزهای کامل هفته
                                                weekDaysFull: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'],
                                                
                                                // تعداد روزهای ماه‌های شمسی در سال عادی
                                                daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],
                                                
                                                init() {
                                                    this.updateYearRange();
                                                    
                                                    // Initialize with current date
                                                    const today = this.getTodayPersian();
                                                    this.currentYear = today.year;
                                                    this.currentMonth = today.month - 1;
                                                    
                                                    // اگر تاریخ از قبل انتخاب شده بود
                                                    if (@this.get('date')) {
                                                        const dateParts = @this.get('date').split('/');
                                                        if (dateParts.length === 3) {
                                                            const year = parseInt(dateParts[0]);
                                                            const month = parseInt(dateParts[1]);
                                                            const day = parseInt(dateParts[2]);
                                                            
                                                            if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
                                                                this.selectedDate = { year, month, day };
                                                                this.displayDate = @this.get('date');
                                                                this.currentYear = year;
                                                                this.currentMonth = month - 1;
                                                            }
                                                        }
                                                    }
                                                },
                                                
                                                // به‌روزرسانی محدوده سال‌ها
                                                updateYearRange() {
                                                    this.yearRange.years = [];
                                                    for (let year = this.yearRange.start; year <= this.yearRange.end; year++) {
                                                        this.yearRange.years.push(year);
                                                    }
                                                },
                                                
                                                // بررسی سال کبیسه
                                                isLeapYear(year) {
                                                    // سال کبیسه شمسی: سال‌هایی که باقیمانده تقسیم به 33 برابر با 1, 5, 9, 13, 17, 22, 26, 30 باشد
                                                    const remainders = [1, 5, 9, 13, 17, 22, 26, 30];
                                                    return remainders.includes(year % 33);
                                                },
                                                
                                                // تعداد روزهای ماه
                                                getDaysInMonth(year, month) {
                                                    const days = [...this.daysInMonthNormal];
                                                    // اگر سال کبیسه باشد، اسفند 30 روز است
                                                    if (month === 11 && this.isLeapYear(year)) {
                                                        return 30;
                                                    }
                                                    return days[month];
                                                },
                                                
                                                // محاسبه روز هفته برای روز اول ماه
                                                getFirstDayOfWeek(year, month) {
                                                    // الگوریتم محاسبه روز هفته برای تقویم هجری شمسی
                                                    // روز اول فروردین سال 1403 = چهارشنبه (index = 4)
                                                    const baseYear = 1403;
                                                    const baseDay = 4; // چهارشنبه (شنبه=0)
                                                    
                                                    // محاسبه تعداد روزهای گذشته از سال پایه
                                                    let days = 0;
                                                    
                                                    // محاسبه روزهای سال‌های کامل
                                                    for (let y = baseYear; y < year; y++) {
                                                        days += this.isLeapYear(y) ? 366 : 365;
                                                    }
                                                    
                                                    // محاسبه روزهای ماه‌های گذشته از سال جاری
                                                    for (let m = 0; m < month; m++) {
                                                        days += this.getDaysInMonth(year, m);
                                                    }
                                                    
                                                    // محاسبه روز هفته (0 = شنبه)
                                                    return (baseDay + days) % 7;
                                                },
                                                
                                                // دریافت تاریخ امروز به شمسی
                                                getTodayPersian() {
                                                    const today = new Date();
                                                    
                                                    // الگوریتم تبدیل میلادی به شمسی (ساده شده)
                                                    const gregorianYear = today.getFullYear();
                                                    const gregorianMonth = today.getMonth() + 1;
                                                    const gregorianDay = today.getDate();
                                                    
                                                    // تبدیل میلادی به شمسی
                                                    return this.gregorianToPersian(gregorianYear, gregorianMonth, gregorianDay);
                                                },
                                                
                                                // تبدیل میلادی به شمسی
                                                gregorianToPersian(gy, gm, gd) {
                                                    // الگوریتم تبدیل میلادی به شمسی
                                                    const gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                                                    
                                                    // بررسی کبیسه میلادی
                                                    const isGregorianLeap = (gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0);
                                                    
                                                    if (isGregorianLeap) {
                                                        gDaysInMonth[1] = 29;
                                                    }
                                                    
                                                    // محاسبه روز از ابتدای سال میلادی
                                                    let dayOfYear = gd;
                                                    for (let i = 0; i < gm - 1; i++) {
                                                        dayOfYear += gDaysInMonth[i];
                                                    }
                                                    
                                                    // نوروز سال جاری
                                                    const marchDay = 79; // 20 مارس
                                                    
                                                    let persianYear, persianMonth, persianDay;
                                                    
                                                    if (dayOfYear > marchDay) {
                                                        persianYear = gy - 621;
                                                        let remainingDays = dayOfYear - marchDay;
                                                        
                                                        const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                                                        if (this.isLeapYear(persianYear)) {
                                                            pDaysInMonth[11] = 30;
                                                        }
                                                        
                                                        for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                                                            if (remainingDays <= pDaysInMonth[persianMonth]) {
                                                                persianDay = remainingDays;
                                                                break;
                                                            }
                                                            remainingDays -= pDaysInMonth[persianMonth];
                                                        }
                                                        persianMonth++; // تبدیل به 1-based
                                                    } else {
                                                        persianYear = gy - 622;
                                                        let remainingDays = dayOfYear + 286;
                                                        
                                                        const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                                                        if (this.isLeapYear(persianYear)) {
                                                            pDaysInMonth[11] = 30;
                                                        }
                                                        
                                                        for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                                                            if (remainingDays <= pDaysInMonth[persianMonth]) {
                                                                persianDay = remainingDays;
                                                                break;
                                                            }
                                                            remainingDays -= pDaysInMonth[persianMonth];
                                                        }
                                                        persianMonth++; // تبدیل به 1-based
                                                    }
                                                    
                                                    return {
                                                        year: persianYear,
                                                        month: persianMonth,
                                                        day: persianDay
                                                    };
                                                },
                                                
                                                // محاسبه روزهای تقویم برای نمایش
                                                get calendarDays() {
                                                    const days = [];
                                                    const daysInMonth = this.getDaysInMonth(this.currentYear, this.currentMonth);
                                                    const firstDayOfWeek = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);
                                                    const today = this.getTodayPersian();
                                                    
                                                    // روزهای ماه قبل
                                                    const prevMonthDays = this.currentMonth === 0 ? 
                                                        this.getDaysInMonth(this.currentYear - 1, 11) : 
                                                        this.getDaysInMonth(this.currentYear, this.currentMonth - 1);
                                                    
                                                    for (let i = 0; i < firstDayOfWeek; i++) {
                                                        const day = prevMonthDays - firstDayOfWeek + i + 1;
                                                        days.push({
                                                            key: `prev-${day}`,
                                                            day: day,
                                                            isSelected: false,
                                                            isToday: false,
                                                            isOtherMonth: true,
                                                            isDisabled: true
                                                        });
                                                    }
                                                    
                                                    // روزهای ماه جاری
                                                    for (let day = 1; day <= daysInMonth; day++) {
                                                        const isSelected = this.selectedDate && 
                                                            this.selectedDate.year === this.currentYear && 
                                                            this.selectedDate.month === this.currentMonth + 1 && 
                                                            this.selectedDate.day === day;
                                                        
                                                        const isToday = today.year === this.currentYear && 
                                                            today.month === this.currentMonth + 1 && 
                                                            today.day === day;
                                                        
                                                        days.push({
                                                            key: `current-${day}`,
                                                            day: day,
                                                            isSelected: isSelected,
                                                            isToday: isToday,
                                                            isOtherMonth: false,
                                                            isDisabled: false
                                                        });
                                                    }
                                                    
                                                    // روزهای ماه بعد
                                                    const remainingCells = 42 - days.length; // 6 ردیف × 7 ستون
                                                    for (let day = 1; day <= remainingCells; day++) {
                                                        days.push({
                                                            key: `next-${day}`,
                                                            day: day,
                                                            isSelected: false,
                                                            isToday: false,
                                                            isOtherMonth: true,
                                                            isDisabled: true
                                                        });
                                                    }
                                                    
                                                    return days;
                                                },
                                                
                                                togglePicker() {
                                                    this.isOpen = !this.isOpen;
                                                    this.showMonthSelector = false;
                                                    this.showYearSelector = false;
                                                },
                                                
                                                closePicker() {
                                                    this.isOpen = false;
                                                    this.showMonthSelector = false;
                                                    this.showYearSelector = false;
                                                },
                                                
                                                toggleMonthSelector() {
                                                    this.showMonthSelector = !this.showMonthSelector;
                                                    this.showYearSelector = false;
                                                },
                                                
                                                toggleYearSelector() {
                                                    this.showYearSelector = !this.showYearSelector;
                                                    this.showMonthSelector = false;
                                                },
                                                
                                                prevYear() {
                                                    this.currentYear--;
                                                    this.updateYearRange();
                                                },
                                                
                                                nextYear() {
                                                    this.currentYear++;
                                                    this.updateYearRange();
                                                },
                                                
                                                prevMonth() {
                                                    if (this.currentMonth === 0) {
                                                        this.currentMonth = 11;
                                                        this.currentYear--;
                                                    } else {
                                                        this.currentMonth--;
                                                    }
                                                },
                                                
                                                nextMonth() {
                                                    if (this.currentMonth === 11) {
                                                        this.currentMonth = 0;
                                                        this.currentYear++;
                                                    } else {
                                                        this.currentMonth++;
                                                    }
                                                },
                                                
                                                prevYearRange() {
                                                    this.yearRange.start -= 12;
                                                    this.yearRange.end -= 12;
                                                    this.updateYearRange();
                                                },
                                                
                                                nextYearRange() {
                                                    this.yearRange.start += 12;
                                                    this.yearRange.end += 12;
                                                    this.updateYearRange();
                                                },
                                                
                                                selectMonth(monthIndex) {
                                                    this.currentMonth = monthIndex;
                                                    this.showMonthSelector = false;
                                                },
                                                
                                                selectYear(year) {
                                                    this.currentYear = year;
                                                    this.showYearSelector = false;
                                                },
                                                
                                                selectDate(day) {
                                                    this.selectedDate = {
                                                        year: this.currentYear,
                                                        month: this.currentMonth + 1,
                                                        day: day
                                                    };
                                                    
                                                    this.displayDate = `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                                                },
                                                
                                                formatDate(date) {
                                                    if (!date) return '';
                                                    return `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`;
                                                },
                                                
                                                setToday() {
                                                    const today = this.getTodayPersian();
                                                    this.currentYear = today.year;
                                                    this.currentMonth = today.month - 1;
                                                    this.selectedDate = today;
                                                    this.displayDate = this.formatDate(today);
                                                },
                                                
                                                clearDate() {
                                                    this.selectedDate = null;
                                                    this.displayDate = '';
                                                    @this.set('date', '');
                                                    this.closePicker();
                                                },
                                                
                                                applyDate() {
                                                    if (this.selectedDate) {
                                                        const formattedDate = this.formatDate(this.selectedDate);
                                                        this.displayDate = formattedDate;
                                                        @this.set('date', formattedDate);
                                                        this.closePicker();
                                                    }
                                                }
                                            }
                                        }
                        </script>

                        <style>
                            /* Hide scrollbar for number inputs */
                            input[type="number"]::-webkit-inner-spin-button,
                            input[type="number"]::-webkit-outer-spin-button {
                                -webkit-appearance: none;
                                margin: 0;
                            }

                            /* Persian datepicker custom styles */
                            .persian-datepicker {
                                font-family: 'Vazir', sans-serif;
                                direction: rtl;
                            }

                            /* Animation for modal */
                            [x-cloak] {
                                display: none !important;
                            }

                            /* Smooth transitions */
                            .transition-all {
                                transition-property: all;
                                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                                transition-duration: 150ms;
                            }

                            /* Custom scrollbar */
                            ::-webkit-scrollbar {
                                width: 8px;
                            }

                            ::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 4px;
                            }

                            ::-webkit-scrollbar-thumb {
                                background: #888;
                                border-radius: 4px;
                            }

                            ::-webkit-scrollbar-thumb:hover {
                                background: #555;
                            }
                        </style>


                    </div>




                  

                    {{-- زون برداشت و دریافت --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">زون
                                برداشت</label>
                            <select wire:model="zone_sender"
                                class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                                class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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

                    {{-- شرح بردگی --}}
                    <div class="mt-3">
                        <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شرح
                            بردگی</label>
                        <textarea wire:model="description_sender" rows="3" placeholder="شرح بردگی..."
                            class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    {{-- شرح رسیدگی --}}
                    <div class="mt-3">
                        <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شرح
                            رسیدگی</label>
                        <textarea wire:model="description_receiver" rows="3" placeholder="شرح رسیدگی..."
                            class="w-full dark:bg-black dark:text-white dark:border-white dark:placeholder-white p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    {{-- دکمه‌ها --}}
                    <div class="flex gap-4 p-4 justify-center items-center flex-wrap">
                        <button type="submit" wire:loading.attr="disabled" wire:target='submitConversion'
                            class="bg-[#2563EB] text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-4 text-white hover:bg-blue-700 transition disabled:opacity-50">
                            <span wire:loading.remove wire:target='submitConversion'>
                                @if ($editingConversionId)
                                ویرایش تبدیل ارز
                                @else
                                ثبت تبدیل ارز
                                @endif
                            </span>


                            <span wire:loading wire:target="submitConversion"
                                class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                @if ($editingConversionId)
                                در حال ویرایش تبدیل ارز
                                @else
                                در حال ثبت تبدیل ارز
                                @endif
                            </span>



                        </button>
                        <button type="button" wire:click="resetForm" wire:loading.attr="disabled"
                            class="bg-[#DD2424] text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-4 text-white hover:bg-red-700 transition">
                            @if ($editingConversionId)
                            انصراف از ویرایش
                            @else
                            انصراف
                            @endif
                        </button>
                    </div>
                </form>

            </div>

            {{-- جدول تراکنش‌های تبدیل ارز --}}
            <div class="flex-1 flex flex-col dark:bg-black dark:border dark:border-white dark:text-white bg-[#F5F5F5] p-3 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[150px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-[340px] md:w-[500px]">
                            <input type="text" wire:model.live="search" wire:keydown.debounce.500ms="search"
                                class="border dark:bg-black dark:text-white dark:placeholder:text-white dark:border-white border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام،...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 dark:hidden">

                            <svg width="24"
                                class="absolute hidden dark:block left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6"
                                height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                            @if ($search)
                            <button wire:click="$set('search', '')"
                                class="absolute left-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- جدول --}}
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table class="w-[890px] text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead
                                class="bg-[#2B65E5] text-white text-[14px] md:text-[18px] vazir h-[50px] md:h-[60px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-2 py-3 font-bold w-12">#</th>
                                    <th class="px-2 py-3 font-bold w-32">از حساب</th>
                                    <th class="px-2 py-3 font-bold w-32">به حساب</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ برداشت</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ دریافت</th>
                                    <th class="px-2 py-3 font-bold w-24">نوع انتقال</th>
                                    <th class="px-2 py-3 font-bold w-36 text-center">توضیحات</th>
                                    <th class="px-2 py-3 font-bold w-28">تاریخ</th>
                                    <th class="px-2 py-3 font-bold w-32 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($SendToAccount as $key => $conversion)
                                <tr class="text-black  dark:text-white border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-1 py-3 vazir text-[16px] md:text-[18px] font-medium w-28">
                                        <div class="truncate" title="{{ $conversion->from_customer_name ?? '-' }}">
                                            {{ $conversion->from_customer_name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[16px] md:text-[18px] font-medium w-14">
                                        <div class="truncate" title="{{ $conversion->to_customer_name ?? '-' }}">
                                            {{ $conversion->to_customer_name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-1  py-3 vazir text-[13px] md:text-[16px] font-medium w-52">
                                        <div class="text-left">
                                            <span class="">{{ number_format($conversion->withdrawal_amount) }}
                                                {{ $this->getCurrencyName($conversion->from_currency) }}</span>
                                            @if($conversion->type === 'باتفاوت' && $conversion->tax_amount > 0)
                                            <div class="text-xs dark:text-white text-red-600">
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[16px] md:text-[16px]  w-44">
                                        <div class="text-left">
                                            <span class="">{{ number_format($conversion->received_amount) }}
                                                {{ $this->getCurrencyName($conversion->from_currency) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px]  w-44">
                                        @if($conversion->type === 'باتفاوت')
                                        <span class="text-red-600 dark:text-white">باتفاوت</span>
                                        @else
                                        <span class="text-green-600 dark:text-white">بدون تفاوت</span>
                                        @endif
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px] font-medium w-36">
                                        <div class="text-right truncate" title="{{ $conversion->description_sender }}">
                                            {{ Str::limit($conversion->description_sender, 35) }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                            {{ explode(' ', $conversion->transaction_date)[0] }}
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

                                            <!-- دکمه پرینت -->
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
                                    <td colspan="9" class="px-4 py-4 text-center text-gray-500 vazir text-[14px]">
                                        هیچ تراکنش تبدیلی یافت نشد.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- صفحه‌بندی --}}
                @if ($SendToAccount->hasPages())
                <div class="mt-4 px-4">
                    {{ $SendToAccount->links() }}
                </div>
                @endif
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

                    <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف تراکنش تبدیل ارز
                    </h1>
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
        </div>

        <script>
            window.addEventListener('report-alert', event => {
                alert(event.detail.message);
            });
        </script>

        {{-- Scrollbar Style --}}
        <style>
            .scroll-container {
                scrollbar-width: thin;
                scrollbar-color: #e5e7eb #f9fafb;
            }

            .scroll-container::-webkit-scrollbar {
                height: 6px;
            }

            .scroll-container::-webkit-scrollbar-track {
                background: #f9fafb;
                border-radius: 10px;
            }

            .scroll-container::-webkit-scrollbar-thumb {
                background: #e5e7eb;
                border-radius: 10px;
            }

            .scroll-container::-webkit-scrollbar-thumb:hover {
                background: #cbd5e1;
            }

            #selectCustomer {
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                background: transparent;
                padding-left: 1rem;
            }

            input[list]::-webkit-calendar-picker-indicator {
                display: none !important;
                -webkit-appearance: none;
            }

            /* در Firefox */
            input[list]::-moz-list-button {
                display: none !important;
            }

            /* در Edge جدید */
            input[list]::-ms-clear,
            input[list]::-ms-expand {
                display: none !important;
            }
        </style>
    </div>
</div>