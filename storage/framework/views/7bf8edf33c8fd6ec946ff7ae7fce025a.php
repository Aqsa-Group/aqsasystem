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
    <h1 class="text-2xl font-bold mb-6">💰 گزارش ترانزکشن‌ها</h1>

    
    <div class="mb-6 flex flex-wrap gap-3 items-center">
    <select wire:model.live="transaction_type" 
            class="border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                   focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">
        <option value="">همه تراکنش‌ها</option>
        <option value="رسید">رسید</option>
        <option value="برداشت">برداشت</option>
    </select>

    <input type="text" wire:model.live="sarafi_name" placeholder="نام صرافی..." 
           class="border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                  focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">

    <input type="text" wire:model.live="user_name" placeholder="نام مشتری / کارمند / متفرقه..." 
           class="border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                  focus:ring-2 focus:ring-cyan-400 focus:outline-none transition">

   
</div>


    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">نوع تراکنش</th>
                    <th class="p-2 border">نام مشتری / کارمند / متفرقه</th>
                    <th class="p-2 border">صرافی</th>
                    <th class="p-2 border">مقدار</th>
                    <th class="p-2 border">ارز</th>
                    <th class="p-2 border">تاریخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border"><?php echo e($index + 1); ?></td>
                        <td class="p-2 border"><?php echo e($transaction['type']); ?></td>
                        <td class="p-2 border">
                            <?php echo e($transaction['customer']['name'] ?? $transaction['staff']['name'] ?? $transaction['person'] ?? '---'); ?>

                        </td>
                        <td class="p-2 border">
                            <?php echo e($transaction['sarafi']['name'] ?? '---'); ?>

                        </td>
                        <td class="p-2 border text-right font-mono <?php echo e($transaction['type'] === 'رسید' ? 'text-green-600 dark:text-green-400' : ($transaction['type'] === 'برداشت' ? 'text-red-600 dark:text-red-400' : '')); ?>">
                            <?php echo e(number_format($transaction['amount'] ?? 0)); ?>

                        </td>
                                <td class="p-2 border">
                                <?php echo e(match($transaction['currency'] ?? '') {
                                        'AFN' => 'افغانی',
                                        'USD' => 'دالر',
                                        'CNY' => 'ین چین',
                                        'EUR' => 'یورو',
                                        'IRR' => 'تومان',
                                        'PRK' => 'کلدار',
                                        default => $transaction['currency'] ?? '---'
                                    }); ?>

                            </td>
                        <td class="p-2 border">
                            <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($transaction['created_at'])->format('Y/m/d H:i')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-400">هیچ تراکنشی ثبت نشده است.</td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/transactions-reports.blade.php ENDPATH**/ ?>