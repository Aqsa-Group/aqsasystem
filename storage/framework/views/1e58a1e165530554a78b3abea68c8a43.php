<div class="px-5">
    <!-- دکمه‌های دسته‌بندی -->
    <div class="grid grid-cols-1 w-[1200px] md:grid-cols-4 lg:grid-cols-4 gap-3  mb-5">
        <button wire:click="selectCategory('customers')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مالی
            مشتریان</button>
        <button wire:click="selectCategory('accounts')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارش حسابات و
            صندوق</button>
        <button wire:click="selectCategory('transactions')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات تراکنش های
            معاملات</button>
        <button wire:click="selectCategory('management')"
            class="bg-[#2563EB] text-white text-[16px] w-full py-3 font-bold rounded-[12px]">گزارشات مدیریتی و
            تحلیلی</button>
    </div>


    <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <!-- select زیرشاخه -->
        <!--[if BLOCK]><![endif]--><?php if($selectedCategory): ?>
        <div class="mb-5 relative w-full ">
            <select wire:model.live="selectedSubCategory"
                class="border bg-transparent border-[#8C8C8C] rounded-[12px] px-4 py-2 pt-[13px] pr-[9px] pl-[9px] pb-[13px] w-full appearance-none ">
                <option value="">انتخاب زیرشاخه</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $subCategories[$selectedCategory]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($sub); ?>"><?php echo e($sub); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>

            <!-- آیکون سفارشی -->
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/arrow-down.svg')); ?>" alt="↓" class="w-4 h-4">
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


        <!-- نمایش محتوای زیرشاخه -->
        <!--[if BLOCK]><![endif]--><?php if($selectedSubCategory): ?>
        <!--[if BLOCK]><![endif]--><?php switch($selectedSubCategory):
        case ('گزارش بیلانس مشتریان'): ?>

        <!-- جدول گزارش بیلانس مشتریان -->
        <div class="overflow-x-auto w-full mt-4">
            <div class="max-h-[600px] overflow-y-auto">
                <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                    <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                        <tr>
                            <th class="px-4 py-4 font-bold w-16">
                                <span class="border border-white px-2 py-1 rounded-lg">#</span>
                            </th>
                            <th class="px-4 py-4 font-bold">نمبرحساب</th>
                            <th class="px-4 py-4 font-bold">نام حساب</th>
                            <th class="px-4 py-4 font-bold">آخرین تاریخ</th>
                            <th class="px-4 py-4 font-bold">دالر</th>
                            <th class="px-4 py-4 font-bold">افغانی</th>
                            <th class="px-4 py-4 font-bold">تومان</th>
                            <th class="px-4 py-4 font-bold">کلدار</th>
                            <th class="px-4 py-4 font-bold">یورو</th>
                            <th class="px-4 py-4 font-bold">درهم</th>
                            <th class="px-4 py-4 font-bold">لیره</th>
                            <th class="px-4 py-4 font-bold">یوان</th>
                            <?php
                            $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                            $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                            ?>
                            <th class="px-4 py-4 font-bold">بیلانس به <?php echo e($sourceCurrency); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 rounded-lg"><?php echo e($index + 1); ?></span>
                            </td>
                            <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo e($report['account_number']); ?></td>
                            <td class="px-4 py-4"><?php echo e($report['fullname']); ?></td>
                            <td class="px-3 py-4">
                                <?php echo e($report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') :
                                '-'); ?>

                            </td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['usd'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['afn'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['irr'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['pkr'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['eur'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['aed'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['try'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 text-left"><?php echo e(number_format($report['balances']['cny'] ?? 0, 2)); ?></td>
                            <td class="px-4 py-4 font-medium text-left"><?php echo e(number_format($report['total_balance'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                                هیچ داده‌ای یافت نشد
                            </td>
                        </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

      <div class="flex-1 flex flex-col bg-[#F5F5F5] p-3 md:p-4 lg:p-6 rounded-[12px] w-full mb-5"
        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      </div>
    <?php break; ?>


    <?php case ('پرداخت‌های مشتری'): ?>
    <div class="border p-5 rounded bg-green-50">
        <h3 class="font-bold text-lg mb-3"><?php echo e($selectedSubCategory); ?></h3>
        <p><?php echo e($reportData); ?></p>
    </div>
    <?php break; ?>

    <?php case ('صورتحساب‌ها'): ?>
    <div class="border p-5 rounded bg-yellow-50">
        <h3 class="font-bold text-lg mb-3"><?php echo e($selectedSubCategory); ?></h3>
        <p><?php echo e($reportData); ?></p>
    </div>
    <?php break; ?>

    <?php case ('گزارش صندوق'): ?>
    <div class="border p-5 rounded bg-purple-50">
        <h3 class="font-bold text-lg mb-3"><?php echo e($selectedSubCategory); ?></h3>
        <p><?php echo e($reportData); ?></p>
    </div>
    <?php break; ?>

    <?php default: ?>
    <div class="border p-5 rounded bg-gray-50">
        <h3 class="font-bold text-lg mb-3"><?php echo e($selectedSubCategory); ?></h3>
        <p><?php echo e($reportData); ?></p>
    </div>
    <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->

    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->



</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/general-reports.blade.php ENDPATH**/ ?>