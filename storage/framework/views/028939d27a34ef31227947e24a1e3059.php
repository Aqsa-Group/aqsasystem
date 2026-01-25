<div>
    <div class="pl-10 pr-10 mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black">خرید و فروش ارز</h1>
            <h1 class="text-[#8C8C8C] text-[18px]">صفحه درج خرید و فروش ارز</h1>
        </div>
        <hr class="text-[#D9D9D9] mt-6 pl-4 pr-4">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-gradient-to-br from-indigo-400 to-indigo-500 vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
<div class="flex flex-col lg:flex-row gap-6 lg:gap-10 mt-3 justify-center items-center lg:items-start w-full px-2">
    
    <!-- جدول خرید -->
    <div class="w-full lg:w-auto max-h-[680px] overflow-y-auto overflow-x-auto">
        <h1 class="text-[20px] md:text-[24px] mb-3 text-center lg:text-start">مجموعه خرید ارز</h1>
        <table class="min-w-[500px] text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="bg-gradient-to-br from-black to-blue-400 w-full dark:bg-gray-700 text-white 
                       text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                <tr>
                    <th class="px-6 py-4 font-bold w-16">#</th>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['usd', 'afn', 'irr', 'pkr']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-6 py-4 font-bold w-48 text-center"><?php echo e($this->getCurrencyName($currency)); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">1</td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['usd', 'afn', 'irr','pkr']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center">
                            <?php echo e(number_format($totalBuy[$currency] ?? 0 ,2)); ?>

                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- جدول فروش -->
    <div class="w-full lg:w-auto max-h-[680px] overflow-y-auto overflow-x-auto mt-6 lg:mt-0">
        <h1 class="text-[20px] md:text-[24px] mb-3 text-center lg:text-start">مجموعه فروش ارز</h1>
        <table class="min-w-[500px] text-sm md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="bg-gradient-to-br from-black to-blue-400 w-full dark:bg-gray-700 text-white 
                       text-[14px] md:text-[16px] lg:text-[18px] vazir h-[50px] md:h-[67px] sticky top-0"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                <tr>
                    <th class="px-6 py-4 font-bold w-16">#</th>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['usd', 'afn', 'irr','pkr']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-6 py-4 font-bold w-48 text-center"><?php echo e($this->getCurrencyName($currency)); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <tr class="text-black border-b border-[#D9D9D9] bg-transparent">
                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">1</td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['usd', 'afn', 'irr','pkr']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center">
                            <?php echo e(number_format($totalSell[$currency] ?? 0 ,2)); ?>

                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>


        <!-- مانده خالص -->
        <div class="grid grid-cols-4 md:grid-cols-4 justify-center items-center text-center mx-auto pr-14 mt-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [ 'afn', 'usd' , 'irr' ,'pkr']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $balance = $netAmounts[$currency] ?? 0;
            ?>
            <div class="flex gap-2">
                <span class="<?php echo e($balance < 0 ? 'text-red-500' : ''); ?>">
                    <?php echo e(number_format($balance)); ?>

                </span>
                <span class="<?php echo e($balance < 0 ? 'text-red-500' : ''); ?>">
                    <?php echo e($this->getCurrencyName($currency)); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 mt-7">
            <!-- فرم تراکنش -->
            <div class="flex flex-col bg-[#F5F5F5] w-full    lg:w-[474px] p-[12px] h-fit rounded-[12px] space-y-2"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-row justify-between pt-[20px] pb-[20px] border border-[#8C8C8C] rounded-[12px] items-center">
                    <p class="flex items-center text-center pr-3">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/pencil.svg')); ?>" alt="" class="h-5 w-5">
                        <?php echo e($isEditing ? 'فورم ویرایش معاملات نقدی' : 'فورم ثبت معاملات نقدی'); ?>

                    </p>


                    <div class="flex items-center gap-2 pl-2 ">
                     
                        <button wire:click="toggleTransactionType" type="button" class="rounded-[8px] p-[10px] text-white vazir text-[14px]
                                transition-colors duration-500 ease-in-out py-4
                                <?php echo e($transactionType === 'خرید' ? 'bg-gradient-to-br from-black to-blue-500  text-white p-6 rounded-xl shadow-lg transition-all duration-300' : 'bg-gradient-to-br from-black to-red-500  text-white p-6 rounded-xl shadow-lg transition-all duration-300'); ?>">
                            <?php echo e($transactionType === 'خرید' ? 'خرید (واحد ارز دربافت صندوق)' : 'فروش (واحد ارز برداشت
                            صندوق)'); ?>

                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="submitTransaction" class="space-y-3">
                    <!-- مقدار و نوع ارز -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="lg:w-[160px]">
                            <label class="block text-[15px] font-medium text-black mb-1 vazir">واحد ارز</label>
                            <div class="relative">
                                <select wire:model="currency"
                                    class="w-full h-[55px] p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" class="w-4 h-4"
                                        alt="">
                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9]/g, '')" />
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($amountInWords): ?>
                            <p class="text-sm text-blue-600 mt-2 vazir"><?php echo e($amountInWords); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    </div>

                    <!-- واحد تبدیل ارز و مبلغ معادل -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="lg:w-[160px]">
                            <label class="block text-[15px] font-medium text-black mb-1 vazir">واحد تبدیل ارز</label>
                            <div class="relative">
                                <select wire:model="to_currency"
                                    class="w-full h-[55px] p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" class="w-4 h-4"
                                        alt="">
                                </div>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">مبلغ معادل</label>
                            <div class="relative w-full">
                                <input type="text" wire:model="eq_amount" placeholder="0" readonly
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 bg-gray-100 cursor-not-allowed dark:text-white" />
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($eqAmountInWords): ?>
                            <p class="text-sm text-purple-600 mt-2 vazir"><?php echo e($eqAmountInWords); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['eq_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    </div>


                    <!-- نرخ و تاریخ -->
                    <div class="flex flex-col lg:flex-row gap-3">
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نرخ ارز</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="exchange_rate" placeholder="0"
                                    class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d)).replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" />
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exchangeRateInWords): ?>
                            <p class="text-sm text-green-600 mt-2 vazir"><?php echo e($exchangeRateInWords); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['exchange_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex-1">
                            <label class="block text-[15px] font-medium text-black mb-1 vazir">تاریخ</label>
                            <input type="text" id="datePicker" wire:model="date" wire:ignore placeholder="YYYY/MM/DD"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        </div>
                    </div>



                    <!-- شرح -->
                    <div>
                        <label class="block text-[15px] font-medium text-black mb-1 vazir">شرح تراکنش</label>
                        <textarea rows="3" wire:model="description" placeholder="شرح تراکنش..."
                            class="w-full p-3 rounded-[10px] border bg-transparent border-[#8C8C8C] focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>


                    <!-- آپلود فایل -->
                    <div>
                        <label class="block text-[15px] font-medium text-black mb-1 vazir">فایل تراکنش</label>
                        <div x-data="{ isDragging: false }"
                            @drop.prevent="isDragging = false; $wire.upload('transaction_file', $event.dataTransfer.files[0])"
                            @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                            :class="isDragging ? 'border-[#2563EB] bg-blue-50' : 'border-[#112080] bg-white'"
                            class="w-full h-[120px] p-3 rounded-[10px] border border-dashed flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 transition">

                            <!-- اضافه کردن label برای input فایل -->
                            <label for="fileInput"
                                class="w-full h-full flex flex-col justify-center items-center cursor-pointer">
                                <template x-if="!$wire.transaction_file">
                                    <div class="flex flex-col items-center">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/upload.svg')); ?>"
                                            class="w-10 h-10 mb-1" alt="">
                                        <h1 class="font-vazir text-gray-600 mt-2 text-[15px] vazir">فایل را اینجا وارد
                                            کنید یا بکشید</h1>

                                    </div>
                                </template>

                                <template x-if="$wire.transaction_file">
                                    <div class="flex flex-col items-center">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/file-uploaded.svg')); ?>"
                                            class="w-10 h-10 mb-1" alt="">
                                        <h1 class="font-vazir text-green-600 text-[15px]">فایل انتخاب شده</h1>
                                        <p class="text-gray-600 text-sm mt-1" x-text="$wire.transaction_file.name"></p>
                                        <p class="text-blue-500 text-xs mt-1">برای تغییر فایل کلیک کنید</p>
                                    </div>
                                </template>
                            </label>

                            <input type="file" wire:model="transaction_file" class="hidden" id="fileInput">

                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['transaction_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-sm mt-1"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <!-- دکمه‌های نهایی -->
                    <div class="flex gap-3 justify-center items-center text-center flex-wrap">
                        <button type="submit"
                            class="bg-[#61B138] text-[15px] vazir font-semibold rounded-[8px] px-10 py-3 text-white hover:bg-green-700 transition">
                            <?php echo e($isEditing ? 'بروزرسانی' : 'ثبت'); ?>

                        </button>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$isEditing): ?>
                        <button type="button" wire:click="submitAndPrint"
                            class="bg-gradient-to-br from-black to-blue-400 text-[15px] vazir font-semibold rounded-[8px] px-10 py-3 text-white hover:bg-blue-700 transition">
                            ثبت و چاپ
                        </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <button type="button" wire:click="cancel"
                            class="bg-[#DD2424] text-[15px] vazir font-semibold rounded-[8px] px-10 py-3 text-white hover:bg-red-700 transition">
                            <?php echo e($isEditing ? 'لغو ویرایش' : 'انصراف'); ?>

                        </button>
                    </div>
                </form>
            </div>

            <!-- جدول تراکنش‌ها -->
            <div class="flex-1 flex flex-col bg-[#F5F5F5] p-4 rounded-[12px]  w-[440px] mb-5 md:w-[430px] lg:w-[200px]"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex md:flex-row justify-between items-center border border-[#8C8C8C] p-3 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-[16px] vazir">ترانزکشن های ثبت شده</h1>
                    <div class="relative w-full md:w-[260px]">
                        <input type="text" wire:model.live="search"
                            class="border border-[#8C8C8C] w-full h-[46px] bg-transparent rounded-[10px] p-2 pr-10 text-sm"
                            placeholder="جستجو ...">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5">
                    </div>
                </div>

                <div class="overflow-x-auto w-full">
                    <div class="max-h-[650px] overflow-y-auto min-w-[800px]">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="bg-gradient-to-br from-black to-blue-400 text-white text-[18px] vazir h-[50px] sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 font-bold">#</th>
                                    <th class="px-4 py-3 font-bold">معامله</th>
                                    <th class="px-4 py-3 font-bold">مبلغ</th>
                                    <th class="px-4 py-3 font-bold">ارز</th>
                                    <th class="px-4 py-3 font-bold">نرخ</th>
                                    <th class="px-4 py-3 font-bold">مبلغ معادل</th>
                                    <th class="px-4 py-3 font-bold">ارز معادل</th>
                                    <th class="px-4 py-3 font-bold text-center">شرح معامله</th>
                                    <th class="px-4 py-3 font-bold text-center">تاریخ</th>
                                    <th class="px-4 py-3 font-bold text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="text-black border-b border-[#D9D9D9] bg-transparent text-center">
                                    <td class="px-2 py-3 vazir text-[18px] font-medium"><?php echo e($loop->iteration); ?></td>
                                    <td
                                        class="px-2 py-3 vazir text-[18px] font-medium <?php echo e($transaction->type === 'خرید' ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($transaction->type); ?>

                                    </td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium"><?php echo e(number_format($transaction->amount ,2)); ?></td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium"><?php echo e($this->getCurrencyName($transaction->from_currency)); ?></td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium"><?php echo e(number_format($transaction->exchange_rate, 2)); ?></td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium"><?php echo e(number_format($transaction->eq_amount ,2)); ?></td>
                                    <td class="px-2 py-3 vazir text-[18px] font-medium"><?php echo e($this->getCurrencyName($transaction->to_currency)); ?></td>
                                    <td class="px-6 py-3 vazir text-[18px] font-medium"><?php echo e($transaction->description); ?>

                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium text-[16px]">
                                                <?php echo e(explode(' ', $transaction->date)[0]); ?>

                                            </div>
                                            <div class="text-gray-500 text-[16px] mt-1">
                                                <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('h:i A')); ?>

                                            </div>
                                        </div>
                                    </td>

                                    <!-- در بخش عملیات جدول -->
                                    <td class="py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="editTransaction(<?php echo e($transaction->id); ?>)"
                                                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                    class="w-7 h-7" alt="Edit">
                                            </button>
                                            <button wire:click="deleteTransaction(<?php echo e($transaction->id); ?>)"
                                                class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                    class="w-7 h-7" alt="Delete">
                                            </button>
                                            <button wire:click="printTransaction(<?php echo e($transaction->id); ?>)"
                                                class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-200 transition">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                    class="w-10 h-10" alt="Print">
                                            </button>

                                            <!-- مودال تایید حذف -->
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmDeleteId): ?>
                                            <div
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-20 z-50">
                                                <div
                                                    class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] pb-[21px] rounded-[12px] shadow-xl w-[653px] h-[252.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3]">

                                                    <!-- دکمه بستن -->
                                                    <div class="flex justify-start">
                                                        <button wire:click="cancelDelete" class="h-4 w-4">
                                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>"
                                                                alt="بستن">
                                                        </button>
                                                    </div>

                                                    <!-- تیتر -->
                                                    <h1
                                                        class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">
                                                        حذف معــاملـــه
                                                    </h1>

                                                    <hr class="bg-[#E1DED3] mt-4">

                                                    <!-- متن سوال -->
                                                    <p class="mb-6 text-xl shabnam mt-5">
                                                        آیا مطمئن هستید می خواهید این معاملــه را حذف کنید؟
                                                    </p>

                                                    <!-- دکمه‌های تایید -->
                                                    <div class="flex justify-center gap-4">
                                                        <button wire:click="cancelDelete"
                                                            class="px-20 text-white text-xl shabnam-fd py-4 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                                                            <?php echo e(__('messages.no') ?? 'خیر'); ?>

                                                        </button>
                                                        <button wire:click="deleteConfirmed"
                                                            class="px-20 py-4 bg-gradient-to-br from-indigo-400 to-indigo-500 text-xl shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                                                            <?php echo e(__('messages.yes') ?? 'بلی'); ?>

                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/tools-panel/buy-sell-currency.blade.php ENDPATH**/ ?>