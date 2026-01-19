<div>
    <div class="container mx-auto">
        <!-- پیام‌های سیستم -->
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2563EB] azir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('message') }}
                </h2>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-700 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('error') }}
                </h2>
            </div>
        </div>
        @endif

        <!-- هدر صفحه -->
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">ثبت ارسال پول به صرافی دیگر</h1>
            <h1 class="text-[#8C8C8C]">صفحه ثبت و ویرایش ارسال پول به مشتریان در صرافی دیگر</h1>
        </div>

        <!-- کارت‌های موجودی -->
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3">
            <!-- کارت مشتری انتخاب شده -->
            @if($selectedCustomer)
            <div class="inline-block align-top ml-4 h-auto">
                <div class="flex flex-col h-[212px] w-[244px] pr-5 pl-5 pt-2 rounded-[12px]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900           bg-[#387EA2]/40
            backdrop-blur-lg
            border border-white/30

            shadow-[0_4px_4px_rgba(24,77,108,0.25)] text-black">
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
                            <img src="{{ asset('assets/web.jpg') }}" alt="{{ $selectedCustomer->fullname }}"
                                class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '{{ asset('assets/web.jpg') }}'">
                        </div>
                        @endif
                    </div>

                    <!-- نام مشتری -->
                    <h1 class="text-[20px] text-black text-center font-bold truncate"
                        title="{{ $selectedCustomer->fullname }}">
                        {{ $selectedCustomer->fullname }}
                    </h1>

                    <!-- شماره تماس -->
                    @if($selectedCustomer->phone)
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-black text-[14px] dir-ltr text-left">{{ $selectedCustomer->phone }}</span>
                    </div>
                    @endif

                    <!-- شماره حساب -->
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-black text-[14px] dir-ltr text-left">{{ $selectedCustomer->account_number
                            }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- کارت‌های موجودی ارزها -->
            @foreach ($currencies as $currencyItem)
            @php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            @endphp
            <div class="inline-block align-top ml-4 h-auto">
                <div class="
    flex flex-col
  h-[212px] w-[244px]
    pr-5 pl-5 pt-3
    rounded-[12px]

    bg-[#387EA2]/40
    backdrop-blur-lg
    border border-white/30

    shadow-[0_4px_4px_rgba(24,77,108,0.25)]

    text-black
  ">

                    <h1 class="text-[24px] text-left vazir text-[#387EA2]">{{ $currencyName }}</h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <div class="flex justify-between items-center text-[14px]">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M10.8332 4.1665C13.1902 4.1665 14.3687 4.1665 15.1009 4.89874C15.8332 5.63097 15.8332 6.80948 15.8332 9.1665C15.8332 11.5235 15.8332 12.702 15.1009 13.4343C14.3687 14.1665 13.1902 14.1665 10.8332 14.1665H6.6665C4.30948 14.1665 3.13097 14.1665 2.39874 13.4343C1.6665 12.702 1.6665 11.5235 1.6665 9.1665C1.6665 6.80948 1.6665 5.63097 2.39874 4.89874C3.13097 4.1665 4.30948 4.1665 6.6665 4.1665H7.49984"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M13.3337 16.6665H9.16704C6.81002 16.6665 5.63151 16.6665 4.89927 15.9343C4.49103 15.5261 4.31039 14.9791 4.23047 14.1665M17.6015 15.9343C18.3337 15.2021 18.3337 14.0236 18.3337 11.6665C18.3337 9.30953 18.3337 8.13101 17.6015 7.39878C17.1932 6.99054 16.6463 6.80991 15.8337 6.72998"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M10.8332 9.16683C10.8332 10.3174 9.90043 11.2502 8.74984 11.2502C7.59924 11.2502 6.6665 10.3174 6.6665 9.16683C6.6665 8.01624 7.59924 7.0835 8.74984 7.0835C9.90043 7.0835 10.8332 8.01624 10.8332 9.16683Z"
                                            stroke="#1C274C" stroke-width="1.5" />
                                        <path d="M13.3335 10.8335L13.3335 7.50016" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M4.1665 10.8335L4.1665 7.50016" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span>نقدی</span>
                            </div>
                            <span class="font-medium text-left" dir="ltr">{{ number_format($cashBalance) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M2.64281 18.2249C3.61913 19.25 5.19047 19.25 8.33317 19.25H11.6665C14.8092 19.25 16.3805 19.25 17.3569 18.2249C18.3332 17.1997 18.3332 15.5498 18.3332 12.25C18.3332 11.2265 18.3332 10.3617 18.304 9.625M17.3569 6.27513C16.3805 5.25 14.8092 5.25 11.6665 5.25H8.33317C5.19047 5.25 3.61913 5.25 2.64281 6.27513C1.6665 7.30025 1.6665 8.95017 1.6665 12.25C1.6665 13.2735 1.6665 14.1383 1.69564 14.875"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M9.99984 1.75C11.5712 1.75 12.3569 1.75 12.845 2.26256C13.3332 2.77513 13.3332 3.60008 13.3332 5.25M7.15466 2.26256C6.6665 2.77513 6.6665 3.60008 6.6665 5.25"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M10.0002 15.1667C10.9206 15.1667 11.6668 14.5137 11.6668 13.7083C11.6668 12.9029 10.9206 12.25 10.0002 12.25C9.07969 12.25 8.3335 11.5971 8.3335 10.7917C8.3335 9.98625 9.07969 9.33333 10.0002 9.33333M10.0002 15.1667C9.07969 15.1667 8.3335 14.5137 8.3335 13.7083M10.0002 15.1667V15.75M10.0002 8.75V9.33333M10.0002 9.33333C10.9206 9.33333 11.6668 9.98625 11.6668 10.7917"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span>بانکی</span>
                            </div>
                            <span class="font-medium text-left" dir="ltr">{{ number_format($bankBalance) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-b border-[#184D6C]/15 pb-2">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M15.8332 11.6665V16.6665M15.8332 16.6665L17.4998 14.9998M15.8332 16.6665L14.1665 14.9998"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.3332 10.0002C18.3332 6.85747 18.3332 5.28612 17.3569 4.30981C16.3805 3.3335 14.8092 3.3335 11.6665 3.3335M11.6665 16.6668H8.33317C5.19047 16.6668 3.61913 16.6668 2.64281 15.6905C1.6665 14.7142 1.6665 13.1429 1.6665 10.0002C1.6665 6.85747 1.6665 5.28612 2.64281 4.30981C3.61913 3.3335 5.19047 3.3335 8.33317 3.3335"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M8.33333 13.3335H5" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M10.8332 13.3335H10.4165" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M1.6665 8.3335L5.83317 8.3335M18.3332 8.3335L9.1665 8.3335"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-[#184D6C]">مجموعه</span>
                            </div>
                            <span class="font-bold text-[16px] text-left" dir="ltr">{{ number_format($totalBalance)
                                }}</span>
                        </div>
                    </div>




                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-[#FFFFFF]/10  rounded-[8px] mr-auto  backdrop:blur-2xl text-[12px] p-2 mt-2 text-gray-800 hover:shadow-md transition border border-white flex items-center justify-end gap-2 w-[114px] h-[25px]">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 12.5L12.5 7.5M12.5 7.5H8.75M12.5 7.5V11.25" stroke="#184D6C"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5.83317 2.78136C7.05889 2.07231 8.48197 1.6665 9.99984 1.6665C14.6022 1.6665 18.3332 5.39746 18.3332 9.99984C18.3332 14.6022 14.6022 18.3332 9.99984 18.3332C5.39746 18.3332 1.6665 14.6022 1.6665 9.99984C1.6665 8.48197 2.07231 7.05889 2.78136 5.83317"
                                stroke="#184D6C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span wire:loading.remove class="text-[#184D6C]">نمایش گزارش</span>
                        <span wire:loading class="text-[#184D6C]">
                            در حال انتقال...
                        </span>

                    </button>
                </div>

            </div>
            @endforeach
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- محتوای اصلی - فرم و جدول -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">
            <!-- فرم ثبت ارسال -->
            <div class="flex flex-col
         dark:bg-black dark:text-white dark:border dark:border-white
 bg-white   border border-[#D7E5EC] shadow-sm backdrop:blur-lg           mx-auto
         w-full max-w-[420px] lg:max-w-[474px]
         p-[10px]
         h-auto
         rounded-[12px]
         space-y-2">
                <!-- هدر فرم -->
                <div class="flex flex-row gap-4 p-4  rounded-[12px] flex-wrap items-center justify-between">
                    <div class="flex">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                                stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <p class="text-center">
                            @if($isEditMode)
                            ویرایش ارسال
                            @else
                            فورم ثبت ارسال
                            @endif
                        </p>
                    </div>
                    <button wire:click="toggleAccountType" class="rounded-[8px] p-[10px] text-white vazir px-12 font-semibold transition-colors duration-500 ease-in-out
                        {{ $accountType === 'نقدی' ?'bg-[#184D6C] text-white'
                : 'bg-white text-[#184D6C] border border-[#184D6C] hover:bg-[#184D6C]/10'}}">
                        {{ $accountType === 'نقدی' ? 'نقدی' : 'بانکی' }}
                    </button>
                </div>

                <!-- فرم -->
                <form wire:submit.prevent="submitRemittance">
                    <!-- حساب مشتری و صرافی مقصد -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- حساب مشتری فرستنده -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">از حساب مشتری</label>
                            <div x-data="{
                searchValue: @entangle('search'),
                customers: @js($customers),
                handleSelect(event) {
                    const value = event.target.value;
                    const match = value.match(/(\d+) - (.+)/);
                    if (match) {
                        const accountNumber = match[1];
                        const fullname = match[2];
                        const customer = this.customers.find(c => 
                            c.account_number == accountNumber && 
                            c.fullname == fullname
                        );
                        if (customer) {
                            $wire.selectCustomer(customer.id);
                        }
                    }
                }
            }" class="relative w-full">
                                <input list="fromCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="  انتخاب حساب فرستنده"
                                    class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="fromCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->account_number }} - {{ $customer->fullname }}">
                                        @endforeach
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            @error('selectedAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- حساب مشتری گیرنده -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">به حساب مشتری</label>
                            <div x-data="{
                searchValue: @entangle('to_customer_search'),
                customers: @js($customers),
                handleSelect(event) {
                    const value = event.target.value;
                    const match = value.match(/(\d+) - (.+)/);
                    if (match) {
                        const accountNumber = match[1];
                        const fullname = match[2];
                        const customer = this.customers.find(c => 
                            c.account_number == accountNumber && 
                            c.fullname == fullname
                        );
                        if (customer) {
                            $wire.selectToCustomer(customer.id);
                        }
                    }
                }
            }" class="relative w-full">
                                <input list="toCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="  انتخاب حساب گیرنده"
                                    class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="toCustomersList">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->account_number }} - {{ $customer->fullname }}">
                                        @endforeach
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            @error('to_customer_id')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">

                        <!--  نمبر حواله -->

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحواله</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="remittance_number" wire:blur="formatAmount"
                                    placeholder="0" readonly
                                    class="w-full h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500   dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                            @error('remittance_number')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- صرافی مقصد -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">به صرافی</label>
                            <div class="relative w-full">
                                <select wire:model="to_sarafi"
                                    class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <option value="">انتخاب صرافی مقصد</option>
                                    @foreach ($sarafi_list as $sarafi)
                                    <option value="{{ $sarafi->id }}">
                                        {{ $sarafi->sarafi_name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            @error('to_sarafi')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>

                    <!-- مقدار و نوع ارز -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- مقدار -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2  focus:ring-blue-500   dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            @if($amountInWords)
                            <p class="text-sm text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- نوع ارز -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </div>
                            </div>
                            @error('currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- تاریخ و زون -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- تاریخ -->
                        <div class="lg:w-full relative" x-data="persianDatePicker()" x-init="init()">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>

                            <!-- Input field -->
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
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

                        <!-- زون -->
                        <div class="w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون</label>
                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 appearance-none">
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
                    </div>


                    <!-- توضیحات -->
                    <div class="mt-3">
                        <textarea wire:model="description" rows="3" placeholder="شرح ارسال ..."
                            class="w-full p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 resize-none"></textarea>
                        @error('description')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- دکمه‌های عملیات -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 py-4 justify-center items-center text-center">
                        <button type="submit"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white whitespace-nowrap">
                            @if($isEditMode)
                            بروزرسانی
                            @else
                            ثبت
                            @endif
                        </button>

                        @if(!$isEditMode)
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#184D6C] text-[14px] vazir font-semibold rounded-[8px] px-12 py-3 text-white whitespace-nowrap">
                            ثبت و چاپ
                        </button>
                        @endif

                        <button type="button" wire:click="cancel"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white whitespace-nowrap">
                            انصراف
                        </button>
                    </div>
                </form>
            </div>

            <!-- جدول تراکنش‌ها -->
            <div
                class="flex-1 flex flex-col  bg-white   border border-[#D7E5EC] shadow-sm backdrop:blur-lg   p-4 lg:p-6 rounded-[12px] w-full">
                <!-- هدر جدول -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 justify-between items-center   p-4 rounded-[12px] mb-4 gap-4">
                    <h1 class="text-xl lg:text-2xl inter">تراکنش‌های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <!-- فیلتر مشتری -->
                        @if($selectedCustomerId && $selectedCustomer)
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: {{ $selectedCustomer->fullname }}</span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        @endif

                        <!-- جستجو -->
                        <div class="relative flex-1">
                            <input type="text" wire:model.live="search" placeholder="جستجو..." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[12px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">

                            {{-- آیکون --}}
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                                <path d="M20 20L22 22" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            @if($search)
                            <button wire:click="clearSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- لیست نتایج جستجو -->
                @if($search && count($filteredCustomers) > 0 && !$selectedCustomerId)
                <div class="mb-4 border border-gray-300 rounded-md shadow-lg bg-white">
                    <ul class="max-h-60 overflow-y-auto">
                        @foreach($filteredCustomers as $customer)
                        <li wire:click="selectCustomer({{ $customer->id }})"
                            class="px-4 py-3 hover:bg-blue-100 cursor-pointer flex justify-between items-center border-b border-gray-100 last:border-b-0">
                            <div>
                                <span class="font-medium">{{ $customer->fullname }}</span>
                                <span class="text-gray-500 text-sm mr-2">{{ $customer->account_number }}</span>
                            </div>
                            <span class="text-blue-500 text-sm">انتخاب</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- جدول -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                        <thead
                            class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                            <tr>
                                <th class="px-4 py-4 font-bold">#</th>
                                <th class="px-4 py-4 font-bold">مشتری</th>
                                <th class="px-4 py-4 font-bold">صرافی</th>
                                <th class="px-4 py-4 font-bold">نمبر احواله</th>
                                <th class="px-4 py-4 font-bold">مبلغ</th>
                                <th class="px-4 py-4 font-bold">واحد</th>
                                <th class="px-4 py-4 font-bold text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold">تاریخ</th>
                                <th class="px-4 py-4 font-bold text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $key => $transaction)
                            <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    {{ $transaction->customer->fullname ?? '-' }}
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    {{ $this->getSarafiName($transaction) }}
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    {{ optional($transaction->changerdeal)->remittance_number ?? '-' }}
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    <span class="{{ $transaction->type === 'برداشت' ? 'text-red-600' : 'text-black' }}">
                                        {{ number_format($transaction->amount) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium">
                                    {{ collect($currencies)->firstWhere('code', $transaction->currency)['name_fa'] ??
                                    $transaction->currency }}
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] font-medium text-center">
                                    <div class="space-y-1 text-right">
                                        <p class="text-sm">زون: {{ $transaction->zone }}</p>
                                        @if($transaction->description)
                                        <p class="text-sm">شرح: {{ $transaction->description }}</p>
                                        @endif
                                        <p class="text-sm">
                                            @if($transaction->type === 'برداشت')
                                            برای: {{ $this->getOtherCustomerName($transaction) }}
                                            @else
                                            از حساب : {{ $this->getOtherCustomerName($transaction) }}
                                            @endif
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] text-center">
                                    <div>
                                        <div class="font-medium">
                                            {{ explode(' ', $transaction->date)[0] }}
                                        </div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        @php
                                        $currentuser =Auth::guard('sarafi')->user();
                                        @endphp


                                        @if ($currentuser && $currentuser->role==='superadmin')
                                        <button wire:click="edit({{ $transaction->id }})"
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 hover:bg-blue-200 transition-colors"
                                            title="ویرایش">
                                            <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                class="w-5 h-5" alt="Edit">
                                        </button>

                                        <button wire:click="confirmDelete({{ $transaction->id }})"
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 transition-colors"
                                            title="حذف">
                                            <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                class="w-5 h-5" alt="Delete">
                                        </button>

                                        @endif
                                        <button wire:click="print({{ $transaction->id }})"
                                            class="w-10 h-10 flex items-center justify-center rounded-full transition-colors"
                                            title="پرینت">
                                            <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                class="w-10 h-10" alt="Print">
                                        </button>

                                        <script>
                                            let printListenerRegistered = false;

    document.addEventListener('livewire:init', () => {
        if (printListenerRegistered) return;
        printListenerRegistered = true;

        Livewire.on('print-pdf', (data) => {

            /* 🔹 1. دانلود (با لینک مخفی) */
            const downloadLink = document.createElement('a');
            downloadLink.href = data.url;
            downloadLink.download = '';
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();

            /* 🔹 2. پرینت */
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = data.url;
            document.body.appendChild(iframe);

            iframe.onload = () => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                /* 🔹 3. حذف با تأخیر */
                setTimeout(() => {
                    iframe.remove();
                    downloadLink.remove();
                }, 5000); // ⏱ ۵ ثانیه
            };
        });
    });
                                        </script>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-8 text-lg">
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

        <!-- مودال تأیید حذف -->
        @if($confirmDeleteId)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 text-center animate-fadeIn">
                <h2 class="text-2xl text-black font-medium mb-4">حذف تراکنش</h2>
                <p class="text-gray-600 mb-6">آیا مطمئن هستید می‌خواهید این تراکنش را حذف کنید؟</p>
                <div class="flex justify-center gap-3">
                    <button wire:click="$set('confirmDeleteId', null)"
                        class="px-8 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        انصراف
                    </button>
                    <button wire:click="deleteConfirmed"
                        class="px-8 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        حذف
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- استایل‌های سفارشی -->
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

            input[list]::-webkit-calendar-picker-indicator {
                display: none !important;
            }

            .animate-fadeIn {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </div>
</div>