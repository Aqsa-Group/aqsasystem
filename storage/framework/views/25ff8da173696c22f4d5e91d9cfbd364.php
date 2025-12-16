<div>
    <div class="container mx-auto">
        <!-- Session Message -->
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Page Header -->
        <div class="space-y-4 mb-6">
            <h1 class="text-[24px] font-medium vazir">گزارش بدهی/طلبی با سایر صرافی‌ها</h1>
            <div class="text-sm text-gray-600">
                <p>مقادیر مثبت: شما از این صرافی بدهکار هستید</p>
                <p>مقادیر منفی: شما به این صرافی طلبکار هستید</p>
            </div>
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <div class="w-full">
            <div class="bg-[#F5F5F5] p-6 rounded-[12px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-[16px] vazir">گزارش تراکنش‌های با سایر صرافی‌ها</h1>
                    <div class="relative w-[350px]">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5">
                        <input type="text" wire:model.live="search" placeholder="جستجو نام صرافی ..."
                            class="w-full border border-[#8C8C8C] bg-transparent rounded-2xl pl-10 pr-3 py-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
                    </div>
                </div>

             <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 items-stretch mb-4">
    <!-- دکمه ۱ - چاپ گزارش -->
 <!-- دکمه PDF -->
<div>
    <button wire:click="downloadPDF"
        class="w-full flex items-center justify-center gap-2 bg-[#2563EB] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
        <span>چاپ گزارش</span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 16L12 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M15 13L12 16L9 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 3H5C4.46957 3 3.96086 3.21071 3.58579 3.58579C3.21071 3.96086 3 4.46957 3 5V19C3 19.5304 3.21071 20.0391 3.58579 20.4142C3.96086 20.7893 4.46957 21 5 21H19C19.5304 21 20.0391 20.7893 20.4142 20.4142C20.7893 20.0391 21 19.5304 21 19V5C21 4.46957 20.7893 3.96086 20.4142 3.58579C20.0391 3.21071 19.5304 3 19 3H16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</div>
    
    <!-- دکمه ۲ - بروزرسانی -->
    <div>
        <button wire:click="refreshReport"
            class="w-full flex items-center justify-center gap-2 bg-[#2563EB] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
            <span>بروز رسانی</span>
            <svg width="24" height="24" viewBox="0 0 30 30" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M18.1875 27.0875C23.55 25.675 27.5 20.8 27.5 15C27.5 8.1 21.95 2.5 15 2.5C6.6625 2.5 2.5 9.45 2.5 9.45M2.5 9.45V3.75M2.5 9.45H5.0125H8.05" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2.5 15C2.5 21.9 8.1 27.5 15 27.5" stroke="white" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="3 3" />
            </svg>
        </button>
    </div>

    <!-- دکمه ۳ - بازنشانی فیلترها -->
    <div>
        <button wire:click="resetFilters"
            class="w-full flex items-center justify-center gap-2  bg-[#2563EB] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
            <span>بازنشانی</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M6 18L18 6M6 6l12 12" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    <!-- سلکت ۱ - انتخاب صرافی خاص -->
    <div class="relative">
        <select wire:model="specificSarafiId"
            class="appearance-none w-full border border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
            <option value="">همه صرافی‌ها</option>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sarafis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sarafi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($sarafi['id']); ?>"><?php echo e($sarafi['sarafi_name']); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" width="20" height="20"
            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995" stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <!-- سلکت ۲ - نوع ارز -->
    <div class="relative">
        <select wire:model="selectedCurrency"
            class="appearance-none w-full border border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
            <option value="">همه ارزها</option>
            <option value="usd">دالر</option>
            <option value="afn">افغانی</option>
            <option value="irr">تومان</option>
            <option value="eur">یورو</option>
            <option value="pkr">کلدار</option>
            <option value="aed">درهم</option>
            <option value="try">لیره</option>
            <option value="cny">یوان</option>
        </select>
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" width="20" height="20"
            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995" stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <!-- سلکت ۳ - نوع حساب (بانکی/نقدی) -->
    <div class="relative">
        <select wire:model="accountType"
            class="appearance-none w-full border border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
            <option value="">همه حساب‌ها</option>
            <option value="بانکی">بانکی</option>
            <option value="نقدی">نقدی</option>
        </select>
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" width="20" height="20"
            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995" stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
</div>

              <div class="overflow-x-auto w-full mt-4">
    <div class="max-h-[600px] overflow-y-auto">
        <table class="w-full text-sm md:text-base text-gray-500">
            <thead class="bg-[#2B65E5] text-white text-[16px] vazir h-16 sticky top-0">
                <tr>
                    <th class="px-2 py-4 font-bold w-12 text-center">
                        <span class="border border-white px-2 py-1 rounded-lg">#</span>
                    </th>
                    <th class="px-3 py-4 font-bold text-center min-w-[150px]">صرافی</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">دالر</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">افغانی</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">تومان</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">کلدار</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">یورو</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">درهم</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">لیره</th>
                    <th class="px-2 py-4 font-bold text-center min-w-[90px]">یوان</th>
                </tr>
            </thead>
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50" dir="ltr">
                    <td class="px-2 py-4 text-center">
                        <span class="border border-gray-300 px-2 py-1 rounded-lg"><?php echo e($index + 1); ?></span>
                    </td>
                    <td class="px-3 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <?php echo e($report['sarafi_name']); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['usd'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['usd'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['usd'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['afn'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['afn'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['afn'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['irr'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['irr'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['irr'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['pkr'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['pkr'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['pkr'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['eur'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['eur'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['eur'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['aed'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['aed'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['aed'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['try'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['try'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['try'] ?? 0, 2)); ?>

                    </td>
                    <td class="px-2 py-4 text-right <?php echo e(($report['balances']['cny'] ?? 0) < 0 ? 'text-red-600 font-bold' : (($report['balances']['cny'] ?? 0) > 0 ? 'text-green-600 font-bold' : 'text-gray-500')); ?>">
                        <?php echo e(number_format($report['balances']['cny'] ?? 0, 2)); ?>

                    </td>
                  
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                        هیچ تراکنشی با سایر صرافی‌ها یافت نشد
                    </td>
                </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>
</div>
            </div>
        </div>
    </div>

    <!-- مودال چاپ -->
   <div x-data="{ showPrintModal: false, printData: null }"
    x-on:open-print-modal.window="showPrintModal = true; printData = $event.detail.printData"
    class="fixed inset-0 z-50 overflow-y-auto" x-show="showPrintModal" x-cloak>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showPrintModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 vazir">
                            پیش‌نمایش چاپ گزارش
                        </h3>
                        <div class="mt-4" id="print-content">
                            <!-- محتوای قابل چاپ -->
                            <div class="border-2 border-dashed border-gray-300 p-4">
                                <div class="text-center mb-4">
                                    <h2 class="text-xl font-bold vazir" x-text="printData?.title"></h2>
                                    <p class="text-gray-600" x-text="'تاریخ چاپ: ' + (printData?.print_date || '')"></p>
                                    <p class="text-gray-600" x-text="'صرافی جاری: ' + (printData?.current_sarafi || '')"></p>
                                </div>

                                <div class="mb-4" x-show="printData?.filters">
                                    <h3 class="font-bold vazir mb-2">فیلترهای اعمال شده:</h3>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <template x-for="[key, value] in Object.entries(printData?.filters || {})">
                                            <div class="flex justify-between">
                                                <span x-text="key" class="font-medium"></span>
                                                <span x-text="value" class="text-gray-600"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- اگر فقط یک صرافی انتخاب شده، جزئیات تراکنش‌ها را نمایش بده -->
                                <template x-if="printData?.reports?.length === 1">
                                    <div class="mb-6">
                                        <h3 class="font-bold vazir mb-2 text-center">
                                            جزئیات معاملات با 
                                            <span x-text="printData?.reports[0]?.sarafi_name"></span>
                                        </h3>
                                        
                                        <div class="mb-4">
                                            <h4 class="font-bold vazir mb-2">تراکنش‌ها:</h4>
                                            <table class="w-full text-sm border-collapse border border-gray-300">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th class="border border-gray-300 p-2">#</th>
                                                        <th class="border border-gray-300 p-2">تاریخ</th>
                                                        <th class="border border-gray-300 p-2">نوع</th>
                                                        <th class="border border-gray-300 p-2">ارز</th>
                                                        <th class="border border-gray-300 p-2">مبلغ</th>
                                                        <th class="border border-gray-300 p-2">نوع حساب</th>
                                                        <th class="border border-gray-300 p-2">توضیحات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-if="printData?.reports[0]?.transactions?.length > 0">
                                                        <template x-for="(transaction, index) in printData?.reports[0]?.transactions" :key="index">
                                                            <tr>
                                                                <td class="border border-gray-300 p-2 text-center" x-text="index + 1"></td>
                                                                <td class="border border-gray-300 p-2 text-center" x-text="transaction.date"></td>
                                                                <td class="border border-gray-300 p-2 text-center" 
                                                                    :class="transaction.type === 'ارسال' ? 'text-red-600' : 'text-green-600'"
                                                                    x-text="transaction.type"></td>
                                                                <td class="border border-gray-300 p-2 text-center">
                                                                    <span x-text="getCurrencyName(transaction.currency)"></span>
                                                                </td>
                                                                <td class="border border-gray-300 p-2 text-right font-bold"
                                                                    :class="transaction.type === 'ارسال' ? 'text-red-600' : 'text-green-600'"
                                                                    x-text="transaction.amount.toFixed(2)"></td>
                                                                <td class="border border-gray-300 p-2 text-center" x-text="transaction.account_type || '-'"></td>
                                                                <td class="border border-gray-300 p-2" x-text="transaction.description || '-'"></td>
                                                            </tr>
                                                        </template>
                                                    </template>
                                                    <template x-if="!printData?.reports[0]?.transactions?.length">
                                                        <tr>
                                                            <td colspan="7" class="border border-gray-300 p-4 text-center text-gray-500">
                                                                هیچ تراکنشی یافت نشد
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </template>

                                <!-- جدول خلاصه موجودی‌ها -->
                                <table class="w-full text-sm border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 p-2">#</th>
                                            <th class="border border-gray-300 p-2">صرافی</th>
                                            <th class="border border-gray-300 p-2">دالر</th>
                                            <th class="border border-gray-300 p-2">افغانی</th>
                                            <th class="border border-gray-300 p-2">تومان</th>
                                            <th class="border border-gray-300 p-2">کلدار</th>
                                            <th class="border border-gray-300 p-2">یورو</th>
                                            <th class="border border-gray-300 p-2">درهم</th>
                                            <th class="border border-gray-300 p-2">لیره</th>
                                            <th class="border border-gray-300 p-2">یوان</th>
                                            <th class="border border-gray-300 p-2">مجموع به دالر</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-if="printData?.reports?.length > 0">
                                            <template x-for="(report, index) in printData?.reports" :key="report.sarafi_id">
                                                <tr>
                                                    <td class="border border-gray-300 p-2 text-center" x-text="index + 1"></td>
                                                    <td class="border border-gray-300 p-2" x-text="report.sarafi_name"></td>
                                                    <td class="border border-gray-300 p-2 text-right" 
                                                        :class="(report.balances?.usd || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.usd || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.usd || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.afn || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.afn || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.afn || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.irr || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.irr || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.irr || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.pkr || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.pkr || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.pkr || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.eur || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.eur || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.eur || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.aed || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.aed || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.aed || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.try || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.try || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.try || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right"
                                                        :class="(report.balances?.cny || 0) < 0 ? 'text-red-600 font-bold' : (report.balances?.cny || 0) > 0 ? 'text-green-600 font-bold' : ''"
                                                        x-text="(report.balances?.cny || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-right font-bold"
                                                        :class="report.total_balance < 0 ? 'text-red-600' : (report.total_balance > 0 ? 'text-green-600' : '')"
                                                        x-text="report.total_balance.toFixed(2)"></td>
                                                </tr>
                                            </template>
                                        </template>
                                        <template x-if="!printData?.reports?.length">
                                            <tr>
                                                <td colspan="11" class="border border-gray-300 p-4 text-center text-gray-500">
                                                    هیچ داده‌ای یافت نشد
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>

                                <div class="mt-4 text-sm">
                                    <p x-text="'تعداد صرافی‌ها: ' + (printData?.total_sarafis || 0)"></p>
                                    <p class="font-bold">
                                        مجموع کل بیلانس: 
                                        <span :class="printData?.total_balance < 0 ? 'text-red-600' : (printData?.total_balance > 0 ? 'text-green-600' : '')"
                                              x-text="(printData?.total_balance || 0).toFixed(2) + ' دالر'"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="window.print()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    چاپ
                </button>
                <button type="button" @click="showPrintModal = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    بستن
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // تابع کمکی برای نمایش نام فارسی ارز
    function getCurrencyName(currencyCode) {
        const currencyMap = {
            'afn': 'افغانی',
            'usd': 'دالر',
            'irr': 'تومان',
            'eur': 'یورو',
            'pkr': 'کلدار',
            'aed': 'درهم',
            'try': 'لیره',
            'cny': 'یوان',
            'gbp': 'پوند',
            'jpy': 'ین',
            'sar': 'ریال سعودی',
            'inr': 'روپیه',
        };
        return currencyMap[currencyCode] || currencyCode;
    }
</script>
    <!-- استایل برای چاپ -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-content,
            #print-content * {
                visibility: visible;
            }

            #print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                font-size: 12pt;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
            }

            th {
                background-color: #f0f0f0;
            }
        }
    </style>

    <!-- استایل کامل برای چاپ -->
<style>
    @media print {
        /* مخفی کردن همه چیز بجز محتوای چاپ */
        body * {
            visibility: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #print-content,
        #print-content * {
            visibility: visible !important;
        }

        #print-content {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0.5cm !important;
            font-family: 'Vazir', Tahoma, sans-serif !important;
            direction: rtl !important;
        }

        /* تنظیم صفحه A4 portrait */
        @page {
            size: A4 portrait;
            margin: 0.5cm;
        }

        /* مخفی کردن المان‌های غیرضروری در چاپ */
        .no-print {
            display: none !important;
        }

        /* استایل جداول */
        .print-table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9pt !important;
            margin: 5px 0 !important;
            page-break-inside: auto;
        }

        .print-table th,
        .print-table td {
            border: 1px solid #000 !important;
            padding: 4px !important;
            text-align: center !important;
            vertical-align: middle !important;
        }

        .print-table th {
            background-color: #f2f2f2 !important;
            font-weight: bold !important;
        }

        .print-table td {
            font-size: 8pt !important;
        }

        /* جلوگیری از شکستن ردیف‌های جدول */
        .print-table tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }

        /* سرتیتر جدول در هر صفحه تکرار شود */
        .print-table thead {
            display: table-header-group !important;
        }

        /* تیترهای گزارش */
        .print-title {
            text-align: center !important;
            font-size: 14pt !important;
            font-weight: bold !important;
            margin-bottom: 10px !important;
            padding-bottom: 5px !important;
            border-bottom: 2px solid #000 !important;
        }

        .print-subtitle {
            text-align: center !important;
            font-size: 10pt !important;
            margin-bottom: 15px !important;
        }

        /* بخش اطلاعات */
        .print-info {
            margin-bottom: 15px !important;
            font-size: 9pt !important;
        }

        .print-info p {
            margin: 2px 0 !important;
        }

        /* کارت فیلترها */
        .filter-card {
            border: 1px solid #ccc !important;
            padding: 8px !important;
            margin-bottom: 15px !important;
            background-color: #f9f9f9 !important;
            font-size: 9pt !important;
        }

        /* تنظیمات برای جدول‌های بزرگ */
        .table-wrapper {
            overflow-x: hidden !important;
            max-width: 100% !important;
        }

        /* رنگ‌های چاپ */
        .text-green-print {
            color: #006400 !important; /* سبز تیره برای چاپ */
        }

        .text-red-print {
            color: #8B0000 !important; /* قرمز تیره برای چاپ */
        }

        /* تنظیم جهت متن برای اعداد */
        .text-number {
            font-family: 'Segoe UI', Tahoma, sans-serif !important;
            direction: ltr !important;
            display: inline-block !important;
        }

        /* فوتر گزارش */
        .print-footer {
            margin-top: 20px !important;
            padding-top: 10px !important;
            border-top: 1px solid #000 !important;
            font-size: 8pt !important;
            text-align: center !important;
        }

        /* تنظیم اندازه ستون‌ها */
        .col-no {
            width: 30px !important;
        }

        .col-sarafi {
            width: 120px !important;
            min-width: 120px !important;
        }

        .col-currency {
            width: 70px !important;
            min-width: 70px !important;
        }

        .col-total {
            width: 90px !important;
            min-width: 90px !important;
        }
    }
</style>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/sarafi-reports.blade.php ENDPATH**/ ?>