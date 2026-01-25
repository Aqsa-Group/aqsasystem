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
    <h1 class="text-2xl font-bold mb-6">💱 گزارش تبدیل ارز</h1>

    <!-- فرم فیلتر -->
    <div class="mb-4 flex flex-wrap gap-2">
           <select wire:model.live="type" class="border rounded-lg px-3 py-1 text-sm">
            <option value="">همه نوع تبادل</option>
            <option value="تبدیل ارز در صرافی">تبدیل ارز در صرافی</option>
            <option value="تبدیل ارز دوکان">تبدیل ارز دوکان</option>
            <option value="تبدیل ارز در حساب مشتری">تبدیل ارز در حساب مشتری</option>
            <option value="تبدیل ارز در حساب کارمند">تبدیل ارز در حساب کارمند</option>
            <option value="تبدیل ارز در حساب متفرقه">تبدیل ارز در حساب متفرقه</option>
        </select>
        <input type="text" wire:model.live="user_name" placeholder="نام صرافی / مشتری / کارمند / متفرقه..." class="border rounded-lg px-3 py-1 text-sm">
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
        <table class="w-full text-sm text-right border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">نوع</th>
                    <th class="p-2 border">از ارز</th>
                    <th class="p-2 border">به ارز</th>
                    <th class="p-2 border">مبلغ</th>
                    <th class="p-2 border">قیمت روز</th>
                    <th class="p-2 border">مجموع</th>
                    <th class="p-2 border">صرافی / مشتری / کارمند / متفرقه</th>
                    <th class="p-2 border">تاریخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $exchanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $exchange): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-2 border"><?php echo e($index + 1); ?></td>
                        <td class="p-2 border"><?php echo e($exchange['type']); ?></td>
                          <td class="p-2 border">
                        <?php echo e(match($exchange['from'] ?? '') {
                                'AFN' => 'افغانی',
                                'USD' => 'دالر',
                                'CNY' => 'ین چین',
                                'EUR' => 'یورو',
                                'IRR' => 'تومان',
                                'PRK' => 'کلدار',
                                default => $exchange['from'] ?? '---'
                            }); ?>

                    </td>
                    <td class="p-2 border">
                        <?php echo e(match($exchange['to'] ?? '') {
                                'AFN' => 'افغانی',
                                'USD' => 'دالر',
                                'CNY' => 'ین چین',
                                'EUR' => 'یورو',
                                'IRR' => 'تومان',
                                'PRK' => 'کلدار',
                                default => $exchange['to'] ?? '---'
                            }); ?>

                    </td>

                        <td class="p-2 border"><?php echo e(number_format($exchange['amount'] ?? 0)); ?></td>
                        <td class="p-2 border"><?php echo e(number_format($exchange['today_price'] ?? 0)); ?></td>
                        <td class="p-2 border"><?php echo e(number_format($exchange['total'] ?? 0)); ?></td>
                        <td class="p-2 border"><?php echo e($exchange['sarafi']['name'] ?? $exchange['customer']['name'] ?? $exchange['staff']['name'] ?? $exchange['person'] ?? '---'); ?></td>
                        <td class="p-2 border"><?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($exchange['created_at'])->format('Y/m/d H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-400">هیچ تبادل ارزی ثبت نشده است.</td>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/exchanges-reports.blade.php ENDPATH**/ ?>