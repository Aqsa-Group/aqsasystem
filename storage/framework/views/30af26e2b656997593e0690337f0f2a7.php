<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <h1 class="text-2xl font-bold mb-2">💳 گزارش قرضه‌ها</h1>

    

    <!-- فرم فیلتر -->
    <div class="mb-6 dark:bg-gray-800 rounded-lg  p-4">
        <h3 class="text-lg font-medium mb-3 text-gray-700 dark:text-gray-300">فیلتر گزارشات</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-400">نام مشتری</label>
                <input 
                    type="text" 
                    wire:model.live="customer_name"
                    placeholder="جستجو بر اساس نام..." 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-400">نوع تراکنش</label>
                <select wire:model.live="type" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">همه نوع‌ها</option>
                    <option value="بردگی">بردگی</option>
                    <option value="رسید">رسید</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600 dark:text-gray-400">نوع ارز</label>
                <select wire:model.live="currency" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">همه ارزها</option>
                    <option value="دالر">دالر</option>
                    <option value="افغانی">افغانی</option>
                </select>
            </div>

         
        </div>
    </div>

    <!-- جدول قرضه‌ها -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">تاریخ</th>
                    <th class="p-2 border">نوع</th>
                    <th class="p-2 border">ارز</th>
                    <th class="p-2 border">نام مشتری</th>
                    <th class="p-2 border">مبلغ قرضه</th>
                    <th class="p-2 border">رسید</th>
                    <th class="p-2 border">باقی‌مانده</th>
                    <th class="p-2 border">ثبت شده در</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border"><?php echo e($index + 1); ?></td>
                        <td class="p-2 border"><?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d')); ?></td>
                        <td class="p-2 border"><?php echo e($loan['type']); ?></td>
                        <td class="p-2 border"><?php echo e($loan['currency']); ?></td>
                        <td class="p-2 border"><?php echo e($loan['customer']['name']); ?></td>
                        <td class="p-2 border text-blue-600 font-bold"><?php echo e(number_format($loan['amount'] ?? 0)); ?></td>
                        <td class="p-2 border text-green-600 font-bold"><?php echo e(number_format($loan['loan_recipt'] ?? 0)); ?></td>
                        <td class="p-2 border text-red-600 font-bold"><?php echo e(number_format($loan['reminded'] ?? 0)); ?></td>
                        <td class="p-2 border"><?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($loan['created_at'])->format('Y/m/d H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-400">هیچ قرضه‌ای ثبت نشده است.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">

    <script>
        jalaliDatepicker.startWatch({
            time: false,
            format: 'YYYY-MM-DD',
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/loans-reports.blade.php ENDPATH**/ ?>