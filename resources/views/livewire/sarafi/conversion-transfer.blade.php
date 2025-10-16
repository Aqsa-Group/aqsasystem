<div>
    <div class="pl-10 pr-10 mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black">درج تبدیل ارز و انتقال از حسابات</h1>
            <h1 class="text-[#8C8C8C] text-[18px]">صفحه درج تبدیل ارز و انتقال از حسابات</h1>
        </div>
    </div>

    <div class="container mx-auto px-4">
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

        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#DC2626] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    {{ session('error') }}
                </h2>
            </div>
        </div>
        @endif

        {{-- کارت‌های ارزها با اسکرول افقی --}}
        <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-full pb-4 p-[12px] h-[264px] rounded-[12px] space-y-2"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            <div class="flex w-full h-[58px] bg-[#2563EB] rounded-[12px]">
                <p class="text-white text-[18px] vazir text-center justify-center flex items-center p-5">موجودی حساب
                    برداشت</p>
            </div>

            <div class="scroll-container overflow-x-auto overflow-y-hidden whitespace-nowrap py-3 pb-12 mt-4 w-full">
                <div class="grid grid-flow-col auto-cols-max gap-4 px-4">
                    @foreach ($currenciesdefault as $currency)
                    <div class="w-[273px] h-[130px] pr-5 pl-5 pt-3 rounded-[12px] mb-5
                @if ($currency['name'] === 'خلاصه بیلانس به دالر') 
                    bg-gradient-to-b from-[#11BEC7] to-[#6371D0]
                @else
                    bg-gradient-to-b from-[#2563EB] to-[#5474BB] 
                @endif text-white">

                        <h1 class="text-[22px] truncate">{{ $currency['name'] }}</h1>
                        <h2 class="text-center text-[28px] mt-1 font-bold">{{ number_format($currency['value']) }}</h2>

                        <button wire:click="showReport" wire:loading.attr="disabled"
                            class="bg-white rounded-[12px] text-[14px] p-1.5 mt-1 text-gray-800 hover:shadow-md transition flex items-center justify-center gap-2 w-full font-medium">
                            <span wire:loading.remove>نمایش گزارش</span>
                            <span wire:loading>در حال انتقال...</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-5 mt-4">

            {{-- فرم تراکنش --}}
            <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div
                    class="flex flex-row justify-between p-[20px] border border-[#8C8C8C] rounded-[12px] flex-wrap items-center">
                    <p class="flex justify-between items-center text-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/pencil.svg') }}" alt="" class="h-6 w-6">
                        <span class="vazir font-semibold">فورم تبدیل ارز و انتقال</span>
                    </p>

                    <button type="button" wire:click="toggleTransactionType"
                        class="rounded-[8px] px-6 py-3 text-white vazir font-semibold text-sm md:text-base
                transition-all duration-300 ease-in-out transform hover:scale-105
                {{ $transactionType === 'خرید' ? 'bg-[#2563EB] hover:bg-[#1D4ED8]' : 'bg-[#DD2424] hover:bg-[#B91C1C]' }}">
                        {{ $transactionType === 'خرید' ? 'خرید ارز از مشتری' : 'فروش ارز به مشتری' }}
                    </button>
                </div>

                {{-- فرم --}}
                <form wire:submit.prevent="submitConversion">

                    {{-- حساب برداشت و دریافت --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- حساب برداشت --}}
                        {{-- حساب برداشت --}}
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب برداشت</label>
                                <div x-data="{
            searchValue: '',
            selectedId: @entangle('withdrawalAccount'),
            customers: @js($customers),
            init() {
                this.updateDisplay();
                
                // گوش دادن به رویداد ویرایش
                $wire.on('edit-mode-activated', (data) => {
                    console.log('Edit mode activated - withdrawal:', data);
                    this.selectedId = data.withdrawalAccount;
                    this.searchValue = data.withdrawalCustomer;
                    setTimeout(() => {
                        this.updateDisplay();
                    }, 100);
                });
                
                // گوش دادن به تغییرات از Livewire
                $wire.on('accountsSwapped', () => {
                    setTimeout(() => {
                        this.updateDisplay();
                    }, 100);
                });
            },
            handleSelect(event) {
                const selected = this.customers.find(
                    c => event.target.value === `${c.account_number} - ${c.fullname}`
                );
                if (selected) {
                    this.selectedId = selected.id;
                    this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                    $wire.selectWithdrawalAccount(selected.id);
                } else {
                    this.selectedId = null;
                    this.searchValue = '';
                    $wire.set('withdrawalAccount', null);
                }
            },
            updateDisplay() {
                if (this.selectedId) {
                    const selected = this.customers.find(c => c.id == this.selectedId);
                    if (selected) {
                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                    }
                }
            }
        }" x-init="init()" class="relative w-full">
                                    <input list="withdrawalCustomersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب برداشت..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">
                                    <datalist id="withdrawalCustomersList">
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                            @endforeach
                                    </datalist>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                    </div>
                                </div>
                                @error('withdrawalAccount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- حساب دریافت --}}
                        {{-- حساب دریافت --}}
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب دریافت</label>
                                <div x-data="{
            searchValue: '',
            selectedId: @entangle('depositAccount'),
            customers: @js($customers),
            init() {
                this.updateDisplay();
                
                // گوش دادن به رویداد ویرایش
                $wire.on('edit-mode-activated', (data) => {
                    console.log('Edit mode activated - deposit:', data);
                    this.selectedId = data.depositAccount;
                    this.searchValue = data.depositCustomer;
                    setTimeout(() => {
                        this.updateDisplay();
                    }, 100);
                });
                
                // گوش دادن به تغییرات از Livewire
                $wire.on('accountsSwapped', () => {
                    setTimeout(() => {
                        this.updateDisplay();
                    }, 100);
                });
            },
            handleSelect(event) {
                const selected = this.customers.find(
                    c => event.target.value === `${c.account_number} - ${c.fullname}`
                );
                if (selected) {
                    this.selectedId = selected.id;
                    this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                    $wire.selectDepositAccount(selected.id);
                } else {
                    this.selectedId = null;
                    this.searchValue = '';
                    $wire.set('depositAccount', null);
                }
            },
            updateDisplay() {
                if (this.selectedId) {
                    const selected = this.customers.find(c => c.id == this.selectedId);
                    if (selected) {
                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                    }
                }
            }
        }" x-init="init()" class="relative w-full">
                                    <input list="depositCustomersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب دریافت..."
                                        class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">
                                    <datalist id="depositCustomersList">
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer['account_number'] }} - {{ $customer['fullname'] }}">
                                            @endforeach
                                    </datalist>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                    </div>
                                </div>
                                @error('depositAccount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    {{-- بخش برداشت --}}
                    <div class="mt-4  rounded-[12px]">

                        <div class="mt-2 flex flex-col lg:flex-row gap-3">
                            {{-- نوع ارز برداشت --}}
                            <div class="lg:w-[191px]">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">ارز برداشت</label>
                                <div class="relative w-full">
                                    <select wire:model="from_currency"
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
                                @error('from_currency')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- مقدار برداشت --}}
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ برداشت</label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="withdrawal_amount" placeholder="0"
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                </div>
                                @if($withdrawalAmountInWords)
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong></strong> {{ $withdrawalAmountInWords }}
                                </div>
                                @endif
                                @error('withdrawal_amount')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- بخش دریافت --}}
                    <div class="mt-4   rounded-[12px] ">

                        <div class="mt-2 flex flex-col lg:flex-row gap-3">
                            {{-- نوع ارز دریافت --}}
                            <div class="lg:w-[191px]">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">ارز دریافت</label>
                                <div class="relative w-full">
                                    <select wire:model="to_currency"
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

                                @error('to_currency')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex-1">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">نرخ ارز</label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="currency_rate" placeholder="0.0000"
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                    @error('currency_rate')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if($currencyRateInWords)
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong></strong> {{ $currencyRateInWords }}
                                </div>
                                @endif
                            </div>


                        </div>
                    </div>



                    {{-- نرخ ارز و تاریخ --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">


                        {{-- مقدار دریافت --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ دریافت</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="received_amount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white bg-gray-100"
                                    readonly />
                            </div>
                            @if($receivedAmountInWords)
                            <div class="mt-2 text-sm text-gray-600">
                                <strong></strong> {{ $receivedAmountInWords }}
                            </div>
                            @endif

                            @error('received_amount')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- تاریخ --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="transaction_date" placeholder="1404/4/20"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                @error('transaction_date')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    {{-- اطلاعات اضافی --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- توسط --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (برداشت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_sender" placeholder="نام مسئول برداشت"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                @error('by_sender')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- توسط دریافت --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (دریافت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_receiver" placeholder="نام مسئول دریافت"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                @error('by_receiver')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- زون --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- زون برداشت --}}
                        <div class="lg:w-[250px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون برداشت</label>
                            <div class="relative">
                                <select wire:model="zone_sender"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="غرب">غرب (هرات، بادغیس، غور، فراه)</option>
                                    <option value="مرکز">مرکز (کابل، پروان، کاپیسا، وردک، لوگر)</option>
                                    <option value="شمال">شمال (بلخ، جوزجان، سرپل، سمنگان، فاریاب)</option>
                                    <option value="شمال‌شرق">شمال‌شرق (کندز، تخار، بدخشان، بغلان)</option>
                                    <option value="جنوب">جنوب (قندهار، ارزگان، زابل، هلمند)</option>
                                    <option value="جنوب‌شرق">جنوب‌شرق (خوست، پکتیا، پکتیکا)</option>
                                    <option value="شرق">شرق (ننگرهار، لغمان، کنر، نورستان)</option>
                                    <option value="جنوب‌غرب">جنوب‌غرب (نیمروز)</option>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                            </div>
                            @error('zone_sender')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- زون دریافت --}}
                        <div class="lg:w-[290px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون دریافت</label>
                            <div class="relative">
                                <select wire:model="zone_receiver"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="غرب">غرب (هرات، بادغیس، غور، فراه)</option>
                                    <option value="مرکز">مرکز (کابل، پروان، کاپیسا، وردک، لوگر)</option>
                                    <option value="شمال">شمال (بلخ، جوزجان، سرپل، سمنگان، فاریاب)</option>
                                    <option value="شمال‌شرق">شمال‌شرق (کندز، تخار، بدخشان، بغلان)</option>
                                    <option value="جنوب">جنوب (قندهار، ارزگان، زابل، هلمند)</option>
                                    <option value="جنوب‌شرق">جنوب‌شرق (خوست، پکتیا، پکتیکا)</option>
                                    <option value="شرق">شرق (ننگرهار، لغمان، کنر، نورستان)</option>
                                    <option value="جنوب‌غرب">جنوب‌غرب (نیمروز)</option>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓">
                                </div>
                            </div>
                            @error('zone_receiver')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- شرح --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح تراکنش</label>
                            <textarea wire:model="description" rows="3" placeholder="شرح کامل تبدیل ارز..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
                            @error('description')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- دکمه‌های نهایی --}}
                    <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-[#2563EB] text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-4 text-white hover:bg-blue-700 transition disabled:opacity-50">
                            @if($editingConversionId)
                            <span wire:loading.remove>ویرایش تبدیل ارز</span>
                            @else
                            <span wire:loading.remove>ثبت تبدیل ارز</span>
                            @endif
                            <span wire:loading>در حال ثبت...</span>
                        </button>
                        <button type="button" wire:click="resetForm" wire:loading.attr="disabled"
                            class="bg-[#DD2424] text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-4 text-white hover:bg-red-700 transition">
                            @if($editingConversionId) انصراف از ویرایش @else انصراف @endif
                        </button>
                    </div>
                </form>
            </div>

            {{-- جدول تراکنش‌های تبدیل ارز --}}
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-full md:w-[250px]">
                            <input type="text" wire:model.live="search" wire:keydown.debounce.500ms="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام،...">

                            <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            @if($search)
                            <button wire:click="$set('search', '')"
                                class="absolute left-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- جدول --}}
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table class="w-[890px] text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead
                                class="bg-[#2B65E5] text-white text-[14px] md:text-[16px] vazir h-[50px] md:h-[60px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-2 py-3 font-bold w-12">#</th>
                                    <th class="px-2 py-3 font-bold w-32">از حساب</th>
                                    <th class="px-2 py-3 font-bold w-32">به حساب</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ برداشت</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ دریافت</th>
                                    <th class="px-2 py-3 font-bold w-24">نرخ ارز</th>
                                    <th class="px-2 py-3 font-bold w-36 text-center">توضیحات</th>
                                    <th class="px-2 py-3 font-bold w-28">تاریخ</th>
                                    <th class="px-2 py-3 font-bold w-32 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversionTransactions as $key => $conversion)
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        {{ $key + 1 }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium w-32">
                                        <div class="truncate" title="{{ $conversion->from_customer_name ?? '-' }}">
                                            {{ $conversion->from_customer_name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium w-32">
                                        <div class="truncate" title="{{ $conversion->to_customer_name ?? '-' }}">
                                            {{ $conversion->to_customer_name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-3  py-3 vazir text-[13px] md:text-[16px] font-medium w-52">
                                        <div class="text-left">
                                            <span class="">{{ number_format($conversion->withdrawal_amount) }} ({{
                                                $this->getCurrencyName($conversion->from_currency) }})</span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[16px]  w-44">
                                        <div class="text-left">
                                            <span class="">{{ number_format($conversion->received_amount) }} ({{
                                                $this->getCurrencyName($conversion->to_currency) }})</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px]  w-44">
                                        {{ number_format($conversion->currency_rate, 2) }}
                                    </td>
                                    <td class="px-2 py-3 vazir text-[13px] md:text-[18px] font-medium w-36">
                                        <div class="text-right truncate" title="{{ $conversion->description }}">
                                            {{ Str::limit($conversion->description, 35) }}
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium text-[16px]">{{ $conversion->transaction_date }}
                                            </div>
                                            <div class="text-gray-500 text-[16px] mt-1">
                                                {{ \Carbon\Carbon::parse($conversion->created_at)->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center w-32">
                                        <div class="flex justify-center gap-2">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="editConversion({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-blue-100"
                                                title="ویرایش">
                                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                                title="حذف">
                                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}"
                                                    class="w-7 h-7" alt="Delete">
                                            </button>

                                            <!-- دکمه پرینت -->
                                            <button wire:click="printTransaction({{ $conversion->id }})"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                                title="پرینت PDF">
                                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}"
                                                    class="w-9 h-9" alt="Print">
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-4 text-center text-gray-500 vazir text-[14px]">
                                        هیچ تراکنش تبدیلی یافت نشد.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- صفحه‌بندی --}}
                @if($conversionTransactions->hasPages())
                <div class="mt-4 px-4">
                    {{ $conversionTransactions->links() }}
                </div>
                @endif
            </div>
             <!-- مودال تأیید حذف -->
    @if ($confirmDeleteId)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="{{ asset('assets/sarafi/all_icon/close.svg') }}" alt="بستن" class="w-4 h-4">
            </button>

            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف تراکنش تبدیل ارز</h1>
            <hr class="bg-[#E1DED3] mt-4 mx-4">
            <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این تراکنش را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                    خیر
                </button>
                <button wire:click="deleteConversion"
                    class="px-12 py-3 bg-[#2563EB] text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                    بلی
                </button>
            </div>
        </div>
    </div>
    @endif
        </div>
    </div>

    <style>
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #f9fafb;
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
            margin-top: 20px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 10px;
            margin: 0 16px 10px 16px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* استایل برای بخش‌های مختلف فرم */
        .form-section {
            border-left: 4px solid #2563EB;
            padding-left: 12px;
        }

        .withdrawal-section {
            border-left-color: #DC2626;
        }

        .deposit-section {
            border-left-color: #059669;
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

    {{-- اسکریپت برای محاسبه خودکار --}}
    <script>
        document.addEventListener('livewire:init', () => {
            // محاسبه خودکار مبلغ دریافت هنگام تغییر مقادیر
            Livewire.on('calculatedReceivedAmount', (amount) => {
                console.log('مبلغ دریافت محاسبه شد:', amount);
            });
        });
    </script>
</div>