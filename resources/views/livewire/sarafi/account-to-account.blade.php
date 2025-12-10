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
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">

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
                            <img src="{{ asset('assets/web.jpg') }}" alt="{{ $withdrawalCustomer->fullname }}"
                                class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform duration-200"
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
            @if($withdrawalCustomerId)
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
            {{-- فرم تراکنش --}}
            <div class="flex flex-col mx-auto bg-[#F5F5F5] w-[420px] lg:w-[534px] p-[10px] h-fit rounded-[12px] space-y-2"
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب مبدا</label>
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
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="withdrawalCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        @endforeach
                                </datalist>
                                @if(empty($withdrawalAccount))

                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                                @endif
                            </div>
                            @error('withdrawalAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- حساب دریافت --}}
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب مقصد</label>
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
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="depositCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        @endforeach
                                </datalist>
                                @if (empty($depositAccount))


                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">از حساب</label>
                            <select wire:model="from_account"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                            @error('from_account')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">به حساب</label>
                            <select wire:model="to_account"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ پول</label>
                            <input type="text" wire:model.live="withdrawal_amount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                            @if ($withdrawalAmountInWords)
                            <div class="mt-2 text-sm text-gray-600">{{ $withdrawalAmountInWords }}</div>
                            @endif
                            @error('withdrawal_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- مبلغ دریافت --}}
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ قابل انتقال
                            </label>
                            <input type="text" wire:model.lazy="transferable_amount" placeholder=""
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-gray-100 focus:ring-2 focus:ring-blue-500" />
                            @if ($receivedAmountInWords)
                            <div class="mt-2 text-sm text-gray-600">{{ $receivedAmountInWords }}</div>
                            @endif
                        </div>

                        {{-- فیلدهای مربوط به کمیشن --}}
                        @if ($transactionType === 'باتفاوت')
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ کمیشن</label>
                            <input type="text" wire:model="commission_amount" placeholder="0" readonly dir="ltr"
                                class="w-full h-[60px] p-3 text-left rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                            @error('commission_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>



                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب کمیشن</label>
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
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
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

                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حالت انتقال</label>
                            <input type="text" value="انتقال با کمیشن" readonly
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-gray-100 focus:ring-2 focus:ring-blue-500" />
                        </div>
                        @endif

                        {{-- ارز --}}
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">ارز</label>
                            <select wire:model="currency"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب ارز</option>
                                @foreach ($currencies as $c)
                                <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                @endforeach
                            </select>
                            @error('from_currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>



                    {{-- توسط و زون‌ها --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (برداشت)</label>
                            <input type="text" wire:model="by_sender" placeholder="نام مسئول برداشت"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent" />
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (دریافت)</label>
                            <input type="text" wire:model="by_receiver" placeholder="نام مسئول دریافت"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent" />
                        </div>
                    </div>

                    {{-- تاریخ و شماره سند --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div class="relative">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" wire:model="transaction_date" placeholder="1404/4/20"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500" />
                            <svg class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none" width="20"
                                height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                <path
                                    d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                    stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />

                                <path
                                    d="M15.6947 13.7H15.7037M15.6947 16.7H15.7037M11.9955 13.7H12.0045M11.9955 16.7H12.0045M8.29431 13.7H8.30329M8.29431 16.7H8.30329"
                                    stroke="#8C8C8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبر سند</label>
                            <input type="text" wire:model="documentNumber" readonly
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-gray-100 focus:ring-2 focus:ring-blue-500 cursor-not-allowed" />
                        </div>
                    </div>

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

                    {{-- شرح بردگی --}}
                    <div class="mt-3">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح بردگی</label>
                        <textarea wire:model="description_sender" rows="3" placeholder="شرح بردگی..."
                            class="w-full p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    {{-- شرح رسیدگی --}}
                    <div class="mt-3">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح رسیدگی</label>
                        <textarea wire:model="description_receiver" rows="3" placeholder="شرح رسیدگی..."
                            class="w-full p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    {{-- دکمه‌ها --}}
                    <div class="flex gap-4 p-4 justify-center items-center flex-wrap">
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-[#2563EB] text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-4 text-white hover:bg-blue-700 transition disabled:opacity-50">
                            @if ($editingConversionId)
                            ویرایش تبدیل ارز
                            @else
                            ثبت تبدیل ارز
                            @endif
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
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[150px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-[340px] md:w-[500px]">
                            <input type="text" wire:model.live="search" wire:keydown.debounce.500ms="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام،...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

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
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
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
                                            <div class="text-xs text-red-600">
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
                                        <span class="text-red-600">باتفاوت</span>
                                        @else
                                        <span class="text-green-600">بدون تفاوت</span>
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

                                            <!-- دکمه پرینت -->
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