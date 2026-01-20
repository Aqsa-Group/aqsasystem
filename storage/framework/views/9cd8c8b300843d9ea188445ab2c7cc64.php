<div>
    <!-- Alert Component -->
    <!--[if BLOCK]><![endif]--><?php if($alert): ?>
    <div x-data="{
            show: true,
            init() {
                $wire.watch('alert', (value) => {
                    if (value) {
                        this.show = true;
                        setTimeout(() => {
                            this.show = false;
                            setTimeout(() => $wire.clearAlert(), 300);
                        }, 4000);
                    }
                });
        
                setTimeout(() => {
                    this.show = false;
                    setTimeout(() => $wire.clearAlert(), 300);
                }, 4000);
            }
        }" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] <?php echo e($alert['type'] === 'error' ? 'bg-red-500' : 'bg-[#2B65E5]'); ?> vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e($alert['message']); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="container mx-auto px-0">
        <!-- کارت‌های ارزها با اسکرول افقی -->
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3">


            <!-- کارت‌های ارزها -->
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $withdrawSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyName => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="inline-block align-top ml-4 h-auto vazir">
                <div dir="rtl" class="flex flex-col h-[180px] w-[273px]
        px-5 py-3
        rounded-[12px]
        justify-between
        dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900
   bg-[#184D6C]
                backdrop-blur-lg
                border border-white/30
        text-white">

                    <!-- عنوان -->
                    <h1 class="text-[24px] text-right">
                        <?php echo e($currencyName); ?>

                    </h1>

                    <!-- مقدار وسط کارت -->
                    <div class="flex flex-col flex-1 justify-center items-center">
                        <span class="font-bold text-[18px] vazir" dir="ltr">
                            <?php echo e(number_format($data['total'])); ?>

                        </span>
                    </div>

                    
                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-[#FFFFFF]/10  rounded-[8px] mr-auto  backdrop:blur-2xl text-[12px] p-2 mt-2 text-gray-800 hover:shadow-md transition border border-white flex items-center justify-end gap-2 w-[114px] h-[25px]">
                       <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 12.5L12.5 7.5M12.5 7.5H8.75M12.5 7.5V11.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5.83366 2.78136C7.05938 2.07231 8.48246 1.6665 10.0003 1.6665C14.6027 1.6665 18.3337 5.39746 18.3337 9.99984C18.3337 14.6022 14.6027 18.3332 10.0003 18.3332C5.39795 18.3332 1.66699 14.6022 1.66699 9.99984C1.66699 8.48197 2.0728 7.05889 2.78184 5.83317" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
</svg>

                        <span wire:loading.remove class="text-[#FFFFFF]">نمایش گزارش</span>
                        <span wire:loading class="text-[#FFFFFF]">
                            در حال انتقال...
                        </span>

                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            <!-- کارت خلاصه به دالر -->
            <!--[if BLOCK]><![endif]--><?php if(count($withdrawSummary) > 0): ?>

            <div class="inline-block align-top ml-4 h-auto vazir">
                <div dir="rtl" class="flex flex-col h-[185px] w-[273px] pr-5 pl-5 pt-3 rounded-[12px]
                        dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900
                        bg-[#184D6C]
                        backdrop-blur-lg
                        border border-white/30 text-white">


                    <!-- عنوان -->
                    <h1 class="text-[24px] text-right">
                        خلاصه برداشت‌ها به دالر
                    </h1>

                    <!-- مقدار وسط کارت -->
                    <div class="flex flex-col flex-1 justify-center items-center">
                        <span class="font-bold text-[18px] vazir" dir="ltr">
                            <?php echo e(number_format($totalUsd, 2)); ?>

                        </span>
                    </div>

               
                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-[#FFFFFF]/10  rounded-[8px] mr-auto  backdrop:blur-2xl text-[12px]  text-gray-800 hover:shadow-md transition border border-white flex items-center justify-end gap-2 w-[114px] h-[25px]">
                       <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.5 12.5L12.5 7.5M12.5 7.5H8.75M12.5 7.5V11.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M5.83366 2.78136C7.05938 2.07231 8.48246 1.6665 10.0003 1.6665C14.6027 1.6665 18.3337 5.39746 18.3337 9.99984C18.3337 14.6022 14.6027 18.3332 10.0003 18.3332C5.39795 18.3332 1.66699 14.6022 1.66699 9.99984C1.66699 8.48197 2.0728 7.05889 2.78184 5.83317" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
</svg>

                        <span wire:loading.remove class="text-[#FFFFFF]">نمایش گزارش</span>
                        <span wire:loading class="text-[#FFFFFF]">
                            در حال انتقال...
                        </span>

                    </button>
                </div>
            </div>

            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- فرم و جدول کنار هم -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            <!-- فرم برداشت -->
            <div class="flex flex-col
         dark:bg-black dark:text-white dark:border dark:border-white
      bg-white   border border-[#D7E5EC] shadow-sm backdrop:blur-lg
         mx-auto
         w-full max-w-[420px] lg:max-w-[474px]
         p-[10px]
         h-auto
         rounded-[12px]
         space-y-2" >

                <!-- بالای فرم -->
                <div
                    class="flex dark:border-white space-y-3 flex-row justify-between p-[10px] rounded-[12px] flex-wrap">
                    <p class="flex justify-center items-center text-center inter gap-2 text-xl">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                                stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>                        <?php echo e($transactionId ? 'فورم ویرایش برداشت' : 'فورم ثبت برداشت'); ?>

                    </p>
                </div>

                <!-- فرم -->
                <form wire:submit.prevent="save" class="dark:text-white">
                    <!-- انتخاب کارمند -->
                    <!-- کارت کارمند انتخاب شده -->
                    <div class="mt-2">
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">انتخاب
                                کارمند</label>
                            <div x-data="{
                                searchValue: <?php if ((object) ('searchStaff') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('searchStaff'->value()); ?>')<?php echo e('searchStaff'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('searchStaff'); ?>')<?php endif; ?>,
                                selectedId: <?php if ((object) ('staff_id') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('staff_id'->value()); ?>')<?php echo e('staff_id'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('staff_id'); ?>')<?php endif; ?>,
                                staffs: <?php echo \Illuminate\Support\Js::from($staffs)->toHtml() ?>,
                                handleSelect(event) {
                                    // جدا کردن ID از متن نمایشی
                                    const selectedText = event.target.value;
                                    const selected = this.staffs.find(
                                        staff => `${staff.name} (${staff.job})` === selectedText
                                    );
                            
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.name} (${selected.job})`;
                                        // فراخوانی متد Livewire برای انتخاب کارمند
                                        $wire.call('selectStaff', selected.id);
                                    } else {
                                        // اگر کارمندی انتخاب نشد
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('staff_id', null);
                                    }
                                },
                                updateDisplay() {
                                    const selected = this.staffs.find(staff => staff.id === this.selectedId);
                                    this.searchValue = selected ? `${selected.name} (${selected.job})` : '';
                                }
                            }" x-init="updateDisplay();
                            $watch('selectedId', () => updateDisplay())" class="relative w-full">
                                <input list="staffsList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب کارمند..."
                                    class="w-full h-[60px] dark:bg-black dark:text-white dark:border-white dark:placeholder:text-white p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">

                                <datalist id="staffsList">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($staff['name']); ?> (<?php echo e($staff['job']); ?>)">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </datalist>

                                <!--[if BLOCK]><![endif]--><?php if(empty($staff_id)): ?>
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
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['staff_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <!-- مقدار و نوع هزینه -->
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- مقدار -->
                        <div class="flex-1">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.lazy="formatted_amount" wire:blur="formatAmount"
                                    placeholder="0"
                                    class="w-full dark:border-white dark:bg-black dark:placeholder:text-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:text-white" />
                            </div>
                            <!--[if BLOCK]><![endif]--><?php if($amount_in_words): ?>
                            <p class="text-sm dark:text-white text-blue-600 mt-2 vazir"><?php echo e($amount_in_words); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="lg:w-full">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 dark:text-white appearance-none">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
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
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>


                    </div>

                    <!-- ارز و تاریخ -->
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- ارز -->


                        <!-- نوع هزینه -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نوع
                                هزینه</label>
                            <div class="relative w-full">
                                <select wire:model="expanses_type"
                                    class="w-full dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 dark:text-white appearance-none">
                                    <option value="کرایه">کرایه</option>
                                    <option value="پول برق">پول برق</option>
                                    <option value="پول آب">پول آب</option>
                                    <option value="مالیه">مالیه</option>
                                    <option value="غذا">غذا</option>
                                    <option value="خرید تجهیزات">خرید تجهیزات</option>
                                    <option value="معاملات ملک">معاملات ملک</option>
                                    <option value="متفرقه">متفرقه</option>
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
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['expanses_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- تاریخ -->
                        <div class="lg:w-full relative" x-data="persianDatePicker()" x-init="init()">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>

                            <!-- Input field -->
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
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

                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
                                                    this.selectedDate = {
                                                        year,
                                                        month,
                                                        day
                                                    };
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

                    <!-- شرح برداشت -->
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح برداشت..."
                                class="w-full p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white resize-none"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>


                    <!-- دکمه‌های نهایی -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 py-4 justify-center items-center text-center">
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            <span wire:loading.remove wire:target="save">
                                <?php echo e($transactionId ? 'بروزرسانی' : 'ثبت'); ?>

                            </span>
                            <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
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

                        <button type="button" wire:click="resetInputFields"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-6 py-3 text-white">
                            <?php echo e($transactionId ? 'لغو ویرایش' : 'انصراف'); ?>

                        </button>
                    </div>
                </form>
            </div>

            <!-- جدول برداشت‌ها -->
            <div class="flex-1 flex flex-col
                        dark:border dark:border-white
                        dark:bg-black dark:text-white
                        bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC]
                        p-3 md:p-4 lg:p-6
                        rounded-[12px]
                        w-full max-w-[440px] md:max-w-[410px] lg:max-w-full
                        mb-5 mx-auto
                        overflow-x-auto" >

                <!-- بالای جدول: عنوان و جستجو -->
                <div
                    class="grid grid-cols-1 md:grid-cols-1 dark:border-white xl:grid-cols-2 justify-between items-center  p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">برداشت‌های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <!-- نمایش نام کارمند انتخاب شده -->
                        <!--[if BLOCK]><![endif]--><?php if($selectedStaff): ?>
                        <div class="bg-blue-100 dark:bg-blue-900/30 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 dark:text-blue-300 vazir">فیلتر:
                                <?php echo e($selectedStaff->name); ?></span>
                            <button wire:click="clearStaff" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div class="relative w-[340px] md:w-[500px]">
                                     <input type="text" wire:model.live="search" placeholder="جستجو..." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[12px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">

                            
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                                <path d="M20 20L22 22" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <svg width="24" height="24"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <!--[if BLOCK]><![endif]--><?php if($search): ?>
                            <button wire:click="$set('search', '')"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <!-- جدول -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">
                                <tr>
                                    <th class="px-4 py-4 font-bold w-16">#</th>
                                    <th class="px-4 py-4 font-bold w-48">نام کارمند</th>
                                    <th class="px-4 py-4 font-bold w-48">نوع هزینه</th>
                                    <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                    <th class="px-4 py-4 font-bold w-32">واحد</th>
                                    <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                    <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                    <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
     <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        <?php echo e(($withdraws->currentPage() - 1) * $withdraws->perPage() + $key + 1); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <div class="flex items-center gap-2">
                                            <!--[if BLOCK]><![endif]--><?php if($withdraw->staff->image): ?>
                                            <img src="<?php echo e(Storage::url($withdraw->staff->image)); ?>"
                                                class="w-8 h-8 rounded-full object-cover">
                                            <?php else: ?>
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <div>
                                                <div><?php echo e($withdraw->staff->name ?? '-'); ?></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    <?php echo e($withdraw->staff->job ?? ''); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <span
                                            class="px-3 py-1 rounded-full text-[16px] bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            <?php echo e($expenseTypes[$withdraw->expanses_type] ?? $withdraw->expanses_type); ?>

                                        </span>
                                    </td>
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <?php echo e(number_format($withdraw->amount)); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <?php echo e(collect($currencies)->firstWhere('code', $withdraw->currency)['name_fa'] ??
                                        $withdraw->currency); ?>

                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <p class="text-sm"><?php echo e($withdraw->description); ?></p>
                                    </td>
                                    <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">

                                                <?php echo e(explode(' ', $withdraw->date)[0]); ?>


                                            </div>
                                            <div class="text-gray-500 dark:text-white text-sm mt-1">
                                                <?php echo e(\Carbon\Carbon::parse($withdraw->created_at)->format('h:i A')); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="edit(<?php echo e($withdraw->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors hover:bg-blue-50 dark:hover:bg-gray-700"
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

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete(<?php echo e($withdraw->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors hover:bg-red-50 dark:hover:bg-gray-700"
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

                                            <!-- دکمه پرینت -->
                                            <button wire:click="print(<?php echo e($withdraw->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors hover:bg-green-50 dark:hover:bg-gray-700"
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
                                        <!--[if BLOCK]><![endif]--><?php if($staff_id): ?>
                                        هیچ برداشتی برای این کارمند یافت نشد
                                        <?php else: ?>
                                        هیچ برداشتی یافت نشد
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <?php echo e($withdraws->links()); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <!--[if BLOCK]><![endif]--><?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <button wire:click="$set('confirmDeleteId', null)" class="flex right-0 h-4 w-4"><img
                    src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt=""></button>
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                حذف برداشـــــت</h1>
            <hr class="bg-[#E1DED3] mt-8">
            <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می
                خواهید این
                برداشت را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-20  text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                    <?php echo e(__('messages.no')); ?>

                </button>
                <button wire:click="delete"
                    class="px-20 py-3 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl  transition flex items-center gap-2">
                    <?php echo e(__('messages.yes')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <script>
        // Date Picker JavaScript (same as before)
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

                monthsAfghan: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'],
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
                                this.selectedDate = {
                                    year,
                                    month,
                                    day
                                };
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
                    return this.gregorianToPersian(
                        today.getFullYear(),
                        today.getMonth() + 1,
                        today.getDate()
                    );
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

        // Print listener
        let printListenerRegistered = false;
        document.addEventListener('livewire:init', () => {
            if (printListenerRegistered) return;
            printListenerRegistered = true;

            Livewire.on('print-pdf', (data) => {
                const downloadLink = document.createElement('a');
                downloadLink.href = data.url;
                downloadLink.download = '';
                downloadLink.style.display = 'none';
                document.body.appendChild(downloadLink);
                downloadLink.click();

                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = data.url;
                document.body.appendChild(iframe);

                iframe.onload = () => {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();

                    setTimeout(() => {
                        iframe.remove();
                        downloadLink.remove();
                    }, 5000);
                };
            });
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

        [x-cloak] {
            display: none !important;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/withdraw.blade.php ENDPATH**/ ?>