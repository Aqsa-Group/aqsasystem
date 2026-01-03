<div>
    <div class="container mx-auto ">
        <!-- Session Message -->
        <?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 ">
            
            <?php if($selectedCustomer): ?>
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">

                    
                    <div x-data="{ showLargeImage: false, largeImageSrc: '' }">
                        <?php if($selectedCustomer->image): ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(Storage::url($selectedCustomer->image)); ?>"
                                alt="<?php echo e($selectedCustomer->fullname); ?>"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(Storage::url($selectedCustomer->image)); ?>'">
                        </div>
                        <?php else: ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(asset('assets/web.jpg')); ?>" alt="<?php echo e($selectedCustomer->fullname); ?>"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(asset('assets/web.jpg')); ?>'">
                        </div>
                        <?php endif; ?>

                        
                        <div x-show="showLargeImage" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4"
                            @click.away="showLargeImage = false" @keydown.escape.window="showLargeImage = false">

                            <div class="relative max-w-4xl max-h-[90vh]">
                                
                                <button @click="showLargeImage = false"
                                    class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl z-10">
                                    ✕
                                </button>

                                
                                <img :src="largeImageSrc" alt="<?php echo e($selectedCustomer->fullname); ?>"
                                    class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">

                                
                                <div class="mt-4 text-center text-white">
                                    <p class="text-lg font-semibold"><?php echo e($selectedCustomer->fullname); ?></p>
                                    <?php if($selectedCustomer->phone): ?>
                                    <p class="text-sm text-gray-300"><?php echo e($selectedCustomer->phone); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-6 flex justify-center gap-4">
                                    <a :href="largeImageSrc"
                                        :download="customerName + '_' + new Date().toISOString().split('T')[0] + '.jpg'"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        دانلود عکس
                                    </a>

                                    <button @click="showLargeImage = false"
                                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                        بستن
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <h1 class="text-[20px] text-white text-center font-bold truncate"
                        title="<?php echo e($selectedCustomer->fullname); ?>">
                        <?php echo e($selectedCustomer->fullname); ?>

                    </h1>

                    
                    <?php if($selectedCustomer->phone): ?>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->phone); ?></span>
                    </div>
                    <?php endif; ?>

                    
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->account_number); ?></span>
                    </div>

                </div>
            </div>
            <?php endif; ?>
            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            ?>

            
            <div class="inline-block align-top ml-4 h-auto ">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#5474BB] text-white">

                    <h1 class="text-[24px] text-white"><?php echo e($currencyName); ?></h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold text-left" dir="ltr"><?php echo e(number_format($cashBalance)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold text-left" dir="ltr"><?php echo e(number_format($bankBalance)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px] text-left" dir="ltr"><?php echo e(number_format($totalBalance)); ?></span>
                        </div>
                    </div>

                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800 hover:shadow-md transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>نمایش گزارش</span>
                        <span wire:loading>
                            در حال انتقال...
                        </span>
                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($selectedCustomerId): ?>
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px]
                        dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900
                        bg-gradient-to-b from-[#11BEC7] to-[#6371D0] text-white">

                    <?php
                    $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                    $sourceCurrency = $latestProfitRate->currency_name ?? 'دالر';

                    $totalCashUsd = 0;
                    $totalBankUsd = 0;

                    // نرخ‌های نقدی
                    $exchangeRatesCash = [
                    'افغانی' => $latestProfitRate->afn_buy_cash ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_cash ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_cash ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_cash ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_cash ?? 44,
                    'لیره' => $latestProfitRate->try_buy_cash ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_cash ?? 43,
                    'روپیه' => 7.14,
                    ];

                    // نرخ‌های بانکی
                    $exchangeRatesBank = [
                    'افغانی' => $latestProfitRate->afn_buy_bank ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_bank ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_bank ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_bank ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_bank ?? 44,
                    'لیره' => $latestProfitRate->try_buy_bank ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_bank ?? 43,
                    'روپیه' => 7.14,
                    ];

                    /* =====================
                    محاسبه موجودی نقدی
                    ====================== */
                    foreach ($customerCashBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalCashUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                    $totalCashUsd += $balance / $exchangeRatesCash[$currency];
                    }
                    }

                    /* =====================
                    محاسبه موجودی بانکی
                    ====================== */
                    foreach ($customerBankBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalBankUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesBank[$currency]) && $exchangeRatesBank[$currency] > 0) {
                    $totalBankUsd += $balance / $exchangeRatesBank[$currency];
                    }
                    }

                    $grandTotalUsd = $totalCashUsd + $totalBankUsd;
                    ?>

                    <h1 class="text-[24px] text-white">
                        خلاصه بیلانس به <?php echo e($sourceCurrency); ?>

                    </h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">

                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold text-left" dir="ltr">
                                <?php echo e(number_format($totalCashUsd, 2)); ?>

                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold text-left" dir="ltr">
                                <?php echo e(number_format($totalBankUsd, 2)); ?>

                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px] text-left" dir="ltr">
                                <?php echo e(number_format($grandTotalUsd, 2)); ?>

                            </span>
                        </div>

                    </div>

                    <button wire:click="showReport" wire:loading.attr="disabled" class="bg-white rounded-[12px] text-[16px] p-1 mt-2 text-gray-800
                   hover:shadow-md transition flex items-center justify-center gap-2">

                        <span wire:loading.remove>نمایش گزارش</span>
                        <span wire:loading>در حال انتقال...</span>
                    </button>

                </div>
            </div>
            <?php endif; ?>

        </div>


        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- Main Content - Form and Table -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">

            <!-- Remittance Form -->
            <div class="flex flex-col dark:bg-black dark:border-white dark:border  bg-[#F5F5F5] w-[420px] lg:w-[534px] mx-auto p-[12px] h-auto rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Form Header -->
                <div class="flex flex-row gap-4 p-4 p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">

                    <p class="flex justify-center items-center text-center dark:text-white">
                        <?php echo e($remittanceId ? 'فورم ویرایش حواله' : 'فورم ثبت اطلاعات حواله'); ?>

                    </p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="submitRemittance">
                    <!-- Account Number and Currency -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Source Account Number -->
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label
                                    class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نمبرحساب
                                    مشتری</label>
                                <div x-data="{
                                    searchValue: '',
                                    selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                    customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
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
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </datalist>
                                    <?php if(empty($selectedAccount)): ?>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php $__errorArgs = ['selectedAccount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Currency Type -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full dark:text-white dark:bg-black dark:placeholder:text-white dark:border-white   h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500   appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓"
                                        class="w-4 h-4">
                                </div>
                            </div>
                            <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Amount and Date -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Amount -->
                        <div class="flex-1">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full dark:bg-black  dark:border-white  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>
                            <?php if($amountInWords): ?>
                            <p class="text-sm dark:text-white text-blue-600 mt-2 vazir"><?php echo e($amountInWords); ?></p>
                            <?php endif; ?>
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Date -->
                        <div class="lg:w-full relative" x-data="persianDatePicker()" x-init="init()">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>

                            <!-- Input field -->
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 cursor-pointer"
                                readonly />

                            <!-- Custom Date Picker Modal -->
                            <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                                aria-modal="true" style="display: none;" :style="isOpen ? 'display: block;' : ''">

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
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="prevMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
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
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button @click="nextYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
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
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !== index
                                    }" class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
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
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
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
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
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
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !== year
                                    }" class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
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
                                        'bg-blue-500 text-white hover:bg-blue-600': day.isSelected,
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day.isToday && !day.isSelected,
                                        'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700': !day.isToday && !day.isSelected && !day.isOtherMonth,
                                        'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800': day.isOtherMonth,
                                        'cursor-not-allowed opacity-50': day.isDisabled
                                    }" class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-medium transition-colors"
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

                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
            if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('date')) {
                const dateParts = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('date').split('/');
                if (dateParts.length === 3) {
                    const year = parseInt(dateParts[0]);
                    const month = parseInt(dateParts[1]);
                    const day = parseInt(dateParts[2]);
                    
                    if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
                        this.selectedDate = { year, month, day };
                        this.displayDate = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('date');
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
            
            this.displayDate = `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
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
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('date', '');
            this.closePicker();
        },
        
        applyDate() {
            if (this.selectedDate) {
                const formattedDate = this.formatDate(this.selectedDate);
                this.displayDate = formattedDate;
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('date', formattedDate);
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
                    </div>

                    <!-- Time and Tracking Code -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Time -->
                        <div class="lg:w-full" x-data="timePicker()" x-init="init()">
                            <label
                                class="block text-[16px] dark:text-white font-medium text-black mb-1 vazir">ساعت</label>

                            <!-- Input field -->
                            <input type="text" x-model="displayTime" @click="togglePicker()"
                                @keydown.enter.prevent="applyTime()" @keydown.escape="closePicker()"
                                placeholder="2:25:20"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer"
                                readonly x-ref="timeInput" />

                            <!-- Time Picker Modal -->
                            <div x-show="isOpen" x-transition.opacity @keydown.escape.window="closePicker()"
                                @click.away="closePicker()" class="fixed z-50 inset-0 overflow-y-auto"
                                aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;"
                                x-bind:style="isOpen ? 'display: block;' : ''">

                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div
                                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="flex justify-between items-center mb-6">
                                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white"
                                                    id="modal-title">
                                                    انتخاب زمان
                                                </h3>
                                                <button @click="closePicker()" type="button"
                                                    class="text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">بستن</span>
                                                    ✕
                                                </button>
                                            </div>

                                            <div class="flex justify-center space-x-6">
                                                <!-- Hours -->
                                                <div class="text-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">ساعت
                                                    </div>
                                                    <div class="flex flex-col items-center space-y-2">
                                                        <button @click="incrementHour()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↑
                                                        </button>
                                                        <div class="relative">
                                                            <input type="number" x-model="hour" @input="updateTime()"
                                                                @keydown.enter.prevent="applyTime()" min="0" max="23"
                                                                class="w-20 text-center text-3xl font-bold bg-transparent border-2 border-gray-200 dark:border-gray-700 rounded-lg py-2 outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                                                x-ref="hourInput" />
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-400 text-sm">H</span>
                                                            </div>
                                                        </div>
                                                        <button @click="decrementHour()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↓
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Divider -->
                                                <div class="flex items-center">
                                                    <span class="text-2xl font-bold text-gray-400">:</span>
                                                </div>

                                                <!-- Minutes -->
                                                <div class="text-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">دقیقه
                                                    </div>
                                                    <div class="flex flex-col items-center space-y-2">
                                                        <button @click="incrementMinute()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↑
                                                        </button>
                                                        <div class="relative">
                                                            <input type="number" x-model="minute" @input="updateTime()"
                                                                @keydown.enter.prevent="applyTime()" min="0" max="59"
                                                                class="w-20 text-center text-3xl font-bold bg-transparent border-2 border-gray-200 dark:border-gray-700 rounded-lg py-2 outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                                                x-ref="minuteInput" />
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-400 text-sm">M</span>
                                                            </div>
                                                        </div>
                                                        <button @click="decrementMinute()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↓
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Divider -->
                                                <div class="flex items-center">
                                                    <span class="text-2xl font-bold text-gray-400">:</span>
                                                </div>

                                                <!-- Seconds -->
                                                <div class="text-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">ثانیه
                                                    </div>
                                                    <div class="flex flex-col items-center space-y-2">
                                                        <button @click="incrementSecond()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↑
                                                        </button>
                                                        <div class="relative">
                                                            <input type="number" x-model="second" @input="updateTime()"
                                                                @keydown.enter.prevent="applyTime()" min="0" max="59"
                                                                class="w-20 text-center text-3xl font-bold bg-transparent border-2 border-gray-200 dark:border-gray-700 rounded-lg py-2 outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                                                x-ref="secondInput" />
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-400 text-sm">S</span>
                                                            </div>
                                                        </div>
                                                        <button @click="decrementSecond()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↓
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-8 flex justify-end space-x-3">
                                                <button @click="resetTime()" type="button"
                                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                    بازنشانی
                                                </button>
                                                <button @click="applyTime()" type="button"
                                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    تأیید و بستن
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $__errorArgs = ['clock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <script>
                            function timePicker() {
    return {
        isOpen: false,
        hour: '00',
        minute: '00',
        second: '00',
        displayTime: '',
        originalTime: '',
        
        init() {
            // Initialize with current time or existing value
            if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock')) {
                const time = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock').split(':');
                this.hour = time[0] || '00';
                this.minute = time[1] || '00';
                this.second = time[2] || '00';
                this.originalTime = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock');
                this.updateDisplay();
            } else {
                const now = new Date();
                this.hour = String(now.getHours()).padStart(2, '0');
                this.minute = String(now.getMinutes()).padStart(2, '0');
                this.second = String(now.getSeconds()).padStart(2, '0');
                this.updateDisplay();
                this.originalTime = this.displayTime;
            }
        },
        
        togglePicker() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                // Save current time
                this.originalTime = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock') || this.displayTime;
                // Focus on hour input when opened
                setTimeout(() => {
                    this.$refs.hourInput.focus();
                    this.$refs.hourInput.select();
                }, 100);
            }
        },
        
        closePicker() {
            if (this.isOpen) {
                // Revert to original time if not applied
                if (this.originalTime && !this.displayTime.includes('NaN')) {
                    const time = this.originalTime.split(':');
                    this.hour = time[0] || '00';
                    this.minute = time[1] || '00';
                    this.second = time[2] || '00';
                    this.updateTime();
                }
                this.isOpen = false;
            }
        },
        
        incrementHour() {
            let h = parseInt(this.hour);
            if (isNaN(h)) h = 0;
            h = (h + 1) % 24;
            this.hour = String(h).padStart(2, '0');
            this.updateTime();
            this.$refs.hourInput.focus();
            this.$refs.hourInput.select();
        },
        
        decrementHour() {
            let h = parseInt(this.hour);
            if (isNaN(h)) h = 0;
            h = (h - 1 + 24) % 24;
            this.hour = String(h).padStart(2, '0');
            this.updateTime();
            this.$refs.hourInput.focus();
            this.$refs.hourInput.select();
        },
        
        incrementMinute() {
            let m = parseInt(this.minute);
            if (isNaN(m)) m = 0;
            m = (m + 1) % 60;
            this.minute = String(m).padStart(2, '0');
            this.updateTime();
            this.$refs.minuteInput.focus();
            this.$refs.minuteInput.select();
        },
        
        decrementMinute() {
            let m = parseInt(this.minute);
            if (isNaN(m)) m = 0;
            m = (m - 1 + 60) % 60;
            this.minute = String(m).padStart(2, '0');
            this.updateTime();
            this.$refs.minuteInput.focus();
            this.$refs.minuteInput.select();
        },
        
        incrementSecond() {
            let s = parseInt(this.second);
            if (isNaN(s)) s = 0;
            s = (s + 1) % 60;
            this.second = String(s).padStart(2, '0');
            this.updateTime();
            this.$refs.secondInput.focus();
            this.$refs.secondInput.select();
        },
        
        decrementSecond() {
            let s = parseInt(this.second);
            if (isNaN(s)) s = 0;
            s = (s - 1 + 60) % 60;
            this.second = String(s).padStart(2, '0');
            this.updateTime();
            this.$refs.secondInput.focus();
            this.$refs.secondInput.select();
        },
        
        updateTime() {
            // Validate and format
            let h = parseInt(this.hour);
            let m = parseInt(this.minute);
            let s = parseInt(this.second);
            
            if (isNaN(h) || h < 0) h = 0;
            if (isNaN(h) || h > 23) h = 23;
            if (isNaN(m) || m < 0) m = 0;
            if (isNaN(m) || m > 59) m = 59;
            if (isNaN(s) || s < 0) s = 0;
            if (isNaN(s) || s > 59) s = 59;
            
            this.hour = String(h).padStart(2, '0');
            this.minute = String(m).padStart(2, '0');
            this.second = String(s).padStart(2, '0');
            
            this.updateDisplay();
            // Update Livewire model in real-time
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('clock', this.displayTime);
        },
        
        updateDisplay() {
            this.displayTime = `${this.hour}:${this.minute}:${this.second}`;
        },
        
        applyTime() {
            // Final validation before applying
            this.updateTime();
            
            // Ensure valid format
            if (!this.displayTime.includes('NaN')) {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('clock', this.displayTime);
                this.isOpen = false;
                this.$refs.timeInput.focus();
            }
        },
        
        resetTime() {
            this.hour = '00';
            this.minute = '00';
            this.second = '00';
            this.updateTime();
            this.$refs.hourInput.focus();
            this.$refs.hourInput.select();
        }
    }
}
                        </script>

                        <style>
                            /* Hide number input arrows */
                            input[type="number"]::-webkit-inner-spin-button,
                            input[type="number"]::-webkit-outer-spin-button {
                                -webkit-appearance: none;
                                margin: 0;
                            }

                            input[type="number"] {
                                -moz-appearance: textfield;
                            }

                            /* Fix for modal backdrop */
                            .fixed {
                                position: fixed;
                            }

                            /* Better transition */
                            [x-cloak] {
                                display: none;
                            }
                        </style>

                        <!-- Tracking Code -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">کد
                                رهگیری</label>
                            <input type="text" wire:model="tracking_code" placeholder="5155221034568"
                                class="w-full  h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer" />
                            <?php $__errorArgs = ['tracking_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Source and Destination Banks -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                بانک مبدا
                            </label>

                            <input type="text" wire:model="from_bank" list="banks-list" placeholder="بانک مبدا"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent dark:bg-black dark:border-white dark:text-white">

                            <datalist id="banks-list">
                                <option value="بانک ملی ایران">
                                <option value="بانک ملت">
                                <option value="بانک صادرات">
                                <option value="بانک سپه">
                                <option value="بانک تجارت">
                                <option value="بانک پاسارگاد">
                                <option value="بانک سامان">
                                <option value="بانک پارسیان">
                                <option value="HSBC">
                                <option value="Standard Chartered">
                                <option value="Citi Bank">
                                <option value="Deutsche Bank">
                                <option value="Bank of China">
                                <option value="Emirates NBD">
                                <option value="QNB">
                                <option value="Al Rajhi Bank">
                            </datalist>

                            <?php $__errorArgs = ['from_bank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                بانک مقصد
                            </label>

                            <input type="text" wire:model="to_bank" list="banks-list" placeholder="بانک مقصد"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent dark:bg-black dark:border-white dark:text-white">

                            <?php $__errorArgs = ['to_bank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <!-- Source and Destination Account Numbers -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Source Account Number (Display only) -->
                        <div class="lg:w-full">
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
                            <?php $__errorArgs = ['source_account_last_four'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <!-- Destination Account -->
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحساب مقصد</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: <?php if ((object) ('toAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('toAccount'->value()); ?>')<?php echo e('toAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('toAccount'); ?>')<?php endif; ?>,
                                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
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
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <?php if(empty($toAccount)): ?>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php $__errorArgs = ['toAccount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>


                    <!-- Zone and Beneficiary -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Zone -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">درج زون
                                ها</label>
                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="<?php echo e(Auth::guard('sarafi')->user()->zone); ?>">
                                        <?php echo e(Auth::guard('sarafi')->user()->zone); ?>

                                    </option>
                                </select>
                            </div>
                            <?php $__errorArgs = ['zone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Beneficiary Name -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir"> نام کارت
                                گیرنده</label>
                            <input type="text" wire:model="giver_name" placeholder="مجید مرتضی"
                                class="w-full   h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-gray-600 dark:text-white cursor-pointer" />
                            <?php $__errorArgs = ['giver_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>


                    <!-- Remittance Description -->
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح حواله..."
                                class="w-full  p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white resize-none"></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <!-- File Upload -->
                    <div class="mt-2 flex gap-3">
                        <div class="w-full dark:bg-black dark:border-white">
                            <div x-data="{
            files: [],
            isUploading: false,
            uploadedFileName: <?php if ((object) ('remittance_image') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('remittance_image'->value()); ?>')<?php echo e('remittance_image'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('remittance_image'); ?>')<?php endif; ?>.defer,
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
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/upload.svg')); ?>" alt="آپلود"
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
                            <?php $__errorArgs = ['remittance_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="mt-2 flex items-center gap-2 text-red-500 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><?php echo e($message); ?></span>
                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>



                            <!-- نمایش فایل ذخیره شده (در حالت ویرایش) -->
                            <?php if($remittance_image && is_string($remittance_image)): ?>
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
                                <a href="<?php echo e(Storage::url($remittance_image)); ?>" target="_blank"
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
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 py-4 justify-center items-center text-center flex-wrap">
                        <button type="submit" wire:loading.attr='disabled' wire:target='submitRemittance'
                            class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">


                            <span wire:loading.remove wire:target="submitRemittance">
                                <?php echo e($remittanceId ? 'بروزرسانی' : 'ثبت'); ?>

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

                        <?php if(!$remittanceId): ?>
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
                        <?php endif; ?>

                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            <?php echo e($remittanceId ? 'لغو ویرایش' : 'انصراف'); ?>

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
                        <?php if($selectedCustomerId): ?>
                        <?php
                        $selectedCustomer = \App\Models\Sarafi\Customer::find($selectedCustomerId);
                        ?>
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: <?php echo e($selectedCustomer->fullname ?? ''); ?></span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        <?php endif; ?>

                        <!-- Search Box -->
                        <div class="relative w-full">
                            <input type="text" wire:model.live="search"
                                class="border dark:bg-black dark:border dark:border-white dark:placeholder:text-white border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام یا نمبر حساب...">


                            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
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


                            <?php if($search): ?>
                            <button wire:click="clearSearchAndFilter"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?>

                            <?php if($search && count($filteredCustomers) > 0 && !$selectedCustomerId): ?>
                            <ul
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                <?php $__currentLoopData = $filteredCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li wire:click="selectCustomer(<?php echo e($customer->id); ?>)"
                                    class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between items-center">
                                    <span><?php echo e($customer->fullname); ?></span>
                                    <span class="text-gray-500 text-sm"><?php echo e($customer->account_number); ?></span>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm  md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-[#2B65E5]  text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
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
                                <?php $__empty_1 = true; $__currentLoopData = $remittances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $remittance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b dark:text-white border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <?php echo e($remittance->customer->fullname ?? '-'); ?>

                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <?php echo e($remittance->recipient->fullname ?? $remittance->giver_name); ?>

                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <?php echo e(number_format($remittance->amount)); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <?php echo e(collect($currencies)->firstWhere('code',
                                        $remittance->currency)['name_fa'] ??
                                        $remittance->currency); ?>

                                    </td>
                                    <td>
                                        <?php if($remittance->state===0): ?>
                                        <span class="text-red-500">در انتظار تایید</span>
                                        <?php else: ?>
                                        <span class="text-green-500">تاییده شده</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            <p class="text-sm">کد رهگیری: <?php echo e($remittance->tracking_code); ?>

                                            </p>
                                            <p class="text-sm">زون: <?php echo e($remittance->zone); ?></p>
                                            <p class="text-sm">تفصیلات: <?php echo e($remittance->description); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">
                                                <?php echo e(explode(' ',$remittance->date)[0]); ?>

                                            </div>
                                            <div class="text-gray-500 dark:text-white  text-sm mt-1">
                                                <?php echo e($remittance->clock); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- Edit Button -->
                                            <button wire:click="edit(<?php echo e($remittance->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="ویرایش">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                    class="w-7 h-7 dark:hidden" alt="Edit">

                                                <svg width="22" height="22" class="hidden dark:block"
                                                    viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.082 1.83325H8.2487C3.66536 1.83325 1.83203 3.66659 1.83203 8.24992V13.7499C1.83203 18.3333 3.66536 20.1666 8.2487 20.1666H13.7487C18.332 20.1666 20.1654 18.3333 20.1654 13.7499V11.9166"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M14.7027 2.76832L7.4794 9.99165C7.2044 10.2667 6.9294 10.8075 6.8744 11.2017L6.48023 13.9608C6.33357 14.96 7.0394 15.6567 8.03857 15.5192L10.7977 15.125C11.1827 15.07 11.7236 14.795 12.0077 14.52L19.2311 7.29665C20.4777 6.04999 21.0644 4.60165 19.2311 2.76832C17.3977 0.934987 15.9494 1.52165 14.7027 2.76832Z"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M13.668 3.8042C14.2821 5.99503 15.9963 7.7092 18.1963 8.33253"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>

                                            <!-- Delete Button -->
                                            <button wire:click="confirmDelete(<?php echo e($remittance->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="حذف">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                    class="w-8 h-8 dark:hidden" alt="Delete">
                                                <svg width="24" height="24" class="hidden dark:block"
                                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M18.8484 9.13989L18.1984 19.2099C18.0884 20.7799 17.9984 21.9999 15.2084 21.9999H8.78844C5.99844 21.9999 5.90844 20.7799 5.79844 19.2099L5.14844 9.13989"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M10.3281 16.5H13.6581" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.5 12.5H14.5" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>

                                            </button>

                                            <!-- Print Button -->
                                            <button wire:click="print(<?php echo e($remittance->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="پرینت">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                    class="w-10 h-10 dark:hidden" alt="Print">
                                                <svg width="30" class="hidden dark:block" height="30"
                                                    viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                                        fill="white" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        <?php if($selectedCustomerId): ?>
                                        هیچ حواله برای این مشتری یافت نشد
                                        <?php else: ?>
                                        هیچ حواله ای یافت نشد
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <?php if($confirmDeleteId): ?>
    <?php
    $remittance = \App\Models\Sarafi\Remittances::find($confirmDeleteId);
    $isApproved = $remittance && $remittance->state == 1;
    ?>
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
                <?php if($isApproved): ?>
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
                <?php else: ?>
                <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                    حذف حــــواله</h1>
                <hr class="bg-[#E1DED3] mt-8">
                <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                    حــــواله را حذف کنید؟</p>
                <?php endif; ?>
            </div>

            <?php if($isApproved): ?>
            <div class="mb-4">
                <p class="text-sm text-gray-500 text-right">
                    عملیات برگشت شامل:
                </p>
                <ul class="text-sm text-gray-600 text-right space-y-1 mt-2">
                    <li>• کاهش موجودی صندوق بانکی</li>
                    <li>• تنظیم مجدد موجودی مشتریان</li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="flex justify-center gap-3  items-center text-center">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-16 py-3 bg-[#2563EB] text-center text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    انصراف
                </button>
                <button wire:click="deleteConfirmed"
                    class="px-16 py-3  <?php echo e($isApproved ? 'bg-red-600 hover:bg-red-700' : 'bg-[#DD2424] hover:bg-red-700'); ?> text-white text-sm text-center font-medium rounded-lg transition-colors flex items-center gap-2">
                    <?php echo e($isApproved ? 'حذف و برگشت' : 'حذف'); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
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
<?php $__env->startPush('script'); ?>
<script>
    window.addEventListener('report-alert', event => {
        alert(event.detail.message);
    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/remittance.blade.php ENDPATH**/ ?>