<div class="p-6 font-vazir">

    <!-- هدر -->
    <div class="flex justify-between items-center mb-8">

        <!-- تب‌ها -->
        <div class="flex gap-3">
            <button wire:click="$set('activeTab','food')"
                class="px-5 py-2 rounded-xl transition
                <?php echo e($activeTab === 'food'
                    ? 'bg-primary text-white'
                    : 'bg-surface-light dark:bg-surface-dark text-gray-700 dark:text-gray-300'); ?>">
                🍽️ منوی غذا
            </button>

            <button wire:click="$set('activeTab','drink')"
                class="px-5 py-2 rounded-xl transition
                <?php echo e($activeTab === 'drink'
                    ? 'bg-primary text-white'
                    : 'bg-surface-light dark:bg-surface-dark text-gray-700 dark:text-gray-300'); ?>">
                🥤 نوشیدنی
            </button>
        </div>

        <!-- افزودن -->
        <button
            class="px-6 py-2 rounded-xl bg-primary text-white hover:bg-primary-dark transition">
            ➕ افزودن آیتم
        </button>
    </div>

    <!-- کارت‌ها -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activeTab === 'food' ? $foods : $drinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div
                class="group overflow-hidden rounded-2xl
                       bg-surface-light dark:bg-surface-dark
                       shadow-md hover:shadow-xl transition
                       border border-gray-200 dark:border-gray-700">

                <!-- تصویر -->
                <div class="relative h-40 overflow-hidden">
                    <img src="<?php echo e($item['image']); ?>"
                         alt="<?php echo e($item['name']); ?>"
                         loading="lazy"
                         class="w-full h-full object-cover
                                group-hover:scale-110 transition duration-500">

                    <span
                        class="absolute top-3 right-3 px-3 py-1 text-xs rounded-full
                               bg-black/60 text-white backdrop-blur">
                        <?php echo e($activeTab === 'food' ? 'غذا' : 'نوشیدنی'); ?>

                    </span>
                </div>

                <!-- محتوا -->
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        <?php echo e($item['name']); ?>

                    </h3>

                    <p class="text-primary font-semibold mb-4">
                        <?php echo e($item['price']); ?> افغانی
                    </p>

                    <button
                        class="w-full py-2 rounded-xl
                               bg-primary text-white font-semibold
                               hover:bg-primary-dark transition
                               flex items-center justify-center gap-2">
                        🛒 سفارش
                    </button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/restaurant/menus.blade.php ENDPATH**/ ?>