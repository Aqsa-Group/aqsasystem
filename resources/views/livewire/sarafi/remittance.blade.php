<div>
    <div class="container mx-auto ">
        <!-- Session Message -->
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

        <!-- Page Header -->
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">ثبت احواله ها</h1>
            <h1 class="text-[#8C8C8C]">صفحه ثبت و ویراش احواله های بانکی</h1>
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- Main Content - Form and Table -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">

            <!-- Remittance Form -->
            <div class="flex flex-col bg-[#F5F5F5] w-[420px] lg:w-[534px] mx-auto p-[12px] h-auto rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Form Header -->
                <div class="flex flex-row gap-4 p-4 p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" alt="" class="h-6 w-6">

                    <p class="flex justify-center items-center text-center">
                        {{ $remittanceId ? 'فورم ویرایش حواله' : 'فورم ثبت اطلاعات حواله' }}
                    </p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="submitRemittance">
                    <!-- Account Number and Currency -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Source Account Number -->
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحساب
                                    مشتری</label>
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
                                }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                    class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">
                                    <datalist id="customersList">
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                            @endforeach
                                    </datalist>
                                    @if(empty($selectedAccount))
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                    </div>
                                    @endif
                                </div>
                                @error('selectedAccount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Currency Type -->
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

                    <!-- Amount and Date -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Amount -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="lg:w-[290px] relative">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" placeholder="YYYY/MM/DD"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                            <svg class="absolute left-3 bottom-3 -translate-y-1/2 pointer-events-none" width="20"
                                height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                <path
                                    d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                    stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />

                                <path
                                    d="M15.6947 13.7H15.7037M15.6947 16.7H15.7037M11.9955 13.7H12.0045M11.9955 16.7H12.0045M8.29431 13.7H8.30329M8.29431 16.7H8.30329"
                                    stroke="#8C8C8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            @error('date')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Time and Tracking Code -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Time -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">ساعت</label>
                            <input type="text" wire:model="clock" placeholder="2:25:20"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                            @error('clock')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tracking Code -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">کد رهگیری</label>
                            <input type="text" wire:model="tracking_code" placeholder="5155221034568"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                            @error('tracking_code')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Source and Destination Banks -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Source Bank -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">بانک مبدا</label>
                            <input type="text" wire:model="from_bank" placeholder="سپه"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                            @error('from_bank')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Destination Bank -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">بانک مقصد</label>
                            <input type="text" wire:model="to_bank" placeholder="صادرات"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                            @error('to_bank')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                          <!-- Source and Destination Account Numbers -->
                    <div class="flex-1 flex gap-2 mt-2">
                        <!-- Source Account Number (Display only) -->
                        <div class="lg:w-[440px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">شماره حساب مبدا</label>
                            <div class="relative">
                                <div
                                    class="flex items-center bg-[#F5F5F5] border border-[#8C8C8C] rounded-[12px] h-[60px] px-3">
                                    <input dir="ltr" type="text" wire:model="source_account_last_four" maxlength="4"
                                        placeholder="1234"
                                        class="w-12 h-full bg-transparent text-center border-0 outline-none font-mono"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <span class="text-gray-500 vazir whitespace-nowrap mr-2">- xxxx - xxxx - xxxx</span>
                                </div>
                            </div>
                            @error('source_account_last_four')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Destination Account -->
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحساب مقصد</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: @entangle('toAccount'),
                                customers: @js($customers),
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.selectToAccount(selected.id);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('toAccount', null);
                                    }
                                },
                                updateDisplay() {
                                    const selected = this.customers.find(c => c.id === this.selectedId);
                                    this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                }
                            }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                class="relative w-full">
                                <input list="customersList2" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب مقصد..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="customersList2">
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                        @endforeach
                                </datalist>
                                @if(empty($toAccount))
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                                @endif
                            </div>
                            @error('toAccount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Zone and Beneficiary -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Zone -->
                        <div class="lg:w-[250px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">درج زون ها</label>
                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="{{ Auth::guard('sarafi')->user()->zone }}">
                                        {{ Auth::guard('sarafi')->user()->zone }}
                                    </option>
                                </select>
                            </div>
                            @error('zone')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Beneficiary Name -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir"> نام کارت گیرنده</label>
                            <input type="text" wire:model="giver_name" placeholder="مجید مرتضی"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                            @error('giver_name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

              
                    <!-- Remittance Description -->
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح حواله..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mt-2 flex gap-3">
                        <div class="w-full">
                            <div x-data="{ files: [] }"
                                x-on:drop.prevent="files = $event.dataTransfer.files; $wire.upload('remittance_image', files[0])"
                                x-on:dragover.prevent
                                class="w-full h-[150px] p-3 rounded-[12px] border border-dashed focus:ring-2 bg-white border-[#112080] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                                x-on:click="$refs.fileInput.click()">
                                <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}" alt="آپلود"
                                    class="w-12 h-12 mb-2">
                                <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">فایل را اینجا وارد
                                    کنید یا بکشید</h1>
                                <input type="file" class="hidden" x-ref="fileInput"
                                    x-on:change="$wire.upload('remittance_image', $event.target.files[0])">
                            </div>
                            @error('remittance_image')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 py-4 justify-center items-center text-center flex-wrap">
                        <button type="submit"
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            {{ $remittanceId ? 'بروزرسانی' : 'ثبت' }}
                        </button>

                        @if(!$remittanceId)
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[14px] text-center justify-center vazir font-semibold rounded-[8px]  flex px-12 py-3 text-white">
                            ثبت و چاپ
                        </button>
                        @endif

                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            {{ $remittanceId ? 'لغو ویرایش' : 'انصراف' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Remittances Table -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[150px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Table Header -->
                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">حواله های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <!-- Selected Customer Filter -->
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

                        <!-- Search Box -->
                        <div class="relative w-full">
                            <input type="text" wire:model.live="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            @if($search)
                            <button wire:click="clearSearchAndFilter"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif

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

                <!-- Table -->
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
                                    <th class="px-4 py-4 font-bold w-32">گیرنده</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-32">وضعیت</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                    <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($remittances as $key => $remittance)
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        {{ $remittance->customer->fullname ?? '-' }}
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        {{ $remittance->recipient->fullname ?? $remittance->giver_name }}
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        {{ number_format($remittance->amount) }}
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        {{ collect($currencies)->firstWhere('code', $remittance->currency)['name_fa'] ??
                                        $remittance->currency }}
                                    </td>
                                    <td>
                                        @if ($remittance->state===0)
                                        <span class="text-red-500">در انتظار تایید</span>
                                        @else
                                        <span class="text-green-500">تاییده شده</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            <p class="text-sm">کد رهگیری: {{ $remittance->tracking_code }}</p>
                                            <p class="text-sm">زون: {{ $remittance->zone }}</p>
                                            <p class="text-sm">تفصیلات: {{ $remittance->description }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">
                                                {{ explode(' ',$remittance->date)[0] }}
                                            </div>
                                            <div class="text-gray-500 text-sm mt-1">
                                                {{ $remittance->clock }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- Edit Button -->
                                            <button wire:click="edit({{ $remittance->id }})"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- Delete Button -->
                                            <button wire:click="confirmDelete({{ $remittance->id }})"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-8 h-8" alt="Delete">
                                            </button>

                                            <!-- Print Button -->
                                            <button wire:click="print({{ $remittance->id }})"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="پرینت">
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
                                        هیچ حواله برای این مشتری یافت نشد
                                        @else
                                        هیچ حواله ای یافت نشد
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

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteId)
    @php
    $remittance = \App\Models\Sarafi\Remittances::find($confirmDeleteId);
    $isApproved = $remittance && $remittance->state == 1;
    @endphp
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6 text-center animate-fadeIn border border-gray-200 relative">
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute right-2     top-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6 right-0 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="mb-4">
                @if($isApproved)
                <svg class="w-16 h-16 mx-auto right-0 text-red-500 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800 mb-2">حذف حواله تایید شده</h2>
                <p class="text-gray-600 mb-4">این حواله قبلاً تایید شده است. آیا مطمئن هستید می‌خواهید آن را حذف کنید؟
                </p>
                <p class="text-sm text-orange-600 bg-orange-50 p-2 rounded-lg">
                    ⚠️ توجه: این عمل باعث برگشت تمام تراکنش‌ها و تغییرات مربوطه خواهد شد.
                </p>
                @else
                <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                    حذف حــــواله</h1>
                <hr class="bg-[#E1DED3] mt-8">
                <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                    حــــواله را حذف کنید؟</p>
                @endif
            </div>

            @if($isApproved)
            <div class="mb-4">
                <p class="text-sm text-gray-500 text-right">
                    عملیات برگشت شامل:
                </p>
                <ul class="text-sm text-gray-600 text-right space-y-1 mt-2">
                    <li>• کاهش موجودی صندوق بانکی</li>
                    <li>• تنظیم مجدد موجودی مشتریان</li>
                </ul>
            </div>
            @endif

            <div class="flex justify-center gap-3  items-center text-center">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-16 py-3 bg-[#2563EB] text-center text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    انصراف
                </button>
                <button wire:click="deleteConfirmed"
                    class="px-16 py-3  {{ $isApproved ? 'bg-red-600 hover:bg-red-700' : 'bg-[#DD2424] hover:bg-red-700' }} text-white text-sm text-center font-medium rounded-lg transition-colors flex items-center gap-2">
                    {{ $isApproved ? 'حذف و برگشت' : 'حذف' }}
                </button>
            </div>
        </div>
    </div>
    @endif

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
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            padding-left: 1rem;
        }

        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        /* در Firefox */
        input[list]::-moz-list-button {
            display: none !important;
        }

        /* در Edge جدید */
        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }
    </style>
</div>


<!-- Event Alert -->
@push('script')
<script>
    window.addEventListener('report-alert', event => {
        alert(event.detail.message);
    });
</script>
@endpush