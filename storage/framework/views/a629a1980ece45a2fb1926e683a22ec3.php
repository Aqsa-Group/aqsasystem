<div>
    <div>
        <div>
            <div class="space-y-4 mb-6">
                <h1 class="text-[24px] font-medium vazir">صفحه گزارشات معاملات روزانه حسابات و صندوق ها</h1>
            </div>

            <div class="w-full">
                <div class="bg-[#F5F5F5] dark:bg-black dark:border dark:border-white p-4 rounded-[12px] mx-auto"
                    style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-7 gap-4 items-end">
                        <!-- چاپ -->
                        <div>
                            <label class="block text-[16px] font-medium invisible mb-1">چاپ</label>
                            <button wire:click="printReport" wire:loading.attr="disabled" wire:target="printReport"
                                class="w-full h-[60px] flex items-center justify-center gap-2
                                       bg-[#2563EB] text-white rounded-xl
                                       hover:bg-blue-700 transition">
                                <svg wire:loading.remove wire:target="printReport" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span wire:loading.remove wire:target="printReport">چاپ PDF</span>
                                <span wire:loading wire:target="printReport">در حال تولید PDF...</span>
                            </button>
                        </div>

                        <!-- ریست فیلترها -->
                        <div>
                            <label class="block text-[16px] font-medium invisible mb-1">ریست</label>
                            <button wire:click="resetFilters" wire:loading.attr="disabled" class="w-full h-[60px] flex items-center justify-center gap-2
                                   bg-gray-500 text-white rounded-xl
                                   hover:bg-gray-600 transition">
                                <span wire:loading.remove>ریست فیلترها</span>
                                <span wire:loading>در حال ریست...</span>
                            </button>
                        </div>

                        <!-- نوع ترانزکشن -->
                        <div>
                            <label class="block text-[16px] font-medium mb-1 dark:text-white">نوع تراکنش</label>
                            <select wire:model.live="transactionType" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                                <option value="">همه</option>
                                <option value="رسید">رسید</option>
                                <option value="برد">برد</option>
                            </select>
                        </div>

                        <!-- نوع حساب -->
                        <div>
                            <label class="block text-[16px] font-medium mb-1 dark:text-white">نوع حساب</label>
                            <select wire:model.live="accountType" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                                <option value="">همه</option>
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                        </div>

                        <!-- ارز -->
                        <div>
                            <label class="block text-[16px] font-medium mb-1 dark:text-white">ارز</label>
                            <select wire:model.live="currency" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                                <option value="">همه ارزها</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>"><?php echo e($name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                            </select>
                        </div>


                        <div>
                            <div class="lg:col-span-3 relative" x-data="fromDatePicker()" x-init="init()">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">از
                                    تاریخ</label>
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder="YYYY/MM/DD"
                                    class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 cursor-pointer"
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

                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['fromDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <div>
                            <!-- تا تاریخ -->
                            <div class="lg:col-span-3 relative" x-data="toDatePicker()" x-init="init()">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تا
                                    تاریخ</label>
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder="YYYY/MM/DD"
                                    class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 cursor-pointer"
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

                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['toDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>


                    </div>



                    <!-- جدول تراکنش‌ها -->
                    <div class="overflow-x-auto w-full mt-4">
                        <div class="max-h-[600px] overflow-y-auto">
                            <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                                <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-4 font-bold w-16">
                                            <span class="border border-white px-2 py-1 rounded-lg">#</span>
                                        </th>
                                        <th class="px-4 py-4 font-bold">نام حساب</th>
                                        <th class="px-4 py-4 font-bold">نوع معامله</th>
                                        <th class="px-4 py-4 font-bold">نوع حساب</th>
                                        <th class="px-4 py-4 font-bold">مقدار</th>
                                        <th class="px-4 py-4 font-bold">ارز</th>
                                        <th class="px-4 py-4 font-bold">بیلانس فعلی</th>
                                        <th class="px-4 py-4 font-bold">توضیحات</th>
                                        <th class="px-4 py-4 font-bold">تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr
                                        class="border-b dark:bg-black dark:text-white dark:border-white hover:bg-gray-50">
                                        <td class="px-4 py-4">
                                            <?php echo e($transactions->firstItem() + $index); ?>

                                        </td>
                                        <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                            <div class="whitespace-nowrap">

                                              <div class="font-medium">
    <!--[if BLOCK]><![endif]--><?php if(
        empty($transaction->customer_id)
        && !empty($transaction->withdraw_id)
    ): ?>
        برداشت
    <?php elseif(
        empty($transaction->customer_id)
        && $transaction->is_sell_table == 1
    ): ?>
        معامله از صندوق
    <?php else: ?>
        <?php echo e($transaction->customer->fullname ?? 'نامشخص'); ?>

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>


                                                <div class="text-gray-500 dark:text-white text-sm mt-1">
                                                    <!--[if BLOCK]><![endif]--><?php if(empty($transaction->customer_id) && $transaction->is_sell_table
                                                    == 1): ?>

                                                    <?php else: ?>
                                                    <?php echo e($transaction->customer->account_number ?? '-'); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>

                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <?php echo e($transaction->type); ?>

                                        </td>
                                        <td class="px-4 py-4">
                                            <?php echo e($transaction->account_type); ?>

                                        </td>
                                        <td
                                            class="px-4 py-4 <?php echo e($transaction->type == 'رسید' ? 'text-green-600' : 'text-red-600'); ?>">
                                            <?php echo e(number_format($transaction->amount, 2)); ?>

                                        </td>
                                        <td class="px-4 py-4">
                                            <?php echo e($transaction->currency_fa); ?>

                                        </td>
                                        <td class="px-4 py-4">
                                            <?php echo e(number_format($transaction->balance, 2)); ?>

                                        </td>
                                        <td class="px-4 py-4">
                                            <?php echo e($transaction->description); ?>

                                        </td>
                                        <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                            <div class="whitespace-nowrap">
                                                <div class="font-medium">

                                                    <?php echo e(explode(' ', $transaction->date)[0]); ?>


                                                </div>
                                                <div class="text-gray-500 dark:text-white text-sm mt-1">
                                                    <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('h:i A')); ?>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="px-4 py-4 text-center text-gray-500 dark:text-gray-300">
                                            هیچ تراکنشی یافت نشد
                                        </td>
                                    </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- جدول خلاصه گزارشات -->
        <div class="w-full mt-10">
            <div class="bg-[#F5F5F5] dark:bg-black dark:border dark:border-white p-6 rounded-[12px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040;">

                <div class="overflow-x-auto w-full mt-4">
                    <table class="w-full text-sm md:text-base text-center text-gray-700 dark:text-white">
                        <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16">
                            <tr>
                                <th class="px-4 py-4 w-12">#</th>
                                <th class="px-4 py-4">ارز</th>
                                <th class="px-4 py-4">رسید نقدی</th>
                                <th class="px-4 py-4">برد نقدی</th>
                                <th class="px-4 py-4">رسید بانکی</th>
                                <th class="px-4 py-4">برد بانکی</th>
                                <th class="px-4 py-4"> بیلانس نقدی</th>
                                <th class="px-4 py-4"> بیلانس بانکی</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b dark:border-white hover:bg-gray-100 dark:hover:bg-gray-800">
                                <td class="px-4 py-4"><?php echo e($index + 1); ?></td>
                                <td class="px-4 py-4 font-bold"><?php echo e($item->currency_fa); ?></td>
                                <td class="px-4 py-4 text-green-600"><?php echo e(number_format($item->receipt_cash, 2)); ?></td>
                                <td class="px-4 py-4 text-red-600"><?php echo e(number_format($item->withdrawal_cash, 2)); ?></td>
                                <td class="px-4 py-4 text-green-600"><?php echo e(number_format($item->receipt_bank, 2)); ?></td>
                                <td class="px-4 py-4 text-red-600"><?php echo e(number_format($item->withdrawal_bank, 2)); ?></td>
                                <td class="px-4 py-4 font-bold <?php echo e($item->balance_cash >= 0 ? 'text-green-600' : 'text-red-600'); ?>"
                                    dir="ltr">
                                    <?php echo e(number_format($item->balance_cash, 2)); ?>

                                </td>
                                <td class="px-4 py-4 font-bold <?php echo e($item->balance_bank >= 0 ? 'text-green-600' : 'text-red-600'); ?>"
                                    dir="ltr">
                                    <?php echo e(number_format($item->balance_bank, 2)); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500 dark:text-gray-300">
                                    داده‌ای برای نمایش وجود ندارد
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function customerSearch() {
    return {
        isOpen: false,
        searchQuery: '',
        selectedCustomerId: <?php if ((object) ('selectedCustomer') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedCustomer'->value()); ?>')<?php echo e('selectedCustomer'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedCustomer'); ?>')<?php endif; ?>.live,
        selectedCustomerName: '',
        allCustomers: <?php echo json_encode($customers, 15, 512) ?>,
        filteredCustomers: [],
        
        init() {
            this.filteredCustomers = [...this.allCustomers];
            
            if (this.selectedCustomerId) {
                const selected = this.allCustomers.find(c => c.id == this.selectedCustomerId);
                if (selected) {
                    this.selectedCustomerName = `${selected.fullname} - ${selected.account_number}`;
                }
            }
            
            this.$watch('selectedCustomerId', (value) => {
                if (value) {
                    const selected = this.allCustomers.find(c => c.id == value);
                    this.selectedCustomerName = selected ? 
                        `${selected.fullname} - ${selected.account_number}` : '';
                } else {
                    this.selectedCustomerName = '';
                }
            });
        },
        
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.searchQuery = '';
                this.filterCustomers();
            }
        },
        
        closeDropdown() {
            this.isOpen = false;
            this.searchQuery = '';
        },
        
        filterCustomers() {
            if (!this.searchQuery.trim()) {
                this.filteredCustomers = [...this.allCustomers];
                return;
            }
            
            const query = this.searchQuery.toLowerCase();
            this.filteredCustomers = this.allCustomers.filter(customer => 
                customer.fullname?.toLowerCase().includes(query) ||
                customer.account_number?.toLowerCase().includes(query) ||
                customer.phone?.includes(query)
            );
        },
        
        selectCustomer(customer) {
            this.selectedCustomerId = customer.id;
            this.selectedCustomerName = `${customer.fullname} - ${customer.account_number}`;
            this.closeDropdown();
        }
    };
}

function createPersianDatePicker(fieldName = 'date') {
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
        daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],
        
        init() {
            this.updateYearRange();
            const today = this.getTodayPersian();
            this.currentYear = today.year;
            this.currentMonth = today.month - 1;
            
            const livewireValue = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get(fieldName);
            if (!livewireValue) {
                this.selectedDate = today;
                this.displayDate = this.formatDate(today);
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set(fieldName, this.formatDate(today));
            } else {
                // تبدیل تاریخ از Y-m-d به Y/m/d برای نمایش
                const dateParts = livewireValue.split('-');
                if (dateParts.length === 3) {
                    const year = parseInt(dateParts[0]);
                    const month = parseInt(dateParts[1]);
                    const day = parseInt(dateParts[2]);
                    
                    if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
                        this.selectedDate = { year, month, day };
                        this.displayDate = `${year}/${String(month).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
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
            this.displayDate = `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
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
            this.displayDate = `${today.year}/${String(today.month).padStart(2, '0')}/${String(today.day).padStart(2, '0')}`;
            
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set(fieldName, this.formatDate(today));
        },
        
        clearDate() {
            this.selectedDate = null;
            this.displayDate = '';
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set(fieldName, '');
            this.closePicker();
        },
        
        applyDate() {
            if (this.selectedDate) {
                const formattedDate = this.formatDate(this.selectedDate);
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set(fieldName, formattedDate);
                this.closePicker();
            } else {
                this.setToday();
            }
        }
    };
}
function fromDatePicker() {
    return createPersianDatePicker('fromDate');
}

function toDatePicker() {
    return createPersianDatePicker('toDate');
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
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/journal.blade.php ENDPATH**/ ?>