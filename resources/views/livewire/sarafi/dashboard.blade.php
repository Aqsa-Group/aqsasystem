<div class="p-6 border-[#8C8C8C] min-h-screen" style="font-family: 'header';">

    <h1 class="mb-8 text-2xl font-bold text-gray-700">📊 {{ __('messages.page_title')}}</h1>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(180px,1fr))] gap-3">

        <!-- رسید/بردگی -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-wallet text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.recipt/withdraw') }}
            </a>
        </div>

        <!-- انتقال -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-arrow-right-arrow-left text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.transfer') }}
            </a>
        </div>

        <!-- حساب‌های روزنامه -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-book-open text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.newspaper_accounts') }}
            </a>
        </div>

        <!-- خرید و فروش ارز و صندوق -->
        <div class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center  gap-3 text-white text-[16px] font-bold">
              <img src="{{ asset('assets/sarafi/all_icon/bitcoin-(btc).svg') }}" alt="">
             <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.selling') }}
            </a>
        </div>

        <!-- حساب تبدیل -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-exchange-alt text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.coversion_account') }}
            </a>
        </div>

        <!-- انتقال تبدیل -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-hand-holding-dollar text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.coversion_transfer') }}
            </a>
        </div>

        <!-- ژورنال عمومی -->
        <div
            class="border bg-[#2563EB] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
            <i class="fa-solid fa-file-invoice-dollar text-white text-xl"></i>
            <a href="" class="font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                {{ __('messages.general_jornal') }}
            </a>
        </div>

    </div>


    <div x-data="{ activeTab: @entangle('activeTab') }" class="mt-12">
        <div class="flex justify-start gap-6 border-b border-[#2563EBB0]">
            <a href="#" @click.prevent="activeTab = 'general'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'general' 
                   ? 'bg-white border-x border-t border-[#2563EBB0] text-[#1E3A8A] shadow-sm' 
                   : 'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                {{ __('messages.tab_general') }}
            </a>
            <a href="#" @click.prevent="activeTab = 'reports'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'reports' 
                   ? 'bg-white border-x border-t border-[#2563EBB0] text-[#1E3A8A] shadow-sm' 
                   : 'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                {{ __('messages.tab_reports') }}
            </a>
            <a href="#" @click.prevent="activeTab = 'safes'" class="px-5 py-2 font-bold transition rounded-t-lg" :class="activeTab === 'safes' 
                   ? 'bg-white border-x border-t border-[#2563EBB0] text-[#1E3A8A] shadow-sm' 
                   : 'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                {{ __('messages.tab_safes') }}
            </a>
        </div>

        <div class="p-6 bg-white rounded-b-xl shadow-sm mt-2">

            <template x-if="activeTab === 'general'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- تعداد کاربران -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
   text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/  users.svg') }}" alt="">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{ __('messages.general_users') }}
                        </h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- تعداد مشتریان -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/customers.svg') }}" alt="">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{ __('messages.general_customers')
                            }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- تراکنش‌های امروز -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/exchange.svg') }}" alt="">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{
                            __('messages.general_today_transactions') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- تراکنش‌های در انتظار -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/timer.svg') }}" alt="">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{
                            __('messages.general_pending_transactions') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">10</p>
                    </div>

                    <!-- امروز سود -->
                    <div class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/dollar-circle.svg') }}" alt="">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{
                            __('messages.general_today_profit') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">1,200</p>
                    </div>

                    <!-- مجموع تراکنش‌ها -->
                    <div class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/receipt-2.svg') }}" alt="">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{
                            __('messages.general_total_transactions') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">4,500</p>
                    </div>

                    <!-- حواله‌ها -->
                    <div class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/send-2.svg') }}" alt="">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{
                            __('messages.general_remittances') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">8</p>
                    </div>

                    <!-- مجموع موجودی حساب‌ها -->
                    <div class="rounded-2xl p-6  shadow-md hover:shadow-xl transition transform hover:scale-105 h-56 flex flex-col items-center justify-between bg-gradient-to-b from-[#3B82F6] to-[#1E40AF]
 text-white">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/wallet-3.svg') }}" alt="">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{
                            __('messages.general_total_balance') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">120,000</p>
                    </div>


                </div>
            </template>


            <template x-if="activeTab === 'reports'">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="border bg-white rounded-2xl p-6 shadow-md">
                        <h2 class="text-lg font-bold text-gray-700 mb-4">📈 {{
                            __('messages.reports_monthly_profit_loss') }}</h2>
                        <canvas id="monthlyProfitLossChart" class="w-full h-64"></canvas>
                    </div>
                    <div class="border bg-white rounded-2xl p-6 shadow-md">
                        <h2 class="text-lg font-bold text-gray-700 mb-4">💰 {{
                            __('messages.reports_transactions_by_currency') }}</h2>
                        <canvas id="transactionsByCurrencyChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </template>

            <template x-if="activeTab === 'safes'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                    <!-- افغانی -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/afn.jpg') }}" alt="AFN"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_afn') }}</h1>
                            <p class="text-2xl font-extrabold text-blue-600">3,500,000</p>
                        </div>
                    </div>

                    <!-- دلار -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/usd.png') }}" alt="USD"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_usd') }}</h1>
                            <p class="text-2xl font-extrabold text-green-600">45,000</p>
                        </div>
                    </div>

                    <!-- یورو -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/eur.png') }}" alt="EUR"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_eur') }}</h1>
                            <p class="text-2xl font-extrabold text-indigo-600">12,000</p>
                        </div>
                    </div>

                    <!-- ریال ایران -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/irr.jpg') }}" alt="IRR"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_irr') }}</h1>
                            <p class="text-2xl font-extrabold text-red-600">1,200,000</p>
                        </div>
                    </div>

                    <!-- درهم امارات -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/aed.jpg') }}" alt="AED"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_aed') }}</h1>
                            <p class="text-2xl font-extrabold text-yellow-600">10,000</p>
                        </div>
                    </div>

                    <!-- لیره ترکیه -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/try.webp') }}" alt="TRY"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_try') }}</h1>
                            <p class="text-2xl font-extrabold text-pink-600">8,000</p>
                        </div>
                    </div>

                    <!-- یوان چین -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/cny.jpeg') }}" alt="CNY"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_cny') }}</h1>
                            <p class="text-2xl font-extrabold text-purple-600">80,000</p>
                        </div>
                    </div>

                    <!-- روپیه کلدار -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/pk.jpg') }}" alt="PKR"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_pkr') }}</h1>
                            <p class="text-2xl font-extrabold text-green-600">200,000</p>
                        </div>
                    </div>

                    <!-- پوند انگلیس -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/gbp.png') }}" alt="GBP"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_gbp') }}</h1>
                            <p class="text-2xl font-extrabold text-blue-600">5,000</p>
                        </div>
                    </div>

                    <!-- ین ژاپن -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/jpy.jpg') }}" alt="JPY"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_jpy') }}</h1>
                            <p class="text-2xl font-extrabold text-red-600">7,500</p>
                        </div>
                    </div>

                    <!-- ریال سعودی -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/sar.webp') }}" alt="SAR"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_sar') }}</h1>
                            <p class="text-2xl font-extrabold text-red-600">15,000</p>
                        </div>
                    </div>

                    <!-- روپیه هند -->
                    <div
                        class="border bg-white rounded-2xl p-4 shadow-lg hover:shadow-xl transition transform hover:scale-105 h-36 flex items-center gap-4">
                        <img src="{{ asset('assets/sarafi/currency/inr.jpg') }}" alt="INR"
                            class="h-14 w-14 rounded-full shadow-md">
                        <div>
                            <h1 class="text-sm font-semibold text-gray-600">{{ __('messages.safes_inr') }}</h1>
                            <p class="text-2xl font-extrabold text-orange-600">15,000</p>
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
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
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
                legend: { position: 'bottom' }
            }
        }
    });
</script>