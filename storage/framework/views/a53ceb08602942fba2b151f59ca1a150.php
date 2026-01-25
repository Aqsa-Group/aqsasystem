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
    <div class="space-y-8 p-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">📊 گزارشات  فروش، قرضه ، برداشت ها ، صندوق</h1>

        
        <section>
            <h2 class="text-xl font-semibold mb-3">فروش‌ها</h2>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                <table class="w-full text-sm text-right border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">شماره فاکتور</th>
                            <th class="p-2 border">تاریخ</th>
                            <th class="p-2 border">نوع فروش</th>
                            <th class="p-2 border">نام خریدار</th>
                            <th class="p-2 border">مجموع فاکتور (افغانی)</th>
                            <th class="p-2 border">مبلغ رسید</th>
                            <th class="p-2 border">باقیمانده</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="p-2 border"><?php echo e($index + 1); ?></td>
                                <td class="p-2 border"><?php echo e($sale['invoice_number'] ?? '---'); ?></td>
                                <td class="p-2 border">
                                    <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($sale['created_at'])->format('Y/m/d H:i')); ?>

                                </td>
                                <td class="p-2 border"><?php echo e($sale['sale_type'] == 'wholesale' ? 'عمده' : 'پرچون'); ?></td>
                                <td class="p-2 border"><?php echo e($sale['buyer_name'] ?? '---'); ?></td>
                                <td class="p-2 border text-blue-600 font-bold"><?php echo e(number_format($sale['total_price'] ?? 0)); ?></td>
                                <td class="p-2 border text-green-600 font-bold"><?php echo e(number_format($sale['received_amount'] ?? 0)); ?></td>
                                <td class="p-2 border text-red-600 font-bold"><?php echo e(number_format($sale['remaining_amount'] ?? 0)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-400">هیچ فروشی ثبت نشده است.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        
        
        <section>
            <h2 class="text-xl font-semibold mt-10 mb-3">قرضه‌ها (وام‌ها)</h2>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                <table class="w-full text-sm text-right border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">تاریخ</th>
                            <th class="p-2 border">نام مشتری</th>
                            <th class="p-2 border">مبلغ قرضه (افغانی)</th>
                            <th class="p-2 border">باقیمانده</th>
                            <th class="p-2 border">وضعیت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="p-2 border"><?php echo e($index + 1); ?></td>
                                <td class="p-2 border">
                                    <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d')); ?>

                                </td>
                                <td class="p-2 border"><?php echo e($loan['customer']['name'] ?? '---'); ?></td>
                                <td class="p-2 border text-blue-600 font-bold"><?php echo e(number_format($loan['amount'] ?? 0)); ?></td>
                                <td class="p-2 border text-red-600 font-bold"><?php echo e(number_format($loan['reminded'] ?? 0)); ?></td>
                                <td class="p-2 border">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($loan['remained'] ?? 0) > 0): ?>
                                        <span class="text-red-600 font-semibold">باقی‌مانده</span>
                                    <?php else: ?>
                                        <span class="text-green-600 font-semibold">تسویه شده</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400">هیچ قرضه‌ای ثبت نشده است.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        
      
<section>
    <h2 class="text-xl font-semibold mt-10 mb-3">برداشت‌ها</h2>
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">تاریخ</th>
                    <th class="p-2 border">نوع برداشت</th>
                    <th class="p-2 border">نام کارمند</th>
                    <th class="p-2 border">مبلغ برداشت (افغانی)</th>
                    <th class="p-2 border">توضیحات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        
                        <td class="p-2 border"><?php echo e($index + 1); ?></td>

                        
                        <td class="p-2 border">
                            <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($withdrawal['created_at'])->format('Y/m/d H:i')); ?>

                        </td>
                        

                        
                        <td class="p-2 border">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($withdrawal->type):
                                case ('electricity'): ?> برق <?php break; ?>
                                <?php case ('rent'): ?> کرایه <?php break; ?>
                                <?php case ('water'): ?> مالیه آب <?php break; ?>
                                <?php case ('food'): ?> غذا <?php break; ?>
                                <?php case ('salary'): ?> معاش کارمند <?php break; ?>
                                <?php case ('other'): ?> متفرقه <?php break; ?>

                                <?php default: ?> ---
                            <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>

                        
                        <td class="p-2 border">
                            <?php echo e($withdrawal->type === 'salary' && $withdrawal->staff ? $withdrawal->staff->name : '---'); ?>

                        </td>

                        
                        <td class="p-2 border text-red-600 font-bold">
                            <?php echo e(number_format($withdrawal->amount ?? 0)); ?>

                        </td>

                        
                        <td class="p-2 border"><?php echo e($withdrawal->description ?? '---'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-400">
                            هیچ برداشتی ثبت نشده است.
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

        
        <section >
            <h2 class="text-xl w-full font-semibold mt-10 mb-3">خلاصه صندوق</h2>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 max-w-full">
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">مجموع کل صندوق:</span>
                    <span class="font-bold text-blue-600"><?php echo e(number_format($safeSummary['total'] ?? 0)); ?> افغانی</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-semibold">درآمد امروز:</span>
                    <span class="font-bold text-green-600"><?php echo e(number_format($safeSummary['today'] ?? 0)); ?> افغانی</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">آخرین بروزرسانی:</span>
                    <span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($safeSummary['last_update']): ?>
                            <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($safeSummary['last_update'])->format('Y/m/d H:i')); ?>

                        <?php else: ?>
                            نامشخص
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                </div>
            </div>
        </section>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/pages/sales-reports.blade.php ENDPATH**/ ?>