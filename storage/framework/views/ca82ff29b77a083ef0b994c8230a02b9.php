<div>
    <!-- دکمه برای نمایش/پنهان کردن فرم -->
    <div class="mb-4">
        <button 
            wire:click="toggleForm" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
            <?php echo e($showForm ? '❌ بستن فرم' : '➕ افزودن مشتری جدید'); ?>

        </button>
    </div>

    <!-- نمایش فرم -->
    <?php if($showForm): ?>
    <div id="customer-form-section" class="mb-8">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('sarafi.customers', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3087475538-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
    <?php endif; ?>

    <!-- نمایش جدول -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('sarafi.customers-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3087475538-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <!-- اسکریپت برای مدیریت اسکرول -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('doScroll', () => {
                setTimeout(() => {
                    const formSection = document.getElementById('customer-form-section');
                    if (formSection) {
                        formSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 100);
            });
        });
    </script>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/customer-management.blade.php ENDPATH**/ ?>