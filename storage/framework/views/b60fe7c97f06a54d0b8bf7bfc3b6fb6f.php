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
    <div class="flex items-center justify-center min-h-screen p-6 -mt-12">
        <div
            class="flex flex-col md:flex-row bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden max-w-5xl w-full">

            <div class="w-full md:w-1/2 p-6">
                
                <form wire:submit.prevent="withdrawFromSafe">

                    
                    <div class="mb-5 grid">
                        <label for="type" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                            نوع مصرف
                        </label>
                        <select id="type" wire:model.live="withdrawType" 
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   dark:bg-gray-800 dark:text-gray-100">
                            <option value="">انتخاب کنید</option>
                            <option value="electricity">برق</option>
                            <option value="rent">کرایه</option>
                            <option value="water">مالیه </option>
                            <option value="food">غذا</option>
                            <option value="salary">معاش کارمند</option>
                            <option value="other">متفرقه</option>

                        </select>
                    </div>

                    
                    <?php if($withdrawType === 'salary'): ?>
                        <div class="mb-5 grid">
                            <label for="staff_id" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                                انتخاب کارمند
                            </label>
                            <select id="staff_id" wire:model.defer="staffId"
                                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 
                                       dark:bg-gray-800 dark:text-gray-100">
                                <option value="">انتخاب کارمند</option>
                                <?php $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($staff->id); ?>"><?php echo e($staff->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    
                    <div class="mb-5 grid">
                        <label for="amount" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                            مقدار برداشت
                        </label>
                        <input type="number" id="amount" wire:model.defer="withdrawAmount"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    
                    <div class="mb-5 grid">
                        <label for="description" class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                            توضیحات برداشت
                        </label>
                        <textarea id="description" rows="4" wire:model.defer="withdrawDescription"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   dark:bg-gray-800 dark:text-gray-100"></textarea>
                    </div>

                    
                    <div>
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'submit','color' => 'info','class' => 'w-full','wire:loading.attr' => 'disabled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','color' => 'info','class' => 'w-full','wire:loading.attr' => 'disabled']); ?>
                            <span wire:loading.remove>ثبت برداشت</span>
                            <span wire:loading>در حال ثبت...</span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>

                    </div>

                </form>
            </div>

            <div class="w-full md:w-1/2">
                <img src="<?php echo e(asset('assets/safe.jpg')); ?>" alt="تصویر صندوق" class="h-64 md:h-full w-full object-cover">
            </div>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/pages/withdraw.blade.php ENDPATH**/ ?>