<div class="pr-4 pl-4">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-3 text-center">

        <a href="{{ route('tools.sales') }}">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                <i class="fa-solid fa-cart-shopping text-2xl "></i>
                <p class="text-lg font-semibold">فروشات</p>
            </div>
        </a>


        <a href="{{ route('tools.loans') }}">

            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                <i class="fa-solid fa-hand-holding-dollar text-3xl mb-2"></i>
                <p class="text-lg font-semibold">قرضه‌ها</p>
            </div>
        </a>

        <a href="{{ route("tools.inventory") }}">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                <i class="fa-solid fa-warehouse text-3xl mb-2"></i>
                <p class="text-lg font-semibold">اجناس گدام</p>
            </div>
        </a>

        <a href="{{ route('tools.warehouse') }}">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                <i class="fa-solid fa-store text-3xl mb-2"></i>
                <p class="text-lg font-semibold">اجناس دوکان</p>
            </div>
        </a>

        <a href="{{ route('tools.withdrawals') }}">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                <i class="fa-solid fa-money-bill-transfer text-3xl mb-2"></i>
                <p class="text-lg font-semibold">برداشت‌ها</p>
            </div>
        </a>

   <a href="{{ route('tools.reports') }}">
         <div
            class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
            <i class="fa-solid fa-chart-line text-3xl mb-2"></i>
            <p class="text-lg font-semibold">گزارشات</p>
        </div>
   </a>
    </div>

    <!-- دو چارت در دو ستون -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <!-- چارت فایده -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">📈 فایده ماهانه</h2>
            <div id="profit-chart"></div>
        </div>

        <!-- چارت ضرر -->
        <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200"
            style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">📉 ضرر ماهانه</h2>
            <div id="loss-chart"></div>
        </div>

    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6 p-4">
        <!-- کارت فروشات امروز -->
        <div
            class="bg-gradient-to-br from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">فروشات امروز</h3>
                <div class="bg-green-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-sm font-bold mt-1 animate-number" data-target="{{ $todaysale }}">0</p>
                <span class="text-sm bg-green-200 px-2 py-1 rounded-full">+12%</span>
            </div>
        </div>

        <!-- کارت فایده امروز -->
        <div
            class="bg-gradient-to-br from-blue-50 to-blue-100 border-l-4 border-blue-500 text-blue-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">فایده امروز</h3>
                <div class="bg-blue-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $todayprofit }}">0</p>
                <span class="text-sm bg-blue-200 px-2 py-1 rounded-full">+8%</span>
            </div>
        </div>

        <!-- کارت مصارف امروز -->
        <div
            class="bg-gradient-to-br from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">مصارف امروز</h3>
                <div class="bg-red-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number vazir" data-target="{{ $withdrawals }}">0</p>
                <span class="text-sm bg-red-200 px-2 py-1 rounded-full">-3%</span>
            </div>
        </div>

        <!-- کارت فروشات این ماه -->
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100 border-l-4 border-purple-500 text-purple-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">فروشات این ماه</h3>
                <div class="bg-purple-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $thismonthsale }}">0</p>
                <span class="text-sm bg-purple-200 px-2 py-1 rounded-full">+15%</span>
            </div>
        </div>

        <!-- کارت مجموعه سرماهه گدام -->
        <div
            class="bg-gradient-to-br from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 text-indigo-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">مجموعه سرمایه گدام</h3>
                <div class="bg-indigo-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $inventorytotalprice }}">0</p>
                <span class="text-sm bg-indigo-200 px-2 py-1 rounded-full">+5%</span>
            </div>
        </div>

        <!-- کارت مجموعه سرمایه دوکان -->
        <div
            class="bg-gradient-to-br from-amber-50 to-amber-100 border-l-4 border-amber-500 text-amber-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">مجموعه سرمایه دوکان</h3>
                <div class="bg-amber-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $warehousetotalprice }}">0</p>
                <span class="text-sm bg-amber-200 px-2 py-1 rounded-full">+7%</span>
            </div>
        </div>

        <!-- کارت مجموعه سرمایه فعلی -->
        <div
            class="bg-gradient-to-br from-cyan-50 to-cyan-100 border-l-4 border-cyan-500 text-cyan-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">مجموعه سرمایه فعلی</h3>
                <div class="bg-cyan-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $totalstock }}">0</p>
                <span class="text-sm bg-cyan-200 px-2 py-1 rounded-full">+10%</span>
            </div>
        </div>

        <!-- کارت مجموعه قرضه ها -->
        <div
            class="bg-gradient-to-br from-pink-50 to-pink-100 border-l-4 border-pink-500 text-pink-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">مجموعه قرضه ها</h3>
                <div class="bg-pink-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-center items-center text-2xl vazir ">
                        <span>دالر:</span>

                        <p class="text-[25px] font-bold mt-1 animate-number vazir" data-target="{{$totalUsdLoan }}">0</p>
                    </div>

                    <div class="flex justify-center items-center text-2xl ">
                        <span> اف: </span>

                        <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{$totalAFNLoan }}">0</p>
                    </div>
                </div>
                <span class="text-sm bg-pink-200 px-2 py-1 rounded-full">-2%</span>
            </div>
        </div>

        <!-- کارت تعداد کاربران -->
        <div
            class="bg-gradient-to-br from-teal-50 to-teal-100 border-l-4 border-teal-500 text-teal-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">تعداد کاربران</h3> 
                <div class="bg-teal-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $usercount }}">0</p>
                <span class="text-sm bg-teal-200 px-2 py-1 rounded-full">+18%</span>
            </div>
        </div>

        <!-- کارت تعداد مشتریان -->
        <div
            class="bg-gradient-to-br from-orange-50 to-orange-100 border-l-4 border-orange-500 text-orange-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">تعداد مشتریان</h3>
                <div class="bg-orange-500 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-[25px] font-bold mt-1 animate-number" data-target="{{ $countcustomer }}">0</p>
                <span class="text-sm bg-orange-200 px-2 py-1 rounded-full">+22%</span>
            </div>
        </div>
    </div>

    <script>
        // انیمیشن شمارش اعداد
    document.addEventListener('DOMContentLoaded', function() {
        const animateNumbers = document.querySelectorAll('.animate-number');
        
        animateNumbers.forEach(element => {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000; 
            const step = target / (duration / 16); 
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    clearInterval(timer);
                    current = target;
                }
                element.textContent = Math.floor(current).toLocaleString();
            }, 16);
        });
    });
    </script>
</div>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

    // دریافت داده‌های واقعی از کامپوننت Livewire
    const months = @json($months);
    const profitData = @json($profitPerMonth);
    const lossData = @json($lossPerMonth);

    // اگر داده‌ای وجود ندارد، از داده‌های پیش‌فرض استفاده کن
    const finalProfitData = profitData && profitData.length > 0 ? profitData : [1200, 1800, 2500, 1900, 2700, 3100, 3400, 3000, 3600, 4100, 4300, 4600];
    const finalLossData = lossData && lossData.length > 0 ? lossData : [800, 600, 1100, 900, 1200, 700, 500, 800, 900, 750, 620, 700];

    const baseChart = {
        chart: {
            type: 'area',
            height: 260,
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 1200,
                animateGradually: { enabled: true, delay: 120 },
                dynamicAnimation: { enabled: true, speed: 800 }
            }
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        xaxis: { 
            categories: months,
            labels: {
                style: {
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('fa-IR') + ' AFN';
                },
                style: {
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                    fontSize: '11px'
                }
            }
        },
        tooltip: { 
            theme: 'dark',
            y: {
                formatter: function(value) {
                    return value.toLocaleString('fa-IR') + ' AFN';
                }
            }
        },
        grid: { 
            borderColor: '#eee', 
            strokeDashArray: 4 
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'left',
            fontFamily: 'system-ui, -apple-system, sans-serif'
        }
    };

    // فایده (سبز)
    const profitChart = new ApexCharts(document.querySelector("#profit-chart"), {
        ...baseChart,
        series: [{ name: 'فایده', data: finalProfitData }],
        colors: ['#16a34a'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.9,  
                opacityFrom: 0.8,    
                opacityTo: 0.4,        
                stops: [0, 90, 100]
            }
        },
        title: {
            text: 'سود ماهانه بر اساس فروش',
            align: 'right',
            style: {
                fontSize: '14px',
                fontFamily: 'system-ui, -apple-system, sans-serif'
            }
        }
    });
    profitChart.render();

    // ضرر (قرمز)
    const lossChart = new ApexCharts(document.querySelector("#loss-chart"), {
        ...baseChart,
        series: [{ name: 'ضرر', data: finalLossData }],
        colors: ['#dc2626'],
        fill: {
            type: 'gradient',
            gradient: { 
                shadeIntensity: 0.9, 
                opacityFrom: 0.8, 
                opacityTo: 0.4, 
                stops: [0, 90, 100] 
            }
        },
        title: {
            text: 'ضرر ماهانه بر اساس آیتم‌های فروش',
            align: 'right',
            style: {
                fontSize: '14px',
                fontFamily: 'system-ui, -apple-system, sans-serif'
            }
        }
    });
    lossChart.render();

    // انیمیشن اعداد برای کارت‌ها
    function animateNumber(el, target, duration = 1000) {
        let start = 0;
        const range = target - start;
        const startTime = performance.now();

        function step(now) {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const value = Math.floor(start + (range * progress));
            el.textContent = value.toLocaleString('fa-IR');
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    // انیمیشن برای تمام اعداد
    document.querySelectorAll('.animate-number').forEach(el => {
        const target = parseInt(el.getAttribute('data-target')) || 0;
        animateNumber(el, target, 1200 + Math.random() * 800);
    });

    // انیمیشن برای داده‌های واقعی از کامپوننت
    function animateRealDataNumbers() {
        // امروز
        const todayProfitEl = document.querySelector('[data-target="today-profit"]');
        const todaySaleEl = document.querySelector('[data-target="today-sale"]');
        const thisMonthSaleEl = document.querySelector('[data-target="this-month-sale"]');
        
        if (todayProfitEl) {
            animateNumber(todayProfitEl, @json($todayprofit), 1500);
        }
        if (todaySaleEl) {
            animateNumber(todaySaleEl, @json($todaysale), 1500);
        }
        if (thisMonthSaleEl) {
            animateNumber(thisMonthSaleEl, @json($thismonthsale), 1500);
        }
    }

    // اجرای انیمیشن پس از لود کامل صفحه
    setTimeout(animateRealDataNumbers, 500);

    // رفرش چارت هنگام تغییر داده‌ها
    Livewire.on('chartUpdated', () => {
        setTimeout(() => {
            profitChart.updateSeries([{
                name: 'فایده',
                data: @this.profitPerMonth
            }]);
            
            lossChart.updateSeries([{
                name: 'ضرر', 
                data: @this.lossPerMonth
            }]);
        }, 100);
    });

});
</script>