<div>
    <div class="flex flex-col pr-20 mx-auto">
        <div class="flex flex-col p-4 space-y-3">
            <h1 class="text-[25px] vazir">گزارش حساب و بیلانس</h1>
            <h1 class="text-[#8C8C8C]  pb-6">لیست تمام مشتریان و خزانه</h1>
        </div>

        {{-- Form --}}
        <div
            class="w-full max-w-[1465px]  bg-white  border border-[#D7E5EC] shadow-sm backdrop:blur-lg  rounded-[12px] p-6 mx-auto">
            <form wire:submit.prevent="loadTransactions" class="space-y-8">

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- ستون سمت راست -->
                    <div class="flex-1 flex flex-col space-y-6">

                        {{-- نمبر حساب --}}
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
                                        } else {
                                            // اگر چیزی اشتباه وارد شد، مقدار پاک شود
                                            this.selectedId = null;
                                            this.searchValue = '';
                                            $wire.set('selectedAccount', null);
                                        }
                                    },
                                
                                    updateDisplay() {
                                        const selected = this.customers.find(c => c.id === this.selectedId);
                                        this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                    }
                                }" x-init="updateDisplay();
                                $watch('selectedId', () => updateDisplay())" class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full h-[60px] p-3 rounded-[12px]  bg-[#EFF6F9] border focus:ring-2 focus:ring-blue-500 appearance-none"
                                        autocomplete="off">

                                    <datalist id="customersList" class="appearance-none">
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        </option>
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
                        </div>


                        {{-- نوع سند --}}
                        <div class="relative">
                            <label class="  block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع
                                سند</label>
                            <select wire:model="typeDocument"
                                class="w-full pr-4 h-[59px] rounded-[12px]  border  bg-[#EFF6F9] focus:ring-2 focus:ring-blue-400 appearance-none">
                                <option value="">همه اسناد</option>
                                <option value="خرید">خرید</option>
                                <option value="فروش">فروش</option>
                                <option value="انتقال">انتقال</option>
                                <option value="دریافت">دریافت</option>
                                <option value="پرداخت">پرداخت</option>
                            </select>
                            <div class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        {{-- نوع معامله --}}
                        <div class="relative">
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع حساب</label>
                            <select wire:model="accountType"
                                class="w-full pr-4 h-[59px] rounded-[12px]  border  bg-[#EFF6F9] focus:ring-2 focus:ring-blue-400 appearance-none">
                                <option value="">همه حساب ها</option>
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                            <div class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        {{-- توضیحات --}}
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">توضیحات</label>
                            <input type="text" wire:model="description"
                                class="w-full pr-4 h-[59px] rounded-[12px]  bg-[#EFF6F9] border  focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                placeholder="درج توضیحات">
                        </div>
                        {{-- نوع گزارش --}}
                        <div class="relative">
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع گزارش</label>
                            <select wire:model="typeTransaction2"
                                class="w-full pr-4 h-[59px] rounded-[12px]  bg-[#EFF6F9] border  focus:ring-2 focus:ring-blue-400 appearance-none">
                                <option value="">همه ترانزکشن‌ها</option>
                                <option value="رسید">رسید</option>
                                <option value="برد">برد</option>
                            </select>
                            <div class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                    </div>

                    <!-- ستون سمت چپ -->
                    <div class="flex-1 flex flex-col space-y-6">


                        {{-- واحد ارز --}}
                        <div class="relative w-full" x-data="{
                            open: false,
                            selectedCurrencies: @entangle('selectedCurrencies'),
                            currencyMap: @js(collect($currencies)->mapWithKeys(fn($c) => [$c['code'] => $c['name_fa']])->toArray())
                        }">
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                انتخاب واحد ارز برای گزارش
                            </label>

                            <!-- Container کلیک‌شدنی و نمایش انتخاب‌ها به جای placeholder -->
                            <div @click="open = !open"
                                class="flex flex-wrap gap-2 items-center min-h-[59px] border  bg-[#EFF6F9] rounded-[12px] p-2 cursor-pointer">
                                <template x-if="selectedCurrencies.length > 0">
                                    <template x-for="code in selectedCurrencies" :key="code">
                                        <span
                                            class="bg-blue-100 text-blue-800 px-2 py-1 rounded-md flex items-center gap-1">
                                            <span x-text="currencyMap[code] || code"></span>
                                            <button type="button"
                                                @click.stop="selectedCurrencies = selectedCurrencies.filter(c => c !== code)"
                                                class="text-blue-600 hover:text-red-600">×</button>
                                        </span>
                                    </template>
                                </template>
                                <template x-if="selectedCurrencies.length === 0">
                                    <span class="text-gray-400">انتخاب ارز...</span>
                                </template>
                            </div>

                            <!-- Dropdown چک‌باکس‌ها -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 max-h-60 overflow-y-auto rounded-md shadow-lg">
                                <div class="p-2 flex flex-wrap gap-2">
                                    @foreach ($currencies as $currency)
                                    <label
                                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 cursor-pointer border border-transparent hover:border-blue-200">
                                        <input type="checkbox" value="{{ $currency['code'] }}"
                                            x-model="selectedCurrencies"
                                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <span class="text-gray-700 font-medium">{{ $currency['name_fa'] }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- زون و توسط --}}
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full relative">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">زون</label>
                                <select wire:model="zone"
                                    class="w-full pr-4 h-[59px] rounded-[12px]  border  bg-[#EFF6F9] focus:ring-2 focus:ring-blue-400 appearance-none">
                                    <option value="">انتخاب زون</option>
                                    <option value="غرب">غرب (هرات، بادغیس، غور، فراه)</option>
                                    <option value="مرکز">مرکز (کابل، پروان، کاپیسا، وردک، لوگر)</option>
                                    <option value="شمال">شمال (بلخ، جوزجان، سرپل، سمنگان، فاریاب)</option>
                                    <option value="شمال‌شرق">شمال‌شرق (کندز، تخار، بدخشان، بغلان)</option>
                                    <option value="جنوب">جنوب (قندهار، ارزگان، زابل، هلمند)</option>
                                    <option value="جنوب‌شرق">جنوب‌شرق (خوست، پکتیا، پکتیکا)</option>
                                    <option value="شرق">شرق (ننگرهار، لغمان، کنر، نورستان)</option>
                                    <option value="جنوب‌غرب">جنوب‌غرب (نیمروز)</option>
                                </select>
                                <div class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">توسط</label>
                            <input type="text" wire:model="by"
                                class="w-full pr-4 h-[59px] rounded-[12px]  bg-[#EFF6F9] border  focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                placeholder="جستجو توسط">
                        </div>

                        <div>
                            <div class="lg:col-span-3 relative" x-data="fromDatePicker()" x-init="init()">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">از
                                    تاریخ</label>
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder="YYYY/MM/DD"
                                    class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2  bg-[#EFF6F9]  focus:ring-blue-500 cursor-pointer"
                                    readonly />

                                <!-- Date Picker Modal -->
                                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                    @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                    class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title"
                                    role="dialog" aria-modal="true" style="display: none;"
                                    :style="isOpen ? 'display: block;' : ''">

                                    <div
                                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                            aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                            aria-hidden="true">&#8203;</span>

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
                                                                    stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7">
                                                                </path>
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
                                                                    'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !==
                                                                        index
                                                                }"
                                                                class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
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
                                                                    'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !==
                                                                        year
                                                                }"
                                                                class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
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
                                                                    'bg-blue-500 text-white hover:bg-blue-600': day
                                                                        .isSelected,
                                                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                                        .isToday && !day.isSelected,
                                                                    'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                                        !day.isToday && !day.isSelected && !day
                                                                        .isOtherMonth,
                                                                    'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800': day
                                                                        .isOtherMonth,
                                                                    'cursor-not-allowed opacity-50': day.isDisabled
                                                                }"
                                                                class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-medium transition-colors"
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

                                @error('startDate')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <!-- تا تاریخ -->
                            <div class="lg:col-span-3 relative" x-data="toDatePicker()" x-init="init()">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تا
                                    تاریخ</label>
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder="YYYY/MM/DD"
                                    class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2  bg-[#EFF6F9]  focus:ring-blue-500 cursor-pointer"
                                    readonly />

                                <!-- Date Picker Modal -->
                                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                    @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                    class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title"
                                    role="dialog" aria-modal="true" style="display: none;"
                                    :style="isOpen ? 'display: block;' : ''">

                                    <div
                                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                            aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                            aria-hidden="true">&#8203;</span>

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
                                                                    stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7">
                                                                </path>
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
                                                                    'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !==
                                                                        index
                                                                }"
                                                                class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
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
                                                                    'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !==
                                                                        year
                                                                }"
                                                                class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
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
                                                                    'bg-blue-500 text-white hover:bg-blue-600': day
                                                                        .isSelected,
                                                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                                        .isToday && !day.isSelected,
                                                                    'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                                        !day.isToday && !day.isSelected && !day
                                                                        .isOtherMonth,
                                                                    'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800': day
                                                                        .isOtherMonth,
                                                                    'cursor-not-allowed opacity-50': day.isDisabled
                                                                }"
                                                                class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-medium transition-colors"
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

                                @error('endDate')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <!-- در بخش دکمه‌ها -->
                <div class="flex justify-center gap-4 pt-4">
                    <button type="submit"
                        class="bg-[#184D6C]  text-white text-[16px] font-medium rounded-[12px] w-full px-8 py-4 transition">
                        بروزرسانی گزارش
                    </button>

                    <button type="button" wire:click="print" wire:loading.attr="disabled"
                        class="bg-[#184D6C]  text-white text-[16px] font-medium rounded-[12px] w-full py-4 transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>چاپ گزارش</span>
                        <span wire:loading>
                            در حال تولید...
                        </span>
                    </button>


                </div>

            </form>
        </div>

        {{-- Report Table --}}
        <div
            class="w-full max-w-[1465px] bg-white  border border-[#D7E5EC] shadow-sm backdrop:blur-lg rounded-[12px] mt-10 p-6 mx-auto">

            <div class="flex justify-between items-center mb-4">
                <div class="relative w-[302px]">
                    <input type="text" wire:model.live="search" placeholder="جستجو..." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[12px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">

                    {{-- آیکون --}}
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                        <path d="M20 20L22 22" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    </svg>

                </div>

                @if ($selectedCustomer)
                <div class="text-right">
                    <h3 class="text-lg font-bold">{{ $selectedCustomerName }}</h3>
                    <p class="text-sm text-gray-600">تعداد تراکنش‌ها: {{ count($transactions) }}</p>
                </div>
                @endif
            </div>

            <table class="w-full text-sm md:text-base text-left mt-6 rtl:text-right text-gray-500 dark:text-gray-400">
                <thead
                    class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                    <tr class="whitespace-nowrap">
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-12 md:w-16" rowspan="2">#</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-48" rowspan="2">تاریخ</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-48" rowspan="2">حساب</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">نمبر سند</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-32 md:w-40" rowspan="2">توضیحات</th>
                        <th class="px-2 md:px-8 py-3 md:py-4 font-bold w-24 md:w-32" rowspan="2">توسط</th>

                        <!-- نمایش داینامیک ارزها بر اساس مشتری -->
                        @foreach ($active_currencies as $code => $currency)
                        @php
                        $currencyName = is_array($currency) ? $currency['name_fa'] : $currency;
                        $colspan = 2;
                        @endphp
                        <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-center" colspan="{{ $colspan }}">
                            {{ $currencyName }}
                        </th>
                        @endforeach

                    </tr>
                    <!-- سطر دوم -->
                    <tr>
                        <!-- نمایش ستون‌های رسید و برد برای هر ارز -->
                        @foreach ($active_currencies as $code => $currency)
                        <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">رسید
                        </th>
                        <th class="px-2 md:px-3 py-2 font-semibold text-center min-w-[70px] md:min-w-[80px]">برد
                        </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="text-[14px] md:text-[15px] text-gray-800">
                    @if (count($transactions) > 0)
                    @foreach ($transactions as $index => $transaction)
                    <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">
                        <td class="px-2 md:px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="px-2 md:px-4 py-3">
                            <div class="flex flex-col">
                                <span>
                                    {{ explode(' ', $transaction->date)[0] }}
                                </span>
                            </div>
                        </td>
                        <td class="px-2 md:px-4 py-3">{{ $transaction->account_type }}</td>
                        <td class="px-2 md:px-4 py-3">
                            {{ $transaction->document_number ?? 'SN-' . str_pad($transaction->id, 3, '0', STR_PAD_LEFT)
                            }}
                        </td>
                        <td class="px-2 md:px-4 py-3">{{ $transaction->description }}</td>
                        <td class="px-2 md:px-4 py-3">{{ $transaction->by }}</td>

                        <!-- نمایش داینامیک مقادیر برای هر ارز -->
                        @foreach ($active_currencies as $code => $currency)
                        <td class="px-2 md:px-3 py-3 text-center">
                            {{ $transaction->currency == $code && $transaction->type == 'رسید' ?
                            number_format($transaction->amount) : '-' }}
                        </td>
                        <td class="px-2 md:px-3 py-3 text-center">
                            {{ $transaction->currency == $code && $transaction->type == 'برد' ?
                            number_format($transaction->amount) : '-' }}
                        </td>
                        @endforeach


                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="{{ 5 + count($active_currencies) * 2 + 1 }}"
                            class="px-4 py-8 text-center text-gray-500">
                            @if ($selectedCustomer)
                            هیچ تراکنشی با فیلترهای انتخاب شده یافت نشد
                            @else
                            لطفاً ابتدا یک مشتری را انتخاب کنید
                            @endif
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>

        </div>

      {{-- General Table --}}
<div class="w-full max-w-[1465px] bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC] rounded-[12px] mt-10 p-6 mx-auto">
    <div class="flex justify-between items-center text-center mx-auto mb-6">
        <h1 class="text-xl font-bold">مجموعه کل</h1>
        <button type="button" wire:click="printSummary"
            class="w-[31px] h-[29.232500076293945px] rounded-[8px] bg-transparent border border-[#000000] pr-1 hover:bg-gray-100 transition-colors cursor-pointer">
            <img src="{{ asset('assets/sarafi/all_icon/printer.svg') }}" alt="چاپ خلاصه"
                class="w-[21.0575008392334px] h-[19.232500076293945px]">
        </button>
    </div>

    <div class="overflow-x-auto w-full mt-6">
        <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                <tr class="whitespace-nowrap">
                    <th class="px-3 py-3 font-bold text-center" style="width: 5%;">#</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 15%;">واحد پول</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 15%;">موجودی قبلی</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 15%;">رسید</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 15%;">برد</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 15%;">بیلانس دوره</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 15%;">موجودی فعلی</th>
                    <th class="px-3 py-3 font-bold text-center" style="width: 20%;">وضعیت</th>
                </tr>
            </thead>

            <tbody class="text-[16px] md:text-[18px] text-gray-800 bg-white">
                @if (count($balances) > 0)
                    @php
                        $totalPrevious = 0;
                        $totalReceived = 0;
                        $totalSpent = 0;
                        $totalBalance = 0;
                        $totalCurrent = 0;
                    @endphp
                    
                    @foreach ($balances as $index => $balance)
                        @php
                            $totalPrevious += $balance['previous_balance'];
                            $totalReceived += $balance['received'];
                            $totalSpent += $balance['spent'];
                            $totalBalance += $balance['balance'];
                            $totalCurrent += $balance['current_balance'];
                        @endphp
                        <tr class="text-black border-b dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">
                            <td class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ $balance['name_fa'] }}
                            </td>
                            <td dir="ltr" class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ number_format($balance['previous_balance']) }}
                            </td>
                            <td dir="ltr" class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ number_format($balance['received']) }}
                            </td>
                            <td dir="ltr" class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ number_format($balance['spent']) }}
                            </td>
                            <td dir="ltr" class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ number_format($balance['balance']) }}
                            </td>
                            <td dir="ltr" class="px-3 py-4 vazir font-medium text-center align-middle">
                                {{ number_format($balance['current_balance']) }}
                            </td>
                            <td class="px-3 py-4 text-center align-middle">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $balance['status'] == 'طلبکار' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    {{ $balance['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    
                
                @else
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 text-[16px]">
                            هیچ موجودی فعالی وجود ندارد
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
    </div>
    <script>
        // جستجوی ساده در جدول
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchTable');
                if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });

    function createPersianDatePicker(fieldName = 'date') {
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

            monthsAfghan: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'],
            weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
            daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],

          init() {
    this.updateYearRange();
    const today = this.getTodayPersian();
    this.currentYear = today.year;
    this.currentMonth = today.month - 1;
    
    const livewireValue = @this.get(fieldName);
    if (!livewireValue) {
        // اگر مقدار Livewire خالی است، تاریخ انتخاب نشده
        this.selectedDate = null; // این خط اضافه شود
        this.displayDate = ''; // نمایش خالی
    } else {
        // تبدیل تاریخ از Y-m-d به Y/m/d برای نمایش
        const dateParts = livewireValue.split('-');
        if (dateParts.length === 3) {
            const year = parseInt(dateParts[0]);
            const month = parseInt(dateParts[1]);
            const day = parseInt(dateParts[2]);
            
            if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
                this.selectedDate = {
                    year,
                    month,
                    day
                };
                this.displayDate = 
                    `${year}/${String(month).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                this.currentYear = year;
                this.currentMonth = month - 1;
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
                const remainders = [1, 5, 9, 13, 17, 22, 26, 30];
                return remainders.includes(year % 33);
            },

            getDaysInMonth(year, month) {
                const days = [...this.daysInMonthNormal];
                if (month === 11 && this.isLeapYear(year)) return 30;
                return days[month];
            },

            getFirstDayOfWeek(year, month) {
                const baseYear = 1403;
                const baseDay = 4;
                let days = 0;

                for (let y = baseYear; y < year; y++) {
                    days += this.isLeapYear(y) ? 366 : 365;
                }

                for (let m = 0; m < month; m++) {
                    days += this.getDaysInMonth(year, m);
                }

                return (baseDay + days) % 7;
            },

            getTodayPersian() {
                const today = new Date();

                const persianDate = this.gregorianToPersian(
                    today.getFullYear(),
                    today.getMonth() + 1,
                    today.getDate()
                );

                return persianDate;
            },

            gregorianToPersian(gy, gm, gd) {
                const gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                const isGregorianLeap = (gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0);

                if (isGregorianLeap) gDaysInMonth[1] = 29;

                let dayOfYear = gd;
                for (let i = 0; i < gm - 1; i++) dayOfYear += gDaysInMonth[i];

                const marchDay = 79;
                let persianYear, persianMonth, persianDay;

                if (dayOfYear > marchDay) {
                    persianYear = gy - 621;
                    let remainingDays = dayOfYear - marchDay;
                    const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                    if (this.isLeapYear(persianYear)) pDaysInMonth[11] = 30;

                    for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                        if (remainingDays <= pDaysInMonth[persianMonth]) {
                            persianDay = remainingDays;
                            break;
                        }
                        remainingDays -= pDaysInMonth[persianMonth];
                    }
                    persianMonth++;
                } else {
                    persianYear = gy - 622;
                    let remainingDays = dayOfYear + 286;
                    const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                    if (this.isLeapYear(persianYear)) pDaysInMonth[11] = 30;

                    for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                        if (remainingDays <= pDaysInMonth[persianMonth]) {
                            persianDay = remainingDays;
                            break;
                        }
                        remainingDays -= pDaysInMonth[persianMonth];
                    }
                    persianMonth++;
                }

                return {
                    year: persianYear,
                    month: persianMonth,
                    day: persianDay
                };
            },

            get calendarDays() {
                const days = [];
                const daysInMonth = this.getDaysInMonth(this.currentYear, this.currentMonth);
                const firstDayOfWeek = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);
                const today = this.getTodayPersian();
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

                const remainingCells = 42 - days.length;
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
                // نمایش به فرمت Y/m/d
                this.displayDate =
                    `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
            },

            formatDate(date) {
                if (!date) return '';
                // ذخیره به فرمت Y-m-d (مشابه دیتابیس)
                return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`;
            },

            setToday() {
                const today = this.getTodayPersian();
                this.currentYear = today.year;
                this.currentMonth = today.month - 1;
                this.selectedDate = today;
                // نمایش به فرمت Y/m/d
                this.displayDate =
                    `${today.year}/${String(today.month).padStart(2, '0')}/${String(today.day).padStart(2, '0')}`;

                @this.set(fieldName, this.formatDate(today));
            },

            clearDate() {
                this.selectedDate = null;
                this.displayDate = '';
                @this.set(fieldName, '');
                this.closePicker();
            },

           applyDate() {
    if (this.selectedDate) {
        const formattedDate = this.formatDate(this.selectedDate);
        console.log('Date selected:', formattedDate);
        
        // برای startDate
        if (fieldName === 'startDate') {
            @this.setStartDate(formattedDate);
        } 
        // برای endDate
        else if (fieldName === 'endDate') {
            @this.setEndDate(formattedDate);
        }
        this.closePicker();
    } else {
        this.setToday();
    }
}
        };
    }

    function fromDatePicker() {
        return createPersianDatePicker('startDate');
    }

    function toDatePicker() {
        return createPersianDatePicker('endDate');
    }

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
                }, 50000);
            };
        });
    });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .transition-transform {
            transition: transform 0.2s ease;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .persian-datepicker {
            font-family: 'Vazir', sans-serif;
            direction: rtl;
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

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