<div x-data="{
    saleType: <?php if ((object) ('saleType') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('saleType'->value()); ?>')<?php echo e('saleType'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('saleType'); ?>')<?php endif; ?>,
    searchQuery: <?php if ((object) ('searchName') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('searchName'->value()); ?>')<?php echo e('searchName'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('searchName'); ?>')<?php endif; ?>,
    showSuggestions: false,
    focused: false,
    
    init() {
        // هندلر برای چاپ فاکتور
        Livewire.on('open-print-window', (data) => {
            this.openPrintWindow(data.content);
        });

        // فوکوس روی فیلد بارکد
        this.$nextTick(() => {
            const barcodeInput = this.$refs.barcodeInput;
            if (barcodeInput) {
                barcodeInput.focus();
            }
        });
    },
    
    onSearchFocus() {
        this.focused = true;
        this.showSuggestions = this.searchQuery.length > 0;
    },
    
    onSearchBlur() {
        setTimeout(() => {
            this.focused = false;
            this.showSuggestions = false;
        }, 200);
    },
    
    onSearchInput() {
        this.showSuggestions = this.searchQuery.length > 0;
    },
    
    selectSuggestion(product) {
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').selectProduct(product.id);
        this.showSuggestions = false;
        this.searchQuery = product.product_name;
    },
    
    openPrintWindow(content) {
        const printWindow = window.open('', '_blank', 'width=900,height=700,scrollbars=yes');
        printWindow.document.write(content);
        printWindow.document.close();
    }
}" class="container mx-auto px-4 py-6 bg-gray-50 min-h-screen vazir" wire:ignore.self>
    <!-- Alert Messages -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span class="vazir"><?php echo e(session('message')); ?></span>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="vazir"><?php echo e(session('error')); ?></span>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- آمار سریع -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">فروش امروز</h3>
                    <p class="text-2xl font-bold mt-2"><?php echo e($stats['total_sales']); ?></p>
                </div>
                <i class="fas fa-shopping-cart text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">درآمد امروز</h3>
                    <p class="text-2xl font-bold mt-2"><?php echo e(number_format($stats['total_amount'])); ?> AFN</p>
                </div>
                <i class="fas fa-money-bill-wave text-3xl opacity-80"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">مشتریان</h3>
                    <p class="text-2xl font-bold mt-2"><?php echo e($stats['total_customers']); ?></p>
                </div>
                <i class="fas fa-users text-3xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- هدر -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- فرم فروش -->
        <div class="lg:w-1/2 bg-white rounded-2xl shadow-lg p-6">
            <!-- بالای فرم: نوع فروش -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6 p-4 bg-gray-50 rounded-xl border">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-cash-register text-blue-500"></i>
                    فورم ثبت فروش
                </h2>

                <div class="flex gap-3">
                    <button @click="saleType = 'retail'; $wire.switchToRetail();" 
                        class="px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2
                        <?php echo e($saleType === 'retail' ? 'bg-red-500 text-white shadow-lg scale-105' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                        <i class="fas fa-shopping-bag"></i>
                        فروش پرچون
                    </button>
                    
                    <button @click="saleType = 'wholesale'; $wire.switchToWholesale();"
                        class="px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2
                        <?php echo e($saleType === 'wholesale' ? 'bg-green-500 text-white shadow-lg scale-105' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        فروش عمده
                    </button>
                </div>
            </div>

            <!-- فرم افزودن محصول -->
            <form wire:submit.prevent="addToCart" class="space-y-4">
                <!-- انتخاب مشتری فقط در فروش عمده -->
                <template x-if="saleType === 'wholesale'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">👤 انتخاب خریدار</label>
                        <div class="relative">
                            <select wire:model="customer_id"
                                class="w-full h-14 p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white">
                                <option value="">انتخاب خریدار...</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->fullname); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- اسکن بارکد -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">📦 اسکن بارکد</label>
                    <input wire:model="barcode" x-ref="barcodeInput" placeholder="بارکد را اسکن کنید..."
                        class="w-full h-14 p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-medium" 
                        autocomplete="off" />
                </div>

                <!-- جستجوی محصول -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">🔍 جستجو با نام محصول</label>
                    <input x-model="searchQuery" 
                           @focus="onSearchFocus()"
                           @blur="onSearchBlur()"
                           @input="onSearchInput()"
                           placeholder="نام محصول را تایپ کنید..."
                           class="w-full h-14 p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           autocomplete="off" />
                    
                    <!-- لیست پیشنهادات -->
                    <div x-show="showSuggestions && focused" 
                         class="absolute w-full bg-white border border-gray-300 rounded-xl shadow-lg mt-1 max-h-60 overflow-y-auto z-50">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $suggestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer text-gray-800 border-b border-gray-100"
                             @click="selectSuggestion(<?php echo \Illuminate\Support\Js::from($product)->toHtml() ?>)">
                            <div class="font-medium text-sm"><?php echo e($product['product_name']); ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                بارکد: <?php echo e($product['barcode']); ?> | 
                                موجودی: <?php echo e($product['total_quantity']); ?> |
                                <!--[if BLOCK]><![endif]--><?php if($saleType === 'wholesale'): ?>
                                قیمت عمده: <?php echo e(number_format($product['wholesale_price'])); ?> AFN
                                <?php else: ?>
                                قیمت پرچون: <?php echo e(number_format($product['retail_price'])); ?> AFN
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 py-3 text-gray-500 text-sm">
                            محصولی یافت نشد
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- تعداد و قیمت -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">🔢 تعداد</label>
                        <input type="number" wire:model="quantity" min="1" max="999"
                            class="w-full h-14 p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg font-medium" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            💰 قیمت (AFN)
                            <span class="text-xs text-gray-500">
                                <!--[if BLOCK]><![endif]--><?php if($saleType === 'wholesale'): ?>
                                (عمده)
                                <?php else: ?>
                                (پرچون)
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                        </label>
                        <input type="number" wire:model="customPrice" min="0" step="0.01"
                            class="w-full h-14 p-4 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-left text-lg font-medium"
                            placeholder="قیمت پیشفرض" />
                    </div>
                </div>

                <!-- دکمه افزودن -->
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white h-14 rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                    <i class="fas fa-plus-circle text-xl"></i>
                    افزودن به فاکتور
                </button>

                <!-- اطلاعات محصول انتخاب شده -->
                <!--[if BLOCK]><![endif]--><?php if($selectedProduct): ?>
                <?php
                    $product = \App\Models\Tools\Warehouses::find($selectedProduct);
                ?>
                <!--[if BLOCK]><![endif]--><?php if($product): ?>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-2">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-blue-800"><?php echo e($product->product_name); ?></h4>
                            <p class="text-sm text-blue-600">بارکد: <?php echo e($product->barcode); ?></p>
                            <p class="text-xs text-gray-600 mt-1">
                                <!--[if BLOCK]><![endif]--><?php if($saleType === 'wholesale'): ?>
                                قیمت عمده: <?php echo e(number_format($product->wholesale_price)); ?> AFN
                                <?php else: ?>
                                قیمت پرچون: <?php echo e(number_format($product->retail_price)); ?> AFN
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">
                                <?php echo e(number_format($customPrice ?: ($saleType === 'wholesale' ? $product->wholesale_price : $product->retail_price))); ?> AFN
                            </p>
                            <p class="text-xs text-gray-500">موجودی: <?php echo e($product->total_quantity); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </form>

            <!-- لیست فروشات اخیر -->
            <div class="mt-6">
                <div class="border border-gray-200 rounded-xl p-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center justify-between">
                        <span>📋 فروشات اخیر</span>
                        <span class="text-sm font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded">
                            امروز
                        </span>
                    </h2>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sales->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-gray-800"><?php echo e($sale->sale_number); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($sale->created_at->format('Y/m/d - H:i')); ?></div>
                                    <!--[if BLOCK]><![endif]--><?php if($sale->customer): ?>
                                    <div class="text-xs text-blue-600 mt-1"><?php echo e($sale->customer->fullname); ?></div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-green-600"><?php echo e(number_format($sale->final_amount, 2)); ?> AFN</div>
                                    <div class="text-xs <?php echo e($sale->remaining_amount > 0 ? 'text-orange-500' : 'text-green-500'); ?>">
                                        <?php echo e($sale->remaining_amount > 0 ? 'قسطی' : 'تسویه'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-gray-500 py-8">
                            <i class="fas fa-receipt text-3xl mb-2 text-gray-300"></i>
                            <p>فروشی یافت نشد</p>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول کالاها و خلاصه فاکتور -->
        <div class="lg:w-1/2 bg-white rounded-2xl shadow-lg p-6">
            <!-- بالای جدول: عنوان -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6 p-4 bg-gray-50 rounded-xl border">
                <h1 class="text-xl font-bold text-gray-800">🛒 لیست کالاهای فاکتور</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-2 rounded-full">
                        تعداد: 
                        <span class="font-bold text-blue-600"><?php echo e(count($items)); ?></span>
                    </span>
                    <!--[if BLOCK]><![endif]--><?php if(count($items) > 0): ?>
                    <button wire:click="clearCart" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition flex items-center gap-2">
                        <i class="fas fa-trash"></i>
                        پاک کردن همه
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- جدول کالاها -->
            <div class="overflow-x-auto">
                <div class="max-h-96 overflow-y-auto">
                    <table class="w-full text-sm text-right">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 sticky top-0">
                            <tr>
                                <th class="p-4 font-bold border-b">نام محصول</th>
                                <th class="p-4 font-bold border-b">تعداد</th>
                                <th class="p-4 font-bold border-b">قیمت واحد</th>
                                <th class="p-4 font-bold border-b">مجموع</th>
                                <th class="p-4 font-bold border-b">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4">
                                    <div class="font-medium text-gray-800"><?php echo e($item['name']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($item['barcode']); ?></div>
                                    <div class="text-xs text-green-600 mt-1">موجودی: <?php echo e($item['max_quantity']); ?></div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="decreaseQuantity(<?php echo e($index); ?>)"
                                            class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow">
                                            −
                                        </button>
                                        <span class="font-medium w-12 text-center text-lg bg-gray-100 py-1 rounded">
                                            <?php echo e($item['quantity']); ?>

                                        </span>
                                        <button wire:click="increaseQuantity(<?php echo e($index); ?>)"
                                            class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition shadow">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <input type="number" min="0" step="0.01" 
                                        wire:model.lazy="items.<?php echo e($index); ?>.price"
                                        wire:change="updateItemPrice(<?php echo e($index); ?>)"
                                        class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-sm text-left focus:ring-2 focus:ring-blue-500" />
                                </td>
                                <td class="p-4">
                                    <span class="font-semibold text-blue-600 text-lg">
                                        <?php echo e(number_format($item['total'], 2)); ?> AFN
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button wire:click="removeItem(<?php echo e($index); ?>)"
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition shadow flex items-center gap-2">
                                        <i class="fas fa-trash"></i>
                                        حذف
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-xl mb-2">سبد خرید خالی است</p>
                                        <p class="text-sm text-gray-400">محصولات را از طریق اسکن بارکد یا جستجو اضافه کنید</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(count($items) > 0): ?>
            <!-- خلاصه فاکتور -->
            <div class="mt-6 border border-gray-200 rounded-2xl p-6 space-y-4 bg-gradient-to-br from-gray-50 to-white shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">🧾 خلاصه فاکتور</h2>

                <!-- مجموع کل -->
                <div class="flex items-center justify-between py-3">
                    <span class="text-lg font-medium text-gray-700">💰 مجموع کل فاکتور:</span>
                    <span class="text-xl font-bold text-blue-600"><?php echo e(number_format($this->getTotalAmountProperty(), 2)); ?> AFN</span>
                </div>

                <!-- تخفیف -->
                <div class="flex items-center justify-between py-3 bg-yellow-50 rounded-lg px-4">
                    <span class="text-lg font-medium text-gray-700">🎁 تخفیف:</span>
                    <div class="flex items-center gap-2">
                        <input wire:model.lazy="discount" type="number" min="0" step="0.01"
                            class="w-32 h-10 border border-yellow-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-500 text-left bg-white" />
                        <span class="text-gray-600 font-bold">AFN</span>
                    </div>
                </div>

                <!-- مبلغ نهایی -->
                <div class="flex items-center justify-between py-4 border-t border-gray-200">
                    <span class="text-xl font-bold text-gray-800">✅ مبلغ نهایی:</span>
                    <span class="text-2xl font-bold text-green-600"><?php echo e(number_format($this->getFinalAmountProperty(), 2)); ?> AFN</span>
                </div>

                <!-- مبلغ پرداخت شده -->
                <div class="space-y-3 bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-medium text-gray-700">💵 مبلغ پرداخت شده:</span>
                        <div class="flex items-center gap-2">
                            <input wire:model.lazy="receivedAmount" type="number" min="0" step="0.01"
                                class="w-32 h-10 border border-green-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 text-left bg-white" />
                            <span class="text-gray-600 font-bold">AFN</span>
                        </div>
                    </div>
                </div>

                <!-- مبلغ باقیمانده برای فروش عمده -->
                <template x-if="saleType === 'wholesale'">
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between bg-red-50 rounded-lg p-4">
                            <span class="text-lg font-medium text-gray-700">🧾 باقیمانده:</span>
                            <span class="text-xl font-bold text-red-600">
                                <?php echo e(number_format($this->getRemainingAmountProperty(), 2)); ?> AFN
                            </span>
                        </div>
                    </div>
                </template>

                <!-- توضیحات -->
                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">📝 توضیحات (اختیاری)</label>
                    <textarea wire:model="notes" rows="2" placeholder="توضیحات فاکتور..."
                        class="w-full p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                </div>

                <!-- دکمه‌های action -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button wire:click="finalizeAndPrintInvoice" 
                        class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white h-14 rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">
                        <i class="fas fa-print text-xl"></i>
                        ثبت و چاپ فاکتور
                    </button>
                    
                    <button wire:click="resetAll" type="button"
                        class="px-8 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white h-14 rounded-xl font-medium transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                        <i class="fas fa-redo"></i>
                        ریست
                    </button>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    <style>
.vazir {
    font-family: 'Vazir', 'B Nazanin', 'Tahoma', sans-serif;
}

.scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb #f9fafb;
}

.scroll-container::-webkit-scrollbar {
    width: 6px;
}

.scroll-container::-webkit-scrollbar-track {
    background: #f9fafb;
    border-radius: 10px;
}

.scroll-container::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}

.scroll-container::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>
</div>


</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/salesmanage.blade.php ENDPATH**/ ?>