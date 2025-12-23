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
            <h1 class="text-[#8C8C8C] dark:text-white">صفحه ثبت و ویراش احواله های بانکی</h1>
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- Main Content - Form and Table -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">

            <!-- Remittance Form -->
            <div class="flex flex-col dark:bg-black dark:border-white dark:border  bg-[#F5F5F5] w-[420px] lg:w-[534px] mx-auto p-[12px] h-auto rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Form Header -->
                <div class="flex flex-row gap-4 p-4 p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" alt="" class="h-6 w-6">

                    <p class="flex justify-center items-center text-center dark:text-white">
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
                                <label
                                    class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نمبرحساب
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
                                        class="w-full dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white  h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
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
                                    class="w-full dark:text-white dark:bg-black dark:placeholder:text-white dark:border-white   h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500   appearance-none">
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
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full dark:bg-black  dark:border-white  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>
                            @if($amountInWords)
                            <p class="text-sm dark:text-white text-blue-600 mt-2 vazir">{{ $amountInWords }}</p>
                            @endif
                            @error('amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="lg:w-[290px] relative">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500  cursor-pointer" />

                            @error('date')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Time and Tracking Code -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Time -->
                        <div class="lg:w-[290px]">
                            <label
                                class="block text-[16px] dark:text-white font-medium text-black mb-1 vazir">ساعت</label>
                            <input type="text" wire:model="clock" placeholder="2:25:20"
                                class="w-full   h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white  cursor-pointer" />
                            @error('clock')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tracking Code -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">کد
                                رهگیری</label>
                            <input type="text" wire:model="tracking_code" placeholder="5155221034568"
                                class="w-full  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer" />
                            @error('tracking_code')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Source and Destination Banks -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- Source Bank -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">بانک
                                مبدا</label>
                            <input type="text" wire:model="from_bank" placeholder="سپه"
                                class="w-full  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer" />
                            @error('from_bank')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>27
                            @enderror
                        </div>
                        <!-- Destination Bank -->
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">بانک
                                مقصد</label>
                            <input type="text" wire:model="to_bank" placeholder="صادرات"
                                class="w-full   h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer" />
                            @error('to_bank')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Source and Destination Account Numbers -->
                    <div class="flex-1 flex gap-2 mt-2">
                        <!-- Source Account Number (Display only) -->
                        <div class="lg:w-[440px]">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شماره
                                حساب مبدا</label>
                            <div class="relative">
                                <div
                                    class="flex items-center dark:bg-black bg-[#F5F5F5] border border-[#8C8C8C] rounded-[12px] h-[60px] px-3">
                                    <input dir="ltr" type="text" wire:model="source_account_last_four" maxlength="4"
                                        placeholder="1234"
                                        class="w-12 dark:bg-black dark:border-white  dark:text-white  h-full bg-transparent text-center border-0 outline-none font-mono"
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
                                    class="w-full dark:bg-black dark:border-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">درج زون
                                ها</label>
                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white appearance-none"
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
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir"> نام کارت
                                گیرنده</label>
                            <input type="text" wire:model="giver_name" placeholder="مجید مرتضی"
                                class="w-full   h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-gray-600 dark:text-white cursor-pointer" />
                            @error('giver_name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Remittance Description -->
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح حواله..."
                                class="w-full  p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white resize-none"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- File Upload -->
                    <div class="mt-2 flex gap-3">
                        <div class="w-full dark:bg-black dark:border-white">
                            <div x-data="{
            files: [],
            isUploading: false,
            uploadedFileName: @entangle('remittance_image').defer,
            init() {
                // گوش دادن به رویدادهای Livewire برای آپلود
                window.addEventListener('upload:start', () => {
                    this.isUploading = true;
                });
                window.addEventListener('upload:finish', () => {
                    this.isUploading = false;
                });
                window.addEventListener('upload:error', () => {
                    this.isUploading = false;
                });
            }
        }" x-on:drop.prevent="
            files = $event.dataTransfer.files; 
            $wire.upload('remittance_image', files[0], () => {
                uploadedFileName = files[0]?.name;
            })
        " x-on:dragover.prevent :class="{
            'border-green-500 bg-green-50': uploadedFileName && !isUploading,
            'border-blue-500 bg-blue-50': isUploading,
            'border-[#112080] bg-white': !uploadedFileName && !isUploading
        }" class="w-full h-[150px] p-3 rounded-[12px] border-2 border-dashed focus:ring-2 dark:bg-black dark:border-white dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 relative"
                                x-on:click="$refs.fileInput.click()">

                                <!-- حالت آپلود در حال انجام -->
                                <template x-if="isUploading">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 dark:bg-black dark:border-white dark:border h-12 mb-2 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                        </div>
                                        <h1 class="font-vazir text-blue-600 dark:text-blue-300 text-[16px]">در حال
                                            آپلود...</h1>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">لطفا منتظر
                                            بمانید</p>
                                    </div>
                                </template>

                                <!-- حالت آپلود موفق -->
                                <template x-if="!isUploading && uploadedFileName">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 h-12 mb-2 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h1 class="font-vazir text-green-600 dark:text-green-300 text-[16px]">آپلود موفق
                                        </h1>
                                        <p class="font-vazir text-gray-600 dark:text-gray-300 text-sm mt-1 truncate max-w-full"
                                            x-text="uploadedFileName"></p>
                                        <button type="button"
                                            x-on:click.stop="uploadedFileName = null; $wire.set('remittance_image', null)"
                                            class="mt-2 text-red-500 hover:text-red-700 text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            حذف فایل
                                        </button>
                                    </div>
                                </template>

                                <!-- حالت اولیه (بدون آپلود) -->
                                <template x-if="!isUploading && !uploadedFileName">
                                    <div class="flex flex-col items-center">
                                        <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}" alt="آپلود"
                                            class="w-12 h-12 mb-2">
                                        <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">فایل را
                                            اینجا وارد کنید یا بکشید</h1>
                                        <p class="font-vazir text-gray-500 dark:text-gray-400 text-sm mt-1">فرمت‌های
                                            مجاز: JPG, PNG, webpp</p>
                                    </div>
                                </template>

                                <input type="file" class="hidden" x-ref="fileInput" accept=".jpg,.jpeg,.png,.pdf,.webp"
                                    x-on:change="
                       if ($event.target.files[0]) {
                           $wire.upload('remittance_image', $event.target.files[0], () => {
                               uploadedFileName = $event.target.files[0]?.name;
                           });
                       }
                   ">
                            </div>

                            <!-- نمایش خطا -->
                            @error('remittance_image')
                            <div class="mt-2 flex items-center gap-2 text-red-500 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror



                            <!-- نمایش فایل ذخیره شده (در حالت ویرایش) -->
                            @if($remittance_image && is_string($remittance_image))
                            <div
                                class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-blue-700 text-sm">فایل قبلاً آپلود شده</span>
                                </div>
                                <a href="{{ Storage::url($remittance_image) }}" target="_blank"
                                    class="text-blue-500 hover:text-blue-700 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    مشاهده
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 py-4 justify-center items-center text-center flex-wrap">
                        <button type="submit" wire:loading.attr='disabled' wire:target='submitRemittance'
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">


                            <span wire:loading.remove wire:target="submitRemittance">
                                {{ $remittanceId ? 'بروزرسانی' : 'ثبت' }}
                            </span>

                            <span wire:loading wire:target="submitRemittance"
                                class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                در حال ثبت
                            </span>
                        </button>

                        @if(!$remittanceId)
                        <button type="button" wire:click="submitAndPrint" wire:loading.attr='disabled'
                            wire:target='submitAndPrint'
                            class="bg-[#2563EB] text-[14px] text-center justify-center vazir font-semibold rounded-[8px]  flex px-12 py-3 text-white">
                            <span wire:loading.remove wire:target='submitAndPrint'>
                                ثبت و چاپ

                            </span>

                            <span wire:loading wire:target="submitAndPrint"
                                class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                در حال ثبت و چاپ
                            </span>
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
            <div class="flex-1 flex flex-col dark:bg-black dark:border dark:border-white dark:text-white bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[150px] mx-auto"
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
                                class="border dark:bg-black dark:border dark:border-white dark:placeholder:text-white border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">


                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute  dark:hidden left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">
                            <svg width="24" height="24"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


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
                                class="bg-[#2B65E5] dark:bg-blue-500 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                <div class="overflow-x-auto w-full">
                                <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                                    <table
                                        class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="bg-[#2B65E5]  text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
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
                                            <tr
                                                class="text-black border-b dark:text-white border-[#D9D9D9] bg-transparent">
                                                <td
                                                    class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
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
                                                    {{ collect($currencies)->firstWhere('code',
                                                    $remittance->currency)['name_fa'] ??
                                                    $remittance->currency }}
                                                </td>
                                                <td>
                                                    @if ($remittance->state===0)
                                                    <span class="text-red-500">در انتظار تایید</span>
                                                    @else
                                                    <span class="text-green-500">تاییده شده</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                                    <div class="space-y-1 text-right">
                                                        <p class="text-sm">کد رهگیری: {{ $remittance->tracking_code }}
                                                        </p>
                                                        <p class="text-sm">زون: {{ $remittance->zone }}</p>
                                                        <p class="text-sm">تفصیلات: {{ $remittance->description }}</p>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                                    <div class="whitespace-nowrap">
                                                        <div class="font-medium">
                                                            {{ explode(' ',$remittance->date)[0] }}
                                                        </div>
                                                        <div class="text-gray-500 dark:text-white  text-sm mt-1">
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
                                                                class="w-7 h-7 dark:hidden" alt="Edit">

                                                            <svg width="22" height="22" class="hidden dark:block"
                                                                viewBox="0 0 22 22" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M10.082 1.83325H8.2487C3.66536 1.83325 1.83203 3.66659 1.83203 8.24992V13.7499C1.83203 18.3333 3.66536 20.1666 8.2487 20.1666H13.7487C18.332 20.1666 20.1654 18.3333 20.1654 13.7499V11.9166"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M14.7027 2.76832L7.4794 9.99165C7.2044 10.2667 6.9294 10.8075 6.8744 11.2017L6.48023 13.9608C6.33357 14.96 7.0394 15.6567 8.03857 15.5192L10.7977 15.125C11.1827 15.07 11.7236 14.795 12.0077 14.52L19.2311 7.29665C20.4777 6.04999 21.0644 4.60165 19.2311 2.76832C17.3977 0.934987 15.9494 1.52165 14.7027 2.76832Z"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M13.668 3.8042C14.2821 5.99503 15.9963 7.7092 18.1963 8.33253"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button wire:click="confirmDelete({{ $remittance->id }})"
                                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                            title="حذف">
                                                            <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                                class="w-8 h-8 dark:hidden" alt="Delete">
                                                            <svg width="24" height="24" class="hidden dark:block"
                                                                viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M18.8484 9.13989L18.1984 19.2099C18.0884 20.7799 17.9984 21.9999 15.2084 21.9999H8.78844C5.99844 21.9999 5.90844 20.7799 5.79844 19.2099L5.14844 9.13989"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M10.3281 16.5H13.6581" stroke="white"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M9.5 12.5H14.5" stroke="white"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>

                                                        </button>

                                                        <!-- Print Button -->
                                                        <button wire:click="print({{ $remittance->id }})"
                                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                            title="پرینت">
                                                            <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                                class="w-10 h-10 dark:hidden" alt="Print">
                                                            <svg width="30" class="hidden dark:block" height="30"
                                                                viewBox="0 0 30 30" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
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
                    <p class="text-gray-600 mb-4">این حواله قبلاً تایید شده است. آیا مطمئن هستید می‌خواهید آن را حذف
                        کنید؟
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