<div class="filament-page vazir text-xl">
    <div class="space-y-3 p-8">
        <h1 class="text-4xl font-medium yekan">
            سیستم گزارش‌گیری جامع
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl vazir">
            مدیریت و تحلیل داده‌های مالی و انبارداری با قابلیت فیلتر پیشرفته
        </p>
    </div>

    <div class="mx-auto max-w-8xl space-y-8 py-8 px-4 sm:px-6 lg:px-8">
        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Report Type Selector -->
                <div
                    class="w-full max-w-full bg-gradient-to-br from-gray-100 to-gray-200 border-l-4 border-blue-500 p-6 rounded-xl shadow-lg transition-all duration-300">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-2 yekan vazir">
                        نوع گزارش
                    </h3>

                    <?php
                    $reportTypes = [

                    'sale' => ['icon' => 'fa-solid fa-tags', 'label' => 'فاکتور فروشات', 'color' => 'teal'],
                    'sale_items' => ['icon' => 'fa-solid fa-list', 'label' => 'اجناس فروخته شده', 'color' => 'indigo'],
                    'inventory' => ['icon' => 'fa-solid fa-warehouse', 'label' => 'موجودی انبار', 'color' => 'blue'],
                    'warehouse' => ['icon' => 'fa-solid fa-shop', 'label' => 'موجودی دوکان', 'color' => 'green'],
                    'withdrawal' => ['icon' => 'fa-solid fa-arrow-up-from-bracket', 'label' => 'برداشت‌ها', 'color' =>
                    'pink'],
                    'loan' => ['icon' => 'fa-solid fa-bank', 'label' => 'قرض‌ها', 'color' => 'red'],
                    'salary' => ['icon' => 'fa-solid fa-money-check-dollar', 'label' => 'معاش کارمندان', 'color' =>
                    'amber'],
                    ];
                    ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = $reportTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button wire:click="$set('reportType', '<?php echo e($type); ?>')" class="flex items-center gap-3 p-4 rounded-2xl border text-sm font-medium transition-all duration-300 shadow-sm hover:scale-105
                            <?php if($reportType === $type): ?>
                                bg-gradient-to-r from-<?php echo e($info['color']); ?>-500 to-<?php echo e($info['color']); ?>-600 text-white border-<?php echo e($info['color']); ?>-500 shadow-md
                            <?php else: ?>
                                bg-white text-gray-700 border-gray-200 hover:bg-<?php echo e($info['color']); ?>-50 hover:border-<?php echo e($info['color']); ?>-300
                            <?php endif; ?>
                        ">
                            <i class="<?php echo e($info['icon']); ?> text-lg"></i>
                            <span class="flex-1 text-xl font-medium"><?php echo e($info['label']); ?></span>
                            <?php if($reportType === $type): ?>
                            <span class="ml-auto text-white font-bold">✓</span>
                            <?php endif; ?>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Filters Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div
                            class="bg-gradient-to-br from-black to-gray-500 border-l-4 border-blue-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <span class="text-2xl"> <i class="fas fa-filters"></i> </span>
                                فیلترهای پیشرفته
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- فیلترهای پایه -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- جستجو -->
                                <div class="space-y-2 mt-7">
                                    <div class="relative flex">
                                        <input type="text" wire:model.live="search" placeholder="جستجو..."
                                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>"
                                                alt="آیکون جستجو">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- فیلترهای پویا بر اساس نوع گزارش -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                <!-- فیلترهای محصول برای موجودی/انبار -->
                                <?php if(in_array($reportType, ['inventory', 'warehouse', 'sale_items'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نام محصول</label>
                                    <input type="text" wire:model.live="productName" placeholder="نام محصول..."
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">دسته‌بندی</label>
                                    <select wire:model.live="category"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه دسته‌ها</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع بسته</label>
                                    <select wire:model.live="packageType"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <?php $__currentLoopData = $packageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type); ?>"><?php echo e($type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <!-- فیلتر وضعیت -->
                                <?php if(in_array($reportType, ['inventory', 'warehouse'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">وضعیت</label>
                                    <select wire:model.live="status"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه وضعیت‌ها</option>
                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>"><?php echo e($status); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <!-- فیلتر نوع فروش -->
                                <?php if($reportType === 'sale'): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع فروش</label>
                                    <select wire:model.live="saleType"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                        <option value="">همه انواع</option>
                                        <option value="retail">خرده</option>
                                        <option value="wholesale">عمده</option>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <!-- فیلتر نوع برای برداشت/وام -->
                                <?php if(in_array($reportType, ['withdrawal', 'loan', 'inventory_history',
                                'warehouse_history'])): ?>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">نوع</label>
                                    <input type="text" wire:model.live="type" placeholder="نوع..."
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all duration-200 bg-white shadow-sm">
                                </div>
                                <?php endif; ?>

                                <!-- تاریخ شروع -->
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
                                    <?php $__errorArgs = ['startDateJalali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- تاریخ پایان -->
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
                                    <?php $__errorArgs = ['endDateJalali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div class="rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Table Header -->
                        <div
                            class="bg-gradient-to-br from-black to-gray-400 border-l-4 border-blue-500 text-white px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">📈</span>
                                    نتایج گزارش - <?php echo e($reportTypes[$reportType]['label'] ?? 'نامشخص'); ?>

                                    <span class="bg-primary-500 text-white text-sm px-3 py-1 rounded-full">
                                        <?php echo e(number_format($reports->total())); ?> مورد
                                    </span>
                                </h3>
                                <div class="flex items-center gap-2 text-sm text-white">
                                    <span>📊</span>
                                    <?php if($reports->total() > 0): ?>
                                    نمایش <?php echo e($reports->firstItem()); ?> - <?php echo e($reports->lastItem()); ?> از <?php echo e($reports->total()); ?>

                                    <?php else: ?>
                                    هیچ داده‌ای یافت نشد
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-primary-50 to-primary-100">
                                    <tr>
                                        <?php switch($reportType):
                                        case ('salary'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            کارمند</th>
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

                                        <?php case ('withdrawal'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
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

                                        <?php case ('inventory'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            بارکد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام محصول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دسته‌بندی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع بسته</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            موجودی کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت خرید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت خرده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت عمده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            وضعیت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            فعال</th>
                                        <?php break; ?>

                                        <?php case ('warehouse'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            بارکد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نام محصول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            دسته‌بندی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            واحد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع بسته</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            موجودی کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت خرید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت خرده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت عمده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            وضعیت</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            فعال</th>
                                        <?php break; ?>

                                        <?php case ('sale'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شماره فاکتور</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع فروش</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            خریدار</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ دریافتی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ باقی‌مانده</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تخفیف</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            سود نهایی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مرجوعی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <?php break; ?>

                                        <?php case ('sale_items'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شماره فاکتور</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            محصول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تعداد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت واحد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            سود</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            ضرر</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <?php break; ?>

                                        <?php case ('loan'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مشتری</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع</th>
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

                                        <?php case ('inventory_history'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            محصول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع تراکنش</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تعداد تغییر</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            موجودی قبلی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            موجودی جدید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت واحد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شماره مرجع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <?php break; ?>

                                        <?php case ('warehouse_history'): ?>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            محصول</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            نوع تراکنش</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تعداد تغییر</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            موجودی قبلی</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            موجودی جدید</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            قیمت واحد</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            مبلغ کل</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            شماره مرجع</th>
                                        <th
                                            class="px-6 py-4 text-right text-sm font-semibold text-primary-700 uppercase tracking-wider">
                                            تاریخ</th>
                                        <?php break; ?>

                                        <?php endswitch; ?>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-primary-50 hover:to-primary-25 transition-all duration-200 group">
                                        <?php switch($reportType):

                                        case ('salary'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->staff->fullname ?? 'بدون نام'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <?php switch($report->currency):
                                                case ('AFN'): ?> افغانی <?php break; ?>
                                                <?php case ('USD'): ?> دالر <?php break; ?>
                                                <?php default: ?> <?php echo e($report->currency); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->date ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') :
                                            'بدون تاریخ'); ?>

                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            <?php echo e($report->description ?? 'بدون توضیح'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('withdrawal'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                <?php echo e($report->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <?php switch($report->currency):
                                                case ('AFN'): ?> افغانی <?php break; ?>
                                                <?php case ('USD'): ?> دالر <?php break; ?>
                                                <?php default: ?> <?php echo e($report->currency); ?>

                                                <?php endswitch; ?>
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

                                        <?php case ('inventory'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900">
                                            <?php echo e($report->barcode); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->product_name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($report->category ?? '-'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo e($report->unit); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <?php echo e($report->package_type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->total_quantity)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-green-600 font-bold"><?php echo e(number_format($report->purchase_price_per_unit)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-red-600 font-bold"><?php echo e(number_format($report->retail_price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-orange-600 font-bold"><?php echo e(number_format($report->wholesale_price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->status === 'موجود' ? 'bg-green-100 text-green-800' : 
                                                   ($report->status === 'ناموجود' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                                <?php echo e($report->status); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo e($report->is_active ? 'فعال' : 'غیرفعال'); ?>

                                            </span>
                                        </td>
                                        <?php break; ?>

                                        <?php case ('warehouse'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900">
                                            <?php echo e($report->barcode); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->product_name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo e($report->category ?? '-'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo e($report->unit); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <?php echo e($report->package_type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->total_quantity)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-green-600 font-bold"><?php echo e(number_format($report->purchase_price_per_unit)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-red-600 font-bold"><?php echo e(number_format($report->retail_price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-orange-600 font-bold"><?php echo e(number_format($report->wholesale_price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->status === 'موجود' ? 'bg-green-100 text-green-800' : 
                                                   ($report->status === 'ناموجود' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                                                <?php echo e($report->status); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo e($report->is_active ? 'فعال' : 'غیرفعال'); ?>

                                            </span>
                                        </td>
                                        <?php break; ?>

                                        <?php case ('sale'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->invoice_number ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->sale_type === 'retail' ? 'bg-teal-100 text-teal-800' : 'bg-indigo-100 text-indigo-800'); ?>">
                                                <?php echo e($report->sale_type === 'retail' ? 'خرده' : 'عمده'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->buyer_name ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->total_price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-green-600 font-bold"><?php echo e(number_format($report->received_amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-red-600 font-bold"><?php echo e(number_format($report->remaining_amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-orange-600 font-bold"><?php echo e(number_format($report->discount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-green-600 font-bold"><?php echo e(number_format($report->final_profit)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->is_return ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'); ?>">
                                                <?php echo e($report->is_return ? 'بله' : 'خیر'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('sale_items'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->sale->invoice_number ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->warehouse->product_name ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->quantity)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-900 font-bold"><?php echo e(number_format($report->price_per_unit)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->total_price)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-green-600 font-bold"><?php echo e(number_format($report->profit)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-red-600 font-bold"><?php echo e(number_format($report->loss)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('loan'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->customer->fullname ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <?php echo e($report->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->amount)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <?php switch($report->currency):
                                                case ('AFN'): ?> افغانی <?php break; ?>
                                                <?php case ('USD'): ?> دالر <?php break; ?>
                                                <?php default: ?> <?php echo e($report->currency); ?>

                                                <?php endswitch; ?>
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

                                        <?php case ('inventory_history'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->inventory->product_name ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->type === 'ورود' ? 'bg-green-100 text-green-800' : 
                                                   ($report->type === 'خروج' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')); ?>">
                                                <?php echo e($report->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-bold <?php echo e($report->quantity_change > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                                <?php echo e(number_format($report->quantity_change)); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e(number_format($report->previous_quantity)); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->new_quantity)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-900"><?php echo e(number_format($report->unit_price ?? 0)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->total_amount
                                                ?? 0)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-600">
                                            <?php echo e($report->reference_number ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php case ('warehouse_history'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            <?php echo e($report->warehouse->product_name ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                <?php echo e($report->type === 'ورود' ? 'bg-green-100 text-green-800' : 
                                                   ($report->type === 'خروج' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')); ?>">
                                                <?php echo e($report->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-bold <?php echo e($report->quantity_change > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                                <?php echo e(number_format($report->quantity_change)); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e(number_format($report->previous_quantity)); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->new_quantity)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-gray-900"><?php echo e(number_format($report->unit_price ?? 0)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900"><?php echo e(number_format($report->total_amount
                                                ?? 0)); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-600">
                                            <?php echo e($report->reference_number ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($report->created_at ?
                                            \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d')
                                            : '-'); ?>

                                        </td>
                                        <?php break; ?>

                                        <?php endswitch; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="20" class="px-6 py-12 text-center">
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
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if($reports->hasPages()): ?>
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
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
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
            function createSimpleDatePicker(inputId) {
                const input = document.getElementById(inputId);
                
                input.addEventListener('click', function() {
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
                    
                    const days = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
                    days.forEach(day => {
                        calendarHTML += `<div style="text-align: center; font-weight: bold;">${day}</div>`;
                    });
                    
                    const daysInMonth = new persianDate([year, month, 1]).daysInMonth();
                    for (let day = 1; day <= daysInMonth; day++) {
                        calendarHTML += `
                            <div style="text-align: center; padding: 5px; cursor: pointer; border-radius: 4px;" 
                                 onclick="selectDate('${inputId}', ${year}, ${month + 1}, ${day})">
                                ${day}
                            </div>`;
                    }
                    
                    calendarHTML += `</div></div>`;
                    
                    const existingCalendar = document.getElementById('simpleCalendar');
                    if (existingCalendar) {
                        existingCalendar.remove();
                    }
                    
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

            window.selectDate = function(inputId, year, month, day) {
                const dateString = `${year}/${month.toString().padStart(2, '0')}/${day.toString().padStart(2, '0')}`;
                document.getElementById(inputId).value = dateString;
                
                if (inputId === 'startDatePicker') {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('startDateJalali', dateString);
                } else if (inputId === 'endDatePicker') {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('endDateJalali', dateString);
                }
                
                const calendar = document.getElementById('simpleCalendar');
                if (calendar) {
                    calendar.remove();
                }
            };

            createSimpleDatePicker('startDatePicker');
            createSimpleDatePicker('endDatePicker');
        });
    </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('styles'); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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
    <?php $__env->stopPush(); ?>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/general-reports.blade.php ENDPATH**/ ?>