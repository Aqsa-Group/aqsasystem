<div>
    <div class="container mx-auto py-4" dir="rtl">

        {{-- پیام‌ها --}}
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#184D6C] vazir">
            <div style="margin-right: 296px" class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px] text-center align-middle">
                    {{ session('message') }}
                </h2>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-800 vazir">
            <div style="margin-right: 296px" class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px] text-center align-middle">
                    {{ session('error') }}
                </h2>
            </div>
        </div>
        @endif

        {{-- کارت‌های ارزها --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mt-4">
            @foreach ($currencies as $code)
            @php
            $name = $this->currencyNames[$code] ?? $code;
            $balance = $this->customerTotalBalances[$name] ?? 0;
            @endphp
            <div
                class="bg-white dark:bg-black rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-3 h-[120px] flex flex-col justify-between">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $name }}</span>
                    <div
                        class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 font-bold">
                        {{ $code }}
                    </div>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-700 pt-2">
                    <span class="block text-xs text-gray-500">موجودی</span>
                    <span class="block text-lg font-bold text-gray-800 dark:text-white dir-ltr">
                        {{ number_format($balance) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- فرم و جدول --}}
        <div class="flex flex-col lg:flex-row gap-5 mt-4">

            {{-- فرم تبدیل (باریک‌تر) --}}
            <div
                class="flex flex-col bg-white dark:bg-black dark:text-white dark:border dark:border-white border border-[#D7E5EC] shadow-sm mx-auto w-full max-w-[380px] lg:max-w-[400px] p-[10px] h-fit rounded-[12px] space-y-2">

                <div class="flex items-center gap-2">
                    <i class="fas fa-exchange-alt text-blue-500 text-2xl"></i>
                    <span class="vazir font-bold text-lg text-gray-800 dark:text-white">
                        {{ $editingConversionId ? 'ویرایش تبدیل ارز' : 'تبدیل ارز' }}
                    </span>
                </div>

                <form wire:submit.prevent="submitTransaction" class="dark:text-white">

                    {{-- انتخاب مشتری --}}
                    <div class="mt-2 w-full">
                        <div x-data="{
                            searchValue: '',
                            selectedId: @entangle('selectedAccount'),
                            customers: @js($customers),
                            open: false,
                            get filteredCustomers() {
                                if (!this.searchValue) return this.customers;
                                return this.customers.filter(c => c.fullname.includes(this.searchValue));
                            },
                            selectCustomer(customer) {
                                this.selectedId = customer.id;
                                this.searchValue = customer.fullname;
                                this.open = false;
                                $wire.selectCustomer(customer.id);
                                $wire.set('search', customer.fullname);
                            },
                            clearSelection() {
                                this.selectedId = null;
                                this.searchValue = '';
                                this.open = false;
                                $wire.set('selectedAccount', null);
                                $wire.set('search', '');
                            }
                        }" x-init="let selected = customers.find(c => c.id == selectedId); if(selected) searchValue = selected.fullname;"
                            @click.outside="open = false" class="relative w-full">

                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شماره
                                حساب</label>

                            <div class="relative">
                                <input type="text" x-model="searchValue" @focus="open = true" @input="open = true"
                                    placeholder="جستجوی مشتری..." autocomplete="off"
                                    class="w-full h-[60px] bg-[#EFF6F9] dark:bg-black dark:text-white placeholder:text-[#929897] p-3 pr-10 rounded-[12px] border dark:border-white focus:ring-2 focus:ring-blue-500">

                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                        <path d="M21 21L16.65 16.65M19 11A8 8 0 1 1 3 11A8 8 0 0 1 19 11Z"
                                            stroke="#929897" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                </div>

                                <button x-show="searchValue" type="button" @click="clearSelection()"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">✕</button>
                            </div>

                            <div x-show="open" x-transition
                                class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="customer in filteredCustomers" :key="customer.id">
                                    <div @click="selectCustomer(customer)"
                                        class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-white">
                                        <span x-text="customer.fullname"></span>
                                    </div>
                                </template>
                                <div x-show="filteredCustomers.length === 0" class="px-4 py-3 text-gray-500">مشتری پیدا
                                    نشد</div>
                            </div>

                            @error('selectedAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- فیلدهای تبدیل --}}
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">از
                                ارز</label>
                            <select wire:model.live="from_currency"
                                class="w-full dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 dark:text-white appearance-none">
                                @foreach ($currencies as $code)
                                <option value="{{ $code }}">{{ $currencyNames[$code] ?? $code }}</option>
                                @endforeach
                            </select>
                            @error('from_currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">به
                                ارز</label>
                            <select wire:model.live="to_currency"
                                class="w-full dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 dark:text-white appearance-none">
                                @foreach ($currencies as $code)
                                <option value="{{ $code }}">{{ $currencyNames[$code] ?? $code }}</option>
                                @endforeach
                            </select>
                            @error('to_currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مبلغ
                                برداشت</label>
                            <input type="text" wire:model.live="withdraw_amount" placeholder="۰"
                                class="w-full dark:border-white dark:bg-black dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:text-white"
                                oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')">
                            @error('withdraw_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نرخ
                                تبدیل</label>
                            <input type="text" wire:model.live="rate" placeholder="۰٫۰۰۰۰"
                                class="w-full dark:border-white dark:bg-black dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:text-white"
                                oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9.]/g, '')">
                            @error('rate')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مبلغ
                                دریافت</label>
                            <input type="text" wire:model="receive_amount" readonly
                                class="w-full dark:border-white dark:bg-gray-700 dark:text-white bg-gray-100 h-[60px] p-3 rounded-[12px] border focus:ring-2 focus:ring-blue-500 cursor-not-allowed">
                            @error('receive_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- تاریخ --}}
                    <div class="mt-3 w-full relative" x-data="persianDatePicker()" x-init="init()">
                        <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>
                        <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                            placeholder="۱۴۰۳/۰۱/۰۱"
                            class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
                            readonly>
                        {{-- مودال انتخابگر --}}
                        <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                            @keydown.escape.window="closePicker()" @click.away="closePicker()"
                            class="fixed z-50 inset-0 overflow-y-auto">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                                <div
                                    class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6">
                                    {{-- هدر --}}
                                    <div class="flex justify-between items-center mb-4">
                                        <button @click="prevYear()" type="button"
                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <div class="flex items-center gap-2">
                                            <span x-text="monthsAfghan[currentMonth]"
                                                class="text-lg font-bold text-gray-800 dark:text-white"></span>
                                            <span x-text="currentYear"
                                                class="text-lg font-bold text-gray-800 dark:text-white"></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button @click="nextYear()" type="button"
                                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                            <button @click="closePicker()" type="button"
                                                class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- روزهای هفته --}}
                                    <div class="grid grid-cols-7 gap-1 mb-2">
                                        <template x-for="day in weekDaysAfghan" :key="day">
                                            <div class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 py-1"
                                                x-text="day"></div>
                                        </template>
                                    </div>

                                    {{-- روزها --}}
                                    <div class="grid grid-cols-7 gap-1">
                                        <template x-for="day in calendarDays" :key="day.key">
                                            <button @click="selectDate(day.day)" :class="{
                                                'bg-blue-500 text-white': day.isSelected,
                                                'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day.isToday && !day.isSelected,
                                                'text-gray-400 dark:text-gray-500': day.isOtherMonth,
                                                'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700': !day.isOtherMonth
                                            }" class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-medium transition"
                                                type="button">
                                                <span x-text="day.day"></span>
                                            </button>
                                        </template>
                                    </div>

                                    {{-- دکمه‌ها --}}
                                    <div
                                        class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                        <span x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"
                                            class="text-sm text-gray-600 dark:text-gray-300"></span>
                                        <div class="flex gap-2">
                                            <button @click="setToday()" type="button"
                                                class="px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 rounded-lg">امروز</button>
                                            <button @click="clearDate()" type="button"
                                                class="px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 rounded-lg">پاک</button>
                                            <button @click="applyDate()" type="button"
                                                class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">تأیید</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('date')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- توضیحات --}}
                    <div class="mt-3">
                        <textarea wire:model="description" rows="3" placeholder="توضیحات ..."
                            class="w-full p-3 rounded-[12px] border focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white resize-none"></textarea>
                        @error('description')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- دکمه‌ها --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4">
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            <span wire:loading.remove>{{ $editingConversionId ? 'ویرایش' : 'ثبت تبدیل' }}</span>
                            <span wire:loading class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                در حال {{ $editingConversionId ? 'ویرایش' : 'ثبت' }} ...
                            </span>
                        </button>
                        <button type="button" wire:click="cancel"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            {{ $editingConversionId ? 'لغو ویرایش' : 'لغو' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- جدول تاریخچه (با دکمه‌های ویرایش و حذف) --}}
            <div
                class="flex-1 flex flex-col dark:border dark:border-white dark:bg-black dark:text-white bg-white shadow-sm backdrop:blur-2xl border border-[#D7E5EC] p-3 md:p-4 lg:p-6 rounded-[12px] w-full max-w-full mb-5 mx-auto overflow-x-auto">

                <div class="flex flex-wrap items-center justify-between gap-4 p-3 md:p-4 rounded-[12px] mb-3">
                    <h1
                        class="text-2xl md:text-[19px] vazir font-semibold text-gray-800 dark:text-white whitespace-nowrap">
                        تاریخچه تبدیل‌ها
                    </h1>
                    <div class="flex flex-wrap items-center gap-3">
                        <div
                            class="inline-flex items-center gap-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 shadow-sm">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4h18M6 12h12M10 20h4" />
                            </svg>
                            <select wire:model.live="perPage"
                                class="h-10 min-w-[90px] appearance-none rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-white pl-8 pr-3 outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach($perPageOptions as $option)
                                <option value="{{ $option }}">{{ $option === 'all' ? 'همه' : $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative w-full sm:w-64 md:w-72">
                            <input type="text" wire:model.live="search" placeholder="جستجو ..."
                                class="w-full h-12 md:h-[51px] border border-[#D7E5EC] dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black rounded-[12px] pl-3 pr-12 text-sm md:text-base bg-transparent">
                            <svg class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" ...>...</svg>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto w-full">
                    <div class="min-w-[890px] max-h-[680px] overflow-y-auto">
                        <table class="w-full text-sm md:text-base text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                                <tr>
                                    <th class="px-1 py-3 font-bold text-center">#</th>
                                    <th class="px-1 py-3 font-bold text-right">مشتری</th>
                                    <th class="px-1 py-3 font-bold text-center">از → به</th>
                                    <th class="px-1 py-3 font-bold text-right">برداشت</th>
                                    <th class="px-1 py-3 font-bold text-right">دریافت</th>
                                    <th class="px-1 py-3 font-bold text-right">نرخ</th>
                                    <th class="px-1 py-3 font-bold text-center">تاریخ</th>
                                    <th class="px-1 py-3 font-bold text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversions as $key => $conv)
                                <tr
                                    class="text-black border-b dark:text-white border-[#D9D9D9] odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black">
                                    <td class="px-1 py-2 text-center">{{ $key + 1 }}</td>
                                    <td class="px-1 py-2">{{ $conv->customer->fullname ?? '-' }}</td>
                                    <td class="px-1 py-2 text-center">{{ $conv->from_currency }} → {{ $conv->to_currency
                                        }}</td>
                                    <td class="px-1 py-2 text-right" dir="ltr">{{ number_format($conv->withdraw_amount,
                                        2) }}</td>
                                    <td class="px-1 py-2 text-right" dir="ltr">{{ number_format($conv->receive_amount,
                                        2) }}</td>
                                    <td class="px-1 py-2 text-right" dir="ltr">{{ number_format($conv->rate, 4) }}</td>
                                    <td class="px-1 py-2 text-center">
                                        {{ $conv->transaction_date ??
                                        \Morilog\Jalali\Jalalian::fromCarbon($conv->created_at)->format('Y/m/d') }}
                                    </td>
                                    <td class="py-2 text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            {{-- دکمه ویرایش --}}
                                            <button wire:click="editConversion({{ $conv->id }})"
                                                class="w-10 h-12 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                                title="ویرایش">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            {{-- دکمه حذف --}}
                                            <button wire:click="confirmDelete({{ $conv->id }})"
                                                class="w-10 h-12 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                                title="حذف">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-8 text-lg">
                                        هیچ تبدیلی ثبت نشده است.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال تأیید حذف --}}
        @if ($confirmDeleteId)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">تأیید حذف</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">آیا از حذف این تبدیل ارز اطمینان دارید؟</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('confirmDeleteId', null)"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        انصراف
                    </button>
                    <button wire:click="deleteConversion"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        حذف
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- اسکریپت تاریخ --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('persianDatePicker', () => ({
                isOpen: false,
                showMonthSelector: false,
                showYearSelector: false,
                displayDate: '',
                currentYear: 1403,
                currentMonth: 0,
                selectedDate: null,
                yearRange: { start: 1400, end: 1410, years: [] },

                monthsAfghan: [
                    'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
                    'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
                ],
                weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
                daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],

                init() {
                    this.updateYearRange();
                    const today = this.getTodayPersian();
                    this.currentYear = today.year;
                    this.currentMonth = today.month - 1;

                    if (@this.get('date')) {
                        const parts = @this.get('date').split('/');
                        if (parts.length === 3) {
                            const y = parseInt(parts[0]), m = parseInt(parts[1]), d = parseInt(parts[2]);
                            if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                                this.selectedDate = { year: y, month: m, day: d };
                                this.displayDate = @this.get('date');
                                this.currentYear = y;
                                this.currentMonth = m - 1;
                            }
                        }
                    }
                },

                updateYearRange() {
                    this.yearRange.years = [];
                    for (let year = this.yearRange.start; year <= this.yearRange.end; year++) {
                        this.yearRange.years.push(year);
                    }
                },

                isLeapYear(year) {
                    return [1, 5, 9, 13, 17, 22, 26, 30].includes(year % 33);
                },

                getDaysInMonth(year, month) {
                    const days = [...this.daysInMonthNormal];
                    if (month === 11 && this.isLeapYear(year)) return 30;
                    return days[month];
                },

                getFirstDayOfWeek(year, month) {
                    const baseYear = 1403, baseDay = 4;
                    let days = 0;
                    for (let y = baseYear; y < year; y++) days += this.isLeapYear(y) ? 366 : 365;
                    for (let m = 0; m < month; m++) days += this.getDaysInMonth(year, m);
                    return (baseDay + days) % 7;
                },

                getTodayPersian() {
                    const now = new Date();
                    return this.gregorianToPersian(now.getFullYear(), now.getMonth() + 1, now.getDate());
                },

                gregorianToPersian(gy, gm, gd) {
                    const gDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    if ((gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0)) gDays[1] = 29;
                    let dayOfYear = gd;
                    for (let i = 0; i < gm - 1; i++) dayOfYear += gDays[i];
                    const marchDay = 79;
                    let py, pm, pd;
                    if (dayOfYear > marchDay) {
                        py = gy - 621;
                        let rem = dayOfYear - marchDay;
                        const pDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                        if (this.isLeapYear(py)) pDays[11] = 30;
                        for (pm = 0; pm < 12; pm++) {
                            if (rem <= pDays[pm]) { pd = rem; break; }
                            rem -= pDays[pm];
                        }
                        pm++;
                    } else {
                        py = gy - 622;
                        let rem = dayOfYear + 286;
                        const pDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                        if (this.isLeapYear(py)) pDays[11] = 30;
                        for (pm = 0; pm < 12; pm++) {
                            if (rem <= pDays[pm]) { pd = rem; break; }
                            rem -= pDays[pm];
                        }
                        pm++;
                    }
                    return { year: py, month: pm, day: pd };
                },

                get calendarDays() {
                    const days = [];
                    const daysInMonth = this.getDaysInMonth(this.currentYear, this.currentMonth);
                    const firstDay = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);
                    const today = this.getTodayPersian();

                    const prevMonthDays = this.currentMonth === 0 ?
                        this.getDaysInMonth(this.currentYear - 1, 11) :
                        this.getDaysInMonth(this.currentYear, this.currentMonth - 1);
                    for (let i = 0; i < firstDay; i++) {
                        days.push({
                            key: `prev-${i}`,
                            day: prevMonthDays - firstDay + i + 1,
                            isSelected: false,
                            isToday: false,
                            isOtherMonth: true,
                            isDisabled: true
                        });
                    }

                    for (let d = 1; d <= daysInMonth; d++) {
                        const isSelected = this.selectedDate &&
                            this.selectedDate.year === this.currentYear &&
                            this.selectedDate.month === this.currentMonth + 1 &&
                            this.selectedDate.day === d;
                        const isToday = today.year === this.currentYear &&
                            today.month === this.currentMonth + 1 &&
                            today.day === d;
                        days.push({
                            key: `current-${d}`,
                            day: d,
                            isSelected,
                            isToday,
                            isOtherMonth: false,
                            isDisabled: false
                        });
                    }

                    const remaining = 42 - days.length;
                    for (let d = 1; d <= remaining; d++) {
                        days.push({
                            key: `next-${d}`,
                            day: d,
                            isSelected: false,
                            isToday: false,
                            isOtherMonth: true,
                            isDisabled: true
                        });
                    }
                    return days;
                },

                togglePicker() { this.isOpen = !this.isOpen; },
                closePicker() { this.isOpen = false; },

                prevYear() { this.currentYear--; this.updateYearRange(); },
                nextYear() { this.currentYear++; this.updateYearRange(); },
                prevMonth() {
                    if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
                    else this.currentMonth--;
                },
                nextMonth() {
                    if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
                    else this.currentMonth++;
                },

                selectDate(day) {
                    this.selectedDate = { year: this.currentYear, month: this.currentMonth + 1, day: day };
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
                        const formatted = this.formatDate(this.selectedDate);
                        this.displayDate = formatted;
                        @this.set('date', formatted);
                        this.closePicker();
                    }
                }
            }));
        });

        // اسکرول به فرم هنگام ویرایش
        document.addEventListener('livewire:init', () => {
            Livewire.on('scroll-to-form', () => {
                const form = document.querySelector('.max-w-\\[380px\\]');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .scroll-container {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
            padding-bottom: 12px;
        }

        .scroll-container::-webkit-scrollbar {
            height: 5px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .max-h-\[680px\]::-webkit-scrollbar {
            width: 5px;
        }

        .max-h-\[680px\]::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 10px;
        }

        .max-h-\[680px\]::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .max-h-\[680px\]::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</div>