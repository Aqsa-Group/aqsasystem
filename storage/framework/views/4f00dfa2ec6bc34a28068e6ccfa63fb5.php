<div>
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
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <!-- کارت‌های آماری -->
    <div class="space-y-6">

        <!-- کارت‌های سالانه -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4 pb-0 pt-1">
            <!-- کارت ۱: کل معاش سالانه -->
            <div
                class="bg-gradient-to-br from-rose-100 to-rose-200 border-l-4 border-rose-500 text-rose-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">کل معاش سالانه</h3>
                    <div class="bg-rose-500 p-2 rounded-full">
                        <i class="fa-solid fa-money-bill-wave text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e(number_format($salaryCards['total_salary'])); ?> افغانی</div>
                    <div class="text-sm mt-2">معاش سالانه کارمند</div>
                </div>
            </div>

            <!-- کارت ۲: کل مبلغ پرداختی -->
            <div
                class="bg-gradient-to-br from-green-100 to-green-200 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">کل مبلغ پرداختی</h3>
                    <div class="bg-green-500 p-2 rounded-full">
                        <i class="fa-solid fa-money-bill-trend-up text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e(number_format($salaryCards['total_paid'])); ?> افغانی</div>
                    <div class="text-sm mt-2">مجموع پرداخت‌های انجام شده</div>
                </div>
            </div>

            <!-- کارت ۳: مانده معاش سالانه -->
            <div
                class="bg-gradient-to-br from-blue-100 to-blue-200 border-l-4 border-blue-500 text-blue-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">مانده معاش سالانه</h3>
                    <div class="bg-blue-500 p-2 rounded-full">
                        <i class="fa-solid fa-scale-balanced text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e(number_format($salaryCards['remaining_salary'])); ?> افغانی</div>
                    <div class="text-sm mt-2">مبلغ باقی‌مانده برای پرداخت</div>
                </div>
            </div>

            <!-- کارت ۴: درصد پرداخت سالانه -->
            <div
                class="bg-gradient-to-br from-purple-100 to-purple-200 border-l-4 border-purple-500 text-purple-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">درصد پرداخت سالانه</h3>
                    <div class="bg-purple-500 p-2 rounded-full">
                        <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e($salaryCards['percentage']); ?>%</div>
                    <div class="text-sm mt-2">درصد پرداخت شده از کل معاش</div>
                </div>
            </div>
        </div>

        <!-- کارت‌های ماهانه -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-4 pb-0 pt-1">
            <!-- کارت ۵: معاش پایه ماهانه -->
            <div
                class="bg-gradient-to-br from-orange-100 to-orange-200 border-l-4 border-orange-500 text-orange-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">معاش پایه ماهانه</h3>
                    <div class="bg-orange-500 p-2 rounded-full">
                        <i class="fa-solid fa-calendar-day text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e(number_format($salaryCards['monthly_salary'])); ?> افغانی</div>
                    <div class="text-sm mt-2">معاش پایه ماهانه کارمند</div>
                </div>
            </div>

            <!-- کارت ۶: پرداختی ماه جاری -->
            <div
                class="bg-gradient-to-br from-teal-100 to-teal-200 border-l-4 border-teal-500 text-teal-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">پرداختی 30 روز گذشته</h3>
                    <div class="bg-teal-500 p-2 rounded-full">
                        <i class="fa-solid fa-money-check text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e(number_format($salaryCards['monthly_paid'])); ?> افغانی</div>
                    <div class="text-sm mt-2">پرداختی در 30 روز گذشته</div>
                </div>
            </div>

            <!-- کارت ۷: مانده معاش ماهانه -->
            <div
                class="bg-gradient-to-br from-indigo-100 to-indigo-200 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">مانده معاش ماهانه</h3>
                    <div class="bg-indigo-500 p-2 rounded-full">
                        <i class="fa-solid fa-clock text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e(number_format($salaryCards['monthly_remaining'])); ?> افغانی</div>
                    <div class="text-sm mt-2">مبلغ باقی‌مانده این ماه</div>
                </div>
            </div>

            <!-- کارت ۸: درصد پرداخت ماهانه -->
            <div
                class="bg-gradient-to-br from-pink-100 to-pink-200 border-l-4 border-pink-500 text-pink-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">درصد پرداخت ماهانه</h3>
                    <div class="bg-pink-500 p-2 rounded-full">
                        <i class="fa-solid fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold"><?php echo e($salaryCards['monthly_percentage']); ?>%</div>
                    <div class="text-sm mt-2">درصد پرداخت شده این ماه</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="flex flex-col lg:flex-row gap-10 mt-4 p-4">

        
        <div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[574px] p-[12px] h-[620px] rounded-[12px] space-y-2"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div class="flex flex-row justify-between p-[16px] border border-[#8C8C8C] rounded-[12px] flex-wrap mb-5">
                <p class="flex justify-center items-center text-center">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" alt="" class="h-6 w-6">
                    <?php echo e($salaryId ? 'فورم ویرایش معاش' : 'فورم ثبت پرداخت معاش'); ?>

                </p>
            </div>

            
            <form wire:submit.prevent="submitSalary" class="space-y-6">

                <!-- انتخاب کارمند -->
                <div>
                    <label class="block text-[16px] font-medium text-black mb-2 vazir">انتخاب کارمند</label>
                    <div x-data="{
                        searchValue: '',
                        selectedId: <?php if ((object) ('selectedStaff') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedStaff'->value()); ?>')<?php echo e('selectedStaff'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedStaff'); ?>')<?php endif; ?>,
                        staffs: <?php echo \Illuminate\Support\Js::from($staffs)->toHtml() ?>,
                        handleSelect(event) {
                            const selected = this.staffs.find(
                                c => event.target.value === `${c.name} ${c.lastname} - ${c.job}`
                            );
                            if (selected) {
                                this.selectedId = selected.id;
                                this.searchValue = `${selected.name} ${selected.lastname} - ${selected.job}`;
                                $wire.selectStaff(selected.id);
                                $wire.set('search', selected.name + ' ' + selected.lastname);
                            } else {
                                this.selectedId = null;
                                this.searchValue = '';
                                $wire.set('selectedStaff', null);
                                $wire.set('search', '');
                            }
                        },
                        updateDisplay() {
                            const selected = this.staffs.find(c => c.id === this.selectedId);
                            this.searchValue = selected ? `${selected.name} ${selected.lastname} - ${selected.job}` : '';
                        }
                    }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())" class="relative w-full">
                        <input list="staffsList" x-model="searchValue" @change="handleSelect" id="select"
                            placeholder="جستجو یا انتخاب کارمند..."
                            class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                            autocomplete="off">
                        <datalist id="staffsList">
                            <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($staff->name); ?> <?php echo e($staff->lastname); ?> - <?php echo e($staff->job); ?>">
                                <?php echo e($staff->name); ?> <?php echo e($staff->lastname); ?> (<?php echo e($staff->job); ?>) - <?php echo e(number_format($staff->salary)); ?> افغانی
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </datalist>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓">
                        </div>
                    </div>
                    <?php $__errorArgs = ['selectedStaff'];
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

                <!-- مقدار و تاریخ -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-2 vazir">مقدار (افغانی)</label>
                        <div class="relative w-full">
                            <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                class="w-full h-[60px] p-3 rounded-[12px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500"
                                oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                افغانی
                            </div>
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
                    <label class="block text-[16px] font-medium text-black mb-2 vazir">شرح پرداخت</label>
                    <textarea wire:model="description" rows="3" placeholder="توضیحات پرداخت معاش..."
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

                <!-- دکمه‌ها -->
                <div class="flex gap-4 p-4 justify-center items-center text-center flex-wrap">
                    <button type="submit"
                        class="bg-[#61B138] text-[18px] vazir font-semibold rounded-[8px] px-20 py-3 text-white">
                        <?php echo e($salaryId ? 'بروزرسانی' : 'ثبت'); ?>

                    </button>

                    <button type="button" wire:click="resetForm"
                        class="bg-[#DD2424] text-[18px] vazir font-semibold rounded-[8px] px-20 py-3 text-white">
                        <?php echo e($salaryId ? 'لغو ویرایش' : 'انصراف'); ?>

                    </button>
                </div>
            </form>
        </div>

        
        <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

            
            <div
                class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                <h1 class="text-lg md:text-xl lg:text-2xl vazir">پرداخت‌های معاش ثبت شده</h1>

                <div class="flex items-center gap-3">
                    
                    <?php if($selectedStaffId): ?>
                    <?php
                    $selectedStaff = \App\Models\Tools\Staffs::find($selectedStaffId);
                    ?>
                    <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                        <span class="text-blue-700 vazir">فیلتر: <?php echo e($selectedStaff->name ?? ''); ?> <?php echo e($selectedStaff->lastname ?? ''); ?></span>
                        <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                            ✕
                        </button>
                    </div>
                    <?php endif; ?>

                    <div class="relative w-full md:w-[302px]">
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] w-full h-12 md:h-[51px] bg-transparent rounded-[12px] p-2 md:p-3 text-sm md:text-base pr-10"
                            placeholder="جستجو بر اساس نام، فامیلی یا شغل...">

                        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">

                        <?php if($search): ?>
                        <button wire:click="clearSearchAndFilter"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                        <?php endif; ?>

                        <?php if($search && count($filteredStaffs) > 0 && !$selectedStaffId): ?>
                        <ul
                            class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                            <?php $__currentLoopData = $filteredStaffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li wire:click="selectStaff(<?php echo e($staff->id); ?>)"
                                class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between items-center">
                                <span><?php echo e($staff->name); ?> <?php echo e($staff->lastname); ?></span>
                                <span class="text-gray-500 text-sm"><?php echo e($staff->job); ?></span>
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
                            class="bg-gradient-to-br from-indigo-400 to-indigo-500 dark:bg-gray-700 text-white text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0">
                            <tr>
                                <th class="px-4 py-4 font-bold w-16">#</th>
                                <th class="px-4 py-4 font-bold w-48">نام کارمند</th>
                                <th class="px-4 py-4 font-bold w-32">شغل</th>
                                <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $salaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $salary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="ttransactionext-black border-b border-[#D9D9D9] bg-transparent">
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                    <?php echo e($key + 1); ?>

                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium w-48">
                                    <?php echo e($salary->staff->name ?? '-'); ?> <?php echo e($salary->staff->lastname ?? ''); ?>

                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-32">
                                    <?php echo e($salary->staff->job ?? '-'); ?>

                                </td>
                                <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium w-40">
                                    <?php echo e(number_format($salary->amount)); ?> <span
                                        class="text-sm text-gray-500">افغانی</span>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                    <div class="space-y-1 text-right">
                                        <p class="text-sm">تفصیلات: <?php echo e($salary->description ?? 'بدون توضیح'); ?></p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 vazir text-[14px] md:text-[16px] text-center w-40">
                                    <div class="whitespace-nowrap">
                                        <div class="font-medium"><?php echo e($salary->date); ?></div>
                                        <div class="text-gray-500 text-sm mt-1">
                                            <?php echo e(\Carbon\Carbon::parse($salary->created_at)->format('h:i A')); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center w-[68]">
                                    <div class="flex justify-center gap-3">
                                        <button wire:click="edit(<?php echo e($salary->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="ویرایش">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                class="w-7 h-7" alt="Edit">
                                        </button>
                                        <button wire:click="confirmDelete(<?php echo e($salary->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="حذف">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                class="w-8 h-8" alt="Delete">
                                        </button>
                                        <button wire:click="print(<?php echo e($salary->id); ?>)"
                                            class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                            title="پرینت">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                class="w-10 h-10" alt="Print">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-8 text-lg">
                                    <?php if($selectedStaffId): ?>
                                    هیچ پرداخت معاش برای این کارمند یافت نشد
                                    <?php else: ?>
                                    هیچ پرداخت معاش یافت نشد
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

    
    <?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن" class="w-4 h-4">
            </button>
            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف پرداخت معاش</h1>
            <hr class="bg-[#E1DED3] mt-4 mx-4">
            <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این پرداخت معاش را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">خیر</button>
                <button wire:click="deleteConfirmed"
                    class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">بلی</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        document.addEventListener('livewire:load', function() {
            if (typeof kamaDatepicker !== 'undefined') {
                kamaDatepicker('datePicker', {
                    buttonsColor: "blue",
                    forceFarsiDigits: true,
                    markToday: true,
                    markHolidays: true,
                    gotoToday: true,
                    highlightSelectedDay: true
                });
            }
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

</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/salary.blade.php ENDPATH**/ ?>