@php
if (!function_exists('getPersianCurrencyName')) {
function getPersianCurrencyName($currencyCode) {
$map = [
'AFN' => 'افغانی',
'USD' => 'دالر',
'EUR' => 'یورو',
'IRR' => 'تومان',
'PKR' => 'کلدار',
'AED' => 'درهم',
'TRY' => 'لیره',
'CNY' => 'یوان',
'GBP' => 'پوند',
'JPY' => 'ین',
'SAR' => 'ریال سعودی',
'INR' => 'روپیه',
];
return $map[$currencyCode] ?? $currencyCode;
}
}
@endphp

<div>
    <div class="container mx-auto">

        <!-- پیام‌های سیستم -->
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">{{ session('message') }}</h2>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-500 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">{{ session('error') }}</h2>
            </div>
        </div>
        @endif

        <div class="w-full">
            <div class="bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC]
                    dark:bg-black dark:border dark:border-white p-6 rounded-[12px] mx-auto">

                <div
                    class="flex flex-col md:flex-row justify-between items-center p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-[22px] inter">گزارشات پرسونل</h1>

                </div>

                <!-- دکمه‌ها و فیلترها -->
                <div class="grid grid-cols-1 md:grid-cols-8 lg:grid-cols-12 gap-4 items-end">

                    <!-- دکمه پرینت -->
                    <div class="lg:col-span-1">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">&nbsp;</label>
                        <button wire:click="printReport" wire:loading.attr='disabled' wire:target='printReport'
                            class="w-full flex items-center justify-center gap-2 bg-[#184D6C] text-white px-6 py-3 rounded-xl transition h-12">
                            <span wire:loading.remove wire:target='printReport'> چاپ</span>
                            <span wire:loading wire:target='printReport'>در حال چاپ.....</span>
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none">
                                <path
                                    d="M10.7714 25C10.2156 25 9.74016 24.802 9.34516 24.4062C8.95016 24.0104 8.75224 23.5358 8.75141 22.9825V20H6.49141C5.93641 20 5.46141 19.802 5.06641 19.4062C4.67141 19.0104 4.47349 18.5354 4.47266 17.9812V13.2687C4.47266 12.5604 4.71307 11.967 5.19391 11.4887C5.67474 11.0087 6.26766 10.7687 6.97266 10.7687H23.0302C23.7385 10.7687 24.3322 11.0087 24.8114 11.4887C25.2906 11.9687 25.5302 12.562 25.5302 13.2687V17.9812C25.5302 18.5362 25.3327 19.0112 24.9377 19.4062C24.5427 19.8012 24.0672 19.9991 23.5114 20H21.2514V22.9812C21.2514 23.5362 21.0535 24.0112 20.6577 24.4062C20.2618 24.8012 19.7868 24.9991 19.2327 25H10.7714ZM6.49141 18.75H8.75141C8.78391 18.2225 8.99307 17.77 9.37891 17.3925C9.76474 17.0158 10.2289 16.8275 10.7714 16.8275H19.2327C19.7743 16.8275 20.2381 17.0162 20.6239 17.3937C21.0097 17.7704 21.2189 18.2225 21.2514 18.75H23.5114C23.7356 18.75 23.9197 18.6779 24.0639 18.5337C24.2081 18.3895 24.2802 18.2054 24.2802 17.9812V13.2687C24.2802 12.9154 24.1606 12.6187 23.9214 12.3787C23.6822 12.1387 23.3852 12.0187 23.0302 12.0187H6.97266C6.61849 12.0187 6.32182 12.1387 6.08266 12.3787C5.84349 12.6187 5.72349 12.9158 5.72266 13.27V17.9812C5.72266 18.2054 5.79474 18.3895 5.93891 18.5337C6.08307 18.6779 6.26724 18.75 6.49141 18.75ZM20.0014 10.77V7.78746C20.0014 7.56246 19.9293 7.37829 19.7852 7.23496C19.641 7.09079 19.4568 7.01871 19.2327 7.01871H10.7702C10.546 7.01871 10.3618 7.09079 10.2177 7.23496C10.0735 7.37912 10.0014 7.56329 10.0014 7.78746V10.7687H8.75141V7.78746C8.75141 7.23246 8.94932 6.75704 9.34516 6.36121C9.74016 5.96537 10.2152 5.76746 10.7702 5.76746H19.2327C19.7877 5.76746 20.2627 5.96537 20.6577 6.36121C21.0535 6.75704 21.2514 7.23204 21.2514 7.78621V10.7687L20.0014 10.77ZM22.0214 15.145C22.3756 15.145 22.6722 15.025 22.9114 14.785C23.1506 14.545 23.2706 14.2483 23.2714 13.895C23.2722 13.5416 23.1522 13.2445 22.9114 13.0037C22.6706 12.7629 22.3739 12.6429 22.0214 12.6437C21.6689 12.6445 21.3718 12.7645 21.1302 13.0037C20.8885 13.2429 20.7689 13.54 20.7714 13.895C20.7739 14.25 20.8935 14.5466 21.1302 14.785C21.3668 15.0233 21.6639 15.1433 22.0214 15.145ZM20.0014 22.98V18.8462C20.0014 18.6212 19.9293 18.4366 19.7852 18.2925C19.641 18.1483 19.4568 18.0762 19.2327 18.0762H10.7702C10.546 18.0762 10.3618 18.1483 10.2177 18.2925C10.0735 18.4375 10.0014 18.622 10.0014 18.8462V22.9812C10.0014 23.2054 10.0735 23.3895 10.2177 23.5337C10.3618 23.6779 10.5464 23.75 10.7714 23.75H19.2327C19.4568 23.75 19.641 23.6779 19.7852 23.5337C19.9293 23.3895 20.0014 23.205 20.0014 22.98ZM6.49141 12.02H5.72266H24.2802H6.49141Z"
                                    fill="white" />
                            </svg>
                        </button>
                    </div>

                    <!-- دکمه بروزرسانی -->
                    <div class="lg:col-span-1">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">&nbsp;</label>
                        <button wire:click="refreshReport"
                            class="w-full flex items-center justify-center gap-2 bg-[#184D6C] text-white px-4 py-3 rounded-xl transition h-12">
                            <span>بروزرسانی</span>
                            <svg width="24" height="24" viewBox="0 0 30 30" fill="none">
                                <path
                                    d="M18.1875 27.0875C23.55 25.675 27.5 20.8 27.5 15C27.5 8.1 21.95 2.5 15 2.5C6.6625 2.5 2.5 9.45 2.5 9.45M2.5 9.45V3.75M2.5 9.45H5.0125H8.05"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2.5 15C2.5 21.9 8.1 27.5 15 27.5" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="3 3" />
                            </svg>
                        </button>
                    </div>

                    <!-- دکمه بازنشانی -->
                    <div class="lg:col-span-1">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">&nbsp;</label>
                        <button wire:click="resetFilters"
                            class="w-full flex items-center justify-center gap-2 bg-[#184D6C] text-white px-4 py-3 rounded-xl transition h-12">
                            <span>بازنشانی</span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M6 18L18 6M6 6l12 12" stroke="white" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>


                    <!-- انتخاب ارز -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">ارز</label>
                        <div class="relative">
                            <select wire:model.live="filterCurrency"
                                class="  w-full dark:bg-black dark:border-white dark:text-white border border-[#8C8C8C] bg-transparent rounded-xl py-2 p-4 appearance-none h-12 focus:ring-2 focus:ring-[#184D6C] focus:outline-none vazir">
                                <option value="">همه ارزها</option>
                                @foreach($currencies as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <!-- نوع تراکنش -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">نوع
                            تراکنش</label>
                        <div class="relative">
                            <select wire:model.live="filterTransactionType"
                                class="appearance-none w-full dark:bg-black dark:border-white dark:text-white border border-[#8C8C8C] bg-transparent rounded-xl py-2 p-4 h-12 focus:ring-2 focus:ring-[#184D6C] focus:outline-none vazir">
                                <option value="all">همه تراکنش‌ها</option>
                                <option value="withdrawal">برداشت‌ها</option>
                                <option value="salary">معاش‌ها</option>
                            </select>

                        </div>
                    </div>

                    <!-- از تاریخ -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">از
                            تاریخ</label>
                        <div x-data="persianDatePicker('startDate')" x-init="init()">
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="روز/ماه/سال"
                                class="w-full dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 h-12 px-4 rounded-xl border focus:ring-2 focus:ring-[#184D6C] bg-white dark:bg-gray-700 cursor-pointer"
                                readonly />

                            <!-- مودال کامل datepicker -->
                            <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                class="fixed z-50 inset-0 overflow-y-auto" style="display: none;"
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
                                            <!-- هدر -->
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center space-x-2">
                                                    <button @click="prevYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                                        </svg>
                                                    </button>
                                                    <button @click="prevMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7" />
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
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                    <button @click="nextYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                    <button @click="closePicker()" type="button"
                                                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- ماه‌ها -->
                                            <div x-show="showMonthSelector" x-transition>
                                                <div class="grid grid-cols-3 gap-2 mb-4">
                                                    <template x-for="(month, index) in monthsAfghan" :key="index">
                                                        <button @click="selectMonth(index)"
                                                            :class="{'bg-blue-500 text-white': currentMonth === index, 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !== index}"
                                                            class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="month"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- سال‌ها -->
                                            <div x-show="showYearSelector" x-transition>
                                                <div class="flex items-center justify-between mb-4">
                                                    <button @click="prevYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7" />
                                                        </svg>
                                                    </button>
                                                    <span class="text-lg font-bold text-gray-800 dark:text-white"><span
                                                            x-text="yearRange.start"></span> - <span
                                                            x-text="yearRange.end"></span></span>
                                                    <button @click="nextYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-4 gap-2 mb-4">
                                                    <template x-for="year in yearRange.years" :key="year">
                                                        <button @click="selectYear(year)"
                                                            :class="{'bg-blue-500 text-white': currentYear === year, 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !== year}"
                                                            class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="year"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- روزها -->
                                            <div x-show="!showMonthSelector && !showYearSelector" x-transition>
                                                <div class="grid grid-cols-7 gap-1 mb-2">
                                                    <template x-for="day in weekDaysAfghan" :key="day">
                                                        <div
                                                            class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 py-1">
                                                            <span x-text="day"></span>
                                                        </div>
                                                    </template>
                                                </div>
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

                                            <!-- فوتر -->
                                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-between items-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300"><span
                                                            x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"></span>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button @click="setToday()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">امروز</button>
                                                        <button @click="clearDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">پاک
                                                            کردن</button>
                                                        <button @click="applyDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">تأیید</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تا تاریخ -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 vazir">تا
                            تاریخ</label>
                        <div x-data="persianDatePicker('endDate')" x-init="init()">
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="روز/ماه/سال"
                                class="w-full dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 h-12 px-4 rounded-xl border focus:ring-2 focus:ring-[#184D6C] bg-white dark:bg-gray-700 cursor-pointer"
                                readonly />

                            <!-- مودال کامل datepicker (کد مشابه بالا) -->
                            <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                class="fixed z-50 inset-0 overflow-y-auto" style="display: none;"
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
                                            <!-- همان کد هدر، ماه‌ها، سال‌ها، روزها و فوتر -->
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center space-x-2">
                                                    <button @click="prevYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                                        </svg>
                                                    </button>
                                                    <button @click="prevMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7" />
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
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                    <button @click="nextYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                    <button @click="closePicker()" type="button"
                                                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- ماه‌ها -->
                                            <div x-show="showMonthSelector" x-transition>
                                                <div class="grid grid-cols-3 gap-2 mb-4">
                                                    <template x-for="(month, index) in monthsAfghan" :key="index">
                                                        <button @click="selectMonth(index)"
                                                            :class="{'bg-blue-500 text-white': currentMonth === index, 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !== index}"
                                                            class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="month"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                            <!-- سال‌ها -->
                                            <div x-show="showYearSelector" x-transition>
                                                <div class="flex items-center justify-between mb-4">
                                                    <button @click="prevYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7" />
                                                        </svg>
                                                    </button>
                                                    <span class="text-lg font-bold text-gray-800 dark:text-white"><span
                                                            x-text="yearRange.start"></span> - <span
                                                            x-text="yearRange.end"></span></span>
                                                    <button @click="nextYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-4 gap-2 mb-4">
                                                    <template x-for="year in yearRange.years" :key="year">
                                                        <button @click="selectYear(year)"
                                                            :class="{'bg-blue-500 text-white': currentYear === year, 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !== year}"
                                                            class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="year"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                            <!-- روزها -->
                                            <div x-show="!showMonthSelector && !showYearSelector" x-transition>
                                                <div class="grid grid-cols-7 gap-1 mb-2">
                                                    <template x-for="day in weekDaysAfghan" :key="day">
                                                        <div
                                                            class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 py-1">
                                                            <span x-text="day"></span>
                                                        </div>
                                                    </template>
                                                </div>
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
                                            <!-- فوتر -->
                                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-between items-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300"><span
                                                            x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"></span>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button @click="setToday()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">امروز</button>
                                                        <button @click="clearDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">پاک
                                                            کردن</button>
                                                        <button @click="applyDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">تأیید</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-[250px] md:w-[350]">
                        <svg width="24" height="24"
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                            viewBox="0 0 24 24" fill="none">
                            <path
                                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <input type="text" wire:model.live="search" placeholder="جستجوی پرسونل..." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[12px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                            <path d="M20 20L22 22" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                                stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>

                </div>

                <!-- ===== جدول اول: خلاصه برداشت‌ها و حقوق‌ها ===== -->
                <div class="overflow-x-auto w-full mt-4">
                    <div class="max-h-[600px] overflow-y-auto">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-700 border-collapse">
                            <thead class="sticky top-0 z-10">
                                <tr class="bg-gradient-to-r from-[#1e3c5c] to-[#2b4f72] text-white">
                                    <th rowspan="2"
                                        class="px-3 py-3 font-bold border border-gray-300 text-center align-middle w-16">
                                        <span class="border border-white/30 px-2 py-1 rounded-lg">#</span>
                                    </th>
                                    <th rowspan="2"
                                        class="px-3 py-3 font-bold border border-gray-300 text-center align-middle">نام
                                        پرسونل</th>
                                    @foreach($currencies as $code => $name)
                                    <th colspan="2" class="px-2 py-3 font-bold border border-gray-300 text-center"
                                        style="background-color: #34495e;">{{ $name }}</th>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-200 text-gray-800">
                                    @foreach($currencies as $code => $name)
                                    <th class="px-2 py-2 font-bold border border-gray-300 text-center text-sm">برداشت
                                    </th>
                                    <th class="px-2 py-2 font-bold border border-gray-300 text-center text-sm">کل معاشات گرفته شده</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $index => $report)
                                @php $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray-50'; @endphp
                                <tr
                                    class="{{ $rowClass }} hover:bg-gray-100 transition-colors duration-150 border-b border-gray-200">
                                    <td class="px-3 py-3 text-center font-mono border-l border-gray-200"><span
                                            class="bg-gray-100 px-2 py-1 rounded-md text-gray-700">{{ $index + 1
                                            }}</span></td>
                                    <td class="px-3 py-3 font-medium text-gray-800 border-l border-gray-200">{{
                                        $report['fullname'] }}</td>
                                    @foreach($currencies as $code => $name)
                                    @php
                                    $with = $report['withdrawals'][$code] ?? 0;
                                    $sal = $report['salaries'][$code] ?? 0;
                                    $withClass = $with < 0 ? 'text-red-600' : ($with> 0 ? 'text-green-600' :
                                        'text-gray-500');
                                        $salClass = $sal < 0 ? 'text-red-600' : ($sal> 0 ? 'text-green-600' :
                                            'text-gray-500');
                                            @endphp
                                            <td class="px-2 py-3 text-left font-mono {{ $withClass }} border-l border-gray-200"
                                                dir="ltr">{{ number_format($with, 2) }}</td>
                                            <td class="px-2 py-3 text-left font-mono {{ $salClass }} {{ !$loop->last ? 'border-l' : '' }} border-gray-200"
                                                dir="ltr">{{ number_format($sal, 2) }}</td>
                                            @endforeach
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ 2 + (count($currencies) * 2) }}"
                                        class="px-4 py-12 text-center text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="text-lg">داده‌ای یافت نشد</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- جمع کل برداشت‌ها -->
                    <div class="mt-6 border-t-2 border-[#184D6C] pt-4">
                        <h3 class="text-lg font-bold mb-3 text-[#184D6C] dark:text-white">جمع کل برداشت‌ها</h3>
                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm md:text-base text-left rtl:text-right text-gray-700 border-collapse bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#1e3c5c] to-[#2b4f72] text-white">
                                        <th class="px-3 py-3 font-bold border border-gray-300 text-center w-16">#</th>
                                        <th class="px-3 py-3 font-bold border border-gray-300 text-center">نوع</th>
                                        @foreach($currencies as $code => $name)
                                        <th class="px-2 py-3 font-bold border border-gray-300 text-center"
                                            style="background-color: #34495e;">{{ $name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-blue-50 dark:bg-blue-900/20 border-b border-gray-300">
                                        <td class="px-3 py-3 text-center font-bold border-l border-gray-300">۱</td>
                                        <td
                                            class="px-3 py-3 font-bold text-gray-800 dark:text-white border-l border-gray-300">
                                            <span
                                                class="bg-blue-100 dark:bg-blue-800 px-3 py-1 rounded-full text-blue-800 dark:text-blue-200">کل
                                                برداشت‌ها</span>
                                        </td>
                                        @foreach($currencies as $code => $name)
                                        @php $amt = $totalWithdrawals[$code] ?? 0; $class = $amt < 0 ? 'text-red-600' :
                                            ($amt> 0 ? 'text-green-600' : 'text-gray-500'); @endphp
                                            <td class="px-2 py-3 text-left font-mono font-bold {{ $class }} border-l border-gray-300"
                                                dir="ltr">{{ number_format($amt, 2) }}</td>
                                            @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- جمع کل حقوق‌ها -->
                    <div class="mt-6 border-t-2 border-[#184D6C] pt-4">
                        <h3 class="text-lg font-bold mb-3 text-[#184D6C] dark:text-white">جمع کل معاشات</h3>
                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm md:text-base text-left rtl:text-right text-gray-700 border-collapse bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#1e3c5c] to-[#2b4f72] text-white">
                                        <th class="px-3 py-3 font-bold border border-gray-300 text-center w-16">#</th>
                                        <th class="px-3 py-3 font-bold border border-gray-300 text-center">نوع</th>
                                        @foreach($currencies as $code => $name)
                                        <th class="px-2 py-3 font-bold border border-gray-300 text-center"
                                            style="background-color: #34495e;">{{ $name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-green-50 dark:bg-green-900/20 border-b border-gray-300">
                                        <td class="px-3 py-3 text-center font-bold border-l border-gray-300">۱</td>
                                        <td
                                            class="px-3 py-3 font-bold text-gray-800 dark:text-white border-l border-gray-300">
                                            <span
                                                class="bg-green-100 dark:bg-green-800 px-3 py-1 rounded-full text-green-800 dark:text-green-200">کل
                                                معاشات</span>
                                        </td>
                                        @foreach($currencies as $code => $name)
                                        @php $amt = $totalSalaries[$code] ?? 0; $class = $amt < 0 ? 'text-red-600' :
                                            ($amt> 0 ? 'text-green-600' : 'text-gray-500'); @endphp
                                            <td class="px-2 py-3 text-left font-mono font-bold {{ $class }} border-l border-gray-300"
                                                dir="ltr">{{ number_format($amt, 2) }}</td>
                                            @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ===== جدول دوم: جزئیات تمام تراکنش‌ها ===== -->
                <div class="mt-8 border-t-2 border-[#184D6C] pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <label class="text-xl font-medium text-gray-700 dark:text-gray-300 vazir">تعداد
                                نمایش:</label>
                            <select wire:model.live="perPage"
                                class="border border-gray-300 appearance-none dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">همه</option>
                            </select>
                        </div>
                        <!-- در صورت نیاز، می‌توانید اطلاعات تعداد کل را هم نمایش دهید -->
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $transactions->total() }} رکورد
                        </span>
                    </div>
                    <h3 class="text-lg font-bold mb-3 text-[#184D6C] dark:text-white">لیست تمام تراکنش‌ها (برداشت‌ها و
                        معاشات)</h3>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-700 border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-[#1e3c5c] to-[#2b4f72] text-white">
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">#</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">نام پرسونل</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">نوع ترانزکشن</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">نوع برداشت</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">مبلغ</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">ارز</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">تاریخ</th>
                                    <th class="px-3 py-3 font-bold border border-gray-300 text-center">توضیحات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $tx)
                                @php
                                $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray-50';
                                $isSalary = $tx['transaction_type'] === 'salary';
                                $typeClass = $isSalary ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
                                $amountClass = $tx['amount'] < 0 ? 'text-red-600' : 'text-green-600' ; @endphp <tr
                                    class="{{ $rowClass }} hover:bg-gray-100 transition-colors duration-150 border-b border-gray-200">
                                    <td class="px-3 py-3 text-center font-mono border-l border-gray-200">{{
                                        ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                                    </td>

                                    <td class="px-3 py-3 font-medium text-gray-800 border-l border-gray-200">{{
                                        $tx['staff_name'] }}</td>
                                         <td  class="px-3 py-3 font-medium text-gray-800 border-l border-gray-200">
        {{ $tx['transaction_type'] === 'withdrawal' ? 'برداشت' : 'معاش' }}
    </td>
                                    <td class="px-3 py-3 text-center border-l border-gray-200">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $typeClass }}">{{
                                            $tx['type'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-left font-mono {{ $amountClass }} border-l border-gray-200"
                                        dir="ltr">{{ number_format($tx['amount'], 2) }}</td>
                                    <td class="px-3 py-3 text-center border-l border-gray-200">{{
                                        getPersianCurrencyName($tx['currency']) }}</td>
                                    <td class="px-3 py-3 text-center border-l border-gray-200 text-xs">{{ $tx['date_fa']
                                        }}</td>
                                    <td class="px-3 py-3 max-w-xs  border-l border-gray-200">{{
                                        $tx['description'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="text-lg">هیچ تراکنشی یافت نشد</span>
                                            </div>
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

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-content,
            #print-content * {
                visibility: visible;
            }

            #print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
        }

        select::-ms-expand {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('livewire:init', function () {
        Livewire.on('print-pdf', (data) => {
            if (data.url) {
                const link = document.createElement('a');
                link.href = data.url;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    });
    </script>
</div>

<script>
    function persianDatePicker(fieldName = 'date') {
        return {
            isOpen: false,
            showMonthSelector: false,
            showYearSelector: false,
            displayDate: '',
            currentYear: 1403,
            currentMonth: 0,
            selectedDate: null,
            yearRange: { start: 1400, end: 1410, years: [] },
            monthsAfghan: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'],
            weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
            daysInMonthNormal: [31,31,31,31,31,31,30,30,30,30,30,29],

            init() {
                this.updateYearRange();
                const today = this.getTodayPersian();
                this.currentYear = today.year;
                this.currentMonth = today.month - 1;
                const val = @this.get(fieldName);
                if (val) {
                    const parts = val.split('/');
                    if (parts.length === 3) {
                        const y = parseInt(parts[0]), m = parseInt(parts[1]), d = parseInt(parts[2]);
                        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                            this.selectedDate = { year: y, month: m, day: d };
                            this.displayDate = `${y}/${String(m).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
                            this.currentYear = y;
                            this.currentMonth = m - 1;
                        }
                    }
                }
            },

            updateYearRange() {
                this.yearRange.years = [];
                for (let y = this.yearRange.start; y <= this.yearRange.end; y++) {
                    this.yearRange.years.push(y);
                }
            },

            isLeapYear(year) {
                return [1,5,9,13,17,22,26,30].includes(year % 33);
            },

            getDaysInMonth(year, month) {
                const days = [...this.daysInMonthNormal];
                if (month === 11 && this.isLeapYear(year)) return 30;
                return days[month];
            },

            getFirstDayOfWeek(year, month) {
                const baseYear = 1403, baseDay = 4;
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
                return this.gregorianToPersian(today.getFullYear(), today.getMonth() + 1, today.getDate());
            },

            gregorianToPersian(gy, gm, gd) {
                const gDays = [31,28,31,30,31,30,31,31,30,31,30,31];
                const leap = (gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0);
                if (leap) gDays[1] = 29;
                let doy = gd;
                for (let i = 0; i < gm - 1; i++) doy += gDays[i];
                const marchDay = 79;
                let py, pm, pd;
                if (doy > marchDay) {
                    py = gy - 621;
                    let rem = doy - marchDay;
                    const pDays = [31,31,31,31,31,31,30,30,30,30,30,29];
                    if (this.isLeapYear(py)) pDays[11] = 30;
                    for (pm = 0; pm < 12; pm++) {
                        if (rem <= pDays[pm]) { pd = rem; break; }
                        rem -= pDays[pm];
                    }
                    pm++;
                } else {
                    py = gy - 622;
                    let rem = doy + 286;
                    const pDays = [31,31,31,31,31,31,30,30,30,30,30,29];
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
                const dim = this.getDaysInMonth(this.currentYear, this.currentMonth);
                const fdow = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);
                const today = this.getTodayPersian();
                const prevDim = this.currentMonth === 0 ? this.getDaysInMonth(this.currentYear-1, 11) : this.getDaysInMonth(this.currentYear, this.currentMonth-1);

                for (let i = 0; i < fdow; i++) {
                    days.push({ key: `prev-${prevDim-fdow+i+1}`, day: prevDim-fdow+i+1, isSelected: false, isToday: false, isOtherMonth: true, isDisabled: true });
                }
                for (let d = 1; d <= dim; d++) {
                    const sel = this.selectedDate && this.selectedDate.year === this.currentYear && this.selectedDate.month === this.currentMonth+1 && this.selectedDate.day === d;
                    const todayFlag = today.year === this.currentYear && today.month === this.currentMonth+1 && today.day === d;
                    days.push({ key: `cur-${d}`, day: d, isSelected: sel, isToday: todayFlag, isOtherMonth: false, isDisabled: false });
                }
                const remaining = 42 - days.length;
                for (let d = 1; d <= remaining; d++) {
                    days.push({ key: `next-${d}`, day: d, isSelected: false, isToday: false, isOtherMonth: true, isDisabled: true });
                }
                return days;
            },

            togglePicker() { this.isOpen = !this.isOpen; this.showMonthSelector = false; this.showYearSelector = false; },
            closePicker() { this.isOpen = false; this.showMonthSelector = false; this.showYearSelector = false; },
            toggleMonthSelector() { this.showMonthSelector = !this.showMonthSelector; this.showYearSelector = false; },
            toggleYearSelector() { this.showYearSelector = !this.showYearSelector; this.showMonthSelector = false; },
            prevYear() { this.currentYear--; this.updateYearRange(); },
            nextYear() { this.currentYear++; this.updateYearRange(); },
            prevMonth() { if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; } else this.currentMonth--; },
            nextMonth() { if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; } else this.currentMonth++; },
            prevYearRange() { this.yearRange.start -= 12; this.yearRange.end -= 12; this.updateYearRange(); },
            nextYearRange() { this.yearRange.start += 12; this.yearRange.end += 12; this.updateYearRange(); },
            selectMonth(idx) { this.currentMonth = idx; this.showMonthSelector = false; },
            selectYear(y) { this.currentYear = y; this.showYearSelector = false; },
            selectDate(d) {
                this.selectedDate = { year: this.currentYear, month: this.currentMonth+1, day: d };
                this.displayDate = `${this.currentYear}/${String(this.currentMonth+1).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
            },
            formatDate(date) { if (!date) return ''; return `${date.year}-${String(date.month).padStart(2,'0')}-${String(date.day).padStart(2,'0')}`; },

            // ===== اصلاح این سه متد =====
            setToday() {
                const t = this.getTodayPersian();
                this.currentYear = t.year;
                this.currentMonth = t.month - 1;
                this.selectedDate = t;
                this.displayDate = `${t.year}/${String(t.month).padStart(2, '0')}/${String(t.day).padStart(2, '0')}`;
                @this.set(fieldName, this.formatDate(t));
                @this.call('generateReport'); // ← اجرای مجدد گزارش
            },

            clearDate() {
                this.selectedDate = null;
                this.displayDate = '';
                @this.set(fieldName, '');
                @this.call('generateReport'); // ← اجرای مجدد گزارش
                this.closePicker();
            },

            applyDate() {
                if (this.selectedDate) {
                    @this.set(fieldName, this.formatDate(this.selectedDate));
                    @this.call('generateReport'); // ← اجرای مجدد گزارش
                    this.closePicker();
                } else {
                    this.setToday();
                }
            }
        };
    }
</script>
