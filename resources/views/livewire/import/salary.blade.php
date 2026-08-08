<div>
    <!-- پیام‌های سیستم -->
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 40000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                {{ session('message') }}
            </h2>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 40000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                {{ session('error') }}
            </h2>
        </div>
    </div>
    @endif

    <!-- کارت‌های آماری -->
    <div class="space-y-6">
        <!-- کارت‌های سالانه -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-0 pt-1">
            <!-- کارت ۱: کل معاش سالانه -->
            <div
                class="bg-gradient-to-br from-rose-100 to-rose-200 border-l-4 border-rose-500 text-rose-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">کل معاش سالانه</h3>
                    <div class="bg-rose-500 p-2 rounded-full">
                        <i class="fa-solid fa-money-bill-wave text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ number_format($salaryCards['total_salary']) }} افغانی</div>
                    <div class="text-sm mt-2">معاش سالانه کارمند</div>
                </div>
            </div>

            <!-- کارت ۲: کل مبلغ پرداختی -->
            <div
                class="bg-gradient-to-br from-green-100 to-green-200 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">کل مبلغ پرداختی</h3>
                    <div class="bg-green-500 p-2 rounded-full">
                        <i class="fa-solid fa-money-bill-trend-up text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ number_format($salaryCards['total_paid']) }} افغانی</div>
                    <div class="text-sm mt-2">مجموع پرداخت‌های انجام شده</div>
                </div>
            </div>

            <!-- کارت ۳: مانده معاش سالانه -->
            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200 border-l-4 border-blue-500 text-blue-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">مانده معاش سالانه</h3>
                    <div class="bg-blue-500 p-2 rounded-full">
                        <i class="fa-solid fa-scale-balanced text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ number_format($salaryCards['remaining_salary']) }} افغانی</div>
                    <div class="text-sm mt-2">مبلغ باقی‌مانده برای پرداخت</div>
                </div>
            </div>

            <!-- کارت ۴: درصد پرداخت سالانه -->
            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200 border-l-4 border-purple-500 text-purple-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">درصد پرداخت سالانه</h3>
                    <div class="bg-purple-500 p-2 rounded-full">
                        <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $salaryCards['percentage'] }}%</div>
                    <div class="text-sm mt-2">درصد پرداخت شده از کل معاش</div>
                </div>
            </div>
        </div>

        <!-- کارت‌های ماهانه -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-0 pt-1">
            <!-- کارت ۵: معاش پایه ماهانه -->
            <div
                class="bg-gradient-to-br from-orange-100 to-orange-200 border-l-4 border-orange-500 text-orange-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">معاش پایه ماهانه</h3>
                    <div class="bg-orange-500 p-2 rounded-full">
                        <i class="fa-solid fa-calendar-day text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ number_format($salaryCards['monthly_salary']) }} افغانی</div>
                    <div class="text-sm mt-2">معاش پایه ماهانه کارمند</div>
                </div>
            </div>

            <!-- کارت ۶: پرداختی ماه جاری -->
            <div
                class="bg-gradient-to-br from-teal-100 to-teal-200 border-l-4 border-teal-500 text-teal-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">پرداختی 30 روز گذشته</h3>
                    <div class="bg-teal-500 p-2 rounded-full">
                        <i class="fa-solid fa-money-check text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ number_format($salaryCards['monthly_paid']) }} افغانی</div>
                    <div class="text-sm mt-2">پرداختی در 30 روز گذشته</div>
                </div>
            </div>

            <!-- کارت ۷: مانده معاش ماهانه -->
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">مانده معاش ماهانه</h3>
                    <div class="bg-indigo-500 p-2 rounded-full">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ number_format($salaryCards['monthly_remaining']) }} افغانی</div>
                    <div class="text-sm mt-2">مبلغ باقی‌مانده این ماه</div>
                </div>
            </div>

            <!-- کارت ۸: درصد پرداخت ماهانه -->
            <div
                class="bg-gradient-to-br from-pink-100 to-pink-200 border-l-4 border-pink-500 text-pink-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">درصد پرداخت ماهانه</h3>
                    <div class="bg-pink-500 p-2 rounded-full">
                        <i class="fa-solid fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $salaryCards['monthly_percentage'] }}%</div>
                    <div class="text-sm mt-2">درصد پرداخت شده این ماه</div>
                </div>
            </div>
        </div>
    </div>

    <!-- فرم و جدول -->
    <div class="flex flex-col lg:flex-row gap-4 mt-4">
        <!-- فرم پرداخت معاش -->
        <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[474px] p-4 h-auto rounded-[12px] space-y-4"
            style="box-shadow: 0px 4px 4px 0px #00000040;">

            <!-- عنوان فرم -->
            <div class="flex flex-row justify-between p-4 border border-[#8C8C8C] rounded-[12px] mb-4">
                <p class="flex justify-center items-center text-center gap-2">
                    <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" alt="" class="h-6 w-6">
                    {{ $salaryId ? 'فورم ویرایش معاش' : 'فورم ثبت پرداخت معاش' }}
                </p>
            </div>

            <!-- فرم -->
            <form wire:submit.prevent="submitSalary" class="space-y-6">
                <!-- انتخاب کارمند -->
                <div>
                    <label class="block text-[16px] font-medium text-black mb-2 vazir">انتخاب کارمند</label>
                    <div x-data="{
                            searchValue: '',
                            selectedId: @entangle('selectedStaff'),
                            staffs: @js($staffs),
                            handleSelect(event) {
                                const selected = this.staffs.find(c => event.target.value === c.name);
                                if (selected) {
                                    this.selectedId = selected.id;
                                    this.searchValue = selected.name;
                                    $wire.selectStaff(selected.id);
                                    $wire.set('search', selected.name);
                                } else {
                                    this.selectedId = null;
                                    this.searchValue = '';
                                    $wire.set('selectedStaff', null);
                                    $wire.set('search', '');
                                }
                            },
                            updateDisplay() {
                                const selected = this.staffs.find(c => c.id === this.selectedId);
                                this.searchValue = selected ? selected.name : '';
                            }
                        }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                        class="relative w-full">
                        <input list="staffsList" x-model="searchValue" @change="handleSelect" id="select"
                            placeholder="جستجو یا انتخاب کارمند..."
                            class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                            autocomplete="off">
                        <datalist id="staffsList">
                            @foreach ($staffs as $staff)
                            <option value="{{ $staff->name }}">
                                {{ $staff->name }} - {{ number_format($staff->salary) }} افغانی
                            </option>
                            @endforeach
                        </datalist>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                        </div>
                    </div>
                    @error('selectedStaff')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- مقدار و تاریخ -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">مقدار (افغانی)</label>
                        <div class="relative w-full">
                            <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                افغانی
                            </div>
                        </div>
                        @if($amountInWords)
                        <p class="text-sm text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                        @endif
                        @error('amount')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="lg:w-[290px]">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">تاریخ</label>
                        <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                            class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 cursor-pointer" />
                    </div>
                </div>

                <!-- توضیحات -->
                <div>
                    <label class="block text-[16px] font-medium text-black mb-2 vazir">شرح پرداخت</label>
                    <textarea wire:model="description" rows="3" placeholder="توضیحات پرداخت معاش..."
                        class="w-full p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    @error('description')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- دکمه‌ها -->
                <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">
                    <button type="submit"
                        class="bg-[#61B138] text-[18px] vazir font-semibold rounded-[8px] px-20 py-3 text-white hover:bg-green-700 transition-colors">
                        {{ $salaryId ? 'بروزرسانی' : 'ثبت' }}
                    </button>

                    <button type="button" wire:click="resetForm"
                        class="bg-[#DD2424] text-[18px] vazir font-semibold rounded-[8px] px-20 py-3 text-white hover:bg-red-700 transition-colors">
                        {{ $salaryId ? 'لغو ویرایش' : 'انصراف' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- جدول پرداخت‌های معاش -->
        <div class="flex-1 flex flex-col bg-[#F5F5F5] p-4 rounded-[12px] mt-8 lg:mt-0 md:mx-auto lg:mx-auto w-[540px] mb-5 md:w-[510px] lg:w-[200px]"
            style="box-shadow: 0px 4px 4px 0px #00000040;">

            <!-- هدر جدول -->
            <div
                class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-4 rounded-[12px] mb-4 gap-3">
                <h1 class="text-lg md:text-xl lg:text-2xl vazir">پرداخت‌های معاش ثبت شده</h1>

                <div class="flex items-center gap-3">
                    <!-- فیلتر کارمند انتخاب شده -->
                    @if($selectedStaffId)
                    @php
                    $selectedStaff = \App\Models\Import\Staff::find($selectedStaffId);
                    @endphp
                    <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                        <span class="text-blue-700 vazir">فیلتر: {{ $selectedStaff->name ?? '' }}</span>
                        <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                            ✕
                        </button>
                    </div>
                    @endif

                    <!-- جستجو -->
                    <div class="relative w-full md:w-[302px]">
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-3 text-sm md:text-base pr-10"
                            placeholder="جستجو بر اساس نام...">

                        <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                        @if($search)
                        <button wire:click="clearSearchAndFilter"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- جدول -->
            <div class="overflow-x-auto w-full">
                <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                    <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                        <thead
                            class="bg-gradient-to-br from-indigo-400 to-indigo-500 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0">
                            <tr>
                                <th class="px-4 py-4 font-bold w-16">#</th>
                                <th class="px-4 py-4 font-bold w-48">نام کارمند</th>
                                <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaries as $key => $salary)
                            <tr class="border-b border-[#D9D9D9] bg-transparent hover:bg-gray-50 transition-colors">
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center">
                                    {{ $key + 1 }}
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium">
                                    {{ $salary->staff->name ?? '-' }}
                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium">
                                    {{ number_format($salary->amount) }} <span
                                        class="text-sm text-gray-500">افغانی</span>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center">
                                    <p class="text-sm">{{ $salary->description ?? 'بدون توضیح' }}</p>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center">
                                    <div class="whitespace-nowrap">
                                        <div class="font-medium">{{ explode(' ', $salary->date)[0] }}</div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            {{ \Carbon\Carbon::parse($salary->created_at)->format('h:i A') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <button wire:click="edit({{ $salary->id }})"
                                            class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-blue-100 transition-colors"
                                            title="ویرایش">
                                            <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                class="w-7 h-7" alt="Edit">
                                        </button>
                                        <button wire:click="confirmDelete({{ $salary->id }})"
                                            class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-red-100 transition-colors"
                                            title="حذف">
                                            <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                class="w-8 h-8" alt="Delete">
                                        </button>
                                        <button wire:click="print({{ $salary->id }})"
                                            class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-green-100 transition-colors"
                                            title="پرینت">
                                            <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                class="w-10 h-10" alt="Print">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-8 text-lg">
                                    @if($selectedStaffId)
                                    هیچ پرداخت معاش برای این کارمند یافت نشد
                                    @else
                                    هیچ پرداخت معاش یافت نشد
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

    <!-- مودال تأیید حذف -->
    @if ($confirmDeleteId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-white p-6 rounded-[12px] shadow-xl w-[90%] max-w-[653px] text-center animate-fadeIn z-50 border border-[#E1DED3] relative">
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-4 top-4 w-6 h-6 flex items-center justify-center text-gray-500 hover:text-gray-700">
                ✕
            </button>
            <h1 class="text-2xl text-black shabnam font-medium mt-2">حذف پرداخت معاش</h1>
            <hr class="border-[#E1DED3] my-4">
            <p class="mb-6 text-xl shabnam">آیا مطمئن هستید می خواهید این پرداخت معاش را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam py-3 bg-[#DD2424] rounded-xl hover:bg-red-700 transition-colors">
                    خیر
                </button>
                <button wire:click="deleteConfirmed"
                    class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2">
                    بلی
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- اسکریپت‌ها -->
    <script>
        document.addEventListener('livewire:load', function() {
                if (typeof kamaDatepicker !== 'undefined') {
                    kamaDatepicker('datePicker', {
                        buttonsColor: "blue",
                        forceFarsiDigits: true,
                        markToday: true,
                        markHolidays: true,
                        gotoToday: true,
                        highlightSelectedDay: true
                    });
                }
            });
    </script>

    <!-- استایل‌های سفارشی -->
    <style>
        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        input[list]::-moz-list-button {
            display: none !important;
        }

        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .flex-header-container {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
    <script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('download-pdf', (event) => {
            // event[0] شامل داده‌های ارسال‌شده است
            const data = event[0];
            const link = document.createElement('a');
            link.href = 'data:application/pdf;base64,' + data.content;
            link.download = data.filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
</script>
</div>