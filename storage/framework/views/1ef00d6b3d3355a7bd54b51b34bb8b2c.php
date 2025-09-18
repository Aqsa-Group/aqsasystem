<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: amiri, Tahoma, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background: #f0f0f0; }
        img { max-width: 200px; max-height: 100px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">لیست اجناس موجود در گدام</h2>
    <table>
        <thead>
            <tr>
                <th>نام جنس</th>
                
                <th>قیمت پرچون</th>
                <th>قیمت عمده</th> 
                <th>ساخت کشور</th>
                
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                   
                    <td><?php echo e($item->name); ?></td>
                    
                    <td><?php echo e($item->retail_price); ?></td> 
                    <td><?php echo e($item->big_whole_price); ?></td>
                    <td><?php echo e($item->brand); ?></td>
                    
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/inventory_print.blade.php ENDPATH**/ ?>