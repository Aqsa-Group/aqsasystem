<div class="container mx-auto px-4">
    <!-- Flash Messages -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2563EB] azir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-700 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="space-y-4 mb-6">
        <h1 class="text-[24px] font-medium vazir dark:text-white">ثبت نرخ ارز برای بیلانس گیری ، مفاد و ضرر</h1>
        <h1 class="text-[#8C8C8C] dark:text-white">ثبت نرخ ارز برای بیلانس گیری ، مفاد و ضرر حسابات مشتریان</h1>
    </div>

    <!-- فرم کامل عرض -->
    <div class="w-full dark:bg-black dark:border-white dark:border  bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC] dark:text-white  p-[12px] rounded-[12px] h-fit mb-6"
        >
        <div class="flex gap-2  rounded-[12px] p-6 mb-4 inter text-xl">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange-rate.svg')); ?>" alt="">
            <p><?php echo e($isEditing ? 'ویرایش قیمت ارز' : 'ثبت قیمت ارز'); ?></p>
        </div>

        <form wire:submit.prevent="submit">
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1 relative">
                    <label class="block text-[16px] font-medium text-black mb-1 vazir"> ارز اصلی</label>
                    <div class="relative">
                        <select wire:model.live="source_currency"
                            class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2  focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer appearance-none pr-3 pl-10">
                            <option value="usd">دالر</option>
                        </select>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓" class="w-4 h-4">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['source_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="flex-1">
                    <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                    <input type="text" wire:model="date" placeholder="YYYY/MM/DD"
                        class="w-full h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- جدول با چهار نوع نرخ برای هر ارز - ساده شده -->
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse mb-4 min-w-[1000px]">
                    <thead>
                        <tr class="bg-white text-black">
                            <th class="px-4 py-3 border-l border-white">واحد ارز</th>
                            <th class="px-4 py-3 border-l border-white">خرید نقدی</th>
                            <th class="px-4 py-3 border-l border-white">خرید بانکی</th>
                            <th class="px-4 py-3 border-l border-white">فروش نقدی</th>
                            <th class="px-4 py-3 border-l border-white">فروش بانکی</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $allCurrencies = ['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'];
                        $formCurrencies = array_filter($allCurrencies, function($currency) {
                        return $currency !== $this->source_currency;
                        });
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $formCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $currencyName = $this->getCurrencyName($currencyCode);
                        ?>
                        <tr class="border-b">
                            <td class="px-4 py-3 font-bold text-gray-700 dark:bg-black bg-[#EFF6F9] dark:border-white dark:border dark:text-white ">
                                <?php echo e($currencyName); ?>

                            </td>

                            <!-- خرید نقدی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.<?php echo e($currencyCode); ?>.buy_cash"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['formData.'.$currencyCode.'.buy_cash'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs block mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            <!-- خرید بانکی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.<?php echo e($currencyCode); ?>.buy_bank"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['formData.'.$currencyCode.'.buy_bank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs block mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            <!-- فروش نقدی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.<?php echo e($currencyCode); ?>.sell_cash"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['formData.'.$currencyCode.'.sell_cash'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs block mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            <!-- فروش بانکی -->
                            <td class="px-2 py-2">
                                <input type="text" wire:model="formData.<?php echo e($currencyCode); ?>.sell_bank"
                                    class="w-full h-[50px] outline-none bg-transparent rounded px-3 text-center border border-gray-300"
                                    placeholder="0.00">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['formData.'.$currencyCode.'.sell_bank'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs block mt-1"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-6 mt-6 justify-center items-center text-center">
                <button type="submit"
                    class="bg-[#184D6C]  transition-all duration-200 text-[16px] vazir font-semibold rounded-[10px] px-8 sm:px-20 py-3 text-white shadow-md w-full sm:w-auto">
                    <?php echo e($isEditing ? 'بروزرسانی' : 'ثبت'); ?>

                </button>

                <button type="button" wire:click="cancel"
                    class="bg-[#184D6C]  transition-all duration-200 text-[16px] vazir font-semibold rounded-[10px] px-8 sm:px-20 py-3 text-white shadow-md w-full sm:w-auto">
                    انصراف
                </button>
            </div>
        </form>
    </div>

    <!-- جدول زیر فرم -->
    <div class="w-full flex flex-col dark:bg-black dark:border dark:border-white  bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC] p-1 md:p-4 lg:p-6 rounded-[12px] overflow-x-auto mx-auto"
       >
        <div class="flex gap-2 border border-[#8C8C8C] rounded-[12px] p-6 mb-4">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange-rate.svg')); ?>" alt="">
            <p>جدول قیمت ارز</p>
        </div>

        <!-- بدنه جدول با اسکرول -->
        <div class="flex-1 overflow-x-auto">
            <table
                class="w-full min-w-[1000px] text-sm md:text-base text-center text-gray-500 dark:text-gray-400 border-collapse">
                <thead class="bg-white text-black">
                    <tr>
                        <th class="px-4 py-3 border-l border-white">ارز مبدأ</th>
                        <?php
                        $allCurrencies = ['usd', 'afn', 'irr', 'eur', 'pkr', 'aed', 'try', 'cny'];
                        $tableCurrencies = $allCurrencies; // نمایش همه ارزها
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tableCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $currencyName = $this->getCurrencyName($currencyCode);
                        ?>
                        <th class="px-4 py-3 border-l border-white"><?php echo e($currencyName); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <th class="px-4 py-3">تاریخ</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">                        <!-- ارز مبدأ -->
                        <td class="px-3 py-2 font-medium border-l dark:bg-black dark:border dark:border-white bg-blue-50">
                            <span class="font-bold text-black dark:text-white"><?php echo e($this->getCurrencyName($record->source_currency)); ?></span>
                            <div class="text-xs dark:text-white text-gray-500 mt-1">نرخ‌ها نسبت به این ارز</div>
                        </td>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tableCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="px-3 py-3 border-l">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currencyCode !== $record->source_currency): ?>
                            <div class="space-y-2">
                                <!-- خرید نقدی -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-white">خرید نقدی:</span>
                                    <span class="font-medium">
                                        <?php echo e($record->{$currencyCode . '_buy_cash'} !== null
                                        ? number_format($record->{$currencyCode . '_buy_cash'}, 3)
                                        : '-'); ?>

                                    </span>
                                </div>

                                <!-- خرید بانکی -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-white">خرید بانکی:</span>
                                    <span class="font-medium">
                                        <?php echo e($record->{$currencyCode . '_buy_bank'} !== null
                                        ? number_format($record->{$currencyCode . '_buy_bank'}, 3)
                                        : '-'); ?>

                                    </span>
                                </div>

                                <!-- فروش نقدی -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-white">فروش نقدی:</span>
                                    <span class="font-medium">
                                        <?php echo e($record->{$currencyCode . '_sell_cash'} !== null
                                        ? number_format($record->{$currencyCode . '_sell_cash'}, 3)
                                        : '-'); ?>

                                    </span>
                                </div>

                                <!-- فروش بانکی -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-white">فروش بانکی:</span>
                                    <span class="font-medium">
                                        <?php echo e($record->{$currencyCode . '_sell_bank'} !== null
                                        ? number_format($record->{$currencyCode . '_sell_bank'}, 3)
                                        : '-'); ?>

                                    </span>
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- اگر این ارز همان ارز مبدأ باشد، سلول خالی می‌ماند -->
                            <div class="text-center text-gray-300 dark:text-white py-4">
                                <span class="text-sm">-</span>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- تاریخ -->
                        <td class="px-3 py-2 font-medium">
                            <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($record->created_at)->format('Y/m/d')); ?>

                        </td>

                        <!-- عملیات -->
                        <td class="py-4">
                            <div class="flex justify-center gap-2">
                                

                                
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <?php
                        $colspan = count($tableCurrencies) + 3; // +3 برای ارز مبدأ، تاریخ و عملیات
                        ?>
                        <td colspan="<?php echo e($colspan); ?>" class="px-4 py-8 text-center text-gray-500">
                            هیچ داده‌ای یافت نشد
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- مودال تأیید حذف -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmDeleteId): ?>
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-10 z-50">
    <div
        class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px] rounded-[12px] shadow-xl w-[653px] h-[239.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
        <button wire:click="$set('confirmDeleteId', null)"
            class="absolute top-4 right-4 h-6 w-6 flex items-center justify-center">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن">
        </button>
        <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-4">
            حذف نرخ ارز
        </h1>
        <hr class="bg-[#E1DED3] mt-8">
        <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این نرخ ارز را حذف کنید؟</p>
        <div class="flex justify-center gap-4">
            <button wire:click="$set('confirmDeleteId', null)"
                class="px-20 text-white text-xl shabnam-fd py-3 bg-[#DD2424] rounded-xl transition">
                <?php echo e(__('messages.no') ?? 'خیر'); ?>

            </button>
            <button wire:click="deleteConfirmed"
                class="px-20 py-3 bg-[#2563EB] text-xl shabnam-fd text-white rounded-xl transition flex items-center gap-2">
                <?php echo e(__('messages.yes') ?? 'بله'); ?>

            </button>
        </div>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Print functionality
    Livewire.on('print-exchange-rates', () => {
        window.print();
    });

    Livewire.on('print-single-exchange-rate', (exchangeRate) => {
        console.log('Printing:', exchangeRate);
    });

    document.addEventListener('livewire:load', function() {
        // Initialize any date pickers here if needed
    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/profit-rates.blade.php ENDPATH**/ ?>