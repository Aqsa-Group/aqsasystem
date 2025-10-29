<div class="filament-page vazir text-xl">

    <div class="space-y-3">
        <h1 class="text-4xl font-medium yekan">
            سیستم گزارش‌گیری جامع
        </h1>


        <p class="text-xl text-gray-600 max-w-2xl  vazir ">
            مدیریت و تحلیل داده‌های مالی با قابلیت فیلتر پیشرفته و خروجی حرفه‌ای
        </p>
    </div>



    <div class="mx-auto max-w-8xl space-y-8 py-8 px-4 sm:px-6 lg:px-8">


        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">

            <!-- Sidebar Filters -->
            <div class="lg:col-span-1 space-y-6">


                <!-- Report Type Selector -->
                <div
                    class="w-[1150px] max-w-[1150px] bg-gradient-to-br from-gray-100 to-gray-200 border-l-4 border-pink-500 p-6 rounded-xl shadow-lg transition-all duration-300">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-2 yekan vazir ">
                        نوع گزارش
                    </h3>

                    <?php
                    $reportTypes = [
                    'withdraw_log' => ['icon' => 'fa-solid fa-arrow-up-from-bracket', 'label' => 'برداشت‌ها', 'color' =>
                    'pink'],
                    'salary' => ['icon' => 'fa-solid fa-money-check-dollar', 'label' => 'معاش کارمندان', 'color' => 'amber'],
                    'outside' => ['icon' => 'fa-solid fa-money-bill', 'label' => 'عواید بیرونی', 'color' => 'green'],
                    'accounting' => ['icon' => 'fa-solid fa-file-invoice', 'label' => 'حسابداری', 'color' => 'blue'],
                    'deposit' => ['icon' => 'fa-solid fa-hourglass-half', 'label' => 'تسویه نشده', 'color' => 'orange'],
                    'loan' => ['icon' => 'fa-solid fa-bank', 'label' => 'بردگی‌ها', 'color' => 'red'],
                    'payment' => ['icon' => 'fa-solid fa-receipt', 'label' => 'رسیدها', 'color' => 'purple'],
                    'buy' => ['icon' => 'fa-solid fa-cart-plus', 'label' => 'خریدها', 'color' => 'indigo'],
                    'sell' => ['icon' => 'fa-solid fa-tags', 'label' => 'فروش‌ها', 'color' => 'teal'],
                    ];

                    $userRole = Auth::guard('market')->user()->role ?? null;
                    ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 ">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reportTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <!--[if BLOCK]><![endif]--><?php if(
                        ($userRole === 'warehouse_manager' && $type === 'accounting') ||
                        ($userRole !== 'warehouse_manager')
                        ): ?>
                        <button wire:click="$set('reportType', '<?php echo e($type); ?>')" class="flex items-center gap-3 p-4 rounded-2xl border text-sm font-medium transition-all duration-300 shadow-sm hover:scale-105
                        <?php if($reportType === $type): ?>
                            bg-gradient-to-r from-<?php echo e($info['color']); ?>-500 to-<?php echo e($info['color']); ?>-600 text-white border-<?php echo e($info['color']); ?>-500 shadow-md
                        <?php else: ?>
                            bg-white text-gray-700 border-gray-200 hover:bg-<?php echo e($info['color']); ?>-50 hover:border-<?php echo e($info['color']); ?>-300
                        <?php endif; ?>
                    ">
                            <i class="<?php echo e($info['icon']); ?> text-lg"></i>
                            <span class="flex-1 text-xl font-medium "><?php echo e($info['label']); ?></span>
                            <!--[if BLOCK]><![endif]--><?php if($reportType === $type): ?>
                            <span class="ml-auto text-white font-bold ">✓</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>


                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">

                    <!-- Filters Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300 
">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <span class="text-2xl">🎛️</span>
                                فیلترهای پیشرفته
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">

                            <!-- Basic Filters -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <!-- Market Select -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="text-primary-600">🏪</span>
                                        مارکت
                                    </label>
                                    <select wire:model.live="marketId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه مارکت‌ها</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $markets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>

                                <!-- Currency Select -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="text-green-600">💵</span>
                                        واحد پول
                                    </label>
                                    <select wire:model.live="currency"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه واحدها</option>
                                        <option value="AFN">🇦🇫 افغانی</option>
                                        <option value="USD">🇺🇸 دالر</option>
                                        <option value="EUR">🇪🇺 یورو</option>
                                        <option value="IRR">🇮🇷 تومان</option>
                                    </select>
                                </div>

                                <!-- Search -->
                                <div class="space-y-2 mt-7">

                                    <div class="relative flex">
                                        <input type="text" wire:model.live="search"
                                            placeholder="  جستجو بر اساس نام شخص"
                                            class="w-full border-2 border-gray-200 rounded-xl  px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt="">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Dynamic Filters Based on Report Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                <!-- Shop Filter (for accounting, deposit) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['accounting', 'deposit'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">شماره دوکان</label>
                                    <select wire:model.live="shopId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه دوکان‌ها</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($number); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['salary'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">کارمند</label>
                                    <select wire:model.live="staffId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه کارمندان</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Booth Filter (for accounting, deposit, sell) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['accounting', 'deposit', 'sell'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">شماره غرفه</label>
                                    <select wire:model.live="boothId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه غرفه‌ها</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $booths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($number); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Shopkeeper Filter (for accounting, deposit, outside, loan, payment) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['accounting', 'deposit', 'outside', 'loan', 'payment'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">دوکاندار</label>
                                    <select wire:model.live="shopkeeperId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه دوکانداران</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $shopkeepers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Customer Filter (for outside, loan, payment, buy, sell) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['outside', 'loan', 'payment', 'buy', 'sell'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">مشتری</label>
                                    <select wire:model.live="customerId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه مشتریان</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Staff Filter (for outside, loan, payment) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['outside', 'loan', 'payment'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">کارمند</label>
                                    <select wire:model.live="staffId"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه کارمندان</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Type Filter (for accounting, deposit, outside, loan) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['accounting', 'deposit', 'outside', 'loan'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع</label>
                                    <select wire:model.live="type"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <option value="دوکان">دوکان</option>
                                        <option value="غرفه">غرفه</option>
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Expanses Type Filter (for accounting, deposit, withdraw_log) -->
                                <!--[if BLOCK]><![endif]--><?php if(in_array($reportType, ['accounting', 'deposit', 'withdraw_log'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع هزینه</label>
                                    <select wire:model.live="expansesType"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <option value="کرایه">کرایه</option>
                                        <option value="تحت الملکی">تحت الملکی</option>
                                        <option value="پول برق">پول برق</option>
                                        <option value="پول آب">پول آب</option>
                                        <option value="صفایی">صفایی</option>
                                    </select>
                                </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!-- Start Date -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">از تاریخ</label>
                                    <div class="relative">
                                        <input type="text" wire:model.live="startDateJalali" placeholder="1403/01/01"
                                            class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm persian-datepicker"
                                            id="startDatePicker">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            📅
                                        </div>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['startDateJalali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>

                                <!-- End Date -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">تا تاریخ</label>
                                    <div class="relative">
                                        <input type="text" wire:model.live="endDateJalali" placeholder="1403/01/31"
                                            class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm persian-datepicker"
                                            id="endDatePicker">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            📅
                                        </div>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endDateJalali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>



                        </div>
                    </div>
                    <!-- Results Section -->
                    <div class=" rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                        <!-- Table Header -->
                        <div
                            class="bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">📈</span>
                                    نتایج گزارش - <?php echo e($reportTypes[$reportType]['label'] ?? 'نامشخص'); ?>

                                    <span class="bg-primary-500 text-white text-sm px-3 py-1 rounded-full">
                                        <?php echo e(number_format($reports->total())); ?> مورد
                                    </span>
                                </h3>
                                <div class="flex items-center gap-2 text-sm text-wite">
                                    <span>📊</span>
                                    <!--[if BLOCK]><![endif]--><?php if($reports->total() > 0): ?>
                                    نمایش <?php echo e($reports->firstItem()); ?> - <?php echo e($reports->lastItem()); ?> از <?php echo e($reports->total()); ?>

                                    <?php else: ?>
                                    هیچ داده‌ای یافت نشد
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-primary-50 to-primary-100">
                                    <tr>
                                        <!--[if BLOCK]><![endif]--><?php switch($reportType):
                                        case ('accounting'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دوکاندار</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع مصرف</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            وضعیت</th>
                                        <?php break; ?>

                                        <?php case ('outside'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        <?php break; ?>

                                        <?php case ('salary'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            کارمند</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            حقوق</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قرضه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ پرداخت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            وضعیت کسر</th>
                                        <?php break; ?>

                                        <?php case ('deposit'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دوکاندار</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع هزینه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ پرداخت</th>
                                        <?php break; ?>

                                        <?php case ('loan'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام شخص</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ اصلی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            پرداخت شده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            باقی مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <?php break; ?>

                                        <?php case ('payment'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            کد قرضه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ پرداخت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ رسید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        <?php break; ?>

                                        <?php case ('buy'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            فروشنده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع خرید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت خرید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ ثبت</th>
                                        <?php break; ?>

                                        <?php case ('sell'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مارکت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مشتری</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع ملک</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت فروش</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            جزئیات</th>
                                        <?php break; ?>

                                        <?php case ('withdraw_log'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع هزینه</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دریافت کننده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد پول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            توضیحات</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ ثبت</th>
                                        <?php break; ?>

                                        <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-25 transition-all duration-200 group">
                                        <!--[if BLOCK]><![endif]--><?php switch($reportType):
                                        case ('accounting'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-blue-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->market->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($report->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->shopkeeper->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <?php echo e($report->expanses_type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <!--[if BLOCK]><![endif]--><?php switch($report->currency):
                                                case ('AFN'): ?>
                                                افغانی
                                                <?php break; ?>
                                                <?php case ('USD'): ?>
                                                دالر
                                                <?php break; ?>
                                                <?php default: ?>
                                                <?php echo e($report->currency); ?>

                                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            :
                                            '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($report->cleared ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                                <?php echo e($report->cleared ? 'تسویه شده' : 'در انتظار'); ?>

                                            </span>
                                        </td>
                                        <?php break; ?>

                                        <?php case ('outside'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-green-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->market->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($report->customer_id ? 'bg-purple-100 text-purple-800' : ($report->staff_id ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')); ?>">
                                                <!--[if BLOCK]><![endif]--><?php if($report->customer_id): ?>
                                                مشتری
                                                <?php elseif($report->staff_id): ?>
                                                کارمند
                                                <?php elseif($report->shopkeeper_id): ?>
                                                دوکاندار
                                                <?php else: ?>
                                                نامشخص
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->customer->fullname ?? $report->staff->fullname ??
                                            $report->shopkeeper->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900"><?php echo e(number_format($report->paid)); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <!--[if BLOCK]><![endif]--><?php switch($report->currency):
                                                case ('AFN'): ?>
                                                افغانی
                                                <?php break; ?>
                                                <?php case ('USD'): ?>
                                                دالر
                                                <?php break; ?>
                                                <?php default: ?>
                                                <?php echo e($report->currency); ?>

                                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            <?php echo e($report->description ?? '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('salary'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-amber-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->market->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->staff->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->salary)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-green-600"><?php echo e(number_format($report->paid)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-red-600"><?php echo e(number_format($report->remained)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-purple-600"><?php echo e(number_format($report->loan)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <!--[if BLOCK]><![endif]--><?php switch($report->currency):
                                                case ('AFN'): ?> افغانی <?php break; ?>
                                                <?php case ('USD'): ?> دالر <?php break; ?>
                                                <?php default: ?> <?php echo e($report->currency); ?>

                                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            : '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($report->is_reduce ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($report->is_reduce ? 'فعال' : 'غیرفعال'); ?>

                                            </span>
                                        </td>
                                        <?php break; ?>

                                        <?php case ('deposit'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-orange-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->accounting->market->name
                                                    ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->accounting->shopkeeper->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <?php echo e($report->expanses_type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-green-600"><?php echo e(number_format($report->paid)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-red-600"><?php echo e(number_format($report->remained)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->paid_date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                                            :
                                            '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('loan'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-red-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->market->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        <?php echo e($report->person === 'مشتری' ? 'bg-purple-100 text-purple-800' : 
                                           ($report->person === 'دوکاندار' ? 'bg-blue-100 text-blue-800' : 
                                           'bg-orange-100 text-orange-800')); ?>">
                                                <?php echo e($report->person); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <!--[if BLOCK]><![endif]--><?php if($report->person === 'مشتری' && $report->customer): ?>
                                            <?php echo e($report->customer->fullname); ?>

                                            <?php elseif($report->person === 'دوکاندار' && $report->shopkeeper): ?>
                                            <?php echo e($report->shopkeeper->fullname); ?>

                                            <?php elseif($report->person === 'کارمند' && $report->staff): ?>
                                            <?php echo e($report->staff->fullname); ?>

                                            <?php else: ?>
                                            -
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-green-600"><?php echo e(number_format($report->totalPaid())); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-bold <?php echo e($report->remainingAmount() > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                                <?php echo e(number_format($report->remainingAmount())); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('payment'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            #<?php echo e($report->loan_id); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <!--[if BLOCK]><![endif]--><?php switch($report->currency):
                                                case ('AFN'): ?>
                                                افغانی
                                                <?php break; ?>
                                                <?php case ('USD'): ?>
                                                دالر
                                                <?php break; ?>
                                                <?php default: ?>
                                                <?php echo e($report->currency); ?>

                                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            <?php echo e($report->description ?? '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('buy'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-indigo-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->market->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->customer->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                <?php echo e($report->property); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900"><?php echo e(number_format($report->price)); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <!--[if BLOCK]><![endif]--><?php switch($report->currency):
                                                case ('AFN'): ?>
                                                افغانی
                                                <?php break; ?>
                                                <?php case ('USD'): ?>
                                                دالر
                                                <?php break; ?>
                                                <?php default: ?>
                                                <?php echo e($report->currency); ?>

                                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            :
                                            '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('sell'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-teal-600 text-sm">🏪</span>
                                                </div>
                                                <span class="font-medium text-gray-900"><?php echo e($report->market->name ?? '-'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->customer->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                <?php echo e($report->property); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900"><?php echo e(number_format($report->price)); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded"><?php echo e($report->currency); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            <?php echo e($report->details ?? '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('withdraw_log'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                <?php echo e($report->expanses_type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->recipient_name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <!--[if BLOCK]><![endif]--><?php switch($report->currency):
                                                case ('AFN'): ?>
                                                افغانی
                                                <?php break; ?>
                                                <?php case ('USD'): ?>
                                                دالر
                                                <?php break; ?>
                                                <?php default: ?>
                                                <?php echo e($report->currency); ?>

                                                <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            <?php echo e($report->description ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            :
                                            '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="10" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-4">
                                                <div
                                                    class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <span class="text-3xl">📭</span>
                                                </div>
                                                <div class="space-y-2">
                                                    <h4 class="text-lg font-semibold text-gray-700">داده‌ای یافت نشد
                                                    </h4>
                                                    <p class="text-gray-500 text-sm">هیچ رکوردی با فیلترهای فعلی مطابقت
                                                        ندارد</p>
                                                    <!--[if BLOCK]><![endif]--><?php if(app()->environment('local')): ?>
                                                    <p class="text-xs text-yellow-600">نوع گزارش: <?php echo e($reportType); ?></p>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <!--[if BLOCK]><![endif]--><?php if($reports->hasPages()): ?>
                        <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    صفحه <?php echo e($reports->currentPage()); ?> از <?php echo e($reports->lastPage()); ?>

                                </div>
                                <div class="flex gap-2">
                                    <?php echo e($reports->links()); ?>

                                </div>
                            </div>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 justify-center pr-7">
                    <button wire:click="exportToExcel"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-green-900 to-green-900 text-white p-3 py-3 rounded-xl font-medium hover:from-green-900 hover:to-green-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/excel.png')); ?>" class="h-10 w-10" alt="">
                        خروجی اکسیل
                    </button>

                    <button wire:click="printReport"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-900 via-red-900 to-red-900 text-white py-3 px-4 rounded-2xl font-semibold shadow-md hover:shadow-lg transition-all duration-300"
                        wire:loading.attr="disabled" wire:target="printReport">
                        <span wire:loading.remove wire:target="printReport">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/pdf.png')); ?>" class="h-10 w-10" alt="">

                        </span>
                        <span wire:loading wire:target="printReport">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828M17.536 7.464l2.828 2.828M4.464 19.536l2.828-2.828" />
                            </svg>
                        </span>
                        خروجی پی دی اف
                    </button>

                    <button wire:click="resetFilters"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-gray-900 to-gray-900 text-white p-3 rounded-xl font-medium hover:from-gray-900 hover:to-gray-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <span>🔄</span>
                        بازنشانی فیلترها
                    </button>

                </div>

            </div>

        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // ساده‌ترین راه‌حل - تقویم ساده
        function createSimpleDatePicker(inputId) {
            const input = document.getElementById(inputId);
            
            input.addEventListener('click', function() {
                // ایجاد یک تقویم ساده
                const today = new persianDate();
                const year = today.year();
                const month = today.month();
                
                let calendarHTML = `
                    <div style="position: absolute; z-index: 1000; background: white; border: 1px solid #ccc; padding: 10px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <button onclick="prevMonth('${inputId}')">‹</button>
                            <span style="margin: 0 10px;">${year}/${month + 1}</span>
                            <button onclick="nextMonth('${inputId}')">›</button>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(7, 30px); gap: 2px;">
                `;
                
                // اضافه کردن روزهای هفته
                const days = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
                days.forEach(day => {
                    calendarHTML += `<div style="text-align: center; font-weight: bold;">${day}</div>`;
                });
                
                // اضافه کردن روزهای ماه
                const daysInMonth = new persianDate([year, month, 1]).daysInMonth();
                for (let day = 1; day <= daysInMonth; day++) {
                    calendarHTML += `
                        <div style="text-align: center; padding: 5px; cursor: pointer; border-radius: 4px;" 
                             onclick="selectDate('${inputId}', ${year}, ${month + 1}, ${day})">
                            ${day}
                        </div>`;
                }
                
                calendarHTML += `</div></div>`;
                
                // حذف تقویم قبلی اگر وجود دارد
                const existingCalendar = document.getElementById('simpleCalendar');
                if (existingCalendar) {
                    existingCalendar.remove();
                }
                
                // اضافه کردن تقویم جدید
                const calendarDiv = document.createElement('div');
                calendarDiv.id = 'simpleCalendar';
                calendarDiv.innerHTML = calendarHTML;
                calendarDiv.style.position = 'absolute';
                calendarDiv.style.zIndex = '1000';
                calendarDiv.style.top = (input.offsetTop + input.offsetHeight) + 'px';
                calendarDiv.style.left = input.offsetLeft + 'px';
                
                document.body.appendChild(calendarDiv);
            });
        }

        // تعریف توابع全局
        window.selectDate = function(inputId, year, month, day) {
            const dateString = `${year}/${month.toString().padStart(2, '0')}/${day.toString().padStart(2, '0')}`;
            document.getElementById(inputId).value = dateString;
            
            if (inputId === 'startDatePicker') {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('startDateJalali', dateString);
            } else if (inputId === 'endDatePicker') {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('endDateJalali', dateString);
            }
            
            // حذف تقویم
            const calendar = document.getElementById('simpleCalendar');
            if (calendar) {
                calendar.remove();
            }
        };

        // مقداردهی اولیه
        createSimpleDatePicker('startDatePicker');
        createSimpleDatePicker('endDatePicker');
    });
    </script>
    <?php $__env->stopPush(); ?>
    <?php $__env->startPush('styles'); ?>
    <!-- ✅ Tailwind از CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


    <!-- ✅ تنظیمات Tailwind -->
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
    <?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/market/general-reports.blade.php ENDPATH**/ ?>