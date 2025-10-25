<div>
    <div class="p-10 space-y-10">
        <h1 class="text-4xl yekan ">گزارش موجودی صندوق صرافی و دوکان</h1>
        <h1 class="text-2xl"> موجودی صندوق صرافی</h1>

        <div class="grid grid-cols-1 sm:grid-cols-۴ lg:grid-cols-4 xl:grid-cols-۴ gap-6 p-4 mb-10">
            <!-- مبلغ افغانی -->
            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">افغانی</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalAFNSarafi }}">
                        {{ number_format($totalAFNSarafi, 2) }}
                    </p>
                </div>
            </div>

            <!-- مبلغ دالر -->
            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">دالر</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalUSDSarafi }}">0</p>
                </div>
            </div>


            <!-- مبلغ تومان -->
            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">تومان</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalIRRSarafi }}">0</p>
                </div>
            </div>


            <!-- مبلغ کلدار -->

            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">کلدار</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalPKRSarafi }}">0</p>
                </div>
            </div>
        </div>

        <hr>
        <h1 class="text-2xl"> موجودی صندوق دوکان</h1>


        <div class="grid grid-cols-1 sm:grid-cols-۴ lg:grid-cols-4 xl:grid-cols-۴ gap-6 p-4 mb-10">
            <!-- مبلغ افغانی -->
            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">افغانی</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalAFNShop }}">
                        {{ number_format($totalAFNSarafi, 2) }}
                    </p>
                </div>
            </div>

            <!-- مبلغ دالر -->
            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">دالر</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalUSDShop }}">0</p>
                </div>
            </div>


            <!-- مبلغ تومان -->
            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">تومان</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalIRRShop }}">0</p>
                </div>
            </div>


            <!-- مبلغ کلدار -->

            <div
                class=" bg-gradient-to-br from-black to-blue-400 border-l-4 border-pink-500 text-white p-6 rounded-xl shadow-lg transition-all duration-300  hover:shadow-xl hover:scale-105 h-40 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">کلدار</h3>
                    <div class="bg-gray-400 p-2 rounded-full">
                        <img src="{{ asset('assets/sarafi/all_icon/coin.svg') }}" alt="">
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold mt-1 animate-number" data-target="{{ $totalPKRShop }}">0</p>
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
</div>