<div class="container mx-auto px-4">
    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2563EB] azir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                {{ session('message') }}
            </h2>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-700 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                {{ session('error') }}
            </h2>
        </div>
    </div>
    @endif

    <div class="space-y-4 mb-6">
        <h1 class="text-[24px] font-medium vazir">درج نرخ ارز برای سنجش مفاد و ضرر</h1>
        <h1 class="text-[#8C8C8C]">اضافه ویرایش ارزها برای سنجش مفاد و ضرر</h1>
    </div>
    <hr class="my-6 border-t border-[#D9D9D9] w-full">

    <!-- فرم کامل عرض -->
    <div class="w-full bg-[#F5F5F5] p-[12px] rounded-[12px] h-fit mb-6"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="flex gap-2 border border-[#8C8C8C] rounded-[12px] p-6 mb-4">
            <img src="{{ asset('assets/sarafi/all_icon/exchange-rate.svg') }}" alt="">
            <p>{{ $isEditing ? 'ویرایش قیمت ارز' : 'ثبت قیمت ارز' }}</p>
        </div>

        <form wire:submit.prevent="submit">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1 relative">
                    <label class="block text-[16px] font-medium text-black mb-1 vazir"> ارز اصلی</label>
                    <div class="relative">
                        <select wire:model.live="source_currency"
                            class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer appearance-none pr-3 pl-10">
                            @foreach($currencies as $currency)
                            <option value="{{ $currency['code'] }}">{{ $currency['name_fa'] }}</option>
                            @endforeach
                        </select>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <img src="{{ asset('assets/sarafi/all_icon/arrow-down.svg') }}" alt="↓" class="w-4 h-4">
                        </div>
                    </div>
                    @error('source_currency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                    <input type="text" wire:model="date" placeholder="YYYY/MM/DD"
                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- جدول با چهار نوع نرخ برای هر ارز - ساده شده -->
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse mb-4 min-w-[1000px]">
                    <thead>
                        <tr class="bg-[#2B65E5] text-white">
                            <th class="px-4 py-3 border-l border-white">واحد ارز</th>
                            <th class="px-4 py-3 border-l border-white">خرید نقدی</th>
                            <th class="px-4 py-3 border-l border-white">خرید بانکی</th>
                            <th class="px-4 py-3 border-l border-white">فروش نقدی</th>
                            <th class="px-4 py-3 border-l border-white">فروش بانکی</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $allCurrencies = ['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'];
                        $formCurrencies = array_filter($allCurrencies, function($currency) {
                            return $currency !== $this->source_currency;
                        });
                        @endphp

                        @foreach($formCurrencies as $currencyCode)
                        @php
                        $currencyName = $this->getCurrencyName($currencyCode);
                        @endphp
                        <tr class="border-b">
                            <td class="px-4 py-3 font-bold text-gray-700 bg-gray-50">
                                {{ $currencyName }}
                            </td>
                            
                            <!-- خرید نقدی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.{{ $currencyCode }}.buy_cash"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                @error('formData.'.$currencyCode.'.buy_cash')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            
                            <!-- خرید بانکی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.{{ $currencyCode }}.buy_bank"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                @error('formData.'.$currencyCode.'.buy_bank')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            
                            <!-- فروش نقدی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.{{ $currencyCode }}.sell_cash"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                @error('formData.'.$currencyCode.'.sell_cash')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            
                            <!-- فروش بانکی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.{{ $currencyCode }}.sell_bank"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                @error('formData.'.$currencyCode.'.sell_bank')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-6 mt-6 justify-center items-center text-center">
                <button type="submit"
                    class="bg-[#2563EB] hover:bg-[#1E4FD6] transition-all duration-200 text-[16px] vazir font-semibold rounded-[10px] px-8 sm:px-20 py-3 text-white shadow-md w-full sm:w-auto">
                    {{ $isEditing ? 'بروزرسانی' : 'ثبت' }}
                </button>

                <button type="button" wire:click="cancel"
                    class="bg-[#DD2424] hover:bg-[#B81E1E] transition-all duration-200 text-[16px] vazir font-semibold rounded-[10px] px-8 sm:px-20 py-3 text-white shadow-md w-full sm:w-auto">
                    انصراف
                </button>
            </div>
        </form>
    </div>

  <!-- جدول زیر فرم -->
<div class="w-full flex flex-col bg-[#F5F5F5] p-1 md:p-4 lg:p-6 rounded-[12px] overflow-x-auto mx-auto"
    style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
    <div class="flex gap-2 border border-[#8C8C8C] rounded-[12px] p-6 mb-4">
        <img src="{{ asset('assets/sarafi/all_icon/exchange-rate.svg') }}" alt="">
        <p>جدول قیمت ارز</p>
    </div>

    <!-- بدنه جدول با اسکرول -->
    <div class="flex-1 overflow-x-auto">
        <table class="w-full min-w-[1000px] text-sm md:text-base text-center text-gray-500 dark:text-gray-400 border-collapse">
            <thead class="bg-[#2B65E5] text-white">
                <tr>
                    <th class="px-4 py-3 border-l border-white">ارز مبدأ</th>
                    @php
                    $allCurrencies = ['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'];
                    $tableCurrencies = $allCurrencies; // نمایش همه ارزها
                    @endphp

                    @foreach($tableCurrencies as $currencyCode)
                    @php
                    $currencyName = $this->getCurrencyName($currencyCode);
                    @endphp
                    <th class="px-4 py-3 border-l border-white">{{ $currencyName }}</th>
                    @endforeach
                    <th class="px-4 py-3">تاریخ</th>
                    <th class="px-4 py-3">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr class="bg-transparent dark:bg-gray-800 dark:hover:bg-gray-700 hover:bg-gray-50">
                    <!-- ارز مبدأ -->
                    <td class="px-3 py-2 font-medium border-l bg-blue-50">
                        <span class="font-bold text-blue-700">{{ $this->getCurrencyName($record->source_currency) }}</span>
                        <div class="text-xs text-gray-500 mt-1">نرخ‌ها نسبت به این ارز</div>
                    </td>

                    @foreach($tableCurrencies as $currencyCode)
                        <td class="px-3 py-3 border-l">
                            @if($currencyCode !== $record->source_currency)
                                <div class="space-y-2">
                                    <!-- خرید نقدی -->
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">خرید نقدی:</span>
                                        <span class="font-medium">{{ $record->{$currencyCode . '_buy_cash'} ?? '-' }}</span>
                                    </div>
                                    
                                    <!-- خرید بانکی -->
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">خرید بانکی:</span>
                                        <span class="font-medium">{{ $record->{$currencyCode . '_buy_bank'} ?? '-' }}</span>
                                    </div>
                                    
                                    <!-- فروش نقدی -->
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">فروش نقدی:</span>
                                        <span class="font-medium">{{ $record->{$currencyCode . '_sell_cash'} ?? '-' }}</span>
                                    </div>
                                    
                                    <!-- فروش بانکی -->
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">فروش بانکی:</span>
                                        <span class="font-medium">{{ $record->{$currencyCode . '_sell_bank'} ?? '-' }}</span>
                                    </div>
                                </div>
                            @else
                                <!-- اگر این ارز همان ارز مبدأ باشد، سلول خالی می‌ماند -->
                                <div class="text-center text-gray-300 py-4">
                                    <span class="text-sm">-</span>
                                </div>
                            @endif
                        </td>
                    @endforeach

                    <!-- تاریخ -->
                    <td class="px-3 py-2 font-medium">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($record->created_at)->format('Y/m/d') }}
                    </td>

                    <!-- عملیات -->
                    <td class="py-4">
                        <div class="flex justify-center gap-2">
                            <!-- دکمه ویرایش -->
                            <button wire:click="edit({{ $record->id }})"
                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-blue-100"
                                title="ویرایش">
                                <img src="{{ asset('assets/sarafi/all_icon/edit_table.svg') }}" class="w-5 h-5"
                                    alt="Edit">
                            </button>

                            <!-- دکمه حذف -->
                            <button wire:click="confirmDelete({{ $record->id }})"
                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                title="حذف">
                                <img src="{{ asset('assets/sarafi/all_icon/trash_table.svg') }}" class="w-6 h-6"
                                    alt="Delete">
                            </button>

                            <!-- دکمه پرینت -->
                            <button wire:click="print({{ $record->id }})"
                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                title="پرینت">
                                <img src="{{ asset('assets/sarafi/all_icon/print_table.svg') }}" class="w-7 h-7"
                                    alt="Print">
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    @php
                    $colspan = count($tableCurrencies) + 3; // +3 برای ارز مبدأ، تاریخ و عملیات
                    @endphp
                    <td colspan="{{ $colspan }}" class="px-4 py-8 text-center text-gray-500">
                        هیچ داده‌ای یافت نشد
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- مودال تأیید حذف -->
@if ($confirmDeleteId)
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
    <div
        class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
        <button wire:click="$set('confirmDeleteId', null)"
            class="absolute top-4 right-4 h-6 w-6 flex items-center justify-center">
            <img src="{{ asset('assets/sarafi/all_icon/close.svg') }}" alt="بستن">
        </button>
        <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-4">
            حذف نرخ ارز
        </h1>
        <hr class="bg-[#E1DED3] mt-8">
        <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این نرخ ارز را حذف کنید؟</p>
        <div class="flex justify-center gap-4">
            <button wire:click="$set('confirmDeleteId', null)"
                class="px-20 text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                {{ __('messages.no') ?? 'خیر' }}
            </button>
            <button wire:click="deleteConfirmed"
                class="px-20 py-3 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl transition flex items-center gap-2">
                {{ __('messages.yes') ?? 'بله' }}
            </button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Print functionality
    Livewire.on('print-exchange-rates', () => {
        window.print();
    });

    Livewire.on('print-single-exchange-rate', (exchangeRate) => {
        console.log('Printing:', exchangeRate);
    });

    document.addEventListener('livewire:load', function() {
        // Initialize any date pickers here if needed
    });
</script>
@endpush