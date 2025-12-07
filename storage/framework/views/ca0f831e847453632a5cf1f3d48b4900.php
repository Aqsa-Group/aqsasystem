<!DOCTYPE html>
<html lang="<?php echo e(session('locale', config('app.locale'))); ?>" dir="<?php echo e(session('locale') === 'en' ? 'ltr' : 'rtl'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم صرافی اقصی</title>
    <?php echo $__env->make('Sarafi.layouts.links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
           /* لودر تمام صفحه فوق العاده زیبا */
            #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            opacity: 1;
        }

        .content-loaded {
            display: block;
            opacity: 1;
        }

        /* استایل‌های دارک مود */
        #toggleCircle {
            transition: transform 0.3s ease-in-out;
        }

        [dir="rtl"] #toggleCircle.move-dark {
            transform: translateX(-2rem);
        }

        [dir="ltr"] #toggleCircle.move-dark {
            transform: translateX(2rem);
        }

        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: #1a202c;
            color: #e2e8f0;
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

        /* استایل‌های ریسپانسیو جدید */

        /* هدر ریسپانسیو */
        .header-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
            width: 100%;
        }

        @media (min-width: 768px) {
            .header-container {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 0 1.5rem;
                height: 80px;
            }
        }

        /* لایه موبایل */
        .mobile-header-layout {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 1rem;
        }

        .mobile-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .mobile-header-bottom {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 0.5rem;
        }

        .mobile-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #122EE1;
        }

        .mobile-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mobile-search-full {
            flex: 1;
        }

        .mobile-tools {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (min-width: 768px) {
            .mobile-header-layout {
                display: none;
            }
        }

        /* لایه دسکتاپ */
        .desktop-header-layout {
            display: none;
            width: 100%;
            justify-content: space-between;
            align-items: center;
        }

        @media (min-width: 768px) {
            .desktop-header-layout {
                display: flex;
            }
        }

        .desktop-brand-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .desktop-actions-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* منوی همبرگری برای موبایل */
        .mobile-menu-btn {
            display: block;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 100;
            background: #122EE1;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        /* سایدبار ریسپانسیو */
        .sidebar-container {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 280px;
            background: white;
            z-index: 90;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            padding: 1rem 0.5rem;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-container.open {
            transform: translateX(0);
        }

        @media (min-width: 768px) {
            .sidebar-container {
                position: static;
                transform: none;
                width: 18rem;
                height: auto;
                box-shadow: none;
                padding: 1.25rem;
            }
        }

        /* محتوای اصلی ریسپانسیو */
        .main-content-wrapper {
            margin-top: 1rem;
            padding: 0 1rem;
        }

        @media (min-width: 768px) {
            .main-content-wrapper {
                margin-top: 2.5rem;
                padding: 0;
            }
        }

        /* لایه overlay برای موبایل */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 80;
            display: none;
        }

        .mobile-overlay.open {
            display: block;
        }

        @media (min-width: 768px) {
            .mobile-overlay {
                display: none !important;
            }
        }

        /* بهبود استایل‌های عمومی */
        .responsive-text {
            font-size: 1.5rem;
        }

        @media (min-width: 768px) {
            .responsive-text {
                font-size: 2.5rem;
            }
        }

        /* استایل برای دکمه‌های موبایل */
        .btn-mobile-small {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img-mobile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* استایل برای dropdown موبایل */
        .dropdown-mobile {
            width: 120px;
        }

        .dropdown-mobile button {
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        .dropdown-mobile img {
            width: 16px;
            height: 16px;
        }

        /* دارک مود موبایل */
        .dark-mode-toggle-mobile {
            width: 40px;
            height: 20px;
        }

        .dark-mode-toggle-mobile label {
            height: 20px;
            padding: 0 0.25rem;
        }

        .dark-mode-toggle-mobile span {
            width: 16px;
            height: 16px;
        }

        .dark-mode-toggle-mobile svg {
            width: 12px;
            height: 12px;
        }
    </style>
</head>

<body class="vazir dark:text-white overflow-x-hidden">

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
                    <span><?php echo e(mb_substr(Auth::guard('sarafi')->user()->sarafi_name, 0, 1)); ?></span>
                </div>
            </div>

            <div class="loader-text">صرافــی <?php echo e(Auth::guard('sarafi')->user()->sarafi_name); ?></div>
            <div class="loader-subtext">در حال بارگذاری...</div>

            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div id="mainContent">
        <!-- دکمه منوی موبایل -->
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- لایه overlay برای موبایل -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <header
            class="bg-white w-full py-4 md:py-0 md:h-[80px] flex items-center shadow-[0_4px_4px_rgba(17,41,199,0.4)]">
            <div class="header-container">
                <!-- لایه موبایل -->
                <div class="mobile-header-layout">
                    <div class="mobile-header-top">
                        <!-- برند -->
                        <div class="mobile-brand yekan"><?php echo e(Auth::guard('sarafi')->user()->sarafi_name); ?></div>

                        <!-- اعلان و پروفایل -->
                        <div class="mobile-actions">
                            <button
                                class="btn-mobile-small relative rounded-full bg-[#E5E5E5] hover:bg-gray-300 transition">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/bill-header.svg')); ?>" alt="اعلان"
                                    class="w-6 h-6">
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                            </button>

                            <div class="header-profile-section">
                                <div class="relative">
                                    <div id="profileBtnMobile"
                                        class="profile-img-mobile border overflow-hidden flex items-center justify-center cursor-pointer transition">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/man.png')); ?>" alt="پروفایل"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mobile-header-bottom">
                        <!-- جستجو -->
                        <div class="mobile-search-full">
                            <div class="relative">
                                <input type="text" placeholder="<?php echo e(__('messages.search_placeholder')); ?>"
                                    class="border border-[#8C8C8C] placeholder:text-black vazir rounded-full px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                                    class="h-4 w-4 absolute left-2 bottom-3">
                            </div>
                        </div>

                        <!-- زبان و دارک مود -->
                        <div class="mobile-tools">
                            <?php $locale = session('locale', config('app.locale')); ?>
                            <div class="relative dropdown-mobile vazir">
                                <button id="dropdownButtonMobile"
                                    class="border border-[#1129C766] bg-white rounded-lg px-2 py-1 w-full flex items-center justify-between font-vazir text-xs text-[#1129C7]">
                                    <img src="<?php echo e($locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png')); ?>"
                                        class="w-4 h-4 ml-1" alt="Lang">
                                    <span>
                                        <?php if($locale === 'fa'): ?> فارسی
                                        <?php elseif($locale === 'ps'): ?> پشتو
                                        <?php else: ?> EN
                                        <?php endif; ?>
                                    </span>
                                </button>

                                <ul id="dropdownMenuMobile"
                                    class="absolute left-5 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                                    <li><a href="<?php echo e(route('set-locale', 'fa')); ?>"
                                            class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                                src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>"
                                                class="w-4 h-4 ml-1" alt="fa">
                                            فارسی</a></li>
                                    <li><a href="<?php echo e(route('set-locale', 'ps')); ?>"
                                            class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                                src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>"
                                                class="w-4 h-4 ml-1" alt="ps">
                                            پشتو</a></li>
                                    <li><a href="<?php echo e(route('set-locale', 'en')); ?>"
                                            class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                                src="<?php echo e(asset('assets/sarafi/all_icon/united.png')); ?>"
                                                class="w-4 h-4 ml-1" alt="en"> English</a></li>
                                </ul>
                            </div>

                            
                        </div>
                    </div>
                </div>

                <!-- لایه دسکتاپ -->
                <div class="desktop-header-layout">
                    <!-- برند + انتخاب زبان -->
                    <div class="desktop-brand-section">
                        <div class="responsive-text text-[#122EE1] font-bold yekan"><?php echo e(Auth::guard('sarafi')->user()->sarafi_name); ?></div>

                        <?php $locale = session('locale', config('app.locale')); ?>
                        <div class="relative inline-block w-[145px] h-[56px] p-2 vazir">
                            <button id="dropdownButton"
                                class="border border-[#1129C766] bg-white rounded-lg px-3 py-2 w-full flex items-center justify-between font-vazir text-sm text-[#1129C7]">
                                <img src="<?php echo e($locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png')); ?>"
                                    class="w-5 h-5 ml-2" alt="Lang">
                                <span>
                                    <?php if($locale === 'fa'): ?> فارسی
                                    <?php elseif($locale === 'ps'): ?> پشتو
                                    <?php else: ?> English
                                    <?php endif; ?>
                                </span>
                            </button>

                            <ul id="dropdownMenu"
                                class="absolute left-0 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                                <li><a href="<?php echo e(route('set-locale', 'fa')); ?>"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                            src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2"
                                            alt="fa">
                                        فارسی</a></li>
                                <li><a href="<?php echo e(route('set-locale', 'ps')); ?>"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                            src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2"
                                            alt="ps">
                                        پشتو</a></li>
                                <li><a href="<?php echo e(route('set-locale', 'en')); ?>"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                            src="<?php echo e(asset('assets/sarafi/all_icon/united.png')); ?>" class="w-5 h-5 ml-2"
                                            alt="en"> English</a></li>
                            </ul>
                        </div>

                        
                    </div>
 
                    <!-- سرچ، اعلان، پروفایل -->
                    <div class="desktop-actions-section">
                        <div class="header-search-section">
                            <div class="relative">
                                <input type="text" placeholder="<?php echo e(__('messages.search_placeholder')); ?>"
                                    class="border border-[#8C8C8C] placeholder:text-black vazir rounded-full px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                                    class="h-5 w-5 absolute left-2 bottom-3">
                            </div>
                        </div>

                        <button
                            class="relative flex items-center justify-center w-[50px] h-[50px] rounded-[25px] bg-[#E5E5E5] hover:bg-gray-300 transition">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/bill-header.svg')); ?>" alt="اعلان"
                                class="w-7 h-7">
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                        </button>

                        <div class="header-profile-section">
                            <div class="relative">
                                <div id="profileBtnDesktop"
                                    class="w-[50px] h-[50px] md:w-[70px] md:h-[70px] rounded-full border overflow-hidden flex items-center justify-center cursor-pointer transition">
                                    <img src="<?php echo e(asset('assets/sarafi/all_icon/man.png')); ?>" alt="پروفایل"
                                        class="w-full h-full object-cover">
                                </div>

                                <!-- منو dropdown -->
                                <div id="profileDropdownDesktop"
                                    style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;"
                                    class="absolute top-full left-0 space-y-3 text-2xl w-72 h-76 bg-white rounded-lg shadow-lg border hidden z-50 p-4">

                                    <div class="p-3 border-b space-y-5">
                                        <div class="flex flex-col justify-center items-center ">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/man.png')); ?>" alt=""
                                                class="h-20 w-20">
                                            <p class="font-vazir font-semibold text-gray-700 mt-5"><?php echo e(Auth::guard('sarafi')->user()->name); ?></p>

                                        </div>

                                    </div>
                                    <div class="flex justify-start items-center  ">
                                        <img src="<?php echo e(asset('assets/sarafi/all_icon/account_profile.svg')); ?>" alt="">

                                        <a href="<?php echo e(route('sarafi.users')); ?>"
                                            class="block px-4 py-2 text-gray-700 vazir">تنظیمات</a>
                                    </div>

                                    <form action="<?php echo e(route('sarafi.logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="flex justify-start items-center">
                                            <img src="<?php echo e(asset('assets/sarafi/all_icon/logout.svg')); ?>" alt="">
                                            <button type="submit" class="px-4 py-2 text-gray-700 vazir">
                                                خروج از حساب
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-col md:flex-row mt-4 min-h-screen">
            <!-- سایدبار -->
            <div class="sidebar-container" id="sidebar">
                <nav class="mt-0 space-y-0" x-data="{
                    openItems: {
                        customers: false,
                        accounts: false,
                        bankFiles: false,
                        editAccounts: false,
                        reports: false,
                        transactions: false,
                        deletedTransactions: false,
                        management: false,
                        sms: false,
                        notifications: false,
                        support: false,
                        settings: false
                    },
                    active: 'dashboard',
                    
                    setActive(item, parent = null) {
                        this.active = item;
                        if (parent && this.openItems.hasOwnProperty(parent)) {
                            this.openItems[parent] = true;
                        }
                        if (window.innerWidth < 768) {
                            document.getElementById('sidebar').classList.remove('open');
                            document.getElementById('mobileOverlay').classList.remove('open');
                        }
                    }
                }">
                    <!-- داشبورد -->
                    <a href="<?php echo e(route('sarafi.home')); ?>"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                        @click="active = 'dashboard'"
                        :class="active === 'dashboard' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/element-3.svg')); ?>" class="w-5 h-5"
                                :class="active === 'dashboard' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            <?php echo e(__('messages.dashboard')); ?>

                        </span>
                    </a>

                    <!-- کاربران -->
                    <a href="<?php echo e(route('sarafi.users')); ?>"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir dark:text-white"
                        @click="active = 'users'"
                        :class="active === 'users' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/profile-2user.svg')); ?>" class="w-5 h-5"
                                :class="active === 'users' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            <span class="dark:text-white"> <?php echo e(__('messages.users')); ?></span>
                        </span>
                    </a>

                    <!-- مشتریان -->
                    <div>
                        <button @click="openItems.customers = !openItems.customers; active = 'customers'"
                            :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/people.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.customers')); ?>

                            </span>
                            <svg :class="[openItems.customers ? 'rotate-180' : '', (active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.customers" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="<?php echo e(route('sarafi.customer-create')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-create', 'customers')"
                                :class="active === 'customer-create' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <i class="fa-solid fa-user-pen w-4 h-4"
                                    :class="active === 'customer-create' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                <?php echo e(__('messages.customer_create')); ?>

                            </a>

                            <a href="<?php echo e(route('sarafi.customer-table')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-table', 'customers')"
                                :class="active === 'customer-table' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <i class="fa-solid fa-users-gear h-4 w-4"
                                    :class="active === 'customer-table' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                <?php echo e(__('messages.customer_list')); ?>

                            </a>
                        </div>
                    </div>

                    <!-- ثبت حسابات و نرخ ارز -->
                    <div>
                        <button @click="openItems.accounts = !openItems.accounts; active = 'accounts'"
                            :class="(active === 'accounts' || active === 'register-accounts') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2 ">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'accounts' || active === 'register-accounts') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.accounts')); ?>

                            </span>
                            <svg :class="[openItems.accounts ? 'rotate-180' : '', (active === 'accounts' || active === 'register-accounts') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.accounts" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="<?php echo e(route('sarafi.exchange-rate')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('register-accounts', 'accounts')"
                                :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/add.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'register-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                               ثبت نرخ بیلانس
                            </a>


                              <a href="<?php echo e(route('sarafi.profit-rates')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('register-accounts', 'accounts')"
                                :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/add.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'register-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                                تعیین نرخ سنجش مفاد و ضرر
                            </a>
                        </div>
                    </div>

                    <!-- بارگذاری فایل بانکی -->
                    <div>
                        <button @click="openItems.bankFiles = !openItems.bankFiles; active = 'bankFiles'"
                            :class="(active === 'bankFiles' || active === 'upload-bank') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/receive-square.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'bankFiles' || active === 'upload-bank') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.bank_files')); ?>

                            </span>
                            <svg :class="[openItems.bankFiles ? 'rotate-180' : '', (active === 'bankFiles' || active === 'upload-bank') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.bankFiles" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="<?php echo e(route('sarafi.remittance')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('upload-bank', 'bankFiles')"
                                :class="active === 'upload-bank' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/upload.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'upload-bank' ? 'filter invert brightness-0' : 'text-gray-500'">
                                ثبت احواله جات
                            </a>
                        </div>
                    </div>


                    <!-- کنترول و بررسی معاملات -->
                    <div>
                        <button @click="openItems.transactions = !openItems.transactions; active = 'transactions'"
                            :class="(active === 'transactions' || active === 'control-transactions') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/health.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'transactions' || active === 'control-transactions') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.transactions')); ?>

                            </span>
                            <svg :class="[openItems.transactions ? 'rotate-180' : '', (active === 'transactions' || active === 'control-transactions') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.transactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="<?php echo e(route('sarafi.remittance-approval')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('control-transactions', 'transactions')"
                                :class="active === 'control-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/eye.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'control-transactions' ? 'filter invert brightness-0' : 'text-gray-500'">
                                احواله های تایید نشده
                            </a>
                        </div>
                    </div>

                    <!-- بررسی معاملات حذف شده -->
                    <div>
                        <button
                            @click="openItems.deletedTransactions = !openItems.deletedTransactions; active = 'deletedTransactions'"
                            :class="(active === 'deletedTransactions' || active === 'deleted-transactions') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/trash.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'deletedTransactions' || active === 'deleted-transactions') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.deleted_transactions')); ?>

                            </span>
                            <svg :class="[openItems.deletedTransactions ? 'rotate-180' : '', (active === 'deletedTransactions' || active === 'deleted-transactions') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.deletedTransactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="<?php echo e(route('sarafi.trash-edit')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('deleted-transactions', 'deletedTransactions')"
                                :class="active === 'deleted-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/archive.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'deleted-transactions' ? 'filter invert brightness-0' : 'text-gray-500'">
                                معاملات حذف شده و ویرایش شده
                            </a>
                        </div>
                    </div>


                    <!-- گزارش و آمار حسابات -->
                    <div>
                        <button @click="openItems.reports = !openItems.reports; active = 'reports'"
                            :class="(active === 'reports' || active === 'view-reports') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/graph.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'reports' || active === 'view-reports') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.reports')); ?>

                            </span>
                            <svg :class="[openItems.reports ? 'rotate-180' : '', (active === 'reports' || active === 'view-reports') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.reports" x-transition class="mr-6 mt-1 space-y-1">

                            
                            <a href="<?php echo e(route('sarafi.account-reports')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-reports', 'reports')"
                                :class="active === 'view-reports' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">

                                <img src="<?php echo e(asset('assets/sarafi/all_icon/chart.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'view-reports' ? 'filter invert brightness-0' : 'text-gray-500'">

                                گزارش حسابات
                            </a>

                            
                            <a href="<?php echo e(route('sarafi.revenue')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-revenue', 'reports')"
                                :class="active === 'view-revenue' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">

                                <img src="<?php echo e(asset('assets/sarafi/all_icon/chart.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'view-revenue' ? 'filter invert brightness-0' : 'text-gray-500'">

                                عواید معاملات
                            </a>

                        </div>

                    </div>
                    <!-- ویرایش حسابات و نرخ ارز -->
                    <div>
                        <button @click="openItems.editAccounts = !openItems.editAccounts; active = 'editAccounts'"
                            :class="(active === 'editAccounts' || active === 'edit-accounts') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'editAccounts' || active === 'edit-accounts') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.edit_accounts')); ?>

                            </span>
                            <svg :class="[openItems.editAccounts ? 'rotate-180' : '', (active === 'editAccounts' || active === 'edit-accounts') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.editAccounts" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'editAccounts')"
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/edit.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'edit-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.edit_accounts_info')); ?>

                            </a>
                        </div>
                    </div>





                    <!-- مدیریت و دسترسی -->
                    <div>
                        <button @click="openItems.management = !openItems.management; active = 'management'"
                            :class="(active === 'management' || active === 'user-management') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/Group 1325.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'management' || active === 'user-management') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.management')); ?>

                            </span>
                            <svg :class="[openItems.management ? 'rotate-180' : '', (active === 'management' || active === 'user-management') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.management" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('user-management', 'management')"
                                :class="active === 'user-management' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/user.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'user-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.user_management')); ?>

                            </a>
                        </div>
                    </div>

                    <!-- مدیریت پیامک ها -->
                    <div>
                        <button @click="openItems.sms = !openItems.sms; active = 'sms'"
                            :class="(active === 'sms' || active === 'sms-management') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/sms.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'sms' || active === 'sms-management') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.sms')); ?>

                            </span>
                            <svg :class="[openItems.sms ? 'rotate-180' : '', (active === 'sms' || active === 'sms-management') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.sms" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('sms-management', 'sms')"
                                :class="active === 'sms-management' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/message.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'sms-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.sms_management')); ?>

                            </a>
                        </div>
                    </div>

                    <!-- اطلاعیه های آنلاین -->
                    <div>
                        <button @click="openItems.notifications = !openItems.notifications; active = 'notifications'"
                            :class="(active === 'notifications' || active === 'online-notifications') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/wifi.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'notifications' || active === 'online-notifications') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.notifications')); ?>

                            </span>
                            <svg :class="[openItems.notifications ? 'rotate-180' : '', (active === 'notifications' || active === 'online-notifications') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.notifications" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('online-notifications', 'notifications')"
                                :class="active === 'online-notifications' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/notification.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'online-notifications' ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.online_notifications')); ?>

                            </a>
                        </div>
                    </div>

                    <!-- پشتیبانی سیستم -->
                    <div>
                        <button @click="openItems.support = !openItems.support; active = 'support'"
                            :class="(active === 'support' || active === 'system-support') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/document-copy.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'support' || active === 'system-support') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.support')); ?>

                            </span>
                            <svg :class="[openItems.support ? 'rotate-180' : '', (active === 'support' || active === 'system-support') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.support" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('system-support', 'support')"
                                :class="active === 'system-support' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/support.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'system-support' ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.system_support')); ?>

                            </a>
                        </div>
                    </div>

                    <!-- تنظیمات -->
                    <div>
                        <button @click="openItems.settings = !openItems.settings; active = 'settings'"
                            :class="(active === 'settings' || active === 'system-settings') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/setting-2.svg')); ?>" class="w-5 h-5"
                                    :class="(active === 'settings' || active === 'system-settings') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.settings')); ?>

                            </span>
                            <svg :class="[openItems.settings ? 'rotate-180' : '', (active === 'settings' || active === 'system-settings') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.settings" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('system-settings', 'settings')"
                                :class="active === 'system-settings' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/settings.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'system-settings' ? 'filter invert brightness-0' : 'text-gray-500'">
                                <?php echo e(__('messages.system_settings')); ?>

                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- محتوای اصلی -->
            <main class="flex-1 mx-auto main-content-wrapper px-3  w-[500px] overflow-x-hidden">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('mainContent');
            const progressBar = document.querySelector('.progress');

            // مدیریت منوی موبایل
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                mobileOverlay.classList.toggle('open');
            });
            
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                mobileOverlay.classList.remove('open');
            });

            // مدیریت پروفایل در موبایل
            const profileBtnMobile = document.getElementById('profileBtnMobile');
            if (profileBtnMobile) {
                profileBtnMobile.addEventListener('click', () => {
                    window.location.href = "<?php echo e(route('sarafi.users')); ?>";
                });
            }

            // مدیریت پروفایل در دسکتاپ
            const profileBtnDesktop = document.getElementById('profileBtnDesktop');
            const profileDropdownDesktop = document.getElementById('profileDropdownDesktop');
            if (profileBtnDesktop && profileDropdownDesktop) {
                profileBtnDesktop.addEventListener('click', () => {
                    profileDropdownDesktop.classList.toggle('hidden');
                });

                document.addEventListener('click', (event) => {
                    if (!profileBtnDesktop.contains(event.target) && !profileDropdownDesktop.contains(event.target)) {
                        profileDropdownDesktop.classList.add('hidden');
                    }
                });
            }

            // محتوا را ابتدا مخفی کن
            mainContent.style.display = 'none';

            let progress = 0;
            let fakeProgressInterval;

            function startFakeProgress() {
                fakeProgressInterval = setInterval(() => {
                    progress += Math.random() * 30;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                },10);
            }

            startFakeProgress();

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
                }, 600);
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

            // مدیریت dropdown زبان برای دسکتاپ
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

            // مدیریت dropdown زبان برای موبایل
            const btnMobile = document.getElementById('dropdownButtonMobile');
            const menuMobile = document.getElementById('dropdownMenuMobile');
            if (btnMobile && menuMobile) {
                btnMobile.addEventListener('click', () => menuMobile.classList.toggle('hidden'));
                document.addEventListener('click', e => {
                    if (!btnMobile.contains(e.target) && !menuMobile.contains(e.target)) {
                        menuMobile.classList.add('hidden');
                    }
                });
            }
        });

        // مدیریت دارک مود
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        const toggleCircle = document.getElementById('toggleCircle');
        
        const darkModeToggleMobile = document.getElementById('darkModeToggleMobile');
        const sunIconMobile = document.getElementById('sunIconMobile');
        const moonIconMobile = document.getElementById('moonIconMobile');
        const toggleCircleMobile = document.getElementById('toggleCircleMobile');
        
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const html = document.documentElement;

        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
            html.classList.add('dark');
            if (darkModeToggle) darkModeToggle.checked = true;
            if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
            if (sunIcon) sunIcon.classList.add('hidden');
            if (sunIconMobile) sunIconMobile.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            if (moonIconMobile) moonIconMobile.classList.remove('hidden');
            if (toggleCircle) toggleCircle.classList.add('move-dark');
            if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
        }

        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                updateDarkMode(this.checked);
            });
        }

        if (darkModeToggleMobile) {
            darkModeToggleMobile.addEventListener('change', function() {
                updateDarkMode(this.checked);
            });
        }

        function updateDarkMode(isDark) {
            if (isDark) {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (sunIcon) sunIcon.classList.add('hidden');
                if (sunIconMobile) sunIconMobile.classList.add('hidden');
                if (moonIcon) moonIcon.classList.remove('hidden');
                if (moonIconMobile) moonIconMobile.classList.remove('hidden');
                if (toggleCircle) toggleCircle.classList.add('move-dark');
                if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
                if (darkModeToggle) darkModeToggle.checked = true;
                if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
            } else {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if (sunIcon) sunIcon.classList.remove('hidden');
                if (sunIconMobile) sunIconMobile.classList.remove('hidden');
                if (moonIcon) moonIcon.classList.add('hidden');
                if (moonIconMobile) moonIconMobile.classList.add('hidden');
                if (toggleCircle) toggleCircle.classList.remove('move-dark');
                if (toggleCircleMobile) toggleCircleMobile.classList.remove('move-dark');
                if (darkModeToggle) darkModeToggle.checked = false;
                if (darkModeToggleMobile) darkModeToggleMobile.checked = false;
            }
        }
    </script>

</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Sarafi/layouts/sidebar.blade.php ENDPATH**/ ?>