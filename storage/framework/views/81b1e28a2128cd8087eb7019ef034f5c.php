<div>
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-3">

        <!-- Flash Messages -->
        <?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2563EB] azir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?>

        <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-700 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('error')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?>



        
        <div class="bg-[#2B65E5] rounded-xl text-white vazir p-3 shadow-md space-y-3"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex justify-between items-center border-b border-white/30 pb-1">
                <h1 class="text-base font-semibold">فایده / ضرر</h1>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-md">امروز</span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">فایده</h2>
                <span class="text-base font-bold"><?php echo e($todayprofit); ?></span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">ضرر</h2>
                <span class="text-base font-bold"><?php echo e($todaylost); ?></span>
            </div>

            <hr class="border-white/30">

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">سود خالص</h2>
                <span class="text-base font-bold"><?php echo e($todayplus); ?></span>
            </div>
        </div>


        
        <div class="bg-[#2B65E5] rounded-xl text-white vazir p-3  space-y-3"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex justify-between items-center border-b border-white/30 pb-1">
                <h1 class="text-base font-semibold">فایده / ضرر</h1>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-md">این هفته</span>
            </div>

            <div class="flex justify-between items-center px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">فایده</h2>
                <span class="text-base font-bold"><?php echo e($weekprofit); ?></span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">ضرر</h2>
                <span class="text-base font-bold"><?php echo e($weeklost); ?></span>
            </div>

            <hr class="border-white/30">

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">سود خالص</h2>
                <span class="text-base font-bold"><?php echo e($weekplus); ?></span>
            </div>
        </div>


        
        <div class="bg-[#2B65E5] rounded-xl text-white vazir p-3 shadow-md space-y-3"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex justify-between items-center border-b border-white/30 pb-1">
                <h1 class="text-base font-semibold">فایده / ضرر</h1>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-md">این ماه</span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">فایده</h2>
                <span class="text-base font-bold"><?php echo e($monthprofit); ?></span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">ضرر</h2>
                <span class="text-base font-bold"><?php echo e($monthlost); ?></span>
            </div>

            <hr class="border-white/30">

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">سود خالص</h2>
                <span class="text-base font-bold"><?php echo e($monthplus); ?></span>
            </div>
        </div>


        
        <div class="bg-[#2B65E5] rounded-xl text-white vazir p-3 shadow-md space-y-3"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex justify-between items-center border-b border-white/30 pb-1">
                <h1 class="text-base font-semibold">فایده / ضرر</h1>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-md">کلی</span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">فایده</h2>
                <span class="text-base font-bold"><?php echo e($totalprofit); ?></span>
            </div>

            <div class="flex justify-between items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">ضرر</h2>
                <span class="text-base font-bold"><?php echo e($totallost); ?></span>
            </div>

            <hr class="border-white/30">

            <div class="flex justify-between  items-center  px-3 py-1.5 rounded-lg">
                <h2 class="text-sm">سود خالص</h2>
                <span class="text-base font-bold"><?php echo e($totalplus); ?></span>
            </div>
        </div>

    </div>

    <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">

        <!--  Form -->
        <div class="flex flex-col bg-[#F5F5F5] w-[420px] lg:w-[534px] mx-auto p-[12px] h-fit rounded-[12px] space-y-8"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex flex-row gap-4  p-[10px] border border-[#8C8C8C] rounded-[12px] flex-wrap">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">

                <p class="flex justify-center items-center text-center">
                    فورم برداشت مفاد
                </p>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="submitRemittance">
                <!-- Account Number and Currency -->
                <div class="mt-2 flex flex-col lg:flex-row gap-3">
                    <!-- Source Account Number -->
                    <div class="flex-1">
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">
                                اضافه به حساب</label>
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
                                    class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
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

               <div class="lg:w-[190px]">
    <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
    <input type="text"
        value="دالر"
        readonly
        class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />

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

                <div class="mt-2 flex flex-col lg:flex-row gap-3">
                    <!-- amount  -->
                    <div class="w-full">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ</label>
                        <input type="text" wire:model="amount" value="4000"
                            class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
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

                    <!--  date -->
                    <div class="lg:w-full">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                        <input type="text" wire:model="date"
                            class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
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

                <!-- description -->
                <div class="mt-3 flex gap-3">
                    <div class="w-full">
                        <textarea wire:model="description" rows="3" placeholder="شرح برداشت..."
                            class="w-full p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-none"></textarea>
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 mt-3  gap-3">
                    <button class="bg-[#61B138] py-3 rounded-[8px] text-white ">ثبت</button>
                    <button class="bg-[#DD2424] py-3 rounded-[8px] text-white ">لغو</button>
                </div>

        </div>

        <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-[440px] mb-5 md:w-[410px] lg:w-[150px] mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div
                class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                <h1 class="text-lg md:text-xl lg:text-2xl vazir">ترانزکشن های ثبت شده</h1>

                <div class="flex items-center gap-3">
                    
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

                    <div class="relative w-[340px] md:w-[500px]">
                        <!-- Input جستجوی زنده با wire:model.live -->
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
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
                            class="bg-[#2B65E5] dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                            <tr>
                                <th class="px-4 py-4 font-bold w-16">#</th>
                                <th class="px-4 py-4 font-bold w-48">نام مشتری</th>
                                <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                <th class="px-4 py-4 font-bold w-32">واحد</th>
                                <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                    <?php echo e($index + 1); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                    <?php echo e($withdraw->customer->fullname ?? 'نامشخص'); ?>

                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                    <?php echo e(number_format($withdraw->amount, 2)); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                    <?php echo e($withdraw->currency_fa); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                    <div class="space-y-1 text-right">
                                        <?php echo e($withdraw->description); ?>

                                    </div>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                    <div class="whitespace-nowrap">
                                        <div class="font-medium">
                                            <?php echo e($withdraw->date); ?>

                                        </div>
                                    </div>
                                </td>
                                
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-8 text-lg">
                                    <?php if($selectedCustomerId): ?>
                                    هیچ برداشتی برای این مشتری یافت نشد
                                    <?php else: ?>
                                    هیچ برداشتی ثبت نشده است
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
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/revenues.blade.php ENDPATH**/ ?>