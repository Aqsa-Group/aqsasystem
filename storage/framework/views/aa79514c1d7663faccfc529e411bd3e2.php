<div class="container mx-auto px-4">
    <!-- Flash Messages -->
    <?php if(session()->has('message')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2563EB] azir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('message')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session()->has('error')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-red-700 vazir">
        <div class="h-[80px] w-full flex justify-start items-center px-4">
            <h2 class="text-white vazir text-[18px]">
                <?php echo e(session('error')); ?>

            </h2>
        </div>
    </div>
    <?php endif; ?>


    <div class="space-y-4 mb-6">
        <h1 class="text-[24px] font-medium vazir">درج ارز برای بیلانس</h1>
        <h1 class="text-[#8C8C8C]">اضافه ویرایش ارزها برای بیلانس گیری</h1>
    </div>
    <hr class="my-6 border-t border-[#D9D9D9] w-full">

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- فرم سمت چپ -->
        <div class="w-full lg:w-[524px] bg-[#F5F5F5] p-[12px] rounded-[12px] h-fit"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex gap-2 border border-[#8C8C8C] rounded-[12px] p-6 mb-4">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange-rate.svg')); ?>" alt="">
                <p><?php echo e($isEditing ? 'ویرایش قیمت ارز' : 'ثبت قیمت ارز'); ?></p>
            </div>

            <form wire:submit.prevent="submit">
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1 relative">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">واحد ارز اصلی</label>
                        <div class="relative">
                            <select wire:model.live="source_currency"
                                class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer appearance-none pr-3 pl-10">
                                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($currency['name_fa']); ?>"><?php echo e($currency['name_fa']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓" class="w-4 h-4">
                            </div>
                        </div>
                        <?php $__errorArgs = ['source_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[16px] font-medium text-black mb-1 vazir">تاریخ</label>
                        <input type="text" wire:model="date" placeholder="YYYY/MM/DD"
                            class="w-full h-[60px] p-3 rounded-[12px] border focus:ring-2 bg-transparent border-[#8C8C8C] focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-pointer" />
                        <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <table class="w-full text-center border-collapse mb-4">
                    <thead class>
                        <tr class="bg-[#2B65E5] text-white">
                            <th class="px-4 py-3">واحد ارز</th>
                            <th class="px-4 py-3">قیمت خرید</th>
                            <th class="px-4 py-3">قیمت فروش</th>
                        </tr>
                    </thead>
                 <tbody>
    <?php
    $allCurrencies = ['افغانی', 'دالر', 'تومان', 'یورو', 'کلدار', 'درهم', 'لیره', 'یوان چین'];
    
    // دیباگ: بررسی مقادیر
    // \Log::info("Source Currency: " . $this->source_currency);
    
    $formCurrencies = array_filter($allCurrencies, function($currency) {
        // حذف فاصله و کاراکترهای اضافی
        $currentCurrency = trim($currency);
        $selectedCurrency = trim($this->source_currency);
        
        return $currentCurrency !== $selectedCurrency;
    });
    
    // دیباگ: بررسی نتیجه فیلتر
    // \Log::info("Form Currencies: " . implode(', ', $formCurrencies));
    ?>

    <?php $__currentLoopData = $formCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr class="border-b">
        <td class="px-4 py-2 font-bold text-gray-700">
            <?php echo e($currency); ?>

        </td>
        <td class="px-4 py-2">
            <input type="text" wire:model="formData.<?php echo e($currency); ?>.buy"
                class="w-full outline-none bg-transparent rounded px-2 py-1 text-right"
                placeholder="0.00">
            <?php $__errorArgs = ['formData.'.$currency.'.buy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </td>
        <td class="px-4 py-2">
            <input type="text" wire:model="formData.<?php echo e($currency); ?>.sell"
                class="w-full outline-none bg-transparent rounded px-2 py-1 text-right"
                placeholder="0.00">
            <?php $__errorArgs = ['formData.'.$currency.'.sell'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-red-500 text-xs"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
                </table>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-6 mt-6 justify-center items-center text-center">
                    <button type="submit"
                        class="bg-[#2563EB] hover:bg-[#1E4FD6] transition-all duration-200 text-[16px] vazir font-semibold rounded-[10px] px-8 sm:px-20 py-3 text-white shadow-md w-full sm:w-auto">
                        <?php echo e($isEditing ? 'بروزرسانی' : 'ثبت'); ?>

                    </button>

                    <button type="button" wire:click="cancel"
                        class="bg-[#DD2424] hover:bg-[#B81E1E] transition-all duration-200 text-[16px] vazir font-semibold rounded-[10px] px-8 sm:px-20 py-3 text-white shadow-md w-full sm:w-auto">
                        انصراف
                    </button>

                </div>
            </form>
        </div>

        <!-- جدول سمت راست -->
        <div class="flex-1 flex flex-col bg-[#F5F5F5] p-1 md:p-4 lg:p-6 rounded-[12px] w-[420px] overflow-x-auto md:w-full lg:w-full mb-4 mx-auto"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <div class="flex gap-2 border border-[#8C8C8C] rounded-[12px] p-6 mb-4">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange-rate.svg')); ?>" alt="">
                <p>جدول قیمت ارز</p>
            </div>

            <!-- بدنه جدول با اسکرول -->
            <div class="flex-1 overflow-x-auto overflow-y-auto">
                <table
                    class="w-full min-w-max text-sm md:text-base text-center text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="bg-[#2B65E5] text-white">
                        <tr>
                            <?php
                            $allCurrencies = ['افغانی', 'دالر', 'تومان', 'یورو', 'کلدار', 'لیره', 'درهم', 'یوان'];
                            $tableCurrencies = array_filter($allCurrencies, function($currency) {
                                return $this->source_currency !== $currency;
                            });
                            ?>

                            <?php $__currentLoopData = $tableCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th colspan="2" class="px-4 py-3 border-l border-white"><?php echo e($currency); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th rowspan="2" class="px-4 py-3">تاریخ</th>
                            <th rowspan="2" class="px-4 py-3">عملیات</th>
                        </tr>
                        <tr>
                            <?php $__currentLoopData = $tableCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="px-2 py-2">خرید</th>
                            <th class="px-2 py-2">فروش</th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="bg-transparent dark:bg-gray-800 dark:hover:bg-gray-700">
                            <?php if($this->source_currency !== 'افغانی'): ?>
                            <td class="px-3 py-2"><?php echo e($record->afn_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->afn_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'دالر'): ?>
                            <td class="px-3 py-2"><?php echo e($record->usd_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->usd_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'تومان'): ?>
                            <td class="px-3 py-2"><?php echo e($record->irr_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->irr_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'یورو'): ?>
                            <td class="px-3 py-2"><?php echo e($record->eur_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->eur_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'کلدار'): ?>
                            <td class="px-3 py-2"><?php echo e($record->pkr_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->pkr_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'لیره'): ?>
                            <td class="px-3 py-2"><?php echo e($record->try_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->try_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'درهم'): ?>
                            <td class="px-3 py-2"><?php echo e($record->aed_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->aed_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <?php if($this->source_currency !== 'یوان'): ?>
                            <td class="px-3 py-2"><?php echo e($record->cny_buy ?? '-'); ?></td>
                            <td class="px-3 py-2"><?php echo e($record->cny_sell ?? '-'); ?></td>
                            <?php endif; ?>

                            <!-- تاریخ -->
                            <td class="px-3 py-2 font-medium">
                                <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($record->created_at)->format('Y/m/d')); ?>

                            </td>
                            
                            <!-- عملیات -->
                            <td class="py-4">
                                <div class="flex justify-center gap-2">
                                    <!-- دکمه ویرایش -->
                                    <button wire:click="edit(<?php echo e($record->id); ?>)"
                                        class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-blue-100"
                                        title="ویرایش">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>" class="w-5 h-5"
                                            alt="Edit">
                                    </button>

                                    <!-- دکمه حذف -->
                                    <button wire:click="confirmDelete(<?php echo e($record->id); ?>)"
                                        class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                        title="حذف">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>" class="w-6 h-6"
                                            alt="Delete">
                                    </button>

                                    <!-- دکمه پرینت -->
                                    <button wire:click="print(<?php echo e($record->id); ?>)"
                                        class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                        title="پرینت">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>" class="w-7 h-7"
                                            alt="Print">
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <?php
                                $colspan = (count($tableCurrencies) * 2) + 2;
                            ?>
                            <td colspan="<?php echo e($colspan); ?>" class="px-4 py-8 text-center text-gray-500">
                                هیچ داده‌ای یافت نشد
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- مودال تأیید حذف (خارج از جدول) -->
<?php if($confirmDeleteId): ?>
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
<?php endif; ?>

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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
<style>
    /* ایجاد فاصله بیشتر بین ستون‌های خرید و فروش */
    table th:nth-child(2),
    table th:nth-child(4),
    table th:nth-child(6),
    table th:nth-child(8),
    table th:nth-child(10),
    table th:nth-child(12),
    table th:nth-child(14),
    table th:nth-child(16) {
        border-right: 2px solid #e5e7eb;
    }

    table td:nth-child(2),
    table td:nth-child(4),
    table td:nth-child(6),
    table td:nth-child(8),
    table td:nth-child(10),
    table td:nth-child(12),
    table td:nth-child(14),
    table td:nth-child(16) {
        border-right: 2px solid #e5e7eb;
    }
</style>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/exchange-rate.blade.php ENDPATH**/ ?>