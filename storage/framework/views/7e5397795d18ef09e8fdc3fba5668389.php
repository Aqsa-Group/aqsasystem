<div class="border-[#8C8C8C] min-h-screen" style="font-family: 'header';">

    <h1 class="mb-8 text-3xl font-bold text-gray-700  dark:text-white"><?php echo e(__('messages.page_title')); ?></h1>

   <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 p-2">
    
    <!-- رسید/بردگی -->
    <a href="<?php echo e(route('sarafi.transactions')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <i class="fa-solid fa-wallet text-blue-600 text-xl"></i>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    <?php echo e(__('messages.recipt/withdraw')); ?>

                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    تراکنش‌های مالی
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 to-cyan-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

    <!-- انتقال -->
    <a href="<?php echo e(route('sarafi.account_to_account')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <i class="fa-solid fa-arrow-right-arrow-left text-emerald-600 text-xl"></i>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    <?php echo e(__('messages.transfer')); ?>

                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    انتقال بین حساب‌ها
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-green-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

    <!-- خرید و فروش ارز و صندوق -->
    <a href="<?php echo e(route('sarafi.buy-sell-currency')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <div class="text-orange-600 text-xl">
                            <i class="fa-brands fa-bitcoin"></i>
                        </div>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    <?php echo e(__('messages.selling')); ?>

                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    خرید و فروش ارز
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

    <!-- حساب تبدیل -->
    <a href="<?php echo e(route('sarafi.conversion.in.account')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <i class="fa-solid fa-exchange-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    <?php echo e(__('messages.coversion_account')); ?>

                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    تبدیل ارز در حساب
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-400 to-indigo-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

    <!-- انتقال تبدیل -->
    <a href="<?php echo e(route('sarafi.conversion-transfer')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-rose-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <i class="fa-solid fa-hand-holding-dollar text-rose-600 text-xl"></i>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    <?php echo e(__('messages.coversion_transfer')); ?>

                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    انتقال با تبدیل ارز
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-400 to-pink-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

    <!-- رسید بانکی -->
    <a href="<?php echo e(route('sarafi.remittance')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-cyan-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <i class="fa-solid fa-book-open text-cyan-600 text-xl"></i>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    رسید بانکی
                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    دریافت حواله بانکی
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-400 to-teal-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

    <!-- برد بانکی -->
    <a href="<?php echo e(route('sarafi.withdrawbank')); ?>" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 p-4 shadow-lg transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <div class="relative flex flex-col items-center justify-center text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-3 shadow-inner">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg">
                        <i class="fa-solid fa-file-invoice-dollar text-indigo-600 text-xl"></i>
                    </div>
                </div>
                
                <span class="text-sm font-semibold text-white whitespace-nowrap">
                    برد بانکی
                </span>
                
                <div class="mt-2 text-xs text-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    برداشت از حساب بانکی
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-400 to-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </div>
    </a>

</div>

    <div x-data="{ activeTab: <?php if ((object) ('activeTab') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'->value()); ?>')<?php echo e('activeTab'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'); ?>')<?php endif; ?> }" class="mt-12">
        <div class="flex justify-start gap-6 border-b  dark:border-white border-[#2563EBB0]">
            <a href="#" @click.prevent="activeTab = 'general'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'general'
                    ?
                    'bg-white dark:bg-black border-x border-t dark:border-white dark:text-white  border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 dark:text-white/30 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                <?php echo e(__('messages.tab_general')); ?>

            </a>

            <a href="#" @click.prevent="activeTab = 'safes'" class="px-5 py-2 font-bold transition rounded-t-lg" :class="activeTab === 'safes'
                    ?
                    'bg-white dark:bg-black border-x border-t d  dark:border-white dark:text-white border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                <?php echo e(__('messages.tab_safes')); ?>

            </a>

            <a href="#" @click.prevent="activeTab = 'account_safe'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'account_safe'
            ?
            'bg-white dark:bg-black border-x border-t dark:border-white dark:text-white border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
            'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                <?php echo e(__('messages.account_safes')); ?>

            </a>
        </div>

        <div class="p-6 bg-white dark:bg-black rounded-b-xl shadow-sm mt-2" :class="(activeTab === 'general') 
                      ? '' 
                      : 'bg-white'">

         <template x-if="activeTab === 'general'">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-4">
        
        <!-- تعداد کاربران -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-4.201V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2h16a2 2 0 002-2v-3.5" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_users')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight"><?php echo e($UserCount); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        </svg>
                        <span>کل کاربران سیستم</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- تعداد مشتریان -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-green-700 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-emerald-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_customers')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight"><?php echo e($customerCount); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>مشتریان فعال</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- تراکنش‌های امروز -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-700 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-purple-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_today_transactions')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight"><?php echo e($TransactionCount); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>تراکنش‌های امروز</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- حواله‌ها -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 via-orange-600 to-red-600 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-orange-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_remittances')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight"><?php echo e($remittancecount); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        <span>کل حواله‌های انجام شده</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- حواله‌های در انتظار -->
        <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
        <a href="<?php echo e(route('sarafi.remittance-approval')); ?>" class="block">
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 via-pink-600 to-rose-700 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-rose-500/30 <?php if($waitting > 0): ?> cursor-pointer hover:border-2 hover:border-white/30 <?php endif; ?>">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
            <div class="absolute top-3 right-3">
                <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-yellow-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_pending_transactions')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight"><?php echo e($waitting); ?></p>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex items-center text-sm text-white/80">
                            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>در انتظار تایید</span>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
                        <span class="text-xs font-semibold text-yellow-300 animate-pulse">نیاز به اقدام</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
        <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
        </a>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- امروز سود -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 via-teal-600 to-emerald-700 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-green-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_today_profit')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight">$<?php echo e(number_format($todayprofit, 2)); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>سود خالص امروز</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- امروز زیان -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-red-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_today_lost')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight">$<?php echo e(number_format($todaylost, 2)); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>زیان خالص امروز</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- مجموع موجودی حساب‌ها -->
        <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-700 via-gray-800 to-slate-900 p-6 shadow-lg transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:shadow-slate-500/30">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-4 -left-4 h-20 w-20 rounded-full bg-white/5"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-lg">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-2 shadow-inner">
                            <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-white/70"><?php echo e(__('messages.general_total_balance')); ?></span>
                </div>
                
                <div class="mt-auto">
                    <p class="text-4xl font-bold text-white tracking-tight" dir="ltr">$<?php echo e(number_format($total_balance_usd, 2)); ?></p>
                    <div class="mt-2 flex items-center text-sm text-white/80">
                        <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>موجودی کل حساب‌ها</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>



            <template x-if="activeTab === 'safes'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border bg-[#F5F5F5] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="rounded-full dark:bg-white/100 bg-[#2563EB] p-6 flex items-center justify-center">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt=""
                                class="h-10 w-10 dark:hidden">
                            <i class="fa-solid fa-coins text-black text-2xl hidden  dark:block"></i>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-[16px] font-semibold dark:text-white text-gray-600"><?php echo e($label); ?></h1>
                            <p dir="ltr" class="  text-[25px] font-extrabold dark:text-white text-[#2563EB]">
                                <?php echo e(number_format($safe->$key ?? 0)); ?>

                            </p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                </div>
            </template>

            <template x-if="activeTab === 'account_safe'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border bg-[#F5F5F5]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 dark:text-white rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="rounded-full dark:bg-white bg-[#2563EB]  p-6 flex items-center justify-center">
                            <i class="fa-solid fa-credit-card dark:text-black text-white text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-[16px] font-semibold dark:text-white text-gray-600"><?php echo e($label); ?></h1>
                            <p dir="ltr" class="  text-[25px] font-extrabold dark:text-white text-[#2563EB]">
                                <?php echo e(number_format($safe_account[$key] ?? 0)); ?>

                            </p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </template>



        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 📈 گراف سود و زیان ماهانه
    const ctx1 = document.getElementById('monthlyProfitLossChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله'],
            datasets: [{
                    label: 'سود (دالر)',
                    data: [1200, 1500, 1100, 1800, 1700, 2000],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'زیان (دالر)',
                    data: [200, 300, 150, 250, 100, 400],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    const ctx2 = document.getElementById('transactionsByCurrencyChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['افغانی', 'دالر', 'یورو', 'ین چین'],
            datasets: [{
                label: 'تعداد تراکنش‌ها',
                data: [300, 120, 50, 40],
                backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#a855f7'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/dashboard.blade.php ENDPATH**/ ?>