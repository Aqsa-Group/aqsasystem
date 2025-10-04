<div>
    <div class="container mx-auto px-4">

        {{-- کارت‌های ارزها --}}
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3">
            @foreach ($currencies as $currency)
            <div class="inline-block align-top ml-4 last:ml-0">
                <div class="flex flex-col h-[149px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px]
                        @if ($currency['name'] === 'خلاصه بیلانس به دالر') 
                            bg-gradient-to-b from-[#11BEC7] to-[#6371D0]
                        @else
                            bg-gradient-to-b from-[#2563EB] to-[#5474BB] 
                        @endif">

                    <h1 class="text-[24px] text-white">{{ $currency['name'] }}</h1>
                    <h2 class="text-center text-[30px] text-white mt-2">{{ $currency['value'] }}</h2>

                    <button wire:click="showReport('{{ $currency['name'] }}')"
                        class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800 hover:shadow-md transition">
                        نمایش گزارش
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex gap-10 ">
            {{-- فرم تراکنش --}}
            <div class="flex flex-col bg-[#F5F5F5] mt-4 w-[574px] h-[858px] p-[12px] rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div class="flex flex-row justify-between p-[10px] border border-[#8C8C8C] rounded-[12px]">
                    <p class="flex justify-center items-center text-center">
                        <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" alt="" class="h-6 w-6"> فورم
                    </p>
                    <div class="flex gap-4">
                        <button class="bg-[#DD2424] rounded-[8px] p-[10px] text-white vazir font-semibold">توقف
                            پیامک</button>
                        <button class="bg-[#2563EB] rounded-[8px] p-[10px] text-white vazir font-semibold">رسید (دریافت
                            صندوق)</button>
                    </div>
                </div>

                {{-- فرم --}}
                <form action="">

                    {{-- شماره حساب و افزودن مشتری --}}
                    <div class="mt-2 flex gap-3">
                        {{-- شماره حساب --}}
                        <div>
                            <label class="block text-[16px] font-medium text-black dark:text-gray-300 mb-1 vazir">
                                نمبر حساب
                            </label>
                            <div class="relative w-[353px]">
                                <select  wire:model="role" class=" w-[353px] h-[60px] p-3 rounded-[12px] border focus:ring-2 
                                   bg-transparent border-[#8C8C8C] focus:ring-blue-500 
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                   @foreach ($customer as $customers )
                                <option value="">{{ $customers->account_number }} - {{ $customers->fullname }}</option>
                                       
                                   @endforeach

                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                            </div>
                            @error('role')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- افزودن مشتری --}}
                        <div class="flex items-end">
                             <button type="submit"
                                class="flex items-center justify-center gap-2 w-[191px] h-[60px] rounded-[12px] bg-transparent border-[#8C8C8C] border text-black font-vazir text-[16px] font-medium transition">
                                افزودن مشتری
                                <img src="{{ asset('assets/sarafi/all_icon/customer-add.svg') }}" alt="افزودن"
                                    class="w-6 h-6">
                         

                            </button>
                           </a>
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- مقدار و نوع ارز --}}
                    <div class="mt-2 flex gap-3">
                        {{-- مقدار --}}
                        <div>
                            <label class="block text-[16px] font-medium text-black dark:text-gray-300 mb-1 vazir">
                                مقدار
                            </label>
                            <div class="relative w-[353px]">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-[353px] h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value
                                   .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                                   .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d))
                                   .replace(/[^0-9]/g, '')" />
                            </div>

                            {{-- معادل فارسی مقدار --}}
                            @if($amountInWords)
                            <p class="text-sm text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif

                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- نوع ارز --}}
                        <div>
                            <label class="block text-[16px] font-medium text-black dark:text-gray-300 mb-1 vazir">
                                نوع ارز
                            </label>
                            <div class="relative w-[191px]">
                                <select wire:model="currency"
                                    class="w-[191px] h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    <option value="usd">دالر</option>
                                    <option value="afn">افغانی</option>
                                    <option value="pkr">کلدار</option>
                                    <option value="eur">یورو</option>
                                    <option value="try">لیره</option>
                                    <option value="irr">تومان</option>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                        class="w-4 h-4">
                                </div>
                            </div>

                            @error('currency')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- توسط --}}
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <label class="block text-[16px] font-medium text-black dark:text-gray-300 mb-1 vazir">
                                توسط
                            </label>
                            <div class="relative w-full">
                                <select wire:model="role"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 
                                   bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                   @foreach ($customer as $customers )
                                   <option value="">{{ $customers->fullname }}</option>
                                       
                                   @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓"
                                        class="w-4 h-4">
                                </div>
                            </div>
                            @error('role')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- درج زون ها و تاریخ --}}
                    <div class="mt-2 flex gap-3">
                        {{-- درج زون ها --}}
                        <div class="w-[250px]">
                            <label class="block text-[16px] font-medium text-black dark:text-gray-300 mb-1 vazir">
                                درج زون ها
                            </label>
                            <div class="relative">
                                <select wire:model="role"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 
                                   bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                    <option value="">هرات (دفتر هرات کابل)</option>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                            </div>
                            @error('role')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- تاریخ --}}
                        <div class="w-[290px]">
                            <label class="block text-[16px] font-medium text-black dark:text-gray-300 mb-1 vazir">
                                تاریخ
                            </label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                    </div>

                    {{-- شرح تراکنش --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="byUser" rows="3" placeholder="شرح تراکنش..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
                            @error('byUser')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- آپلود فایل --}}
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <div x-data="{ files: [] }"
                                x-on:drop.prevent="files = $event.dataTransfer.files; $wire.upload('byUser', files[0])"
                                x-on:dragover.prevent
                                class="w-full h-[150px] p-3 rounded-[12px] border border-dashed focus:ring-2 
                                    bg-white border-[#112080] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white 
                                    flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                                x-on:click="$refs.fileInput.click()">
                                <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}" alt="آپلود"
                                    class="w-12 h-12 mb-2">
                                <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">
                                    فایل را اینجا وارد کنید یا بکشید
                                </h1>
                                <input type="file" class="hidden" x-ref="fileInput"
                                    x-on:change="$wire.upload('byUser', $event.target.files[0])">
                            </div>
                            @error('byUser')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- دکمه‌های نهایی --}}
                    <div class="flex gap-4 p-4 justify-center items-center text-center">
                        <button
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">ثبت</button>
                        <button
                            class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">ثبت
                            و چاپ</button>
                        <button
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">انصراف</button>
                    </div>

                </form>
            </div>

            <div class="flex flex-col bg-[#F5F5F5] mt-4 w-full h-[858px]  rounded-[12px] space-y-2 p-[12px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                <div class="flex justify-between items-center border border-[#8C8C8C] p-[12px] rounded-[12px]">
                    <h1 class="text-2xl vazir">ترانزکشن های ثبت شده</h1>
                    <div class="flex relative ">
                        <input type="text" class="border border-[#8C8C8C] w-[302px] h-[51px] bg-transparent rounded-[12px] p-3"
                            placeholder="جستجو....">
                        <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                            class="absolute left-2 top-2">
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-5">

                        <!-- هدر جدول -->
                        <thead class="bg-[#2B65E5] dark:bg-gray-700 text-white text-[18px] vazir h-[67px]"
                            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                            <tr>
                                <th class="px-6 py-6 font-bold">
                                    <span class="border border-white h-2 w-5 px-3 rounded-lg">#</span>
                                </th>
                                <th class="px-6 py-6 font-bold">معامله</th>
                                <th class="px-6 py-6 font-bold">مبلغ</th>
                                <th class="px-6 py-6 font-bold">واحد</th>
                                <th class="px-6 py-6 font-bold text-center">توضیحات</th>
                                <th class="px-6 py-6 font-bold">تاریخ</th>
                                <th class="px-6 py-6 font-bold text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>





                            </tr>

                            {{-- 2 --}}
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">2</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>
                            </tr>


                            {{-- 3 --}}
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">3</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>
                            </tr>


                            {{-- 4 --}}
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">4</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>
                            </tr>



                            {{-- 5 --}}
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>
                            </tr>


                            {{-- 6 --}}
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">6</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>
                            </tr>

                            {{-- 7 --}}
                            <tr class=" text-black border-b border-[#D9D9D9] bg-transparent pt-4 ">
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">7</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">برد</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">5000</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">افغانی</td>
                                <td class="px-3 py-2 vazir text-[16px] font-medium ">
                                    <p class="text-center text-[16px] vazir">توسط: خودش</p>
                                    <p class="text-center text-[16px] vazir">زون: هرات</p>
                                    <p class="text-center text-[16px] vazir">تفصیلات: رسید شد توسط بسم الله جان</p>
                                </td>

                                <td class="px-3 py-2 vazir text-[16px] font-medium ">1404/7/11</td>

                                <td class="px-6 py-4 flex justify-center gap-1">
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                            class="w-10 h-10" alt="Edit">
                                    </button>
                                    <button wire:click="" class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                            class="w-10 h-10" alt="Delete">
                                    </button>
                                    <button class="px-2 py-1">
                                        <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                            class="w-14 h-14" alt="Print">
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>


            </div>


        </div>

    </div>

    {{-- Event Alert --}}
    <script>
        window.addEventListener('report-alert', event => {
            alert(event.detail.message);
        });
    </script>

    {{-- Scrollbar Style --}}
    <style>
        .scroll-container {
            scrollbar-width: thin;
            /* فایرفاکس */
            scrollbar-color: #e5e7eb #f9fafb;
            /* رنگ شَست و بَک‌گراند */
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</div>