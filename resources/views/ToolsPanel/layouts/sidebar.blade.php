<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم ابزارآلات اقصی</title>
    @include('Sarafi.layouts.links')

    <style>
        /* لودر تمام صفحه فوق العاده زیبا */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #161c0f 0%, #5f502d 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .loader-container {
            text-align: center;
            animation: fadeInUp 1s ease;
        }

        .spinner-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
        }

        .spinner {
            position: absolute;
            border: 4px solid transparent;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        .spinner-1 {
            width: 120px;
            height: 120px;
            border-top: 4px solid #122EE1;
            border-bottom: 4px solid #122EE1;
            animation-duration: 1.5s;
        }

        .spinner-2 {
            width: 100px;
            height: 100px;
            top: 10px;
            left: 10px;
            border-left: 4px solid #FF6B6B;
            border-right: 4px solid #FF6B6B;
            animation-duration: 2s;
            animation-direction: reverse;
        }

        .spinner-3 {
            width: 80px;
            height: 80px;
            top: 20px;
            left: 20px;
            border-top: 4px solid #4ECDC4;
            border-bottom: 4px solid #4ECDC4;
            animation-duration: 2.5s;
        }

        .logo-loader {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 30px;
            left: 30px;
            box-shadow: 0 0 20px rgba(18, 46, 225, 0.3);
        }

        .logo-loader span {
            font-size: 24px;
            font-weight: bold;
            color: #122EE1;
            font-family: 'Yekan', sans-serif;
        }

        .loader-text {
            color: white;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
            font-family: 'Vazir', sans-serif;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .loader-subtext {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-family: 'Vazir', sans-serif;
        }

        .progress-bar {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin: 20px auto 0;
            overflow: hidden;
        }

        .progress {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #122EE1, #4ECDC4);
            border-radius: 2px;
            animation: progress 3s ease-in-out infinite;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .element-1 {
            width: 20px;
            height: 20px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .element-2 {
            width: 15px;
            height: 15px;
            top: 60%;
            left: 80%;
            animation-delay: 1s;
        }

        .element-3 {
            width: 25px;
            height: 25px;
            top: 80%;
            left: 20%;
            animation-delay: 2s;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loader-complete {
            opacity: 0;
            visibility: hidden;
        }

        /* افکت‌های اضافی */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* محتوای اصلی - اصلاح شده */
        #mainContent {
            display: none;
            /* ابتدا مخفی باشد */
            opacity: 1;
            /* opacity را 1 قرار دهید */
        }

        .content-loaded {
            display: block;
            /* نمایش داده شود */
            opacity: 1;
            /* کاملاً قابل دیدن */
        }

        /* استایل‌های دارک مود */
        /* انیمیشن حرکت توگل */
        #toggleCircle {
            transition: transform 0.3s ease-in-out;
        }

        [dir="rtl"] #toggleCircle.move-dark {
            transform: translateX(-2rem);
        }

        [dir="ltr"] #toggleCircle.move-dark {
            transform: translateX(2rem);
        }

        /* حالت دارک */
        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: #1a202c;
            color: #e2e8f0;
            @apply text-white
        }

        .dark header {
            background-color: #2d3748;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);
        }

        .dark aside {
            background-color: #2d3748;
        }

        .dark input {
            background-color: #4a5568;
            color: #e2e8f0;
            border-color: #718096;
        }
    </style>
</head>

<body class="vazir dark:text-white">

    <!-- لودر فوق العاده زیبا -->
    <div id="loader">
        <div class="floating-elements">
            <div class="floating-element element-1"></div>
            <div class="floating-element element-2"></div>
            <div class="floating-element element-3"></div>
        </div>

        <div class="loader-container pulse">
            <div class="spinner-wrapper">
                <div class="spinner spinner-1"></div>
                <div class="spinner spinner-2"></div>
                <div class="spinner spinner-3"></div>
                <div class="logo-loader">
                    <span>{{ mb_substr(Auth::guard('tools')->user()->company_name, 0, 1) }}</span>
                </div>
            </div>

            <div class="loader-text">فروشگاه {{ Auth::guard('tools')->user()->company_name }}</div>
            <div class="loader-subtext">در حال بارگذاری...</div>

            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div id="mainContent">
        <header
            class="bg-white w-full h-[80px] flex items-center justify-between px-6 shadow-[0_4px_4px_rgba(32,41,199,0.4)]">
            <div class="flex items-center space-x-4 rtl:space-x-reverse gap-6 justify-center ">
                <div class="text-[40px] text-[#353e73] font-bold amiri"> شرکت {{
                    Auth::guard('tools')->user()->company_name }}
                </div>
            </div>





            <!-- سرچ، اعلان، پروفایل -->
            <div class="flex items-center space-x-4 gap-1  pl-10 rtl:space-x-reverse">
                <div class="relative">
                    <input type="text" placeholder="{{ __('messages.search_placeholder') }}"
                        class="border border-[#8C8C8C] placeholder:text-black vazir rounded-2xl px-3 py-3 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                        class="h-7 w-7 absolute left-2 bottom-3">
                </div>

                <button
                    class="relative flex items-center justify-center w-[50px] h-[50px] rounded-[25px] bg-[#E5E5E5] hover:bg-gray-300 transition">
                    <img src="{{ asset('assets/sarafi/all_icon/bill-header.svg') }}" alt="اعلان" class="w-7 h-7">
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                </button>

                <div class="relative">
                    <div id="profileBtn"
                        class="w-[70px] h-[70px] rounded-full border  overflow-hidden flex items-center justify-center cursor-pointer transition">
                        <img src="{{ asset('assets/tools/man.png') }}" alt="پروفایل"
                            class="w-[50px] h-[50px] object-cover">
                    </div>

                    <!-- منو dropdown -->
                    <div id="profileDropdown" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;"
                        class="absolute top-full left-0 space-y-3 text-2xl w-72 h-76 bg-white rounded-lg shadow-lg border hidden z-50 p-4">

                        <div class="p-3 border-b space-y-5">
                            <div class="flex flex-col justify-center items-center ">
                                <img src="{{ asset('assets/tools/man.png') }}" alt="" class="h-20 w-20">
                                <p class="font-vazir font-semibold text-gray-700 mt-5">{{
                                    Auth::guard('tools')->user()->name }}</p>

                            </div>

                        </div>
                        <div class="flex justify-start items-center  ">
                            <img src="{{ asset('assets/sarafi/all_icon/account_profile.svg') }}" alt="">

                            <a href="{{ route('tools.users') }}" class="block px-4 py-2 text-gray-700 vazir">تنظیمات</a>
                        </div>

                        <form action="{{ route('tools.logout') }}" method="POST">
                            @csrf
                            <div class="flex justify-start items-center">
                                <img src="{{ asset('assets/sarafi/all_icon/logout.svg') }}" alt="">
                                <button type="submit" class="px-4 py-2 text-gray-700 vazir">
                                    خروج از حساب
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 mt-4 min-h-screen sticky ">
            <aside class="w-72 hidden md:block shadow-xl p-5 dark:text-white static">
                <nav class="mt-0 space-y-0" x-data="{
                            openItems: {
                                customers: false,
                                accounting: false
                            },
                            active: '{{ Route::currentRouteName() }}' // همواره با route فعلی هماهنگ
                        }">

                    <!-- داشبورد -->
                    <a href="{{ route('tools.home') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                        @click="active = '{{ Route::currentRouteName() }}'"
                        :class="active === 'tools.home' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/element-3.svg') }}" class="w-5 h-5"
                                :class="active === 'tools.home' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            {{ __('messages.dashboard') }}
                        </span>
                    </a>

                    <!-- کاربران -->
                    <a href="{{ route('tools.users') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir dark:text-white"
                        @click="active = 'tools.users'"
                        :class="active === 'tools.users' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile-2user.svg') }}" class="w-5 h-5"
                                :class="active === 'tools.users' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            {{ __('messages.users') }}
                        </span>
                    </a>

                    <!-- مشتریان -->
                    <div>
                        <button @click="openItems.customers = !openItems.customers"
                            :class="active.startsWith('tools.customer') ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/people.svg') }}" class="w-5 h-5"
                                    :class="active.startsWith('tools.customer') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.customers') }}
                            </span>
                            <svg :class="[openItems.customers ? 'rotate-180' : '', active.startsWith('tools.customer') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openItems.customers" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('tools.customer-create') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="active = 'tools.customer-create'"
                                :class="active === 'tools.customer-create' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <i class="fa-solid fa-user-pen w-4 h-4"
                                    :class="active === 'tools.customer-create' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_create') }}
                            </a>

                            <a href="{{ route('tools.customer-table') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="active = 'tools.customer-table'"
                                :class="active === 'tools.customer-table' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <i class="fa-solid fa-users-gear h-4 w-4"
                                    :class="active === 'tools.customer-table' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_list') }}
                            </a>
                        </div>
                    </div>

                    <!-- حسابداری -->
                    <div>
                        <!-- دکمه حسابداری -->
                        <button @click="openItems.accounting = !openItems.accounting"
                            :class="active.startsWith('tools.loans') ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/tools/gameboy.svg') }}" class="w-5 h-5"
                                    :class="active.startsWith('tools.loans') ? 'filter invert brightness-0' : 'text-gray-500'">
                                حسابداری
                            </span>
                            <svg :class="[openItems.accounting ? 'rotate-180' : '', active.startsWith('tools.loans') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- زیرمنو -->
                        <div x-show="openItems.accounting" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('tools.loans') }}"
                                @click="active = 'tools.loans'; openItems.accounting = true"
                                :class="active === 'tools.loans' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir">
                                <i class="fa-solid fa-money-bill-transfer w-4 h-4"
                                    :class="active === 'tools.loans' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                قرضه ها
                            </a>
                        </div>
                    </div>


                </nav>
            </aside>


            <main class="flex-1  ">
                @yield('content')
            </main>
        </div>



    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader');
    const mainContent = document.getElementById('mainContent');
    const progressBar = document.querySelector('.progress');

    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    profileBtn.addEventListener('click', () => {
        profileDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });

    // محتوا را ابتدا مخفی کن
    mainContent.style.display = 'none';

    let progress = 0;
    let fakeProgressInterval;

    // شروع شبیه‌سازی پیشرفت فقط اگر لود طول بکشد
    function startFakeProgress() {
        fakeProgressInterval = setInterval(() => {
            progress += Math.random() * 30; // سرعت متوسط
            if (progress > 90) progress = 90; // رسیدن به 90% و منتظر load واقعی
            progressBar.style.width = progress + '%';
        },10);
    }

    startFakeProgress();

    // وقتی صفحه واقعاً لود شد
    window.addEventListener('load', function() {
        clearInterval(fakeProgressInterval);
        progress = 100;
        progressBar.style.width = progress + '%';

        setTimeout(() => {
            loader.classList.add('loader-complete');
            mainContent.style.display = 'block';
            mainContent.classList.add('content-loaded');

            setTimeout(() => {
                loader.style.display = 'none';
            }, 400);
        }, 600); // کمی تأخیر برای انیمیشن
    });

    // مدیریت کلیک روی لینک‌ها
    const navLinks = document.querySelectorAll('.nav-link, .locale-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#')) {
                e.preventDefault();
                loader.style.display = 'flex';
                loader.classList.remove('loader-complete');
                setTimeout(() => window.location.href = href, 50);
            }
        });
    });

    // مدیریت dropdown زبان
    const btn = document.getElementById('dropdownButton');
    const menu = document.getElementById('dropdownMenu');
    if (btn && menu) {
        btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        document.addEventListener('click', e => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }
});

const darkModeToggle = document.getElementById('darkModeToggle');
const sunIcon = document.getElementById('sunIcon');
const moonIcon = document.getElementById('moonIcon');
const toggleCircle = document.getElementById('toggleCircle');
const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
const html = document.documentElement;

// بررسی حالت ذخیره‌شده
const currentTheme = localStorage.getItem('theme');
if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
    html.classList.add('dark');
    darkModeToggle.checked = true;
    sunIcon.classList.add('hidden');
    moonIcon.classList.remove('hidden');
    toggleCircle.classList.add('move-dark');
} else {
    toggleCircle.classList.remove('move-dark');
}

darkModeToggle.addEventListener('change', function() {
    if (this.checked) {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
        toggleCircle.classList.add('move-dark');
    } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
        toggleCircle.classList.remove('move-dark');
    }
});
    </script>

</body>

</html>