<div>
    <div class="pl-10 pr-10 mb-5">
        <div class="flex flex-col space-y-3">
            <h1 class="text-[24px] font-semibold text-black dark:text-white">تبدیل ارز در حساب مشتری</h1>
            <h1 class="text-[#8C8C8C] dark:text-white text-[18px]">صفحه تبدیل ارز در حساب مشتری</h1>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <!-- پیام‌های سیستم -->
        <?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
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


        
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 -mt-5">



            <?php if($selectedCustomer): ?>
            <div class="inline-block align-top ml-4 h-auto">
                <div class="flex flex-col h-[212px] w-[244px] pr-5 pl-5 pt-2 rounded-[12px]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900     
           bg-[#184D6C]
                backdrop-blur-lg
                border border-white/30

            shadow-[0_4px_4px_rgba(24,77,108,0.25)] text-black">
                    
                    <div x-data="{ 
    showLargeImage: false, 
    largeImageSrc: '',
    customerName: '<?php echo e(addslashes($selectedCustomer->fullname)); ?>',
    customerPhone: '<?php echo e(addslashes($selectedCustomer->phone ?? '')); ?>'
}">

                        
                        <?php if($selectedCustomer->image): ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(Storage::url($selectedCustomer->image)); ?>"
                                alt="<?php echo e($selectedCustomer->fullname); ?>" class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer 
                   hover:scale-105 transition-transform duration-200 hover:shadow-lg"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(Storage::url($selectedCustomer->image)); ?>'"
                                onerror="this.onerror=null; this.src='<?php echo e(asset('assets/web.jpg')); ?>'"
                                title="برای بزرگنمایی کلیک کنید">
                        </div>
                        <?php else: ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(asset('assets/web.jpg')); ?>" alt="<?php echo e($selectedCustomer->fullname); ?>" class="w-20 h-20 rounded-lg object-cover border-2 border-white cursor-pointer 
                              hover:scale-105 transition-transform duration-200 hover:shadow-lg"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(asset('assets/web.jpg')); ?>'"
                                title="برای بزرگنمایی کلیک کنید">
                        </div>
                        <?php endif; ?>

                        
                        <div x-show="showLargeImage" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 p-4"
                            @click.away="showLargeImage = false" @keydown.escape.window="showLargeImage = false"
                            style="display: none;">

                            <div class="relative w-full max-w-5xl">

                                
                                <button @click="showLargeImage = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 
                           text-3xl z-10 transition-colors duration-200 p-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                
                                <div class="flex justify-center">
                                    <img :src="largeImageSrc" :alt="customerName" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl 
                            border-4 border-white/20">
                                </div>

                                
                                <div class="mt-6 text-center text-white">
                                    <p class="text-2xl font-bold mb-2" x-text="customerName"></p>

                                    <template x-if="customerPhone">
                                        <p class="text-lg text-gray-300" x-text="customerPhone"></p>
                                    </template>

                                    
                                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                                        
                                        <a :href="largeImageSrc"
                                            :download="customerName + '_' + new Date().toISOString().split('T')[0] + '.jpg'"
                                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg 
                              transition-colors duration-200 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            دانلود
                                        </a>


                                        
                                        <button @click="showLargeImage = false" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg 
                                   transition-colors duration-200">
                                            بستن
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    
                    <h1 class="text-[20px] text-white text-center font-bold truncate"
                        title="<?php echo e($selectedCustomer->fullname); ?>">
                        <?php echo e($selectedCustomer->fullname); ?>

                    </h1>

                    
                    <?php if($selectedCustomer->phone): ?>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->phone); ?></span>
                    </div>
                    <?php endif; ?>

                    
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->account_number); ?></span>
                    </div>

                </div>
            </div>
            <?php endif; ?>

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            ?>

            
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div class="
    flex flex-col
  h-[212px] w-[244px]
    pr-5 pl-5 pt-3
    rounded-[12px]

 bg-[#184D6C]
                backdrop-blur-lg
                border border-white/30

    shadow-[0_4px_4px_rgba(24,77,108,0.25)]

    text-black
  ">
                    <h1 class="text-[24px] text-left vazir text-[#FFFFFF]"><?php echo e($currencyName); ?></h1>


                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <div class="flex justify-between items-center text-[14px]">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M10.8332 4.1665C13.1902 4.1665 14.3687 4.1665 15.1009 4.89874C15.8332 5.63097 15.8332 6.80948 15.8332 9.1665C15.8332 11.5235 15.8332 12.702 15.1009 13.4343C14.3687 14.1665 13.1902 14.1665 10.8332 14.1665H6.6665C4.30948 14.1665 3.13097 14.1665 2.39874 13.4343C1.6665 12.702 1.6665 11.5235 1.6665 9.1665C1.6665 6.80948 1.6665 5.63097 2.39874 4.89874C3.13097 4.1665 4.30948 4.1665 6.6665 4.1665H7.49984"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M13.3337 16.6665H9.16704C6.81002 16.6665 5.63151 16.6665 4.89927 15.9343C4.49103 15.5261 4.31039 14.9791 4.23047 14.1665M17.6015 15.9343C18.3337 15.2021 18.3337 14.0236 18.3337 11.6665C18.3337 9.30953 18.3337 8.13101 17.6015 7.39878C17.1932 6.99054 16.6463 6.80991 15.8337 6.72998"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M10.8332 9.16683C10.8332 10.3174 9.90043 11.2502 8.74984 11.2502C7.59924 11.2502 6.6665 10.3174 6.6665 9.16683C6.6665 8.01624 7.59924 7.0835 8.74984 7.0835C9.90043 7.0835 10.8332 8.01624 10.8332 9.16683Z"
                                            stroke="#1C274C" stroke-width="1.5" />
                                        <path d="M13.3335 10.8335L13.3335 7.50016" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M4.1665 10.8335L4.1665 7.50016" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-white">نقدی</span>
                            </div>
                            <span class="font-medium text-left text-white" dir="ltr"><?php echo e(number_format($cashBalance)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px]">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M2.64281 18.2249C3.61913 19.25 5.19047 19.25 8.33317 19.25H11.6665C14.8092 19.25 16.3805 19.25 17.3569 18.2249C18.3332 17.1997 18.3332 15.5498 18.3332 12.25C18.3332 11.2265 18.3332 10.3617 18.304 9.625M17.3569 6.27513C16.3805 5.25 14.8092 5.25 11.6665 5.25H8.33317C5.19047 5.25 3.61913 5.25 2.64281 6.27513C1.6665 7.30025 1.6665 8.95017 1.6665 12.25C1.6665 13.2735 1.6665 14.1383 1.69564 14.875"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M9.99984 1.75C11.5712 1.75 12.3569 1.75 12.845 2.26256C13.3332 2.77513 13.3332 3.60008 13.3332 5.25M7.15466 2.26256C6.6665 2.77513 6.6665 3.60008 6.6665 5.25"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M10.0002 15.1667C10.9206 15.1667 11.6668 14.5137 11.6668 13.7083C11.6668 12.9029 10.9206 12.25 10.0002 12.25C9.07969 12.25 8.3335 11.5971 8.3335 10.7917C8.3335 9.98625 9.07969 9.33333 10.0002 9.33333M10.0002 15.1667C9.07969 15.1667 8.3335 14.5137 8.3335 13.7083M10.0002 15.1667V15.75M10.0002 8.75V9.33333M10.0002 9.33333C10.9206 9.33333 11.6668 9.98625 11.6668 10.7917"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-white">بانکی</span>
                            </div>
                            <span class="font-medium text-left text-white" dir="ltr"><?php echo e(number_format($bankBalance)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] border-b border-[#184D6C]/15 pb-2">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M15.8332 11.6665V16.6665M15.8332 16.6665L17.4998 14.9998M15.8332 16.6665L14.1665 14.9998"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.3332 10.0002C18.3332 6.85747 18.3332 5.28612 17.3569 4.30981C16.3805 3.3335 14.8092 3.3335 11.6665 3.3335M11.6665 16.6668H8.33317C5.19047 16.6668 3.61913 16.6668 2.64281 15.6905C1.6665 14.7142 1.6665 13.1429 1.6665 10.0002C1.6665 6.85747 1.6665 5.28612 2.64281 4.30981C3.61913 3.3335 5.19047 3.3335 8.33317 3.3335"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M8.33333 13.3335H5" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M10.8332 13.3335H10.4165" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M1.6665 8.3335L5.83317 8.3335M18.3332 8.3335L9.1665 8.3335"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-[#FFFFFF]">مجموعه</span>
                            </div>
                            <span class="font-bold text-[16px] text-left text-white" dir="ltr"><?php echo e(number_format($totalBalance)); ?></span>
                        </div>
                    </div>




                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-[#FFFFFF]/10  rounded-[8px] mr-auto  backdrop:blur-2xl text-[12px] p-2 mt-2 text-gray-800 hover:shadow-md transition border border-white flex items-center justify-end gap-2 w-[114px] h-[25px]">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 12.5L12.5 7.5M12.5 7.5H8.75M12.5 7.5V11.25" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5.83366 2.78136C7.05938 2.07231 8.48246 1.6665 10.0003 1.6665C14.6027 1.6665 18.3337 5.39746 18.3337 9.99984C18.3337 14.6022 14.6027 18.3332 10.0003 18.3332C5.39795 18.3332 1.66699 14.6022 1.66699 9.99984C1.66699 8.48197 2.0728 7.05889 2.78184 5.83317"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />
                        </svg>

                        <span wire:loading.remove class="text-[#FFFFFF]">نمایش گزارش</span>
                        <span wire:loading class="text-[#FFFFFF]">
                            در حال انتقال...
                        </span>

                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($selectedCustomerId): ?>
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div class="flex flex-col h-[212px] w-[244px] pr-5 pl-5 pt-3 rounded-[12px]
                        dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900
               bg-[#184D6C]
                backdrop-blur-lg
                border border-white/30

    shadow-[0_4px_4px_rgba(24,77,108,0.25)] text-black">


                    <?php
                    /* =========================
                    تبدیل کد ارز به نام فارسی
                    ========================== */
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

                    $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                    $sourceCurrency = getPersianCurrencyName($latestProfitRate->source_currency ?? 'usd');

                    $totalCashUsd = 0;
                    $totalBankUsd = 0;

                    /* =========================
                    نرخ‌های خرید نقدی
                    ========================== */
                    $exchangeRatesCash = [
                    'افغانی' => $latestProfitRate->afn_buy_cash ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_cash ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_cash ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_cash ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_cash ?? 44,
                    'لیره' => $latestProfitRate->try_buy_cash ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_cash ?? 43,
                    'روپیه' => $latestProfitRate->inr_buy_cash ?? 7.14,
                    ];

                    /* =========================
                    نرخ‌های خرید بانکی
                    ========================== */
                    $exchangeRatesBank = [
                    'افغانی' => $latestProfitRate->afn_buy_bank ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_bank ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_bank ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_bank ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_bank ?? 44,
                    'لیره' => $latestProfitRate->try_buy_bank ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_bank ?? 43,
                    'روپیه' => $latestProfitRate->inr_buy_bank ?? 7.14,
                    ];

                    /* =========================
                    محاسبه موجودی نقدی
                    ========================== */
                    foreach ($customerCashBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalCashUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                    $totalCashUsd += $balance / $exchangeRatesCash[$currency];
                    }
                    }

                    /* =========================
                    محاسبه موجودی بانکی
                    ========================== */
                    foreach ($customerBankBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalBankUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesBank[$currency]) && $exchangeRatesBank[$currency] > 0) {
                    $totalBankUsd += $balance / $exchangeRatesBank[$currency];
                    }
                    }

                    $grandTotalUsd = $totalCashUsd + $totalBankUsd;
                    ?>

                    <h1 class="text-[24px] text-left vazir text-[#FFFFFF]">
                        خلاصه بیلانس به <?php echo e($sourceCurrency); ?>

                    </h1>

                    <div class="flex flex-col gap-1 mt-1 text-center">
                        <div class="flex justify-between items-center text-[14px]">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M10.8332 4.1665C13.1902 4.1665 14.3687 4.1665 15.1009 4.89874C15.8332 5.63097 15.8332 6.80948 15.8332 9.1665C15.8332 11.5235 15.8332 12.702 15.1009 13.4343C14.3687 14.1665 13.1902 14.1665 10.8332 14.1665H6.6665C4.30948 14.1665 3.13097 14.1665 2.39874 13.4343C1.6665 12.702 1.6665 11.5235 1.6665 9.1665C1.6665 6.80948 1.6665 5.63097 2.39874 4.89874C3.13097 4.1665 4.30948 4.1665 6.6665 4.1665H7.49984"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M13.3337 16.6665H9.16704C6.81002 16.6665 5.63151 16.6665 4.89927 15.9343C4.49103 15.5261 4.31039 14.9791 4.23047 14.1665M17.6015 15.9343C18.3337 15.2021 18.3337 14.0236 18.3337 11.6665C18.3337 9.30953 18.3337 8.13101 17.6015 7.39878C17.1932 6.99054 16.6463 6.80991 15.8337 6.72998"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M10.8332 9.16683C10.8332 10.3174 9.90043 11.2502 8.74984 11.2502C7.59924 11.2502 6.6665 10.3174 6.6665 9.16683C6.6665 8.01624 7.59924 7.0835 8.74984 7.0835C9.90043 7.0835 10.8332 8.01624 10.8332 9.16683Z"
                                            stroke="#1C274C" stroke-width="1.5" />
                                        <path d="M13.3335 10.8335L13.3335 7.50016" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M4.1665 10.8335L4.1665 7.50016" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-white">نقدی</span>
                            </div>
                            <span class="font-medium text-left text-white" dir="ltr">
                                <?php echo e(number_format($totalCashUsd, 2)); ?>

                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[14px]">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M2.64281 18.2249C3.61913 19.25 5.19047 19.25 8.33317 19.25H11.6665C14.8092 19.25 16.3805 19.25 17.3569 18.2249C18.3332 17.1997 18.3332 15.5498 18.3332 12.25C18.3332 11.2265 18.3332 10.3617 18.304 9.625M17.3569 6.27513C16.3805 5.25 14.8092 5.25 11.6665 5.25H8.33317C5.19047 5.25 3.61913 5.25 2.64281 6.27513C1.6665 7.30025 1.6665 8.95017 1.6665 12.25C1.6665 13.2735 1.6665 14.1383 1.69564 14.875"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M9.99984 1.75C11.5712 1.75 12.3569 1.75 12.845 2.26256C13.3332 2.77513 13.3332 3.60008 13.3332 5.25M7.15466 2.26256C6.6665 2.77513 6.6665 3.60008 6.6665 5.25"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M10.0002 15.1667C10.9206 15.1667 11.6668 14.5137 11.6668 13.7083C11.6668 12.9029 10.9206 12.25 10.0002 12.25C9.07969 12.25 8.3335 11.5971 8.3335 10.7917C8.3335 9.98625 9.07969 9.33333 10.0002 9.33333M10.0002 15.1667C9.07969 15.1667 8.3335 14.5137 8.3335 13.7083M10.0002 15.1667V15.75M10.0002 8.75V9.33333M10.0002 9.33333C10.9206 9.33333 11.6668 9.98625 11.6668 10.7917"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-white">بانکی </span>
                            </div> <span class="font-medium text-left text-white" dir="ltr">
                                <?php echo e(number_format($totalBankUsd, 2)); ?>

                            </span>
                        </div>

                        <div class="flex justify-between items-center text-[14px] border-b border-[#184D6C]/15 pb-2">
                            <div class="flex justify-end items-center gap-2">
                                <div class="bg-white rounded-[12px] h-[30px] w-[30px] justify-center items-center   ">
                                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="flex justify-center items-center w-full mt-1">
                                        <path
                                            d="M15.8332 11.6665V16.6665M15.8332 16.6665L17.4998 14.9998M15.8332 16.6665L14.1665 14.9998"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M18.3332 10.0002C18.3332 6.85747 18.3332 5.28612 17.3569 4.30981C16.3805 3.3335 14.8092 3.3335 11.6665 3.3335M11.6665 16.6668H8.33317C5.19047 16.6668 3.61913 16.6668 2.64281 15.6905C1.6665 14.7142 1.6665 13.1429 1.6665 10.0002C1.6665 6.85747 1.6665 5.28612 2.64281 4.30981C3.61913 3.3335 5.19047 3.3335 8.33317 3.3335"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M8.33333 13.3335H5" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M10.8332 13.3335H10.4165" stroke="#1C274C" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M1.6665 8.3335L5.83317 8.3335M18.3332 8.3335L9.1665 8.3335"
                                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>

                                </div>
                                <span class="text-[#FFFFFF]">مجموعه</span>
                            </div> <span class="font-bold text-[16px] text-left text-white" dir="ltr">
                                <?php echo e(number_format($grandTotalUsd, 2)); ?>

                            </span>
                        </div>

                    </div>


                    <button wire:click="showReport" wire:loading.attr="disabled"
                        class="bg-[#FFFFFF]/10  rounded-[8px] mr-auto  backdrop:blur-2xl text-[12px] p-2 mt-2 text-gray-800 hover:shadow-md transition border border-white flex items-center justify-end gap-2 w-[114px] h-[25px]">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 12.5L12.5 7.5M12.5 7.5H8.75M12.5 7.5V11.25" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5.83366 2.78136C7.05938 2.07231 8.48246 1.6665 10.0003 1.6665C14.6027 1.6665 18.3337 5.39746 18.3337 9.99984C18.3337 14.6022 14.6027 18.3332 10.0003 18.3332C5.39795 18.3332 1.66699 14.6022 1.66699 9.99984C1.66699 8.48197 2.0728 7.05889 2.78184 5.83317"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />
                        </svg>

                        <span wire:loading.remove class="text-[#FFFFFF]">نمایش گزارش</span>
                        <span wire:loading class="text-[#FFFFFF]">
                            در حال انتقال...
                        </span>

                    </button>


                </div>
            </div>
            <?php endif; ?>

        </div>
        <div class="flex flex-col lg:flex-row gap-5 mt-4">
            <!-- فرم تبدیل ارز -->
            <div class="flex flex-col
         dark:bg-black dark:text-white dark:border dark:border-white
bg-white   border border-[#D7E5EC] shadow-sm backdrop:blur-lg          mx-auto
         w-full max-w-[420px] lg:max-w-[474px]
         p-[10px]
         h-auto
         rounded-[12px]
         space-y-2">

                <!-- هدر فرم -->
                <div
                    class="flex flex-row justify-between p-[20px]  rounded-[12px] flex-wrap items-center gap-2 space-y-2">
                    <p class="flex justify-between items-center text-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                                stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span class="text-xl font-semibold inter">فورم تبدیل ارز در حساب</span>
                    </p>

                    <button wire:click="toggleTransactionType" class="rounded-[8px] p-[10px] text-white vazir px-12 font-semibold transition-colors duration-500 ease-in-out
                        <?php echo e($transactionType === 'خرید' ? 'bg-[#184D6C] text-white'
                : 'bg-white text-[#184D6C] border border-[#184D6C] hover:bg-[#184D6C]/10'); ?>">
                        <?php echo e($transactionType === 'خرید' ? 'خرید' : 'فروش'); ?>

                    </button>
                    

                </div>

                <!-- فرم اصلی -->
                <form wire:submit.prevent="submitConversion">
                    <!-- انتخاب حساب -->
                    <div class="mt-2">
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">حساب
                                مشتری</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                init() {
                                    this.updateDisplay();
                                    
                                    $wire.on('edit-mode-activated', (data) => {
                                        this.selectedId = data.selectedAccount;
                                        this.searchValue = data.selectedCustomer;
                                        setTimeout(() => {
                                            this.updateDisplay();
                                        }, 100);
                                    });
                                    
                                    $wire.on('transactionTypeToggled', () => {
                                        setTimeout(() => {
                                            this.updateDisplay();
                                        }, 100);
                                    });
                                },
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.selectAccount(selected.id);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('selectedAccount', null);
                                    }
                                },
                                updateDisplay() {
                                    if (this.selectedId) {
                                        const selected = this.customers.find(c => c.id == this.selectedId);
                                        if (selected) {
                                            this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        }
                                    }
                                }
                            }" x-init="init()" class="relative w-full">
                                <input list="customersList" x-model="searchValue" @change="handleSelect"
                                    placeholder="جستجو یا انتخاب حساب مشتری..."
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="customersList">
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </datalist>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            <?php $__errorArgs = ['selectedAccount'];
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


                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">از
                                حساب</label>
                            <select wire:model="from_account"
                                class="w-full dark:border-white dark:bg-black dark:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                            <?php $__errorArgs = ['from_account'];
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

                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">به
                                حساب</label>
                            <select wire:model="to_account"
                                class="w-full dark:border-white dark:text-white dark:bg-black  h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="نقدی">نقدی</option>
                                <option value="بانکی">بانکی</option>
                            </select>
                            <?php $__errorArgs = ['to_account'];
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
                    <!-- بخش تبدیل ارز -->
                    <div class="mt-4 rounded-[12px]">
                        <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                            <!-- ارز مبدا -->
                            <div class="lg:w-full">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    <?php if($transactionType === 'خرید'): ?>
                                    ارز خرید
                                    <?php else: ?>
                                    ارز خرید
                                    <?php endif; ?>
                                </label>
                                <div class="relative w-full">
                                    <select wire:model="from_currency"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                        <option value="">انتخاب ارز</option>
                                        <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <?php $__errorArgs = ['from_currency'];
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

                            <!-- مبلغ خرید -->
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    <?php if($transactionType === 'خرید'): ?>
                                    مبلغ خرید
                                    <?php else: ?>
                                    مبلغ خرید
                                    <?php endif; ?>
                                </label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="buy_amount"
                                        wire:click="setCalculatingField('buy')" placeholder="0"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                </div>
                                <?php if($withdrawalAmountInWords): ?>
                                <div class="mt-2 text-sm text-gray-600 dark:text-white">
                                    <strong></strong> <?php echo e($withdrawalAmountInWords); ?>

                                </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['buy_amount'];
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

                    <!-- بخش دریافت -->
                    <div class="mt-4 rounded-[12px]">
                        <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                            <!-- ارز مقصد -->
                            <div class="lg:w-full">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    <?php if($transactionType === 'خرید'): ?>
                                    ارز فروش
                                    <?php else: ?>
                                    ارز فروش
                                    <?php endif; ?>
                                </label>
                                <div class="relative w-full">
                                    <select wire:model="to_currency"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                        <option value="">انتخاب ارز</option>
                                        <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <?php $__errorArgs = ['to_currency'];
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

                            <!-- نرخ ارز -->
                            <div class="flex-1">
                                <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                    <?php if($transactionType === 'خرید'): ?>
                                    نرخ خرید
                                    <?php else: ?>
                                    نرخ فروش
                                    <?php endif; ?>
                                </label>
                                <div class="relative w-full">
                                    <input type="text" wire:model.live="currency_rate" placeholder="0.0000"
                                        class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                                    <?php $__errorArgs = ['currency_rate'];
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
                                <?php if($currencyRateInWords): ?>
                                <div class="mt-2 text-sm text-gray-600 dark:text-white">
                                    <strong></strong> <?php echo e($currencyRateInWords); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- مبلغ فروش و تاریخ -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- مبلغ فروش -->
                        <div class="flex-1">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                <?php if($transactionType === 'خرید'): ?>
                                مبلغ فروش
                                <?php else: ?>
                                مبلغ فروش
                                <?php endif; ?>
                            </label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="sell_amount"
                                    wire:click="setCalculatingField('sell')" placeholder="0"
                                    class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 " />
                            </div>
                            <?php if($receivedAmountInWords): ?>
                            <div class="mt-2 text-sm text-gray-600 dark:text-white">
                                <strong></strong> <?php echo e($receivedAmountInWords); ?>

                            </div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['sell_amount'];
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
                        <!-- تاریخ -->
                        <div class="lg:w-full relative" x-data="persianDatePicker()" x-init="init()">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">تاریخ</label>

                            <!-- Input field -->
                            <input type="text" x-ref="dateInput" x-model="displayDate" @click="togglePicker()"
                                placeholder="YYYY/MM/DD"
                                class="w-full dark:text-white dark:bg-black dark:border-white h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 cursor-pointer"
                                readonly />

                            <!-- Custom Date Picker Modal -->
                            <div x-show="isOpen" x-transition.opacity.duration.300ms x-cloak
                                @keydown.escape.window="closePicker()" @click.away="closePicker()"
                                class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                                aria-modal="true" style="display: none;" :style="isOpen ? 'display: block;' : ''">

                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <!-- Background overlay -->
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <!-- Modal panel -->
                                    <div
                                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6">
                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center space-x-2">
                                                    <button @click="prevYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button @click="prevMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <button @click="toggleMonthSelector()" type="button"
                                                        class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                        <span x-text="monthsAfghan[currentMonth]"></span>
                                                    </button>
                                                    <button @click="toggleYearSelector()" type="button"
                                                        class="text-lg font-bold text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                        <span x-text="currentYear"></span>
                                                    </button>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <button @click="nextMonth()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button @click="nextYear()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                    <button @click="closePicker()" type="button"
                                                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Month Selector -->
                                            <div x-show="showMonthSelector" x-transition>
                                                <div class="grid grid-cols-3 gap-2 mb-4">
                                                    <template x-for="(month, index) in monthsAfghan" :key="index">
                                                        <button @click="selectMonth(index)" :class="{
                                        'bg-blue-500 text-white': currentMonth === index,
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentMonth !== index
                                    }" class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="month"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Year Selector -->
                                            <div x-show="showYearSelector" x-transition>
                                                <div class="flex items-center justify-between mb-4">
                                                    <button @click="prevYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                    </button>
                                                    <span class="text-lg font-bold text-gray-800 dark:text-white">
                                                        <span x-text="yearRange.start"></span> - <span
                                                            x-text="yearRange.end"></span>
                                                    </span>
                                                    <button @click="nextYearRange()" type="button"
                                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-4 gap-2 mb-4">
                                                    <template x-for="year in yearRange.years" :key="year">
                                                        <button @click="selectYear(year)" :class="{
                                        'bg-blue-500 text-white': currentYear === year,
                                        'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': currentYear !== year
                                    }" class="py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                                            type="button">
                                                            <span x-text="year"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Calendar View -->
                                            <div x-show="!showMonthSelector && !showYearSelector" x-transition>
                                                <!-- Week Days -->
                                                <div class="grid grid-cols-7 gap-1 mb-2">
                                                    <template x-for="day in weekDaysAfghan" :key="day">
                                                        <div
                                                            class="text-center text-sm font-medium text-gray-500 dark:text-gray-400 py-1">
                                                            <span x-text="day"></span>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Days Grid -->
                                                <div class="grid grid-cols-7 gap-1">
                                                    <template x-for="day in calendarDays" :key="day.key">
                                                        <button @click="selectDate(day.day)" :class="{
                                        'bg-blue-500 text-white hover:bg-blue-600': day.isSelected,
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300': day.isToday && !day.isSelected,
                                        'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700': !day.isToday && !day.isSelected && !day.isOtherMonth,
                                        'text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800': day.isOtherMonth,
                                        'cursor-not-allowed opacity-50': day.isDisabled
                                    }" class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-medium transition-colors"
                                                            :disabled="day.isDisabled" type="button">
                                                            <span x-text="day.day"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Footer -->
                                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-between items-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                                        <span
                                                            x-text="selectedDate ? formatDate(selectedDate) : 'تاریخ انتخاب نشده'"></span>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <button @click="setToday()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                                            امروز
                                                        </button>
                                                        <button @click="clearDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                                            پاک کردن
                                                        </button>
                                                        <button @click="applyDate()" type="button"
                                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            تأیید
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $__errorArgs = ['date'];
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

                        <script>
                            function persianDatePicker() {
    return {
        isOpen: false,
        showMonthSelector: false,
        showYearSelector: false,
        displayDate: '',
        currentYear: 1403,
        currentMonth: 0,
        selectedDate: null,
        yearRange: {
            start: 1400,
            end: 1410,
            years: []
        },
        
        // ماه‌های افغانی
        monthsAfghan: [
            'حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله',
            'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'
        ],
        
        // روزهای هفته (شنبه شروع می‌شود)
        weekDaysAfghan: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
        
        // روزهای کامل هفته
        weekDaysFull: ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'],
        
        // تعداد روزهای ماه‌های شمسی در سال عادی
        daysInMonthNormal: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29],
        
        init() {
            this.updateYearRange();
            
            // Initialize with current date
            const today = this.getTodayPersian();
            this.currentYear = today.year;
            this.currentMonth = today.month - 1;
            
            // اگر تاریخ از قبل انتخاب شده بود
            if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('date')) {
                const dateParts = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('date').split('/');
                if (dateParts.length === 3) {
                    const year = parseInt(dateParts[0]);
                    const month = parseInt(dateParts[1]);
                    const day = parseInt(dateParts[2]);
                    
                    if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
                        this.selectedDate = { year, month, day };
                        this.displayDate = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('date');
                        this.currentYear = year;
                        this.currentMonth = month - 1;
                    }
                }
            }
        },
        
        // به‌روزرسانی محدوده سال‌ها
        updateYearRange() {
            this.yearRange.years = [];
            for (let year = this.yearRange.start; year <= this.yearRange.end; year++) {
                this.yearRange.years.push(year);
            }
        },
        
        // بررسی سال کبیسه
        isLeapYear(year) {
            // سال کبیسه شمسی: سال‌هایی که باقیمانده تقسیم به 33 برابر با 1, 5, 9, 13, 17, 22, 26, 30 باشد
            const remainders = [1, 5, 9, 13, 17, 22, 26, 30];
            return remainders.includes(year % 33);
        },
        
        // تعداد روزهای ماه
        getDaysInMonth(year, month) {
            const days = [...this.daysInMonthNormal];
            // اگر سال کبیسه باشد، اسفند 30 روز است
            if (month === 11 && this.isLeapYear(year)) {
                return 30;
            }
            return days[month];
        },
        
        // محاسبه روز هفته برای روز اول ماه
        getFirstDayOfWeek(year, month) {
            // الگوریتم محاسبه روز هفته برای تقویم هجری شمسی
            // روز اول فروردین سال 1403 = چهارشنبه (index = 4)
            const baseYear = 1403;
            const baseDay = 4; // چهارشنبه (شنبه=0)
            
            // محاسبه تعداد روزهای گذشته از سال پایه
            let days = 0;
            
            // محاسبه روزهای سال‌های کامل
            for (let y = baseYear; y < year; y++) {
                days += this.isLeapYear(y) ? 366 : 365;
            }
            
            // محاسبه روزهای ماه‌های گذشته از سال جاری
            for (let m = 0; m < month; m++) {
                days += this.getDaysInMonth(year, m);
            }
            
            // محاسبه روز هفته (0 = شنبه)
            return (baseDay + days) % 7;
        },
        
        // دریافت تاریخ امروز به شمسی
        getTodayPersian() {
            const today = new Date();
            
            // الگوریتم تبدیل میلادی به شمسی (ساده شده)
            const gregorianYear = today.getFullYear();
            const gregorianMonth = today.getMonth() + 1;
            const gregorianDay = today.getDate();
            
            // تبدیل میلادی به شمسی
            return this.gregorianToPersian(gregorianYear, gregorianMonth, gregorianDay);
        },
        
        // تبدیل میلادی به شمسی
        gregorianToPersian(gy, gm, gd) {
            // الگوریتم تبدیل میلادی به شمسی
            const gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            
            // بررسی کبیسه میلادی
            const isGregorianLeap = (gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0);
            
            if (isGregorianLeap) {
                gDaysInMonth[1] = 29;
            }
            
            // محاسبه روز از ابتدای سال میلادی
            let dayOfYear = gd;
            for (let i = 0; i < gm - 1; i++) {
                dayOfYear += gDaysInMonth[i];
            }
            
            // نوروز سال جاری
            const marchDay = 79; // 20 مارس
            
            let persianYear, persianMonth, persianDay;
            
            if (dayOfYear > marchDay) {
                persianYear = gy - 621;
                let remainingDays = dayOfYear - marchDay;
                
                const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                if (this.isLeapYear(persianYear)) {
                    pDaysInMonth[11] = 30;
                }
                
                for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                    if (remainingDays <= pDaysInMonth[persianMonth]) {
                        persianDay = remainingDays;
                        break;
                    }
                    remainingDays -= pDaysInMonth[persianMonth];
                }
                persianMonth++; // تبدیل به 1-based
            } else {
                persianYear = gy - 622;
                let remainingDays = dayOfYear + 286;
                
                const pDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                if (this.isLeapYear(persianYear)) {
                    pDaysInMonth[11] = 30;
                }
                
                for (persianMonth = 0; persianMonth < 12; persianMonth++) {
                    if (remainingDays <= pDaysInMonth[persianMonth]) {
                        persianDay = remainingDays;
                        break;
                    }
                    remainingDays -= pDaysInMonth[persianMonth];
                }
                persianMonth++; // تبدیل به 1-based
            }
            
            return {
                year: persianYear,
                month: persianMonth,
                day: persianDay
            };
        },
        
        // محاسبه روزهای تقویم برای نمایش
        get calendarDays() {
            const days = [];
            const daysInMonth = this.getDaysInMonth(this.currentYear, this.currentMonth);
            const firstDayOfWeek = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);
            const today = this.getTodayPersian();
            
            // روزهای ماه قبل
            const prevMonthDays = this.currentMonth === 0 ? 
                this.getDaysInMonth(this.currentYear - 1, 11) : 
                this.getDaysInMonth(this.currentYear, this.currentMonth - 1);
            
            for (let i = 0; i < firstDayOfWeek; i++) {
                const day = prevMonthDays - firstDayOfWeek + i + 1;
                days.push({
                    key: `prev-${day}`,
                    day: day,
                    isSelected: false,
                    isToday: false,
                    isOtherMonth: true,
                    isDisabled: true
                });
            }
            
            // روزهای ماه جاری
            for (let day = 1; day <= daysInMonth; day++) {
                const isSelected = this.selectedDate && 
                    this.selectedDate.year === this.currentYear && 
                    this.selectedDate.month === this.currentMonth + 1 && 
                    this.selectedDate.day === day;
                
                const isToday = today.year === this.currentYear && 
                    today.month === this.currentMonth + 1 && 
                    today.day === day;
                
                days.push({
                    key: `current-${day}`,
                    day: day,
                    isSelected: isSelected,
                    isToday: isToday,
                    isOtherMonth: false,
                    isDisabled: false
                });
            }
            
            // روزهای ماه بعد
            const remainingCells = 42 - days.length; // 6 ردیف × 7 ستون
            for (let day = 1; day <= remainingCells; day++) {
                days.push({
                    key: `next-${day}`,
                    day: day,
                    isSelected: false,
                    isToday: false,
                    isOtherMonth: true,
                    isDisabled: true
                });
            }
            
            return days;
        },
        
        togglePicker() {
            this.isOpen = !this.isOpen;
            this.showMonthSelector = false;
            this.showYearSelector = false;
        },
        
        closePicker() {
            this.isOpen = false;
            this.showMonthSelector = false;
            this.showYearSelector = false;
        },
        
        toggleMonthSelector() {
            this.showMonthSelector = !this.showMonthSelector;
            this.showYearSelector = false;
        },
        
        toggleYearSelector() {
            this.showYearSelector = !this.showYearSelector;
            this.showMonthSelector = false;
        },
        
        prevYear() {
            this.currentYear--;
            this.updateYearRange();
        },
        
        nextYear() {
            this.currentYear++;
            this.updateYearRange();
        },
        
        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },
        
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },
        
        prevYearRange() {
            this.yearRange.start -= 12;
            this.yearRange.end -= 12;
            this.updateYearRange();
        },
        
        nextYearRange() {
            this.yearRange.start += 12;
            this.yearRange.end += 12;
            this.updateYearRange();
        },
        
        selectMonth(monthIndex) {
            this.currentMonth = monthIndex;
            this.showMonthSelector = false;
        },
        
        selectYear(year) {
            this.currentYear = year;
            this.showYearSelector = false;
        },
        
        selectDate(day) {
            this.selectedDate = {
                year: this.currentYear,
                month: this.currentMonth + 1,
                day: day
            };
            
            this.displayDate = `${this.currentYear}/${String(this.currentMonth + 1).padStart(2, '0')}/${String(day).padStart(2, '0')}`;
        },
        
        formatDate(date) {
            if (!date) return '';
            return `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`;
        },
        
        setToday() {
            const today = this.getTodayPersian();
            this.currentYear = today.year;
            this.currentMonth = today.month - 1;
            this.selectedDate = today;
            this.displayDate = this.formatDate(today);
        },
        
        clearDate() {
            this.selectedDate = null;
            this.displayDate = '';
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('date', '');
            this.closePicker();
        },
        
        applyDate() {
            if (this.selectedDate) {
                const formattedDate = this.formatDate(this.selectedDate);
                this.displayDate = formattedDate;
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('date', formattedDate);
                this.closePicker();
            }
        }
    }
}
                        </script>

                        <style>
                            /* Hide scrollbar for number inputs */
                            input[type="number"]::-webkit-inner-spin-button,
                            input[type="number"]::-webkit-outer-spin-button {
                                -webkit-appearance: none;
                                margin: 0;
                            }

                            /* Persian datepicker custom styles */
                            .persian-datepicker {
                                font-family: 'Vazir', sans-serif;
                                direction: rtl;
                            }

                            /* Animation for modal */
                            [x-cloak] {
                                display: none !important;
                            }

                            /* Smooth transitions */
                            .transition-all {
                                transition-property: all;
                                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                                transition-duration: 150ms;
                            }

                            /* Custom scrollbar */
                            ::-webkit-scrollbar {
                                width: 8px;
                            }

                            ::-webkit-scrollbar-track {
                                background: #f1f1f1;
                                border-radius: 4px;
                            }

                            ::-webkit-scrollbar-thumb {
                                background: #888;
                                border-radius: 4px;
                            }

                            ::-webkit-scrollbar-thumb:hover {
                                background: #555;
                            }
                        </style>
                    </div>



                    <!-- زون‌ها -->
                    
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">زون
                                برداشت</label>
                            <select wire:model="zone_sender"
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب زون</option>
                                <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($zone); ?>"><?php echo e($zone); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['zone_sender'];
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

                        <div>
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">زون
                                دریافت</label>
                            <select wire:model="zone_receiver"
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500 appearance-none">
                                <option value="">انتخاب زون</option>
                                <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($zone); ?>"><?php echo e($zone); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['zone_receiver'];
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


                    <!-- شرح تراکنش -->
                    <div class="mt-3">
                        <div class="w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شرح
                                تراکنش</label>
                            <textarea wire:model="description" rows="3" placeholder="شرح کامل تبدیل ارز..."
                                class="w-full dark:bg-black dark:border dark:border-white dark:text-white dark:placeholder:text-white p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2  focus:ring-blue-500 resize-none"></textarea>
                            <?php $__errorArgs = ['description'];
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

                    <!-- دکمه‌های نهایی -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 justify-center items-center gap-4 py-4 text-center">
                        <button type="submit" wire:loading.attr="disabled" wire:target='submitConversion'
                            class="bg-[#184D6C] text-[14px] vazir font-semibold rounded-[8px] px-16 py-4 text-white   transition disabled:opacity-50">
                            <?php if($editingConversionId): ?>
                            <span wire:loading.remove>ویرایش تبدیل ارز</span>
                            <?php else: ?>
                            <span wire:loading.remove>ثبت تبدیل ارز</span>
                            <?php endif; ?>
                            <span wire:loading wire:target="submitConversion"
                                class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <?php if($editingConversionId): ?>
                                <span>ویرایش تبدیل ارز</span>
                                <?php else: ?>
                                <span>ثبت تبدیل ارز</span>
                                <?php endif; ?> </span> </button>

                        <button type="button" wire:click="resetForm" wire:loading.attr="disabled"
                            class="bg-[#184D6C] text-[14px] vazir font-semibold rounded-[8px] px-16 py-4 text-white  transition">
                            <?php if($editingConversionId): ?> انصراف از ویرایش <?php else: ?> انصراف <?php endif; ?>
                        </button>
                    </div>

                </form>
            </div>

            <!-- جدول تراکنش‌های تبدیل ارز -->
            <div class="flex-1 flex flex-col
         dark:border dark:border-white
         dark:bg-black dark:text-white
   bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC]         p-3 md:p-4 lg:p-6
         rounded-[12px]
         w-full max-w-[440px] md:max-w-[410px] lg:max-w-full
         mb-5 mx-auto
         overflow-x-auto">

                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center  p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl inter">تراکنش های تبدیل ارز ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <div class="relative w-full ">
                            <input type="text" wire:model.live="search" placeholder="جستجو..." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[12px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">

                            
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                                <path d="M20 20L22 22" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <svg width="24" height="24" viewBox="0 0 24 24"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <?php if($search): ?>
                            <button wire:click="$set('search', '')"
                                class="absolute left-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- جدول -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-full">
                        <table class="w-full text-sm md:text-base text-left rtl:text-right text-gray-500">
                            <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir">

                                <tr class="whitespace-nowrap">
                                    <th class="px-2 py-3 font-bold w-12">#</th>
                                    <th class="px-2 py-3 font-bold w-32">مشتری</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ ارز برداشت</th>
                                    <th class="px-2 py-3 font-bold w-36">مبلغ ارز دریافت</th>
                                    <th class="px-2 py-3 font-bold w-24">نرخ ارز</th>
                                    <th class="px-2 py-3 font-bold w-36 text-center">توضیحات</th>
                                    <th class="px-2 py-3 font-bold w-28">تاریخ</th>
                                    <th class="px-2 py-3 font-bold w-32 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $conversionTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $conversion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium text-center w-12">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] font-medium w-32">
                                        <div class="truncate" title="<?php echo e($conversion->customer->fullname ?? '-'); ?>">
                                            <?php echo e($conversion->customer->fullname ?? '-'); ?>

                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-white">
                                            <?php echo e($conversion->customer->account_number ?? ''); ?>

                                        </div>
                                    </td>
                                    <td class="px-3 py-3 vazir text-[13px] md:text-[16px] font-medium w-52">
                                        <div class="text-left">
                                            <span class=""><?php echo e(number_format($conversion->buy_amount)); ?> (<?php echo e($this->getCurrencyName($conversion->from_currency)); ?>)</span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[16px] w-44">
                                        <div class="text-left">
                                            <span class=""><?php echo e(number_format($conversion->sell_amount)); ?> (<?php echo e($this->getCurrencyName($conversion->to_currency)); ?>)</span>
                                        </div>
                                    </td>
                                    <td class="px-1 py-3 vazir text-[13px] md:text-[18px] w-44">
                                        <?php echo e(number_format($conversion->currency_rate, 2)); ?>

                                    </td>
                                    <td class="px-2 py-3 vazir text-[13px] md:text-[18px] font-medium w-36">
                                        <div class="text-right truncate" title="<?php echo e($conversion->description); ?>">
                                            <?php echo e(Str::limit($conversion->description, 35)); ?>

                                        </div>
                                    </td>
                                    <td class="px-2 py-3 vazir text-[16px] md:text-[18px] text-center w-28">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium text-[16px]">
                                                <?php echo e(explode(' ', $conversion->transaction_date)[0]); ?>

                                            </div>
                                            <div class="text-gray-500 dark:text-white text-[16px] mt-1">
                                                <?php echo e(\Carbon\Carbon::parse($conversion->created_at)->format('h:i A')); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center w-32">
                                        <div class="flex justify-center gap-2">
                                            <!-- دکمه ویرایش -->
                                            <button wire:click="editConversion(<?php echo e($conversion->id); ?>)"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-blue-100"
                                                title="ویرایش">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit_table.svg')); ?>"
                                                    class="w-7 h-7 dark:hidden" alt="Edit">

                                                <svg width="22" height="22" class="hidden dark:block"
                                                    viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.082 1.83325H8.2487C3.66536 1.83325 1.83203 3.66659 1.83203 8.24992V13.7499C1.83203 18.3333 3.66536 20.1666 8.2487 20.1666H13.7487C18.332 20.1666 20.1654 18.3333 20.1654 13.7499V11.9166"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M14.7027 2.76832L7.4794 9.99165C7.2044 10.2667 6.9294 10.8075 6.8744 11.2017L6.48023 13.9608C6.33357 14.96 7.0394 15.6567 8.03857 15.5192L10.7977 15.125C11.1827 15.07 11.7236 14.795 12.0077 14.52L19.2311 7.29665C20.4777 6.04999 21.0644 4.60165 19.2311 2.76832C17.3977 0.934987 15.9494 1.52165 14.7027 2.76832Z"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path
                                                        d="M13.668 3.8042C14.2821 5.99503 15.9963 7.7092 18.1963 8.33253"
                                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>

                                            </button>

                                            <!-- دکمه حذف -->
                                            <button wire:click="confirmDelete(<?php echo e($conversion->id); ?>)"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-red-100"
                                                title="حذف">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/trash_table.svg')); ?>"
                                                    class="w-8 h-8 dark:hidden" alt="Delete">
                                                <svg width="24" height="24" class="hidden dark:block"
                                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M18.8484 9.13989L18.1984 19.2099C18.0884 20.7799 17.9984 21.9999 15.2084 21.9999H8.78844C5.99844 21.9999 5.90844 20.7799 5.79844 19.2099L5.14844 9.13989"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M10.3281 16.5H13.6581" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9.5 12.5H14.5" stroke="white" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>

                                            <button wire:click="printTransaction(<?php echo e($conversion->id); ?>)"
                                                class="w-10 h-10 flex items-center justify-center rounded-full transition-colors hover:bg-green-100"
                                                title="پرینت PDF">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                    class="w-10 h-10 dark:hidden" alt="Print">
                                                <svg width="30" class="hidden dark:block" height="30"
                                                    viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                                        fill="white" />
                                                </svg>

                                            </button>

                                            <script>
                                                let printListenerRegistered = false;

    document.addEventListener('livewire:init', () => {
        if (printListenerRegistered) return;
        printListenerRegistered = true;

        Livewire.on('print-pdf', (data) => {

            /* 🔹 1. دانلود (با لینک مخفی) */
            const downloadLink = document.createElement('a');
            downloadLink.href = data.url;
            downloadLink.download = '';
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();

            /* 🔹 2. پرینت */
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = data.url;
            document.body.appendChild(iframe);

            iframe.onload = () => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                /* 🔹 3. حذف با تأخیر */
                setTimeout(() => {
                    iframe.remove();
                    downloadLink.remove();
                }, 50000); // ⏱ ۵ ثانیه
            };
        });
    });
                                            </script>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 vazir text-[14px]">
                                        هیچ تراکنش تبدیلی یافت نشد.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- صفحه‌بندی -->
                <?php if($conversionTransactions->hasPages()): ?>
                <div class="mt-4 px-4">
                    <?php echo e($conversionTransactions->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- مودال تأیید حذف -->
    <?php if($confirmDeleteId): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-[#FFFFFF] pt-[21px] pr-[15px] pl-[15px]  rounded-[12px] shadow-xl w-[653px] h-[219.7267608642578px] text-center animate-fadeIn z-50 border-[1px] border-[#E1DED3] relative">
            <!-- دکمه بستن -->
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute left-0 right-4 top-4 h-6 w-6 flex items-center justify-center">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/close.svg')); ?>" alt="بستن" class="w-4 h-4">
            </button>

            <h1 class="text-2xl text-black shabnam font-medium leading-[100%] mt-2">حذف تراکنش تبدیل ارز</h1>
            <hr class="bg-[#E1DED3] mt-4 mx-4">
            <p class="mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این تراکنش را حذف کنید؟</p>
            <div class="flex justify-center gap-4">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-12 text-white text-lg shabnam-fd py-3 bg-[#DD2424] rounded-xl transition hover:bg-red-700">
                    خیر
                </button>
                <button wire:click="deleteConversion"
                    class="px-12 py-3 bg-[#2563EB] text-lg shabnam-fd text-white rounded-xl transition hover:bg-blue-700 flex items-center gap-2">
                    بلی
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <style>
        .scroll-container {
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #f9fafb;
        }

        .scroll-container::-webkit-scrollbar {
            height: 6px;
            margin-top: 20px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f9fafb;
            border-radius: 10px;
            margin: 0 16px 10px 16px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        input[list]::-webkit-calendar-picker-indicator {
            display: none !important;
            -webkit-appearance: none;
        }

        input[list]::-moz-list-button {
            display: none !important;
        }

        input[list]::-ms-clear,
        input[list]::-ms-expand {
            display: none !important;
        }
    </style>
</div><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/conversion-in-account.blade.php ENDPATH**/ ?>