<div>
    <!-- پیام‌های موفقیت و خطا -->
    <?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-[60px] sm:h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[14px] sm:text-[18px]">
                <i class="fa-solid fa-check-circle ml-2"></i>
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-500 vazir">
        <div class="h-[60px] sm:h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[14px] sm:text-[18px]">
                <i class="fa-solid fa-exclamation-triangle ml-2"></i>
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

<!-- کارت‌های آماری -->
<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 p-2 sm:p-4">
    <!-- فروش امروز -->
    <div
        class="flex flex-col justify-between bg-gradient-to-br from-green-100 to-green-200 border-l-4 border-green-500 text-green-800 p-3 sm:p-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold truncate">فروش امروز</h3>
            <div class="bg-green-500 p-1.5 sm:p-2 rounded-full">
                <i class="fa-solid fa-shopping-cart text-white text-xs sm:text-sm"></i>
            </div>
        </div>
        <div class="text-center mt-2">
            <div class="text-sm sm:text-lg font-bold leading-tight"><?php echo e(number_format($todaySales)); ?></div>
            <div class="text-[10px] sm:text-xs mt-1 opacity-80">افغانی</div>
        </div>
    </div>

    <!-- سود امروز -->
    <div
        class="flex flex-col justify-between bg-gradient-to-br from-blue-100 to-blue-200 border-l-4 border-blue-500 text-blue-800 p-3 sm:p-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold truncate">سود امروز</h3>
            <div class="bg-blue-500 p-1.5 sm:p-2 rounded-full">
                <i class="fa-solid fa-chart-line text-white text-xs sm:text-sm"></i>
            </div>
        </div>
        <div class="text-center mt-2">
            <div class="text-sm sm:text-lg font-bold leading-tight"><?php echo e(number_format($todayProfit)); ?></div>
            <div class="text-[10px] sm:text-xs mt-1 opacity-80">افغانی</div>
        </div>
    </div>

    <!-- فروش ماه -->
    <div
        class="flex flex-col justify-between bg-gradient-to-br from-purple-100 to-purple-200 border-l-4 border-purple-500 text-purple-800 p-3 sm:p-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold truncate">فروش ماه</h3>
            <div class="bg-purple-500 p-1.5 sm:p-2 rounded-full">
                <i class="fa-solid fa-calendar text-white text-xs sm:text-sm"></i>
            </div>
        </div>
        <div class="text-center mt-2">
            <div class="text-sm sm:text-lg font-bold leading-tight"><?php echo e(number_format($monthSales)); ?></div>
            <div class="text-[10px] sm:text-xs mt-1 opacity-80">افغانی</div>
        </div>
    </div>

    <!-- سود ماه -->
    <div
        class="flex flex-col justify-between bg-gradient-to-br from-orange-100 to-orange-200 border-l-4 border-orange-500 text-orange-800 p-3 sm:p-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold truncate">سود ماه</h3>
            <div class="bg-orange-500 p-1.5 sm:p-2 rounded-full">
                <i class="fa-solid fa-money-bill-trend-up text-white text-xs sm:text-sm"></i>
            </div>
        </div>
        <div class="text-center mt-2">
            <div class="text-sm sm:text-lg font-bold leading-tight"><?php echo e(number_format($monthProfit)); ?></div>
            <div class="text-[10px] sm:text-xs mt-1 opacity-80">افغانی</div>
        </div>
    </div>

    <!-- کل فروش -->
    <div
        class="flex flex-col justify-between bg-gradient-to-br from-red-100 to-red-200 border-l-4 border-red-500 text-red-800 p-3 sm:p-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold truncate">کل فروش</h3>
            <div class="bg-red-500 p-1.5 sm:p-2 rounded-full">
                <i class="fa-solid fa-chart-bar text-white text-xs sm:text-sm"></i>
            </div>
        </div>
        <div class="text-center mt-2">
            <div class="text-sm sm:text-lg font-bold leading-tight"><?php echo e(number_format($totalSales)); ?></div>
            <div class="text-[10px] sm:text-xs mt-1 opacity-80">افغانی</div>
        </div>
    </div>

    <!-- کل سود -->
    <div
        class="flex flex-col justify-between bg-gradient-to-br from-teal-100 to-teal-200 border-l-4 border-teal-500 text-teal-800 p-3 sm:p-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold truncate">کل سود</h3>
            <div class="bg-teal-500 p-1.5 sm:p-2 rounded-full">
                <i class="fa-solid fa-coins text-white text-xs sm:text-sm"></i>
            </div>
        </div>
        <div class="text-center mt-2">
            <div class="text-sm sm:text-lg font-bold leading-tight"><?php echo e(number_format($totalProfit)); ?></div>
            <div class="text-[10px] sm:text-xs mt-1 opacity-80">افغانی</div>
        </div>
    </div>
</div>


    
    <div class="flex flex-col xl:flex-row gap-4 sm:gap-6 p-2 sm:p-4">

        
<div class="flex flex-col bg-[#F5F5F5] w-full lg:w-[474px] p-[10px] h-auto  rounded-[12px] space-y-2"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">            <div class="flex justify-between items-center p-3 sm:p-4 border-b border-gray-300 mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold vazir text-gray-800">
                    <i class="fa-solid fa-cart-plus ml-2 text-green-600"></i>
                    فورم ثبت فروش
                </h2>
            </div>

            <!-- نوع فروش و تاریخ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2 vazir">
                        <i class="fa-solid fa-tag ml-1 text-blue-600"></i>
                        نوع فروش
                    </label>
                    <div class="flex gap-1 sm:gap-2">
                        <button wire:click="switchToRetail"
                            class="flex-1 px-2 sm:px-4 py-2 sm:py-3 rounded-lg font-medium transition-all duration-200 text-xs sm:text-sm <?php echo e($saleType === 'retail' ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 border border-gray-300'); ?>">
                            <i class="fa-solid fa-user ml-1"></i>
                            پرچون
                        </button>
                        <button wire:click="switchToWholesale"
                            class="flex-1 px-2 sm:px-4 py-2 sm:py-3 rounded-lg font-medium transition-all duration-200 text-xs sm:text-sm <?php echo e($saleType === 'wholesale' ? 'bg-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 border border-gray-300'); ?>">
                            <i class="fa-solid fa-users ml-1"></i>
                            عمده
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2 vazir">
                        <i class="fa-solid fa-calendar ml-1 text-green-600"></i>
                        تاریخ فروش
                    </label>
                    <input type="text" id="datePicker" wire:model="date" placeholder="YYYY/MM/DD"
                        class="w-full h-10 sm:h-12 border border-gray-300 rounded-lg p-2 sm:p-3 vazir focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 cursor-pointer text-sm sm:text-base" />
                </div>
            </div>

            <!-- مشتری (فقط برای فروش عمده) -->
            <?php if($saleType === 'wholesale'): ?>
            <div class="mb-4 sm:mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2 vazir">
                    <i class="fa-solid fa-user-tie ml-1 text-purple-600"></i>
                    مشتری عمده
                </label>
                <div class="relative">
                    <input type="text" wire:model.live="searchCustomer" placeholder="جستجوی مشتری..."
                        class="w-full h-10 sm:h-12 border border-gray-300 rounded-lg p-2 sm:p-3 vazir focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 pr-8 sm:pr-10 text-sm sm:text-base" />
                    <i class="fa-solid fa-search absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>

                    <?php if($searchCustomer && count($filteredCustomers) > 0): ?>
                    <div
                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-y-auto shadow-lg">
                        <?php $__currentLoopData = $filteredCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div wire:click="selectCustomer(<?php echo e($customer->id); ?>)"
                            class="p-2 sm:p-3 hover:bg-purple-50 cursor-pointer border-b border-gray-100 transition-colors duration-200">
                            <div class="font-medium text-gray-900 text-sm"><?php echo e($customer->fullname); ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fa-solid fa-phone ml-1"></i>
                                <?php echo e($customer->phone); ?>

                                <?php if($customer->idcard_number): ?>
                                <span class="mr-2 sm:mr-4">
                                    <i class="fa-solid fa-id-card ml-1"></i>
                                    <?php echo e($customer->idcard_number); ?>

                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- جستجوی محصول -->
            <div class="mb-4 sm:mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2 vazir">
                    <i class="fa-solid fa-box ml-1 text-indigo-600"></i>
                    جستجوی محصول
                </label>
                <div class="relative">
                    <input type="text" wire:model.live="searchProduct"
                        placeholder="نام محصول یا بارکد را تایپ یا اسکن کنید..."
                        class="w-full h-10 sm:h-12 border border-gray-300 rounded-lg p-2 sm:p-3 vazir focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 pr-8 sm:pr-10 text-sm sm:text-base"
                        id="productSearch" x-data @keydown.enter.prevent="" />
                    <i class="fa-solid fa-search absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>

                    <!-- دکمه پاک کردن -->
                    <?php if($searchProduct): ?>
                    <button type="button" wire:click="clearCurrentProduct"
                        class="absolute left-8 sm:left-10 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fa-solid fa-times text-sm"></i>
                    </button>
                    <?php endif; ?>
                </div>

                <!-- نمایش لیست محصولات -->
                <?php if($searchProduct && count($filteredProducts) > 0 && !$selectedProduct): ?>
                <div
                    class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                    <!-- دکمه غیرفعال کردن انتخاب خودکار -->
                    <div class="p-2 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <span class="text-xs text-gray-600">لیست محصولات پیدا شده</span>
                        <button type="button" wire:click="disableAutoSelect"
                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 transition-colors">
                            <i class="fa-solid fa-pause ml-1"></i>
                            توقف انتخاب خودکار
                        </button>
                    </div>
                    <?php $__currentLoopData = $filteredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div wire:click="selectProduct(<?php echo e($product->id); ?>)"
                        class="p-2 sm:p-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-100 transition-colors duration-200">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 text-sm"><?php echo e($product->product_name); ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="ml-2 sm:ml-3">
                                        <i class="fa-solid fa-barcode ml-1"></i>
                                        <?php echo e($product->barcode); ?>

                                    </span>
                                    <?php if($product->category): ?>
                                    <span class="ml-2 sm:ml-3">
                                        <i class="fa-solid fa-folder ml-1"></i>
                                        <?php echo e($product->category); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-right text-xs sm:text-sm">
                                <div class="font-medium text-green-600">
                                    <i class="fa-solid fa-cubes ml-1"></i>
                                    <?php echo e(number_format($product->total_quantity)); ?> <?php echo e($product->unit); ?>

                                </div>
                                <div class="text-gray-500 mt-1">
                                    <i class="fa-solid fa-user ml-1"></i>
                                    <?php echo e(number_format($product->retail_price)); ?>

                                </div>
                                <div class="text-gray-500">
                                    <i class="fa-solid fa-users ml-1"></i>
                                    <?php echo e(number_format($product->wholesale_price)); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <!-- نمایش اطلاعات محصول انتخاب شده -->
                <?php if($selectedProduct): ?>
                <div
                    class="mt-3 p-3 sm:p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg shadow-sm">
                    <div class="flex justify-between items-start mb-3 sm:mb-4">
                        <div>
                            <h4 class="font-bold text-green-800 text-sm sm:text-lg">
                                <i class="fa-solid fa-check-circle ml-1"></i>
                                <?php echo e($selectedProduct->product_name); ?>

                            </h4>
                            <p class="text-xs sm:text-sm text-green-600 mt-1">
                                <i class="fa-solid fa-barcode ml-1"></i>
                                بارکد: <?php echo e($selectedProduct->barcode); ?>

                                <?php if($selectedProduct->category): ?>
                                <span class="mr-2 sm:mr-4">
                                    <i class="fa-solid fa-tag ml-1"></i>
                                    <?php echo e($selectedProduct->category); ?>

                                </span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <button type="button" wire:click="clearCurrentProduct"
                            class="text-red-500 hover:text-red-700 p-1 transition-colors duration-200">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 text-xs sm:text-sm">
                        <div class="text-center bg-white p-2 sm:p-3 rounded-lg border border-gray-200">
                            <div class="font-medium text-gray-700">
                                <i class="fa-solid fa-cubes ml-1"></i>
                                موجودی
                            </div>
                            <div class="text-sm sm:text-lg font-bold text-blue-600 mt-1">
                                <?php echo e(number_format($selectedProduct->total_quantity)); ?> <?php echo e($selectedProduct->unit); ?>

                            </div>
                        </div>
                        <div class="text-center bg-white p-2 sm:p-3 rounded-lg border border-gray-200">
                            <div class="font-medium text-gray-700">
                                <i class="fa-solid fa-shopping-cart ml-1"></i>
                                قیمت خرید
                            </div>
                            <div class="text-sm sm:text-lg font-bold text-gray-600 mt-1">
                                <?php echo e(number_format($selectedProduct->purchase_price_per_unit)); ?>

                            </div>
                        </div>
                        <div class="text-center bg-white p-2 sm:p-3 rounded-lg border border-gray-200">
                            <div class="font-medium text-gray-700">
                                <i class="fa-solid fa-user ml-1"></i>
                                قیمت پرچون
                            </div>
                            <div class="text-sm sm:text-lg font-bold text-purple-600 mt-1">
                                <?php echo e(number_format($selectedProduct->retail_price)); ?>

                            </div>
                        </div>
                        <div class="text-center bg-white p-2 sm:p-3 rounded-lg border border-gray-200">
                            <div class="font-medium text-gray-700">
                                <i class="fa-solid fa-users ml-1"></i>
                                قیمت عمده
                            </div>
                            <div class="text-sm sm:text-lg font-bold text-orange-600 mt-1">
                                <?php echo e(number_format($selectedProduct->wholesale_price)); ?>

                            </div>
                        </div>
                    </div>

                    <?php if($selectedProduct->is_low_stock): ?>
                    <div class="mt-3 p-2 sm:p-3 bg-yellow-100 border border-yellow-300 rounded-lg flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-yellow-600 ml-2"></i>
                        <span class="text-yellow-800 text-xs sm:text-sm font-medium">
                            موجودی این محصول کم است! (حداقل موجودی: <?php echo e($selectedProduct->min_stock_level); ?>)
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>


            <!-- در بخش مقدار و قیمت -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2 vazir">
                        <i class="fa-solid fa-hashtag ml-1 text-blue-600"></i>
                        تعداد/مقدار
                    </label>
                    <input type="number" wire:model="quantity" step="0.01" min="0.01"
                        class="w-full h-10 sm:h-12 border border-gray-300 rounded-lg p-2 sm:p-3 vazir focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm sm:text-base"
                        id="quantityInput" wire:loading.attr="disabled" />
                    <?php if($selectedProduct): ?>
                    <div class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fa-solid fa-info-circle ml-1"></i>
                        حداکثر: <?php echo e(number_format($selectedProduct->total_quantity)); ?> <?php echo e($selectedProduct->unit); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2 vazir">
                        <i class="fa-solid fa-tag ml-1 text-green-600"></i>
                        قیمت واحد
                    </label>
                    <input type="number" wire:model="unitPrice"
                        class="w-full h-10 sm:h-12 border border-gray-300 rounded-lg p-2 sm:p-3 vazir focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm sm:text-base"
                        id="unitPriceInput" wire:loading.attr="disabled" />
                </div>
            </div>

            <!-- دکمه اضافه به سبد -->
            <button wire:click="addToCart"
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 sm:py-4 rounded-lg font-bold hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl mb-4 sm:mb-6 flex items-center justify-center text-sm sm:text-base">
                <i class="fa-solid fa-cart-plus ml-2 text-lg"></i>
                اضافه به سبد خرید
            </button>

            <!-- سبد خرید -->
            <?php if(count($cartItems) > 0): ?>
            <div class="border-t pt-4 sm:pt-6">
                <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center">
                    <i class="fa-solid fa-shopping-basket ml-2 text-indigo-600"></i>
                    سبد خرید (<?php echo e(count($cartItems)); ?> محصول)
                </h3>

                <div class="space-y-2 sm:space-y-3 max-h-60 overflow-y-auto mb-4 sm:mb-6">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 sm:p-4 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex-1 mb-2 sm:mb-0">
                            <div class="font-medium text-gray-900 text-sm"><?php echo e($item['product_name']); ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fa-solid fa-barcode ml-1"></i>
                                <?php echo e($item['barcode']); ?>

                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fa-solid fa-tag ml-1"></i>
                                <?php echo e(number_format($item['quantity'])); ?> × <?php echo e(number_format($item['unit_price'])); ?> افغانی
                            </div>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto justify-between sm:justify-start">
                            <span class="font-bold text-green-600 text-base sm:text-lg"><?php echo e(number_format($item['total'])); ?></span>

                            <div class="flex items-center gap-1">
                                <button wire:click="decreaseCartQuantity(<?php echo e($index); ?>)"
                                    class="w-6 h-6 sm:w-8 sm:h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors duration-200">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>

                                <span
                                    class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                                    <?php echo e($item['quantity']); ?>

                                </span>

                                <button wire:click="increaseCartQuantity(<?php echo e($index); ?>)"
                                    class="w-6 h-6 sm:w-8 sm:h-8 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors duration-200">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>

                                <button wire:click="removeFromCart(<?php echo e($index); ?>)"
                                    class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-500 text-white rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors duration-200">
                                    <i class="fa-solid fa-times text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- در بخش خلاصه فاکتور -->
                <div class="bg-gradient-to-r from-gray-50 to-slate-100 p-4 sm:p-6 rounded-lg border border-gray-200 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-3 sm:mb-4 text-base sm:text-lg flex items-center">
                        <i class="fa-solid fa-receipt ml-2 text-purple-600"></i>
                        خلاصه فاکتور
                    </h4>

                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-sm sm:text-base">مجموع سبد:</span>
                            <span class="font-bold text-base sm:text-lg text-gray-900"><?php echo e(number_format($cartTotal)); ?> افغانی</span>
                        </div>

                        <!-- نمایش سود قبل از تخفیف -->
                        <div class="flex justify-between items-center bg-blue-50 p-2 sm:p-3 rounded-lg">
                            <span class="text-gray-700 font-medium text-sm sm:text-base">
                                <i class="fa-solid fa-chart-line ml-1 text-blue-600"></i>
                                سود قبل از تخفیف:
                            </span>
                            <span class="font-bold text-base sm:text-lg text-blue-600"><?php echo e(number_format($cartProfitBeforeDiscount)); ?>

                                افغانی</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-sm sm:text-base">تخفیف:</span>
                            <div class="flex items-center gap-1 sm:gap-2">
                                <input type="number" wire:model="discount" wire:change="calculateCartTotals"
                                    class="w-24 sm:w-32 text-right border border-gray-300 rounded-lg p-1 sm:p-2 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 text-sm sm:text-base"
                                    placeholder="0" max="<?php echo e($cartProfitBeforeDiscount); ?>" />
                                <span class="text-gray-500 text-sm sm:text-base">افغانی</span>
                            </div>
                        </div>

                        <!-- نمایش سود خالص -->
                        <div
                            class="flex justify-between items-center bg-green-50 p-2 sm:p-3 rounded-lg border-2 border-green-200">
                            <span class="text-gray-700 font-bold text-sm sm:text-base">
                                <i class="fa-solid fa-calculator ml-1 text-green-600"></i>
                                سود خالص:
                            </span>
                            <span class="font-bold text-lg sm:text-xl text-green-600"><?php echo e(number_format($cartProfit)); ?>

                                افغانی</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-sm sm:text-base">مبلغ پرداختی:</span>
                            <div class="flex items-center gap-1 sm:gap-2">
                                <?php if($saleType === 'retail'): ?>
                                <!-- در فروش پرچون، مبلغ پرداختی غیرقابل ویرایش است -->
                                <input type="number" wire:model="paidAmount"
                                    class="w-24 sm:w-32 text-right border border-gray-300 rounded-lg p-1 sm:p-2 bg-gray-100 cursor-not-allowed text-sm sm:text-base"
                                    readonly />
                                <span class="text-gray-500 text-sm sm:text-base">افغانی</span>
                                <div class="text-xs text-green-600 flex items-center">
                                    <i class="fa-solid fa-lock ml-1"></i>
                                    نقدی
                                </div>
                                <?php else: ?>
                                <!-- در فروش عمده، مبلغ پرداختی قابل ویرایش است -->
                                <input type="number" wire:model="paidAmount" wire:change="calculateCartTotals"
                                    class="w-24 sm:w-32 text-right border border-gray-300 rounded-lg p-1 sm:p-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm sm:text-base"
                                    placeholder="0" />
                                <span class="text-gray-500 text-sm sm:text-base">افغانی</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="border-t pt-2 sm:pt-3 mt-2 sm:mt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-800 text-sm sm:text-base">باقی مانده:</span>
                                <span
                                    class="font-bold text-lg sm:text-xl <?php echo e($remainingAmount > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                    <?php if($saleType === 'retail'): ?>
                                    <span class="text-green-600">۰</span>
                                    <?php else: ?>
                                    <?php echo e(number_format($remainingAmount)); ?>

                                    <?php endif; ?>
                                    افغانی
                                </span>
                            </div>
                            <?php if($saleType === 'retail'): ?>
                            <div class="text-xs text-green-600 mt-1 flex items-center">
                                <i class="fa-solid fa-check-circle ml-1"></i>
                                فروش پرچون به صورت نقدی انجام می‌شود
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- دکمه‌های نهایی -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6">
                    <button wire:click="submitSale"
                        class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-3 sm:py-4 rounded-lg font-bold hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitSale">
                            <i class="fa-solid fa-check-circle ml-2"></i>
                            ثبت فروش و چاپ فاکتور
                        </span>
                        <span wire:loading wire:target="submitSale">
                            <i class="fa-solid fa-spinner fa-spin ml-2"></i>
                            در حال ثبت...
                        </span>
                    </button>

                    <button wire:click="resetForm"
                        class="flex-1 bg-gradient-to-r from-red-500 to-red-600 text-white py-3 sm:py-4 rounded-lg font-bold hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base">
                        <i class="fa-solid fa-times-circle ml-2"></i>
                        انصراف
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px]  mt-[30px] sm:overflow-x-auto md:mt-0 lg:mt-0 mx-auto  md:mx-auto lg:mx-auto w-[440px] mb-5 md:w-[430px] lg:w-[200px] "
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center p-3 sm:p-4 border-b border-gray-300 mb-4 sm:mb-6 gap-3 sm:gap-4">
                <h2 class="text-lg sm:text-xl font-bold vazir text-gray-800">
                    <i class="fa-solid fa-list ml-2 text-indigo-600"></i>
                    لیست فروش‌های ثبت شده
                </h2>

                <div class="flex gap-2 items-center w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input type="text" wire:model.live="filterInvoice" placeholder="جستجو براساس شماره فاکتور"
                            class="border border-gray-300 rounded-lg p-2 sm:p-3 vazir text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 w-full md:w-64"
                            dir="ltr" />
                    </div>
                    <?php if($filterInvoice): ?>
                    <button wire:click="$set('filterInvoice', '')"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fa-solid fa-times"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full text-xs sm:text-sm text-left rtl:text-right text-gray-500">
                    <thead class="bg-gradient-to-br from-indigo-400 to-indigo-500 text-white text-xs sm:text-sm vazir">
                        <tr>
                            <th class="p-2 sm:p-4 font-bold text-center">#</th>
                            <th class="p-2 sm:p-4 font-bold">نوع فروش</th>
                            <th class="p-2 sm:p-4 font-bold">شرح فروش</th>
                            <th class="p-2 sm:p-4 font-bold">مشتری</th>
                            <th class="p-2 sm:p-4 font-bold text-center">مبلغ کل</th>
                            <th class="p-2 sm:p-4 font-bold text-center">پرداختی</th>
                            <th class="p-2 sm:p-4 font-bold text-center">باقی‌مانده</th>
                            <th class="p-2 sm:p-4 font-bold text-center">سود</th>
                            <th class="p-2 sm:p-4 font-bold text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <td class="p-2 sm:p-4 text-center">
                                <span class="text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                                    <?php echo e($sale->id); ?>

                                </span>
                            </td>
                            <td class="p-2 sm:p-4">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($sale->sale_type === 'retail' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'); ?>">
                                    <?php if($sale->sale_type === 'retail'): ?>
                                    <i class="fa-solid fa-user mr-1"></i>
                                    <?php else: ?>
                                    <i class="fa-solid fa-users mr-1"></i>
                                    <?php endif; ?>
                                    <?php echo e($sale->sale_type === 'retail' ? 'پرچون' : 'عمده'); ?>

                                </span>
                            </td>
                            <td class="p-2 sm:p-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 text-xs sm:text-sm">
                                        <?php echo e($sale->buyer_name ?: 'بدون توضیح'); ?>

                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">
                                        <i class="fa-solid fa-calendar ml-1"></i>
                                        <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($sale->created_at)->format('Y/m/d
                                        H:i')); ?>

                                    </span>
                                    <?php if($sale->description): ?>
                                    <span class="text-xs text-gray-500 mt-1">
                                        <i class="fa-solid fa-file-lines ml-1"></i>
                                        <?php echo e(Str::limit($sale->description, 30)); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="p-2 sm:p-4">
                                <?php if($sale->customer): ?>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm"><?php echo e($sale->customer->fullname); ?></span>
                                    <?php if($sale->customer->phone): ?>
                                    <span class="text-xs text-gray-500 mt-1 flex items-center">
                                        <i class="fa-solid fa-phone ml-1"></i>
                                        <?php echo e($sale->customer->phone); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <span class="text-gray-400 italic text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-2 sm:p-4 text-center">
                                <span class="font-bold text-gray-900 text-xs sm:text-sm"><?php echo e(number_format($sale->total_price)); ?></span>
                                <div class="text-xs text-gray-500 mt-1">افغانی</div>
                            </td>
                            <td class="p-2 sm:p-4 text-center">
                                <span class="font-bold text-green-600 text-xs sm:text-sm"><?php echo e(number_format($sale->received_amount)); ?></span>
                                <div class="text-xs text-gray-500 mt-1">افغانی</div>
                            </td>
                            <td class="p-2 sm:p-4 text-center">
                                <span
                                    class="font-bold <?php echo e($sale->remaining_amount > 0 ? 'text-red-600' : 'text-green-600'); ?> text-xs sm:text-sm">
                                    <?php echo e(number_format($sale->remaining_amount)); ?>

                                </span>
                                <div class="text-xs text-gray-500 mt-1">افغانی</div>
                            </td>
                            <td class="p-2 sm:p-4 text-center">
                                <div class="flex flex-col items-center">
                                    <?php
                                    $saleProfit = $sale->saleItems->sum('profit');
                                    ?>
                                    <span class="font-bold <?php echo e($saleProfit >= 0 ? 'text-green-600' : 'text-red-600'); ?> text-xs sm:text-sm">
                                        <?php echo e(number_format($saleProfit)); ?>

                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">افغانی</div>
                                </div>
                            </td>
                            <td class="p-2 sm:p-4 text-center">
                                <div class="flex justify-center items-center space-x-1 sm:space-x-2 space-x-reverse">
                                    <button wire:click="printInvoice(<?php echo e($sale->id); ?>)"
                                        class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors duration-200 border border-blue-200 text-xs sm:text-sm"
                                        title="چاپ فاکتور" wire:loading.attr="disabled"
                                        wire:target="printInvoice(<?php echo e($sale->id); ?>)">
                                        <i class="fa-solid fa-print"></i>
                                    </button>

                                    <!-- دکمه برگشت کالا -->
                                    <button wire:click="selectSaleForReturn(<?php echo e($sale->id); ?>)"
                                        class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors duration-200 border border-red-200 text-xs sm:text-sm"
                                        title="برگشت کالا">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </div>

                                <!-- نمایش loading هنگام چاپ -->
                                <div wire:loading wire:target="printInvoice(<?php echo e($sale->id); ?>)" class="mt-1 sm:mt-2">
                                    <div
                                        class="inline-flex items-center text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                                        در حال چاپ...
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="p-4 sm:p-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 py-4 sm:py-8">
                                    <i class="fa-solid fa-cart-shopping text-4xl sm:text-6xl mb-2 sm:mb-4 text-gray-300"></i>
                                    <p class="text-base sm:text-lg font-medium mb-1 sm:mb-2 text-gray-400">هیچ فروشی یافت نشد</p>
                                    <p class="text-xs sm:text-sm text-gray-500">فروش جدیدی ثبت کنید تا در اینجا نمایش داده شود</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- صفحه‌بندی -->
            <?php if($sales->hasPages()): ?>
            <div class="mt-4 sm:mt-6 px-2 sm:px-4">
                <?php echo e($sales->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- مودال برگشت کالا -->
    <?php if($selectedSaleForReturn): ?>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800">
                        <i class="fa-solid fa-rotate-left ml-2 text-red-600"></i>
                        برگشت کالا - فاکتور شماره <?php echo e($selectedSaleForReturn->invoice_number); ?>

                    </h3>
                    <button wire:click="resetReturn" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times text-lg sm:text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                <!-- اطلاعات فاکتور -->
                <div class="bg-blue-50 p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 text-xs sm:text-sm">
                        <div>
                            <span class="font-medium">مشتری:</span>
                            <span><?php echo e($selectedSaleForReturn->customer ? $selectedSaleForReturn->customer->fullname :
                                'خریدار نقدی'); ?></span>
                        </div>
                        <div>
                            <span class="font-medium">تاریخ فاکتور:</span>
                            <span><?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($selectedSaleForReturn->created_at)->format('Y/m/d
                                H:i')); ?></span>
                        </div>
                        <div>
                            <span class="font-medium">مبلغ کل:</span>
                            <span><?php echo e(number_format($selectedSaleForReturn->total_price)); ?> افغانی</span>
                        </div>
                    </div>
                </div>

                <!-- آیتم‌های قابل برگشت -->
                <div class="mb-4 sm:mb-6">
                    <h4 class="font-bold text-base sm:text-lg mb-3 sm:mb-4 text-gray-700">کالاهای قابل برگشت</h4>
                    <div class="space-y-2 sm:space-y-3">
                        <?php $__currentLoopData = $returnItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-3 sm:p-4 bg-white border border-gray-200 rounded-lg">
                            <div class="flex-1 mb-2 sm:mb-0">
                                <div class="font-medium text-gray-900 text-sm"><?php echo e($item['product_name']); ?></div>
                                <div class="text-xs text-gray-500">
                                    <i class="fa-solid fa-barcode ml-1"></i>
                                    <?php echo e($item['barcode']); ?>

                                </div>
                                <div class="text-xs text-gray-500">
                                    قیمت واحد: <?php echo e(number_format($item['unit_price'])); ?> افغانی
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">
                                <div class="text-center">
                                    <div class="text-xs text-gray-600">تعداد خریداری شده</div>
                                    <div class="font-bold text-blue-600 text-sm"><?php echo e($item['quantity']); ?></div>
                                </div>

                                <div class="flex items-center gap-1 sm:gap-2">
                                    <button wire:click="decreaseReturnQuantity(<?php echo e($index); ?>)"
                                        class="w-6 h-6 sm:w-8 sm:h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors text-xs">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    <input type="number" wire:model="returnItems.<?php echo e($index); ?>.return_quantity" min="0"
                                        max="<?php echo e($item['max_returnable']); ?>"
                                        class="w-16 sm:w-20 text-center border border-gray-300 rounded-lg p-1 sm:p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">

                                    <button wire:click="increaseReturnQuantity(<?php echo e($index); ?>)"
                                        class="w-6 h-6 sm:w-8 sm:h-8 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors text-xs">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>

                                <div class="text-center">
                                    <div class="text-xs text-gray-600">مبلغ برگشتی</div>
                                    <div class="font-bold text-green-600 text-sm">
                                        <?php echo e(number_format($item['return_quantity'] * $item['unit_price'])); ?> افغانی
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- خلاصه برگشت -->
                <div class="bg-gradient-to-r from-gray-50 to-slate-100 p-4 sm:p-6 rounded-lg border border-gray-200 mb-4 sm:mb-6">
                    <h4 class="font-bold text-gray-800 mb-3 sm:mb-4 text-base sm:text-lg">خلاصه برگشت</h4>
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-sm sm:text-base">مجموع مبلغ برگشتی:</span>
                            <span class="font-bold text-base sm:text-lg text-red-600"><?php echo e(number_format($returnTotal)); ?> افغانی</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 text-sm sm:text-base">مبلغ قابل بازگشت:</span>
                            <span class="font-bold text-base sm:text-lg text-green-600"><?php echo e(number_format($refundAmount)); ?>

                                افغانی</span>
                        </div>
                    </div>
                </div>

                <!-- دلیل برگشت -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        <i class="fa-solid fa-comment ml-1 text-orange-600"></i>
                        دلیل برگشت
                    </label>
                    <textarea wire:model="returnReason" rows="3"
                        class="w-full border border-gray-300 rounded-lg p-2 sm:p-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 resize-none text-sm sm:text-base"
                        placeholder="دلیل برگشت کالا را وارد کنید..."></textarea>
                </div>

                <!-- دکمه‌های اقدام -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <button wire:click="submitReturn"
                        class="flex-1 bg-gradient-to-r from-red-500 to-red-600 text-white py-3 sm:py-4 rounded-lg font-bold hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base">
                        <i class="fa-solid fa-check-circle ml-2"></i>
                        ثبت برگشت و بازگشت وجه
                    </button>

                    <button wire:click="resetReturn"
                        class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 text-white py-3 sm:py-4 rounded-lg font-bold hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center text-sm sm:text-base">
                        <i class="fa-solid fa-times-circle ml-2"></i>
                        انصراف
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('livewire:load', function() {
        // تاریخ‌پیکر
        if (typeof kamaDatepicker !== 'undefined') {
            kamaDatepicker('datePicker', {
                buttonsColor: "blue",
                forceFarsiDigits: true,
                markToday: true,
                markHolidays: true,
                gotoToday: true,
                highlightSelectedDay: true,
                placeholder: "تاریخ را انتخاب کنید"
            });
        }

        // مدیریت فوکوس
        Livewire.on('focus-quantity', () => {
            setTimeout(() => {
                const quantityInput = document.getElementById('quantityInput');
                if (quantityInput) {
                    quantityInput.focus();
                    quantityInput.select();
                }
            }, 100);
        });

        // اتو فوکوس روی جستجوی محصول
        setTimeout(() => {
            document.getElementById('productSearch')?.focus();
        }, 500);
    });

    // شبیه‌سازی اسکنر بارکد
    function startBarcodeScanner() {
        const barcode = prompt('لطفاً بارکد را اسکن کنید یا وارد نمایید:');
        if (barcode && barcode.trim() !== '') {
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').scanBarcode(barcode.trim());
        }
    }

    // مدیریت کلیدهای سریع
    document.addEventListener('keydown', function(e) {
        // Ctrl+B برای اسکنر بارکد
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            startBarcodeScanner();
        }
        
        // فوکوس روی جستجوی محصول با Ctrl+P
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            document.getElementById('productSearch').focus();
        }

        // ریست فرم با Ctrl+R
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').resetForm();
        }

        // فوکوس روی تعداد با Ctrl+Q
        if (e.ctrlKey && e.key === 'q') {
            e.preventDefault();
            document.getElementById('quantityInput').focus();
        }
    });

    // اتو محاسبه وقتی تعداد تغییر می‌کند
    document.addEventListener('input', function(e) {
        if (e.target.id === 'quantityInput' || e.target.id === 'unitPriceInput') {
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').calculateCurrentTotal();
        }
    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/sales.blade.php ENDPATH**/ ?>