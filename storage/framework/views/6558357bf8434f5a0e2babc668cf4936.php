<div>
    <div class="container mx-auto px-4">
        <?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
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

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
    <!-- در کارت‌های ارزها -->
    <?php $__currentLoopData = $currenciesdefault; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="w-full">
        <div class="flex flex-col h-[140px] sm:h-[180px] md:h-[155px] w-full p-4 md:pr-5 md:pl-5 md:pt-3 rounded-[12px]
              bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white rounded-xl shadow-lg transition-all duration-300">

            <h1 class="text-[18px] sm:text-[20px] md:text-[24px] text-white truncate"><?php echo e($currency['name']); ?></h1>
            <h2 class="text-center text-[24px] sm:text-[26px] md:text-[30px] text-white mt-2"><?php echo e(number_format($currency['value'])); ?></h2>
            <button wire:click="showReport" wire:loading.attr="disabled"
                class="bg-white rounded-[12px] text-[14px] sm:text-[16px] p-1 mt-2 text-gray-800 hover:shadow-md transition flex items-center justify-center gap-2">
                <span wire:loading.remove>نمایش گزارش</span>
                <span wire:loading>
                    در حال انتقال...
                </span>
            </button>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            
            <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[470px] p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                
                <div
                    class="flex flex-row justify-between p-[20px] border border-[#8C8C8C] rounded-[12px] flex-wrap items-center">
                    <p class="flex justify-between items-center text-center gap-1">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/pencil.svg')); ?>" alt="" class="h-6 w-6">
                        <span class="vazir font-semibold">فورم انتفال بین حسابات</span>
                    </p>
                
                    <button type="button" wire:click="toggleTransactionType"
                        class="rounded-[8px] px-6 py-3 text-white vazir font-semibold text-sm md:text-base
                            transition-all duration-300 ease-in-out transform hover:scale-105
                            <?php echo e($transactionType === 'باتفاوت' ? 'bg-gradient-to-br from-black to-blue-500  text-white p-6 rounded-xl shadow-lg transition-all duration-300' : '   bg-gradient-to-br from-black to-red-500  text-pink-800 p-6 rounded-xl shadow-lg transition-all duration-300 '); ?>">
                        <?php echo e($transactionType === 'باتفاوت' ? 'باتفاوت کمیشن' : 'بدون تفاوت کمیشن'); ?>

                    </button>

                </div>

                
                <form wire:submit.prevent="submitConversion" class="space-y-6">

                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-2">
                        
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب مبدا</label>
                            <div x-data="{
                searchValue: '',
                selectedId: <?php if ((object) ('withdrawalAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('withdrawalAccount'->value()); ?>')<?php echo e('withdrawalAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('withdrawalAccount'); ?>')<?php endif; ?>,
                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                init() {
                    this.updateDisplay();
                    $wire.on('edit-mode-activated', (data) => {
                        this.selectedId = data.withdrawalAccount;
                        this.searchValue = data.withdrawalCustomer;
                        setTimeout(() => this.updateDisplay(), 100);
                    });
                    $wire.on('accountsSwapped', () => setTimeout(() => this.updateDisplay(), 100));
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
                                    placeholder="جستجو یا انتخاب حساب بردگی..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="withdrawalCustomersList">
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
                            </div>
                            <?php $__errorArgs = ['withdrawalAccount'];
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب مقصد</label>
                            <div x-data="{
                searchValue: '',
                selectedId: <?php if ((object) ('depositAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('depositAccount'->value()); ?>')<?php echo e('depositAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('depositAccount'); ?>')<?php endif; ?>,
                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                init() {
                    this.updateDisplay();
                    $wire.on('edit-mode-activated', (data) => {
                        this.selectedId = data.depositAccount;
                        this.searchValue = data.depositCustomer;
                        setTimeout(() => this.updateDisplay(), 100);
                    });
                    $wire.on('accountsSwapped', () => setTimeout(() => this.updateDisplay(), 100));
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
                                    placeholder="جستجو یا انتخاب حساب رسیدگی..."
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="depositCustomersList">
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
                            </div>
                            <?php $__errorArgs = ['depositAccount'];
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

                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ پول</label>
                            <input type="text" wire:model.live="withdrawal_amount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                            <?php if($withdrawalAmountInWords): ?>
                            <div class="mt-2 text-sm text-gray-600"><?php echo e($withdrawalAmountInWords); ?></div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['withdrawal_amount'];
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

                        
                        <?php if($transactionType === 'باتفاوت'): ?>
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ کمیشن</label>
                            <input type="text" wire:model.live="commission_amount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                            <?php $__errorArgs = ['commission_amount'];
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ قابل انتقال
                                </label>
                            <input type="text" wire:model="received_amount" placeholder="0" readonly
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-gray-100 focus:ring-2 focus:ring-blue-500" />
                            <?php if($receivedAmountInWords): ?>
                            <div class="mt-2 text-sm text-gray-600"><?php echo e($receivedAmountInWords); ?></div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حساب کمیشن</label>
                            <div x-data="{
                searchValue: '',
                selectedId: <?php if ((object) ('commissionAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('commissionAccount'->value()); ?>')<?php echo e('commissionAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('commissionAccount'); ?>')<?php endif; ?>,
                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                init() {
                    this.updateDisplay();
                    $wire.on('edit-mode-activated', (data) => {
                        this.selectedId = data.commissionAccount;
                        this.searchValue = data.commissionCustomer;
                        setTimeout(() => this.updateDisplay(), 100);
                    });
                },
                handleSelect(event) {
                    const selected = this.customers.find(
                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                    );
                    if (selected) {
                        this.selectedId = selected.id;
                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                        $wire.set('commissionAccount', selected.id);
                    } else {
                        this.selectedId = null;
                        this.searchValue = '';
                        $wire.set('commissionAccount', null);
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
                                <input list="commissionCustomersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="حساب دریافت کمیشن"
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="commissionCustomersList">
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                                </div>
                            </div>
                            <?php $__errorArgs = ['commissionAccount'];
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
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">حالت انتقال</label>
                            <input type="text" value="انتقال با کمیشن" readonly
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-gray-100 focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <?php endif; ?>

                        
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">ارز</label>
                            <select wire:model="currency"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب ارز</option>
                                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
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

                  
                    </div>

                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (برداشت)</label>
                            <input type="text" wire:model="by_sender" placeholder="نام مسئول برداشت"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent" />
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">توسط (دریافت)</label>
                            <input type="text" wire:model="by_receiver" placeholder="نام مسئول دریافت"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 bg-transparent" />
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" wire:model="transaction_date" placeholder="1404/4/20"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبر سند</label>
                            <input type="text" placeholder="13425"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                
                    
                    <div class="mt-3">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح بردگی</label>
                        <textarea wire:model="description_sender" rows="3" placeholder="شرح بردگی..."
                            class="w-full p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    
                    <div class="mt-3">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">شرح رسیدگی</label>
                        <textarea wire:model="description_receiver" rows="3" placeholder="شرح رسیدگی..."
                            class="w-full p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-gradient-to-br from-black to-blue-400  text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-2 text-white hover:bg-blue-700 transition disabled:opacity-50">
                            <?php if($editingConversionId): ?>
                            ویرایش تبدیل ارز
                            <?php else: ?>
                            ثبت تبدیل ارز
                            <?php endif; ?>
                        </button>
                        <button type="button" wire:click="resetForm" wire:loading.attr="disabled"
                            class="bg-[#DD2424] text-[14px] vazir font-semibold rounded-[8px] px-[74px] py-2 text-white hover:bg-red-700 transition">
                            <?php if($editingConversionId): ?>
                            انصراف از ویرایش
                            <?php else: ?>
                            انصراف
                            <?php endif; ?>
                        </button>
                    </div>
                </form>

            </div>

            
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-[440px] mb-5 md:w-[430px] lg:w-[200px"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-full md:w-[250px]">
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

                
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table class="w-[890px] text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead
                                class="bg-gradient-to-br from-black to-blue-400 text-white text-[14px] md:text-[18px] vazir h-[50px] md:h-[60px] sticky top-0"
                                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                                <tr>
                                    <th class="px-2 py-3 font-bold w-12">#</th>
                                    <th class="px-2 py-3 font-bold w-32">از حساب</th>
                                    <th class="px-2 py-3 font-bold w-32">به حساب</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ برداشت</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ دریافت</th>
                                    <th class="px-2 py-3 font-bold w-24">نوع انتقال</th>
                                    <th class="px-2 py-3 font-bold w-36 text-center">توضیحات</th>
                                    <th class="px-2 py-3 font-bold w-28">تاریخ</th>
                                    <th class="px-2 py-3 font-bold w-32 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $SendToAccount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $conversion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-1 py-3 vazir text-[16px] md:text-[18px] font-medium w-28">
                                        <div class="truncate" title="<?php echo e($conversion->from_customer_name ?? '-'); ?>">
                                            <?php echo e($conversion->from_customer_name ?? '-'); ?>

                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[16px] md:text-[18px] font-medium w-14">
                                        <div class="truncate" title="<?php echo e($conversion->to_customer_name ?? '-'); ?>">
                                            <?php echo e($conversion->to_customer_name ?? '-'); ?>

                                        </div>
                                    </td>
                                    <td class="px-1  py-3 vazir text-[13px] md:text-[16px] font-medium w-52">
                                        <div class="text-left">
                                            <span class=""><?php echo e(number_format($conversion->withdrawal_amount)); ?>

                                                <?php echo e($this->getCurrencyName($conversion->from_currency)); ?></span>
                                            <?php if($conversion->type === 'باتفاوت' && $conversion->tax_amount > 0): ?>
                                            <div class="text-xs text-red-600">
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[16px] md:text-[16px]  w-44">
                                        <div class="text-left">
                                            <span class=""><?php echo e(number_format($conversion->received_amount)); ?>

                                                <?php echo e($this->getCurrencyName($conversion->from_currency)); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px]  w-44">
                                        <?php if($conversion->type === 'باتفاوت'): ?>
                                        <span class="text-red-600">باتفاوت</span>
                                        <?php else: ?>
                                        <span class="text-green-600">بدون تفاوت</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px] font-medium w-36">
                                        <div class="text-right truncate" title="<?php echo e($conversion->description_sender); ?>">
                                            <?php echo e(Str::limit($conversion->description_sender, 35)); ?>

                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                          <?php echo e(explode(' ', $conversion->transaction_date)[0]); ?>

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

                                            <!-- دکمه پرینت -->
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
                                    <td colspan="9" class="px-4 py-4 text-center text-gray-500 vazir text-[14px]">
                                        هیچ تراکنش تبدیلی یافت نشد.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <?php if($SendToAccount->hasPages()): ?>
                <div class="mt-4 px-4">
                    <?php echo e($SendToAccount->links()); ?>

                </div>
                <?php endif; ?>
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

                    <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف تراکنش تبدیل ارز
                    </h1>
                    <hr class="bg-[#E1DED3] mt-4 mx-4">
                    <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این تراکنش را حذف کنید؟</p>
                    <div class="flex justify-center gap-4">
                        <button wire:click="$set('confirmDeleteId', null)"
                            class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                            خیر
                        </button>
                        <button wire:click="deleteConversion"
                            class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                            بلی
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <script>
            window.addEventListener('report-alert', event => {
                alert(event.detail.message);
            });
        </script>

        
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
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/account-to-account.blade.php ENDPATH**/ ?>