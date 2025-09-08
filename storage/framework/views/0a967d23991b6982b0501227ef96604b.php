<?php $__currentLoopData = $getActions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($action->isVisible()): ?>
        <?php echo e($action); ?>

    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/vendor/filament-forms/components/actions/action-container.blade.php ENDPATH**/ ?>