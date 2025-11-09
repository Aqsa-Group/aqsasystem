<div>
    <!-- Alert Messages -->
    <?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#DC2626] vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>



      <div class="flex flex-col  space-y-3 pr-0 md:pr-10 lg:pr-24 xl:pr-24">
            <h1 class="text-[25px] mb-2 yekan">مدیریت اجناس گدام </h1>
            <h1 class="text-[rgb(140,140,140)] border-b border-[#D9D9D9] pb-6">لیست تمام محصولات و موجودی گدام</h1>
            <h1 class="text-[16px] font-medium pb-4">ثبت محصول جدید</h1>
        </div> 


    <div class="flex flex-col   ">


        
        <div class="w-[400px] md:w-[400px] lg:w-[750px] xl:w-[1300px]  bg-[#F5F5F5] rounded-[12px] p-6 mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040;">
            <form wire:submit.prevent="saveProduct" class="space-y-8">

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- ستون سمت راست -->
                    <div class="flex-1 flex flex-col space-y-6">

                        
                        
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-medium text-blue-800 mb-3">جستجو و انتقال از گدام</h3>

                            
                            <div class="mb-4">
                                <label class="block text-[16px] font-medium text-black vazir">بارکد یا نام محصول از
                                    گدام</label>
                                <div class="flex gap-2">
                                    <input type="text" wire:model="search_query"
                                        class="flex-1 h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        placeholder="بارکد یا نام محصول را وارد کنید">
                                    <button type="button" wire:click="searchFromInventory"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-[12px] transition">
                                        جستجو در گدام
                                    </button>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    پس از وارد کردن بارکد یا نام محصول، دکمه "جستجو در گدام" را بزنید
                                </div>
                                <?php $__errorArgs = ['search_query'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <?php if($show_transfer_section && $inventory_product): ?>
                            <div class="bg-green-50 p-3 rounded-lg border border-green-200 mb-4">
                                <h4 class="font-medium text-green-800">محصول یافت شده در گدام:</h4>
                                <div class="grid grid-cols-2 gap-2 mt-2 text-sm">
                                    <div>نام: <strong><?php echo e($inventory_product->product_name); ?></strong></div>
                                    <div>بارکد: <strong><?php echo e($inventory_product->barcode); ?></strong></div>
                                    <div>موجودی: <strong><?php echo e(number_format($inventory_product->total_packages)); ?> <?php echo e($inventory_product->package_type); ?></strong></div>
                                    <div>قیمت خرید: <strong><?php echo e(number_format($inventory_product->purchase_price_per_package)); ?></strong>
                                    </div>
                                    <div>قیمت فروش: <strong><?php echo e(number_format($inventory_product->retail_price)); ?></strong></div>
                                    <div>دسته بندی: <strong><?php echo e($inventory_product->category); ?></strong></div>
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label class="block text-[16px] font-medium text-black vazir">تعداد برداشت از
                                    گدام</label>
                                <div class="flex gap-2 mb-2">
                                    <input type="number" wire:model="transfer_quantity" min="1"
                                        max="<?php echo e($inventory_product->total_packages); ?>" wire:keydown.enter.prevent
                                        class="flex-1 h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                        placeholder="تعداد مورد نظر را وارد کنید">
                                </div>
                                <div class="text-xs text-gray-500 mb-3">
                                    حداکثر قابل برداشت: <?php echo e(number_format($inventory_product->total_packages)); ?> <?php echo e($inventory_product->package_type); ?>

                                </div>

                                <div class="flex gap-2">
                                    <button type="button" wire:click="transferFromInventory"
                                        wire:loading.attr="disabled"
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-[12px] transition flex items-center gap-2">
                                        <i class="fas fa-exchange-alt"></i>
                                        انتقال به دوکان
                                    </button>

                                    <button type="button" wire:click="cancelTransfer"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-[12px] transition flex items-center gap-2">
                                        <i class="fas fa-times"></i>
                                        لغو
                                    </button>
                                </div>

                                <?php $__errorArgs = ['transfer_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-2 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black vazir">بارکد محصول</label>
                            <input type="text" wire:model="barcode"
                                class="w-full h-[60px] p-3 rounded-[12px] border border-[#8C8C8C] bg-transparent focus:ring-2 focus:ring-blue-500"
                                placeholder="بارکد محصول را وارد کنید">
                            <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نام جنس</label>
                            <input type="text" wire:model="product_name"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400 placeholder:text-[#404040]"
                                placeholder="نام کامل محصول">
                            <?php $__errorArgs = ['product_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>


                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">دسته بندی</label>
                                <select id="categorySelect"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                    <option value="">انتخاب دسته بندی</option>
                                    <option value="ابزار و صنعتی">ابزار و صنعتی</option>
                                    <option value="سوپرمارکت">سوپرمارکت</option>
                                    <option value="آرایشی و بهداشتی">آرایشی و بهداشتی</option>
                                    <option value="خودرو و موتورسیکلت">خودرو و موتورسیکلت</option>
                                    <option value="لوازم خانگی">لوازم خانگی</option>
                                    <option value="الکترونیک و دیجیتال">الکترونیک و دیجیتال</option>
                                    <option value="پوشاک و مد">پوشاک و مد</option>
                                    <option value="خانه و آشپزخانه">خانه و آشپزخانه</option>
                                    <option value="سرگرمی و hobbies">سرگرمی و hobbies</option>
                                    <option value="کودک و نوزاد">کودک و نوزاد</option>
                                </select>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">زیر دسته</label>
                                <select id="subCategorySelect"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                    <option value="">ابتدا دسته بندی را انتخاب کنید</option>
                                </select>
                            </div>
                        </div>


                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">واحد جنس</label>
                            <select wire:model="unit"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <option value="">انتخاب واحد</option>
                                <option value="عدد">عدد</option>
                                <option value="کیلوگرم">کیلوگرم</option>
                                <option value="گرم">گرم</option>
                                <option value="لیتر">لیتر</option>
                                <option value="متر">متر</option>
                                <option value="جعبه">جعبه</option>
                                <option value="بسته">بسته</option>
                            </select>
                            <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">نوع
                                    بسته‌بندی</label>
                                <select wire:model="package_type"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                    <option value="کارتن">کارتن</option>
                                    <option value="بسته">بسته</option>
                                    <option value="دانه">دانه</option>
                                </select>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                    <?php if($package_type === 'دانه'): ?>
                                    تعداد
                                    <?php else: ?>
                                    تعداد در هر <?php echo e($package_type); ?>

                                    <?php endif; ?>
                                </label>
                                <input type="number" wire:model="quantity_per_package" wire:change="calculatePrices"
                                    min="1"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <?php $__errorArgs = ['quantity_per_package'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                    تعداد <?php echo e($package_type === 'دانه' ? 'دانه' : $package_type); ?>

                                </label>
                                <input type="number" wire:model="total_packages" wire:change="calculatePrices" min="0"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <?php $__errorArgs = ['total_packages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">موجودی کل (<?php echo e($unit); ?>)</label>
                                <input type="text" value="<?php echo e(number_format($total_quantity)); ?>" readonly
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-gray-100 border border-[#8C8C8C]">
                            </div>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">حداقل موجودی برای
                                اعلان</label>
                            <input type="number" wire:model="min_stock_level" min="0"
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                            <?php $__errorArgs = ['min_stock_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- ستون سمت چپ -->
                    <div class="flex-1 flex flex-col space-y-6">

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                    قیمت خرید هر <?php echo e($package_type === 'دانه' ? 'دانه' : $package_type); ?>

                                </label>
                                <input type="number" wire:model="purchase_price_per_package"
                                    wire:change="calculatePrices" min="0" step="0.01"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <?php $__errorArgs = ['purchase_price_per_package'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">قیمت خرید هر <?php echo e($unit); ?></label>
                                <input type="text" value="<?php echo e(number_format($purchase_price_per_unit, 2)); ?>" readonly
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-gray-100 border border-[#8C8C8C]">
                            </div>
                        </div>

                        
                        <div>
                            <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">مبلغ کل خرید</label>
                            <input type="text" value="<?php echo e(number_format($total_purchase_amount)); ?>" readonly
                                class="w-full pr-4 h-[59px] rounded-[12px] bg-gray-100 border border-[#8C8C8C]">
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">قیمت فروش پرچون
                                    (هر <?php echo e($unit); ?>)</label>
                                <input type="number" wire:model="retail_price" min="0" step="0.01"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <?php $__errorArgs = ['retail_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">قیمت فروش عمده (هر
                                    <?php echo e($unit); ?>)</label>
                                <input type="number" wire:model="wholesale_price" wire:change="calculatePrices" min="0"
                                    step="0.01"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <?php $__errorArgs = ['wholesale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                    <?php if($profit_loss_per_unit >= 0): ?>
                                    سود هر <?php echo e($unit); ?>

                                    <?php else: ?>
                                    ضرر هر <?php echo e($unit); ?>

                                    <?php endif; ?>
                                </label>
                                <input type="text" value="<?php echo e(number_format(abs($profit_loss_per_unit), 2)); ?>"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-gray-100 border border-[#8C8C8C] <?php echo e($profit_loss_per_unit >= 0 ? 'text-green-600' : 'text-red-600'); ?>"
                                    readonly>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">
                                    <?php if($total_profit_loss >= 0): ?>
                                    سود کل
                                    <?php else: ?>
                                    ضرر کل
                                    <?php endif; ?>
                                </label>
                                <input type="text" value="<?php echo e(number_format(abs($total_profit_loss), 2)); ?>"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-gray-100 border border-[#8C8C8C] <?php echo e($total_profit_loss >= 0 ? 'text-green-600' : 'text-red-600'); ?>"
                                    readonly>
                            </div>
                        </div>

                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">کشور
                                    سازنده</label>
                                <input type="text" wire:model="country_of_origin"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400"
                                    placeholder="کشور سازنده">
                                <?php $__errorArgs = ['country_of_origin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="w-full">
                                <label class="block mb-2 pr-2 text-[16px] font-medium text-[#404040]">سال تولید</label>
                                <input type="number" wire:model="production_year"
                                    class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                                <?php $__errorArgs = ['production_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        
                        <div class="mt-10 flex gap-3 ">
                            <div class="w-full">
                                <div x-data="{ files: [] }"
                                    x-on:drop.prevent="files = $event.dataTransfer.files; $wire.upload('product_image', files[0])"
                                    x-on:dragover.prevent
                                    class="w-full h-[150px] p-3 rounded-[12px] border border-dashed focus:ring-2 bg-transparent  border-gray-400 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white flex flex-col justify-center items-center text-center cursor-pointer transition"
                                    x-on:click="$refs.fileInput.click()">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/upload.svg')); ?>" alt="آپلود"
                                        class="w-12 h-12 mb-2">
                                    <h1 class="font-vazir text-gray-600 dark:text-gray-300 text-[16px]">عکس محصول را
                                        بکشید اینجا یا انتخاب کنید.</h1>
                                    <input type="file" class="hidden" x-ref="fileInput"
                                        x-on:change="$wire.upload('product_image', $event.target.files[0])">
                                </div>
                                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- دکمه‌ها -->
                <div class="flex justify-center gap-4 pt-4">
                    <?php if(!$show_transfer_section): ?>
                    <button type="submit"
                        class="bg-gradient-to-br from-black to-blue-400 hover:bg-[#1D4ED8] text-white text-[16px] font-medium rounded-[12px] w-full px-8 py-4 transition">
                        <?php if($editingId): ?>
                        بروزرسانی محصول
                        <?php else: ?>
                        ثبت محصول جدید
                        <?php endif; ?>
                    </button>
                    <?php endif; ?>

                    <?php if($editingId): ?>
                    <button type="button" wire:click="resetForm"
                        class="bg-[#6B7280] hover:bg-[#4B5563] text-white text-[16px] font-medium rounded-[12px] w-full py-4 transition">
                        انصراف
                    </button>
                    <?php endif; ?>
                </div>

            </form>
        </div>



        
        <?php if($lowStockProducts->count() > 0): ?>
        <div class="w-[400px] md:w-[800px] lg:w-[750px] xl:w-[1300px] mt-6 mx-auto">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl ml-2"></i>
                    <h3 class="text-lg font-medium text-yellow-800">هشدار: موجودی کم</h3>
                </div>
                <p class="mt-2 text-sm text-yellow-700">
                    <?php echo e($lowStockProducts->count()); ?> محصول موجودی کمی دارند و نیاز به تکمیل دارند.
                </p>
                <div class="mt-2">
                    <?php $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mr-2 mb-1">
                        <?php echo e($product->product_name); ?> (<?php echo e($product->total_quantity); ?> <?php echo e($product->unit); ?>)
                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <div class=" w-[400px] md:w-[200px] lg:w-[750px] xl:w-[1300px]  bg-[#F5F5F5] rounded-[12px] mt-6 p-6 mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040;">

            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <label class="block mb-2 text-[16px] font-medium text-[#404040]">جستجو</label>
                    <input type="text" wire:model.live="search"
                        class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400"
                        placeholder="جستجو بر اساس نام، بارکد یا دسته بندی...">
                </div>

                <div class="w-full md:w-48">
                    <label class="block mb-2 text-[16px] font-medium text-[#404040]">دسته بندی</label>
                    <select wire:model.live="selectedCategory"
                        class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                        <option value="">همه دسته‌ها</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="w-full md:w-48">
                    <label class="block mb-2 text-[16px] font-medium text-[#404040]">وضعیت</label>
                    <select wire:model.live="selectedStatus"
                        class="w-full pr-4 h-[59px] rounded-[12px] bg-transparent border border-[#8C8C8C] focus:ring-2 focus:ring-blue-400">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="موجود">موجود</option>
                        <option value="ناموجود">ناموجود</option>
                        <option value="در حال تکمیل">در حال تکمیل</option>
                    </select>
                </div>

                
                <div class="flex items-end gap-2">
                    
                    <button type="button" wire:click="clearFilters"
                        class="bg-[#6B7280] hover:bg-[#4B5563] text-white h-[59px] px-6 rounded-[12px] transition flex items-center gap-2">
                        پاک کردن فیلترها
                    </button>
                </div>
            </div>

            
            <div class="overflow-x-auto">
                <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead
                        class="bg-gradient-to-br from-black to-blue-400 w-full text-white text-[14px] md:text-[16px] h-[50px] md:h-[67px]"
                        style="box-shadow: 0px 4px 4px 0px #00000040;">
                        <tr>
                            <th class="px-4 py-3 font-bold">#</th>
                            <th class="px-4 py-3 font-bold">بارکد</th>
                            <th class="px-4 py-3 font-bold">نام محصول</th>
                            <th class="px-4 py-3 font-bold">دسته بندی</th>
                            <th class="px-4 py-3 font-bold">واحد</th>
                            <th class="px-4 py-3 font-bold">موجودی</th>
                            <th class="px-4 py-3 font-bold">قیمت خرید</th>
                            <th class="px-4 py-3 font-bold">قیمت فروش</th>
                            <th class="px-4 py-3 font-bold">سود/ضرر</th>
                            <th class="px-4 py-3 font-bold">وضعیت</th>
                            <th class="px-4 py-3 font-bold">عملیات</th>
                        </tr>
                    </thead>

                    <tbody class="text-[14px] md:text-[15px] text-gray-800">
                        <?php if($products->count() > 0): ?>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-400 hover:bg-gray-50">
                            <td class="px-4 py-3 text-center"><?php echo e($index + 1); ?></td>
                            <td class="px-4 py-3 font-mono"><?php echo e($product->barcode); ?></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <?php if($product->image_path): ?>
                                    <img src="<?php echo e(Storage::url($product->image_path)); ?>"
                                        class="w-10 h-10 object-cover rounded">
                                    <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium"><?php echo e($product->product_name); ?></div>
                                        <?php if($product->supplier_name): ?>
                                        <div class="text-xs text-gray-500"><?php echo e($product->supplier_name); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?php if($product->category): ?>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?php echo e($product->category); ?></span>
                                <?php endif; ?>
                                <?php if($product->sub_category): ?>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e($product->sub_category); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center"><?php echo e($product->unit); ?></td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="font-bold <?php echo e($product->total_quantity <= $product->min_stock_level ? 'text-red-600' : 'text-green-600'); ?>">
                                        <?php echo e(number_format($product->total_quantity)); ?>

                                    </span>
                                    <span class="text-xs text-gray-500"><?php echo e($product->unit); ?></span>
                                    <?php if($product->total_quantity <= $product->min_stock_level): ?>
                                        <span class="text-red-500 text-xs mt-1">⚠️ موجودی کم</span>
                                        <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col">
                                    <span class="text-sm"><?php echo e(number_format($product->purchase_price_per_unit, 2)); ?></span>
                                    <span class="text-xs text-gray-500">فی <?php echo e($product->unit); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col">
                                    <span class="text-sm text-green-600"><?php echo e(number_format($product->retail_price)); ?></span>
                                    <span class="text-xs text-gray-500"><?php echo e(number_format($product->wholesale_price)); ?></span>
                                    <div class="text-xs text-gray-400 mt-1">
                                        پرچون / عمده
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs <?php echo e($product->profit_loss_per_unit >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e(number_format($product->profit_loss_per_unit, 2)); ?>

                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">فی <?php echo e($product->unit); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded-full text-xs 
                                        <?php echo e($product->status == 'موجود' ? 'bg-green-100 text-green-800' : ''); ?>

                                        <?php echo e($product->status == 'ناموجود' ? 'bg-red-100 text-red-800' : ''); ?>

                                        <?php echo e($product->status == 'در حال تکمیل' ? 'bg-yellow-100 text-yellow-800' : ''); ?>">
                                    <?php echo e($product->status); ?>

                                </span>
                                <br>
                                <button wire:click="toggleActive(<?php echo e($product->id); ?>)"
                                    class="text-xs mt-1 <?php echo e($product->is_active ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e($product->is_active ? 'فعال' : 'غیرفعال'); ?>

                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2">
                                    <div class="flex gap-2 justify-center">
                                        <button wire:click="editProduct(<?php echo e($product->id); ?>)"
                                            class="bg-blue-800 hover:bg-blue-800 text-white px-14 py-3 rounded text-md flex items-center gap-1">
                                            <i class="fas fa-edit text-xs"></i>
                                            ویرایش
                                        </button>
                                        <button wire:click="confirmDelete(<?php echo e($product->id); ?>)"
                                            class="bg-red-800 hover:bg-red-800 text-white px-14 py-3 rounded text-md flex items-center gap-1">
                                            <i class="fas fa-trash text-xs"></i>
                                            حــذف
                                        </button>
                                    </div>

                                    <!-- Stock Management -->
                                    <div class="mt-1 p-4 bg-gray-50 rounded border space-y-4">
                                        <div class="text-md font-medium mb-1 text-gray-700">مدیریت موجودی:</div>
                                        <div class="flex gap-1 mb-1">
                                            <select wire:model="stock_type" class="text-md border rounded p-1 w-40">
                                                <option value="ورود">ورود</option>
                                                <option value="خروج">خروج</option>
                                                <option value="فروش">فروش</option>
                                                <option value="خرید">خرید</option>
                                                <option value="تعدیل">تعدیل</option>
                                            </select>
                                            <input type="number" wire:model="stock_quantity" min="1"
                                                class="text-xs border rounded p-1 w-32" placeholder="تعداد">
                                        </div>

                                        <button wire:click="applyStockChange(<?php echo e($product->id); ?>)"
                                            class=" px-2 py-3 rounded bg-gradient-to-br from-black to-blue-400 w-full text-white text-[14px] w-full">
                                            اعمال تغییر موجودی
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-lg">هیچ محصولی یافت نشد</p>
                                    <p class="text-sm text-gray-500 mt-1">برای شروع، یک محصول جدید ثبت کنید</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- مودال تأیید حذف محصول -->
            <?php if($confirmDeleteId): ?>
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div
                    class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
                    <!-- دکمه بستن -->
                    <button wire:click="cancelDelete"
                        class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف محصول</h1>
                    <hr class="bg-[#E1DED3] mt-4 mx-4">
                    <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این محصول را حذف کنید؟</p>
                    <div class="flex justify-center gap-4">
                        <button wire:click="cancelDelete"
                            class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                            خیر
                        </button>
                        <button wire:click="deleteProductConfirmed"
                            class="px-12 py-3 bg-gradient-to-br from-indigo-400 to-indigo-500 text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                            بلی
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($products->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($products->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alpine.js for alerts -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #f9fafb;
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
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

        #selectCustomer {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            padding-left: 1rem;
        }

        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        /* در Firefox */
        input[list]::-moz-list-button {
            display: none !important;
        }

        /* در Edge جدید */
        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }
    </style>
    <script>
        document.getElementById('categorySelect').addEventListener('change', function() {
    const category = this.value;
    const subCategorySelect = document.getElementById('subCategorySelect');
    
    // پاک کردن گزینه‌های قبلی
    subCategorySelect.innerHTML = '<option value="">انتخاب زیر دسته</option>';
    
    if (!category) return;
    
    // اضافه کردن گزینه‌های مربوط به دسته‌بندی انتخاب شده
    const subcategories = getSubcategories(category);
    subcategories.forEach(sub => {
        const option = document.createElement('option');
        option.value = sub;
        option.textContent = sub;
        subCategorySelect.appendChild(option);
    });
});

function getSubcategories(category) {
    const subcategories = {
        'لوازم خانگی': ['یخچال و فریزر', 'ماشین لباسشویی', 'ماشین ظرفشویی', 'اجاق گاز و هود', 'تهویه مطبوع', 'آبگرمکن و پکیج'],
        'الکترونیک و دیجیتال': ['موبایل و تبلت', 'لپ‌تاپ و کامپیوتر', 'دوربین و عکاسی', 'هدفون و هندزفری', 'لوازم جانبی الکترونیک', 'گجت‌های هوشمند'],
        'پوشاک و مد': ['لباس مردانه', 'لباس زنانه', 'لباس بچه‌گانه', 'کفش و کتانی', 'کیف و لوازم جانبی', 'لباس ورزشی'],
        'خانه و آشپزخانه': ['مبلمان و دکوراسیون', 'لوازم آشپزخانه', 'سرو و پذیرایی', 'خواب و حمام', 'روشنایی و لوستر', 'فرش و گلیم'],
        'سرگرمی و hobbies': ['کتاب و مجله', 'اسباب‌بازی', 'سازهای موسیقی', 'لوازم هنری', 'ورزش و سفر', 'کلکسیون'],
        'آرایشی و بهداشتی': ['مراقبت پوست', 'آرایش صورت', 'عطر و ادکلن', 'مراقبت مو', 'بهداشت شخصی', 'لوازم آرایشی'],
        'ابزار و صنعتی': ['ابزار دستی', 'ابزار برقی', 'یراق و اتصالات', 'تجهیزات ایمنی', 'لوازم باغبانی', 'مواد مصرفی'],
        'سوپرمارکت': ['مواد غذایی', 'نوشیدنی‌ها', 'محصولات بهداشتی', 'شوینده و پاک‌کننده', 'لوازم تحریر', 'تنقلات'],
        'خودرو و موتورسیکلت': ['لوازم یدکی', 'لوازم جانبی خودرو', 'مراقبت و نگهداری', 'صوتی و تصویری', 'لوازم موتورسیکلت', 'تجهیزات کارواش'],
        'کودک و نوزاد': ['پوشاک نوزاد', 'شیردهی و غذاخوری', 'اسباب‌بازی آموزشی', 'وسایل خواب نوزاد', 'ایمنی و مراقبت', 'لوازم سیسمونی']
    };
    
    return subcategories[category] || [];
}
    </script>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/warehouse.blade.php ENDPATH**/ ?>