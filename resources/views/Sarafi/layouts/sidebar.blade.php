<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل مدیریت')</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Links -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jalaali-js@1.1.0/dist/jalaali.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-jalaali/build/moment-jalaali.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        

        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s;
        }

        @font-face {
            font-family: 'Vazir';
            src: url('{{ asset('fonts/amiri-regular.ttf') }}') format('truetype');
        }


        @font-face {
            font-family: 'header';
            src: url('{{ asset('fonts/Mj_Afrigha.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'header';
            src: url('{{ asset('fonts/B Titr Bold_0.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Vazir';
        }

        .sidebar-link {
            color: #637381;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background-color: #212b36;
            color: #fff;
        }

        .active-link {
            background-color: #e5e7eb;
            color: #2563eb;
            font-weight: bold;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 {{ session('locale') == 'en' ? 'ltr text-left' : 'rtl text-right' }}">

    <!-- From Uiverse.io by escannord --> 
<div class="loader">
  <span style="--i:1" class="element"></span>
  <span style="--i:2" class="element"></span>
  <span style="--i:3" class="element"></span>
  <span style="--i:4" class="element"></span>
  <span style="--i:5" class="element"></span>
  <span style="--i:6" class="element"></span>
  <span style="--i:7" class="element"></span>
  <span style="--i:8" class="element"></span>
  <span style="--i:9" class="element"></span>
  <span style="--i:10" class="element"></span>
  <span style="--i:11" class="element"></span>
  <span style="--i:12" class="element"></span>
  <span style="--i:13" class="element"></span>
  <span style="--i:14" class="element"></span>
  <span style="--i:15" class="element"></span>
</div>

    <!-- Sidebar + Main -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed lg:static top-0 left-0 h-full w-40 bg-white shadow-lg transition-all duration-300 transform -translate-x-full lg:translate-x-0 z-50">
            <div class="p-4 text-center border-b flex justify-between items-center">
                <h1 id="sidebar-title" class="text-xl font-bold text-blue-600">
                    {{ __('messages.exchange_name') }}
                </h1>
                <!-- دکمه toggle -->
                <button id="toggle-btn" class="hidden lg:inline-block p-2 border rounded-lg">
                    <span class="material-icons">menu_open</span>
                </button>
                <!-- دکمه بستن فقط برای موبایل -->
                <button id="menu-btn" class="lg:hidden p-2 border rounded-lg">✖</button>
            </div>

            <!-- لینک‌های منو -->
            <nav class="p-4 space-y-2">
                <a href="#"
                    class="flex items-center p-2 rounded-lg sidebar-link {{ request()->is('/') ? 'active-link' : '' }}">
                    <span class="material-icons">dashboard</span>
                    <span class="ml-2 sidebar-text">{{ __('messages.dashboard') }}</span>
                </a>
                <a href="#"
                    class="flex items-center p-2 rounded-lg sidebar-link {{ request()->is('users*') ? 'active-link' : '' }}">
                    <span class="material-icons">people</span>
                    <span class="ml-2 sidebar-text">{{ __('messages.users') }}</span>
                </a>

                <div>
                    <button id="products-btn"
                        class="flex items-center justify-between w-full p-2 rounded-lg sidebar-link">
                        <div class="flex items-center">
                            <span class="material-icons ">shopping_cart</span>
                            <span class="ml-2 sidebar-text">{{ __('messages.products') }}</span>
                        </div>
                        <span id="products-arrow" class="material-icons transition-transform hidden">expand_more</span>
                    </button>
                    <div id="products-submenu" class="ml-8 mt-1 space-y-1 hidden">
                        <a href="#" class="block p-2 rounded-lg submenu-link">{{ __('messages.pastry') }}</a>
                        <a href="#" class="block p-2 rounded-lg submenu-link">{{ __('messages.clothes') }}</a>
                    </div>
                </div>
                
            </nav>
        </aside>

        <!-- Overlay موبایل -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden lg:hidden z-40"></div>

        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="flex flex-wrap items-center justify-between gap-3 bg-white shadow px-4 py-3">
                <!-- دکمه بازکردن منو در موبایل -->
                <button id="open-menu" class="lg:hidden p-2 border rounded-lg">☰</button>

                            @php
                            $locale = session('locale', config('app.locale'));
                        @endphp
                      

        
                        <div class="relative inline-block text-left">
                            <button id="lang-btn" class="flex items-center gap-2 border rounded-lg px-2 justify-center py-1 text-sm sm:mr-20">
                                <img src="{{ $locale === 'en' ? asset('assets/icon/us.png') : asset('assets/icon/af.png') }}"
                                     class="w-5 h-5" alt="Lang">
                                <span class="hidden sm:inline">
                                    @if ($locale === 'fa')
                                        فارسی
                                    @elseif($locale === 'ps')
                                        پشتو
                                    @else
                                        English
                                    @endif
                                </span>
                            </button>
        
                            <div id="lang-menu" class="hidden absolute mt-1 w-32 bg-white border rounded-lg shadow-lg z-50">
                                <a href="{{ route('set-locale', 'fa') }}" class="flex items-center gap-2 px-3 py-1 hover:bg-gray-100">
                                    <img src="{{ asset('assets/icon/af.png') }}" class="w-5 h-5"> فارسی
                                </a>
                                <a href="{{ route('set-locale', 'ps') }}" class="flex items-center gap-2 px-3 py-1 hover:bg-gray-100">
                                    <img src="{{ asset('assets/icon/af.png') }}" class="w-5 h-5"> پشتو
                                </a>
                                <a href="{{ route('set-locale', 'en') }}" class="flex items-center gap-2 px-3 py-1 hover:bg-gray-100">
                                    <img src="{{ asset('assets/icon/us.png') }}" class="w-5 h-5"> English
                                </a>
                            </div>
                        </div>
        
           
            
                <!-- ساعت و تاریخ -->

                <div id="jalali-clock" class=" text-lg w-64"></div>


                <!-- سرچ + پروفایل -->
                <div class="flex items-center gap-3 flex-1 sm:flex-initial sm:justify-end">
                    <div class="relative flex-1 sm:flex-initial">
                        <input type="text"
                            class="w-full sm:w-48 border rounded-2xl py-1 pl-8 pr-2 outline-none border-gray-300 text-sm"
                            placeholder="{{ __('messages.search_placeholder') != 'messages.search_placeholder' ? __('messages.search_placeholder') : 'Search...' }}">

                        <img src="{{ asset('assets/icon/search.svg') }}" class="absolute top-1.5 left-2 w-4 h-4">
                    </div>
                    <img src="{{ asset('assets/icon/notification-bing.svg') }}" class="w-5 h-5">
                    <img src="{{ asset('assets/icon/user.png') }}" class="w-6 h-6 rounded-full border cursor-pointer">
                </div>
            </header>

            <!-- Page content -->
            <main class="p-4 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const openMenu = document.getElementById("open-menu");
        const menuBtn = document.getElementById("menu-btn");
        const toggleBtn = document.getElementById("toggle-btn");
        const productsBtn = document.getElementById("products-btn");
        const productsSubmenu = document.getElementById("products-submenu");
        const productsArrow = document.getElementById("products-arrow");
        const sidebarLinks = document.querySelectorAll(".sidebar-link");

        // active link
        function setActiveLink(activeLink) {
            sidebarLinks.forEach(link => {
                link.style.backgroundColor = "";
                link.style.color = "";
            });
            activeLink.style.backgroundColor = "#212b36";
            activeLink.style.color = "#ffffff";
        }

        document.addEventListener("DOMContentLoaded", () => {
            const dashboardLink = sidebarLinks[0];
            setActiveLink(dashboardLink);
        });

        sidebarLinks.forEach(link => {
            link.addEventListener("click", () => {
                setActiveLink(link);
            });
        });


        // sub-items 
           document.addEventListener("DOMContentLoaded", () => {
            if (!collapsed) {
                productsArrow.style.display = "inline-flex";
                if (!productsSubmenu.classList.contains("hidden")) {
                    productsArrow.classList.add("rotate-180");
                }
            } else {
                productsArrow.style.display = "none";
            }
        });

        // Toggle sidebar
        menuBtn.addEventListener("click", () => {
            collapsed = !collapsed;

            if (collapsed) {
                sidebar.classList.remove("w-56");
                sidebar.classList.add("w-4");
                sidebarTexts.forEach(t => t.classList.add("hidden"));
                sidebarTitle.classList.add("hidden");
                productsSubmenu.classList.add("hidden");
                productsArrow.style.display = "none";
            } else {
                sidebar.classList.remove("w-20");
                sidebar.classList.add("w-40");
                sidebarTexts.forEach(t => t.classList.remove("hidden"));
                sidebarTitle.classList.remove("hidden");
                productsArrow.style.display = "inline-flex";
            }
        });

        // Toggle products submenu
        productsBtn.addEventListener("click", () => {
            if (collapsed) return;

            productsSubmenu.classList.toggle("hidden");
            productsArrow.classList.toggle("rotate-180");

            productsArrow.style.display = "inline-flex";
        });

        openMenu.addEventListener("click", () => {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");

            sidebar.classList.add("w-64");
            sidebar.classList.remove("w-20");
            document.querySelectorAll(".sidebar-text").forEach(t => t.classList.remove("hidden"));
            document.getElementById("sidebar-title").classList.remove("hidden");
        });
        menuBtn.addEventListener("click", closeSidebar);
        overlay.addEventListener("click", closeSidebar);

        function closeSidebar() {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        }

        let collapsed = false;
        toggleBtn.addEventListener("click", () => {
            collapsed = !collapsed;
            if (collapsed) {
                sidebar.classList.add("w-20");
                sidebar.classList.remove("w-42");
                document.querySelectorAll(".sidebar-text").forEach(t => t.classList.add("hidden"));
                document.getElementById("sidebar-title").classList.add("hidden");
            } else {
                sidebar.classList.add("w-42");
                sidebar.classList.remove("w-20");
                document.querySelectorAll(".sidebar-text").forEach(t => t.classList.remove("hidden"));
                document.getElementById("sidebar-title").classList.remove("hidden");
            }

            if (!collapsed) {
                productsArrow.style.display = "inline-flex";
                if (!productsSubmenu.classList.contains("hidden")) {
                    productsArrow.classList.add("rotate-180");
                }
            } else {
                productsArrow.style.display = "none";
            }
        });

        document.getElementById('lang-btn').addEventListener('click', () => {
            document.getElementById('lang-menu').classList.toggle('hidden');
        });

        function updateClock() {
            const locale = "{{ session('locale', 'fa') }}";
            const now = new Date();
            const j = jalaali.toJalaali(now.getFullYear(), now.getMonth() + 1, now.getDate());

            let hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();

            let ampm = hours >= 12 ? 'بعد از ظهر' : 'قبل از ظهر';
            if (locale === 'en') {
                ampm = hours >= 12 ? 'PM' : 'AM';
            }

            hours = hours % 12;
            hours = hours ? hours : 12; 

            const timeStr = hours.toString().padStart(2, '0') + ':' +
                minutes.toString().padStart(2, '0') + ':' +
                seconds.toString().padStart(2, '0') + ' ' + ampm;

            const dateStr = j.jy + '/' + j.jm.toString().padStart(2, '0') + '/' + j.jd.toString().padStart(2, '0');

            const clockDiv = document.getElementById('jalali-clock');

            clockDiv.innerHTML = `<div class="flex gap-4">${dateStr}<span>${timeStr}</span></div>`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        window.addEventListener("beforeunload", () => {
            document.getElementById("loader").classList.remove("hidden");
        });
    </script>

    @stack('scripts')
</body>

</html>
