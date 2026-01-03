<?php if (isset($component)) { $__componentOriginalbe23554f7bded3778895289146189db7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbe23554f7bded3778895289146189db7 = $attributes; } ?>
<?php $component = Filament\View\LegacyComponents\Page::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Filament\View\LegacyComponents\Page::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="overflow-x-auto rounded-xl shadow-md border border-gray-300 dark:border-gray-600 w-full">
        <table class="w-full min-w-full text-sm text-right text-gray-800 dark:text-gray-100 bg-white dark:bg-[#1e1e2a] border-collapse">
            
            <thead class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white dark:from-[#3b3b4f] dark:to-[#4b4b5e]">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">نوع مصرف</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">افغانی</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">دالر</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">یورو</th>
                    <th class="px-6 py-3 font-semibold uppercase tracking-wide border-b border-indigo-300 dark:border-gray-500">تومان</th>
                </tr>
            </thead>

            
            <tbody>
                <?php
                    $total_af = $total_us = $total_er = $total_ir = 0;
                ?>

                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $total_af += $row['af'];
                        $total_us += $row['us'];
                        $total_er += $row['er'];
                        $total_ir += $row['ir'];
                    ?>
                    <tr class="bg-white dark:bg-[#2a2a3a] hover:bg-gray-100 dark:hover:bg-[#3a3a4a] transition duration-200 border-b border-gray-200 dark:border-gray-600">
                        <td class="px-6 py-3 font-medium text-indigo-800 dark:text-gray-200 whitespace-nowrap"><?php echo e($row['type']); ?></td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right"><?php echo e(number_format($row['af'])); ?></td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right"><?php echo e(number_format($row['us'])); ?></td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right"><?php echo e(number_format($row['er'])); ?></td>
                        <td class="px-6 py-3 text-indigo-800 dark:text-gray-300 text-right"><?php echo e(number_format($row['ir'])); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <tr class="bg-indigo-100 dark:bg-[#444455] font-bold border-t border-indigo-300 dark:border-gray-500 text-indigo-900 dark:text-gray-100">
                    <td class="px-6 py-4 text-center">جمع کل</td>
                    <td class="px-6 py-4 text-right"><?php echo e(number_format($total_af)); ?></td>
                    <td class="px-6 py-4 text-right"><?php echo e(number_format($total_us)); ?></td>
                    <td class="px-6 py-4 text-right"><?php echo e(number_format($total_er)); ?></td>
                    <td class="px-6 py-4 text-right"><?php echo e(number_format($total_ir)); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbe23554f7bded3778895289146189db7)): ?>
<?php $attributes = $__attributesOriginalbe23554f7bded3778895289146189db7; ?>
<?php unset($__attributesOriginalbe23554f7bded3778895289146189db7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbe23554f7bded3778895289146189db7)): ?>
<?php $component = $__componentOriginalbe23554f7bded3778895289146189db7; ?>
<?php unset($__componentOriginalbe23554f7bded3778895289146189db7); ?>
<?php endif; ?>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/resources/safe-resource/pages/list-safes.blade.php ENDPATH**/ ?>