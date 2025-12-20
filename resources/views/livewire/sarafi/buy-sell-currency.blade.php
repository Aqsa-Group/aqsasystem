<div>
    <div class=" mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold dark:text-white text-black">خرید و فروش ارز</h1>
            <h1 class="text-[#8C8C8C] dark:text-white text-[18px]">صفحه درج خرید و فروش ارز</h1>
        </div>
        <hr class="text-[#D9D9D9] mt-6 pl-4 pr-4">

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

        <div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2  px-10  gap-10 mt-3 justify-center">
            <!-- جدول خرید -->
            <div class="max-h-[680px] overflow-y-auto w-full">
                <table class="w-[500px] text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <h1 class="text-[24px] mb-5">مجموعه خرید ارز</h1>
                    <thead
                        class="bg-[#2B65E5] w-full dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <tr>
                            <th class="px-6 py-4 font-bold w-16">#</th>
                            @foreach (['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'] as $currency)
                            <th class="px-6 py-4 font-bold w-48 text-center">{{ $this->getCurrencyName($currency) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-black dark:text-white border-b border-[#D9D9D9] bg-transparent">
                            <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">1</td>
                            @foreach (['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'] as $currency)
                            <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center">
                                {{ number_format($totalBuy[$currency] ?? 0 ,2) }}
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- جدول فروش -->
            <div class="max-h-[680px] overflow-y-auto w-full ">
                <table class="w-[500px] text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <h1 class="text-[24px] mb-5">مجموعه فروش ارز</h1>
                    <thead
                        class="bg-[#2B65E5] w-full dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <tr>
                            <th class="px-6 py-4 font-bold w-16">#</th>
                            @foreach (['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'] as $currency)
                            <th class="px-6 py-4 font-bold w-48 text-center">{{ $this->getCurrencyName($currency) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-black dark:text-white border-b border-[#D9D9D9] bg-transparent">
                            <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">1</td>
                            @foreach (['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'] as $currency)
                            <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center">
                                {{ number_format($totalSell[$currency] ?? 0 ,2) }}
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- مانده خالص -->
        <div class="grid grid-cols-3 md:grid-cols-8 justify-center items-center text-center mx-auto pr-14 mt-6">
            @foreach ([ 'afn', 'usd' , 'irr' ,'pkr', 'eur', 'aed', 'try', 'cny'] as $currency)
            @php
            $balance = $netAmounts[$currency] ?? 0;
            @endphp
            <div class="flex gap-2">
                <span class="{{ $balance < 0 ? 'text-red-500' : '' }}">
                    {{ number_format($balance) }}
                </span>
                <span class="{{ $balance < 0 ? 'text-red-500' : '' }}">
                    {{ $this->getCurrencyName($currency) }}
                </span>
            </div>
            @endforeach
        </div>
        <div class="flex flex-col lg:flex-row gap-5 mt-7 mx-auto">
            <!-- فرم تراکنش -->
            <div class="flex flex-col dark:bg-black dark:border-white dark:border bg-[#F5F5F5] w-[420px] lg:w-[534px] p-[12px] h-auto rounded-[12px] space-y-2  mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-row justify-between pt-[20px] pb-[20px] border border-[#8C8C8C] rounded-[12px] items-center">
                    <p class="flex items-center text-center pr-3">
                        <img src="{{ asset('assets/sarafi/all_icon/pencil.svg') }}" alt="" class="h-5 w-5">
                        {{ $isEditing ? 'فورم ویرایش معاملات نقدی' : 'فورم ثبت معاملات نقدی' }}
                    </p>


                    <div class="flex items-center gap-2 pl-2 ">

                        <button wire:click="toggleTransactionType" type="button" class="rounded-[8px] p-[10px] text-white vazir text-[14px]
                                transition-colors duration-500 ease-in-out py-4
                                {{ $transactionType === 'خرید' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                            {{ $transactionType === 'خرید' ? 'خرید (واحد ارز دربافت صندوق)' : 'فروش (واحد ارز برداشت
                            صندوق)' }}
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="submitTransaction" class="space-y-3">
                    <!-- مقدار و نوع ارز -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="lg:w-[160px]">
                            <label class="block text-[15px] font-medium dark:text-white text-black mb-1 vazir">واحد ارز</label>
                            <div class="relative">
                                <select wire:model="currency"
                                    class="w-full dark:bg-black dark:border-white dark:text-white dark:placeholder:text-white h-[55px] p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" class="w-4 h-4 dark:hidden"
                                        alt="">

                                        <svg width="24" class="hidden dark:block" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مبلغ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" placeholder="0"
                                    class="w-full dark:bg-black dark:border-white  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            @if($amountInWords)
                            <p class="text-sm dark:text-white text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- واحد تبدیل ارز و مبلغ معادل -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="lg:w-[160px]">
                            <label class="block text-[15px] font-medium  dark:text-white text-black mb-1 vazir">واحد تبدیل ارز</label>
                            <div class="relative">
                                <select wire:model="to_currency"
                                    class="w-full dark:bg-black dark:text-white dark:border-white h-[55px] p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" class="w-4 h-4 dark:hidden"
                                        alt="">
                                        <svg width="24" class="hidden dark:block " height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مبلغ معادل</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="eq_amount" placeholder="0" readonly
                                    class="w-full dark:bg-black dark:border-white dark:text-white  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 bg-gray-100 cursor-not-allowed dark:text-white" />
                            </div>
                            @if($eqAmountInWords)
                            <p class="text-sm dark:text-white text-purple-600 mt-2 vazir">{{ $eqAmountInWords }}</p>
                            @endif
                            @error('eq_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                    <!-- نرخ و تاریخ -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                @if ($transactionType==='خرید')
                                    نرخ خرید ارز
                                    @else
                                    نرخ فروش ارز
                                @endif
                            </label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="exchange_rate" placeholder="0"
                                    class="w-full dark:bg-black dark:border-white dark:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" />
                            </div>
                            @if($exchangeRateInWords)
                            <p class="text-sm dark:text-white text-green-600 mt-2 vazir">{{ $exchangeRateInWords }}</p>
                            @endif
                            @error('exchange_rate')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex-1 relative">
                            <label class="block text-[15px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                                class="w-full  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer" />
                        
                        </div>
                    </div>



                    <!-- شرح -->
                    <div>
                        <label class="block text-[15px] font-medium dark:text-white text-black mb-1 vazir">شرح تراکنش</label>
                        <textarea rows="3" wire:model="description" placeholder="شرح تراکنش..."
                            class="w-full dark:border-white dark:placeholder:text-white p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>


                    <!-- آپلود فایل -->
                    <div>
                        <label class="block text-[15px] font-medium dark:text-white text-black mb-1 vazir">فایل تراکنش</label>
                        <div x-data="{ isDragging: false }"
                            @drop.prevent="isDragging = false; $wire.upload('transaction_file', $event.dataTransfer.files[0])"
                            @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                            :class="isDragging ? 'border-[#2563EB] bg-blue-50 dark:bg-black' : 'border-[#112080] dark:border-white bg-white dark:bg-black'"
                            class="w-full h-[120px] p-3 rounded-[10px] border border-dashed flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 transition">

                            <!-- اضافه کردن label برای input فایل -->
                            <label for="fileInput"
                                class="w-full h-full flex flex-col justify-center items-center cursor-pointer">
                                <template x-if="!$wire.transaction_file">
                                    <div class="flex flex-col items-center">
                                        <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}"
                                            class="w-10 h-10 mb-1" alt="">
                                        <h1 class="font-vazir dark:text-white text-gray-600 mt-2 text-[15px] vazir">فایل را اینجا وارد
                                            کنید یا بکشید</h1>

                                    </div>
                                </template>

                                <template x-if="$wire.transaction_file">
                                    <div class="flex flex-col items-center">
                                        <img src="{{ asset('assets/sarafi/all_icon/file-uploaded.svg') }}"
                                            class="w-10 h-10 mb-1" alt="">
                                        <h1 class="font-vazir text-green-600 text-[15px]">فایل انتخاب شده</h1>
                                        <p class="text-gray-600 text-sm mt-1" x-text="$wire.transaction_file.name"></p>
                                        <p class="text-blue-500 text-xs mt-1">برای تغییر فایل کلیک کنید</p>
                                    </div>
                                </template>
                            </label>

                            <input type="file" wire:model="transaction_file" class="hidden" id="fileInput">

                        </div>
                        @error('transaction_file')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- دکمه‌های نهایی -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-3 justify-center items-center text-center flex-wrap">
                        <button type="submit"
                            class="bg-[#61B138] text-[15px] vazir font-semibold rounded-[8px] px-10 py-3 text-white hover:bg-green-700 transition">
                            {{ $isEditing ? 'بروزرسانی' : 'ثبت' }}
                        </button>

                        @if(!$isEditing)
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[15px] vazir font-semibold rounded-[8px] px-10 py-3 text-white hover:bg-blue-700 transition">
                            ثبت و چاپ
                        </button>
                        @endif


                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[15px] vazir font-semibold rounded-[8px] px-10 py-3 text-white hover:bg-red-700 transition">
                            {{ $isEditing ? 'لغو ویرایش' : 'انصراف' }}
                        </button>


                    </div>
                </form>
            </div>

            <!-- جدول تراکنش‌ها -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] dark:bg-black dark:border-white dark:border p-4 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[300px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-[16px] vazir">ترانزکشن های ثبت شده</h1>
                    <div class="relative w-full">
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] dark:bg-black dark:text-white dark:placeholder:text-white dark:border-white dark:border w-full h-[46px] bg-transparent rounded-[10px] p-2 pr-10 text-sm"
                            placeholder="جستجو ...">  
                        <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 dark:hidden">

                            <svg width="24"  class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 hidden dark:block" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

                    </div>
                </div>

                <div class="overflow-x-auto w-full">
                    <div class="max-h-[650px] overflow-y-auto min-w-[800px]">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="bg-[#2B65E5] text-white text-[18px] vazir h-[50px] sticky top-0">
                                <tr class="dark:text-white">
                                    <th class="px-4 py-3 font-bold">#</th>
                                    <th class="px-4 py-3 font-bold">معامله</th>
                                    <th class="px-4 py-3 font-bold">مبلغ</th>
                                    <th class="px-4 py-3 font-bold">ارز</th>
                                    <th class="px-4 py-3 font-bold">نرخ</th>
                                    <th class="px-4 py-3 font-bold">مبلغ معادل</th>
                                    <th class="px-4 py-3 font-bold">ارز معادل</th>
                                    <th class="px-4 py-3 font-bold text-center">شرح معامله</th>
                                    <th class="px-4 py-3 font-bold text-center">تاریخ</th>
                                    <th class="px-4 py-3 font-bold text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr class="text-black dark:text-white border-b border-[#D9D9D9] bg-transparent text-center">
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{ $loop->iteration }}</td>
                                    <td
                                        class="px-2 py-3 vazir text-[18px] font-medium {{ $transaction->type === 'خرید' ? 'text-green-600 dark:text-white' : 'text-red-600 dark:text-white' }}">
                                        {{ $transaction->type }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{
                                        number_format($transaction->amount ,2) }}</td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{
                                        $this->getCurrencyName($transaction->from_currency) }}</td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{
                                        number_format($transaction->exchange_rate, 2) }}</td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{
                                        number_format($transaction->eq_amount ,2) }}</td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{
                                        $this->getCurrencyName($transaction->to_currency) }}</td>
                                    <td class="px-6 py-3 vazir text-[18px] font-medium">{{ $transaction->description }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">
                                        {{ explode(' ', $transaction->date)[0] }}
                                    </td>

                                    <!-- در بخش عملیات جدول -->
                                    <td class="py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="editTransaction({{ $transaction->id }})"
                                                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
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
                                            <button wire:click="deleteTransaction({{ $transaction->id }})"
                                                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
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
                                            <button wire:click="printTransaction({{ $transaction->id }})"
                                                class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
                                                 <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10 dark:hidden" alt="Print">
                                                <svg width="30" class="hidden dark:block" height="30"
                                                    viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                                        fill="white" />
                                                </svg>
                                            </button>

                                            <!-- مودال تایید حذف -->
                                            @if ($confirmDeleteId)
                                            <div
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-20 z-50">
                                                <div
                                                    class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] pb-[21px] rounded-[12px] shadow-xl w-[653px] h-[252.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3]">

                                                    <!-- دکمه بستن -->
                                                    <div class="flex justify-start">
                                                        <button wire:click="cancelDelete" class="h-4 w-4">
                                                            <img src="{{ asset('assets/sarafi/all_icon/close.svg') }}"
                                                                alt="بستن">
                                                        </button>
                                                    </div>

                                                    <!-- تیتر -->
                                                    <h1
                                                        class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">
                                                        حذف معــاملـــه
                                                    </h1>

                                                    <hr class="bg-[#E1DED3] mt-4">

                                                    <!-- متن سوال -->
                                                    <p class="mb-6 text-xl shabnam mt-5">
                                                        آیا مطمئن هستید می خواهید این معاملــه را حذف کنید؟
                                                    </p>

                                                    <!-- دکمه‌های تایید -->
                                                    <div class="flex justify-center gap-4">
                                                        <button wire:click="cancelDelete"
                                                            class="px-20 text-white text-xl shabnam-fd py-4 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                                                            {{ __('messages.no') ?? 'خیر' }}
                                                        </button>
                                                        <button wire:click="deleteConfirmed"
                                                            class="px-20 py-4 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                                                            {{ __('messages.yes') ?? 'بلی' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>