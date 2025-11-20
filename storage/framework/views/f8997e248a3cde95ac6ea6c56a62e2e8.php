<div>
    <div class="pl-10 pr-10 mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black">تبدیل ارز در حساب مشتری</h1>
            <h1 class="text-[#8C8C8C] text-[18px]">صفحه تبدیل ارز در حساب مشتری</h1>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <!-- پیام‌های سیستم -->
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

        <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#DC2626] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('error')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 -mt-5">
            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            ?>

            
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div
                    class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] bg-gradient-to-b from-[#2563EB] to-[#5474BB] text-white">

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
                <div
                    class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px] bg-gradient-to-b from-[#11BEC7] to-[#6371D0] text-white">

                    <?php
                    $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                    $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                    ?>
                    <h1 class="text-[24px] text-white">خلاصه بیلانس به <?php echo e($sourceCurrency); ?></h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <?php
                        $totalCashUsd = 0;
                        $totalBankUsd = 0;
                        $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();

                        $exchangeRates = [
                        'افغانی' => $latestExchangeRate->afn_buy ?? 66.20,
                        'دالر' => 1,
                        'تومان' => $latestExchangeRate->irr_buy ?? 110000.00,
                        'یورو' => $latestExchangeRate->eur_buy ?? 70.00,
                        'کلدار' => $latestExchangeRate->pkr_buy ?? 32.00,
                        'درهم' => $latestExchangeRate->aed_buy ?? 44.00,
                        'لیره' => $latestExchangeRate->try_buy ?? 60.00,
                        'یوان' => $latestExchangeRate->cny_buy ?? 43.00,
                        'روپیه' => 7.14,
                        ];

                        foreach($customerCashBalances as $currency => $balance) {
                        if(isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0) {

                        $totalCashUsd += $balance / $exchangeRates[$currency];
                        }
                        }

                        foreach($customerBankBalances as $currency => $balance) {
                        if(isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0) {
                        // تقسیم کردن نه ضرب کردن!
                        $totalBankUsd += $balance / $exchangeRates[$currency];
                        }
                        }
                        $grandTotalUsd = $totalCashUsd + $totalBankUsd;
                        ?>

                        <div class="flex justify-between items-center text-[14px]">
                            <span>نقدی:</span>
                            <span class="font-bold text-left" dir="ltr"><?php echo e(number_format($totalCashUsd, 2)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <span>بانکی:</span>
                            <span class="font-bold text-left" dir="ltr"><?php echo e(number_format($totalBankUsd, 2)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-t border-white/30 pt-1">
                            <span class="font-semibold">مجموعه:</span>
                            <span class="font-bold text-[16px] text-left" dir="ltr"><?php echo e(number_format($grandTotalUsd, 2)); ?></span>
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
            <?php endif; ?>
        </div>

        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            <!-- فرم تبدیل ارز -->
            <div class="flex flex-col bg-[#F5F5F5] mx-auto w-[420px] lg:w-[424px] p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- هدر فرم -->
                <div
                    class="flex flex-row justify-between p-[20px] border border-[#8C8C8C] rounded-[12px] flex-wrap items-center">
                    <p class="flex justify-between items-center text-center gap-2">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/pencil.svg')); ?>" alt="" class="h-6 w-6">
                        <span class="vazir font-semibold">فورم تبدیل ارز در حساب</span>
                    </p>

                    <button wire:click="toggleAccountType" class="rounded-[8px] p-[10px] text-white vazir px-12 font-semibold transition-colors duration-500 ease-in-out
                        <?php echo e($accountType === 'نقدی' ? 'bg-[#2563EB]' : 'bg-[#DD2424]'); ?>">
                        <?php echo e($accountType === 'نقدی' ? 'نقدی' : 'بانکی'); ?>

                    </button>
                </div>

                <!-- فرم اصلی -->
                <form wire:submit.prevent="submitConversion">
                    <!-- انتخاب حساب -->
                    <div class="mt-2">
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب مشتری</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                init() {
                                    this.updateDisplay();
                                    
                                    $wire.on('edit-mode-activated', (data) => {
                                        this.selectedId = data.selectedAccount;
                                        this.searchValue = data.selectedCustomer;
                                        setTimeout(() => {
                                            this.updateDisplay();
                                        }, 100);
                                    });
                                    
                                    $wire.on('transactionTypeToggled', () => {
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
                                        $wire.selectAccount(selected.id);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('selectedAccount', null);
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
                                <input list="customersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب مشتری..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="customersList">
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
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

                    <!-- بخش تبدیل ارز -->
                    <div class="mt-4 rounded-[12px]">
                        <div class="mt-2 flex flex-col lg:flex-row gap-3">
                            <!-- ارز مبدا -->
                            <div class="lg:w-[191px]">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">ارز مبدا</label>
                                <div class="relative w-full">
                                    <select wire:model="from_currency"
                                        class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                                <?php $__errorArgs = ['from_currency'];
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

                            <!-- مبلغ خرید -->
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ خرید</label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="buy_amount" placeholder="0"
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                </div>
                                <?php if($withdrawalAmountInWords): ?>
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong></strong> <?php echo e($withdrawalAmountInWords); ?>

                                </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['buy_amount'];
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
                    </div>

                    <!-- بخش دریافت -->
                    <div class="mt-4 rounded-[12px]">
                        <div class="mt-2 flex flex-col lg:flex-row gap-3">
                            <!-- ارز مقصد -->
                            <div class="lg:w-[191px]">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">ارز مقصد</label>
                                <div class="relative w-full">
                                    <select wire:model="to_currency"
                                        class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
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
                                <?php $__errorArgs = ['to_currency'];
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

                            <!-- نرخ ارز -->
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium text-black mb-1 vazir">نرخ ارز</label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="currency_rate" placeholder="0.0000"
                                        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                    <?php $__errorArgs = ['currency_rate'];
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
                                <?php if($currencyRateInWords): ?>
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong></strong> <?php echo e($currencyRateInWords); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- مبلغ فروش و تاریخ -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- مبلغ فروش -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ فروش</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="sell_amount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 bg-gray-100"
                                    readonly />
                            </div>
                            <?php if($receivedAmountInWords): ?>
                            <div class="mt-2 text-sm text-gray-600">
                                <strong></strong> <?php echo e($receivedAmountInWords); ?>

                            </div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['sell_amount'];
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

                        <!-- تاریخ -->
                        <div class="flex-1 relative">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="transaction_date" placeholder="1404/4/20"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                <svg class="absolute left-3 bottom-2 -translate-y-1/2 pointer-events-none" width="20"
                                    height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                    <path
                                        d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                        stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <path
                                        d="M15.6947 13.7H15.7037M15.6947 16.7H15.7037M11.9955 13.7H12.0045M11.9955 16.7H12.0045M8.29431 13.7H8.30329M8.29431 16.7H8.30329"
                                        stroke="#8C8C8C" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <?php $__errorArgs = ['transaction_date'];
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
                    </div>

                    <!-- اطلاعات مسئولین -->
                    <div class="mt-2 flex flex-col lg:flex-row gap-3">
                        <!-- توسط برداشت -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (برداشت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_sender" placeholder="نام مسئول برداشت"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                <?php $__errorArgs = ['by_sender'];
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

                        <!-- توسط دریافت -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (دریافت)</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="by_receiver" placeholder="نام مسئول دریافت"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500" />
                                <?php $__errorArgs = ['by_receiver'];
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
                    </div>

                    <!-- زون‌ها -->
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون برداشت</label>
                            <select wire:model="zone_sender"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب زون</option>
                                <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($zone); ?>"><?php echo e($zone); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['zone_sender'];
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

                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">زون دریافت</label>
                            <select wire:model="zone_receiver"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب زون</option>
                                <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($zone); ?>"><?php echo e($zone); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['zone_receiver'];
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


                    <!-- شرح تراکنش -->
                    <div class="mt-3">
                        <div class="w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح تراکنش</label>
                            <textarea wire:model="description" rows="3" placeholder="شرح کامل تبدیل ارز..."
                                class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 resize-none"></textarea>
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

                    <!-- دکمه‌های نهایی -->
                    <div class="flex flex-wrap justify-center items-center gap-4 py-4 text-center">
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-[#2563EB] text-[14px] vazir font-semibold rounded-[8px] px-16 py-4 text-white hover:bg-blue-700 transition disabled:opacity-50">
                            <?php if($editingConversionId): ?>
                            <span wire:loading.remove>ویرایش تبدیل ارز</span>
                            <?php else: ?>
                            <span wire:loading.remove>ثبت تبدیل ارز</span>
                            <?php endif; ?>
                            <span wire:loading>در حال ثبت...</span>
                        </button>

                        <button type="button" wire:click="resetForm" wire:loading.attr="disabled"
                            class="bg-[#DD2424] text-[14px] vazir font-semibold rounded-[8px] px-16 py-4 text-white hover:bg-red-700 transition">
                            <?php if($editingConversionId): ?> انصراف از ویرایش <?php else: ?> انصراف <?php endif; ?>
                        </button>
                    </div>

                </form>
            </div>

            <!-- جدول تراکنش‌های تبدیل ارز -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]  w-[440px] mb-5 md:w-[1010px] lg:w-[150px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-full ">
                            <input type="text" wire:model.live="search" wire:keydown.debounce.500ms="search"
                                class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                                placeholder="جستجو بر اساس نام،...">

                            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                            <?php if($search): ?>
                            <button wire:click="$set('search', '')"
                                class="absolute left-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- جدول -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table class="w-[890px] text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead
                                class="bg-[#2B65E5] text-white text-[14px] md:text-[16px] vazir h-[50px] md:h-[60px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-2 py-3 font-bold w-12">#</th>
                                    <th class="px-2 py-3 font-bold w-32">مشتری</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ ارز برداشت</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ ارز دریافت</th>
                                    <th class="px-2 py-3 font-bold w-24">نرخ ارز</th>
                                    <th class="px-2 py-3 font-bold w-36 text-center">توضیحات</th>
                                    <th class="px-2 py-3 font-bold w-28">تاریخ</th>
                                    <th class="px-2 py-3 font-bold w-32 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $conversionTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $conversion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium w-32">
                                        <div class="truncate" title="<?php echo e($conversion->customer->fullname ?? '-'); ?>">
                                            <?php echo e($conversion->customer->fullname ?? '-'); ?>

                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php echo e($conversion->customer->account_number ?? ''); ?>

                                        </div>
                                    </td>
                                    <td class="px-3 py-3 vazir text-[13px] md:text-[16px] font-medium w-52">
                                        <div class="text-left">
                                            <span class=""><?php echo e(number_format($conversion->buy_amount)); ?> (<?php echo e($this->getCurrencyName($conversion->from_currency)); ?>)</span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[16px] w-44">
                                        <div class="text-left">
                                            <span class=""><?php echo e(number_format($conversion->sell_amount)); ?> (<?php echo e($this->getCurrencyName($conversion->to_currency)); ?>)</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px] w-44">
                                        <?php echo e(number_format($conversion->currency_rate, 2)); ?>

                                    </td>
                                    <td class="px-2 py-3 vazir text-[13px] md:text-[18px] font-medium w-36">
                                        <div class="text-right truncate" title="<?php echo e($conversion->description); ?>">
                                            <?php echo e(Str::limit($conversion->description, 35)); ?>

                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium text-[16px]">
                                                <?php echo e(explode(' ', $conversion->transaction_date)[0]); ?>

                                            </div>
                                            <div class="text-gray-500 text-[16px] mt-1">
                                                <?php echo e(\Carbon\Carbon::parse($conversion->created_at)->format('h:i A')); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center w-32">
                                        <div class="flex justify-center gap-2">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="editConversion(<?php echo e($conversion->id); ?>)"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-blue-100"
                                                title="ویرایش">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete(<?php echo e($conversion->id); ?>)"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                                title="حذف">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                    class="w-7 h-7" alt="Delete">
                                            </button>

                                            <button wire:click="printTransaction(<?php echo e($conversion->id); ?>)"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                                title="پرینت PDF">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                    class="w-9 h-9" alt="Print">
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 vazir text-[14px]">
                                        هیچ تراکنش تبدیلی یافت نشد.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- صفحه‌بندی -->
                <?php if($conversionTransactions->hasPages()): ?>
                <div class="mt-4 px-4">
                    <?php echo e($conversionTransactions->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- مودال تأیید حذف -->
    <?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن" class="w-4 h-4">
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
    <?php endif; ?>

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

        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        input[list]::-moz-list-button {
            display: none !important;
        }

        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }
    </style>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/conversion-in-account.blade.php ENDPATH**/ ?>