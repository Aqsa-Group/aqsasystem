<div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border overflow-x-auto">
    <table class="w-full text-sm text-right border-collapse">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
            <tr>
                <th class="p-2 border">#</th>
                <th class="p-2 border">شماره فاکتور</th>
                <th class="p-2 border">تاریخ</th>
                <th class="p-2 border">نوع فروش</th>
                <th class="p-2 border">نام خریدار</th>
                <th class="p-2 border">مجموع فاکتور</th>
                <th class="p-2 border">مبلغ رسید</th>
                <th class="p-2 border">باقیمانده</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="p-2 border"><?php echo e($index + 1); ?></td>
                <td class="p-2 border"><?php echo e($sale['invoice_number'] ?? '---'); ?></td>
                <td class="p-2 border">
                    <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($sale['created_at'])->format('Y/m/d H:i')); ?>

                </td>
                <td class="p-2 border"><?php echo e($sale['sale_type'] == 'wholesale' ? 'عمده' : 'پرچون'); ?></td>
                <td class="p-2 border"><?php echo e($sale['buyer_name'] ?? '---'); ?></td>
                <td class="p-2 border text-blue-600 font-bold">
                    <?php echo e(sprintf('%.2f', $sale['total_price'] ?? 0)); ?>

                </td>
                <td class="p-2 border text-green-600 font-bold">
                    <?php echo e(sprintf('%.2f', $sale['received_amount'] ?? 0)); ?>

                </td>
                <td class="p-2 border text-red-600 font-bold">
                    <?php echo e(sprintf('%.2f', $sale['remaining_amount'] ?? 0)); ?>

                </td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center py-6 text-gray-400">هیچ فروشی ثبت نشده است.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/partials/sales-table.blade.php ENDPATH**/ ?>