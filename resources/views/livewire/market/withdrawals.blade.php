<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Notifications -->
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-400  bg-[#184d6c] vazir">
        <div style="margin-right: 296px" class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px] text-center align-middle">
                {{ session('message') }}
            </h2>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-400  bg-red-800 vazir">
        <div style="margin-right: 296px" class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px] text-center align-middle">
                {{ session('error') }}
            </h2>
        </div>
    </div>
    @endif
    <!-- ==================== دکمه باز کردن مودال ==================== -->
    <div class="px-4 pb-2">
        <button wire:click="openModal"
            class="bg-[#184d6c] text-white px-6 py-3 rounded-xl font-medium shadow-lg transition vazir">
            <i class="fa-solid fa-plus ml-2"></i>
            ثبت برداشت جدید (Draft)
        </button>
    </div>

    <!-- ==================== مودال مدیریت پیش‌نویس‌ها ==================== -->
    @if($showModal)

    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- هدر مودال -->
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">خطا!</strong>
                <span class="block sm:inline">لطفاً همه فیلدهای ضروری را پر کنید.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="sticky top-0 bg-[#184d6c] p-4 rounded-t-2xl flex justify-between items-center z-10">
                <h3 class="text-lg font-bold vazir text-white dark:text-gray-200 mb-4">
                    {{ $editingDraftId ? 'ویرایش پیش‌نویس' : 'ثبت پیش‌نویس جدید' }}
                </h3>
                <button wire:click="closeModal" class="text-white hover:bg-white/20 p-2 rounded-lg transition">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- ========== فرم ثبت پیش‌نویس ========== -->
                <!-- ========== فرم ثبت پیش‌نویس ========== -->
                <div
                    class="bg-[#80aec9] dark:bg-yellow-900/10 rounded-xl p-4 border border-yellow-200 dark:border-yellow-700">


                    <form wire:submit.prevent="submitDraft" class="space-y-4">
                        <!-- توضیحات (با قابلیت جمع‌آوری مبالغ) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 vazir">
                                توضیحات برداشت *

                            </label>
                            <textarea wire:model.live="draftDescription" rows="8"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-yellow-500 bg-white dark:bg-gray-700 vazir"
                                placeholder="مثال:&#10;1- توسط  احمد بابت خرجی چاشت برده شد مبلغ 320 افغانی 2- توسط محمپ  بابت خرید میوه برده شد مبلغ 600 افغانی"></textarea>
                            @error('draftDescription') <span class="text-red-500 text-xs vazir">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-2 ">
                            <!-- مبلغ کل (فقط نمایشی) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 vazir">
                                    مبلغ کل (جمع خودکار)
                                </label>
                                <div
                                    class="w-full h-12 px-4 border rounded-xl bg-gray-100 dark:bg-gray-600 flex items-center text-lg font-bold text-gray-800 dark:text-gray-200 vazir">
                                    {{ number_format($draftTotalAmount) }} افغانی
                                </div>
                                @error('draftTotalAmount') <span class="text-red-500 text-xs vazir">{{ $message
                                    }}</span>
                                @enderror
                            </div>

                            <!-- نوع برداشت -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 vazir">نوع
                                    برداشت
                                    *</label>
                                <select wire:model="draftType"
                                    class="w-full h-12 px-4 border rounded-xl focus:ring-2 focus:ring-[#184d6c] bg-white dark:bg-gray-700 vazir">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($this->expansesTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('draftType') <span class="text-red-500 text-xs vazir">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- تاریخ -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 vazir">تاریخ
                                    *</label>
                                <input type="text" wire:model="draftDate"
                                    class="w-full h-12 px-4 border rounded-xl focus:ring-2 focus:ring-yellow-500 bg-white dark:bg-gray-700 vazir"
                                    placeholder="YYYY/MM/DD">
                                @error('draftDate') <span class="text-red-500 text-xs vazir">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        <!-- دکمه‌های فرم -->
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 bg-[#184d6c] hover:from-yellow-600 hover:to-amber-700 text-white py-3 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-regular fa-floppy-disk ml-2"></i>
                                {{ $editingDraftId ? 'بروزرسانی پیش‌نویس' : 'ذخیره پیش‌نویس' }}
                            </button>
                            @if($editingDraftId)
                            <button type="button" wire:click="cancelEditDraft"
                                class="flex-1 bg-gray-400 hover:bg-gray-500 text-white py-3 rounded-xl font-medium transition vazir">
                                <i class="fa-solid fa-times ml-2"></i>
                                لغو
                            </button>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- ========== لیست پیش‌نویس‌ها ========== -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold vazir text-gray-800 dark:text-gray-200">
                            <i class="fa-solid fa-list ml-2"></i>
                            لیست پیش‌نویس‌ها ({{ $drafts->count() }})
                        </h3>
                        @if($drafts->count() > 0)
                        <button wire:click="finalizeAllDrafts"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition vazir">
                            <i class="fa-solid fa-check-double ml-1"></i>
                            ثبت همه
                        </button>
                        @endif
                    </div>

                    @if($drafts->isEmpty())
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 vazir">
                        <i class="fa-regular fa-file-lines text-3xl mb-2 block"></i>
                        هیچ پیش‌نویسی ثبت نشده است.
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 vazir">
                                <tr>
                                    <th class="px-4 py-2 text-right">نوع</th>
                                    <th class="px-4 py-2 text-right">ارز</th>
                                    <th class="px-4 py-2 text-right">مبلغ</th>
                                    <th class="px-4 py-2 text-right">گیرنده</th>
                                    <th class="px-4 py-2 text-right">تاریخ</th>
                                    <th class="px-4 py-2 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drafts as $draft)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-2 vazir">{{ $draft->expanses_type }}</td>
                                    <td class="px-4 py-2">{{ $draft->currency }}</td>
                                    <td class="px-4 py-2">{{ number_format($draft->amount) }}</td>
                                    <td class="px-4 py-2">
                                        @if($draft->staff)
                                        {{ $draft->staff->fullname }}
                                        @elseif($draft->customer)
                                        {{ $draft->customer->fullname }}
                                        @endif
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($draft->created_at)->format('Y/m/d') }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-1">
                                            <button wire:click="editDraft({{ $draft->id }})"
                                                class="text-blue-600 hover:text-blue-800 p-1">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDeleteDraft({{ $draft->id }})"
                                                class="text-red-600 hover:text-red-800 p-1">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                            <button wire:click="finalizeDraft({{ $draft->id }})"
                                                class="text-green-600 hover:text-green-800 p-1">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- ==================== مودال حذف یکپارچه ==================== -->
    @if($confirmDeleteId)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-center vazir text-gray-900 dark:text-gray-100">تأیید حذف</h3>
            <p class="text-center text-gray-600 dark:text-gray-400 my-4 vazir">
                آیا از حذف این {{ $confirmDeleteIsDraft ? 'پیش‌نویس' : 'برداشت' }} اطمینان دارید؟ این عمل غیرقابل بازگشت
                است.
            </p>
            <div class="flex gap-3">
                <button wire:click="deleteWithdrawal"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl transition vazir">
                    بله، حذف کن
                </button>
                <button wire:click="$set('confirmDeleteId', null)"
                    class="flex-1 bg-gray-400 hover:bg-gray-500 text-white py-2 rounded-xl transition vazir">
                    انصراف
                </button>
            </div>
        </div>
    </div>
    @endif
    {{--
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4">
        <!-- Today's Withdrawals -->
        <div
            class="bg-gradient-to-br from-rose-100 to-rose-200 dark:from-rose-900/30 dark:to-rose-800/30 border-l-4 border-rose-500 text-rose-800 dark:text-rose-200 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های امروز</h3>
                <div class="bg-rose-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-day text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-rose-700 dark:text-rose-300 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['today'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- This Week's Withdrawals -->
        <div
            class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 border-l-4 border-green-500 text-green-800 dark:text-green-200 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های این هفته</h3>
                <div class="bg-green-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-week text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-green-700 dark:text-green-300 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['week'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- This Month's Withdrawals -->
        <div
            class="bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 border-l-4 border-blue-500 text-blue-800 dark:text-blue-200 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های این ماه</h3>
                <div class="bg-blue-500 p-2 rounded-full">
                    <i class="fa-solid fa-calendar-alt text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700 dark:text-blue-300 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['month'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Total Withdrawals -->
        <div
            class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 border-l-4 border-purple-500 text-purple-800 dark:text-purple-200 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold vazir">برداشت‌های کلی</h3>
                <div class="bg-purple-500 p-2 rounded-full">
                    <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['AFN' => 'افغانی', 'USD' => 'دالر', 'EUR' => 'یورو', 'IRR' => 'تومان'] as $currency => $label)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-purple-700 dark:text-purple-300 vazir">{{ $label }}:</span>
                    <span class="text-lg font-bold vazir">{{ number_format($withdrawalStats['total'][$currency])
                        }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row gap-6 p-4">

        <!-- Withdrawal Form -->
        <div class="lg:w-full xl:full">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                <!-- Form Header -->
                <div class="bg-[#184d6c] p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white vazir">
                            <i class="fa-solid fa-money-bill-wave ml-2"></i>
                            {{ $editingId ? 'ویرایش برداشت' : 'ثبت برداشت جدید' }}
                        </h2>
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-wallet text-white"></i>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-6 space-y-6">
                    <form wire:submit.prevent="withdraw" class="space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">

                            <!-- Withdrawal Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 vazir">
                                    نوع برداشت <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="type"
                                    class="w-full h-12 px-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">انتخاب نوع برداشت</option>
                                    @foreach($this->expansesTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 vazir">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Currency and Amount -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Currency -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 vazir">
                                        ارز <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="currency"
                                        class="w-full h-12 px-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <option value="AFN">افغانی</option>
                                        <option value="USD">دالر</option>
                                        <option value="EUR">یورو</option>
                                        <option value="IRR">تومان</option>
                                    </select>
                                    @error('currency')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 vazir">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 vazir">
                                        مقدار برداشت <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" wire:model="amount" step="0.01" min="0"
                                        class="w-full h-12 px-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        placeholder="0">
                                    @error('amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 vazir">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Receiver Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 vazir">
                                    تحویل به <span class="text-red-500">*</span>
                                </label>

                                <select wire:model.live="receiver_type" class="w-full h-12 px-4 border border-gray-300 dark:border-gray-600 rounded-xl
        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition vazir
        bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">

                                    <option value="staff">کارمند</option>
                                    <option value="customer">مشتری</option>

                                </select>
                            </div>


                            @php
                            $entangledField = $receiver_type === 'staff' ? 'staff_id' : 'customer_id';
                            @endphp

                            <div wire:key="receiver-search-{{ $receiver_type }}" x-data="{
         search: '',
         selectedId: @entangle($entangledField),
         items: @js($receiver_type === 'staff' ? $this->staffs : $this->customers),
         open: false,

         init() {
             if (this.selectedId && this.items[this.selectedId]) {
                 this.search = this.items[this.selectedId];
             }
             this.$watch('selectedId', value => {
                 if (value && this.items[value]) {
                     this.search = this.items[value];
                 } else {
                     this.search = '';
                 }
             });
         },

         get filteredItems() {
             if (!this.search) return Object.entries(this.items);
             return Object.entries(this.items).filter(([id, name]) =>
                 name.toLowerCase().includes(this.search.toLowerCase())
             );
         },

         select(id, name) {
             this.selectedId = id;
             this.search = name;
             this.open = false;
         },

         clear() {
             this.selectedId = null;
             this.search = '';
             this.open = false;
         }
     }" x-init="init()" @click.away="open=false" class="relative w-full mt-1">

                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                    @if($receiver_type === 'staff')
                                    کارمند دریافت‌کننده
                                    @else
                                    مشتری دریافت‌کننده
                                    @endif
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input x-model="search" @focus="open=true" @input="open=true" autocomplete="off"
                                        placeholder="{{ $receiver_type === 'staff' ? 'جستجوی کارمند...' : 'جستجوی مشتری...' }}"
                                        class="block w-full rounded-xl border border-gray-300 dark:border-gray-600
                   bg-white dark:bg-gray-900
                   py-3 pr-10 pl-11
                   text-sm
                   shadow-sm
                   transition
                   focus:border-primary-500
                   focus:ring-2
                   focus:ring-primary-500/20
                   outline-none" />

                                    <!-- آیکون جستجو -->
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m21 21-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                                        </svg>
                                    </div>

                                    <!-- دکمه پاک‌کردن -->
                                    <button type="button" x-show="search" @click="clear()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293a1 1 0 00-1.414-1.414L10 8.586 7.707 6.293A1 1 0 006.293 7.707L8.586 10l-2.293 2.293a1 1 0 101.414 1.414L10 11.414l2.293 2.293a1 1 0 001.414-1.414L11.414 10l2.293-2.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- منوی کشویی -->
                                <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-xl
                border border-gray-200 dark:border-gray-700
                bg-white dark:bg-gray-900
                shadow-xl overflow-hidden">
                                    <div class="max-h-72 overflow-y-auto">
                                        <template x-for="[id, name] in filteredItems" :key="id">
                                            <div @click="select(id, name)"
                                                class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                                <span x-text="name"
                                                    class="text-sm text-gray-700 dark:text-gray-200"></span>
                                                <svg x-show="selectedId == id" xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </template>
                                        <div x-show="filteredItems.length === 0"
                                            class="py-5 text-center text-sm text-gray-500">
                                            موردی یافت نشد
                                        </div>
                                    </div>
                                </div>

                                <!-- نمایش خطاها -->
                                @if($receiver_type === 'staff')
                                @error('staff_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @else
                                @error('customer_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @endif
                            </div>
                            <div class="relative" x-data="persianDatePicker()" x-init="init()">
                                <label
                                    class="block text-[16px] font-medium dark:text-gray-300 text-gray-700 mb-1 vazir">تاریخ</label>

                                <!-- Input field -->
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder=" روز/ ماه / سال"
                                    class="w-full dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-white focus:ring-blue-500 cursor-pointer"
                                    readonly />

                                <!-- Custom Date Picker Modal -->
                                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                    @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                    class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title"
                                    role="dialog" aria-modal="true" style="display: none;"
                                    :style="isOpen ? 'display: block;' : ''">

                                    <div
                                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <!-- Background overlay -->
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                            aria-hidden="true"></div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                            aria-hidden="true">&#8203;</span>

                                        <!-- Modal panel -->
                                        <div
                                            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                                                <!-- Header -->
                                                <div class="flex justify-between items-center mb-4">
                                                    <div class="flex items-center space-x-2">
                                                        <button @click="prevYear()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button @click="prevMonth()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                        <button @click="nextYear()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
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
                                                                    'bg-blue-500 text-white hover:bg-blue-600': day
                                                                        .isSelected,
                                                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                                        .isToday && !day.isSelected,
                                                                    'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                                        !day.isToday && !day.isSelected && !day
                                                                        .isOtherMonth,
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

                                @error('date')
                                <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>





                            <script>
                                function persianDatePicker() {
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

                                        // ماه‌های افغانی
                                        monthsAfghan: [
                                            'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
                                            'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
                                        ],

                                        // روزهای هفته (شنبه شروع می‌شود)
                                        weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],

                                        // روزهای کامل هفته
                                        weekDaysFull: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'],

                                        // تعداد روزهای ماه‌های شمسی در سال عادی
                                        daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],

                                        init() {
                                            this.updateYearRange();

                                            // Initialize with current date
                                            const today = this.getTodayPersian();
                                            this.currentYear = today.year;
                                            this.currentMonth = today.month - 1;

                                            // اگر تاریخ از قبل انتخاب شده بود
                                            if (@this.get('date')) {
                                                const dateParts = @this.get('date').split('/');
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
                                                        this.displayDate = @this.get('date');
                                                        this.currentYear = year;
                                                        this.currentMonth = month - 1;
                                                    }
                                                }
                                            }
                                        },

                                        // به‌روزرسانی محدوده سال‌ها
                                        updateYearRange() {
                                            this.yearRange.years = [];
                                            for (let year = this.yearRange.start; year <= this.yearRange.end; year++) {
                                                this.yearRange.years.push(year);
                                            }
                                        },

                                        // بررسی سال کبیسه
                                        isLeapYear(year) {
                                            // سال کبیسه شمسی: سال‌هایی که باقیمانده تقسیم به 33 برابر با 1, 5, 9, 13, 17, 22, 26, 30 باشد
                                            const remainders = [1, 5, 9, 13, 17, 22, 26, 30];
                                            return remainders.includes(year % 33);
                                        },

                                        // تعداد روزهای ماه
                                        getDaysInMonth(year, month) {
                                            const days = [...this.daysInMonthNormal];
                                            // اگر سال کبیسه باشد، اسفند 30 روز است
                                            if (month === 11 && this.isLeapYear(year)) {
                                                return 30;
                                            }
                                            return days[month];
                                        },

                                        // محاسبه روز هفته برای روز اول ماه
                                        getFirstDayOfWeek(year, month) {
                                            // الگوریتم محاسبه روز هفته برای تقویم هجری شمسی
                                            // روز اول فروردین سال 1403 = چهارشنبه (index = 4)
                                            const baseYear = 1403;
                                            const baseDay = 4; // چهارشنبه (شنبه=0)

                                            // محاسبه تعداد روزهای گذشته از سال پایه
                                            let days = 0;

                                            // محاسبه روزهای سال‌های کامل
                                            for (let y = baseYear; y < year; y++) {
                                                days += this.isLeapYear(y) ? 366 : 365;
                                            }

                                            // محاسبه روزهای ماه‌های گذشته از سال جاری
                                            for (let m = 0; m < month; m++) {
                                                days += this.getDaysInMonth(year, m);
                                            }

                                            // محاسبه روز هفته (0 = شنبه)
                                            return (baseDay + days) % 7;
                                        },

                                        // دریافت تاریخ امروز به شمسی
                                        getTodayPersian() {
                                            const today = new Date();

                                            // الگوریتم تبدیل میلادی به شمسی (ساده شده)
                                            const gregorianYear = today.getFullYear();
                                            const gregorianMonth = today.getMonth() + 1;
                                            const gregorianDay = today.getDate();

                                            // تبدیل میلادی به شمسی
                                            return this.gregorianToPersian(gregorianYear, gregorianMonth, gregorianDay);
                                        },

                                        // تبدیل میلادی به شمسی
                                        gregorianToPersian(gy, gm, gd) {
                                            // الگوریتم تبدیل میلادی به شمسی
                                            const gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

                                            // بررسی کبیسه میلادی
                                            const isGregorianLeap = (gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0);

                                            if (isGregorianLeap) {
                                                gDaysInMonth[1] = 29;
                                            }

                                            // محاسبه روز از ابتدای سال میلادی
                                            let dayOfYear = gd;
                                            for (let i = 0; i < gm - 1; i++) {
                                                dayOfYear += gDaysInMonth[i];
                                            }

                                            // نوروز سال جاری
                                            const marchDay = 79; // 20 مارس

                                            let persianYear, persianMonth, persianDay;

                                            if (dayOfYear > marchDay) {
                                                persianYear = gy - 621;
                                                let remainingDays = dayOfYear - marchDay;

                                                const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                                                if (this.isLeapYear(persianYear)) {
                                                    pDaysInMonth[11] = 30;
                                                }

                                                for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                                                    if (remainingDays <= pDaysInMonth[persianMonth]) {
                                                        persianDay = remainingDays;
                                                        break;
                                                    }
                                                    remainingDays -= pDaysInMonth[persianMonth];
                                                }
                                                persianMonth++; // تبدیل به 1-based
                                            } else {
                                                persianYear = gy - 622;
                                                let remainingDays = dayOfYear + 286;

                                                const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                                                if (this.isLeapYear(persianYear)) {
                                                    pDaysInMonth[11] = 30;
                                                }

                                                for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                                                    if (remainingDays <= pDaysInMonth[persianMonth]) {
                                                        persianDay = remainingDays;
                                                        break;
                                                    }
                                                    remainingDays -= pDaysInMonth[persianMonth];
                                                }
                                                persianMonth++; // تبدیل به 1-based
                                            }

                                            return {
                                                year: persianYear,
                                                month: persianMonth,
                                                day: persianDay
                                            };
                                        },

                                        // محاسبه روزهای تقویم برای نمایش
                                        get calendarDays() {
                                            const days = [];
                                            const daysInMonth = this.getDaysInMonth(this.currentYear, this.currentMonth);
                                            const firstDayOfWeek = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);
                                            const today = this.getTodayPersian();

                                            // روزهای ماه قبل
                                            const prevMonthDays = this.currentMonth === 0 ?
                                                this.getDaysInMonth(this.currentYear - 1, 11) :
                                                this.getDaysInMonth(this.currentYear, this.currentMonth - 1);

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
                                            const remainingCells = 42 - days.length; // 6 ردیف × 7 ستون
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

                                            this.displayDate =
                                                `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                                        },

                                        formatDate(date) {
                                            if (!date) return '';
                                            return `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`;
                                        },

                                        setToday() {
                                            const today = this.getTodayPersian();
                                            this.currentYear = today.year;
                                            this.currentMonth = today.month - 1;
                                            this.selectedDate = today;
                                            this.displayDate = this.formatDate(today);
                                        },

                                        clearDate() {
                                            this.selectedDate = null;
                                            this.displayDate = '';
                                            @this.set('date', '');
                                            this.closePicker();
                                        },

                                        applyDate() {
                                            if (this.selectedDate) {
                                                const formattedDate = this.formatDate(this.selectedDate);
                                                this.displayDate = formattedDate;
                                                @this.set('date', formattedDate);
                                                this.closePicker();
                                            }
                                        }
                                    }
                                }
                            </script>

                            <style>
                                /* Hide scrollbar for number inputs */
                                input[type="number"]::-webkit-inner-spin-button,
                                input[type="number"]::-webkit-outer-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }

                                /* Persian datepicker custom styles */
                                .persian-datepicker {
                                    font-family: 'Vazir', sans-serif;
                                    direction: rtl;
                                }

                                /* Animation for modal */
                                [x-cloak] {
                                    display: none !important;
                                }

                                /* Smooth transitions */
                                .transition-all {
                                    transition-property: all;
                                    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                                    transition-duration: 150ms;
                                }

                                /* Custom scrollbar */
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
                            </style>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 vazir">
                                    توضیحات
                                </label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none vazir bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    placeholder="دلیل برداشت را وارد کنید..."></textarea>
                                @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 vazir">{{ $message }}</p>
                                @enderror
                            </div>


                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-3 px-6 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-solid fa-check ml-2"></i>
                                {{ $editingId ? 'بروزرسانی برداشت' : 'ثبت برداشت' }}
                            </button>

                            @if($editingId)
                            <button type="button" wire:click="cancelEdit"
                                class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-3 px-6 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-solid fa-times ml-2"></i>
                                لغو ویرایش
                            </button>
                            @else
                            <button type="button" wire:click="resetForm"
                                class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white py-3 px-6 rounded-xl font-medium transition shadow-lg vazir">
                                <i class="fa-solid fa-eraser ml-2"></i>
                                پاک کردن فرم
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    <!-- Withdrawals Table -->
    <div class="w-full lg:full xl:w-full px-4 pb-4">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
            <!-- Table Header -->
            <div class="bg-[#184d6c] p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white vazir">
                        <i class="fa-solid fa-list ml-2"></i>
                        تاریخچه برداشت‌ها
                    </h2>
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-history text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Table Body -->
            <div class="p-6">

                <div class="overflow-x-auto">
                    <div class="mb-4 flex flex-col md:flex-row gap-3 items-center" wire:ignore>

                        <div>
                            <div class="lg:col-span-3 relative" x-data="fromDatePicker()" x-init="init()">
                                <label
                                    class="block text-[16px] font-medium dark:text-gray-300 text-gray-700 mb-1 vazir">
                                    از تاریخ
                                </label>
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder=" روز/ ماه / سال"
                                    class="w-full dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 h-[50px] p-3 rounded-[12px] border focus:ring-2 bg-[#EFF6F9] dark:bg-gray-700 focus:ring-blue-500 cursor-pointer"
                                    readonly />

                                <!-- Date Picker Modal -->
                                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                    @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                    class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title"
                                    role="dialog" aria-modal="true" style="display: none;"
                                    :style="isOpen ? 'display: block;' : ''">

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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button @click="prevMonth()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                        <button @click="nextYear()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
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
                                                                    'bg-blue-500 text-white hover:bg-blue-600': day
                                                                        .isSelected,
                                                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                                        .isToday && !day.isSelected,
                                                                    'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                                        !day.isToday && !day.isSelected && !day
                                                                        .isOtherMonth,
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

                                @error('startDate')
                                <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div>
                            <!-- تا تاریخ -->
                            <div class="lg:col-span-3 relative" x-data="toDatePicker()" x-init="init()">
                                <label
                                    class="block text-[16px] font-medium dark:text-gray-300 text-gray-700 mb-1 vazir">
                                    تا تاریخ
                                </label>
                                <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                    placeholder=" روز/ ماه / سال"
                                    class="w-full dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 h-[50px] p-3 rounded-[12px] border focus:ring-2 bg-[#EFF6F9] dark:bg-gray-700 focus:ring-blue-500 cursor-pointer"
                                    readonly />

                                <!-- Date Picker Modal -->
                                <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                    @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                    class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title"
                                    role="dialog" aria-modal="true" style="display: none;"
                                    :style="isOpen ? 'display: block;' : ''">

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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <button @click="prevMonth()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                        <button @click="nextYear()" type="button"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
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
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
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
                                                                    'bg-blue-500 text-white hover:bg-blue-600': day
                                                                        .isSelected,
                                                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day
                                                                        .isToday && !day.isSelected,
                                                                    'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700':
                                                                        !day.isToday && !day.isSelected && !day
                                                                        .isOtherMonth,
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

                                @error('endDate')
                                <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <script>
                            // جستجوی ساده در جدول
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchTable');
                if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });

    function createPersianDatePicker(fieldName = 'date') {
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
    this.updateYearRange();
    const today = this.getTodayPersian();
    this.currentYear = today.year;
    this.currentMonth = today.month - 1;
    
    const livewireValue = @this.get(fieldName);
    if (!livewireValue) {
        // اگر مقدار Livewire خالی است، تاریخ انتخاب نشده
        this.selectedDate = null; // این خط اضافه شود
        this.displayDate = ''; // نمایش خالی
    } else {
        // تبدیل تاریخ از Y-m-d به Y/m/d برای نمایش
        const dateParts = livewireValue.split('-');
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
                this.displayDate = 
                    `${year}/${String(month).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
                this.currentYear = year;
                this.currentMonth = month - 1;
            }
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

                const persianDate = this.gregorianToPersian(
                    today.getFullYear(),
                    today.getMonth() + 1,
                    today.getDate()
                );

                return persianDate;
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

            // درون تابع persianDatePicker()
selectDate(day) {
    this.selectedDate = {
        year: this.currentYear,
        month: this.currentMonth + 1,
        day: day
    };
    this.displayDate =
        `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
    
    // اضافه کنید: اعمال خودکار تاریخ به Livewire
    this.applyDate();
},

            formatDate(date) {
                if (!date) return '';
                // ذخیره به فرمت Y-m-d (مشابه دیتابیس)
                return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`;
            },

            setToday() {
                const today = this.getTodayPersian();
                this.currentYear = today.year;
                this.currentMonth = today.month - 1;
                this.selectedDate = today;
                // نمایش به فرمت Y/m/d
                this.displayDate =
                    `${today.year}/${String(today.month).padStart(2, '0')}/${String(today.day).padStart(2, '0')}`;

                @this.set(fieldName, this.formatDate(today));
            },

            clearDate() {
                this.selectedDate = null;
                this.displayDate = '';
                @this.set(fieldName, '');
                this.closePicker();
            },

           applyDate() {
    if (this.selectedDate) {
        const formattedDate = this.formatDate(this.selectedDate);
        console.log('Date selected:', formattedDate);
        
        // برای startDate
        if (fieldName === 'startDate') {
            @this.setStartDate(formattedDate);
        } 
        // برای endDate
        else if (fieldName === 'endDate') {
            @this.setEndDate(formattedDate);
        }
        this.closePicker();
    } else {
        this.setToday();
    }
}
        };
    }

    function fromDatePicker() {
        return createPersianDatePicker('startDate');
    }

    function toDatePicker() {
        return createPersianDatePicker('endDate');
    }

    let printListenerRegistered = false;

    document.addEventListener('livewire:init', () => {
        if (printListenerRegistered) return;
        printListenerRegistered = true;

        Livewire.on('print-pdf', (data) => {
            /* 🔹 1. دانلود (با لینک مخفی) */
            const downloadLink = document.createElement('a');
            downloadLink.href = data.url;
            downloadLink.download = '';
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();

            /* 🔹 2. پرینت */
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = data.url;
            document.body.appendChild(iframe);

            iframe.onload = () => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                /* 🔹 3. حذف با تأخیر */
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
                        </style>




                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 vazir">
                            <tr>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">#</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">نوع برداشت</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">ارز</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">مبلغ</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">دریافت‌کننده</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">توضیحات</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">تاریخ</th>
                                <th class="px-4 py-3 font-bold border-b dark:border-gray-600">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="vazir">
                            @forelse($withdrawals as $key => $withdrawal)
                            <tr
                                class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 text-center">
                                    {{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $key + 1 }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $withdrawal->expanses_type }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                    $currencyStyles = [
                                    'AFN' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-200',
                                    'USD' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                                    'EUR' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                                    'IRR' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200',
                                    ];
                                    $currencyLabels = [
                                    'AFN' => 'افغانی',
                                    'USD' => 'دالر',
                                    'EUR' => 'یورو',
                                    'IRR' => 'تومان',
                                    ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium {{ $currencyStyles[$withdrawal->currency] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $currencyLabels[$withdrawal->currency] ?? $withdrawal->currency }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ number_format($withdrawal->amount) }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($withdrawal->staff_id && $withdrawal->staff)
                                    <span
                                        class="bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 px-2 py-1 rounded text-xs">
                                        {{ $withdrawal->staff->fullname }}
                                    </span>
                                    @elseif($withdrawal->customer_id && $withdrawal->customer)
                                    <span
                                        class="bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 px-2 py-1 rounded text-xs">
                                        {{ $withdrawal->customer->fullname }}
                                    </span>
                                    @else
                                    <span
                                        class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs">
                                        صندوق
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 max-w-xs truncate text-gray-900 dark:text-gray-100">
                                    {{ $withdrawal->description ?? 'بدون توضیح' }}
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{
                                        \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($withdrawal->created_at))
                                        ->format('Y/m/d H:i')
                                        }}
                                    </div>

                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($withdrawal->created_at)->format('h:i') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-1 space-x-2">
                                        <button wire:click="edit({{ $withdrawal->id }})"
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition tooltip"
                                            title="ویرایش">
                                            <i class="fa-solid fa-edit text-sm"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $withdrawal->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition tooltip"
                                            title="حذف">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 vazir">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                        <p class="text-lg">هیچ برداشتی یافت نشد</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($withdrawals->hasPages())
                <div class="mt-6">
                    {{ $withdrawals->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteId)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div
                    class="flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/50 rounded-full mx-auto mb-4">
                    <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-center text-gray-900 dark:text-gray-100 mb-2 vazir">
                    تأیید حذف برداشت
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-center mb-6 vazir">
                    آیا از حذف این برداشت اطمینان دارید؟ این عمل غیرقابل بازگشت است.
                </p>
                <div class="flex gap-3">
                    <button wire:click="deleteWithdrawal"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl font-medium transition vazir">
                        <i class="fa-solid fa-trash ml-2"></i>
                        بله، حذف شود
                    </button>
                    <button wire:click="$set('confirmDeleteId', null)"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-xl font-medium transition vazir">
                        <i class="fa-solid fa-times ml-2"></i>
                        انصراف
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker/dist/js/persian-datepicker.min.js"></script>
<script>
    function initDatePickers() {

                        $('#from_date_picker').persianDatepicker({
                            format: 'YYYY/MM/DD',
                            autoClose: true,
                            initialValue: false,
                            onSelect: function (unix) {
                                let date = new persianDate(unix).format('YYYY/MM/DD');
                                @this.set('from_date', date);
                            }
                        });

                        $('#to_date_picker').persianDatepicker({
                            format: 'YYYY/MM/DD',
                            autoClose: true,
                            initialValue: false,
                            onSelect: function (unix) {
                                let date = new persianDate(unix).format('YYYY/MM/DD');
                                @this.set('to_date', date);
                            }
                        });
                    }

                    function resetDates() {
                        $('#from_date_picker').val('');
                        $('#to_date_picker').val('');
                        @this.set('from_date', null);
                        @this.set('to_date', null);
                    }

                    document.addEventListener("livewire:init", function () {
                        initDatePickers();
                    });

                    document.addEventListener("livewire:navigated", function () {
                        initDatePickers();
                    });

     Livewire.on('print-pdf', (data) => {
    if (data.url) {
        window.open(data.url, '_blank');
    }
});
                    
</script>
<script>
    tailwind.config = {
                            darkMode: 'class',
                            theme: {
                                extend: {
                                    colors: {
                                        primary: {
                                            50: '#EEF2FF',
                                            500: '#6366F1',
                                            600: '#4F46E5',
                                        },
                                    },
                                    fontFamily: {
                                        vazir: ['Vazir', 'sans-serif'],
                                        shabnam: ['Shabnam', 'sans-serif'],
                                        yekan: ['DimaYekan', 'sans-serif'],
                                        amiri: ['Yekan-Regular', 'sans-serif'],
                                        times: ['Times', 'serif'],
                                    },
                                },
                            },
                        }

                        
</script>

<!-- ✅ فونت‌ها و کلاس‌ها -->
<style>
    @font-face {
        font-family: "DimaYekan";
        src: url("/fonts/Yekan-Regular.ttf") format("truetype");
    }

    @font-face {
        font-family: "times";
        src: url("/fonts/times.ttf") format("truetype");
    }

    @font-face {
        font-family: "vazir";
        src: url("/fonts/Vazir.ttf") format("truetype");
    }

    @font-face {
        font-family: "shabnam";
        src: url("/fonts/Shabnam-Medium.ttf") format("truetype");
    }

    @font-face {
        font-family: "Mj_Afrigha";
        src: url("/fonts/Mj_Afrigha.ttf") format("truetype");
    }

    @font-face {
        font-family: "Yekan-Regular";
        src: url("/fonts/Yekan-Regular.ttf") format("truetype");
    }

    /* کلاس‌های کمکی برای انتخاب سریع فونت */
    .yekan {
        font-family: "DimaYekan", sans-serif;
    }

    .shabnam {
        font-family: "shabnam", sans-serif;
    }

    .Mj_Afrigha {
        font-family: "Mj_Afrigha", sans-serif;
    }

    .vazir {
        font-family: "vazir", sans-serif;
    }

    .amiri {
        font-family: "Yekan-Regular", sans-serif;
    }

    .times {
        font-family: "times", serif;
    }
</style>
@endpush



@push('script')

@endpush