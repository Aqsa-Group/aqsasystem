<div>
    <!-- Alert Component -->
    @if ($alert)
    <div x-data="{
            show: true,
            init() {
                $wire.watch('alert', (value) => {
                    if (value) {
                        this.show = true;
                        setTimeout(() => {
                            this.show = false;
                            setTimeout(() => $wire.clearAlert(), 300);
                        }, 4000);
                    }
                });
        
                setTimeout(() => {
                    this.show = false;
                    setTimeout(() => $wire.clearAlert(), 300);
                }, 4000);
            }
        }" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] {{ $alert['type'] === 'error' ? 'bg-red-500' : 'bg-[#2B65E5]' }} vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                {{ $alert['message'] }}
            </h2>
        </div>
    </div>
    @endif

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

    <!-- Add Staff Form -->
    <div class="w-full max-w-[1200px] p-4 mx-auto bg-white  border border-[#D7E5EC] shadow-sm backdrop:blur-lg dark:border-white dark:bg-black dark:border dark:border-white rounded-2xl space-y-2 mb-5"
       >

        <!-- Header -->
        <div class="text-center space-y-2 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 vazir dark:text-white">
                مدیریت کارمندان
            </h2>
            <p class="text-lg text-gray-600 dark:text-white vazir">
                فرم ثبت و مدیریت اطلاعات کارمندان
            </p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="save" class="space-y-6">
            {{-- نمبر حساب --}}
            <div class="flex-1 w-full">
                <div class="relative w-full">
                    <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نمبر
                        حساب کارمند</label>
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
                                            $wire.selectCustomer(selected.id);
                                            $wire.set('search', selected.fullname);
                                        } else {
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
                                }" x-init="updateDisplay();
                                $watch('selectedId', () => updateDisplay())" class="relative w-full">
                        <input list="customersList" x-model="searchValue" @change="handleSelect"
                            placeholder="جستجو یا انتخاب حساب..."
                            class="w-full h-[60px] dark:bg-black dark:text-white dark:border-white dark:placeholder:text-white p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                            autocomplete="off">
                        <datalist id="customersList">
                            @foreach ($customers as $customer)
                            <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                @endforeach
                        </datalist>
                        @if (empty($selectedAccount))
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓" class="dark:hidden">
                            <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                        @endif
                    </div>
                    @error('selectedAccount')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- First Row - 2 columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white vazir mb-1">
                        نام
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="name" placeholder="نام کارمند"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:bg-black dark:placeholder:text-white dark:border-white dark:text-white">
                        @error('name')
                        <span class="text-red-500 text-xs">{{ $message }} مرد</span>
                        @enderror
                    </div>
                </div>

                <!-- Father Name -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        نام پدر
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="fathername" placeholder="نام پدر"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        @error('fathername')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>




            </div>

            <!-- Second Row - 2 columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Age -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        سن
                    </label>
                    <div class="relative">
                        <input type="number" wire:model="age" placeholder="سن" min="18" max="80"
                            class="w-full p-3 rounded-xl dark:border focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        @error('age')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        جنسیت
                    </label>
                    <div class="relative">
                        <select wire:model="gender"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                            <option value="male">مرد</option>
                            <option value="female">زن</option>
                        </select>
                        @error('gender')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Third Row - 2 columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        شماره تماس
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="phone" placeholder="شماره تماس"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        @error('phone')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Job -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        شغل
                    </label>
                    <div class="relative">
                        <input type="text" wire:model="job" placeholder="عنوان شغل"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                        @error('job')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Fourth Row - 2 columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Salary Amount -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        میزان معاش (افغانی)
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="formatted_salary" placeholder="مبلغ معاش"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">

                        @error('salary_amount')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror

                        <!-- نمایش مبلغ به حروف -->
                        @if ($salary_in_words)
                        <div class="text-xs text-black dark:text-green-400 mt-1 font-semibold">
                            {{ $salary_in_words }}
                        </div>
                        @endif
                    </div>
                </div>


                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        فیصدی مالیه
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.lazy="tax_percent" placeholder="فیصدی مالیه (%)"
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">

                        @error('salary_amount')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror

                        <!-- نمایش مبلغ به حروف -->
                        @if ($tax_in_words)
                        <div class="text-xs text-black dark:text-green-400 mt-1 font-semibold">
                            {{ $tax_in_words }}
                        </div>
                        @endif

                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        معاش خالص
                    </label>
                    <div class="relative">
                        <input type="text" value="{{ number_format($final_salary) }}" readonly
                            class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                      focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">

                        @error('salary_amount')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror

                        <!-- نمایش مبلغ به حروف -->
                        @if ($final_salary_in_words)
                        <div class="text-xs text-black dark:text-green-400 mt-1 font-semibold">
                            {{ $final_salary_in_words }}
                        </div>
                        @endif

                    </div>
                </div>


                <!-- From Date -->

                <div>
                    <div class="lg:col-span-3 relative" x-data="fromDatePicker()" x-init="init()">
                        <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">از
                            تاریخ</label>
                        <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                            placeholder="YYYY/MM/DD"
                            class="w-full dark:text-white dark:bg-black dark:border-white p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
                            readonly />

                        <!-- Date Picker Modal -->
                        <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                            @keydown.escape.window="closePicker()" @click.away="closePicker()"
                            class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                            aria-modal="true" style="display: none" :style="isOpen ? 'display: block;' : ''">
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
                                                            stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
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
                                                    <span x-text="yearRange.start"></span>
                                                    -
                                                    <span x-text="yearRange.end"></span>
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
                                                            'bg-blue-500 text-white hover:bg-blue-600': day.isSelected,
                                                            'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                                .isToday && !day.isSelected,
                                                            'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                                !day.isToday && !day.isSelected && !day.isOtherMonth,
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

                        @error('contract_start')
                        <span class="text-red-500 text-xs mt-1 block">{{
                            $message
                            }}</span>
                        @enderror
                    </div>
                </div>


            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <!-- تا تاریخ -->
                <div class="" x-data="toDatePicker()" x-init="init()">
                    <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تا
                        تاریخ</label>
                    <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                        placeholder="YYYY/MM/DD"
                        class="w-full dark:text-white dark:bg-black dark:border-white  p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
                        readonly />

                    <!-- Date Picker Modal -->
                    <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                        @keydown.escape.window="closePicker()" @click.away="closePicker()"
                        class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true" style="display: none;" :style="isOpen ? 'display: block;' : ''">

                        <div
                            class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                            </div>
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
                                                        'bg-blue-500 text-white hover:bg-blue-600': day.isSelected,
                                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                            .isToday && !day.isSelected,
                                                        'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                            !day.isToday && !day.isSelected && !day.isOtherMonth,
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

                    @error('contract_end')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-1">
                        آدرس
                    </label>
                    <input type="text" wire:model="address" placeholder="‌آدرس"
                        class="w-full p-3 rounded-xl  focus:ring-2 bg-[#EFF6F9]
                                   focus:ring-blue-500 dark:placeholder:text-white dark:bg-black dark:border-white dark:text-white">
                    @error('address')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>


            </div>

            <!-- File Uploads - Now 3 in 2 rows -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                        تصویر کارمند
                    </label>
                    <div x-data="{
                        files: [],
                        isUploading: false,
                        uploadedFileName: null,
                        uploadedFileUrl: null,
                        init() {
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
                    
                            @if($editId && $image)
                            this.uploadedFileName = 'تصویر آپلود شده';
                            this.uploadedFileUrl = '{{ $tempImageUrl }}';
                            @endif
                        },
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.uploadedFileName = file.name;
                                this.isUploading = true;
                                this.$wire.upload('image', file, () => {
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
                                this.$wire.upload('image', file, () => {
                                    this.isUploading = false;
                                });
                            }
                        },
                        removeFile() {
                            this.uploadedFileName = null;
                            this.uploadedFileUrl = null;
                            this.$wire.set('image', null);
                            this.$wire.call('removeImage', 'image');
                            if (this.$refs.fileInput) {
                                this.$refs.fileInput.value = '';
                            }
                        }
                    }" x-on:drop.prevent="handleDrop" x-on:dragover.prevent :class="{
                            'border-green-500 bg-green-50 dark:bg-black': uploadedFileName && !isUploading,
                            'border-blue-500 bg-blue-50 dark:bg-black': isUploading,
                            'border-[#112080] bg-white dark:bg-black': !uploadedFileName && !isUploading
                        }"
                        class="w-full h-[46px] p-3 rounded-[12px] border-2 border-dashed focus:ring-2 focus:ring-blue-500 dark:border-white dark:bg-black dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 relative"
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
                           <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                        </template>

                        <!-- حالت اولیه (بدون فایل) -->
                        <template x-if="!isUploading && !uploadedFileName">
                            <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                        </template>

                        <input type="file" class="hidden" x-ref="fileInput" accept=".jpg,.jpeg,.png,.webp"
                            x-on:change="handleFileSelect($event)">
                    </div>

                    @error('image')
                    <div class="mt-2 flex items-center gap-2 text-red-500 dark:text-red-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror

                    <!-- نمایش فایل ذخیره شده (در حالت ویرایش) -->
                    @if ($editId && is_string($image))
                    <div
                        class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-700 dark:text-blue-300 text-sm">تصویر قبلاً آپلود شده</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ Storage::url($image) }}" target="_blank"
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
                            <button type="button" wire:click="removeImage('image')"
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

                <!-- ID Card Upload -->
                <div>
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                        تصویر تذکره
                    </label>
                    <div x-data="{
                        files: [],
                        isUploading: false,
                        uploadedFileName: null,
                        uploadedFileUrl: null,
                        init() {
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
                    
                            @if($editId && $id_card)
                            this.uploadedFileName = 'تصویر تذکره آپلود شده';
                            this.uploadedFileUrl = '{{ $tempIdCardUrl }}';
                            @endif
                        },
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.uploadedFileName = file.name;
                                this.isUploading = true;
                                this.$wire.upload('id_card', file, () => {
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
                                this.$wire.upload('id_card', file, () => {
                                    this.isUploading = false;
                                });
                            }
                        },
                        removeFile() {
                            this.uploadedFileName = null;
                            this.uploadedFileUrl = null;
                            this.$wire.set('id_card', null);
                            this.$wire.call('removeImage', 'id_card');
                            if (this.$refs.fileInput) {
                                this.$refs.fileInput.value = '';
                            }
                        }
                    }" x-on:drop.prevent="handleDrop" x-on:dragover.prevent :class="{
                            'border-green-500 bg-green-50 dark:bg-black': uploadedFileName && !isUploading,
                            'border-blue-500 bg-blue-50 dark:bg-black': isUploading,
                            'border-[#112080] bg-white dark:bg-black': !uploadedFileName && !isUploading
                        }"
                        class="w-full h-[46px] p-3 rounded-[12px] border-2 border-dashed focus:ring-2 focus:ring-blue-500 dark:border-white dark:bg-black dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 relative"
                        x-on:click="$refs.fileInput.click()">

                        <template x-if="isUploading">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-12 h-12 mb-2 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                </div>
                                <h1 class="font-vazir text-blue-600 dark:text-blue-300 text-[16px]">در حال آپلود...
                                </h1>
                                <p class="font-vazir text-gray-500 dark:text-gray-400 text-sm mt-1"
                                    x-text="uploadedFileName"></p>
                            </div>
                        </template>

                        <template x-if="!isUploading && uploadedFileName">
                            <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                        </template>

                        <template x-if="!isUploading && !uploadedFileName">
                            <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                        </template>

                        <input type="file" class="hidden" x-ref="fileInput" accept=".jpg,.jpeg,.png,.webp"
                            x-on:change="handleFileSelect($event)">
                    </div>

                    @error('id_card')
                    <div class="mt-2 flex items-center gap-2 text-red-500 dark:text-red-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror

                    @if ($editId && is_string($id_card))
                    <div
                        class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-700 dark:text-blue-300 text-sm">تصویر تذکره قبلاً آپلود
                                شده</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ Storage::url($id_card) }}" target="_blank"
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
                            <button type="button" wire:click="removeImage('id_card')"
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

                <!-- Document Upload - Full width -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-black dark:text-white mb-2">
                        اسناد دیگر (PDF, DOC, DOCX)
                    </label>
                    <div x-data="{
                        files: [],
                        isUploading: false,
                        uploadedFileName: null,
                        uploadedFileUrl: null,
                        init() {
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
                    
                            @if($editId && $document)
                            this.uploadedFileName = 'سند آپلود شده';
                            @endif
                        },
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.uploadedFileName = file.name;
                                this.isUploading = true;
                                this.$wire.upload('document', file, () => {
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
                                this.$wire.upload('document', file, () => {
                                    this.isUploading = false;
                                });
                            }
                        },
                        removeFile() {
                            this.uploadedFileName = null;
                            this.uploadedFileUrl = null;
                            this.$wire.set('document', null);
                            this.$wire.call('removeImage', 'document');
                            if (this.$refs.fileInput) {
                                this.$refs.fileInput.value = '';
                            }
                        }
                    }" x-on:drop.prevent="handleDrop" x-on:dragover.prevent :class="{
                            'border-green-500 bg-green-50 dark:bg-black': uploadedFileName && !isUploading,
                            'border-blue-500 bg-blue-50 dark:bg-black': isUploading,
                            'border-[#112080] bg-white dark:bg-black': !uploadedFileName && !isUploading
                        }"
                        class="w-full h-[46px] p-3 rounded-[12px] border-2 border-dashed focus:ring-2 focus:ring-blue-500 dark:border-white dark:bg-black dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 relative"
                        x-on:click="$refs.fileInput.click()">

                        <template x-if="isUploading">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-12 h-12 mb-2 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                </div>
                                <h1 class="font-vazir text-blue-600 dark:text-blue-300 text-[16px]">در حال آپلود...
                                </h1>
                                <p class="font-vazir text-gray-500 dark:text-gray-400 text-sm mt-1"
                                    x-text="uploadedFileName"></p>
                            </div>
                        </template>

                        <template x-if="!isUploading && uploadedFileName">
                           <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                        </template>

                        <template x-if="!isUploading && !uploadedFileName">
                            <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                        </template>

                        <input type="file" class="hidden" x-ref="fileInput" accept=".pdf,.doc,.docx"
                            x-on:change="handleFileSelect($event)">
                    </div>

                    @error('document')
                    <div class="mt-2 flex items-center gap-2 text-red-500 dark:text-red-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror

                    @if ($editId && is_string($document))
                    <div
                        class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-700 dark:text-blue-300 text-sm">سند قبلاً آپلود شده</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ Storage::url($document) }}" target="_blank"
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
                            <button type="button" wire:click="removeImage('document')"
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

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <button type="button" wire:click="resetInputFields"
                    class="px-8 py-4 bg-[#184D6C] text-white rounded-xl  transition">
                    انصراف
                </button>
                <button type="submit" class="px-8 py-4 bg-[#184D6C] text-white rounded-xl    transition">
                    {{ $editId ? 'بروزرسانی' : 'ثبت کارمند' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Filters and Search -->
    <div class="flex w-full max-w-[1200px] items-center mt-5 gap-3 mx-auto">
        <!-- Filter Button -->
        <div class="relative">
            <button wire:click="$toggle('filterOpen')"
                class="px-4 py-2 border rounded-lg dark:bg-black bg-[#184D6C] transition flex items-center gap-2 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                <span>فیلتر</span>
            </button>

            @if ($filterOpen)
            <div
                class="absolute top-full mt-2 dark:bg-black bg-white border rounded-xl shadow-lg p-4 w-72 z-50 space-y-3">
                <!-- Gender Filter -->
                <div>
                    <label class="block text-sm font-medium mb-1">جنسیت</label>
                    <select wire:model="filterGender"
                        class="border rounded px-3 py-2 w-full dark:bg-black dark:text-white">
                        <option value="">همه</option>
                        <option value="male">مرد</option>
                        <option value="female">زن</option>
                    </select>
                </div>

                <!-- Job Filter -->
                <div>
                    <label class="block text-sm font-medium mb-1">شغل</label>
                    <select wire:model="filterJob"
                        class="border rounded px-3 py-2 w-full dark:bg-black dark:text-white">
                        <option value="">همه مشاغل</option>
                        @foreach ($jobs as $job)
                        <option value="{{ $job }}">{{ $job }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button wire:click="applyFilter"
                        class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        اعمال فیلتر
                    </button>
                    <button wire:click="clearFilters"
                        class="flex-1 px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        پاک کردن
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Search Box -->
        <div class="relative flex-1 max-w-md">
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

        </div>
    </div>

    <!-- Staff Table -->
    <div class="w-full max-w-[1200px] p-6 mt-4 mx-auto dark:bg-black dark:border dark:border-white  bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC] rounded-lg"
     >

        <!-- Table Header -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">                    <tr>
                        <th class="px-6 py-4 font-bold">#</th>
                        <th class="px-6 py-4 font-bold">تصویر</th>
                        <th class="px-6 py-4 font-bold">نام کامل</th>
                        <th class="px-6 py-4 font-bold">نام پدر</th>
                        <th class="px-6 py-4 font-bold">سن</th>
                        <th class="px-6 py-4 font-bold">جنسیت</th>
                        <th class="px-6 py-4 font-bold">شغل</th>
                        <th class="px-6 py-4 font-bold">معاش</th>
                        <th class="px-6 py-4 font-bold">مدت قرارداد</th>
                        <th class="px-6 py-4 font-bold text-center">عملیات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($staffs as $index => $staff)
<tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">                        <td class="px-6 py-4">{{ $staffs->firstItem() + $index }}</td>

                        <!-- Image -->
                        <td class="px-6 py-4">
                            @if ($staff->image)
                            <img src="{{ Storage::url($staff->image) }}" alt="{{ $staff->name }}"
                                class="w-12 h-12 rounded-full object-cover">
                            @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                    </path>
                                </svg>
                            </div>
                            @endif
                        </td>

                        <!-- Name -->
                        <td class="px-6 py-4 font-medium">{{ $staff->name }}</td>

                        <!-- Father Name -->
                        <td class="px-6 py-4">{{ $staff->fathername }}</td>

                        <!-- Age -->
                        <td class="px-6 py-4">{{ $staff->age }} سال</td>

                        <!-- Gender -->
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 rounded-full text-xs {{ $staff->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $staff->gender == 'male' ? 'مرد' : 'زن' }}
                            </span>
                        </td>

                        <!-- Job -->
                        <td class="px-6 py-4">{{ $staff->job }}</td>

                        <!-- Salary -->
                        <td class="px-6 py-4">
                            {{ number_format($staff->final_salary) }} افغانی
                        </td>

                        <!-- Contract Duration -->
                        <td class="px-6 py-4">
                            @php
                            $start = \Carbon\Carbon::parse($staff->contract_start);
                            $end = \Carbon\Carbon::parse($staff->contract_end);
                            $diff = $start->diff($end);
                            @endphp
                            <div class="text-sm">
                                <div>از: {{ $start->format('Y/m/d') }}</div>
                                <div>تا: {{ $end->format('Y/m/d') }}</div>
                                <div class="text-gray-500">
                                    ({{ $diff->y }} سال و {{ $diff->m }} ماه)
                                </div>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <!-- Edit Button -->
                                <button wire:click="edit({{ $staff->id }})"
                                    class="p-2 text-blue-600 hover:text-blue-800" title="ویرایش">
                                    <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                        class="w-7 h-7 dark:hidden" alt="Edit">
                                    <svg width="22" height="22" class="hidden dark:block" viewBox="0 0 22 22"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.082 1.83325H8.2487C3.66536 1.83325 1.83203 3.66659 1.83203 8.24992V13.7499C1.83203 18.3333 3.66536 20.1666 8.2487 20.1666H13.7487C18.332 20.1666 20.1654 18.3333 20.1654 13.7499V11.9166"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M14.7027 2.76832L7.4794 9.99165C7.2044 10.2667 6.9294 10.8075 6.8744 11.2017L6.48023 13.9608C6.33357 14.96 7.0394 15.6567 8.03857 15.5192L10.7977 15.125C11.1827 15.07 11.7236 14.795 12.0077 14.52L19.2311 7.29665C20.4777 6.04999 21.0644 4.60165 19.2311 2.76832C17.3977 0.934987 15.9494 1.52165 14.7027 2.76832Z"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.668 3.8042C14.2821 5.99503 15.9963 7.7092 18.1963 8.33253"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button wire:click="confirmDelete({{ $staff->id }})"
                                    class="p-2 text-red-600 hover:text-red-800" title="حذف">
                                    <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                        class="w-8 h-8 dark:hidden" alt="Delete">
                                    <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
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

                                <!-- Print Button -->
                                <button wire:click="print({{ $staff->id }})"
                                    class="p-2 text-green-600 hover:text-green-800" title="پرینت">
                                    <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                        class="w-10 h-10 dark:hidden" alt="Print">
                                    <svg width="30" class="hidden dark:block" height="30" viewBox="0 0 30 30"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                            fill="white" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                            هیچ کارمندی یافت نشد.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $staffs->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-96 p-6">
            <div class="text-center">
                <!-- Warning Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.768 0L4.17 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>

                <!-- Message -->
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    حذف کارمند
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-300 mb-6">
                    آیا مطمئن هستید که می‌خواهید این کارمند را حذف کنید؟
                </p>

                <!-- Buttons -->
                <div class="flex justify-center gap-3">
                    <button wire:click="$set('confirmDeleteId', null)"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        انصراف
                    </button>
                    <button wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
        // Auto-hide alert after 4 seconds
        Livewire.on('show-alert', (data) => {
            setTimeout(() => {
                Livewire.dispatch('clear-alert');
            }, 4000);
        });
    });

    // تابع برای فرمت کردن اعداد فارسی در input
    function formatPersianNumber(input) {
        // تبدیل اعداد انگلیسی به فارسی
        const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        let value = input.value;

        // حذف همه کاراکترهای غیرعددی به جز کاما
        value = value.replace(/[^0-9,]/g, '');

        // اضافه کردن جداکننده هزارگان
        if (value) {
            const parts = value.split(',');
            let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            // تبدیل اعداد انگلیسی به فارسی
            for (let i = 0; i < 10; i++) {
                integerPart = integerPart.replace(new RegExp(englishNumbers[i], 'g'), persianNumbers[i]);
            }

            input.value = integerPart;
        }
    }

    // اعمال فرمت‌دهی روی inputهای مربوط به مبلغ
    document.addEventListener('DOMContentLoaded', function() {
        const salaryInputs = document.querySelectorAll('input[wire\\:model*="formatted_salary"]');

        salaryInputs.forEach(input => {
            // فرمت کردن در زمان تایپ
            input.addEventListener('input', function() {
                formatPersianNumber(this);
            });

            // فرمت کردن در زمان لود اولیه
            if (input.value) {
                formatPersianNumber(input);
            }
        });
    });

    // تاریخ‌نگار فارسی - تابع اصلی
    function createPersianDatePicker(fieldName) {
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
                console.log('DatePicker initialized for:', fieldName);
                
                this.updateYearRange();
                const today = this.getTodayPersian();
                this.currentYear = today.year;
                this.currentMonth = today.month - 1;

                // مقداردهی اولیه از Livewire
                this.$watch('$wire.' + fieldName, (value) => {
                    if (value) {
                        this.setDateFromWire(value);
                    }
                });

                // مقدار اولیه
                const initialValue = this.$wire.get(fieldName);
                if (initialValue) {
                    this.setDateFromWire(initialValue);
                } else {
                    this.selectedDate = today;
                    this.displayDate = this.formatDisplayDate(today);
                }
            },

            setDateFromWire(value) {
                if (!value) return;
                
                const dateParts = value.split('-');
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
                        this.displayDate = `${year}/${String(month).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                        this.currentYear = year;
                        this.currentMonth = month - 1;
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
                return this.gregorianToPersian(
                    today.getFullYear(),
                    today.getMonth() + 1,
                    today.getDate()
                );
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

                // روزهای ماه قبل
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
                console.log('Picker toggled, isOpen:', this.isOpen);
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
                return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`;
            },

            formatDisplayDate(date) {
                if (!date) return '';
                return `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`;
            },

            setToday() {
                const today = this.getTodayPersian();
                this.currentYear = today.year;
                this.currentMonth = today.month - 1;
                this.selectedDate = today;
                this.displayDate = this.formatDisplayDate(today);
                this.$wire.set(fieldName, this.formatDate(today));
            },

            clearDate() {
                this.selectedDate = null;
                this.displayDate = '';
                this.$wire.set(fieldName, '');
                this.closePicker();
            },

            applyDate() {
                if (this.selectedDate) {
                    const formattedDate = this.formatDate(this.selectedDate);
                    this.$wire.set(fieldName, formattedDate);
                    this.closePicker();
                } else {
                    this.setToday();
                }
            }
        };
    }

    // تابع‌های خاص برای هر فیلد
    function fromDatePicker() {
        return createPersianDatePicker('contract_start');
    }

    function toDatePicker() {
        return createPersianDatePicker('contract_end');
    }

    // برای پرینت
    let printListenerRegistered = false;

    document.addEventListener('livewire:init', () => {
        if (printListenerRegistered) return;
        printListenerRegistered = true;

        Livewire.on('print-pdf', (data) => {
            const downloadLink = document.createElement('a');
            downloadLink.href = data.url;
            downloadLink.download = '';
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();

            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = data.url;
            document.body.appendChild(iframe);

            iframe.onload = () => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

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

        /* استایل‌های مودال Date Picker */
        .fixed.inset-0 {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .bg-opacity-75 {
            --tw-bg-opacity: 0.75;
        }

        .flex.items-center.justify-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .inline-block {
            display: inline-block;
        }

        .align-bottom {
            vertical-align: bottom;
        }

        .bg-white {
            background-color: white;
        }

        .dark\:bg-gray-800:is(.dark *) {
            background-color: #1f2937;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .transform {
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .sm\:my-8 {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .sm\:align-middle {
            vertical-align: middle;
        }

        .sm\:max-w-lg {
            max-width: 32rem;
        }

        .sm\:w-full {
            width: 100%;
        }

        /* اسکرول بار */
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

        /* انیمیشن‌ها */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .transition-opacity {
            transition-property: opacity;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .duration-300ms {
            transition-duration: 300ms;
        }
    </style>
</div>