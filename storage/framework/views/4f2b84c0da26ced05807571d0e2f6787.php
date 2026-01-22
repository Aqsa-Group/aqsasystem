<div>
    <div class="container mx-auto px-0">
        <?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-400 bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?>


        <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] dark:bg-gradient-to-b dark:from-red-500 dark:to-red-700 bg-red-600 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('error')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?>

 <!-- Header Section -->
        <div class="mb-2 text-right space-y-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white vazir mb-1">
               بخش پرداخت معاشات کارمندان
            </h1>
            <p class="text-base text-gray-600 dark:text-gray-300 vazir">
           پرداخت معاشات کارمندان بر حسب حاضری
            </p>

    <!-- Main Container -->
    <div class="w-full max-w-[1400px] mx-auto p-4">

       
        </div>




        
        <?php if($paymentMethod === 'کارتی' && $selectedCustomer): ?>
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3">
            
            <div class="inline-block align-top ml-4 h-auto">
                <div
                    class="flex flex-col h-[180px] w-[273px] pr-5 pl-5 pt-2 rounded-[12px] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#20559c] to-[#3065b5] text-white">
                    
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

            
            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            ?>

            <div class="inline-block align-top ml-4 h-auto">
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
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            
            <div class="flex flex-col dark:bg-black dark:text-white dark:border dark:border-white  bg-white   border border-[#D7E5EC] shadow-sm backdrop:blur-lg  mx-auto w-full max-w-[420px] lg:max-w-[474px] p-[10px] h-auto rounded-[12px] space-y-2"
              >

                
                <div
                    class="flex dark:border-white space-y-3 flex-row justify-between p-[10px]  rounded-[12px] flex-wrap">
                    <p class="flex justify-center items-center text-center">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">
                        فورم پرداخت معاشات
                    </p>

                    <div class="flex gap-4 flex-wrap">
                        <button wire:click="setPaymentMethod('نقدی')" class="rounded-[8px] p-[10px] text-white vazir font-semibold transition-colors duration-500 ease-in-out
                            <?php echo e($paymentMethod === 'نقدی' ?  'bg-[#184D6C]' : 'bg-[#FFFF] border border-[#184D6C] text-black'); ?>">
                            پرداخت نقدی
                        </button>

                        <button wire:click="setPaymentMethod('کارتی')" class="rounded-[8px] p-[10px] text-white vazir font-semibold transition-colors duration-500 ease-in-out
                            <?php echo e($paymentMethod === 'کارتی' ? 'bg-[#184D6C]' : 'bg-[#FFFF] border border-[#184D6C] text-black'); ?>">
                            پرداخت کارتی
                        </button>
                    </div>
                </div>

                
                <form wire:submit.prevent="paySalary">
                    
                    <div class="mt-2 grid grid-cols-1 gap-3">
                        <div class="flex-1 w-full">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    انتخاب کارمند
                                </label>
                                <select wire:model.live="selectedStaffId"
                                    class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] dark:border focus:ring-2 focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white appearance-none">
                                    <option value="">انتخاب کارمند</option>
                                    <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($staff->id); ?>">
                                        <?php echo e($staff->name); ?> <?php echo e($staff->fathername); ?> - <?php echo e($staff->job); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                     <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <?php $__errorArgs = ['selectedStaffId'];
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

                    
                    <?php if($staffDetails): ?>
                    <div dir="rtl"
                        class="mt-3 p-4  bg-[#EFF6F9] dark:border rounded-xl  dark:bg-green-900/20 space-y-4 text-sm">

                        
                        <h3 class="font-bold text-green-700 dark:text-green-300 text-base">
                            اطلاعات کارمند
                        </h3>

                        
                        <div class="space-y-2">

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">نام:</span>
                                <span class="font-semibold">
                                    <?php echo e($staffDetails->name); ?> <?php echo e($staffDetails->fathername); ?>

                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">شغل:</span>
                                <span class="font-semibold"><?php echo e($staffDetails->job); ?></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">حقوق ماهیانه:</span>
                                <span class="font-semibold text-green-700 dark:text-green-400">
                                    <?php echo e(number_format($staffDetails->final_salary)); ?> افغانی
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">تاریخ شروع:</span>
                                <span class="font-semibold ltr">
                                    <?php echo e(explode(' ', $staffDetails->contract_start)[0]); ?>

                                </span>
                            </div>

                            <?php if($staffDetails->customer_id): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">مشتری ثبت شده:</span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400">
                                    <?php echo e($staffDetails->customer->fullname ?? 'ندارد'); ?>

                                </span>
                            </div>
                            <?php endif; ?>

                        </div>

                        
                        <div class="border-t pt-3 space-y-2">

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">روزهای دارای حضور:</span>
                                <span class="font-bold text-blue-600 dark:text-blue-400">
                                    <?php echo e($dueDays); ?> روز
                                </span>
                            </div>

                            <div class="flex justify-between text-base">
                                <span class="text-gray-600 dark:text-gray-300">
                                    مبلغ قابل پرداخت (بر اساس حضور):
                                </span>
                                <span class="font-bold text-green-700 dark:text-green-400">
                                    <?php echo e(number_format($dueAmount)); ?> افغانی
                                </span>
                            </div>

                        </div>

                        
                        <?php if(count($attendanceData) > 0): ?>
                        <div class="border-t pt-3">

                            <h4 class="font-bold text-blue-700 dark:text-blue-300 mb-2">
                                جزئیات روزهای حضور
                            </h4>

                            <div class="max-h-40 overflow-y-auto text-xs border rounded-lg">
                                <table class="w-full text-center">
                                    <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0">
                                        <tr>
                                            <th class="p-2">تاریخ</th>
                                            <th class="p-2">صبح</th>
                                            <th class="p-2">شام</th>
                                            <th class="p-2">مرخصی</th>
                                            <th class="p-2">معاش</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $attendanceData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr
                                            class="border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="p-1 ltr"><?php echo e($day['date']); ?></td>

                                            <td class="p-1">
                                                <span
                                                    class="<?php echo e($day['morning_present'] ? 'text-green-600' : 'text-red-600'); ?>">
                                                    <?php echo e($day['morning_present'] ? '✓' : '✗'); ?>

                                                </span>
                                            </td>

                                            <td class="p-1">
                                                <span
                                                    class="<?php echo e($day['evening_present'] ? 'text-green-600' : 'text-red-600'); ?>">
                                                    <?php echo e($day['evening_present'] ? '✓' : '✗'); ?>

                                                </span>
                                            </td>

                                            <td class="p-1">
                                                <?php echo e($day['leave_type'] !== 'none' ? $day['leave_type'] : '-'); ?>

                                            </td>

                                            <td class="p-1 font-bold text-green-700 dark:text-green-400">
                                                <?php echo e(number_format($day['salary'])); ?>

                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>


                    
                    <?php if($paymentMethod === 'کارتی'): ?>
                    <div class="mt-3 grid grid-cols-1 gap-3">
                        <div class="flex-1 w-full">
                            <div class="relative w-full">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    <?php if($staffDetails && $staffDetails->customer_id): ?>
                                   کارت کارمند
                                    <?php else: ?>
                                    انتخاب کارت کارمند
                                    <?php endif; ?>
                                </label>
                                <?php if($staffDetails && $staffDetails->customer_id): ?>
                                <div
                                    class="w-full h-[60px] p-3 rounded-[12px]  border border-[#8C8C8C] bg-transparent dark:bg-green-900/20 flex items-center justify-between">
                                    <div>
                                        <span class="font-bold"><?php echo e($selectedCustomer->fullname ??
                                            $staffDetails->customer->fullname ?? 'مشتری'); ?></span>
                                        <span class="text-sm text-gray-600 dark:text-gray-300 mr-2">- <?php echo e($selectedCustomer->account_number ?? $staffDetails->customer->account_number
                                            ?? ''); ?></span>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div x-data="{
                                            searchValue: '',
                                            selectedId: <?php if ((object) ('selectedCustomerId') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedCustomerId'->value()); ?>')<?php echo e('selectedCustomerId'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedCustomerId'); ?>')<?php endif; ?>,
                                            customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                            handleSelect(event) {
                                                const selected = this.customers.find(
                                                    c => event.target.value === `${c.account_number} - ${c.fullname}`
                                                );
                                                if (selected) {
                                                    this.selectedId = selected.id;
                                                    this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                                    $wire.selectCustomer(selected.id);
                                                } else {
                                                    this.selectedId = null;
                                                    this.searchValue = '';
                                                    $wire.set('selectedCustomerId', null);
                                                }
                                            },
                                            updateDisplay() {
                                                const selected = this.customers.find(c => c.id === this.selectedId);
                                                this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                            }
                                        }" x-init="updateDisplay();
                                        $watch('selectedId', () => updateDisplay())" class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب مشتری..."
                                        class="w-full h-[60px] dark:bg-black dark:text-white dark:border-white dark:placeholder:text-white p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">
                                    <datalist id="customersList">
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </datalist>
                                    <?php if(empty($selectedCustomerId)): ?>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                                stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['selectedCustomerId'];
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
                    <?php endif; ?>

                    
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        
                        <div class="flex-1">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full dark:border-white dark:bg-black dark:placeholder:text-white h-[60px] p-3 rounded-[12px] dark:border focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:text-white"
                                    readonly>
                            </div>
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

                        
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نوع
                                ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 dark:text-white appearance-none">
                                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                            stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
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

                    
                    <div class="mt-2">
                        <div class="lg:w-full relative" x-data="persianDatePicker()" x-init="init()">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ
                                پرداخت</label>
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
                                readonly />
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

                    
                    <div class="mt-3">
                        <textarea wire:model="description" rows="3" placeholder="شرح پرداخت..."
                            class="w-full p-3 rounded-[12px] dark:border focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white resize-none"></textarea>
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

                    
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 py-4 justify-center items-center text-center">
                        <button type="submit" wire:loading.attr="disabled" wire:target="paySalary"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            <span wire:loading.remove wire:target="paySalary">
                                پرداخت حقوق
                            </span>
                            <span wire:loading wire:target="paySalary" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                در حال پرداخت
                            </span>
                        </button>

                        <button type="button" wire:click="clearFilter"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            پاک کردن
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="flex-1 flex flex-col dark:border dark:border-white dark:bg-black dark:text-white                         bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC]
 p-3 md:p-4 lg:p-6 rounded-[12px] w-full max-w-[440px] md:max-w-[410px] lg:max-w-full mb-5 mx-auto overflow-x-auto"
                >

                
                <div
                    class="grid grid-cols-1 md:grid-cols-1 dark:border-white xl:grid-cols-2 justify-between items-center  p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">تاریخچه پرداخت معاشات</h1>

                    <div class="flex items-center gap-3">
                        <?php if($selectedStaffId && $staffDetails): ?>
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">کارمند: <?php echo e($staffDetails->name); ?> <?php echo e($staffDetails->fathername); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                                <tr class="whitespace-nowrap">
                                    <th class="px-4 py-4 font-bold w-16">#</th>
                                    <th class="px-4 py-4 font-bold w-32">تاریخ پرداخت</th>
                                    <th class="px-4 py-4 font-bold w-48">کارمند</th>
                                    <th class="px-4 py-4 font-bold w-32">روش پرداخت</th>
                                    <th class="px-4 py-4 font-bold w-48">مشتری (برای کارتی)</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-48 text-center">ثبت کننده</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $salaryHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $salary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-32">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">
                                                <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($salary->paid_date)->format('Y/m/d')); ?>

                                            </div>
                                            <div class="text-gray-500 dark:text-white text-sm mt-1">
                                                <?php echo e(\Carbon\Carbon::parse($salary->created_at)->format('h:i A')); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <?php echo e($salary->staff->name ?? '-'); ?> <?php echo e($salary->staff->fathername ?? ''); ?>

                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <span
                                            class="px-3 py-1 rounded-full text-[16px] 
                                                <?php echo e($salary->payment_method === 'نقدی' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'); ?>">
                                            <?php echo e($salary->payment_method); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <?php echo e($salary->customer->fullname ?? '-'); ?>

                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <?php echo e(number_format($salary->amount)); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <?php echo e(collect($currencies)->firstWhere('code', $salary->currency)['name_fa'] ??
                                        $salary->currency); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <?php echo e($salary->description); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-48">
                                        <?php echo e($salary->admin->name ?? $salary->user->name ?? '-'); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-gray-500 py-8 text-lg">
                                        <?php if($selectedStaffId): ?>
                                        هیچ پرداختی برای این کارمند ثبت نشده است
                                        <?php else: ?>
                                        لطفاً کارمند را انتخاب کنید
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

                monthsAfghan: [
                    'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
                    'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
                ],

                weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],

                daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],

                init() {
                    this.updateYearRange();
                    const today = this.getTodayPersian();
                    this.currentYear = today.year;
                    this.currentMonth = today.month - 1;

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
                    if (month === 11 && this.isLeapYear(year)) {
                        return 30;
                    }
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
                    const gy = today.getFullYear();
                    const gm = today.getMonth() + 1;
                    const gd = today.getDate();

                    return this.gregorianToPersian(gy, gm, gd);
                },

                gregorianToPersian(gy, gm, gd) {
                    const gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    const isGregorianLeap = (gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0);

                    if (isGregorianLeap) {
                        gDaysInMonth[1] = 29;
                    }

                    let dayOfYear = gd;
                    for (let i = 0; i < gm - 1; i++) {
                        dayOfYear += gDaysInMonth[i];
                    }

                    const marchDay = 79;
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
                        persianMonth++;
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
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/salary.blade.php ENDPATH**/ ?>