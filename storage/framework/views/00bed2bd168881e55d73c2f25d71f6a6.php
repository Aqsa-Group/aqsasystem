<?php $__env->startSection('content'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('sarafi.dashboard');

$__html = app('livewire')->mount($__name, $__params, 'lw-201231765-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Sarafi.layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Sarafi/components/dashboard.blade.php ENDPATH**/ ?>