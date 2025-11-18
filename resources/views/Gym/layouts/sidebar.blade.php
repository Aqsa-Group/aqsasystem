<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فیت کلاب - سیستم مدیریت</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Vazirmatn:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Vazirmatn', sans-serif;
        }
        
        .amiri {
            font-family: 'Amiri', serif;
        }
        
        .vazir {
            font-family: 'Vazirmatn', sans-serif;
        }

        /* لودر ورزشی */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .loader-container {
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .fitness-loader {
            width: 180px;
            height: 180px;
            position: relative;
            margin: 0 auto 40px;
        }

        .outer-ring {
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top: 3px solid #e74c3c;
            border-right: 3px solid #e74c3c;
            border-radius: 50%;
            animation: spin 2s linear infinite;
            position: absolute;
        }

        .middle-ring {
            width: 140px;
            height: 140px;
            border: 3px solid transparent;
            border-top: 3px solid #3498db;
            border-right: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1.5s linear infinite reverse;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .inner-ring {
            width: 100px;
            height: 100px;
            border: 3px solid transparent;
            border-top: 3px solid #2ecc71;
            border-right: 3px solid #2ecc71;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 40px;
            left: 40px;
        }

        .center-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #e74c3c, #3498db);
            border-radius: 50%;
            position: absolute;
            top: 60px;
            left: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(231, 76, 60, 0.5);
            animation: pulse 2s infinite;
        }

        .center-logo i {
            font-size: 24px;
            color: white;
        }

        .loader-text {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(90deg, #e74c3c, #3498db, #2ecc71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .loader-subtext {
            font-size: 16px;
            color: #bdc3c7;
            margin-bottom: 30px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .progress-container {
            width: 300px;
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #e74c3c, #3498db);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .weight-loader {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 30px auto 20px;
        }

        .weight {
            width: 20px;
            height: 60px;
            background: linear-gradient(to bottom, #e74c3c, #c0392b);
            border-radius: 5px;
            animation: weight-lift 1.5s ease-in-out infinite;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .weight:nth-child(2) {
            animation-delay: 0.2s;
            background: linear-gradient(to bottom, #3498db, #2980b9);
        }

        .weight:nth-child(3) {
            animation-delay: 0.4s;
            background: linear-gradient(to bottom, #2ecc71, #27ae60);
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
            animation: float 8s ease-in-out infinite;
        }

        .element-1 {
            width: 30px;
            height: 30px;
            top: 15%;
            left: 10%;
            background: rgba(231, 76, 60, 0.3);
            animation-delay: 0s;
        }

        .element-2 {
            width: 20px;
            height: 20px;
            top: 70%;
            left: 85%;
            background: rgba(52, 152, 219, 0.3);
            animation-delay: 1s;
        }

        .loader-info {
            display: flex;
            justify-content: space-between;
            width: 300px;
            margin: 20px auto 0;
            font-size: 12px;
            color: #7f8c8d;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(231, 76, 60, 0.5);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 0 30px rgba(231, 76, 60, 0.8);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(231, 76, 60, 0.5);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }

        @keyframes weight-lift {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .loader-complete {
            opacity: 0;
            visibility: hidden;
        }

        /* استایل‌های اصلی */
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --border-color: #475569;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            height: 80px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .mobile-menu-icon span {
            display: block;
            height: 3px;
            width: 100%;
            background-color: white;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .company-name {
            color: white;
        }

        .search-input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        .notification-btn {
            background-color: rgba(255, 255, 255, 0.1);
            width: 45px;
            height: 45px;
        }

        .profile-image {
            width: 45px;
            height: 45px;
            border: 2px solid var(--border-color);
        }

        .profile-dropdown {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .content-wrapper {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        .sidebar-container {
            width: 280px;
            background-color: var(--bg-secondary);
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .nav-link {
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        .main-content-container {
            flex: 1;
            padding: 1.5rem;
            background-color: var(--bg-primary);
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .theme-toggle {
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--text-primary);
            font-size: 1.5rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* استایل‌های نمونه برای محتوا */
        .sample-card {
            background-color: var(--bg-card);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        /* استایل‌های زیرمنو */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .submenu.open {
            max-height: 500px;
        }

        .submenu-item {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            border-right: 2px solid transparent;
        }

        .submenu-item:hover {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
            border-right-color: #6366f1;
        }

        .submenu-item.active {
            background-color: rgba(99, 102, 241, 0.2);
            color: var(--text-primary);
            border-right-color: #6366f1;
        }

        .menu-toggle {
            transition: transform 0.3s ease;
        }

        .menu-toggle.rotate {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
            }
            
            .sidebar-container {
                position: fixed;
                top: 0;
                right: -280px;
                height: 100vh;
                z-index: 50;
                transition: right 0.3s ease;
            }
            
            .sidebar-container.active {
                right: 0;
            }
            
            .mobile-overlay.active {
                display: block;
            }
            
            .header-container {
                padding: 0 1rem;
            }
            
            .header-right {
                gap: 1rem;
            }

            .fitness-loader {
                width: 140px;
                height: 140px;
            }
            
            .middle-ring {
                width: 100px;
                height: 100px;
                top: 20px;
                left: 20px;
            }
            
            .inner-ring {
                width: 70px;
                height: 70px;
                top: 35px;
                left: 35px;
            }
            
            .center-logo {
                width: 50px;
                height: 50px;
                top: 45px;
                left: 45px;
            }
            
            .loader-text {
                font-size: 22px;
            }
        }
    </style>
</head>

<body class="dark" x-data="{
    openMenu: 'dashboard',
    customerAccountingOpen: false,
    closeMobileMenu() {
        document.getElementById('mobileSidebar').classList.remove('active');
        document.getElementById('mobileOverlay').classList.remove('active');
    }
}">
    <!-- لودر -->
    <div id="loader">
        <div class="floating-elements">
            <div class="floating-element element-1"></div>
            <div class="floating-element element-2"></div>
        </div>
        
        <div class="loader-container">
            <div class="fitness-loader">
                <div class="outer-ring"></div>
                <div class="middle-ring"></div>
                <div class="inner-ring"></div>
                <div class="center-logo">
                    <i class="fas fa-dumbbell"></i>
                </div>
            </div>
            
            <div class="loader-text">فیت کلاب</div>
            <div class="loader-subtext">در حال آماده‌سازی محیط ورزشی شما</div>
            
            <div class="weight-loader">
                <div class="weight"></div>
                <div class="weight"></div>
                <div class="weight"></div>
            </div>
            
            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            
            <div class="loader-info">
                <span>سیستم مدیریت باشگاه ورزشی</span>
                <span id="progressText">0%</span>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div id="mainContent">
        <!-- هدر -->
        <header class="bg-[#191715] dark:bg-slate-900 w-full h-[80px]">
            <div class="header-container">
                <!-- بخش چپ هدر -->
                <div class="header-left">
                    <!-- دکمه منو موبایل -->
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <div class="mobile-menu-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>

                    <!-- نام شرکت -->
                    <div class="text-[40px] text-white font-bold amiri company-name">
                        فیت کلاب
                    </div>
                </div>

                <!-- بخش راست هدر -->
                <div class="header-right">
                    <!-- دکمه تغییر حالت دارک/روشن -->
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fa-regular fa-moon" id="themeIcon"></i>
                    </button>

                    <!-- جستجو -->
                    <div class="relative">
                        <input type="text" placeholder="جستجو..."
                            class="border border-[#8C8C8C] dark:border-slate-600 placeholder:text-black dark:placeholder:text-slate-400 vazir rounded-2xl px-3 py-3 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 search-input w-64">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-500 dark:text-slate-400"></i>
                    </div>

                    <!-- اعلان -->
                    <button
                        class="relative flex items-center justify-center rounded-[25px] bg-[#E5E5E5] dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 transition notification-btn w-12 h-12">
                        <i class="fa-regular fa-bell text-gray-700 dark:text-slate-300"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- پروفایل -->
                    <div class="relative">
                        <div id="profileBtn"
                            class="rounded-full border overflow-hidden flex items-center justify-center cursor-pointer transition profile-image w-12 h-12">
                            <i class="fas fa-user text-gray-700 dark:text-slate-300 text-xl"></i>
                        </div>

                        <!-- منو dropdown -->
                        <div id="profileDropdown"
                            class="absolute top-full left-0 space-y-3 text-2xl w-72 h-76 bg-white dark:bg-slate-800 rounded-lg shadow-lg border dark:border-slate-700 hidden z-50 p-4 profile-dropdown">

                            <div class="p-3 border-b dark:border-slate-600 space-y-5">
                                <div class="flex flex-col justify-center items-center ">
                                    <div class="h-20 w-20 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600 dark:text-indigo-300 text-3xl"></i>
                                    </div>
                                    <p class="font-vazir font-semibold text-gray-700 dark:text-slate-300 mt-5">
                                        مدیر سیستم
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex justify-start items-center p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition">
                                <i class="fas fa-cog text-gray-600 dark:text-slate-400 ml-3"></i>
                                <a href="#" class="block px-4 py-2 text-gray-700 dark:text-slate-300 vazir text-lg">تنظیمات</a>
                            </div>

                            <form action="#" method="POST">
                                <div class="flex justify-start items-center p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition">
                                    <i class="fas fa-sign-out-alt text-gray-600 dark:text-slate-400 ml-3"></i>
                                    <button type="submit" class="px-4 py-2 text-gray-700 dark:text-slate-300 vazir text-lg">
                                        خروج از حساب
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Overlay for mobile menu -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <div class="content-wrapper mt-5 p-0">
            <!-- سایدبار -->
            <div class="sidebar-container bg-[#191715] dark:bg-slate-900 h-full" id="mobileSidebar">
                <nav class="mt-0 space-y-1 w-[280px] p-4">
                    <!-- داشبورد -->
                    <a href="#"
                        class="nav-link flex items-center justify-between py-4 px-5 rounded-lg transition vazir text-[16px]"
                        :class="openMenu === 'dashboard' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800'"
                        @click="openMenu = 'dashboard'; closeMobileMenu()">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-chart-pie w-6 h-6"
                                :class="openMenu === 'dashboard' ? 'text-white' : 'text-gray-500 dark:text-slate-400'"></i>
                            داشبورد
                        </span>
                    </a>

                    <!-- کاربران -->
                    <a href="#"
                        class="nav-link flex items-center justify-between py-4 px-5 rounded-lg transition vazir text-[16px]"
                        :class="openMenu === 'users' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800'"
                        @click="openMenu = 'users'; closeMobileMenu()">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-users w-6 h-6"
                                :class="openMenu === 'users' ? 'text-white' : 'text-gray-500 dark:text-slate-400'"></i>
                            کاربران
                        </span>
                    </a>

                    <!-- کارمندان -->
                    <a href="#"
                        class="nav-link flex items-center justify-between py-4 px-5 rounded-lg transition vazir text-[16px]"
                        :class="openMenu === 'staff' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800'"
                        @click="openMenu = 'staff'; closeMobileMenu()">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-user-tie w-7 h-6"
                                :class="openMenu === 'staff' ? 'text-white' : 'text-gray-500 dark:text-slate-400'"></i>
                            ثبت کارمندان
                        </span>
                    </a>

                    <!-- مشتریان -->
                    <div class="mt-2">
                        <button class="flex items-center justify-between w-full py-4 px-5 rounded-lg transition vazir text-[16px] text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800"
                            @click="openMenu = 'customers'">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-user-friends w-6 h-6 text-gray-500 dark:text-slate-400"></i>
                                مشتریان
                            </span>
                            <i class="fas fa-chevron-down text-gray-500 dark:text-slate-400 text-sm menu-toggle"
                                :class="openMenu === 'customers' ? 'rotate' : ''"></i>
                        </button>
                        
                        <!-- زیرمنوی مشتریان -->
                        <div class="submenu mr-4 mt-2" :class="openMenu === 'customers' ? 'open' : ''">
                            <a href="#" class="submenu-item" :class="openMenu === 'customer-create' ? 'active' : ''"
                                @click="openMenu = 'customer-create'; closeMobileMenu()">
                                <i class="fas fa-user-plus ml-2 text-sm"></i>
                                ایجاد مشتری جدید
                            </a>
                            <a href="#" class="submenu-item" :class="openMenu === 'customer-list' ? 'active' : ''"
                                @click="openMenu = 'customer-list'; closeMobileMenu()">
                                <i class="fas fa-list ml-2 text-sm"></i>
                                لیست مشتریان
                            </a>
                            <a href="#" class="submenu-item" :class="openMenu === 'customer-groups' ? 'active' : ''"
                                @click="openMenu = 'customer-groups'; closeMobileMenu()">
                                <i class="fas fa-layer-group ml-2 text-sm"></i>
                                گروه‌های مشتریان
                            </a>
                        </div>
                    </div>

                    <!-- حسابداری مشتریان -->
                    <div class="mt-2">
                        <button class="flex items-center justify-between w-full py-4 px-5 rounded-lg transition vazir text-[16px] text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800"
                            @click="customerAccountingOpen = !customerAccountingOpen">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-calculator w-6 h-6 text-gray-500 dark:text-slate-400"></i>
                                حسابداری مشتریان
                            </span>
                            <i class="fas fa-chevron-down text-gray-500 dark:text-slate-400 text-sm menu-toggle"
                                :class="customerAccountingOpen ? 'rotate' : ''"></i>
                        </button>
                        
                        <!-- زیرمنوی حسابداری مشتریان -->
                        <div class="submenu mr-4 mt-2" :class="customerAccountingOpen ? 'open' : ''">
                            <a href="#" class="submenu-item" @click="openMenu = 'customer-accounts'; closeMobileMenu()">
                                <i class="fas fa-wallet ml-2 text-sm"></i>
                                حساب‌های مشتریان
                            </a>
                            <a href="#" class="submenu-item" @click="openMenu = 'customer-payments'; closeMobileMenu()">
                                <i class="fas fa-money-bill-wave ml-2 text-sm"></i>
                                پرداخت‌های مشتریان
                            </a>
                            <a href="#" class="submenu-item" @click="openMenu = 'customer-invoices'; closeMobileMenu()">
                                <i class="fas fa-receipt ml-2 text-sm"></i>
                                فاکتورهای مشتریان
                            </a>
                            <a href="#" class="submenu-item" @click="openMenu = 'customer-debts'; closeMobileMenu()">
                                <i class="fas fa-hand-holding-usd ml-2 text-sm"></i>
                                بدهی‌های مشتریان
                            </a>
                            <a href="#" class="submenu-item" @click="openMenu = 'customer-reports'; closeMobileMenu()">
                                <i class="fas fa-chart-bar ml-2 text-sm"></i>
                                گزارشات مالی مشتریان
                            </a>
                            <a href="#" class="submenu-item" @click="openMenu = 'customer-discounts'; closeMobileMenu()">
                                <i class="fas fa-percentage ml-2 text-sm"></i>
                                تخفیف‌های مشتریان
                            </a>
                        </div>
                    </div>

                    <!-- برنامه ورزشی -->
                    <a href="#"
                        class="nav-link flex items-center justify-between py-4 px-5 rounded-lg transition vazir text-[16px]"
                        :class="openMenu === 'workout' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800'"
                        @click="openMenu = 'workout'; closeMobileMenu()">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-dumbbell w-6 h-6"
                                :class="openMenu === 'workout' ? 'text-white' : 'text-gray-500 dark:text-slate-400'"></i>
                            برنامه ورزشی
                        </span>
                    </a>

                    <!-- مربیان -->
                    <a href="#"
                        class="nav-link flex items-center justify-between py-4 px-5 rounded-lg transition vazir text-[16px]"
                        :class="openMenu === 'trainers' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800'"
                        @click="openMenu = 'trainers'; closeMobileMenu()">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-user-graduate w-6 h-6"
                                :class="openMenu === 'trainers' ? 'text-white' : 'text-gray-500 dark:text-slate-400'"></i>
                            مربیان
                        </span>
                    </a>

                    <!-- گزارشات -->
                    <a href="#"
                        class="nav-link flex items-center justify-between py-4 px-5 rounded-lg transition vazir text-[16px]"
                        :class="openMenu === 'reports' ? 'bg-gradient-to-br from-indigo-400 to-indigo-500 text-white' : 'text-white dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800'"
                        @click="openMenu = 'reports'; closeMobileMenu()">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-chart-bar w-6 h-6"
                                :class="openMenu === 'reports' ? 'text-white' : 'text-gray-500 dark:text-slate-400'"></i>
                            گزارشات
                        </span>
                    </a>
                </nav>
            </div>

            <!-- محتوای اصلی -->
            <main class="flex-1 main-content-container px-2">
                <!-- نمایش محتوای فعال -->
                <div x-show="openMenu === 'dashboard'">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3 class="text-lg font-semibold">تعداد اعضا</h3>
                            <p class="text-3xl font-bold mt-2">۱,۲۴۵</p>
                            <p class="text-sm mt-1">+۱۲٪ نسبت به ماه گذشته</p>
                        </div>
                        <div class="stat-card">
                            <h3 class="text-lg font-semibold">درآمد ماهانه</h3>
                            <p class="text-3xl font-bold mt-2">۱۲۵M</p>
                            <p class="text-sm mt-1">+۸٪ نسبت به ماه گذشته</p>
                        </div>
                        <div class="stat-card">
                            <h3 class="text-lg font-semibold">کلاس‌های فعال</h3>
                            <p class="text-3xl font-bold mt-2">۴۸</p>
                            <p class="text-sm mt-1">+۵ کلاس جدید</p>
                        </div>
                    </div>
                    
                    <div class="sample-card">
                        <h2 class="text-xl font-bold mb-4">آمار فعالیت‌های اخیر</h2>
                        <p class="text-slate-600 dark:text-slate-400">این بخش برای نمایش محتوای اصلی صفحه استفاده می‌شود.</p>
                    </div>
                </div>

                <div x-show="openMenu.startsWith('customer') && openMenu !== 'dashboard'" class="space-y-4">
                    <div class="sample-card">
                        <h2 class="text-xl font-bold mb-4" x-text="
                            openMenu === 'customer-accounts' ? 'حساب‌های مشتریان' :
                            openMenu === 'customer-payments' ? 'پرداخت‌های مشتریان' :
                            openMenu === 'customer-invoices' ? 'فاکتورهای مشتریان' :
                            openMenu === 'customer-debts' ? 'بدهی‌های مشتریان' :
                            openMenu === 'customer-reports' ? 'گزارشات مالی مشتریان' :
                            openMenu === 'customer-discounts' ? 'تخفیف‌های مشتریان' :
                            'حسابداری مشتریان'
                        "></h2>
                        <p class="text-slate-600 dark:text-slate-400">محتوای مربوط به بخش انتخاب شده در اینجا نمایش داده می‌شود.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('mainContent');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            
            // مدیریت حالت دارک/روشن
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const htmlElement = document.documentElement;
            
            themeToggle.addEventListener('click', () => {
                htmlElement.classList.toggle('dark');
                htmlElement.classList.toggle('light');
                
                if (htmlElement.classList.contains('dark')) {
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                } else {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                }
            });
            
            // مدیریت منوی موبایل
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            mobileMenuBtn.addEventListener('click', () => {
                mobileSidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            });
            
            mobileOverlay.addEventListener('click', () => {
                mobileSidebar.classList.remove('active');
                mobileOverlay.classList.remove('active');
            });
            
            // مدیریت منوی پروفایل
            const profileBtn = document.getElementById('profileBtn');
            const profileDropdown = document.getElementById('profileDropdown');
            
            profileBtn.addEventListener('click', () => {
                profileDropdown.classList.toggle('hidden');
            });
            
            // بستن منوها با کلیک خارج
            document.addEventListener('click', (e) => {
                if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
            
            // لودر
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 100) progress = 100;
                
                progressBar.style.width = progress + '%';
                progressText.textContent = Math.round(progress) + '%';
                
                if (progress >= 100) {
                    clearInterval(progressInterval);
                    
                    setTimeout(() => {
                        loader.classList.add('loader-complete');
                        
                        setTimeout(() => {
                            loader.style.display = 'none';
                            if (mainContent) {
                                mainContent.style.display = 'block';
                            }
                        }, 800);
                    }, 500);
                }
            }, 200);
        });
    </script>
</body>
</html>