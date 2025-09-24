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
                    <td class="p-2 border">
                        <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d')); ?>

                    </td>
                    <td class="p-2 border"><?php echo e($loan['type'] ?? '---'); ?></td>
                    <td class="p-2 border"><?php echo e($loan['currency'] ?? '---'); ?></td>
                    <td class="p-2 border"><?php echo e($loan['customer']['name'] ?? '---'); ?></td>
                    <td class="p-2 border text-blue-600 font-bold"><?php echo e(number_format($loan['amount'] ?? 0)); ?></td>
                    <td class="p-2 border text-green-600 font-bold"><?php echo e(number_format($loan['loan_recipt'] ?? 0)); ?></td>
                    <td class="p-2 border text-red-600 font-bold"><?php echo e(number_format($loan['reminded'] ?? 0)); ?></td>
                    <td class="p-2 border">
                        <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($loan['created_at'])->format('Y/m/d H:i')); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">هیچ قرضه‌ای ثبت نشده است.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/partials/loans-table.blade.php ENDPATH**/ ?>