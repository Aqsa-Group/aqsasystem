<div>
    <div class="pr-6 pl-6">
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">فورم ژورنال و حسابات</h1>
            <h1 class="text-[#8C8C8C]">صفحه درج چندین حساب برای مشتریان و یا حسابات خاص</h1>
        </div>
        <div class="flex flex-col lg:flex-row gap-10 mt-4">

            {{-- فرم تراکنش --}}
            <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div class="flex flex-row justify-between p-[20px] border  border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <p class="flex justify-center items-center text-center">
                        <img src="{{ asset('assets/sarafi/all_icon/pencil.svg') }}" alt="" class="h-6 w-6"> فورم ثبت
                        ژورنال
                    </p>

                </div>

                {{-- فرم --}}
                <form wire:submit.prevent="submitTransaction">

                    {{-- شماره حساب و افزودن مشتری --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- شماره حساب --}}
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">انتخاب حساب
                                    بردگی</label>

                                <div x-data="{
                                        searchValue: '',
                                        selectedId: @entangle('selectedAccount'),
                                        customers: ,
                                        handleSelect(event) {
                                            const selected = this.customers.find(c => event.target.value === `${c.account_number} - ${c.fullname}`);
                                            if (selected) {
                                                this.selectedId = selected.id;
                                                this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                            }
                                        },
                                        updateDisplay() {
                                            const selected = this.customers.find(c => c.id === this.selectedId);
                                            this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                        }
                                    }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                    class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        autocomplete="off">

                                    <datalist id="customersList">

                                        <option value=" احمد-۲۳۶۴۵۶۲۳۴۵۶۳۲۵">
                                            احمد-۲۳۶۴۵۶۲۳۴۵۶۳۲۵
                                        </option>

                                    </datalist>

                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                    </div>
                                </div>

                                @error('selectedAccount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                    </div>

                    {{-- مقدار و نوع ارز --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ پول</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>

                        {{-- نوع ارز --}}
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                        @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                        class="w-4 h-4">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- شرح --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح تراکنش..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>

                        </div>
                    </div>


                    {{-- رسبد --}}

                    {{-- شماره حساب و افزودن مشتری --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- شماره حساب --}}
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">انتخاب حساب
                                    رسیدگی</label>

                                <div x-data="{
                                        searchValue: '',
                                        selectedId: @entangle('selectedAccount'),
                                        customers: ,
                                        handleSelect(event) {
                                            const selected = this.customers.find(c => event.target.value === `${c.account_number} - ${c.fullname}`);
                                            if (selected) {
                                                this.selectedId = selected.id;
                                                this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                            }
                                        },
                                        updateDisplay() {
                                            const selected = this.customers.find(c => c.id === this.selectedId);
                                            this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                        }
                                    }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                    class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        autocomplete="off">

                                    <datalist id="customersList">

                                        <option value=" احمد-۲۳۶۴۵۶۲۳۴۵۶۳۲۵">
                                            احمد-۲۳۶۴۵۶۲۳۴۵۶۳۲۵
                                        </option>

                                    </datalist>

                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                    </div>
                                </div>

                                @error('selectedAccount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                    </div>

                    {{-- مقدار و نوع ارز --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ پول</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>

                        {{-- نوع ارز --}}
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                       @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                        class="w-4 h-4">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- شرح --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح تراکنش..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>

                        </div>
                    </div>

                    {{-- درج زون ها و تاریخ --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">

                        {{-- تاریخ --}}
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="1404/4/4"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                        {{-- سند --}}
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">سند</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="34912"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>

                    {{-- برداشت و دریافت--}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">

                        {{-- برداشت ها --}}
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">برداشت ها</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="4000"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                        {{-- دریافت ها --}}
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">دریافت ها</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="8000"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>





                    {{-- دکمه‌های نهایی --}}
                    <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">

                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-[95px] py-4 text-white">ثبت
                            و چاپ</button>
                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-[95px] py-4 text-white">انصراف</button>
                    </div>
                </form>
            </div>
            {{-- جدول تراکنش‌ها --}}
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای جدول: عنوان و جستجو --}}
                <div
                    class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">ترانزکشن های ثبت شده</h1>

                    <div class="flex items-center gap-3">




                        <div class="relative w-full md:w-[302px]">
                            <!-- Input جستجوی زنده با wire:model.live -->
                            <input type="text" wire:model.live="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">






                        </div>
                    </div>
                </div>

                {{-- جدول --}}
                <div class="overflow-x-auto w-full ml-4">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-[#2B65E5] w-full dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-6 py-4 font-bold w-16">#</th>
                                    <th class="px-6 py-4 font-bold w-48">حساب</th>
                                    <th class="px-6 py-4 font-bold w-32">مبلغ</th>
                                    <th class="px-6 py-4 font-bold w-32">واحد</th>
                                    <th class="px-8 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-6 py-4 font-bold w-40">تاریخ</th>
                                    <th class="px-6 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        1
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>



                                {{-- --}}


                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        2
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>


                                {{-- --}}


                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        3
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>



                                {{-- --}}


                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        4
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>



                                {{-- --}}


                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        5
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>




                                {{-- --}}


                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        6
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>




                                {{-- --}}



                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        7
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        بسم الله جان
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        20,000
                                    </td>
                                    <td class="px-1 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        افغانی
                                    </td>
                                    <td
                                        class="px-1  py-4 flex flex-col vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <span>
                                            توسط | زون : غرب
                                        </span>
                                        <span>
                                            تفصیلات : رسید شد توسط بسم الله جان
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            1404/4/4
                                        </div>
                                    </td>

                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                 rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>



                                            <!-- دکمه پرینت -->
                                            <button wire:click="" class="w-12 h-12 flex items-center justify-center  
                                                  rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>

                                </tr>

                            </tbody>


                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>