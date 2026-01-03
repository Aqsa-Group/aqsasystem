<div class="border-[#8C8C8C] min-h-screen" style="font-family: 'header';">

    <h1 class="mb-8 text-3xl font-bold text-gray-700  dark:text-white">{{ __('messages.page_title') }}</h1>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(180px,1fr))] gap-3">

        <!-- رسید/بردگی -->
        <a href="{{ route('sarafi.transactions') }}" class="block">
            <div class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 
                text-white text-[16px] font-bold">
                <i class="fa-solid fa-wallet text-white text-xl"></i>
                <span class=" whitespace-nowrap overflow-hidden text-ellipsis font-bold">
                    {{ __('messages.recipt/withdraw') }}
                </span>
            </div>
        </a>

        <!-- انتقال -->
        <a href="{{ route('sarafi.account_to_account') }}" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-arrow-right-arrow-left text-white text-xl"></i>
                <span>{{ __('messages.transfer') }}</span>
            </div>
        </a>


        <!-- خرید و فروش ارز و صندوق -->
        <a href="{{ route('sarafi.buy-sell-currency') }}" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center gap-3 text-white text-[16px] font-bold">
                <img src="{{ asset('assets/sarafi/all_icon/bitcoin-(btc).svg') }}" alt="">
                <span>{{ __('messages.selling') }}</span>
            </div>
        </a>

        <!-- حساب تبدیل -->
        <a href="{{ route('sarafi.conversion.in.account') }}" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-exchange-alt text-white text-xl"></i>
                <span>{{ __('messages.coversion_account') }}</span>
            </div>
        </a>

        <!-- انتقال تبدیل -->
        <a href="{{ route('sarafi.conversion-transfer') }}" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-hand-holding-dollar text-white text-xl"></i>
                <span>{{ __('messages.coversion_transfer') }}</span>
            </div>
        </a>


        <!-- رسید بانکی -->
        <a href="{{ route('sarafi.remittance') }}" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-book-open text-white text-xl"></i>
                <span>رسید بانکی</span>
            </div>
        </a>



        <!-- ژورنال عمومی -->
        <a href="{{ route('sarafi.withdrawbank') }}" class="block">
            <div
                class="border bg-gradient-to-b dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900  from-[#2563EB] to-[#1e325d] rounded-xl px-3 py-4 flex items-center justify-center gap-3 text-white text-[16px] font-bold">
                <i class="fa-solid fa-file-invoice-dollar text-white text-xl"></i>
                <span>برد بانکی</span>
            </div>
        </a>


    </div>


    <div x-data="{ activeTab: @entangle('activeTab') }" class="mt-12">
        <div class="flex justify-start gap-6 border-b  dark:border-white border-[#2563EBB0]">
            <a href="#" @click.prevent="activeTab = 'general'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'general'
                    ?
                    'bg-white dark:bg-black border-x border-t dark:border-white dark:text-white  border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 dark:text-white/30 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                {{ __('messages.tab_general') }}
            </a>

            <a href="#" @click.prevent="activeTab = 'safes'" class="px-5 py-2 font-bold transition rounded-t-lg" :class="activeTab === 'safes'
                    ?
                    'bg-white dark:bg-black border-x border-t d  dark:border-white dark:text-white border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
                    'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                {{ __('messages.tab_safes') }}
            </a>

            <a href="#" @click.prevent="activeTab = 'account_safe'" class="px-5 py-2 font-bold transition rounded-t-lg"
                :class="activeTab === 'account_safe'
            ?
            'bg-white dark:bg-black border-x border-t dark:border-white dark:text-white border-[#2563EBB0] text-[#1E3A8A] shadow-sm' :
            'text-gray-600 hover:text-[#1E3A8A] hover:border-b-2 hover:border-indigo-400'">
                {{ __('messages.account_safes') }}
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
                            <img src="{{ asset('assets/sarafi/all_icon/  users.svg') }}" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">{{ __('messages.general_users') }}
                        </h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">{{ $UserCount }}</p>
                    </div>

                    <!-- تعداد مشتریان -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/customers.svg') }}" alt="" class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            {{ __('messages.general_customers') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">{{ $customerCount }}</p>
                    </div>

                    <!-- تراکنش‌های امروز -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/exchange.svg') }}" alt="" class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            {{ __('messages.general_today_transactions') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">{{ $TransactionCount }}</p>
                    </div>

                    <!-- حواله‌ها -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/send-2.svg') }}" alt="" class="h-10 w-10">
                        </div>


                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            {{ __('messages.general_remittances') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">{{ $remittancecount }}</p>
                    </div>

                    @if($waitting > 0)
                    <a href="{{ route('sarafi.remittance-approval') }}">
                        @endif
                        <!-- حواله های در انتظار -->
                        <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                            <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                                <img src="{{ asset('assets/sarafi/all_icon/timer.svg') }}" alt="" class="h-10 w-10">
                            </div>


                            <h1 class="text-lg font-semibold drop-shadow-md text-center">
                                {{ __('messages.general_pending_transactions') }}</h1>

                            <p class="text-3xl font-extrabold drop-shadow-md">{{ $waitting }}</p>

                        </div>

                        @if($waitting > 0)
                    </a>
                    @endif





                    <!-- امروز سود -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/dollar-circle.svg') }}" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            {{ __('messages.general_today_profit') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">{{ $todayprofit }}</p>
                    </div>


                    <!-- امروز سود -->
                    <div class="border rounded-2xl p-6  shadow-md hover:shadow-xl transition transform  h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d]
                                     text-white" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">

                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/dollar-circle.svg') }}" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            {{ __('messages.general_today_lost') }}</h1>

                        <p class="text-3xl font-extrabold drop-shadow-md">{{ $todaylost }}</p>
                    </div>









                    <!-- مجموع موجودی حساب‌ها -->
                    <div
                        class="border rounded-2xl p-6 shadow-md hover:shadow-xl transition transform h-56 flex flex-col items-center justify-between dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 bg-gradient-to-b from-[#2563EB] to-[#1e325d] text-white">
                        <div class="flex items-center justify-center bg-white rounded-full h-20 w-20 shadow-lg">
                            <img src="{{ asset('assets/sarafi/all_icon/wallet-3.svg') }}" alt="" class="h-10 w-10">
                        </div>

                        <h1 class="text-lg font-semibold drop-shadow-md text-center">
                            {{ __('messages.general_total_balance') }}
                        </h1>

                        <p class="text-3xl font-extrabold drop-shadow-md" dir="ltr">
                            {{ number_format($total_balance_usd, 2) }}
                        </p>

                    </div>
                </div>

            </template>


            @php
            $currentUser = Auth::guard('sarafi')->user();
            @endphp

            @if (
            $currentUser &&
            in_array($currentUser->role, ['superadmin', 'admin', 'cashier'])
            )
            <template x-if="activeTab === 'safes'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">

                    @foreach($currencies as $key => $label)
                    <div class="border bg-[#F5F5F5] dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="rounded-full dark:bg-white/100 bg-[#2563EB] p-6 flex items-center justify-center">
                            <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt=""
                                class="h-10 w-10 dark:hidden">
                            <i class="fa-solid fa-coins text-black text-2xl hidden  dark:block"></i>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-[16px] font-semibold dark:text-white text-gray-600">{{ $label }}</h1>
                            <p dir="ltr" class="  text-[25px] font-extrabold dark:text-white text-[#2563EB]">
                                {{ number_format($safe->$key ?? 0) }}
                            </p>
                        </div>
                    </div>
                    @endforeach

                </div>
            </template>

            <template x-if="activeTab === 'account_safe'">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 vazir">
                    @foreach($currencies as $key => $label)
                    <div class="border bg-[#F5F5F5]  dark:bg-gradient-to-b dark:from-slate-500 dark:to-gray-900 dark:text-white rounded-xl p-6 h-48 flex flex-col items-center gap-4 justify-center text-center"
                        style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
                        <div class="rounded-full dark:bg-white bg-[#2563EB]  p-6 flex items-center justify-center">
                            <i class="fa-solid fa-credit-card dark:text-black text-white text-2xl"></i>
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-[16px] font-semibold dark:text-white text-gray-600">{{ $label }}</h1>
                            <p dir="ltr" class="  text-[25px] font-extrabold dark:text-white text-[#2563EB]">
                                {{ number_format($safe_account[$key] ?? 0) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </template>

            @endif

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