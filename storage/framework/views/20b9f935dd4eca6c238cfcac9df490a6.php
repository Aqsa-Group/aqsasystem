<div>
    <!-- Alert Component -->
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

    <!-- Main Container -->
    <div class="w-full max-w-[1400px] mx-auto p-4">

        <!-- Header Section -->
        <div class="mb-8 text-right space-y-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white vazir mb-1">
                سیستم حضور و غیاب کارمندان
            </h1>
            <p class="text-base text-gray-600 dark:text-gray-300 vazir">
                <!--[if BLOCK]><![endif]--><?php if($isMonthlyView): ?>
                نمایش و گزارش حضور و غیاب ماه <?php echo e($persianMonths[$monthFilter]); ?> سال <?php echo e($yearFilter); ?>

                <?php else: ?>
                ثبت روزانه حضور و غیاب با نعیین معاش دریافتی هر کارمند
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </p>
        </div>

        <!-- Date & Time Section -->
        <!--[if BLOCK]><![endif]--><?php if(!$isMonthlyView): ?>
        <div class="bg-[#F5F5F5] dark:bg-blue-900/20 rounded-xl mt-5 p-5 mb-6"
            style="box-shadow: 0px 4px 4px 0px #00000040;">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Date & Day -->
                <div class="flex items-center gap-4">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 vazir">
                        <?php echo e($persianDate); ?>

                    </div>

                    <div class="h-6 w-px bg-gray-300 dark:bg-gray-600"></div>

                    <div class="text-lg text-gray-700 dark:text-gray-300 vazir">
                        <?php echo e($persianDay); ?>

                    </div>
                </div>

                <!-- Live Time (Kabul) -->
                <div x-data="{ time: '' }" x-init="
                    setInterval(() => {
                        const now = new Date(
                            new Date().toLocaleString('en-US', { timeZone: 'Asia/Kabul' })
                        );

                        const hours = now.getHours();
                        const isAM = hours < 12;

                        let h = hours % 12 || 12;
                        let m = now.getMinutes().toString().padStart(2, '0');
                        let s = now.getSeconds().toString().padStart(2, '0');

                        time =     h + ':' + m + ':' + s + '         ' + (isAM ? 'قبل از ظهر ' : 'بعد از ظهر ');
                    }, 1000);
                " class="text-sm text-gray-600 dark:text-gray-400 vazir">
                    <span x-text="time"></span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-[#F5F5F5] dark:bg-blue-900/20 rounded-xl mt-5 p-5 mb-6"
            style="box-shadow: 0px 4px 4px 0px #00000040;">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 vazir">
                    گزارش ماه <?php echo e($persianMonths[$monthFilter]); ?> سال <?php echo e($yearFilter); ?>

                </div>
                <div class="text-lg text-gray-700 dark:text-gray-300 vazir">
                    <?php echo e($this->getJalaliDaysInMonth($yearFilter, $monthFilter)); ?> روز کاری
                </div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Filters Section -->
        <div class="w-full">
            <div class="bg-[#F5F5F5] dark:bg-black dark:border dark:border-white p-4 mb-3 rounded-[12px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4 items-end">
                    <!-- چاپ -->
                    <div>
                        <button wire:click="printReport" wire:loading.attr="disabled" wire:target="printReport" 
                            class="w-full h-[60px] flex items-center justify-center gap-2
                                   bg-[#2563EB] text-white rounded-xl
                                   hover:bg-blue-700 transition">
                            <svg wire:loading.remove wire:target="printReport" class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span wire:loading.remove wire:target="printReport">
                                <!--[if BLOCK]><![endif]--><?php if($isMonthlyView): ?>
                                چاپ گزارش ماهانه
                                <?php else: ?>
                                چاپ گزارش روزانه
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                            <span wire:loading wire:target="printReport">در حال تولید گزارش...</span>
                        </button>
                    </div>

                    <!-- ریست فیلترها -->
                    <div>
                        <button wire:click="resetFilters" wire:loading.attr="disabled" class="w-full h-[60px] flex items-center justify-center gap-2
                                   bg-gray-500 text-white rounded-xl
                                   hover:bg-gray-600 transition">
                            <span wire:loading.remove>ریست فیلترها</span>
                            <span wire:loading>در حال ریست...</span>
                        </button>
                    </div>

                    <!--  کارمند -->
                    <div>
                        <select wire:model.live="filterEmployee" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                            <option value="">انتخاب کارمند</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($staff['id']); ?>">
                                    <?php echo e($staff['name']); ?> <?php echo e($staff['fathername']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>

                    <!-- وضعیت (فقط در حالت روزانه فعال باشد) -->
                    <!--[if BLOCK]><![endif]--><?php if(!$isMonthlyView): ?>
                    <div>
                        <select wire:model.live="filterStatus" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                            <option value="">وضعیت</option>
                            <option value="حاضر">حاضر</option>
                            <option value="غیرحاضر">غیرحاضر</option>
                        </select>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- فیلتر ماه -->
                    <div>
                        <select wire:model.live="filterMonth" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                            <option value="">فیلتر ماه</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $persianMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($month); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                    
                    <!-- فیلتر سال -->
                    <div>
                        <select wire:model.live="yearFilter" class="w-full h-[60px] appearance-none border dark:bg-black dark:border-white
                                   border-[#8C8C8C] rounded-xl px-4 text-sm">
                            <option value="">فیلتر سال</option>
                            <?php
                                $currentYear = \Morilog\Jalali\Jalalian::now()->getYear();
                                $years = range($currentYear - 2, $currentYear + 2);
                            ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year); ?>" <?php echo e($year == $yearFilter ? 'selected' : ''); ?>>
                                    <?php echo e($year); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Attendance Form (فقط در حالت روزانه نمایش داده شود) -->
        <!--[if BLOCK]><![endif]--><?php if($selectedStaff && !$isMonthlyView): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white vazir">
                        ثبت حضور و غیاب برای <?php echo e($selectedStaff->name); ?>

                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        معاش روزانه: <?php echo e(number_format($selectedStaff->final_salary / 30)); ?> افغانی
                    </p>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    معاش صبح: <?php echo e(number_format($selectedStaff->final_salary / 60)); ?> افغانی
                    <span class="mx-2">|</span>
                    معاش شام: <?php echo e(number_format($selectedStaff->final_salary / 60)); ?> افغانی
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Morning Attendance -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white vazir">
                            قبل از ظهر
                        </h3>
                        <div class="flex items-center gap-2">
                            <button wire:click="recordCurrentTime('morning')" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-600 
                                       dark:text-blue-400 rounded-lg text-sm hover:bg-blue-200 
                                       dark:hover:bg-blue-800 transition">
                                ثبت زمان فعلی
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Attendance Toggle -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-gray-300">وضعیت حضور:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="attendanceData.morning_present"
                                    class="sr-only peer" <?php echo e($currentAttendance['leave_type']==='morning' ||
                                    $currentAttendance['leave_type']==='full_day' ? 'disabled' : ''); ?>>
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                        dark:bg-gray-600 peer-checked:after:translate-x-full 
                                        peer-checked:after:border-white after:content-[''] 
                                        after:absolute after:top-0.5 after:left-[4px] 
                                        after:bg-white after:border-gray-300 after:border 
                                        after:rounded-full after:h-6 after:w-6 after:transition-all 
                                        dark:border-gray-600 peer-checked:bg-green-500">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    <?php echo e($attendanceData['morning_present'] ? 'حاضر' : 'غایب'); ?>

                                </span>
                            </label>
                        </div>

                        <!-- Time Input -->
                        <!--[if BLOCK]><![endif]--><?php if($attendanceData['morning_present']): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                زمان حضور صبح
                            </label>
                            <div class="relative">
                                <input type="text" wire:model="morningTime" placeholder="08:30 AM" class="w-full p-3 rounded-xl border border-gray-300 
                                          dark:border-gray-600 bg-white dark:bg-gray-800 
                                          text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                فرمت: ساعت:دقیقه AM/PM (مثال: 08:30 AM)
                            </p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Evening Attendance -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white vazir">
                            بعد از ظهر
                        </h3>
                        <div class="flex items-center gap-2">
                            <button wire:click="recordCurrentTime('evening')" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-600 
                                       dark:text-blue-400 rounded-lg text-sm hover:bg-blue-200 
                                       dark:hover:bg-blue-800 transition">
                                ثبت زمان فعلی
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Attendance Toggle -->
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 dark:text-gray-300">وضعیت حضور:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="attendanceData.evening_present"
                                    class="sr-only peer" <?php echo e($currentAttendance['leave_type']==='evening' ||
                                    $currentAttendance['leave_type']==='full_day' ? 'disabled' : ''); ?>>
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                        dark:bg-gray-600 peer-checked:after:translate-x-full 
                                        peer-checked:after:border-white after:content-[''] 
                                        after:absolute after:top-0.5 after:left-[4px] 
                                        after:bg-white after:border-gray-300 after:border 
                                        after:rounded-full after:h-6 after:w-6 after:transition-all 
                                        dark:border-gray-600 peer-checked:bg-green-500">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    <?php echo e($attendanceData['evening_present'] ? 'حاضر' : 'غایب'); ?>

                                </span>
                            </label>
                        </div>

                        <!-- Time Input -->
                        <!--[if BLOCK]><![endif]--><?php if($attendanceData['evening_present']): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                زمان حضور شام
                            </label>
                            <div class="relative">
                                <input type="text" wire:model="eveningTime" placeholder="01:00 PM" class="w-full p-3 rounded-xl border border-gray-300 
                                          dark:border-gray-600 bg-white dark:bg-gray-800 
                                          text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                فرمت: ساعت:دقیقه AM/PM (مثال: 01:00 PM)
                            </p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>

            <!-- Leave Type Selection -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white vazir mb-4">
                    ثبت مرخصی
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['none' => 'بدون مرخصی', 'morning' => 'مرخصی صبح', 'evening' => 'مرخصی شام', 'full_day'
                    =>
                    'مرخصی کامل']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label
                        class="flex items-center p-3 border rounded-lg cursor-pointer 
                             <?php echo e($attendanceData['leave_type'] === $value ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600'); ?>">
                        <input type="radio" wire:model="attendanceData.leave_type" value="<?php echo e($value); ?>"
                            class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="mr-3 text-sm text-gray-700 dark:text-gray-300"><?php echo e($label); ?></span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <!--[if BLOCK]><![endif]--><?php if($attendanceData['leave_type'] !== 'none'): ?>
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="attendanceData.is_paid"
                            class="w-4 h-4 text-blue-600 rounded">
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">مرخصی با حقوق</span>
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        در صورت انتخاب مرخصی با حقوق، معاش آن نوبت محاسبه می‌شود
                    </p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    توضیحات
                </label>
                <textarea wire:model="note" rows="2" placeholder="توضیحات اضافی..." class="w-full p-3 rounded-xl border border-gray-300 dark:border-gray-600 
                             bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                             focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" wire:click="$set('selectedStaff', null)"
                    class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition">
                    انصراف
                </button>
                <button type="button" wire:click="saveAttendance"
                    class="px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition">
                    ثبت حضور و غیاب
                </button>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

       <!-- در بخش Quick Attendance Table (خط 391 به بعد) -->

<!-- Quick Attendance Table (حالت روزانه) -->
<!--[if BLOCK]><![endif]--><?php if(!$isMonthlyView): ?>
<div class="bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6"
    style="box-shadow: 0px 4px 4px 0px #00000040;">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white vazir">
            حضور و غیاب روز <?php echo e($persianDate); ?>

        </h2>
        <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
            مجموع معاش امروز: <span class="text-red-500"><?php echo e(number_format(
                collect($staffsArray)->sum(function($staff) use ($selectedDate) {
                    $attendance = \App\Models\Sarafi\StaffAttendance::where('staff_id', $staff['id'])
                        ->whereDate('attendance_date', $selectedDate)
                        ->first();
                    return $attendance ? $attendance->daily_salary : 0;
                })
            )); ?></span> افغانی
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="bg-blue-500 dark:bg-blue-700 text-white text-lg vazir">
                <tr>
                    <th class="px-6 py-4 font-bold">#</th>
                    <th class="px-6 py-4 font-bold">نام کارمند</th>
                    <th class="px-6 py-4 font-bold">شغل</th>
                    <th class="px-6 py-4 font-bold text-center">قبل از ظهر</th>
                    <th class="px-6 py-4 font-bold text-center">بعد از ظهر</th>
                    <th class="px-6 py-4 font-bold text-center">مرخصی</th>
                    <th class="px-6 py-4 font-bold">وضعیت</th>
                    <th class="px-6 py-4 font-bold">معاش روز</th>
                </tr>
            </thead>
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $staffsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $attendance = \App\Models\Sarafi\StaffAttendance::where('staff_id', $staff['id'])
                ->whereDate('attendance_date', $selectedDate)
                ->first();
                ?>
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4"><?php echo e($index + 1); ?></td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <?php echo e($staff['name']); ?> <?php echo e($staff['fathername']); ?>

                    </td>
                    <td class="px-6 py-4"><?php echo e($staff['job']); ?></td>

                    <!-- Morning Attendance -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <button wire:click="quickAttendance(<?php echo e($staff['id']); ?>, 'morning_present')"
                                class="p-2 rounded-lg <?php echo e($attendance && $attendance->morning_present ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'); ?>">
                                <!--[if BLOCK]><![endif]--><?php if($attendance && $attendance->morning_present): ?>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <?php else: ?>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                            <!--[if BLOCK]><![endif]--><?php if($attendance && $attendance->morning_time): ?>
                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                <?php echo e($attendance->morning_time); ?>

                            </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </td>

                    <!-- Evening Attendance -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <button wire:click="quickAttendance(<?php echo e($staff['id']); ?>, 'evening_present')"
                                class="p-2 rounded-lg <?php echo e($attendance && $attendance->evening_present ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'); ?>">
                                <!--[if BLOCK]><![endif]--><?php if($attendance && $attendance->evening_present): ?>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <?php else: ?>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>
                            <!--[if BLOCK]><![endif]--><?php if($attendance && $attendance->evening_time): ?>
                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                <?php echo e($attendance->evening_time); ?>

                            </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </td>

                    <!-- Full Day Leave -->
                    <td class="px-6 py-4 text-center">
                        <button wire:click="quickAttendance(<?php echo e($staff['id']); ?>, 'full_day_leave')"
                            class="p-2 rounded-lg <?php echo e($attendance && $attendance->leave_type === 'full_day' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($attendance && $attendance->leave_type === 'full_day'): ?>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                                    clip-rule="evenodd" />
                            </svg>
                            <?php else: ?>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>
                    </td>

                    <!-- Day Status -->
                    <td class="px-6 py-4">
                        <!--[if BLOCK]><![endif]--><?php if($attendance): ?>
                        <span class="px-3 py-1 rounded-full text-xs 
                        <?php echo e($attendance->morning_present && $attendance->evening_present ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                          ($attendance->leave_type !== 'none' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 
                          'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300')); ?>">
                            <!--[if BLOCK]><![endif]--><?php if($attendance->leave_type === 'full_day'): ?>
                                مرخصی کامل
                            <?php elseif($attendance->leave_type === 'morning'): ?>
                                مرخصی صبح
                            <?php elseif($attendance->leave_type === 'evening'): ?>
                                مرخصی شام
                            <?php elseif($attendance->morning_present && $attendance->evening_present): ?>
                                حاضر کامل
                            <?php elseif($attendance->morning_present): ?>
                                حاضر صبح
                            <?php elseif($attendance->evening_present): ?>
                                حاضر شام
                            <?php else: ?>
                                غایب
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </span>
                        <?php else: ?>
                        <span
                            class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            ثبت نشده
                        </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>

                    <!-- Daily Salary -->
                    <td class="px-6 py-4 font-medium">
                        <!--[if BLOCK]><![endif]--><?php if($attendance): ?>
                        <?php echo e(number_format($attendance->daily_salary)); ?> افغانی
                        <?php else: ?>
                        0 افغانی
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        هیچ کارمندی یافت نشد.
                    </td>
                </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Monthly Report Summary -->
        <div class="bg-[#F5F5F5] dark:bg-gray-800 rounded-2xl shadow-lg p-6"
            style="box-shadow: 0px 4px 4px 0px #00000040;">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white vazir">
                    <!--[if BLOCK]><![endif]--><?php if($isMonthlyView): ?>
                    گزارش تفصیلی ماه <?php echo e($persianMonths[$monthFilter]); ?> سال <?php echo e($yearFilter); ?>

                    <?php else: ?>
                    گزارش ماه <?php echo e($persianMonths[$monthFilter]); ?> سال <?php echo e($yearFilter); ?>

                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </h2>
                <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                    مجموع معاش این ماه کل کارمندان: <span class="text-red-500"><?php echo e(number_format($totalMonthlySalary)); ?></span> افغانی
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="bg-blue-500 dark:bg-blue-700 text-white text-lg vazir">
                        <tr>
                            <th class="px-6 py-4 font-bold">#</th>
                            <th class="px-6 py-4 font-bold">نام کارمند</th>
                            <th class="px-6 py-4 font-bold">شغل</th>
                            <th class="px-6 py-4 font-bold">روزهای کامل</th>
                            <th class="px-6 py-4 font-bold">نیمه روز</th>
                            <th class="px-6 py-4 font-bold">مرخصی</th>
                            <th class="px-6 py-4 font-bold">غیبت</th>
                            <th class="px-6 py-4 font-bold">معاش ماه</th>
                            <th class="px-6 py-4 font-bold">% حضور</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $monthlyReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        $totalDays = $this->getJalaliDaysInMonth($yearFilter, $monthFilter);
                        $presentDays = $report['full_days'] + ($report['half_days'] * 0.5);
                        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
                        ?>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4"><?php echo e($index + 1); ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                <?php echo e($report['staff']->name); ?> <?php echo e($report['staff']->fathername); ?>

                            </td>
                            <td class="px-6 py-4"><?php echo e($report['staff']->job); ?></td>
                            <td class="px-6 py-4"><?php echo e($report['full_days']); ?> روز</td>
                            <td class="px-6 py-4"><?php echo e($report['half_days']); ?> روز</td>
                            <td class="px-6 py-4"><?php echo e($report['leave_days']); ?> روز</td>
                            <td class="px-6 py-4"><?php echo e($report['absent_days']); ?> روز</td>
                            <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">
                                <?php echo e(number_format($report['total_salary'])); ?> افغانی
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: <?php echo e($attendancePercentage); ?>%"></div>
                                    </div>
                                    <span class="mr-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e($attendancePercentage); ?>%
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                هیچ گزارشی برای این ماه موجود نیست.
                            </td>
                        </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // اضافه کردن event listener برای باز کردن PDF
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('print-pdf', (event) => {
                console.log('Received print-pdf event:', event);
                
                if (event && event.url) {
                    console.log('Opening PDF URL:', event.url);
                    // باز کردن PDF در تب جدید
                    window.open(event.url, '_blank');
                } else {
                    console.error('No URL provided in event');
                }
            });
        });
    </script>

    <style>
        /* Custom Styles */
        .vazir {
            font-family: 'Vazir', sans-serif;
        }

        /* Calendar styles */
        [x-cloak] {
            display: none !important;
        }

        /* Time input styling */
        input[type="text"][placeholder*=":"] {
            font-family: monospace;
        }

        /* Attendance status badges */
        .status-badge {
            @apply px-3 py-1 rounded-full text-xs font-medium;
        }

        .status-present {
            @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300;
        }

        .status-absent {
            @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300;
        }

        .status-leave {
            @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300;
        }

        /* Animation for quick attendance buttons */
        button:active {
            transform: scale(0.95);
            transition: transform 0.1s;
        }

        /* Custom scrollbar */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Dark mode adjustments */
        .dark .bg-gray-50 {
            background-color: #1f2937;
        }

        .dark .border-gray-300 {
            border-color: #4b5563;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .flex-col-mobile {
                flex-direction: column;
            }

            .text-center-mobile {
                text-align: center;
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .print-full {
                width: 100% !important;
            }

            table {
                font-size: 12px !important;
            }
        }
    </style>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/staff-attendance-component.blade.php ENDPATH**/ ?>