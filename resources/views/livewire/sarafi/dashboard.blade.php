<div class="p-6 bg-gray-50 min-h-screen" style="font-family: 'header';">

    <!-- عنوان صفحه -->
    <h1 class="mb-8 text-2xl font-bold text-gray-700">📊 صفحه اصلی گزارشات و آمار</h1>

    <!-- کارت‌های منو -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- کارت -->
        <div class="border bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition hover:bg-cyan-50">
            <div class="flex items-center justify-center gap-2 text-gray-700">
                <i class="fas fa-credit-card text-indigo-600"></i>
                <a href="" class="font-medium text-sm">{{ __('messages.recipt/withdraw') }}</a>
            </div>
        </div>

        <div class="border bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition hover:bg-cyan-50">
            <div class="flex items-center justify-center gap-2 text-gray-700">
                <i class="fa-solid fa-money-bill-transfer text-green-600"></i>
                <a href="" class="font-medium text-sm">{{ __('messages.transfer') }}</a>
            </div>
        </div>

        <div class="border bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition hover:bg-cyan-50">
            <div class="flex items-center justify-center gap-2 text-gray-700">
                <i class="fa-solid fa-newspaper text-orange-500"></i>
                <a href="" class="font-medium text-sm">{{ __('messages.newspaper_accounts') }}</a>
            </div>
        </div>

        <div class="border bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition hover:bg-cyan-50">
            <div class="flex items-center justify-center gap-2 text-gray-700">
                <img src="{{ asset('assets/sarafi/exchange_money.png') }}" class="h-6 w-6" alt="">
                <a href="" class="font-medium text-sm">{{ __('messages.coversion_account') }}</a>
            </div>
        </div>

        <div class="border bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition hover:bg-cyan-50">
            <div class="flex items-center justify-center gap-2 text-gray-700">
                <i class="fa-solid fa-money-bill-transfer text-purple-600"></i>
                <a href="" class="font-medium text-sm">{{ __('messages.coversion_transfer') }}</a>
            </div>
        </div>

        <div class="border bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition hover:bg-cyan-50">
            <div class="flex items-center justify-center gap-2 text-gray-700">
                <img src="{{ asset('assets/sarafi/general.png') }}" class="h-6 w-6" alt="">
                <a href="" class="font-medium text-sm">{{ __('messages.general_jornal') }}</a>
            </div>
        </div>
    </div>

    <!-- تب‌ها -->
    <div x-data="{ activeTab: @entangle('activeTab') }" class="mt-12">
        <div class="flex justify-start gap-6 border-b border-gray-300">
            
            <a href="#"
               @click.prevent="activeTab = 'general'"
               class="px-5 py-2 font-bold transition rounded-t-lg"
               :class="activeTab === 'general' 
                   ? 'bg-white border-x border-t border-gray-300 text-indigo-600 shadow-sm' 
                   : 'text-gray-600 hover:text-indigo-500 hover:border-b-2 hover:border-indigo-400'">
                پنل عمومی
            </a>

            <a href="#"
               @click.prevent="activeTab = 'reports'"
               class="px-5 py-2 font-bold transition rounded-t-lg"
               :class="activeTab === 'reports' 
                   ? 'bg-white border-x border-t border-gray-300 text-indigo-600 shadow-sm' 
                   : 'text-gray-600 hover:text-indigo-500 hover:border-b-2 hover:border-indigo-400'">
                پنل گزارشات و بیلانس
            </a>
        </div>

        <!-- محتوای تب‌ها -->
        <div class="p-6 bg-white rounded-b-xl shadow-sm mt-2">

            <!-- تب عمومی -->
            <template x-if="activeTab === 'general'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- کارت آماری -->
                    <div class="border bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition transform hover:scale-105">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-full h-16 w-16 shadow-lg">
                                <img src="{{ asset('assets/sarafi/user.png') }}" alt="Users" class="h-10 w-10">
                            </div>
                            <div>
                                <h1 class="text-sm font-semibold text-gray-600">تعداد کاربران</h1>
                                <p class="text-2xl font-extrabold text-indigo-600">10</p>
                            </div>
                        </div>
                    </div>

                    <div class="border bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition transform hover:scale-105">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full h-16 w-16 shadow-lg">
                                <img src="{{ asset('assets/sarafi/customer.png') }}" alt="Customers" class="h-10 w-10">
                            </div>
                            <div>
                                <h1 class="text-sm font-semibold text-gray-600">تعداد مشتریان</h1>
                                <p class="text-2xl font-extrabold text-green-600">10</p>
                            </div>
                        </div>
                    </div>

                    <div class="border bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition transform hover:scale-105">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full h-16 w-16 shadow-lg">
                                <img src="{{ asset('assets/sarafi/transaction_s.png') }}" alt="Transactions" class="h-10 w-10">
                            </div>
                            <div>
                                <h1 class="text-sm font-semibold text-gray-600">ترانزکشن‌های امروز</h1>
                                <p class="text-2xl font-extrabold text-purple-600">10</p>
                            </div>
                        </div>
                    </div>

                    <div class="border bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition transform hover:scale-105">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-full h-16 w-16 shadow-lg">
                                <img src="{{ asset('assets/sarafi/transaction_w.png') }}" alt="Pending" class="h-10 w-10">
                            </div>
                            <div>
                                <h1 class="text-sm font-semibold text-gray-600">ترانزکشن‌های در انتظار</h1>
                                <p class="text-2xl font-extrabold text-yellow-600">10</p>
                            </div>
                        </div>
                    </div>

                </div>
            </template>

            <!-- تب گزارشات -->
            <template x-if="activeTab === 'reports'">
                <h2 class="text-lg font-bold text-gray-700">📑 محتوای پنل گزارشات و بیلانس</h2>
            </template>
        </div>
    </div>

</div>
