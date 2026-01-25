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
    <h1 class="text-2xl font-bold mb-6">🛒 گزارش خریدها</h1>

    
    <div class="mb-6">
       

    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-gray-300">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border px-4 py-3">تاریخ و ساعت</th>
                    <th class="border px-4 py-3">نام شرکت</th>
                    <th class="border px-4 py-3">نام جنس</th>
                    <th class="border px-4 py-3">واحد</th>
                    <th class="border px-4 py-3">قیمت فی دانه</th>
                    <th class="border px-4 py-3">تعداد خریده شده</th>
                    <th class="border px-4 py-3">نوع ارز</th>
                    <th class="border px-4 py-3">مبلغ خرید</th>
                    <th class="border px-4 py-3">مبلغ رسید</th>
                    <th class="border px-4 py-3">باقی‌مانده</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $buys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border px-4 py-2 text-center">
                            <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($buy->created_at)->format('%Y/%m/%d H:i:s')); ?>

                        </td>
<td class="border px-4 py-2"><?php echo e($buy->company?->name ?? '-'); ?></td>
                        <td class="border px-4 py-2"><?php echo e($buy->name); ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo e($buy->unit); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($buy->price, 2)); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($buy->all_exist_number)); ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo e($buy->currency === 'USD' ? 'دالر' : 'افغانی'); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($buy->amount, 2)); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($buy->paid, 2)); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($buy->remaining, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="border px-4 py-3 text-center text-gray-500" colspan="10">
                            هیچ خریدی یافت نشد
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/buy-report.blade.php ENDPATH**/ ?>