<div class="border-[#8C8C8C] min-h-screen" style="font-family: 'header';">

    <h1 class="mb-8 text-3xl font-bold text-gray-700  dark:text-white"><?php echo e(__('messages.page_title')); ?></h1>

    <div class="grid grid-cols-2 md:grid-cols-7 gap-3">

        <!-- رسید/بردگی -->
        <a href="<?php echo e(route('sarafi.transactions')); ?>" class="block">
            <div class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 
                text-white text-[16px] font-bold">
                <i class="fa-solid fa-wallet text-white text-xl"></i>
                <span class=" whitespace-nowrap overflow-hidden text-ellipsis font-bold">
                    <?php echo e(__('messages.recipt/withdraw')); ?>

                </span>
            </div>
        </a>

        <!-- انتقال -->
        <a href="<?php echo e(route('sarafi.account_to_account')); ?>" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-arrow-right-arrow-left text-white text-xl"></i>
                <span><?php echo e(__('messages.transfer')); ?></span>
            </div>
        </a>


        <!-- خرید و فروش ارز و صندوق -->
        <a href="<?php echo e(route('sarafi.buy-sell-currency')); ?>" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center gap-3 text-white justify-center  text-[16px] font-bold">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/bitcoin-(btc).svg')); ?>" alt="">
                <span><?php echo e(__('messages.selling')); ?></span>
            </div>
        </a>

        <!-- حساب تبدیل -->
        <a href="<?php echo e(route('sarafi.conversion.in.account')); ?>" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-exchange-alt text-white text-xl"></i>
                <span><?php echo e(__('messages.coversion_account')); ?></span>
            </div>
        </a>

        <!-- انتقال تبدیل -->
        <a href="<?php echo e(route('sarafi.conversion-transfer')); ?>" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-hand-holding-dollar text-white text-xl"></i>
                <span><?php echo e(__('messages.coversion_transfer')); ?></span>
            </div>
        </a>


        <!-- رسید بانکی -->
        <a href="<?php echo e(route('sarafi.remittance')); ?>" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-book-open text-white text-xl"></i>
                <span>رسید بانکی</span>
            </div>
        </a>



        <!-- ژورنال عمومی -->
        <a href="<?php echo e(route('sarafi.withdrawbank')); ?>" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900  from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-file-invoice-dollar text-white text-xl"></i>
                <span>برد بانکی</span>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- تعداد کاربران -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                      text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/  users.svg')); ?>" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center"><?php echo e(__('messages.general_users')); ?>

                        </h1>

                        <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($UserCount); ?></p>
                    </div>

                    <!-- تعداد مشتریان -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/customers.svg')); ?>" alt="" class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_customers')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($customerCount); ?></p>
                    </div>

                    <!-- تراکنش‌های امروز -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange.svg')); ?>" alt="" class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_today_transactions')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($TransactionCount); ?></p>
                    </div>

                    <!-- حواله‌ها -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/send-2.svg')); ?>" alt="" class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_remittances')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($remittancecount); ?></p>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
                    <a href="<?php echo e(route('sarafi.remittance-approval')); ?>">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!-- حواله های در انتظار -->
                        <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                            <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/timer.svg')); ?>" alt="" class="h-10 w-10">
                            </div>


                            <h1 class="text-lg font-semibold drop-shadow-md text-center">
                                <?php echo e(__('messages.general_pending_transactions')); ?></h1>

                            <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($waitting); ?></p>

                        </div>

                        <!--[if BLOCK]><![endif]--><?php if($waitting > 0): ?>
                    </a>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->





                    <!-- امروز سود -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/dollar-circle.svg')); ?>" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_today_profit')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($todayprofit); ?></p>
                    </div>


                    <!-- امروز سود -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/dollar-circle.svg')); ?>" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_today_lost')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md"><?php echo e($todaylost); ?></p>
                    </div>









                    <!-- مجموع موجودی حساب‌ها -->
                    <div
                        class="border rounded-2xl p-6 shadow-md hover:shadow-xl transition transform h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d] text-white">
                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/wallet-3.svg')); ?>" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_total_balance')); ?>

                        </h1>

                        <p class="text-3xl font-extrabold drop-shadow-md" dir="ltr">

                            <?php
                            $currentUser = Auth::guard('sarafi')->user();
                            ?>

                            <!--[if BLOCK]><![endif]--><?php if(
                            $currentUser &&
                            in_array($currentUser->role, ['superadmin', 'admin', 'cashier'])
                            ): ?>
                            <?php echo e(number_format($total_balance_usd, 2)); ?>

                            <?php else: ?>
                            0
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </p>

                    </div>


                </div>

            </template>


            <?php
            $currentUser = Auth::guard('sarafi')->user();
            ?>

            <!--[if BLOCK]><![endif]--><?php if(
            $currentUser &&
            in_array($currentUser->role, ['superadmin', 'admin', 'cashier'])
            ): ?>
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

            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

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