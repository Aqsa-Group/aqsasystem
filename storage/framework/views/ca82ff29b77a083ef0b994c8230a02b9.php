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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
    <div id="customer-form-section" class="mb-8">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('sarafi.customers', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3087475538-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- نمایش جدول -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('sarafi.customers-table', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3087475538-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

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