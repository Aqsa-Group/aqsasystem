<div>
    <div class="container mx-auto ">
        <!-- Session Message -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#2B65E5] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('message')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


        <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="fixed top-0 left-0 right-0 w-full z-[9999]  bg-[#DC2626] vazir">
            <div class="h-[80px] w-full flex justify-start items-center px-4">
                <h2 class="text-white vazir text-[18px]">
                    <?php echo e(session('error')); ?>

                </h2>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>




        
        <div class="scroll-container overflow-x-auto whitespace-nowrap py-3 ">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomer): ?>
            <div class="inline-block align-top ml-4 h-auto">
                <div class="flex flex-col    h-[212px] w-[244px] pr-5 pl-5 pt-2 rounded-[12px]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900  bg-[#184D6C]
                backdrop-blur-lg
                border border-white/30 text-white">

                    
                    <div x-data="{ showLargeImage: false, largeImageSrc: '' }">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomer->image): ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(Storage::url($selectedCustomer->image)); ?>"
                                alt="<?php echo e($selectedCustomer->fullname); ?>"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(Storage::url($selectedCustomer->image)); ?>'">
                        </div>
                        <?php else: ?>
                        <div class="flex justify-center mb-2">
                            <img src="<?php echo e(asset('assets/web.jpg')); ?>" alt="<?php echo e($selectedCustomer->fullname); ?>"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white cursor-pointer hover:scale-105 transition-transform"
                                @click="showLargeImage = true; largeImageSrc = '<?php echo e(asset('assets/web.jpg')); ?>'">
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div x-show="showLargeImage" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4"
                            @click.away="showLargeImage = false" @keydown.escape.window="showLargeImage = false">

                            <div class="relative max-w-4xl max-h-[90vh]">
                                
                                <button @click="showLargeImage = false"
                                    class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl z-10">
                                    ✕
                                </button>

                                
                                <img :src="largeImageSrc" alt="<?php echo e($selectedCustomer->fullname); ?>"
                                    class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">

                                
                                <div class="mt-4 text-center text-white">
                                    <p class="text-lg font-semibold"><?php echo e($selectedCustomer->fullname); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomer->phone): ?>
                                    <p class="text-sm text-gray-300"><?php echo e($selectedCustomer->phone); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="mt-6 flex justify-center gap-4">
                                    <a :href="largeImageSrc"
                                        :download="customerName + '_' + new Date().toISOString().split('T')[0] + '.jpg'"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        دانلود عکس
                                    </a>

                                    <button @click="showLargeImage = false"
                                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                                        بستن
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <h1 class="text-[20px] text-white text-center font-bold truncate"
                        title="<?php echo e($selectedCustomer->fullname); ?>">
                        <?php echo e($selectedCustomer->fullname); ?>

                    </h1>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomer->phone): ?>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.9-2-1.3-4.1-1.3-6.2V6zM19 19c-2.1 0-4.2-.4-6.2-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V19z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->phone); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-8-7c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-2.2 0-4 1.8-4 4h8c0-2.2-1.8-4-4-4z" />
                        </svg>
                        <span class="text-white text-[14px] dir-ltr text-left"><?php echo e($selectedCustomer->account_number); ?></span>
                    </div>

                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $currencyName = $currencyItem['name_fa'];
            $cashBalance = $customerCashBalances[$currencyName] ?? 0;
            $bankBalance = $customerBankBalances[$currencyName] ?? 0;
            $totalBalance = $customerTotalBalances[$currencyName] ?? 0;
            ?>

            
            <div class="inline-block align-top ml-4 h-auto ">
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomerId): ?>
            <div class="inline-block align-top ml-4 last:ml-0 min-w-[273px]">
                <div class="flex flex-col h-[212px] w-[244px] pr-5 pl-5 pt-3 rounded-[12px]
                        dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900
                  bg-[#184D6C]
                backdrop-blur-lg
                border border-white/30

    shadow-[0_4px_4px_rgba(24,77,108,0.25)] text-black">
                    <?php
                    $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                    $sourceCurrency = $latestProfitRate->currency_name ?? 'دالر';

                    $totalCashUsd = 0;
                    $totalBankUsd = 0;

                    // نرخ‌های نقدی
                    $exchangeRatesCash = [
                    'افغانی' => $latestProfitRate->afn_buy_cash ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_cash ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_cash ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_cash ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_cash ?? 44,
                    'لیره' => $latestProfitRate->try_buy_cash ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_cash ?? 43,
                    'روپیه' => 7.14,
                    ];

                    // نرخ‌های بانکی
                    $exchangeRatesBank = [
                    'افغانی' => $latestProfitRate->afn_buy_bank ?? 66.20,
                    'دالر' => 1,
                    'تومان' => $latestProfitRate->irr_buy_bank ?? 110000,
                    'یورو' => $latestProfitRate->eur_buy_bank ?? 70,
                    'کلدار' => $latestProfitRate->pkr_buy_bank ?? 32,
                    'درهم' => $latestProfitRate->aed_buy_bank ?? 44,
                    'لیره' => $latestProfitRate->try_buy_bank ?? 60,
                    'یوان' => $latestProfitRate->cny_buy_bank ?? 43,
                    'روپیه' => 7.14,
                    ];

                    /* =====================
                    محاسبه موجودی نقدی
                    ====================== */
                    foreach ($customerCashBalances as $currency => $balance) {
                    if ($currency === 'دالر') {
                    $totalCashUsd += $balance; // دالر مستقیم
                    } elseif (isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                    $totalCashUsd += $balance / $exchangeRatesCash[$currency];
                    }
                    }

                    /* =====================
                    محاسبه موجودی بانکی
                    ====================== */
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>



        <hr class="my-6 border-t border-[#D9D9D9] w-full">

        <!-- Main Content - Form and Table -->
        <div class="flex flex-col lg:flex-row gap-5 mt-4 mx-auto">

            <!-- Remittance Form -->
            <div class="flex flex-col
         dark:bg-black dark:text-white dark:border dark:border-white
         bg-white   border border-[#D7E5EC] shadow-sm backdrop:blur-lg
         mx-auto
         w-full max-w-[420px] lg:max-w-[474px]
         p-[10px]
         h-auto
         rounded-[12px]
         space-y-2">

                <!-- Form Header -->
                <div class="flex flex-row gap-4 justify-center items-center p-[10px]  rounded-[12px] flex-wrap">
                   <div class="flex gap-2">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    </svg>

                    <p class="flex justify-center items-center text-center dark:text-white inter text-xl">
                        <?php echo e($remittanceId ? 'فورم ویرایش برداشت بانکی' : 'فورم ثبت برداشت بانکی'); ?>

                    </p>
                   </div>
                    <div>
                        <button wire:click="toggleAccountType"
                            class="rounded-[8px] p-[10px] px-10 text-white vazir font-semibold
                   whitespace-nowrap transition-colors duration-500 ease-in-out
                   <?php echo e($accountType === 'معاملات داخلی' ? 'bg-[#184D6C]' : 'bg-[#FFFF] border border-[#184D6C] text-black'); ?>">
                            <?php echo e($accountType === 'معاملات داخلی' ? 'معاملات داخلی' : 'معاملات بیرونی'); ?>

                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="submitRemittance">
                    <!-- Account Number and Currency -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Source Account Number -->
                        <div class="flex-1">
                            <div class="relative w-full">
                                <label
                                    class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">نمبرحساب
                                    مشتری</label>
                                <div x-data="{
                                    searchValue: '',
                                    selectedId: <?php if ((object) ('selectedAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'->value()); ?>')<?php echo e('selectedAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedAccount'); ?>')<?php endif; ?>,
                                    customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                    handleSelect(event) {
                                        const selected = this.customers.find(
                                            c => event.target.value === `${c.account_number} - ${c.fullname}`
                                        );
                                        if (selected) {
                                            this.selectedId = selected.id;
                                            this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                            $wire.selectCustomer(selected.id);
                                            $wire.set('search', selected.fullname);
                                        } else {
                                            this.selectedId = null;
                                            this.searchValue = '';
                                            $wire.set('selectedAccount', null);
                                            $wire.set('search', '');
                                        }
                                    },
                                    updateDisplay() {
                                        const selected = this.customers.find(c => c.id === this.selectedId);
                                        this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                    }
                                }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                    class="relative w-full">
                                    <input list="customersList" x-model="searchValue" @change="handleSelect"
                                        placeholder="جستجو یا انتخاب حساب..."
                                        class="w-full dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white  h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                                        autocomplete="off">
                                    <datalist id="customersList">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </datalist>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($selectedAccount)): ?>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['selectedAccount'];
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

                        <!-- Currency Type -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نوع ارز</label>
                            <div class="relative w-full">
                                <select wire:model="currency"
                                    class="w-full dark:text-white dark:bg-black dark:placeholder:text-white dark:border-white   h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500   appearance-none">
                                    <option value="">انتخاب ارز</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c['code']); ?>"><?php echo e($c['name_fa']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['currency'];
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

                    <!-- Amount and Date -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Amount -->
                        <div class="flex-1">
                            <label
                                class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">مقدار</label>
                            <div class="relative w-full">
                                <input type="text" wire:model.live="amount" wire:blur="formatAmount" placeholder="0"
                                    class="w-full dark:bg-black  dark:border-white  h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:text-white"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($amountInWords): ?>
                            <p class="text-sm dark:text-white text-blue-600 mt-2 vazir"><?php echo e($amountInWords); ?></p>
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

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['date'];
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

                    <!-- Time and Tracking Code -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Time -->
                        <div class="lg:w-full" x-data="timePicker()" x-init="init()">
                            <label
                                class="block text-[16px] dark:text-white font-medium text-black mb-1 vazir">ساعت</label>

                            <!-- Input field -->
                            <input type="text" x-model="displayTime" @click="togglePicker()"
                                @keydown.enter.prevent="applyTime()" @keydown.escape="closePicker()"
                                placeholder="2:25:20"
                                class="w-full h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer"
                                readonly x-ref="timeInput" />

                            <!-- Time Picker Modal -->
                            <div x-show="isOpen" x-transition.opacity @keydown.escape.window="closePicker()"
                                @click.away="closePicker()" class="fixed z-50 inset-0 overflow-y-auto"
                                aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;"
                                x-bind:style="isOpen ? 'display: block;' : ''">

                                <div
                                    class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>

                                    <div
                                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="flex justify-between items-center mb-6">
                                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white"
                                                    id="modal-title">
                                                    انتخاب زمان
                                                </h3>
                                                <button @click="closePicker()" type="button"
                                                    class="text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">بستن</span>
                                                    ✕
                                                </button>
                                            </div>

                                            <div class="flex justify-center space-x-6">
                                                <!-- Hours -->
                                                <div class="text-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">ساعت
                                                    </div>
                                                    <div class="flex flex-col items-center space-y-2">
                                                        <button @click="incrementHour()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↑
                                                        </button>
                                                        <div class="relative">
                                                            <input type="number" x-model="hour" @input="updateTime()"
                                                                @keydown.enter.prevent="applyTime()" min="0" max="23"
                                                                class="w-20 text-center text-3xl font-bold bg-transparent border-2 border-gray-200 dark:border-gray-700 rounded-lg py-2 outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                                                x-ref="hourInput" />
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-400 text-sm">H</span>
                                                            </div>
                                                        </div>
                                                        <button @click="decrementHour()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↓
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Divider -->
                                                <div class="flex items-center">
                                                    <span class="text-2xl font-bold text-gray-400">:</span>
                                                </div>

                                                <!-- Minutes -->
                                                <div class="text-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">دقیقه
                                                    </div>
                                                    <div class="flex flex-col items-center space-y-2">
                                                        <button @click="incrementMinute()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↑
                                                        </button>
                                                        <div class="relative">
                                                            <input type="number" x-model="minute" @input="updateTime()"
                                                                @keydown.enter.prevent="applyTime()" min="0" max="59"
                                                                class="w-20 text-center text-3xl font-bold bg-transparent border-2 border-gray-200 dark:border-gray-700 rounded-lg py-2 outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                                                x-ref="minuteInput" />
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-400 text-sm">M</span>
                                                            </div>
                                                        </div>
                                                        <button @click="decrementMinute()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↓
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Divider -->
                                                <div class="flex items-center">
                                                    <span class="text-2xl font-bold text-gray-400">:</span>
                                                </div>

                                                <!-- Seconds -->
                                                <div class="text-center">
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">ثانیه
                                                    </div>
                                                    <div class="flex flex-col items-center space-y-2">
                                                        <button @click="incrementSecond()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↑
                                                        </button>
                                                        <div class="relative">
                                                            <input type="number" x-model="second" @input="updateTime()"
                                                                @keydown.enter.prevent="applyTime()" min="0" max="59"
                                                                class="w-20 text-center text-3xl font-bold bg-transparent border-2 border-gray-200 dark:border-gray-700 rounded-lg py-2 outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                                                                x-ref="secondInput" />
                                                            <div
                                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-400 text-sm">S</span>
                                                            </div>
                                                        </div>
                                                        <button @click="decrementSecond()" type="button"
                                                            class="w-12 h-12 flex items-center justify-center text-2xl font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors">
                                                            ↓
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-8 flex justify-end space-x-3">
                                                <button @click="resetTime()" type="button"
                                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                                    بازنشانی
                                                </button>
                                                <button @click="applyTime()" type="button"
                                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    تأیید و بستن
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['clock'];
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

                        <script>
                            function timePicker() {
    return {
        isOpen: false,
        hour: '00',
        minute: '00',
        second: '00',
        displayTime: '',
        originalTime: '',
        
        init() {
            // Initialize with current time or existing value
            if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock')) {
                const time = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock').split(':');
                this.hour = time[0] || '00';
                this.minute = time[1] || '00';
                this.second = time[2] || '00';
                this.originalTime = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock');
                this.updateDisplay();
            } else {
                const now = new Date();
                this.hour = String(now.getHours()).padStart(2, '0');
                this.minute = String(now.getMinutes()).padStart(2, '0');
                this.second = String(now.getSeconds()).padStart(2, '0');
                this.updateDisplay();
                this.originalTime = this.displayTime;
            }
        },
        
        togglePicker() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                // Save current time
                this.originalTime = window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('clock') || this.displayTime;
                // Focus on hour input when opened
                setTimeout(() => {
                    this.$refs.hourInput.focus();
                    this.$refs.hourInput.select();
                }, 100);
            }
        },
        
        closePicker() {
            if (this.isOpen) {
                // Revert to original time if not applied
                if (this.originalTime && !this.displayTime.includes('NaN')) {
                    const time = this.originalTime.split(':');
                    this.hour = time[0] || '00';
                    this.minute = time[1] || '00';
                    this.second = time[2] || '00';
                    this.updateTime();
                }
                this.isOpen = false;
            }
        },
        
        incrementHour() {
            let h = parseInt(this.hour);
            if (isNaN(h)) h = 0;
            h = (h + 1) % 24;
            this.hour = String(h).padStart(2, '0');
            this.updateTime();
            this.$refs.hourInput.focus();
            this.$refs.hourInput.select();
        },
        
        decrementHour() {
            let h = parseInt(this.hour);
            if (isNaN(h)) h = 0;
            h = (h - 1 + 24) % 24;
            this.hour = String(h).padStart(2, '0');
            this.updateTime();
            this.$refs.hourInput.focus();
            this.$refs.hourInput.select();
        },
        
        incrementMinute() {
            let m = parseInt(this.minute);
            if (isNaN(m)) m = 0;
            m = (m + 1) % 60;
            this.minute = String(m).padStart(2, '0');
            this.updateTime();
            this.$refs.minuteInput.focus();
            this.$refs.minuteInput.select();
        },
        
        decrementMinute() {
            let m = parseInt(this.minute);
            if (isNaN(m)) m = 0;
            m = (m - 1 + 60) % 60;
            this.minute = String(m).padStart(2, '0');
            this.updateTime();
            this.$refs.minuteInput.focus();
            this.$refs.minuteInput.select();
        },
        
        incrementSecond() {
            let s = parseInt(this.second);
            if (isNaN(s)) s = 0;
            s = (s + 1) % 60;
            this.second = String(s).padStart(2, '0');
            this.updateTime();
            this.$refs.secondInput.focus();
            this.$refs.secondInput.select();
        },
        
        decrementSecond() {
            let s = parseInt(this.second);
            if (isNaN(s)) s = 0;
            s = (s - 1 + 60) % 60;
            this.second = String(s).padStart(2, '0');
            this.updateTime();
            this.$refs.secondInput.focus();
            this.$refs.secondInput.select();
        },
        
        updateTime() {
            // Validate and format
            let h = parseInt(this.hour);
            let m = parseInt(this.minute);
            let s = parseInt(this.second);
            
            if (isNaN(h) || h < 0) h = 0;
            if (isNaN(h) || h > 23) h = 23;
            if (isNaN(m) || m < 0) m = 0;
            if (isNaN(m) || m > 59) m = 59;
            if (isNaN(s) || s < 0) s = 0;
            if (isNaN(s) || s > 59) s = 59;
            
            this.hour = String(h).padStart(2, '0');
            this.minute = String(m).padStart(2, '0');
            this.second = String(s).padStart(2, '0');
            
            this.updateDisplay();
            // Update Livewire model in real-time
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('clock', this.displayTime);
        },
        
        updateDisplay() {
            this.displayTime = `${this.hour}:${this.minute}:${this.second}`;
        },
        
        applyTime() {
            // Final validation before applying
            this.updateTime();
            
            // Ensure valid format
            if (!this.displayTime.includes('NaN')) {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('clock', this.displayTime);
                this.isOpen = false;
                this.$refs.timeInput.focus();
            }
        },
        
        resetTime() {
            this.hour = '00';
            this.minute = '00';
            this.second = '00';
            this.updateTime();
            this.$refs.hourInput.focus();
            this.$refs.hourInput.select();
        }
    }
}
                        </script>

                        <style>
                            /* Hide number input arrows */
                            input[type="number"]::-webkit-inner-spin-button,
                            input[type="number"]::-webkit-outer-spin-button {
                                -webkit-appearance: none;
                                margin: 0;
                            }

                            input[type="number"] {
                                -moz-appearance: textfield;
                            }

                            /* Fix for modal backdrop */
                            .fixed {
                                position: fixed;
                            }

                            /* Better transition */
                            [x-cloak] {
                                display: none;
                            }
                        </style>
                        <!-- Tracking Code -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">کد
                                رهگیری</label>
                            <input type="text" wire:model="tracking_code" placeholder="5155221034568"
                                class="w-full  h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white cursor-pointer" />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tracking_code'];
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

                    <!-- Source and Destination Banks -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                بانک مبدا
                            </label>

                            <input type="text" wire:model="from_bank" list="banks-list" placeholder="بانک مبدا"
                                class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] dark:bg-black dark:border-white dark:text-white">

                            <datalist id="banks-list">
                                <option value="بانک ملی ایران">
                                <option value="بانک ملت">
                                <option value="بانک صادرات">
                                <option value="بانک سپه">
                                <option value="بانک تجارت">
                                <option value="بانک پاسارگاد">
                                <option value="بانک سامان">
                                <option value="بانک پارسیان">
                                <option value="HSBC">
                                <option value="Standard Chartered">
                                <option value="Citi Bank">
                                <option value="Deutsche Bank">
                                <option value="Bank of China">
                                <option value="Emirates NBD">
                                <option value="QNB">
                                <option value="Al Rajhi Bank">
                            </datalist>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['from_bank'];
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

                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">
                                بانک مقصد
                            </label>

                            <input type="text" wire:model="to_bank" list="banks-list" placeholder="بانک مقصد"
                                class="w-full h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] dark:bg-black dark:border-white dark:text-white">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['to_bank'];
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
                    <!-- Source and Destination Account Numbers -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Source Account Number (Display only) -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شماره
                                حساب مبدا</label>
                            <div class="relative">
                                <div
                                    class="flex items-center dark:bg-black bg-[#EFF6F9]   rounded-[12px] h-[60px] px-3">
                                    <input dir="ltr" type="text" wire:model="source_account_last_four" maxlength="4"
                                        placeholder="1234"
                                        class="w-12 dark:bg-black dark:border-white  dark:text-white  h-full bg-white text-center border-0 outline-none font-mono"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <span class="text-gray-500 vazir whitespace-nowrap mr-2">- xxxx - xxxx - xxxx</span>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['source_account_last_four'];
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


                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accountType === 'معاملات داخلی'): ?>

                        <!-- Destination Account -->
                        <div class="relative w-full">
                            <label class="block text-[16px] font-medium text-black mb-1 vazir">نمبرحساب کارت
                                برداشت</label>
                            <div x-data="{
                                searchValue: '',
                                selectedId: <?php if ((object) ('toAccount') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('toAccount'->value()); ?>')<?php echo e('toAccount'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('toAccount'); ?>')<?php endif; ?>,
                                customers: <?php echo \Illuminate\Support\Js::from($customers)->toHtml() ?>,
                                handleSelect(event) {
                                    const selected = this.customers.find(
                                        c => event.target.value === `${c.account_number} - ${c.fullname}`
                                    );
                                    if (selected) {
                                        this.selectedId = selected.id;
                                        this.searchValue = `${selected.account_number} - ${selected.fullname}`;
                                        $wire.selectToAccount(selected.id);
                                    } else {
                                        this.selectedId = null;
                                        this.searchValue = '';
                                        $wire.set('toAccount', null);
                                    }
                                },
                                updateDisplay() {
                                    const selected = this.customers.find(c => c.id === this.selectedId);
                                    this.searchValue = selected ? `${selected.account_number} - ${selected.fullname}` : '';
                                }
                            }" x-init="updateDisplay(); $watch('selectedId', () => updateDisplay())"
                                class="relative w-full">
                                <input list="customersList2" x-model="searchValue" @change="handleSelect"
                                    placeholder="  انتخاب حساب مقصد"
                                    class="w-full dark:bg-black dark:border-white dark:placeholder:text-white h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2 focus:ring-blue-500"
                                    autocomplete="off">
                                <datalist id="customersList2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer['account_number']); ?> - <?php echo e($customer['fullname']); ?>">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </datalist>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($toAccount)): ?>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="#929897"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['toAccount'];
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
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accountType === 'معاملات بیرونی'): ?>

                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">شماره
                                حساب مقصد</label>
                            <div class="relative">
                                <div
                                    class="flex items-center dark:bg-black bg-[#EFF6F9]   rounded-[12px] h-[60px] px-3">
                                    <input dir="ltr" type="text" wire:model="distantion_account_last_four" maxlength="4"
                                        placeholder="1234"
                                        class="w-12 dark:bg-black dark:border-white  dark:text-white  h-full bg-white text-center border-0 outline-none font-mono"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <span class="text-gray-500 vazir whitespace-nowrap mr-2">- xxxx - xxxx - xxxx</span>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['distantion_account_last_four'];
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
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>


                    <!-- Zone and Beneficiary -->
                    <div class="mt-2  grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 w-full gap-3">
                        <!-- Zone -->


                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accountType === 'معاملات داخلی'): ?>
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir">درج زون
                                ها</label>

                            <div class="relative">
                                <select wire:model="zone"
                                    class="w-full h-[60px] p-3 rounded-[12px]  focus:ring-2 bg-[#EFF6F9] focus:ring-blue-500 dark:bg-black dark:border-white dark:text-white appearance-none"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <option value="">انتخاب زون</option>
                                    <option value="<?php echo e(Auth::guard('sarafi')->user()->zone); ?>">
                                        <?php echo e(Auth::guard('sarafi')->user()->zone); ?>

                                    </option>
                                </select>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['zone'];
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
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accountType === 'معاملات داخلی'): ?>

                        <!-- Beneficiary Name -->
                        <div class="lg:w-full">
                            <label class="block text-[16px] font-medium dark:text-white text-black mb-1 vazir"> نام کارت
                                برداشت</label>
                            <input type="text" wire:model="giver_name" placeholder="مجید مرتضی"
                                class="w-full   h-[60px] p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2  focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-gray-600 dark:text-white cursor-pointer" />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['giver_name'];
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
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>


                    <!-- Remittance Description -->
                    <div class="mt-3 flex gap-3">
                        <div class="w-full">
                            <textarea wire:model="description" rows="3" placeholder="شرح برد..."
                                class="w-full  p-3 rounded-[12px] bg-[#EFF6F9] focus:ring-2  focus:ring-blue-500 dark:bg-black dark:border-white dark:placeholder:text-white dark:text-white resize-none"></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
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
                    <!-- File Upload -->
                    <div class="mt-2 flex gap-3">
                        <div class="w-full dark:bg-black dark:border-white">
                            <div x-data="{
            files: [],
            isUploading: false,
            uploadedFileName: <?php if ((object) ('remittance_image') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('remittance_image'->value()); ?>')<?php echo e('remittance_image'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('remittance_image'); ?>')<?php endif; ?>.defer,
            init() {
                // گوش دادن به رویدادهای Livewire برای آپلود
                window.addEventListener('upload:start', () => {
                    this.isUploading = true;
                });
                window.addEventListener('upload:finish', () => {
                    this.isUploading = false;
                });
                window.addEventListener('upload:error', () => {
                    this.isUploading = false;
                });
            }
        }" x-on:drop.prevent="
            files = $event.dataTransfer.files; 
            $wire.upload('remittance_image', files[0], () => {
                uploadedFileName = files[0]?.name;
            })
        " x-on:dragover.prevent :class="{
            'border-green-500 bg-green-50': uploadedFileName && !isUploading,
            'border-green-500 bg-green-50 dark:bg-black': uploadedFileName && !isUploading,
                                    'border-[#184D6C] bg-blue-50 dark:bg-black': isUploading,
                                    'border-[#184D6C] bg-white dark:bg-black': !uploadedFileName && !isUploading
        }" class="w-full h-[46px] p-3 rounded-[12px] border-2 border-dashed focus:ring-2 dark:bg-black dark:border-white dark:text-white flex flex-col justify-center items-center text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 relative"
                                x-on:click="$refs.fileInput.click()">

                                <!-- حالت آپلود در حال انجام -->
                                <template x-if="isUploading">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 dark:bg-black dark:border-white dark:border h-12 mb-2 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                        </div>
                                        <h1 class="font-vazir text-blue-600 dark:text-blue-300 text-[16px]">در حال
                                            آپلود...</h1>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">لطفا منتظر
                                            بمانید</p>
                                    </div>
                                </template>

                                <!-- حالت آپلود موفق -->
                                <template x-if="!isUploading && uploadedFileName">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 h-12 mb-2 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h1 class="font-vazir text-green-600 dark:text-green-300 text-[16px]">آپلود موفق
                                        </h1>
                                        <p class="font-vazir text-gray-600 dark:text-gray-300 text-sm mt-1 truncate max-w-full"
                                            x-text="uploadedFileName"></p>
                                        <button type="button"
                                            x-on:click.stop="uploadedFileName = null; $wire.set('remittance_image', null)"
                                            class="mt-2 text-red-500 hover:text-red-700 text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            حذف فایل
                                        </button>
                                    </div>
                                </template>

                                <!-- حالت اولیه (بدون آپلود) -->
                                <template x-if="!isUploading && !uploadedFileName">
                                    <div class="flex justify-between w-full  items-center ">
                                        <div class="flex">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.5 17.5H7.5C5.14298 17.5 3.96447 17.5 3.23223 16.7678C2.5 16.0355 2.5 14.857 2.5 12.5M17.5 12.5C17.5 14.857 17.5 16.0355 16.7678 16.7678C16.5179 17.0176 16.2162 17.1822 15.8333 17.2906"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.99984 13.3333V2.5M9.99984 2.5L13.3332 6.14583M9.99984 2.5L6.6665 6.14583"
                                                    stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            <h1 class="font-vazir text-gray-600 dark:text-white text-[16px]">آپلود فایل
                                            </h1>
                                        </div>
                                        <p class="font-vazir text-gray-500 dark:text-white text-sm mt-1">
                                            JPG, PNG,WEBP</p>
                                    </div>
                                </template>

                                <input type="file" class="hidden" x-ref="fileInput" accept=".jpg,.jpeg,.png,.pdf,.webp"
                                    x-on:change="
                       if ($event.target.files[0]) {
                           $wire.upload('remittance_image', $event.target.files[0], () => {
                               uploadedFileName = $event.target.files[0]?.name;
                           });
                       }
                   ">
                            </div>

                            <!-- نمایش خطا -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['remittance_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="mt-2 flex items-center gap-2 text-red-500 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><?php echo e($message); ?></span>
                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



                            <!-- نمایش فایل ذخیره شده (در حالت ویرایش) -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remittance_image && is_string($remittance_image)): ?>
                            <div
                                class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-blue-700 text-sm">فایل قبلاً آپلود شده</span>
                                </div>
                                <a href="<?php echo e(Storage::url($remittance_image)); ?>" target="_blank"
                                    class="text-blue-500 hover:text-blue-700 text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    مشاهده
                                </a>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 py-4 justify-center items-center text-center flex-wrap">
                        <button type="submit" wire:loading.attr='disabled' wire:target='submitRemittance'
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white whitespace-nowrap">


                            <span wire:loading.remove wire:target="submitRemittance">
                                <?php echo e($remittanceId ? 'بروزرسانی' : 'ثبت'); ?>

                            </span>

                            <span wire:loading wire:target="submitRemittance"
                                class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                در حال ثبت
                            </span>
                        </button>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$remittanceId): ?>
                        <button type="button" wire:click="submitAndPrint" wire:loading.attr='disabled'
                            wire:target='submitAndPrint'
                            class="bg-[#184D6C] text-[14px] text-center justify-center vazir font-semibold rounded-[8px]  flex px-12 py-3 text-white whitespace-nowrap">
                            <span wire:loading.remove wire:target='submitAndPrint'>
                                ثبت و چاپ

                            </span>

                            <span wire:loading wire:target="submitAndPrint"
                                class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                در حال ثبت و چاپ
                            </span>
                        </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <button type="button" wire:click="cancel"
                            class="bg-[#184D6C] text-[16px] vazir font-semibold rounded-[8px] px-12 py-3 text-white">
                            <?php echo e($remittanceId ? 'لغو ویرایش' : 'انصراف'); ?>

                        </button>
                    </div>
                </form>
            </div>

            <!-- Remittances Table -->
            <div class="flex-1 flex flex-col
         dark:border dark:border-white
         dark:bg-black dark:text-white
            bg-white shadow-sm backdrop-blur-2xl border border-[#D7E5EC]

         p-3 md:p-4 lg:p-6
         rounded-[12px]
         w-full max-w-[440px] md:max-w-[410px] lg:max-w-full
         mb-5 mx-auto
         overflow-x-auto" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                <!-- Table Header -->
                <div
                    class="grid grid-cols-1 md:grid-cols-1 xl:grid-cols-2 justify-between items-center  p-3 md:p-4 rounded-[12px] mb-3 gap-3">
                    <h1 class="text-lg md:text-xl lg:text-2xl vazir">برد های ثبت شده</h1>

                    <div class="flex items-center gap-3">
                        <!-- Selected Customer Filter -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomerId): ?>
                        <?php
                        $selectedCustomer = \App\Models\Sarafi\Customer::find($selectedCustomerId);
                        ?>
                        <div class="bg-blue-100 px-3 py-2 rounded-lg flex items-center gap-2">
                            <span class="text-blue-700 vazir">فیلتر: <?php echo e($selectedCustomer->fullname ?? ''); ?></span>
                            <button wire:click="clearFilter" class="text-red-500 hover:text-red-700 text-lg">
                                ✕
                            </button>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Search Box -->
                        <div class="relative w-full">
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
                            <svg width="24" height="24"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-5 h-5 md:w-6 md:h-6 hidden dark:block"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                            <button wire:click="clearSearchAndFilter"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search && count($filteredCustomers) > 0 && !$selectedCustomerId): ?>
                            <ul
                                class="absolute z-50 w-full bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filteredCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li wire:click="selectCustomer(<?php echo e($customer->id); ?>)"
                                    class="px-3 py-2 hover:bg-blue-100 cursor-pointer flex justify-between items-center">
                                    <span><?php echo e($customer->fullname); ?></span>
                                    <span class="text-gray-500 text-sm"><?php echo e($customer->account_number); ?></span>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto w-full">
                    <div class="max-h-[680px] overflow-y-auto min-w-[890px]">
                        <table
                            class="w-full text-sm  md:text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="sticky top-0 bg-white dark:bg-black text-black dark:text-white text-[14px] md:text-[16px] vazir whitespace-nowrap">
                                <th class="px-4 py-4 font-bold w-16">#</th>
                                <th class="px-4 py-4 font-bold w-48">حساب مشتری</th>
                                <th class="px-4 py-4 font-bold w-32">از کارت</th>
                                <th class="px-4 py-4 font-bold w-40">مبلغ</th>
                                <th class="px-4 py-4 font-bold w-32">واحد</th>
                                <th class="px-4 py-4 font-bold w-80 text-center">توضیحات</th>
                                <th class="px-4 py-4 font-bold w-40">تاریخ</th>
                                <th class="px-4 py-4 font-bold w-48 text-center">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $remittances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $remittance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="text-black border-b  dark:text-white border-[#D9D9D9]
                               odd:bg-[#EFF6F9] even:bg-white dark:odd:bg-[#1E293B] dark:even:bg-black
                               transition-colors">
                                    <td class="px-2 py-4 vazir text-[14px] md:text-[16px] font-medium text-center w-16">
                                        <?php echo e($key + 1); ?>

                                    </td>
                                    <td class="px-4 py-2 vazir text-[14px] md:text-[16px] font-medium w-48">
                                        <?php echo e($remittance->customer->fullname ?? '-'); ?>

                                    </td>
                                    <td
                                        class="px-2 py-2 vazir text-[14px] md:text-[16px] font-medium w-32 whitespace-nowrap">
                                        <?php echo e($remittance->recipient->fullname ?? $remittance->giver_name); ?>

                                    </td>
                                    <td class="px-2 py-2 vazir text-[14px] md:text-[16px] font-medium w-40">
                                        <?php echo e(number_format($remittance->amount)); ?>

                                    </td>
                                    <td class="px-4 py-2 vazir text-[14px] md:text-[16px] font-medium w-32">
                                        <?php echo e(collect($currencies)->firstWhere('code',
                                        $remittance->currency)['name_fa'] ??
                                        $remittance->currency); ?>

                                    </td>

                                    <td class="px-4 py-2 vazir text-[14px] md:text-[16px] font-medium text-center w-80">
                                        <div class="space-y-1 text-right">
                                            <p class="text-sm">کد رهگیری: <?php echo e($remittance->tracking_code); ?>

                                            </p>
                                            <p class="text-sm">زون: <?php echo e($remittance->zone); ?></p>
                                            <p class="text-sm">تفصیلات: <?php echo e($remittance->description); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 vazir text-[14px] md:text-[16px] text-center w-40">
                                        <div class="whitespace-nowrap">
                                            <div class="font-medium">
                                                <?php echo e(explode(' ',$remittance->date)[0]); ?>

                                            </div>
                                            <div class="text-gray-500 dark:text-white  text-sm mt-1">
                                                <?php echo e($remittance->clock); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2 text-center w-[68]">
                                        <div class="flex justify-center gap-3">
                                            <!-- Edit Button -->
                                            <button wire:click="edit(<?php echo e($remittance->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
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

                                            <!-- Delete Button -->
                                            <button wire:click="confirmDelete(<?php echo e($remittance->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
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

                                            <!-- Print Button -->
                                            <button wire:click="print(<?php echo e($remittance->id); ?>)"
                                                class="w-12 h-12 flex items-center justify-center rounded-full transition-colors"
                                                title="پرینت">
                                                <img src="<?php echo e(asset('assets/sarafi/all_icon/print_table.svg')); ?>"
                                                    class="w-10 h-10 dark:hidden" alt="Print">
                                                <svg width="30" class="hidden dark:block" height="30"
                                                    viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10.7714 25.0001C10.2156 25.0001 9.74016 24.8022 9.34516 24.4063C8.95016 24.0105 8.75224 23.5359 8.75141 22.9826V20.0001H6.49141C5.93641 20.0001 5.46141 19.8022 5.06641 19.4063C4.67141 19.0105 4.47349 18.5355 4.47266 17.9813V13.2688C4.47266 12.5605 4.71307 11.9672 5.19391 11.4888C5.67474 11.0088 6.26766 10.7688 6.97266 10.7688H23.0302C23.7385 10.7688 24.3322 11.0088 24.8114 11.4888C25.2906 11.9688 25.5302 12.5622 25.5302 13.2688V17.9813C25.5302 18.5363 25.3327 19.0113 24.9377 19.4063C24.5427 19.8013 24.0672 19.9992 23.5114 20.0001H21.2514V22.9813C21.2514 23.5363 21.0535 24.0113 20.6577 24.4063C20.2618 24.8013 19.7868 24.9992 19.2327 25.0001H10.7714ZM6.49141 18.7501H8.75141C8.78391 18.2226 8.99307 17.7701 9.37891 17.3926C9.76474 17.0159 10.2289 16.8276 10.7714 16.8276H19.2327C19.7743 16.8276 20.2381 17.0163 20.6239 17.3938C21.0097 17.7705 21.2189 18.2226 21.2514 18.7501H23.5114C23.7356 18.7501 23.9197 18.678 24.0639 18.5338C24.2081 18.3897 24.2802 18.2055 24.2802 17.9813V13.2688C24.2802 12.9155 24.1606 12.6188 23.9214 12.3788C23.6822 12.1388 23.3852 12.0188 23.0302 12.0188H6.97266C6.61849 12.0188 6.32182 12.1388 6.08266 12.3788C5.84349 12.6188 5.72349 12.9159 5.72266 13.2701V17.9813C5.72266 18.2055 5.79474 18.3897 5.93891 18.5338C6.08307 18.678 6.26724 18.7501 6.49141 18.7501ZM20.0014 10.7701V7.78758C20.0014 7.56258 19.9293 7.37841 19.7852 7.23508C19.641 7.09091 19.4568 7.01883 19.2327 7.01883H10.7702C10.546 7.01883 10.3618 7.09091 10.2177 7.23508C10.0735 7.37925 10.0014 7.56341 10.0014 7.78758V10.7688H8.75141V7.78758C8.75141 7.23258 8.94932 6.75716 9.34516 6.36133C9.74016 5.9655 10.2152 5.76758 10.7702 5.76758H19.2327C19.7877 5.76758 20.2627 5.9655 20.6577 6.36133C21.0535 6.75716 21.2514 7.23216 21.2514 7.78633V10.7688L20.0014 10.7701ZM22.0214 15.1451C22.3756 15.1451 22.6722 15.0251 22.9114 14.7851C23.1506 14.5451 23.2706 14.2484 23.2714 13.8951C23.2722 13.5417 23.1522 13.2447 22.9114 13.0038C22.6706 12.763 22.3739 12.643 22.0214 12.6438C21.6689 12.6447 21.3718 12.7647 21.1302 13.0038C20.8885 13.243 20.7689 13.5401 20.7714 13.8951C20.7739 14.2501 20.8935 14.5467 21.1302 14.7851C21.3668 15.0234 21.6639 15.1434 22.0214 15.1451ZM20.0014 22.9801V18.8463C20.0014 18.6213 19.9293 18.4367 19.7852 18.2926C19.641 18.1484 19.4568 18.0763 19.2327 18.0763H10.7702C10.546 18.0763 10.3618 18.1484 10.2177 18.2926C10.0735 18.4376 10.0014 18.6222 10.0014 18.8463V22.9813C10.0014 23.2055 10.0735 23.3897 10.2177 23.5338C10.3618 23.678 10.5464 23.7501 10.7714 23.7501H19.2327C19.4568 23.7501 19.641 23.678 19.7852 23.5338C19.9293 23.3897 20.0014 23.2051 20.0014 22.9801ZM6.49141 12.0201H5.72266H24.2802H6.49141Z"
                                                        fill="white" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-gray-500 py-8 text-lg">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCustomerId): ?>
                                        هیچ بردی برای این مشتری یافت نشد
                                        <?php else: ?>
                                        هیچ بردگی ای یافت نشد
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($confirmDeleteId): ?>
    <?php
    $remittance = \App\Models\Sarafi\Remittances::find($confirmDeleteId);
    $isApproved = $remittance && $remittance->state == 1;
    ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6 text-center animate-fadeIn border border-gray-200 relative">
            <button wire:click="$set('confirmDeleteId', null)"
                class="absolute right-2     top-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6 right-0 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="mb-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isApproved): ?>
                <svg class="w-16 h-16 mx-auto right-0 text-red-500 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h2 class="text-xl font-bold text-gray-800 mb-2">حذف حواله تایید شده</h2>
                <p class="text-gray-600 mb-4">این حواله قبلاً تایید شده است. آیا مطمئن هستید می‌خواهید آن را حذف
                    کنید؟
                </p>
                <p class="text-sm text-orange-600 bg-orange-50 p-2 rounded-lg">
                    ⚠️ توجه: این عمل باعث برگشت تمام تراکنش‌ها و تغییرات مربوطه خواهد شد.
                </p>
                <?php else: ?>
                <h1 class="text-2xl text-black shabnam font-medium leading-[100%] ">
                    حذف حــــواله</h1>
                <hr class="bg-[#E1DED3] mt-8">
                <p class=" mb-6 text-xl shabnam mt-5">آیا مطمئن هستید می خواهید این
                    حــــواله را حذف کنید؟</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isApproved): ?>
            <div class="mb-4">
                <p class="text-sm text-gray-500 text-right">
                    عملیات برگشت شامل:
                </p>
                <ul class="text-sm text-gray-600 text-right space-y-1 mt-2">
                    <li>• کاهش موجودی صندوق بانکی</li>
                    <li>• تنظیم مجدد موجودی مشتریان</li>
                </ul>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex justify-center gap-3  items-center text-center">
                <button wire:click="$set('confirmDeleteId', null)"
                    class="px-16 py-3 bg-[#2563EB] text-center text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    انصراف
                </button>
                <button wire:click="deleteConfirmed"
                    class="px-16 py-3  <?php echo e($isApproved ? 'bg-red-600 hover:bg-red-700' : 'bg-[#DD2424] hover:bg-red-700'); ?> text-white text-sm text-center font-medium rounded-lg transition-colors flex items-center gap-2">
                    <?php echo e($isApproved ? 'حذف و برگشت' : 'حذف'); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
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
</div>


<!-- Event Alert -->
<?php $__env->startPush('script'); ?>
<script>
    window.addEventListener('report-alert', event => {
        alert(event.detail.message);
    });
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/withdraw-bank.blade.php ENDPATH**/ ?>