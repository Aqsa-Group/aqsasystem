<div>
    <div class="container mx-auto px-4">
        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false,  4000)" x-show="show" x-transition
            class="p-4 mb-4 h-[103px] w-full absolute flex justify-start items-center top-0 left-0 bg-[#2B65E5] vazir">

            <h2 class="text-white vazir text-[18px]">
                {{ session('message') }}
            </h2>
        </div>
        @endif



        {{-- کارت‌های ارزها با اسکرول افقی --}}
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 -mt-5">
            @foreach ($currenciesdefault as $currency)
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
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

        {{-- فرم و جدول کنار هم --}}
        <div class="flex flex-col lg:flex-row gap-10 mt-4">

            {{-- فرم تراکنش --}}
            <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-[820px] rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div class="flex flex-row justify-between p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <p class="flex justify-center items-center text-center">
                        <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" alt="" class="h-6 w-6"> فورم
                    </p>
                    <div class="flex gap-4 flex-wrap">
                        <button class="bg-[#DD2424] rounded-[8px] p-[10px] text-white vazir font-semibold">توقف
                            پیامک</button>
                        <button wire:click="toggleTransactionType" class="rounded-[8px] p-[10px] text-white vazir font-semibold
                                transition-colors duration-500 ease-in-out
                                {{ $transactionType === 'برد' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                            {{ $transactionType === 'برد' ? 'رسید (دریافت صندوق)' : 'برد (برداشت صندوق)' }}
                        </button>
                    </div>
                </div>

                {{-- فرم --}}
                <form wire:submit.prevent="submitTransaction">

                    {{-- شماره حساب و افزودن مشتری --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- شماره حساب --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبر حساب</label>
                            <div class="relative w-full">
                                <select wire:model="selectedAccount" id="selectCustomer"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500">
                                    <option value="">انتخاب حساب</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->account_number }} - {{ $customer->fullname }}
                                    </option>
                                    @endforeach
                                </select>
                                

                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                            </div>
                            @error('selectedAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- افزودن مشتری --}}
                        <div class="flex items-end lg:w-[191px]">
                            <button type="button" wire:click="addCustomer"
                                class="flex items-center justify-center gap-2 w-full h-[60px] rounded-[12px] bg-transparent border-[#8C8C8C] border text-black font-vazir text-[16px] font-medium transition">
                                افزودن مشتری
                                <img src="{{ asset('assets/sarafi/all_icon/customer-add.svg') }}" alt="افزودن"
                                    class="w-6 h-6">
                            </button>
                        </div>
                    </div>

                    {{-- مقدار و نوع ارز --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            @if($amountInWords)
                            <p class="text-sm text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- نوع ارز --}}
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}">{{ $c['name_fa'] }}</option>
                                    @endforeach
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط</label>
                            <div class="relative w-full">
                                <input list="customerList" wire:model="byUser" placeholder="توسط کی...."
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                            @error('byUser')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- درج زون ها و تاریخ --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- درج زون ها --}}
                        <div class="lg:w-[250px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">درج زون ها</label>
                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">

                                    <option value="">انتخاب زون</option>
                                    <!-- غرب -->
                                    <option value="غرب">غرب (هرات، بادغیس، غور، فراه)</option>
                                    <!-- مرکز -->
                                    <option value="مرکز">مرکز (کابل، پروان، کاپیسا، وردک، لوگر)</option>

                                    <!-- شمال -->
                                    <option value="شمال">شمال (بلخ، جوزجان، سرپل، سمنگان، فاریاب)</option>

                                    <!-- شمال‌شرق -->
                                    <option value="شمال‌شرق">شمال‌شرق (کندز، تخار، بدخشان، بغلان)</option>



                                    <!-- جنوب -->
                                    <option value="جنوب">جنوب (قندهار، ارزگان، زابل، هلمند)</option>

                                    <!-- جنوب‌شرق -->
                                    <option value="جنوب‌شرق">جنوب‌شرق (خوست، پکتیا، پکتیکا)</option>

                                    <!-- شرق -->
                                    <option value="شرق">شرق (ننگرهار، لغمان، کنر، نورستان)</option>

                                    <!-- جنوب‌غرب -->
                                    <option value="جنوب‌غرب">جنوب‌غرب (نیمروز)</option>

                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                            </div>
                            @error('zone')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- تاریخ --}}
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>

                    {{-- شرح تراکنش --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح تراکنش..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- آپلود فایل --}}
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <div x-data="{ files: [] }"
                                x-on:drop.prevent="files = $event.dataTransfer.files; $wire.upload('file', files[0])"
                                x-on:dragover.prevent
                                class="w-full h-[150px] p-3 rounded-[12px] border border-dashed focus:ring-2 bg-white border-[#112080] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                                x-on:click="$refs.fileInput.click()">
                                <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}" alt="آپلود"
                                    class="w-12 h-12 mb-2">
                                <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">فایل را اینجا وارد
                                    کنید یا بکشید</h1>
                                <input type="file" class="hidden" x-ref="fileInput"
                                    x-on:change="$wire.upload('file', $event.target.files[0])">
                            </div>
                            @error('file')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- دکمه‌های نهایی --}}
                    <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">
                        <button type="submit"
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">ثبت</button>
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">ثبت
                            و چاپ</button>
                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">انصراف</button>
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
                        {{-- نمایش نام مشتری انتخاب شده --}}
                        @if($selectedCustomerId)
                        @php
                        $selectedCustomer = \App\Models\Sarafi\Customer::find($selectedCustomerId);
                        @endphp
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: {{ $selectedCustomer->fullname ?? '' }}</span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        @endif

                        <div class="relative w-full md:w-[302px]">
                            <!-- Input جستجوی زنده با wire:model.live -->
                            <input type="text" wire:model.live="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            <!-- دکمه پاک کردن جستجو -->
                            @if($search)
                            <button wire:click="clearSearchAndFilter"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif

                            <!-- لیست پیشنهادات -->
                            @if($search && count($filteredCustomers) > 0 && !$selectedCustomerId)
                            <ul
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                @foreach($filteredCustomers as $customer)
                                <li wire:click="selectCustomer({{ $customer->id }})"
                                    class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between items-center">
                                    <span>{{ $customer->fullname }}</span>
                                    <span class="text-gray-500 text-sm">{{ $customer->account_number }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- جدول --}}
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-[#2B65E5] dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">#</th>
                                    <th class="px-4 py-4 font-bold w-48">نام مشتری</th>
                                    <th class="px-4 py-4 font-bold w-32">معامله</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                    <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $transaction)
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $transaction->customer->fullname ?? '-' }}
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <span
                                            class="px-3 py-1 rounded-full text-[16px] {{ $transaction->type === 'رسید' ? ' text-green-800' : 'text-red-800' }}">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        {{ number_format($transaction->amount) }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        {{ collect($currencies)->firstWhere('code', $transaction->currency)['name_fa']
                                        ?? $transaction->currency }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            <p class="text-sm">توسط: {{ $transaction->by }}</p>
                                            <p class="text-sm">زون: {{ $transaction->zone }}</p>
                                            <p class="text-sm">تفصیلات: {{ $transaction->description }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">{{ $transaction->date }}</div>
                                            <div class="text-gray-500 text-sm mt-1">
                                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="edit({{ $transaction->id }})" class="w-12 h-12 flex items-center justify-center  
                    rounded-full transition-colors" title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete({{ $transaction->id }})"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>

                                            <!-- مودال تأیید حذف -->
                                            @if ($confirmDeleteId)
                                            <div
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                                <div
                                                    class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] pb-[21px] rounded-[12px] shadow-xl w-[653px] h-[252.7267608642578px] text-center animate-fadeIn z-50 border-[1px]  border-[#E1DED3]">
                                                    <button wire:click="$set('confirmDeleteId', null)"
                                                        class="flex right-0 h-4 w-4"><img
                                                            src="{{ asset('assets/sarafi/all_icon/close.svg') }}"
                                                            alt=""></button>
                                                    <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                                                        حذف ترانزکشــــــــــن</h1>
                                                    <hr class="bg-[#E1DED3] mt-8">
                                                    <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                                                        ترانزکشن را حذف کنید؟</p>
                                                    <div class="flex justify-center gap-4">
                                                        <button wire:click="$set('confirmDeleteId', null)"
                                                            class="px-20  text-white text-xl shabnam-fd py-4 bg-[#DD2424] rounded-xl transition">
                                                            {{ __('messages.no') }}
                                                        </button>
                                                        <button wire:click="deleteConfirmed"
                                                            class="px-20 py-4 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl  transition flex items-center gap-2">
                                                            {{ __('messages.yes') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif



                                            <!-- دکمه پرینت -->
                                            <button wire:click="print({{ $transaction->id }})" class="w-12 h-12 flex items-center justify-center  
                    rounded-full transition-colors" title="پرینت">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-10 h-10" alt="Print">
                                            </button>
                                        </div>
                                    </td>


                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        @if($selectedCustomerId)
                                        هیچ تراکنشی برای این مشتری یافت نشد
                                        @else
                                        هیچ تراکنشی یافت نشد
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
            scrollbar-color: #e5e7eb #f9fafb;
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
        #selectCustomer {
    appearance: none;          /* برای مرورگرهای مدرن */
    -webkit-appearance: none;  /* برای Chrome, Safari */
    -moz-appearance: none;     /* برای Firefox */
    background: transparent;   /* اطمینان از شفاف بودن پس‌زمینه */
    padding-left: 1rem;        /* اگر لازم باشه فاصله داخلی برای متن */
}

    </style>
</div>