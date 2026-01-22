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
    <h1 class="text-2xl font-bold mb-6">💳 گزارش رسیدهای شرکت‌ها</h1>

    
    <div class="mb-6">
        <select wire:model="selectedCompany" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700">
            <option value="">انتخاب شرکت</option>
            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($company->id); ?>"><?php echo e($company->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-gray-300">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border px-4 py-3">نام شرکت</th>
                    <th class="border px-4 py-3">مبلغ قرضه</th>
                    <th class="border px-4 py-3">مبلغ رسید</th>
                    <th class="border px-4 py-3">باقی‌مانده</th>
                    <th class="border px-4 py-3">ارز</th>
                    <th class="border px-4 py-3">تاریخ و ساعت</th>

                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="bg-white hover:bg-gray-50">
                     
                        <td class="border px-4 py-2"><?php echo e($payment->company->name); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($payment->total_debt, 2)); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($payment->paid_amount, 2)); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo e(number_format($payment->remaining, 2)); ?></td>
                        <td class="border px-4 py-2"><?php echo e($payment->currency); ?></td>
                           <td class="border px-4 py-2">
                            <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($payment->created_at)->format('%Y/%m/%d H:i:s')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="border px-4 py-3 text-center text-gray-500" colspan="6">
                            هیچ رسیدی یافت نشد
                        </td>
                    </tr>
                <?php endif; ?>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/company-payments.blade.php ENDPATH**/ ?>