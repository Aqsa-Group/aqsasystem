<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RTL</title>
    @include('Sarafi.layouts.links')
    
    <style>
        /* لودر صفحه */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .loader-content {
            text-align: center;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #122EE1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        .loader-text {
            font-family: Vazir, sans-serif;
            color: #122EE1;
            font-size: 16px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fade-out {
            opacity: 0;
            pointer-events: none;
        }

        /* جلوگیری از کلیک هنگام لودینگ */
        body.loading {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-white font-vazir">

    <!-- لودر صفحه -->
    <div id="pageLoader" class="page-loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text">در حال بارگذاری...</div>
        </div>
    </div>

    <header class="bg-white w-full h-[80px] flex items-center justify-between px-6 shadow-[0_4px_4px_rgba(17,41,199,0.4)]">

        <!-- سمت راست: برند + زبان -->
        <div class="flex items-center space-x-4 rtl:space-x-reverse gap-6 justify-center ">
            <div class="text-[40px] text-[#122EE1] font-bold yekan">صرافی زرین</div>

            <!-- انتخاب زبان -->
            @php
                $locale = session('locale', config('app.locale'));
            @endphp

            <div class="relative inline-block w-[145px] h-[56px] p-2 vazir">
                <button id="dropdownButton"
                    class="border border-[#1129C766] bg-white rounded-lg px-3 py-2 w-full flex items-center justify-between font-vazir text-sm text-[#1129C7]">
                    <img src="{{ $locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png') }}"
                        class="w-5 h-5 ml-2" alt="Lang">
                    <span>
                        @if ($locale === 'fa')
                            فارسی
                        @elseif($locale === 'ps')
                            پشتو
                        @else
                            English
                        @endif
                    </span>
                </button>

                <ul id="dropdownMenu"
                    class="absolute left-0 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                    <li>
                        <a href="{{ route('set-locale', 'fa') }}" class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2" alt="fa">
                            فارسی
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('set-locale', 'ps') }}" class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2" alt="ps">
                            پشتو
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('set-locale', 'en') }}" class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="{{ asset('assets/sarafi/all_icon/united.png') }}" class="w-5 h-5 ml-2" alt="en">
                            English
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- سمت چپ: سرچ، هشدار، پروفایل -->
        <div class="flex items-center space-x-4 gap-1 pl-10 rtl:space-x-reverse">
            <!-- سرچ -->
            <div class="relative">
                <input type="text" placeholder="جستجو..."
                    class="border border-[#8C8C8C] placeholder:text-black vazir rounded-full px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">
                <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                    class="h-5 w-5 absolute left-2 bottom-3 transform">
            </div>

            <!-- دکمه اعلان -->
            <button
                class="relative flex items-center justify-center w-10 h-10 rounded-full bg-[#E5E5E5] hover:bg-gray-300 transition">
                <img src="{{ asset('assets/sarafi/all_icon/notification.png') }}" alt="اعلان" class="w-5 h-5">
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">
                    3
                </span>
            </button>

            <!-- پروفایل -->
            <div
                class="w-10 h-10 rounded-full overflow-hidden bg-[#E5E5E5] flex items-center justify-center hover:bg-gray-300 transition">
                <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="پروفایل" class="w-7 h-7 object-cover">
            </div>
        </div>
    </header>

    <!-- بخش اصلی: سایدبار + محتوا -->
    <div class="flex flex-1 min-h-screen">
        <!-- سایدبار -->
        <aside class="w-64 hidden md:block p-5">
            <nav class="mt-4 space-y-1" x-data="{ openSettings: false, active: 'dashboard' }">
                <!-- داشبورد -->
                <a href="{{ route('sarafi.home') }}" class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                    @click="active = 'dashboard'" 
                    :class="active === 'dashboard' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/element-3.svg') }}" class="w-5 h-5"
                            :class="active === 'dashboard' ? 'filter invert brightness-0' : 'text-gray-500'">
                        داشبورد
                    </span>
                </a>

                <!-- کاربران -->
                <a href="#" class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                    @click="active = 'users'"
                    :class="active === 'users' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/profile-2user.svg') }}" class="w-5 h-5"
                            :class="active === 'users' ? 'filter invert brightness-0' : 'text-gray-500'">
                        کاربران
                    </span>
                </a>

                <!-- مشتریان (با زیرمنو) -->
                <div>
                    <button @click="openSettings = !openSettings; active = 'settings'"
                        :class="active === 'settings' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                        class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/people.svg') }}" class="w-5 h-5"
                                :class="active === 'settings' ? 'filter invert brightness-0' : 'text-gray-500'">
                            مشتریان
                        </span>
                        <svg :class="[openSettings ? 'rotate-180' : '', active === 'settings' ? 'text-white' : 'text-gray-500']"
                            class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- زیرمنو -->
                    <div x-show="openSettings" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('sarafi.customer-create') }}" class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                            @click="active = 'settings-profile'"
                            :class="active === 'settings-profile' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                            <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" class="w-4 h-4"
                                :class="active === 'settings-profile' ? 'filter invert brightness-0' : 'text-gray-500'">
                            ثبت مشتری
                        </a>
                    </div>
                </div>
            </nav>
        </aside>
        
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script>
        // لودر صفحه
        const pageLoader = document.getElementById('pageLoader');
        
        // مخفی کردن لودر بعد از لود کامل صفحه
        window.addEventListener('load', function() {
            setTimeout(() => {
                pageLoader.classList.add('fade-out');
                document.body.classList.remove('loading');
                
                // حذف کامل لودر از DOM بعد از انیمیشن
                setTimeout(() => {
                    pageLoader.style.display = 'none';
                }, 300);
            }, 500);
        });

        // نمایش لودر هنگام کلیک روی لینک‌ها
        document.addEventListener('DOMContentLoaded', function() {
            // لینک‌های نویگیشن
            const navLinks = document.querySelectorAll('.nav-link');
            const localeLinks = document.querySelectorAll('.locale-link');
            
            const allLinks = [...navLinks, ...localeLinks];
            
            allLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // فقط برای لینک‌های داخلی
                    if (this.getAttribute('href') && !this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        
                        // نمایش لودر
                        pageLoader.style.display = 'flex';
                        pageLoader.classList.remove('fade-out');
                        document.body.classList.add('loading');
                        
                        // رفتن به صفحه بعد از نمایش لودر
                        setTimeout(() => {
                            window.location.href = href;
                        }, 300);
                    }
                });
            });
            
            // مدیریت dropdown زبان
            const btn = document.getElementById('dropdownButton');
            const menu = document.getElementById('dropdownMenu');
            
            if (btn && menu) {
                btn.addEventListener('click', () => menu.classList.toggle('hidden'));
                
                menu.querySelectorAll('li').forEach(li => {
                    li.addEventListener('click', () => {
                        btn.innerHTML = li.innerHTML;
                        menu.classList.add('hidden');
                    });
                });
            }
        });

        // نمایش لودر هنگام submit فرم‌ها (اگر فرمی دارید)
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                pageLoader.style.display = 'flex';
                pageLoader.classList.remove('fade-out');
                document.body.classList.add('loading');
            }
        });
    </script>

</body>

</html>