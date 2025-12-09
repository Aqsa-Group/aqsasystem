<div>
    <div class="container mx-auto px-0 ">
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



        {{-- کارت‌های ارزها با اسکرول افقی --}}
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 ">
            {{-- کارت مشتری انتخاب شده --}}
            @if($selectedCustomer)
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">

                    {{-- عکس مشتری --}}
                 <div x-data="{ showLargeImage: false, largeImageSrc: '' }">
    @if($selectedCustomer->image)
    <div class="flex justify-center mb-2">
        <img src="{{ Storage::url($selectedCustomer->image) }}" 
             alt="{{ $selectedCustomer->fullname }}"
             class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
             @click="showLargeImage = true; largeImageSrc = '{{ Storage::url($selectedCustomer->image) }}'">
    </div>
    @else
    <div class="flex justify-center mb-2">
        <img src="{{ asset('assets/web.jpg') }}" 
             alt="{{ $selectedCustomer->fullname }}"
             class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
             @click="showLargeImage = true; largeImageSrc = '{{ asset('assets/web.jpg') }}'">
    </div>
    @endif

    {{-- مودال نمایش عکس بزرگ --}}
    <div x-show="showLargeImage" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4"
         @click.away="showLargeImage = false"
         @keydown.escape.window="showLargeImage = false">
        
        <div class="relative max-w-4xl max-h-[90vh]">
            {{-- دکمه بستن --}}
            <button @click="showLargeImage = false"
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl z-10">
                ✕
            </button>
            
            {{-- عکس بزرگ --}}
            <img :src="largeImageSrc" 
                 alt="{{ $selectedCustomer->fullname }}"
                 class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
            
            {{-- نام مشتری زیر عکس --}}
            <div class="mt-4 text-center text-white">
                <p class="text-lg font-semibold">{{ $selectedCustomer->fullname }}</p>
                @if($selectedCustomer->phone)
                <p class="text-sm text-gray-300">{{ $selectedCustomer->phone }}</p>
                @endif
            </div>

                <div class="mt-6 flex justify-center gap-4">
                    <a :href="largeImageSrc" 
                       :download="customerName + '_' + new Date().toISOString().split('T')[0] + '.jpg'"
                       class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
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
            <div class="inline-block align-top ml-4 h-auto ">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] bg-gradient-to-b from-[#2563EB] to-[#5474BB] text-white">

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
                    $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                    $sourceCurrency = $latestProfitRate->currency_name ?? 'دالر';
                    @endphp
                    <h1 class="text-[24px] text-white">خلاصه بیلانس به {{$sourceCurrency }}</h1>
                    <div class="flex flex-col gap-1 mt-1 text-center">
                        @php
                        $totalCashUsd = 0;
                        $totalBankUsd = 0;
                        $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                        $sourceCurrency = $latestProfitRate->source_currency ?? 'دالر';

                        $exchangeRatesCash = [
                        'افغانی' => $latestProfitRate->afn_buy_cash ?? 66.20,
                        'دالر' => $latestProfitRate->usd_buy_cash ?? 1,
                        'تومان' => $latestProfitRate->irr_buy_cash ?? 110000.00,
                        'یورو' => $latestProfitRate->eur_buy_cash ?? 70.00,
                        'کلدار' => $latestProfitRate->pkr_buy_cash ?? 32.00,
                        'درهم' => $latestProfitRate->aed_buy_cash ?? 44.00,
                        'لیره' => $latestProfitRate->try_buy_cash ?? 60.00,
                        'یوان' => $latestProfitRate->cny_buy_cash ?? 43.00,
                        'روپیه' => 7.14,
                        ];

                        $exchangeRatesBank = [
                        'افغانی' => $latestProfitRate->afn_buy_bank ?? 66.20,
                        'دالر' => $latestProfitRate->usd_buy_bank ?? 1,
                        'تومان' => $latestProfitRate->irr_buy_bank ?? 110000.00,
                        'یورو' => $latestProfitRate->eur_buy_bank ?? 70.00,
                        'کلدار' => $latestProfitRate->pkr_buy_bank ?? 32.00,
                        'درهم' => $latestProfitRate->aed_buy_bank ?? 44.00,
                        'لیره' => $latestProfitRate->try_buy_bank ?? 60.00,
                        'یوان' => $latestProfitRate->cny_buy_bank ?? 43.00,
                        'روپیه' => 7.14,
                        ];

                        // محاسبه موجودی نقدی به دالر
                        foreach($customerCashBalances as $currency => $balance) {
                        if(isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                        $totalCashUsd += $balance / $exchangeRatesCash[$currency];
                        }
                        }

                        // محاسبه موجودی بانکی به دالر
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
        {{-- فرم و جدول کنار هم --}}
        <div class="flex flex-col lg:flex-row gap-5 mt-4">

            {{-- فرم تراکنش --}}
            <div class="flex flex-col  bg-[#F5F5F5]  mx-auto w-[420px] lg:w-[474px] p-[10px]  h-auto rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div
                    class="flex  space-y-3 flex-row justify-between p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <p class="flex justify-center items-center text-center">
                        <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" alt="" class="h-6 w-6">
                        {{ $transactionId ? 'فورم ویرایش ترانزکشن' : 'فورم ثبت ترانزکشن' }}
                    </p>

                    <div class="flex gap-4 flex-wrap">

                        {{-- <button wire:click="toggleAccountType" class="rounded-[8px] p-[10px] text-white vazir font-semibold transition-colors duration-500 ease-in-out
    {{ $accountType === 'نقدی' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                            {{ $accountType === 'نقدی' ? 'نقدی' : 'بانکی' }}
                        </button> --}}

                        <button wire:click="toggleTransactionType" class="rounded-[8px] p-[10px] text-white vazir font-semibold transition-colors duration-500 ease-in-out
    {{ $transactionType === 'رسید' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                            {{ $transactionType === 'رسید' ? 'رسید (دریافت صندوق)' : 'برد (برداشت صندوق)' }}
                        </button>

                    </div>
                </div>

                {{-- فرم --}}
                <form wire:submit.prevent="submitTransaction">

                    {{-- شماره حساب و افزودن مشتری --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- در بخش نمبر حساب -->
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبر حساب</label>
                                <div x-data="{
                                                searchValue: '',
                                                selectedId: @entangle('selectedAccount'),
                                                customers: @js($customers),
                                                handleSelect(event) {
                                                    const selected = this.customers.find(
                                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                                    );
                                                    if (selected) {
                                                        this.selectedId = selected.id;
                                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                                        // ✅ فراخوانی متد Livewire برای انتخاب مشتری
                                                        $wire.selectCustomer(selected.id);
                                                        // به روزرسانی جستجو
                                                        $wire.set('search', selected.fullname);
                                                    } else {
                                                        // اگر چیزی اشتباه وارد شد، مقدار پاک شود
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
                                        <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                            @endforeach
                                    </datalist>
                                    @if (empty($selectedAccount))
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                    </div>
                                    @endif
                                </div>
                                @error('selectedAccount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- افزودن مشتری --}}
                        <div class="flex items-end lg:w-[191px]">
                            <button type="button" wire:click.prevent="goToCustomers"
                                class="flex items-center justify-center gap-2 w-full h-[60px] rounded-[12px] bg-transparent border-[#8C8C8C] border text-black font-vazir text-[16px] font-medium transition">
                                افزودن مشتری
                                <img src="{{ asset('assets/sarafi/all_icon/customer-add.svg') }}" alt="افزودن"
                                    class="w-6 h-6">
                            </button>
                        </div>
                    </div>

                    {{-- مقدار و نوع ارز --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500   dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            @if($amountInWords)
                            <p class="text-sm text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- نوع ارز --}}
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
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
                            @error('currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- توسط --}}
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط</label>
                            <div class="relative w-full">
                                <input list="customerList" wire:model="byUser" placeholder="توسط کی...."
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                            @error('byUser')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- درج زون ها و تاریخ --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- درج زون ها --}}
                        <div class="lg:w-[250px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">درج زون ها</label>
                            <div class="relative">
                                <select wire:model="zone" wire:init="setDefaultZone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="{{ Auth::guard('sarafi')->user()->zone }}">
                                        {{ Auth::guard('sarafi')->user()->zone }}
                                    </option>
                                </select>
                            </div>
                            @error('zone')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- تاریخ --}}
                        <div class="lg:w-[290px] relative">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>

                            <div class="relative">
                                <input type="text" id="datePicker" wire:model="date" wire:ignore
                                    placeholder="YYYY/MM/DD"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />

                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" width="20"
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
                            </div>
                        </div>

                    </div>

                    {{-- شرح تراکنش --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح تراکنش..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- آپلود فایل --}}
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <div x-data="{
            files: [],
            isUploading: false,
            uploadedFileName: null,
            uploadedFileUrl: null,
            init() {
                // گوش دادن به رویدادهای آپلود Livewire
                this.$wire.on('upload:started', () => {
                    this.isUploading = true;
                    this.uploadedFileName = null;
                    this.uploadedFileUrl = null;
                });
                
                this.$wire.on('upload:finished', (event) => {
                    this.isUploading = false;
                    if (event.detail.filename) {
                        this.uploadedFileName = event.detail.filename;
                    }
                });
                
                this.$wire.on('upload:error', () => {
                    this.isUploading = false;
                });
            },
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.uploadedFileName = file.name;
                    this.isUploading = true;
                    this.$wire.upload('file', file, () => {
                        this.isUploading = false;
                    });
                }
            },
            handleDrop(event) {
                event.preventDefault();
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.uploadedFileName = file.name;
                    this.isUploading = true;
                    this.$wire.upload('file', file, () => {
                        this.isUploading = false;
                    });
                }
            },
            removeFile() {
                this.uploadedFileName = null;
                this.uploadedFileUrl = null;
                this.$wire.set('file', null);
                // ریست کردن input فایل
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            }
        }" x-on:drop.prevent="handleDrop" x-on:dragover.prevent :class="{
            'border-green-500 bg-green-50 dark:bg-green-900/20': uploadedFileName && !isUploading,
            'border-blue-500 bg-blue-50 dark:bg-blue-900/20': isUploading,
            'border-[#112080] bg-white dark:bg-gray-700': !uploadedFileName && !isUploading
        }" class="w-full h-[150px] p-3 rounded-[12px] border-2 border-dashed focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 relative"
                                x-on:click="$refs.fileInput.click()">

                                <!-- حالت در حال آپلود -->
                                <template x-if="isUploading">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 h-12 mb-2 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                        </div>
                                        <h1 class="font-vazir text-blue-600 dark:text-blue-300 text-[16px]">در حال
                                            آپلود...</h1>
                                        <p class="font-vazir text-gray-500 dark:text-gray-400 text-sm mt-1"
                                            x-text="uploadedFileName"></p>
                                    </div>
                                </template>

                                <!-- حالت آپلود موفق -->
                                <template x-if="!isUploading && uploadedFileName">
                                    <div class="flex flex-col items-center w-full">
                                        <div
                                            class="w-12 h-12 mb-2 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h1 class="font-vazir text-green-600 dark:text-green-300 text-[16px]">آپلود موفق
                                        </h1>
                                        <p class="font-vazir text-gray-600 dark:text-gray-300 text-sm mt-1 truncate max-w-full px-2"
                                            x-text="uploadedFileName" :title="uploadedFileName"></p>
                                        <button type="button" x-on:click.stop="removeFile()"
                                            class="mt-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm flex items-center gap-1 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            حذف فایل
                                        </button>
                                    </div>
                                </template>

                                <!-- حالت اولیه (بدون فایل) -->
                                <template x-if="!isUploading && !uploadedFileName">
                                    <div class="flex flex-col items-center">
                                        <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}" alt="آپلود"
                                            class="w-12 h-12 mb-2">
                                        <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">فایل را
                                            اینجا وارد کنید یا بکشید</h1>
                                        <p class="font-vazir text-gray-500 dark:text-gray-400 text-sm mt-1">فرمت‌های
                                            مجاز: JPG, PNG,WEBP</p>
                                    </div>
                                </template>

                                <input type="file" class="hidden" x-ref="fileInput" accept=".jpg,.jpeg,.png,.pdf,.webp"
                                    x-on:change="handleFileSelect($event)">
                            </div>

                            <!-- نمایش خطاهای اعتبارسنجی -->
                            @error('file')
                            <div class="mt-2 flex items-center gap-2 text-red-500 dark:text-red-400 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror



                            <!-- نمایش فایل ذخیره شده (در حالت ویرایش) -->
                            @if($file && is_string($file))
                            <div
                                class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-blue-700 dark:text-blue-300 text-sm">فایل قبلاً آپلود شده</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ Storage::url($file) }}" target="_blank"
                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        مشاهده
                                    </a>
                                    <button type="button" wire:click="$set('file', null)"
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        حذف
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- دکمه‌های نهایی -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 py-4 justify-center items-center text-center ">
                        <button type="submit"
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            {{ $transactionId ? 'بروزرسانی' : 'ثبت' }}
                        </button>

                        @if(!$transactionId)
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            ثبت و چاپ
                        </button>
                        @endif

                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            {{ $transactionId ? 'لغو ویرایش' : 'انصراف' }}
                        </button>


                    </div>

                </form>
            </div>
            {{-- جدول تراکنش‌ها --}}
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[150px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای جدول: عنوان و جستجو --}}
                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">ترانزکشن های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        {{-- نمایش نام مشتری انتخاب شده --}}
                        @if($selectedCustomerId)
                        @php
                        $selectedCustomer = \App\Models\Sarafi\Customer::find($selectedCustomerId);
                        @endphp
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: {{ $selectedCustomer->fullname ?? '' }}</span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        @endif

                        <div class="relative w-[340px] md:w-[500px]">
                            <!-- Input جستجوی زنده با wire:model.live -->
                            <input type="text" wire:model.live="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            <!-- دکمه پاک کردن جستجو -->
                            @if($search)
                            <button wire:click="clearSearchAndFilter"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif

                            <!-- لیست پیشنهادات -->
                            @if($search && count($filteredCustomers) > 0 && !$selectedCustomerId)
                            <ul
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                @foreach($filteredCustomers as $customer)
                                <li wire:click="selectCustomer({{ $customer->id }})"
                                    class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between items-center">
                                    <span>{{ $customer->fullname }}</span>
                                    <span class="text-gray-500 text-sm">{{ $customer->account_number }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- جدول --}}
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-[#2B65E5] dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">#</th>
                                    <th class="px-4 py-4 font-bold w-48">نام مشتری</th>
                                    <th class="px-4 py-4 font-bold w-32">معامله</th>
                                    <th class="px-4 py-4 font-bold w-48">نوع ترانزکشن</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                    <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $transaction)
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $transaction->customer->fullname ?? '-' }}
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <span
                                            class="px-3 py-1 rounded-full text-[16px] {{ $transaction->type === 'رسید' ? ' text-green-800' : 'text-red-800' }}">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>


                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <span
                                            class="px-3 py-1 rounded-full text-[16px] {{ $transaction->type === 'نقدی' ? ' text-green-800' : 'text-red-800' }}">
                                            {{ $transaction->account_type }}
                                        </span>
                                    </td>

                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        {{ number_format($transaction->amount) }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        {{ collect($currencies)->firstWhere('code', $transaction->currency)['name_fa']
                                        ?? $transaction->currency }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            <p class="text-sm">توسط: {{ $transaction->by }}</p>
                                            <p class="text-sm">زون: {{ $transaction->zone }}</p>
                                            <p class="text-sm">تفصیلات: {{ $transaction->description }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">

                                                {{ explode(' ', $transaction->date)[0] }}

                                            </div>
                                            <div class="text-gray-500 text-sm mt-1">
                                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="edit({{ $transaction->id }})" class="w-12 h-12 flex items-center justify-center  
                                                    rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete({{ $transaction->id }})"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>

                                            <!-- مودال تأیید حذف -->
                                            @if ($confirmDeleteId)
                                            <div
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
                                                <div
                                                    class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
                                                    <button wire:click="$set('confirmDeleteId', null)"
                                                        class="flex right-0 h-4 w-4"><img
                                                            src="{{ asset('assets/sarafi/all_icon/close.svg') }}"
                                                            alt=""></button>
                                                    <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                                                        حذف ترانزکشــــــــــن</h1>
                                                    <hr class="bg-[#E1DED3] mt-8">
                                                    <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                                                        ترانزکشن را حذف کنید؟</p>
                                                    <div class="flex justify-center gap-4">
                                                        <button wire:click="$set('confirmDeleteId', null)"
                                                            class="px-20  text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                                                            {{ __('messages.no') }}
                                                        </button>
                                                        <button wire:click="deleteConfirmed"
                                                            class="px-20 py-3 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl  transition flex items-center gap-2">
                                                            {{ __('messages.yes') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif



                                            <!-- دکمه پرینت -->
                                            <button wire:click="print({{ $transaction->id }})" class="w-12 h-12 flex items-center justify-center  
                                                rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        @if($selectedCustomerId)
                                        هیچ تراکنشی برای این مشتری یافت نشد
                                        @else
                                        هیچ تراکنشی یافت نشد
                                        @endif
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>



    </div>

    {{-- Event Alert --}}
    <script>
        window.addEventListener('report-alert', event => {
                alert(event.detail.message);
            });

                window.addEventListener('redirectToCustomers', () => {
        window.location.href = "{{ route('sarafi.customers.create') }}";
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