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


        {{-- کارت‌های ارزها با اسکرول افقی --}}

        <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-full p-[12px] h-[264px] rounded-[12px] space-y-2"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            <div class="flex w-full h-[58px] bg-[#2563EB] rounded-[12px] ">
                <p class="text-white text-[18px] vazir text-center justify-center flex items-center p-5  ">موجودی فرد</p>
            </div>

            <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 -mt-5 justify-center items-center flex">
                <!-- در کارت‌های ارزها -->
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

                        <button wire:click="showReport" wire:loading.attr="disabled"
                            class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800 hover:shadow-md transition flex items-center justify-center gap-2">
                            <span wire:loading.remove>نمایش گزارش</span>
                            <span wire:loading>
                                در حال انتقال...
                            </span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

        </div>


        <div class="flex flex-col lg:flex-row gap-10 mt-4">

            {{-- فرم تراکنش --}}
            <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                {{-- بالای فرم: فورم و دکمه‌ها --}}
                <div class="flex flex-row justify-between p-[20px] border  border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <p class="flex justify-between items-center text-center">
                        <img src="{{ asset('assets/sarafi/all_icon/pencil.svg') }}" alt="" class="h-6 w-6">  
                       فورم تبدیل ارز و انتقال
                    </p>

                       <button wire:click="toggleTransactionType" class="rounded-[8px] p-[10px] text-white vazir font-semibold
                                transition-colors duration-500 ease-in-out
                                {{ $transactionType === 'خرید' ? 'bg-[#2563EB]' : 'bg-[#DD2424]' }}">
                            {{ $transactionType === 'خرید' ? 'خرید ارز از مشتری' : 'فروش ارز از مشتری' }}
                        </button>


 


                </div>

                {{-- فرم --}}
                <form wire:submit.prevent="submitTransaction">

                    {{-- شماره حساب و افزودن مشتری --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- شماره حساب --}}
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir"> حساب
                                    برداشت</label>

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

                    {{-- مقدار برداشت و نوع ارز --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                      

                        {{-- نوع ارز --}}
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">واحد</label>
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

                          {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ برداشت</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>


                        
                    </div>


                       {{-- نرخ ارز و   تاریخ--}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                      

                        {{-- نرخ ارز --}}
                        <div class="lg:w-[320px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نرخ اررز</label>
                            <div class="relative w-full">
                               <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>

                          {{-- تاریخ --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <div class="relative w-[250px]">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="1404/4/20"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>


                        
                    </div>
                    
                       {{-- مقدا دریاقت و نوع ارز --}}
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                      

                        {{-- نوع ارز --}}
                        <div class="lg:w-[191px]">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">واحد</label>
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

                          {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ دریافت</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>


                        
                    </div>



                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        {{-- شماره حساب --}}
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir"> حساب
                                    دریافت</label>

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

                     <div class="mt-2 flex flex-col lg:flex-row gap-3">
                      

                         <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir"> توسط</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="برداشت مبلغ"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>

                          {{-- مقدار --}}
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="برداشت مبلغ"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>

                        </div>


                        
                    </div>



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


                        <div class="lg:w-[290px]">
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

                      
                    </div>



                    




                        

                

                   
              
                    {{-- شرح --}}
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح تراکنش..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>

                        </div>
                    </div>

                

                 





                    {{-- دکمه‌های نهایی --}}
                    <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">

                        <button type="button" wire:click="submitAndPrint"
                            class="bg-[#2563EB] text-[16px] vazir font-semibold rounded-[8px] px-[92px] py-4 text-white">
 ثبت و تبادله</button>
                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-[92px] py-4 text-white">انصراف</button>
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
                    <div class="max-h-[680px] overflow-y-auto min-w-[790px]">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-[#2B65E5] w-full dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-6 py-4 font-bold w-16">#</th>
                                    <th class="px-6 py-4 font-bold w-48">معامله</th>
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
                                           برد
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
                                        رسید
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
                                        رسید
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
                                      رسید
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
                                      برد
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



    {{-- استایل اسکرول --}}
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
    </style>

</div>