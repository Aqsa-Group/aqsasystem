<!DOCTYPE html>
<html class="dark" lang="<?php echo e(session('locale', config('app.locale'))); ?>"
    dir="<?php echo e(session('locale') === 'en' ? 'ltr' : 'rtl'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>پنل مدیریت رستوران</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('assets/aqsa.jpg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php echo $__env->make('Restaurant.layouts.links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#FFDAB9',
                            DEFAULT: '#FF8C42',
                            dark: '#E67E22'
                        },
                        secondary: {
                            light: '#C8E6C9',
                            DEFAULT: '#4CAF50',
                            dark: '#388E3C'
                        },
                        accent: {
                            light: '#FFECB3',
                            DEFAULT: '#FFC107',
                            dark: '#FFA000'
                        },
                        surface: {
                            light: '#FFFFFF',
                            dark: '#1A1A1A'
                        },
                        background: {
                            light: '#F8F9FA',
                            dark: '#121212'
                        }
                    },
                    fontFamily: {
                        'vazir': ['Vazir', 'sans-serif'],
                        'yekan': ['Yekan', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        @font-face {
            font-family: "DimaYekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .yekan {
            font-family: "DimaYekan", sans-serif;
        }


        @font-face {
            font-family: "vazir";
            src: url("/fonts/Vazir.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }


        .vazir {
            font-family: "vazir", sans-serif;
        }

        body {
            overflow-x: hidden;
            background: #f8f9fa;
        }

        .dark body {
            background: #121212;
            color: #e0e0e0;
        }

        /* Loader */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #201108 0%, #3c19a4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loader-content {
            text-align: center;
            color: white;
        }

        .loader-spinner {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* هدر جدید */
        .header-main {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .dark .header-main {
            background: #1A1A1A;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header-container {
            max-width: 100%;
            padding: 0 1rem;
        }

        /* لوگو و برند */
        .brand-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FF8C42 0%, #FF6B6B 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .dark .brand-text {
            color: white;
        }

        /* بخش جستجو */
        .search-container {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .dark .search-input {
            background: #2D2D2D;
            border-color: #404040;
            color: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #FF8C42;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        /* بخش ابزارها */
        .tools-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* دکمه اعلان */
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dark .notification-btn {
            background: #2D2D2D;
        }

        .notification-btn:hover {
            background: #e0e0e0;
        }

        .dark .notification-btn:hover {
            background: #404040;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #FF6B6B;
            color: white;
            font-size: 0.75rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* سوییچ آفتاب/ماه */
        .sun-moon-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sun-moon-switch {
            width: 60px;
            height: 30px;
            border-radius: 15px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            padding: 0 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .dark .sun-moon-switch {
            background: #404040;
        }

        .sun-moon-switch.active {
            background: #FF8C42;
        }

        .sun-moon-slider {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
            position: absolute;
            left: 4px;
        }

        .sun-moon-switch.active .sun-moon-slider {
            transform: translateX(30px);
        }

        .sun-icon,
        .moon-icon {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sun-icon {
            color: #FFA000;
        }

        .moon-icon {
            color: #1A237E;
        }

        .dark .sun-icon {
            color: #FFD54F;
        }

        .dark .moon-icon {
            color: #C5CAE9;
        }

        /* پروفایل */
        .profile-section {
            position: relative;
        }

        .profile-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dark .profile-btn {
            border-color: #404040;
        }

        .profile-btn:hover {
            border-color: #FF8C42;
        }

        .profile-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 250px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            margin-top: 0.5rem;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.2s ease;
        }

        .dark .profile-menu {
            background: #2D2D2D;
        }

        .profile-menu.open {
            display: block;
        }

        .profile-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            text-align: center;
        }

        .dark .profile-header {
            border-bottom-color: #404040;
        }

        .profile-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            border: 3px solid #FF8C42;
        }

        .profile-name {
            font-weight: 600;
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .dark .profile-name {
            color: white;
        }

        .profile-role {
            font-size: 0.875rem;
            color: #666;
        }

        .profile-links {
            padding: 0.75rem;
        }

        .profile-link {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
            gap: 0.75rem;
        }

        .dark .profile-link {
            color: #ccc;
        }

        .profile-link:hover {
            background: #f5f5f5;
            color: #FF8C42;
        }

        .dark .profile-link:hover {
            background: #404040;
        }

        .profile-logout {
            padding: 0.75rem;
            border-top: 1px solid #e0e0e0;
        }

        .dark .profile-logout {
            border-top-color: #404040;
        }

        .logout-btn {
            width: 100%;
            padding: 0.75rem;
            background: #ffebee;
            color: #d32f2f;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .dark .logout-btn {
            background: rgba(211, 47, 47, 0.1);
            color: #ff5252;
        }

        .logout-btn:hover {
            background: #ffcdd2;
        }

        .dark .logout-btn:hover {
            background: rgba(211, 47, 47, 0.2);
        }

        /* منوی موبایل */
        .mobile-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #FF8C42;
            border: none;
            color: white;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        /* سایدبار جدید */
        .sidebar-main {
            background: white;
            width: 260px;
            min-height: calc(100vh - 70px);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .dark .sidebar-main {
            background: #1A1A1A;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            text-align: center;
        }

        .dark .sidebar-header {
            border-bottom-color: #333;
        }

        .restaurant-info {
            text-align: center;
        }

        .restaurant-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            border: 3px solid #FF8C42;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            color: #FF8C42;
        }

        .restaurant-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .dark .restaurant-name {
            color: white;
        }

        .restaurant-status {
            font-size: 0.875rem;
            color: #4CAF50;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #4CAF50;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .sidebar-menu {
            padding: 1rem;
        }

        .menu-section {
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #666;
            padding: 0.5rem 1rem;
            letter-spacing: 1px;
        }

        .dark .section-title {
            color: #999;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            margin-bottom: 0.25rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 0.75rem;
            position: relative;
        }

        .dark .menu-link {
            color: #ccc;
        }

        .menu-link:hover,
        .menu-link.active {
            background: #FFF3E0;
            color: #FF8C42;
        }

        .dark .menu-link:hover,
        .dark .menu-link.active {
            background: #2D2D2D;
            color: #FF8C42;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-badge {
            position: absolute;
            left: 1rem;
            background: #FF6B6B;
            color: white;
            font-size: 0.75rem;
            padding: 0.125rem 0.375rem;
            border-radius: 10px;
        }

        /* محتوای اصلی */
        .main-content {
            flex: 1;
            padding: 1.5rem;
            min-height: calc(100vh - 70px);
            overflow-x: hidden;
        }

        /* رسپانسیو */
        @media (max-width: 1024px) {
            .sidebar-main {
                position: fixed;
                top: 70px;
                right: -260px;
                height: calc(100vh - 70px);
                z-index: 999;
            }

            .sidebar-main.open {
                right: 0;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .overlay.open {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 0 0.5rem;
            }

            .brand-text {
                font-size: 1rem;
            }

            .search-container {
                max-width: 200px;
            }

            .main-content {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .search-container {
                display: none;
            }

            .tools-section {
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body class="vazir dark:bg-background-dark dark:text-white">
    <!-- Loader -->
    <div id="loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="text-xl font-bold mb-2"><?php echo e(Auth::guard('restaurant')->user()->restaurant_name); ?></div>
            <p class="text-white/80">در حال بارگذاری پنل مدیریت...</p>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div id="mainContent" class="hidden">
        <!-- هدر -->
        <header class="header-main">
            <div class="header-container mx-auto">
                <div class="flex items-center justify-between h-16">
                    <!-- بخش سمت چپ: برند و منوی موبایل -->
                    <div class="flex items-center gap-4">
                        <!-- دکمه منوی موبایل -->
                        <button class="mobile-menu-btn" id="mobileMenuBtn">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- لوگو و نام رستوران -->
                        <div class="brand-section">

                            <div class="brand-text yekan text-[30px]">
                                <?php echo e(Auth::guard('restaurant')->user()->restaurant_name); ?>

                            </div>
                        </div>
                    </div>

                    <!-- بخش وسط: جستجو -->
                    <div class="search-container">
                        <input type="text" placeholder="جستجوی منو، سفارشات، مشتریان..." class="search-input">
                        <svg class="search-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- بخش سمت راست: ابزارها -->
                    <div class="tools-section">
                        <!-- دکمه اعلان -->
                        <button class="notification-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <span class="notification-badge">3</span>
                        </button>

                        <!-- سوییچ آفتاب/ماه -->
                        <div class="sun-moon-toggle">
                            <div class="sun-moon-switch" id="sunMoonSwitch">
                                <div class="sun-moon-slider">
                                    <div class="sun-icon" id="sunIcon">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="moon-icon" id="moonIcon" style="display: none;">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M9.37 5.51A7.35 7.35 0 009.1 7.5c0 4.08 3.32 7.4 7.4 7.4.68 0 1.35-.09 1.99-.27A7.014 7.014 0 0112 19c-3.86 0-7-3.14-7-7 0-2.93 1.81-5.45 4.37-6.49zM12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-3.14-9.8c-.44-.06-.9-.1-1.36-.1z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- پروفایل -->
                        <div class="profile-section">
                            <?php
                            $currentUser = Auth::guard('restaurant')->user();
                            $userImage = $currentUser->user_image ? asset('storage/' . $currentUser->user_image) :
                            asset('assets/sarafi/avatar.svg');
                            ?>
                            <button class="profile-btn" id="profileBtn">
                                <img src="<?php echo e($userImage); ?>" alt="<?php echo e($currentUser->name); ?>">
                            </button>
                            <div class="profile-menu" id="profileMenu">
                                <div class="profile-header">
                                    <div class="profile-avatar">
                                        <img src="<?php echo e($userImage); ?>" alt="<?php echo e($currentUser->name); ?>">
                                    </div>
                                    <div class="profile-name"><?php echo e($currentUser->name); ?></div>
                                    <div class="profile-role">مدیر رستوران</div>
                                </div>
                                <div class="profile-links">
                                    <a href="" class="profile-link">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <span>پروفایل من</span>
                                    </a>
                                    <a href="" class="profile-link">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>تنظیمات</span>
                                    </a>
                                    <a href="" class="profile-link">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <span>راهنما و پشتیبانی</span>
                                    </a>
                                </div>
                                <div class="profile-logout">
                                    <form action="<?php echo e(route('restaurnat.logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="logout-btn">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            <span>خروج از سیستم</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Overlay برای موبایل -->
        <div class="overlay" id="overlay"></div>

        <div class="flex">
            <!-- سایدبار -->
            <aside class="sidebar-main" id="sidebar">
                <div class="sidebar-menu">
                    <!-- بخش اصلی -->
                    <div class="menu-section">
                        <div class="section-title">اصلی</div>
                        <ul class="menu-list">
                            <li class="menu-item">
                               <a href="<?php echo e(route('restaurant.home')); ?>"
   class="menu-link <?php echo e(request()->routeIs('restaurant.home') ? 'active' : ''); ?>">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    <span>داشبورد</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <span>سفارشات</span>
                                    <span class="menu-badge">5</span>
                                </a>
                            </li>
                            <li class="menu-item">
                             <a href="<?php echo e(route('restaurant.menu')); ?>"
   class="menu-link <?php echo e(request()->routeIs('restaurant.menu') ? 'active' : ''); ?>">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span>منو و قیمت‌ها</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- بخش مدیریت -->
                    <div class="menu-section">
                        <div class="section-title">مدیریت</div>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>مشتریان</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>رزروها</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 1.5a7.5 7.5 0 01-7.5 7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span>پرسنل</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- بخش مالی -->
                    <div class="menu-section">
                        <div class="section-title">مالی</div>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span>گزارش مالی</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <span>موجودی انبار</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <span>هزینه‌ها</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- بخش تنظیمات -->
                    <div class="menu-section">
                        <div class="section-title">تنظیمات</div>
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>تنظیمات سیستم</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span>راهنما و پشتیبانی</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- محتوای اصلی -->
            <main class="main-content">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // مدیریت Loader
            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('mainContent');
            
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.display = 'none';
                    mainContent.classList.remove('hidden');
                }, 500);
            }, 1000);

            // مدیریت منوی موبایل
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('open');
            });
            
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            });

            // مدیریت منوی پروفایل
            const profileBtn = document.getElementById('profileBtn');
            const profileMenu = document.getElementById('profileMenu');
            
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('open');
            });
            
            document.addEventListener('click', (e) => {
                if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.remove('open');
                }
            });

            // مدیریت سوییچ آفتاب/ماه
            const sunMoonSwitch = document.getElementById('sunMoonSwitch');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');
            const html = document.documentElement;
            
            // بررسی وضعیت فعلی
            const isDarkMode = localStorage.getItem('theme') === 'dark' || 
                              (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDarkMode) {
                html.classList.add('dark');
                sunMoonSwitch.classList.add('active');
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'flex';
            } else {
                sunMoonSwitch.classList.remove('active');
                sunIcon.style.display = 'flex';
                moonIcon.style.display = 'none';
            }
            
            // تغییر حالت با کلیک
            sunMoonSwitch.addEventListener('click', function() {
                const isActive = this.classList.contains('active');
                
                if (isActive) {
                    // تغییر به حالت لایت
                    this.classList.remove('active');
                    html.classList.remove('dark');
                    sunIcon.style.display = 'flex';
                    moonIcon.style.display = 'none';
                    localStorage.setItem('theme', 'light');
                } else {
                    // تغییر به حالت دارک
                    this.classList.add('active');
                    html.classList.add('dark');
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'flex';
                    localStorage.setItem('theme', 'dark');
                }
            });

            // مدیریت کلیک روی لینک‌های منو
            const menuLinks = document.querySelectorAll('.menu-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // حذف حالت فعال از همه لینک‌ها
                    menuLinks.forEach(l => l.classList.remove('active'));
                    // اضافه کردن حالت فعال به لینک کلیک شده
                    this.classList.add('active');
                    
                    // بستن سایدبار در موبایل
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.remove('open');
                        overlay.classList.remove('open');
                    }
                });
            });

            // مدیریت ریسایز
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                }
            });
        });
    </script>
</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Restaurant/layouts/sidebar.blade.php ENDPATH**/ ?>