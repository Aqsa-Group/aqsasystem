<div>
    <div class=" mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black">خرید و فروش ارز</h1>
            <h1 class="text-[#8C8C8C] text-[18px]">صفحه درج خرید و فروش ارز</h1>
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
                        <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
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
            <div class="max-h-[680px] overflow-y-auto w-full">
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
                        <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
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
            <div class="flex flex-col bg-[#F5F5F5] w-[420px] lg:w-[534px] p-[12px] h-auto rounded-[12px] space-y-2  mx-auto"
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
                            <label class="block text-[15px] font-medium text-black mb-1 vazir">واحد ارز</label>
                            <div class="relative">
                                <select wire:model="currency"
                                    class="w-full h-[55px] p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" class="w-4 h-4"
                                        alt="">
                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            @if($amountInWords)
                            <p class="text-sm text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- واحد تبدیل ارز و مبلغ معادل -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="lg:w-[160px]">
                            <label class="block text-[15px] font-medium text-black mb-1 vazir">واحد تبدیل ارز</label>
                            <div class="relative">
                                <select wire:model="to_currency"
                                    class="w-full h-[55px] p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" class="w-4 h-4"
                                        alt="">
                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ معادل</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="eq_amount" placeholder="0" readonly
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 bg-gray-100 cursor-not-allowed dark:text-white" />
                            </div>
                            @if($eqAmountInWords)
                            <p class="text-sm text-purple-600 mt-2 vazir">{{ $eqAmountInWords }}</p>
                            @endif
                            @error('eq_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                    <!-- نرخ و تاریخ -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نرخ ارز</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="exchange_rate" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" />
                            </div>
                            @if($exchangeRateInWords)
                            <p class="text-sm text-green-600 mt-2 vazir">{{ $exchangeRateInWords }}</p>
                            @endif
                            @error('exchange_rate')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex-1 relative">
                            <label class="block text-[15px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                             <svg class="absolute left-3 bottom-3 -translate-y-1/2 pointer-events-none" width="20"
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
                    </div>



                    <!-- شرح -->
                    <div>
                        <label class="block text-[15px] font-medium text-black mb-1 vazir">شرح تراکنش</label>
                        <textarea rows="3" wire:model="description" placeholder="شرح تراکنش..."
                            class="w-full p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>


                    <!-- آپلود فایل -->
                    <div>
                        <label class="block text-[15px] font-medium text-black mb-1 vazir">فایل تراکنش</label>
                        <div x-data="{ isDragging: false }"
                            @drop.prevent="isDragging = false; $wire.upload('transaction_file', $event.dataTransfer.files[0])"
                            @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                            :class="isDragging ? 'border-[#2563EB] bg-blue-50' : 'border-[#112080] bg-white'"
                            class="w-full h-[120px] p-3 rounded-[10px] border border-dashed flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 transition">

                            <!-- اضافه کردن label برای input فایل -->
                            <label for="fileInput"
                                class="w-full h-full flex flex-col justify-center items-center cursor-pointer">
                                <template x-if="!$wire.transaction_file">
                                    <div class="flex flex-col items-center">
                                        <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}"
                                            class="w-10 h-10 mb-1" alt="">
                                        <h1 class="font-vazir text-gray-600 mt-2 text-[15px] vazir">فایل را اینجا وارد
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
                    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-3 justify-center items-center text-center flex-wrap">
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
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-4 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[300px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-[16px] vazir">ترانزکشن های ثبت شده</h1>
                        <div class="relative w-full">
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] w-full h-[46px] bg-transparent rounded-[10px] p-2 pr-10 text-sm"
                            placeholder="جستجو ...">
                        <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5">
                    </div>
                </div>

                <div class="overflow-x-auto w-full">
                    <div class="max-h-[650px] overflow-y-auto min-w-[800px]">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="bg-[#2B65E5] text-white text-[18px] vazir h-[50px] sticky top-0">
                                <tr>
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
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent text-center">
                                    <td class="px-2 py-3 vazir text-[18px] font-medium">{{ $loop->iteration }}</td>
                                    <td
                                        class="px-2 py-3 vazir text-[18px] font-medium {{ $transaction->type === 'خرید' ? 'text-green-600' : 'text-red-600' }}">
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
                                                    class="w-7 h-7" alt="Edit">
                                            </button>
                                            <button wire:click="deleteTransaction({{ $transaction->id }})"
                                                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-7 h-7" alt="Delete">
                                            </button>
                                            <button wire:click="printTransaction({{ $transaction->id }})"
                                                class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
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