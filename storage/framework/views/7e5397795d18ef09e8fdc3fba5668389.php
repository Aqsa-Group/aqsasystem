<div class="p-6 border-[#8C8C8C] min-h-screen" style="font-family: 'header';">

    <h1 class="mb-8 text-3xl font-bold text-gray-700 vazir"><?php echo e(__('messages.page_title')); ?></h1>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(180px,1fr))] gap-3">

        <!-- رسید/بردگی -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-wallet text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.recipt/withdraw')); ?>

            </a>
        </div>

        <!-- انتقال -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-arrow-right-arrow-left text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.transfer')); ?>

            </a>
        </div>

        <!-- حساب‌های روزنامه -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-book-open text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.newspaper_accounts')); ?>

            </a>
        </div>

        <!-- خرید و فروش ارز و صندوق -->
        <div class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center  gap-3 text-white text-[16px] font-bold">
            <img src="<?php echo e(asset('assets/sarafi/all_icon/bitcoin-(btc).svg')); ?>" alt="">
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.selling')); ?>

            </a>
        </div>

        <!-- حساب تبدیل -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-exchange-alt text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.coversion_account')); ?>

            </a>
        </div>

        <!-- انتقال تبدیل -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-hand-holding-dollar text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.coversion_transfer')); ?>

            </a>
        </div>

        <!-- ژورنال عمومی -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-file-invoice-dollar text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                <?php echo e(__('messages.general_jornal')); ?>

            </a>
        </div>

    </div>


    <div x-data="{ activeTab: <?php if ((object) ('activeTab') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'->value()); ?>')<?php echo e('activeTab'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('activeTab'); ?>')<?php endif; ?> }" class="mt-12">
        <div class="flex justify-start gap-6 border-b border-[#2563EBB0]">
            <a href="#" @click.prevent="activeTab = 'general'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'general'
                    ?
                    'bg-white border-x border-t border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                <?php echo e(__('messages.tab_general')); ?>

            </a>
            <a href="#" @click.prevent="activeTab = 'reports'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'reports'
                    ?
                    'bg-white border-x border-t border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                <?php echo e(__('messages.tab_reports')); ?>

            </a>
            <a href="#" @click.prevent="activeTab = 'safes'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'safes'
                    ?
                    'bg-white border-x border-t border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                <?php echo e(__('messages.tab_safes')); ?>

            </a>
        </div>

        <div class="p-6 bg-white rounded-b-xl shadow-sm mt-2" :class="(activeTab === 'general') 
                      ? 'bg-gray-100' 
                      : 'bg-white'">

            <template x-if="activeTab === 'general'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- تعداد کاربران -->
                    <div
                        class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#FF9AA2] to-[#E52C1C]
   text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/  users.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center"><?php echo e(__('messages.general_users')); ?>

                        </h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- تعداد مشتریان -->
                    <div
                        class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#FF9AA2] to-[#526FF5]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/customers.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_customers')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- تراکنش‌های امروز -->
                    <div
                        class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#2563EB] to-[#3293CC]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/exchange.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_today_transactions')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- تراکنش‌های در انتظار -->
                    <div
                        class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#239ABB] to-[#61B138]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/timer.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_pending_transactions')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- امروز سود -->
                    <div
                        class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#F6F884] to-[#B2620C]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/dollar-circle.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_today_profit')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">1,200</p>
                    </div>

                    <!-- مجموع تراکنش‌ها -->
                    <div
                        class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#AAB2BE] to-[#1874F6]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/receipt-2.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_total_transactions')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">4,500</p>
                    </div>

                    <!-- حواله‌ها -->
                    <div
                        class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#659EEF] to-[#2297B7]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/send-2.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_remittances')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">8</p>
                    </div>

                    <!-- مجموع موجودی حساب‌ها -->
                    <div
                        class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#BC83F6] to-[#5A0FA6]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/wallet-3.svg')); ?>" alt=""
                                class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            <?php echo e(__('messages.general_total_balance')); ?></h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">120,000</p>
                    </div>


                </div>
            </template>


            <template x-if="activeTab === 'reports'">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pr-6">

                    
                    <div class="border bg-[#E5E5E5] rounded-2xl p-6 shadow-md" style="height:532px; width:687px;">
                        <div class="flex items-center gap-3 mb-4">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/bit-gr.svg')); ?>" alt=""
                                class="h-8 w-8">
                            <h2 class="text-lg font-bold text-gray-700">
                                <?php echo e(__('messages.reports_monthly_profit_loss')); ?>

                            </h2>
                        </div>
                        <canvas id="monthlyProfitLossChart" class="w-full h-[450px]"></canvas>
                    </div>

                    
                    <div class="border bg-[#E5E5E5] rounded-2xl p-6 shadow-md" style="height:532px; width:687px;">
                        <div class="flex items-center gap-3 mb-4">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/chart.svg')); ?>" alt=""
                                class="h-8 w-8">
                            <h2 class="text-lg font-bold text-gray-700">
                                <?php echo e(__('messages.reports_transactions_by_currency')); ?>

                            </h2>
                        </div>
                        <canvas id="transactionsByCurrencyChart" class="w-full h-[450px]"></canvas>
                    </div>

                </div>
            </template>


           <template x-if="activeTab === 'safes'">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">

    <!-- افغانی -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_afn')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">3,500,000</p>
      </div>
    </div>

    <!-- دلار -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_usd')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">45,000</p>
      </div>
    </div>

    <!-- یورو -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_eur')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">12,000</p>
      </div>
    </div>

    <!-- ریال ایران -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
       <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_irr')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">1,200,000</p>
      </div>
    </div>

    <!-- درهم امارات -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_aed')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">10,000</p>
      </div>
    </div>

    <!-- لیره ترکیه -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_try')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">8,000</p>
      </div>
    </div>

    <!-- یوان چین -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
        <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_cny')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">80,000</p>
      </div>
    </div>

    <!-- روپیه کلدار (پاکستان) -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_pkr')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">200,000</p>
      </div>
    </div>

    <!-- پوند انگلیس -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_gbp')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">5,000</p>
      </div>
    </div>

    <!-- ین ژاپن -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
       <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_jpy')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">7,500</p>
      </div>
    </div>

    <!-- ریال سعودی -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-gray-600"><?php echo e(__('messages.safes_sar')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">15,000</p>
      </div>
    </div>

    <!-- روپیه هند -->
<div class="border bg-[#F5F5F5] rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
     style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
      <div class="rounded-full bg-[#2563EB] p-6 flex items-center justify-center">
        <img src="<?php echo e(asset('assets/sarafi/all_icon/coin.svg')); ?>" alt="" class="h-10 w-10">
      </div>
      <div class="space-y-2">
        <h1 class="text-[16px] font-semibold text-black"><?php echo e(__('messages.safes_inr')); ?></h1>
        <p class="text-[25px] font-extrabold text-[#2563EB]">15,000</p>
      </div>
    </div>

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
</script>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/livewire/sarafi/dashboard.blade.php ENDPATH**/ ?>