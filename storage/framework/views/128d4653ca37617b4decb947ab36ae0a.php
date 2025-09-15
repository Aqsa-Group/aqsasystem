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
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">📊  گزارشات</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="<?php echo e(route('filament.import.pages.sales-reports')); ?>"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">🛒 گزارش فروش</h3>
                <p class="text-sm text-gray-500 mt-2">مشاهده جزئیات فروش‌ها</p>
            </a>

            <a href="<?php echo e(route('filament.import.pages.loans-reports')); ?>"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">💳 قرضه‌ها</h3>
                <p class="text-sm text-gray-500 mt-2">گزارش وام‌ها</p>
            </a>

            <a href="<?php echo e(route('filament.import.pages.withdrawals-reports')); ?>"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">💸 برداشت‌ها</h3>
                <p class="text-sm text-gray-500 mt-2">گزارش برداشت‌ها</p>
            </a>

            <a href="<?php echo e(route('filament.import.pages.safe-summary')); ?>"
               class="block bg-white dark:bg-gray-900 rounded-2xl shadow p-6 border hover:shadow-lg transition">
                <h3 class="text-lg font-semibold">🏦 صندوق</h3>
                <p class="text-sm text-gray-500 mt-2">خلاصه صندوق</p>
            </a>
        </div>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/import/pages/report-panel.blade.php ENDPATH**/ ?>