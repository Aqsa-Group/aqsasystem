<?php
// تابع تبدیل کد ارز به نام فارسی (برای استفاده در کل view)
function getPersianCurrencyName($currencyCode) {
$currencyMap = [
'afn' => 'افغانی',
'usd' => 'دالر',
'irr' => 'تومان',
'eur' => 'یورو',
'pkr' => 'کلدار',
'aed' => 'درهم',
'try' => 'لیره',
'cny' => 'یوان',
'gbp' => 'پوند',
'jpy' => 'ین',
'sar' => 'ریال سعودی',
'inr' => 'روپیه',
];

$currencyCode = strtolower($currencyCode ?? 'usd');
return $currencyMap[$currencyCode] ?? $currencyCode;
}
?>

<div>
    <div class="container mx-auto ">
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
            <h1 class="text-[24px] font-medium vazir">گزارش بیلانس مشتریان براساس نوعیت</h1>
            <h1 class="text-[#8C8C8C] dark:text-white">لیست بیلانس تمام مشتریانی که نوعیت حسابشان انتخاب شده</h1>
        </div>

        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <div class="w-full">
            <div class="bg-[#F5F5F5] dark:bg-black dark:border dark:border-white p-6 rounded-[12px] mx-auto"
                style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <div
                    class="flex flex-col md:flex-row justify-between items-center border border-[#8C8C8C] p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-[16px] vazir">گزارش مشتریان بر اساس نوعیت / دسته</h1>
                    <div class="relative w-[350px]">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                            class="absolute  dark:hidden left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6">
                        <svg width="24" height="24"
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        <input type="text" wire:model.live="search" placeholder="جستجو ..."
                            class="w-full dark:bg-black dark:text-white dark:border dark:border-white dark:placeholder:text-white border border-[#8C8C8C] bg-transparent rounded-2xl pl-10 pr-3 py-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-7 gap-4 items-stretch">
                    <div>
                        <button wire:click="printReport" wire:loading.attr='disabled' wire:target='printReport'
                            class="w-full flex items-center justify-center gap-2 bg-[#2563EB] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
                            <span wire:loading.remove wire:target='printReport'>چاپ گزارش</span>
                            <span wire:loading wire:target='printReport'> در حال چاپ گزارش.....</span>
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.7714 25C10.2156 25 9.74016 24.802 9.34516 24.4062C8.95016 24.0104 8.75224 23.5358 8.75141 22.9825V20H6.49141C5.93641 20 5.46141 19.802 5.06641 19.4062C4.67141 19.0104 4.47349 18.5354 4.47266 17.9812V13.2687C4.47266 12.5604 4.71307 11.967 5.19391 11.4887C5.67474 11.0087 6.26766 10.7687 6.97266 10.7687H23.0302C23.7385 10.7687 24.3322 11.0087 24.8114 11.4887C25.2906 11.9687 25.5302 12.562 25.5302 13.2687V17.9812C25.5302 18.5362 25.3327 19.0112 24.9377 19.4062C24.5427 19.8012 24.0672 19.9991 23.5114 20H21.2514V22.9812C21.2514 23.5362 21.0535 24.0112 20.6577 24.4062C20.2618 24.8012 19.7868 24.9991 19.2327 25H10.7714ZM6.49141 18.75H8.75141C8.78391 18.2225 8.99307 17.77 9.37891 17.3925C9.76474 17.0158 10.2289 16.8275 10.7714 16.8275H19.2327C19.7743 16.8275 20.2381 17.0162 20.6239 17.3937C21.0097 17.7704 21.2189 18.2225 21.2514 18.75H23.5114C23.7356 18.75 23.9197 18.6779 24.0639 18.5337C24.2081 18.3895 24.2802 18.2054 24.2802 17.9812V13.2687C24.2802 12.9154 24.1606 12.6187 23.9214 12.3787C23.6822 12.1387 23.3852 12.0187 23.0302 12.0187H6.97266C6.61849 12.0187 6.32182 12.1387 6.08266 12.3787C5.84349 12.6187 5.72349 12.9158 5.72266 13.27V17.9812C5.72266 18.2054 5.79474 18.3895 5.93891 18.5337C6.08307 18.6779 6.26724 18.75 6.49141 18.75ZM20.0014 10.77V7.78746C20.0014 7.56246 19.9293 7.37829 19.7852 7.23496C19.641 7.09079 19.4568 7.01871 19.2327 7.01871H10.7702C10.546 7.01871 10.3618 7.09079 10.2177 7.23496C10.0735 7.37912 10.0014 7.56329 10.0014 7.78746V10.7687H8.75141V7.78746C8.75141 7.23246 8.94932 6.75704 9.34516 6.36121C9.74016 5.96537 10.2152 5.76746 10.7702 5.76746H19.2327C19.7877 5.76746 20.2627 5.96537 20.6577 6.36121C21.0535 6.75704 21.2514 7.23204 21.2514 7.78621V10.7687L20.0014 10.77ZM22.0214 15.145C22.3756 15.145 22.6722 15.025 22.9114 14.785C23.1506 14.545 23.2706 14.2483 23.2714 13.895C23.2722 13.5416 23.1522 13.2445 22.9114 13.0037C22.6706 12.7629 22.3739 12.6429 22.0214 12.6437C21.6689 12.6445 21.3718 12.7645 21.1302 13.0037C20.8885 13.2429 20.7689 13.54 20.7714 13.895C20.7739 14.25 20.8935 14.5466 21.1302 14.785C21.3668 15.0233 21.6639 15.1433 22.0214 15.145ZM20.0014 22.98V18.8462C20.0014 18.6212 19.9293 18.4366 19.7852 18.2925C19.641 18.1483 19.4568 18.0762 19.2327 18.0762H10.7702C10.546 18.0762 10.3618 18.1483 10.2177 18.2925C10.0735 18.4375 10.0014 18.622 10.0014 18.8462V22.9812C10.0014 23.2054 10.0735 23.3895 10.2177 23.5337C10.3618 23.6779 10.5464 23.75 10.7714 23.75H19.2327C19.4568 23.75 19.641 23.6779 19.7852 23.5337C19.9293 23.3895 20.0014 23.205 20.0014 22.98ZM6.49141 12.02H5.72266H24.2802H6.49141Z"
                                    fill="white" />
                            </svg>
                        </button>
                    </div>
                    <!-- دکمه ۲ - بروزرسانی -->
                    <div>
                        <button wire:click="refreshReport"
                            class="w-full flex items-center justify-center gap-2 bg-[#2563EB] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
                            <span>بروز رسانی </span>
                            <svg width="24" height="24" viewBox="0 0 30 30" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.1875 27.0875C23.55 25.675 27.5 20.8 27.5 15C27.5 8.1 21.95 2.5 15 2.5C6.6625 2.5 2.5 9.45 2.5 9.45M2.5 9.45V3.75M2.5 9.45H5.0125H8.05"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2.5 15C2.5 21.9 8.1 27.5 15 27.5" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="3 3" />
                            </svg>
                        </button>
                    </div>

                    <!-- دکمه ۳ - بازنشانی فیلترها -->
                    <div>
                        <button wire:click="resetFilters"
                            class="w-full flex items-center justify-center gap-2  bg-[#2563EB] text-white px-4 py-4 rounded-xl hover:bg-blue-700 transition">
                            <span>بازنشانی </span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 18L18 6M6 6l12 12" stroke="white" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <!-- سلکت ۱ - انتخاب مشتری -->
                    <div class="relative">
                        <select wire:model.live="selectedCustomer"
                            class="appearance-none w-full dark:bg-black dark:border-white dark:text-white border border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
                            <option value="">همه مشتریان</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($customer['id']); ?>"><?php echo e($customer['fullname']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none dark:hidden" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>


                        <svg width="24" height="24"
                            class=" absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none hidden dark:block"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>

                    <!-- سلکت ۲ - نوع ارز -->
                    <div class="relative">
                        <select wire:model.live="selectedCurrency"
                            class="appearance-none w-full border dark:text-white dark:bg-black dark:border-white border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
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
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none dark:hidden" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>


                        <svg width="24" height="24"
                            class=" absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none hidden dark:block"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>

                    <!-- سلکت ۳ - نوع حساب (بانکی/نقدی) -->
                    <div class="relative">
                        <select wire:model.live="accountType"
                            class="appearance-none w-full border dark:bg-black dark:border-white dark:text-white border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800">
                            <option value="">همه حساب‌ها</option>
                            <option value="بانکی">بانکی</option>
                            <option value="نقدی">نقدی</option>
                        </select>
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none dark:hidden" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>


                        <svg width="24" height="24"
                            class=" absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none hidden dark:block"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>

                    <!-- فیلد تاریخ -->
                    <div class="relative flex items-center justify-center text-center">
                        <input type="text" wire:model.live.debounce.300ms="date" wire:change="generateReport"
                            placeholder="1403/01/01"
                            class="appearance-none w-full border dark:bg-black dark:border-white  border-[#8C8C8C] bg-transparent rounded-xl py-4 pl-10 pr-4 focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm text-gray-800 text-center">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" width="20" height="20"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 2V5M16 2V5M3.5 9.09H20.5M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z"
                                stroke="#8C8C8C" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M15.6947 13.7H15.7037M15.6947 16.7H15.7037M11.9955 13.7H12.0045M11.9955 16.7H12.0045M8.29431 13.7H8.30329M8.29431 16.7H8.30329"
                                stroke="#8C8C8C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

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
                                    <th class="px-4 py-4 font-bold">مشتری معرف</th>
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
                                    // دریافت نام ارز مبدا به فارسی
                                    $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                                    $sourceCurrency = getPersianCurrencyName($latestProfitRate->source_currency ??
                                    'usd');
                                    ?>
                                    <th class="px-4 py-4 font-bold">بیلانس به <?php echo e($sourceCurrency); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class=" border-b  dark:bg-black dark:text-white dark:border-white hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <span class="border border-gray-300 px-2 py-1 rounded-lg"><?php echo e($index + 1); ?></span>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        <?php echo e($report['account_number']); ?>

                                    </td>
                                    <td class="px-4 py-4"><?php echo e($report['fullname']); ?></td>
                                    <td class="px-4 py-4">
                                        <?php echo e($report['related_customer_name'] ?? '-'); ?>

                                    </td>
                                    <td class="px-4 py-4">
                                        <?php echo e($report['last_date'] ?
                                        \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') : '-'); ?>

                                    </td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['usd'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['afn'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['irr'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['pkr'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['eur'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['aed'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['try'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 text-left" dir="ltr"><?php echo e(number_format($report['balances']['cny'] ?? 0, 2)); ?></td>
                                    <td class="px-4 py-4 font-medium text-left" dir="ltr">
                                        <?php echo e(number_format($report['total_balance'], 2)); ?>

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
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
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
                                        <p class="text-gray-600" x-text="'تاریخ چاپ: ' + (printData?.print_date || '')">
                                        </p>
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

                                    <table class="w-full text-sm border-collapse border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 p-2">#</th>
                                                <th class="border border-gray-300 p-2">نمبرحساب</th>
                                                <th class="border border-gray-300 p-2">نام حساب</th>
                                                <th class="border border-gray-300 p-2">مشتری معرف</th>
                                                <th class="border border-gray-300 p-2">دالر</th>
                                                <th class="border border-gray-300 p-2">افغانی</th>
                                                <?php
                                                // دریافت نام ارز مبدا به فارسی برای چاپ
                                                $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                                                $sourceCurrency =
                                                getPersianCurrencyName($latestProfitRate->source_currency ?? 'usd');
                                                ?>
                                                <th class="border border-gray-300 p-2">بیلانس به <?php echo e($sourceCurrency); ?>

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(report, index) in printData?.reports || []"
                                                :key="report.id">
                                                <tr>
                                                    <td class="border border-gray-300 p-2 text-center"
                                                        x-text="index + 1"></td>
                                                    <td class="border border-gray-300 p-2"
                                                        x-text="report.account_number"></td>
                                                    <td class="border border-gray-300 p-2" x-text="report.fullname">
                                                    </td>
                                                    <td class="border border-gray-300 p-2"
                                                        x-text="report.related_customer_name || '-'"></td>
                                                    <td class="border border-gray-300 p-2 text-left"
                                                        x-text="(report.balances?.usd || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-left"
                                                        x-text="(report.balances?.afn || 0).toFixed(2)"></td>
                                                    <td class="border border-gray-300 p-2 text-left font-bold"
                                                        x-text="report.total_balance.toFixed(2)"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>

                                    <div class="mt-4 text-sm">
                                        <p x-text="'تعداد کل مشتریان: ' + (printData?.total_customers || 0)"></p>
                                        <p
                                            x-text="'مجموع بیلانس: ' + (printData?.total_balance?.toFixed(2) || '0.00') + ' دالر'">
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
            }
        }
    </style>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/account-reports.blade.php ENDPATH**/ ?>