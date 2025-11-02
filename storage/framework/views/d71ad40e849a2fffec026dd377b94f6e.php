<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/vendor/filament-forms/components/grid.blade.php ENDPATH**/ ?>