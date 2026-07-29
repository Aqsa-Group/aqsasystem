<div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">گزارش خرید از شرکت‌ها</h2>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

        <!-- نمایش پیام -->
        @if(!empty($message))
        <div
            class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg mb-4">
            {{ $message }}
        </div>
        @endif

        @if(session()->has('error'))
        <div
            class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
        @endif
        <!-- فیلترها -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">شرکت</label>
                <select wire:model.live="company_id"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="">همه شرکت‌ها</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- از تاریخ -->
            <div x-data="persianDatePicker()" x-init="initFor('from_date')">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">از تاریخ</label>
                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                    placeholder="YYYY/MM/DD"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white cursor-pointer focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                    readonly />
                <!-- مودال تقویم -->
                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak @click.away="closePicker()"
                    @keydown.escape.window="closePicker()"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70"
                    style="display: none;">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- هدر -->
                        <div
                            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex gap-1">
                                <button @click="prevYear()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button @click="prevMonth()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <button @click="toggleMonthSelector()" type="button"
                                    class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <span x-text="monthsAfghan[currentMonth]"></span>
                                </button>
                                <button @click="toggleYearSelector()" type="button"
                                    class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <span x-text="currentYear"></span>
                                </button>
                            </div>
                            <div class="flex gap-1">
                                <button @click="nextMonth()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click="nextYear()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click="closePicker()" type="button"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- انتخاب ماه -->
                        <div x-show="showMonthSelector" x-transition class="p-4">
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="(month, index) in monthsAfghan" :key="index">
                                    <button @click="selectMonth(index)"
                                        :class="currentMonth === index ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300'"
                                        class="py-2 rounded-lg text-sm font-medium transition">
                                        <span x-text="month"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- انتخاب سال -->
                        <div x-show="showYearSelector" x-transition class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <button @click="prevYearRange()"
                                    class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <span class="font-bold text-gray-800 dark:text-white"><span
                                        x-text="yearRange.start"></span> - <span x-text="yearRange.end"></span></span>
                                <button @click="nextYearRange()"
                                    class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                <template x-for="year in yearRange.years" :key="year">
                                    <button @click="selectYear(year)"
                                        :class="currentYear === year ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300'"
                                        class="py-2 rounded-lg text-sm font-medium transition">
                                        <span x-text="year"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- تقویم اصلی -->
                        <div x-show="!showMonthSelector && !showYearSelector" class="p-4">
                            <div class="grid grid-cols-7 gap-1 mb-2 text-center">
                                <template x-for="day in weekDaysAfghan" :key="day">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><span
                                            x-text="day"></span></div>
                                </template>
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="day in calendarDays" :key="day.key">
                                    <button @click="selectDate(day.day)" :disabled="day.isDisabled" :class="{
                                        'bg-blue-500 text-white': day.isSelected,
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day.isToday && !day.isSelected,
                                        'text-gray-400 dark:text-gray-500': day.isOtherMonth,
                                        'hover:bg-gray-100 dark:hover:bg-gray-700': !day.isSelected && !day.isOtherMonth
                                    }" class="w-10 h-10 rounded-lg text-sm font-medium flex items-center justify-center transition"
                                        type="button">
                                        <span x-text="day.day"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- فوتر -->
                        <div
                            class="flex justify-between items-center p-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-300"
                                x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"></span>
                            <div class="flex gap-2">
                                <button @click="setToday()" type="button"
                                    class="px-3 py-1 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">امروز</button>
                                <button @click="clearDate()" type="button"
                                    class="px-3 py-1 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">پاک
                                    کردن</button>
                                <button @click="applyDate()" type="button"
                                    class="px-4 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors">تأیید</button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('from_date') <span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message
                    }}</span> @enderror
            </div>

            <!-- تا تاریخ -->
            <div x-data="persianDatePicker()" x-init="initFor('to_date')">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">تا تاریخ</label>
                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                    placeholder="YYYY/MM/DD"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-white cursor-pointer focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                    readonly />
                <!-- مودال تقویم (کامل) -->
                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak @click.away="closePicker()"
                    @keydown.escape.window="closePicker()"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70"
                    style="display: none;">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- هدر -->
                        <div
                            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex gap-1">
                                <button @click="prevYear()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button @click="prevMonth()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <button @click="toggleMonthSelector()" type="button"
                                    class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <span x-text="monthsAfghan[currentMonth]"></span>
                                </button>
                                <button @click="toggleYearSelector()" type="button"
                                    class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <span x-text="currentYear"></span>
                                </button>
                            </div>
                            <div class="flex gap-1">
                                <button @click="nextMonth()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click="nextYear()" type="button"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button @click="closePicker()" type="button"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- انتخاب ماه -->
                        <div x-show="showMonthSelector" x-transition class="p-4">
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="(month, index) in monthsAfghan" :key="index">
                                    <button @click="selectMonth(index)"
                                        :class="currentMonth === index ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300'"
                                        class="py-2 rounded-lg text-sm font-medium transition">
                                        <span x-text="month"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- انتخاب سال -->
                        <div x-show="showYearSelector" x-transition class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <button @click="prevYearRange()"
                                    class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <span class="font-bold text-gray-800 dark:text-white"><span
                                        x-text="yearRange.start"></span> - <span x-text="yearRange.end"></span></span>
                                <button @click="nextYearRange()"
                                    class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                <template x-for="year in yearRange.years" :key="year">
                                    <button @click="selectYear(year)"
                                        :class="currentYear === year ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300'"
                                        class="py-2 rounded-lg text-sm font-medium transition">
                                        <span x-text="year"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- تقویم اصلی -->
                        <div x-show="!showMonthSelector && !showYearSelector" class="p-4">
                            <div class="grid grid-cols-7 gap-1 mb-2 text-center">
                                <template x-for="day in weekDaysAfghan" :key="day">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400"><span
                                            x-text="day"></span></div>
                                </template>
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="day in calendarDays" :key="day.key">
                                    <button @click="selectDate(day.day)" :disabled="day.isDisabled" :class="{
                                        'bg-blue-500 text-white': day.isSelected,
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day.isToday && !day.isSelected,
                                        'text-gray-400 dark:text-gray-500': day.isOtherMonth,
                                        'hover:bg-gray-100 dark:hover:bg-gray-700': !day.isSelected && !day.isOtherMonth
                                    }" class="w-10 h-10 rounded-lg text-sm font-medium flex items-center justify-center transition"
                                        type="button">
                                        <span x-text="day.day"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- فوتر -->
                        <div
                            class="flex justify-between items-center p-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-600 dark:text-gray-300"
                                x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"></span>
                            <div class="flex gap-2">
                                <button @click="setToday()" type="button"
                                    class="px-3 py-1 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">امروز</button>
                                <button @click="clearDate()" type="button"
                                    class="px-3 py-1 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">پاک
                                    کردن</button>
                                <button @click="applyDate()" type="button"
                                    class="px-4 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 rounded-lg transition-colors">تأیید</button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('to_date') <span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- دکمه‌ها -->
            <div class="flex items-end gap-2">
            
                <button wire:click="printPdf" type="button"
                    class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    چاپ PDF
                </button>
            </div>
        </div>

        <!-- نمایش گزارش -->
        @if(!empty($reportData))
        <div class="overflow-x-auto">
            @foreach($reportData as $group)
            <div class="mb-8 border rounded-lg overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-bold text-lg">
                    {{ $group['company']['name'] ?? 'بدون نام' }}
                </div>
                <table class="w-full text-sm text-right">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2 border-b">بارکد</th>
                            <th class="px-4 py-2 border-b">نام کالا</th>
                            <th class="px-4 py-2 border-b">تعداد</th>
                            <th class="px-4 py-2 border-b">قیمت کل</th>
                            <th class="px-4 py-2 border-b">پرداختی</th>
                            <th class="px-4 py-2 border-b">بدهی</th>
                            <th class="px-4 py-2 border-b">تاریخ خرید</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['items'] as $buy)
                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $buy->barcode }}</td>
                            <td class="px-4 py-2">{{ $buy->name }}</td>
                            <td class="px-4 py-2">{{ $buy->all_exist_number ?? 0 }}</td>
                            <td class="px-4 py-2">{{ number_format($buy->total_price, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($buy->paid, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($buy->remaining, 2) }}</td>
                            <td class="px-4 py-2">{{ $buy->import_date->format('Y/m/d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 dark:bg-gray-800 font-bold">
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-left">جمع کل شرکت</td>
                            <td class="px-4 py-2">{{ number_format($group['totals']['total_price'], 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($group['totals']['paid'], 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($group['totals']['remaining'], 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endforeach

            <div class="bg-gray-200 dark:bg-gray-700 p-4 rounded-lg mt-4">
                <div class="grid grid-cols-3 gap-4 text-center font-bold text-lg">
                    <div>جمع کل: {{ number_format($totalAll['total_price'], 2) }}</div>
                    <div>کل پرداختی: {{ number_format($totalAll['paid'], 2) }}</div>
                    <div>کل بدهی: {{ number_format($totalAll['remaining'], 2) }}</div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
            برای مشاهده گزارش، فیلترها را تنظیم و دکمه «نمایش گزارش» را بزنید.
        </div>
        @endif
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
                yearRange: { start: 1400, end: 1410, years: [] },
                monthsAfghan: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
                    'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'],
                weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
                daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],

                init() {
                    this.updateYearRange();
                    const today = this.getTodayPersian();
                    this.currentYear = today.year;
                    this.currentMonth = today.month - 1;
                },

                initFor(field) {
                    this.init();
                    if (@this.get(field)) {
                        this.displayDate = @this.get(field);
                        const parts = @this.get(field).split('/');
                        if (parts.length === 3) {
                            this.selectedDate = {
                                year: parseInt(parts[0]),
                                month: parseInt(parts[1]),
                                day: parseInt(parts[2])
                            };
                            this.currentYear = parseInt(parts[0]);
                            this.currentMonth = parseInt(parts[1]) - 1;
                        }
                    }
                    this.applyDate = () => {
                        if (this.selectedDate) {
                            const formattedDate = this.formatDate(this.selectedDate);
                            this.displayDate = formattedDate;
                            @this.set(field, formattedDate);
                            this.closePicker();
                        }
                    };
                    this.clearDate = () => {
                        this.selectedDate = null;
                        this.displayDate = '';
                        @this.set(field, '');
                        this.closePicker();
                    };
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
                    return this.gregorianToPersian(today.getFullYear(), today.getMonth() + 1, today.getDate());
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
                        const pDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                        if (this.isLeapYear(persianYear)) pDays[11] = 30;
                        for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                            if (remainingDays <= pDays[persianMonth]) {
                                persianDay = remainingDays;
                                break;
                            }
                            remainingDays -= pDays[persianMonth];
                        }
                        persianMonth++;
                    } else {
                        persianYear = gy - 622;
                        let remainingDays = dayOfYear + 286;
                        const pDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                        if (this.isLeapYear(persianYear)) pDays[11] = 30;
                        for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                            if (remainingDays <= pDays[persianMonth]) {
                                persianDay = remainingDays;
                                break;
                            }
                            remainingDays -= pDays[persianMonth];
                        }
                        persianMonth++;
                    }
                    return { year: persianYear, month: persianMonth, day: persianDay };
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
                        days.push({ key: `prev-${day}`, day, isSelected: false, isToday: false, isOtherMonth: true,
                            isDisabled: true });
                    }
                    for (let day = 1; day <= daysInMonth; day++) {
                        const isSelected = this.selectedDate &&
                            this.selectedDate.year === this.currentYear &&
                            this.selectedDate.month === this.currentMonth + 1 &&
                            this.selectedDate.day === day;
                        const isToday = today.year === this.currentYear &&
                            today.month === this.currentMonth + 1 &&
                            today.day === day;
                        days.push({ key: `current-${day}`, day, isSelected, isToday, isOtherMonth: false,
                            isDisabled: false });
                    }
                    const remainingCells = 42 - days.length;
                    for (let day = 1; day <= remainingCells; day++) {
                        days.push({ key: `next-${day}`, day, isSelected: false, isToday: false, isOtherMonth: true,
                            isDisabled: true });
                    }
                    return days;
                },

                togglePicker() { this.isOpen = !this.isOpen;
                    this.showMonthSelector = false;
                    this.showYearSelector = false; },
                closePicker() { this.isOpen = false;
                    this.showMonthSelector = false;
                    this.showYearSelector = false; },
                toggleMonthSelector() { this.showMonthSelector = !this.showMonthSelector;
                    this.showYearSelector = false; },
                toggleYearSelector() { this.showYearSelector = !this.showYearSelector;
                    this.showMonthSelector = false; },
                prevYear() { this.currentYear--;
                    this.updateYearRange(); },
                nextYear() { this.currentYear++;
                    this.updateYearRange(); },
                prevMonth() { if (this.currentMonth === 0) { this.currentMonth = 11;
                        this.currentYear--; } else this.currentMonth--; },
                nextMonth() { if (this.currentMonth === 11) { this.currentMonth = 0;
                        this.currentYear++; } else this.currentMonth++; },
                prevYearRange() { this.yearRange.start -= 12;
                    this.yearRange.end -= 12;
                    this.updateYearRange(); },
                nextYearRange() { this.yearRange.start += 12;
                    this.yearRange.end += 12;
                    this.updateYearRange(); },
                selectMonth(index) { this.currentMonth = index;
                    this.showMonthSelector = false; },
                selectYear(year) { this.currentYear = year;
                    this.showYearSelector = false; },
                selectDate(day) {
                    this.selectedDate = { year: this.currentYear, month: this.currentMonth + 1, day };
                    this.displayDate =
                        `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                },
                formatDate(date) { if (!date) return ''; return `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`; },
                setToday() {
                    const today = this.getTodayPersian();
                    this.currentYear = today.year;
                    this.currentMonth = today.month - 1;
                    this.selectedDate = today;
                    this.displayDate = this.formatDate(today);
                },
                applyDate() {},
                clearDate() {},
            };
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('print-pdf', (event) => {
                const printWindow = window.open(event.url, '_blank');
                if (printWindow) {
                    printWindow.focus();
                    printWindow.print();
                } else {
                    alert('لطفاً باز شدن پنجره جدید را در مرورگر خود مجاز کنید.');
                }
            });
        });
    </script>
       @push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* فونت‌ها و تنظیمات دیگر (همانند قبل) */
        @font-face {
            font-family: "DimaYekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
        }

        @font-face {
            font-family: "times";
            src: url("/fonts/times.ttf") format("truetype");
        }

        @font-face {
            font-family: "vazir";
            src: url("/fonts/Vazir.ttf") format("truetype");
        }

        @font-face {
            font-family: "shabnam";
            src: url("/fonts/Shabnam-Medium.ttf") format("truetype");
        }

        @font-face {
            font-family: "Mj_Afrigha";
            src: url("/fonts/Mj_Afrigha.ttf") format("truetype");
        }

        @font-face {
            font-family: "Yekan-Regular";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
        }

        .yekan {
            font-family: "DimaYekan", sans-serif;
        }

        .shabnam {
            font-family: "shabnam", sans-serif;
        }

        .Mj_Afrigha {
            font-family: "Mj_Afrigha", sans-serif;
        }

        .vazir {
            font-family: "vazir", sans-serif;
        }

        .amiri {
            font-family: "Yekan-Regular", sans-serif;
        }

        .times {
            font-family: "times", serif;
        }
    </style>
    @endpush
</div>