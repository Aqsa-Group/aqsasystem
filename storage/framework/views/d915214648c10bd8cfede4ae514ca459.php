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
    <div x-data="{ saleType: <?php if ((object) ('saleType') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('saleType'->value()); ?>')<?php echo e('saleType'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('saleType'); ?>')<?php endif; ?> }" class="space-y-6 p-4">

        
        <div class="flex items-center justify-between mb-3 gap-6">
            <div class="flex gap-3 flex-1 max-w-xs">
                <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['@click' => 'saleType = \'retail\'; $wire.switchToRetail();','color' => 'danger','icon' => 'heroicon-o-shopping-bag','class' => 'flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition','xBind:class' => 'saleType === \'retail\' ? \'ring-2 ring-red-400\' : \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'saleType = \'retail\'; $wire.switchToRetail();','color' => 'danger','icon' => 'heroicon-o-shopping-bag','class' => 'flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition','x-bind:class' => 'saleType === \'retail\' ? \'ring-2 ring-red-400\' : \'\'']); ?>
                    فروش پرچون
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

                <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['@click' => 'saleType = \'wholesale\'; $wire.switchToWholesale();','color' => 'success','icon' => 'heroicon-o-shopping-cart','class' => 'flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition','xBind:class' => 'saleType === \'wholesale\' ? \'ring-2 ring-green-400\' : \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['@click' => 'saleType = \'wholesale\'; $wire.switchToWholesale();','color' => 'success','icon' => 'heroicon-o-shopping-cart','class' => 'flex-1 py-3 text-base font-bold rounded-xl shadow hover:scale-105 transition','x-bind:class' => 'saleType === \'wholesale\' ? \'ring-2 ring-green-400\' : \'\'']); ?>
                    فروش عمده
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

            
            <template x-if="saleType === 'wholesale'">
                <div class="flex-shrink-0 w-48">
                    <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">👤 انتخاب خریدار</label>
                    <select wire:model.defer="customer_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-400">
                        <option value="">انتخاب خریدار...</option>
                        <?php $__currentLoopData = \App\Models\Import\Customer::where('user_id', auth()->id())->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </template>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            
            <div class="col-span-1 space-y-4">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 space-y-3">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-1">🏷️ افزودن محصول</h2>

                    <form wire:submit.prevent="submitForm" class="space-y-3 relative">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">اسکن بارکد</label>
                            <input wire:model="barcode" placeholder="اسکن بارکد..."
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        <div class="relative">
                            <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">جستجو با نام محصول</label>
                            <input wire:model.debounce.200ms="searchName" placeholder="نام محصول..." autocomplete="off"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                            <?php if(!empty($suggestions)): ?>
                                <div class="absolute w-full bg-white dark:bg-gray-800 border rounded-lg shadow mt-1 max-h-40 overflow-y-auto z-50">
                                    <?php $__currentLoopData = $suggestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-gray-800 dark:text-gray-200"
                                             wire:click="selectProduct(<?php echo e($product['id']); ?>)">
                                            <?php echo e($product['name']); ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-gray-200 text-sm mb-1">تعداد</label>
                            <input type="number" wire:model="quantity" min="1"
                                class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'submit','color' => 'success','class' => 'w-full py-2 text-sm rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','color' => 'success','class' => 'w-full py-2 text-sm rounded-lg']); ?>
                            افزودن به فاکتور
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
                    </form>
                </div>
            </div>

            
            <div class="col-span-2 space-y-4">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md p-4 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🛒 لیست کالاهای فاکتور</span>
                        <span class="text-sm text-gray-500">تعداد کالا: <?php echo e(count($items)); ?></span>
                    </div>
                    <table class="w-full text-sm text-right">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="p-2">نام محصول</th>
                                <th class="p-2">تعداد</th>
                                <th class="p-2">قیمت واحد (دالر)</th>
                                <th class="p-2">مجموع (دالر)</th>
                                <th class="p-2">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="p-2 text-gray-800 dark:text-gray-200"><?php echo e($item['name']); ?></td>
                                    <td class="p-2 flex items-center justify-center gap-2">
                                        <button wire:click="decreaseQuantity(<?php echo e($index); ?>)" class="px-2 bg-red-500 text-white rounded">−</button>
                                        <span><?php echo e($item['quantity']); ?></span>
                                        <button wire:click="increaseQuantity(<?php echo e($index); ?>)" class="px-2 bg-green-500 text-white rounded">+</button>
                                    </td>
                                        <td class="p-2 text-gray-600 dark:text-gray-300">
                                        <input 
                                            type="number" 
                                            min="0" 
                                            step="0.001"
                                            wire:model.lazy="items.<?php echo e($index); ?>.price"
                                            wire:change="updateItemPrice(<?php echo e($index); ?>)"
                                            class="w-20 border rounded px-2 py-1 text-sm bg-gray-50 dark:bg-gray-800 dark:text-gray-100" 
                                        />
                                    </td>

                                    <td class="p-2 font-semibold text-blue-600 dark:text-blue-400"><?php echo e(number_format($item['total'],3)); ?></td>
                                    <td class="p-2 text-center">
                                        <button wire:click="removeItem(<?php echo e($index); ?>)" class="px-3 py-1 bg-gray-600 text-white rounded">حذف</button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-400">هیچ کالایی ثبت نشده است.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(count($items) > 0): ?>
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 space-y-4">

                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">💰 مجموع کل فاکتور:</span>
                        <span class="text-xl font-extrabold text-blue-600 dark:text-blue-400"><?php echo e(number_format(collect($items)->sum('total'),3)); ?> دالر</span>
                    </div>

                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🎁 تخفیف:</span>
                        <input wire:model.lazy="discount" type="number" min="0" step="0.001"
                            class="w-40 border rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100"
                            placeholder="0" />
                    </div>

                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">✅ مبلغ نهایی:</span>
                        <span class="text-xl font-extrabold text-green-600 dark:text-green-400"><?php echo e(number_format(max(collect($items)->sum('total') - $discount,0),3)); ?> دالر</span>
                    </div>

                  <div x-data="{ currency: <?php if ((object) ('receivedCurrency') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('receivedCurrency'->value()); ?>')<?php echo e('receivedCurrency'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('receivedCurrency'); ?>')<?php endif; ?> }">

    <div class="flex items-center justify-between">
        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🪙 ارز دریافتی:</span>
        <select x-model="currency" wire:model="receivedCurrency"
            class="w-40 border rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100">
            <option value="USD" selected>دالر</option>
            <option value="AFN">افغانی</option>
        </select>
    </div>

    <template x-if="currency === 'AFN'">
        <div class="space-y-3">

            <div class="flex items-center justify-between mt-2">
                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">💵 نرخ دالر به افغانی:</span>
                <input wire:model.lazy="usdToAfnRate" type="number" min="0" step="0.001"
                    class="w-40 border rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100"
                    placeholder="0" />
            </div>

            <div class="flex items-center justify-between" x-show="usdToAfnRate > 0">
                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">💰 مجموع کل به افغانی:</span>
                <span class="text-xl font-extrabold text-green-600 dark:text-green-400">
                    <?php echo e(number_format(max((collect($items)->sum('total') - $discount) * $usdToAfnRate,0),3)); ?> افغانی
                </span>
            </div>

        </div>
    </template>
</div>



                 

                    
                  <!-- مبلغ رسید و باقی‌مانده فقط برای فروش عمده -->
<template x-if="saleType === 'wholesale'">
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">💵 مبلغ رسید:</span>
            <input wire:model.lazy="receivedAmount" type="number" min="0" step="0.001"
                class="w-40 border rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-50 dark:bg-gray-800 dark:text-gray-100"
                placeholder="0" />
        </div>

        <div class="flex items-center justify-between">
            <span class="text-lg font-bold text-gray-800 dark:text-gray-200">🧾 باقیمانده:</span>
            <span class="text-xl font-extrabold text-red-600 dark:text-red-400">
                <?php echo e(number_format(max((collect($items)->sum('total') - $discount) - $this->convertedReceivedAmount,0),3)); ?> دالر
            </span>
        </div>
    </div>
</template>

                    
                    <div class="flex gap-3">
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['wire:click' => 'finalizeInvoice','color' => 'success','class' => 'px-4 py-2 rounded-lg text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'finalizeInvoice','color' => 'success','class' => 'px-4 py-2 rounded-lg text-sm']); ?>ثبت فاکتور <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['wire:click' => 'printInvoice','color' => 'info','class' => 'px-4 py-2 rounded-lg text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'printInvoice','color' => 'info','class' => 'px-4 py-2 rounded-lg text-sm']); ?>چاپ فاکتور <?php echo $__env->renderComponent(); ?>
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

                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('download-invoice', (data) => {
                    window.open(data.url, '_blank');
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/pages/sales-panel.blade.php ENDPATH**/ ?>