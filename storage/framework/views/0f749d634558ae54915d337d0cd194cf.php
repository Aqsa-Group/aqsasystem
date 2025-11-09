<div >
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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6  pb-0 pt-1">

        <!-- کارت ۱: کل مبلغ قرضه -->
        <div
            class="bg-gradient-to-br from-rose-100 to-rose-200 border-l-4 border-rose-500 text-rose-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">مجموعه بردگی ها</h3>
                <div class="bg-rose-500 p-2 rounded-full">
                    <i class="fa-solid fa-hand-holding-dollar text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-medium">افغانی:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['total_loan']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">دالر:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['total_loan']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">تومان:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['total_loan']['irr'])); ?></span>
                </div>
            </div>
        </div>

        <!-- کارت ۲: کل مبلغ پرداختی -->
        <div
            class="bg-gradient-to-br from-green-100 to-green-200 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">مجموعه رسیدها</h3>
                <div class="bg-green-500 p-2 rounded-full">
                    <i class="fa-solid fa-money-bill-trend-up text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-medium">افغانی:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['total_paid']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">دالر:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['total_paid']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">تومان:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['total_paid']['irr'])); ?></span>
                </div>
            </div>
        </div>

        <!-- کارت ۳: مانده قرضه -->
        <div
            class="bg-gradient-to-br from-blue-100 to-blue-200 border-l-4 border-blue-500 text-blue-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">الباقی قرضه</h3>
                <div class="bg-blue-500 p-2 rounded-full">
                    <i class="fa-solid fa-scale-balanced text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-medium">افغانی:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['remaining_loan']['afn'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">دالر:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['remaining_loan']['usd'])); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">تومان:</span>
                    <span class="font-bold"><?php echo e(number_format($loanCards['remaining_loan']['irr'])); ?></span>
                </div>
            </div>
        </div>

        <!-- کارت ۴: درصد بازپرداخت -->
        <div
            class="bg-gradient-to-br from-purple-100 to-purple-200 border-l-4 border-purple-500 text-purple-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">درصد بازپرداخت</h3>
                <div class="bg-purple-500 p-2 rounded-full">
                    <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-medium">افغانی:</span>
                    <span class="font-bold"><?php echo e($loanCards['percentage']['afn']); ?>%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">دالر:</span>
                    <span class="font-bold"><?php echo e($loanCards['percentage']['usd']); ?>%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-medium">تومان:</span>
                    <span class="font-bold"><?php echo e($loanCards['percentage']['irr']); ?>%</span>
                </div>
            </div>
        </div>

    </div>


    
    <div class="flex flex-col lg:flex-row gap-5 mt-4 ">

        
        <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[494px] p-[12px] h-auto rounded-[12px] space-y-2"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div class="flex flex-row justify-between p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap mb-5">
                <p class="flex justify-center items-center text-center">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">
                    <?php echo e($transactionId ? 'فورم ویرایش قرضه' : 'فورم ثبت قرضه'); ?>

                </p>

                <div class="flex gap-4 flex-wrap">

                    <button wire:click="toggleTransactionType"
                        class="rounded-[8px] p-[10px] text-white vazir font-semibold
                                    transition-colors duration-500 ease-in-out
                                    <?php echo e($transactionType === 'برد' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500' : 'bg-[#DD2424]'); ?>">
                        <?php echo e($transactionType === 'برد' ? 'رسید (دریافت صندوق)' : 'برد (برداشت صندوق)'); ?>

                    </button>
                </div>
            </div>

            
            <form wire:submit.prevent="submitLoan" class="space-y-6">

                <!-- انتخاب مشتری و نوع ارز -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- انتخاب مشتری -->
                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">انتخاب مشتری</label>
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
                }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())" class="relative w-full">
                            <input list="customersList" x-model="searchValue" @change="handleSelect"
                                placeholder="جستجو یا انتخاب مشتری..."
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

                    <!-- نوع ارز -->
                    <div class="lg:w-[191px]">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">نوع ارز</label>
                        <div class="relative w-full">
                            <select wire:model="currency" id="selectCurrency"
                                class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500">
                                <option value="">انتخاب ارز</option>
                                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓" class="w-4 h-4">
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

                <!-- مقدار و تاریخ -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">مقدار</label>
                        <div class="relative w-full">
                            <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500"
                                oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                        </div>
                        <?php if($amountInWords): ?>
                        <p class="text-sm text-blue-600 mt-2 vazir"><?php echo e($amountInWords); ?></p>
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

                    <div class="lg:w-[290px]">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">تاریخ</label>
                        <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                            class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 cursor-pointer" />
                    </div>
                </div>

                <!-- توضیحات -->
                <div>
                    <label class="block text-[16px] font-medium text-black mb-2 vazir">شرح قرضه</label>
                    <textarea wire:model="description" rows="3" placeholder="توضیحات قرضه..."
                        class="w-full p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
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

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-2">
                    <button type="submit"
                        class="bg-[#61B138] text-[16px] vazir font-semibold rounded-[8px] px-12 py-1 text-white">
                        <?php echo e($transactionId ? 'بروزرسانی' : 'ثبت'); ?>

                    </button>

                    <?php if(!$transactionId): ?>
                    <button type="button" wire:click="submitAndPrint"
                        class="bg-gradient-to-br from-indigo-400 to-indigo-500 text-[16px] vazir font-semibold rounded-[8px] px-12   py-1 text-white">
                        ثبت و چاپ
                    </button>
                    <?php endif; ?>

                    <button type="button" wire:click="cancel"
                        class="bg-[#DD2424] text-[16px] vazir font-semibold rounded-[8px] px-12 py-1 text-white">
                        <?php echo e($transactionId ? 'لغو ویرایش' : 'انصراف'); ?>

                    </button>
                </div>
            </form>

        </div>

        
        <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]  mt-[30px] sm:overflow-x-auto md:mt-0 lg:mt-0 mx-auto  md:mx-auto lg:mx-auto w-[440px] mb-5 md:w-[430px] lg:w-[200px] "
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div
                class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                <h1 class="text-lg md:text-xl lg:text-xl vazir">قرضه های ثبت شده</h1>

                <div class="flex items-center gap-3">
                    
                    <?php if($selectedCustomerId): ?>
                    <?php
                    $selectedCustomer = \App\Models\Tools\Customer::find($selectedCustomerId);
                    ?>
                    <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                        <span class="text-blue-700 vazir">فیلتر: <?php echo e($selectedCustomer->fullname ?? ''); ?></span>
                        <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                            ✕
                        </button>
                    </div>
                    <?php endif; ?>

                    <div class="relative w-full md:w-full">
                        <!-- Input جستجوی زنده با wire:model.live -->
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] w-[400px] md:w-full lg:w-[300px] h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                            placeholder="جستجو بر اساس نام یا نمبر حساب...">

                        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                        <!-- دکمه پاک کردن جستجو -->
                        <?php if($search): ?>
                        <button wire:click="clearSearchAndFilter"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                        <?php endif; ?>

                        <!-- لیست پیشنهادات -->
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

            
            <div class="overflow-x-auto w-full">
                <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                    <table
                        class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
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
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                    <?php echo e($key + 1); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                    <?php echo e($transaction->customer->fullname ?? '-'); ?>

                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                    <span
                                        class="px-3 py-1 rounded-full text-[16px] <?php echo e($transaction->type === 'رسید' ? ' text-green-800' : 'text-red-800'); ?>">
                                        <?php echo e($transaction->type); ?>

                                    </span>
                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                    <?php echo e(number_format($transaction->amount)); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                    <?php echo e(collect($currencies)->firstWhere('code', $transaction->currency)['name_fa']
                                    ?? $transaction->currency); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                    <div class="space-y-1 text-right">

                                        <p class="text-sm">تفصیلات: <?php echo e($transaction->description); ?></p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                    <div class="whitespace-nowrap">
                                        <div class="font-medium">

                                            <?php echo e(explode(' ', $transaction->date)[0]); ?>


                                        </div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('h:i A')); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center w-[68]">
                                    <div class="flex justify-center gap-3">
                                        <!-- دکمه ویرایش -->
                                        <button wire:click="edit(<?php echo e($transaction->id); ?>)" class="w-12 h-12 flex items-center justify-center  
                                                    rounded-full transition-colors" title="ویرایش">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                class="w-7 h-7" alt="Edit">
                                        </button>

                                        <!-- دکمه حذف -->
                                        <button wire:click="confirmDelete(<?php echo e($transaction->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="حذف">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                class="w-8 h-8" alt="Delete">
                                        </button>

                                        <!-- مودال تأیید حذف -->
                                        <?php if($confirmDeleteId): ?>
                                        <div
                                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
                                            <div
                                                class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
                                                <button wire:click="$set('confirmDeleteId', null)"
                                                    class="flex right-0 h-4 w-4"><img
                                                        src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>"
                                                        alt=""></button>
                                                <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                                                    حذف فرصـــه</h1>
                                                <hr class="bg-[#E1DED3] mt-8">
                                                <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                                                    قرضــه را حذف کنید؟</p>
                                                <div class="flex justify-center gap-4">
                                                    <button wire:click="$set('confirmDeleteId', null)"
                                                        class="px-20  text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                                                        <?php echo e(__('messages.no')); ?>

                                                    </button>
                                                    <button wire:click="deleteConfirmed"
                                                        class="px-20 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-xl shabnam-fd text-white rounded-xl  transition flex items-center gap-2">
                                                        <?php echo e(__('messages.yes')); ?>

                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>



                                        <!-- دکمه پرینت -->
                                        <button wire:click="print(<?php echo e($transaction->id); ?>)" class="w-12 h-12 flex items-center justify-center  
                                                rounded-full transition-colors" title="پرینت">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                class="w-10 h-10" alt="Print">
                                        </button>
                                    </div>
                                </td>


                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                    <?php if($selectedCustomerId): ?>
                                    هیچ تراکنشی برای این مشتری یافت نشد
                                    <?php else: ?>
                                    هیچ تراکنشی یافت نشد
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


    <script>
        document.addEventListener('livewire:load', function() {
        Livewire.hook('element.updated', (el, component) => {
            if (el.hasAttribute('wire:model') || el.hasAttribute('wire:click')) {
                document.querySelectorAll('.animate-number').forEach(el => {
                    const target = +el.getAttribute('data-target');
                    let count = 0;
                    const step = Math.ceil(target / 50);
                    const update = () => {
                        count += step;
                        if (count < target) {
                            el.textContent = count.toLocaleString('en-US');
                            requestAnimationFrame(update);
                        } else {
                            el.textContent = target.toLocaleString('en-US');
                        }
                    };
                    update();
                });
            }
        });
    });
    </script>

    <style>
        #selectCustomer {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            padding-left: 1rem;
        }

        #selectCurrency {
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

        .currency-card {
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .currency-row {
            padding: 4px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .currency-row:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .currency-card {
                min-height: 180px;
            }
        }
    </style>

</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/loans.blade.php ENDPATH**/ ?>