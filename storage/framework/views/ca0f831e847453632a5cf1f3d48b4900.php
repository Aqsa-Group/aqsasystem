<!DOCTYPE html>
<html lang="<?php echo e(session('locale', config('app.locale'))); ?>" dir="<?php echo e(session('locale') === 'en' ? 'ltr' : 'rtl'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم صرافی اقصی</title>
    <?php echo $__env->make('Sarafi.layouts.links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        /* لودر بازار ارز و پول - حرفه‌ای */
        #currency-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.8s ease, visibility 0.8s ease;
            overflow: hidden;
        }

        .currency-loader-container {
            text-align: center;
            animation: fadeInUp 1s ease;
            position: relative;
            z-index: 2;
        }

        /* انیمیشن جابجایی ارزها */
        .currency-flow {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .currency-item {
            position: absolute;
            border-radius: 50%;
            animation: floatCurrency 15s linear infinite;
            opacity: 0.7;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .currency-item:nth-child(1) {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #ffd700, #daa520);
            top: 10%;
            left: 5%;
            animation-delay: 0s;
            animation-duration: 20s;
        }

        .currency-item:nth-child(1)::after {
            content: '$';
        }

        .currency-item:nth-child(2) {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #c0c0c0, #808080);
            top: 70%;
            left: 15%;
            animation-delay: -3s;
            animation-duration: 18s;
        }

        .currency-item:nth-child(2)::after {
            content: '€';
        }

        .currency-item:nth-child(3) {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #d4af37, #b8860b);
            top: 30%;
            left: 85%;
            animation-delay: -6s;
            animation-duration: 22s;
        }

        .currency-item:nth-child(3)::after {
            content: '£';
        }

        .currency-item:nth-child(4) {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #e5e4e2, #b0b0b0);
            top: 85%;
            left: 75%;
            animation-delay: -9s;
            animation-duration: 16s;
        }

        .currency-item:nth-child(4)::after {
            content: '¥';
        }

        .currency-item:nth-child(5) {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            top: 50%;
            left: 10%;
            animation-delay: -12s;
            animation-duration: 19s;
        }

        .currency-item:nth-child(5)::after {
            content: '₿';
            font-size: 18px;
        }

        /* نمودار انیمیشن */
        .chart-animation {
            width: 200px;
            height: 100px;
            margin: 30px auto;
            position: relative;
            overflow: hidden;
        }

        .chart-line {
            fill: none;
            stroke: url(#chartGradient);
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 300;
            stroke-dashoffset: 300;
            animation: drawChart 3s ease-in-out forwards;
        }

        /* اسپینر ارزی */
        .exchange-spinner {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 30px;
        }

        .coin-spinner {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffd700 0%, #fbb034 50%, #ffd700 100%);
            animation: spinCoin 4s ease-in-out infinite;
            box-shadow: 
                0 0 40px rgba(255, 215, 0, 0.5),
                inset 0 0 30px rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .coin-spinner::before {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #daa520 0%, #b8860b 100%);
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.4);
        }

        .coin-spinner::after {
            content: 'صرافی';
            position: absolute;
            color: white;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Vazir', 'Yekan', sans-serif;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
            z-index: 2;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .coin-detail {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2), transparent 70%);
        }

        /* ارزهای در حال چرخش */
        .rotating-currencies {
            position: absolute;
            width: 180px;
            height: 180px;
            top: -20px;
            left: -20px;
        }

        .currency-symbol {
            position: absolute;
            font-size: 28px;
            font-weight: bold;
            color: #3b82f6;
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
            animation: rotateSymbol 8s linear infinite;
        }

        .currency-symbol:nth-child(1) { 
            top: 0; 
            left: 50%; 
            transform: translateX(-50%); 
            animation-delay: 0s; 
            color: #10b981;
        }
        .currency-symbol:nth-child(2) { 
            top: 50%; 
            right: 0; 
            transform: translateY(-50%); 
            animation-delay: -2s; 
            color: #f59e0b;
        }
        .currency-symbol:nth-child(3) { 
            bottom: 0; 
            left: 50%; 
            transform: translateX(-50%); 
            animation-delay: -4s; 
            color: #ef4444;
        }
        .currency-symbol:nth-child(4) { 
            top: 50%; 
            left: 0; 
            transform: translateY(-50%); 
            animation-delay: -6s; 
            color: #8b5cf6;
        }

        /* متن‌ها */
        .currency-loader-text {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Yekan', 'Vazir', sans-serif;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
            background: linear-gradient(90deg, #ffd700, #3b82f6, #10b981, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 300% 300%;
            animation: gradientShift 4s ease infinite;
        }

        .currency-loader-subtext {
            color: rgba(255, 255, 255, 0.85);
            font-size: 16px;
            font-family: 'Vazir', sans-serif;
            max-width: 350px;
            line-height: 1.6;
            margin: 0 auto 25px;
            padding: 0 20px;
        }

        .currency-loader-name {
            color: #ffd700;
            font-weight: 600;
            font-family: 'Yekan', 'Vazir', sans-serif;
            margin-bottom: 5px;
        }

        /* نوار پیشرفت */
        .currency-progress-container {
            width: 320px;
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            margin: 30px auto 0;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .currency-progress {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, 
                #ffd700 0%, 
                #3b82f6 25%, 
                #10b981 50%, 
                #f59e0b 75%, 
                #ef4444 100%);
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .currency-progress::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.4), 
                transparent);
            animation: shimmer 1.5s infinite;
        }

        .progress-text {
            position: absolute;
            top: -25px;
            right: 0;
            color: #94a3b8;
            font-family: 'Vazir', sans-serif;
            font-size: 12px;
        }

        /* اعداد ارزی */
        .currency-numbers {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-top: 30px;
            opacity: 0.9;
        }

        .currency-number {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #cbd5e1;
            font-family: 'Vazir', sans-serif;
            font-size: 13px;
            min-width: 80px;
        }

        .currency-number-value {
            font-size: 18px;
            font-weight: 700;
            color: #ffd700;
            margin-bottom: 5px;
            font-family: 'Arial', sans-serif;
        }

        .currency-number-label {
            font-size: 12px;
            opacity: 0.8;
        }

        /* انیمیشن‌های جدید */
        @keyframes floatCurrency {
            0% {
                transform: translateY(100vh) translateX(-100px) rotate(0deg) scale(0.8);
                opacity: 0;
            }
            10% {
                opacity: 0.8;
            }
            90% {
                opacity: 0.8;
            }
            100% {
                transform: translateY(-150px) translateX(100px) rotate(360deg) scale(1.2);
                opacity: 0;
            }
        }

        @keyframes spinCoin {
            0% {
                transform: rotateY(0deg) rotateX(0deg) scale(1);
                box-shadow: 0 0 40px rgba(255, 215, 0, 0.5);
            }
            25% {
                transform: rotateY(90deg) rotateX(15deg) scale(1.05);
                box-shadow: 0 0 50px rgba(59, 130, 246, 0.6);
            }
            50% {
                transform: rotateY(180deg) rotateX(0deg) scale(1.1);
                box-shadow: 0 0 60px rgba(16, 185, 129, 0.7);
            }
            75% {
                transform: rotateY(270deg) rotateX(-15deg) scale(1.05);
                box-shadow: 0 0 50px rgba(245, 158, 11, 0.6);
            }
            100% {
                transform: rotateY(360deg) rotateX(0deg) scale(1);
                box-shadow: 0 0 40px rgba(255, 215, 0, 0.5);
            }
        }

        @keyframes rotateSymbol {
            0% {
                transform: translateX(-50%) translateY(-50%) rotate(0deg);
                opacity: 0.5;
                filter: blur(0px);
            }
            50% {
                opacity: 1;
                filter: blur(0px);
                text-shadow: 0 0 20px currentColor;
            }
            100% {
                transform: translateX(-50%) translateY(-50%) rotate(360deg);
                opacity: 0.5;
                filter: blur(0px);
            }
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        @keyframes drawChart {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
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
            pointer-events: none;
        }

        /* محتوای اصلی */
        #mainContent {
            display: none;
            opacity: 1;
        }

        .content-loaded {
            display: block;
            opacity: 1;
            animation: contentFadeIn 0.8s ease;
        }

        @keyframes contentFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* استایل‌های ریسپانسیو */
        @media (max-width: 768px) {
            .currency-loader-text {
                font-size: 22px;
            }
            
            .currency-loader-subtext {
                font-size: 14px;
                max-width: 280px;
            }
            
            .exchange-spinner {
                width: 120px;
                height: 120px;
            }
            
            .coin-spinner {
                width: 120px;
                height: 120px;
            }
            
            .coin-spinner::before {
                width: 100px;
                height: 100px;
            }
            
            .currency-progress-container {
                width: 280px;
            }
            
            .currency-numbers {
                gap: 15px;
            }
            
            .currency-number {
                min-width: 70px;
            }
            
            .currency-number-value {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .currency-loader-text {
                font-size: 20px;
            }
            
            .currency-loader-subtext {
                font-size: 13px;
                max-width: 250px;
            }
            
            .exchange-spinner {
                width: 100px;
                height: 100px;
                margin-bottom: 20px;
            }
            
            .coin-spinner {
                width: 100px;
                height: 100px;
            }
            
            .coin-spinner::before {
                width: 85px;
                height: 85px;
            }
            
            .coin-spinner::after {
                font-size: 14px;
            }
            
            .currency-progress-container {
                width: 250px;
            }
            
            .currency-numbers {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .currency-number {
                min-width: 60px;
            }
        }

        /* استایل‌های باقیمانده اصلی */
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
                z-index: 0;
                height: auto;
                box-shadow: none;
                padding: 1.25rem;
            }
        }

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

        .responsive-text {
            font-size: 1.5rem;
        }

        @media (min-width: 768px) {
            .responsive-text {
                font-size: 2.5rem;
            }
        }

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

    <!-- لودر جدید بازار ارز -->
    <div id="currency-loader">
        <div class="currency-flow">
            <div class="currency-item"></div>
            <div class="currency-item"></div>
            <div class="currency-item"></div>
            <div class="currency-item"></div>
            <div class="currency-item"></div>
        </div>

        <div class="currency-loader-container">
            <!-- اسپینر ارزی -->
            <div class="exchange-spinner">
                <div class="coin-spinner">
                    <div class="coin-detail"></div>
                </div>
                <div class="rotating-currencies">
                    <div class="currency-symbol">$</div>
                    <div class="currency-symbol">€</div>
                    <div class="currency-symbol">£</div>
                    <div class="currency-symbol">¥</div>
                </div>
            </div>

            <!-- متن لودر -->
            <div class="currency-loader-text">سیستم صرافی اقصی</div>
            <div class="currency-loader-name"><?php echo e(Auth::guard('sarafi')->user()->sarafi_name); ?></div>
            
            <div class="currency-loader-subtext">
                در حال بارگذاری سیستم معاملات ارزی...
                <br>
                بازارهای جهانی در حال به‌روزرسانی
            </div>

            <!-- اعداد ارزی -->
            <div class="currency-numbers">
                <div class="currency-number">
                    <span class="currency-number-value">$1.25</span>
                    <span class="currency-number-label">یورو/دلار</span>
                </div>
                <div class="currency-number">
                    <span class="currency-number-value">£1.10</span>
                    <span class="currency-number-label">پوند/دلار</span>
                </div>
                <div class="currency-number">
                    <span class="currency-number-value">¥145</span>
                    <span class="currency-number-label">ین/دلار</span>
                </div>
                <div class="currency-number">
                    <span class="currency-number-value">₿45,200</span>
                    <span class="currency-number-label">بیت‌کوین</span>
                </div>
            </div>

            <!-- نوار پیشرفت -->
            <div class="currency-progress-container">
                <div class="progress-text">در حال بارگذاری...</div>
                <div class="currency-progress"></div>
            </div>
        </div>

        <!-- SVG برای گرادیانت نمودار -->
        <svg style="position: absolute; width: 0; height: 0;">
            <defs>
                <linearGradient id="chartGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#ffd700" />
                    <stop offset="25%" stop-color="#3b82f6" />
                    <stop offset="50%" stop-color="#10b981" />
                    <stop offset="75%" stop-color="#f59e0b" />
                    <stop offset="100%" stop-color="#ef4444" />
                </linearGradient>
            </defs>
        </svg>
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
                            <div class="relative" x-data="customerSearch()" x-init="init()">

                                <input type="text" x-model="searchQuery" @input.debounce.500ms="performSearch"
                                    placeholder="جستجو مشتری"
                                    class="border border-[#8C8C8C] placeholder:text-black vazir rounded-[12px] px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">

                                <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                                    class="h-5 w-5 absolute left-2 bottom-3">

                                <!-- آیکون لودینگ -->
                                <div x-show="isLoading" class="absolute left-10 bottom-3">
                                    <div
                                        class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin">
                                    </div>
                                </div>

                                <!-- نتایج جستجو -->
                                <div x-show="showResults && results.length > 0" @click.outside="closeResults"
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">

                                    <div class="p-2">
                                        <template x-for="customer in results" :key="customer.id">
                                            <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b last:border-b-0"
                                                @click="handleCustomerClick(customer)">

                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <!-- عکس مشتری -->
                                                        <div
                                                            class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                                            <template x-if="customer.image">
                                                                <img :src="getImageUrl(customer.image)"
                                                                    :alt="customer.fullname"
                                                                    class="w-full h-full object-cover">
                                                            </template>
                                                            <template x-if="!customer.image">
                                                                <span class="text-gray-600 text-sm font-bold"
                                                                    x-text="getFirstLetter(customer.fullname)"></span>
                                                            </template>
                                                        </div>

                                                        <!-- اطلاعات مشتری -->
                                                        <div>
                                                            <div class="font-medium" x-text="customer.fullname"></div>
                                                            <div class="text-xs text-gray-500">
                                                                <span x-text="customer.phone"></span>
                                                                <span class="mx-1">•</span>
                                                                <span class="dir-ltr"
                                                                    x-text="customer.account_number"></span>
                                                                <span x-show="customer.city" class="mr-2">
                                                                    • <span x-text="customer.city"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- وضعیت و دکمه عمل -->
                                                    <div class="text-xs flex items-center gap-2">
                                                        <template x-if="customer.is_mine">
                                                            <span
                                                                class="bg-green-100 text-green-800 px-2 py-1 rounded">مشتری
                                                                من</span>
                                                        </template>
                                                        <template x-if="!customer.is_mine && customer.admin_id">
                                                            <button @click.stop="linkCustomer(customer)"
                                                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition">
                                                                لینک کن
                                                            </button>
                                                        </template>
                                                        <template x-if="!customer.admin_id">
                                                            <button @click.stop="linkCustomer(customer)"
                                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-200 transition">
                                                                استفاده کن
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- مودال تایید -->
                                <div x-show="showConfirmModal"
                                    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4">

                                    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">

                                        <!-- هدر مودال -->
                                        <div class="flex justify-between items-center p-6 border-b">
                                            <h3 class="text-xl font-bold text-gray-800">لینک کردن مشتری</h3>
                                            <button @click="showConfirmModal = false"
                                                class="text-gray-500 hover:text-gray-700 text-2xl">
                                                ✕
                                            </button>
                                        </div>

                                        <!-- بدنه مودال -->
                                        <div class="p-6" x-show="selectedCustomer">
                                            <div class="mb-6">
                                                <div class="flex items-center gap-4 mb-4">
                                                    <template x-if="selectedCustomer.image">
                                                        <img :src="getImageUrl(selectedCustomer.image)"
                                                            :alt="selectedCustomer.fullname"
                                                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                                    </template>
                                                    <template x-if="!selectedCustomer.image">
                                                        <div
                                                            class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center border-2 border-gray-200">
                                                            <span class="text-blue-600 text-xl font-bold"
                                                                x-text="getFirstLetter(selectedCustomer.fullname)"></span>
                                                        </div>
                                                    </template>

                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-800"
                                                            x-text="selectedCustomer.fullname"></h4>
                                                        <p class="text-gray-600" x-text="selectedCustomer.phone"></p>
                                                        <p class="text-gray-500 text-sm dir-ltr"
                                                            x-text="selectedCustomer.account_number"></p>
                                                    </div>
                                                </div>

                                                <div class="p-4 bg-blue-50 rounded-lg">
                                                    <p class="text-blue-700 text-sm">
                                                        آیا می‌خواهید این مشتری را به لیست مشتریان خود اضافه کنید؟
                                                        <br>

                                                    </p>
                                                </div>
                                            </div>

                                            <!-- دکمه‌ها -->
                                            <div class="flex gap-3">
                                                <button @click="confirmLinkCustomer"
                                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                                    <svg x-show="!isLinking" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    <div x-show="isLinking"
                                                        class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin">
                                                    </div>
                                                    <span x-text="isLinking ? 'در حال لینک...' : 'بله، لینک کن'"></span>
                                                </button>

                                                <button @click="showConfirmModal = false" :disabled="isLinking"
                                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 rounded-lg font-medium transition-colors disabled:opacity-50">
                                                    انصراف
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <script>
                            function customerSearch() {
    return {
        searchQuery: '',
        results: [],
        showResults: false,
        isLoading: false,
        selectedCustomer: null,
        showConfirmModal: false,
        isLinking: false,
        
        init() {
            console.log('Customer search initialized');
        },
        
        async performSearch() {
            if (this.searchQuery.length < 2) {
                this.showResults = false;
                this.results = [];
                return;
            }
            
            this.isLoading = true;
            
            try {
                const response = await fetch(`<?php echo e(route('api.search-customers')); ?>?q=${encodeURIComponent(this.searchQuery)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.error) {
                    console.error('Search error:', data.error);
                    return;
                }
                
                if (data.customers && data.customers.length > 0) {
                    this.results = data.customers;
                    this.showResults = true;
                } else {
                    this.results = [];
                    this.showResults = false;
                }
                
            } catch (error) {
                console.error('Search error:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        closeResults() {
            this.showResults = false;
        },
        
        getImageUrl(imagePath) {
            return imagePath ? `/storage/${imagePath}` : '';
        },
        
        getFirstLetter(name) {
            return name ? name.charAt(0).toUpperCase() : '?';
        },
        
        handleCustomerClick(customer) {
            if (customer.is_mine) {
                // اگر مشتری مال خودتان است، به صفحه مشتری بروید
                window.location.href = `<?php echo e(route('sarafi.customer-table')); ?>?customer=${customer.id}`;
            } else {
                // در غیر این صورت، مودال لینک را نشان دهید
                this.selectedCustomer = customer;
                this.showConfirmModal = true;
                this.showResults = false;
            }
        },
        
        linkCustomer(customer) {
            this.selectedCustomer = customer;
            this.showConfirmModal = true;
            this.showResults = false;
        },
        
        async confirmLinkCustomer() {
            if (!this.selectedCustomer) return;
            
            this.isLinking = true;
            
            try {
                const response = await fetch('<?php echo e(route("api.link-customer")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        customer_id: this.selectedCustomer.id 
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // نمایش پیام موفقیت
                    alert(data.message);
                    
                    // بستن مودال و پاک کردن جستجو
                    this.showConfirmModal = false;
                    this.searchQuery = '';
                    this.results = [];
                    
                    // ریدایرکت به صفحه مشتریان یا رفرش
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route("sarafi.customer-table")); ?>';
                    }, 1000);
                    
                } else {
                    alert(data.message);
                    this.showConfirmModal = false;
                }
                
            } catch (error) {
                console.error('Link error:', error);
                alert('خطا در لینک کردن مشتری');
            } finally {
                this.isLinking = false;
            }
        }
    };
}
                        </script>

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
                                    class="w-[50px] h-[50px] md:w-[60px] md:h-[60px] rounded-full border overflow-hidden flex items-center justify-center cursor-pointer transition">
                                    <img src="<?php echo e(asset('assets/sarafi/avatar.svg')); ?>" alt="پروفایل"
                                        class="w-full h-full object-cover">
                                </div>

                                <!-- منو dropdown -->
                                <div id="profileDropdownDesktop"
                                    style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;"
                                    class="absolute top-full left-0 space-y-3 text-2xl w-72 h-76 bg-white rounded-lg shadow-lg border hidden z-50 p-4">

                                    <div class="p-3 border-b space-y-5">
                                        <div class="flex flex-col justify-center items-center ">
                                            <img src="<?php echo e(asset('assets/sarafi/avatar.svg')); ?>" alt="" class="h-20 w-20">
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
                            <a href="<?php echo e(route('sarafi.profit-rates')); ?>"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('register-accounts', 'accounts')"
                                :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="<?php echo e(asset('assets/sarafi/all_icon/add.svg')); ?>" class="w-4 h-4"
                                    :class="active === 'register-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                                ثبت نرخ ارزها
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
            const loader = document.getElementById('currency-loader');
            const mainContent = document.getElementById('mainContent');
            const progressBar = document.querySelector('.currency-progress');
            const progressText = document.querySelector('.progress-text');
            const currencyValues = document.querySelectorAll('.currency-number-value');

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

            // مخفی کردن محتوای اصلی در ابتدا
            mainContent.style.display = 'none';

            let progress = 0;
            let fakeProgressInterval;

            // شبیه‌سازی نرخ ارز
            function simulateExchangeRates() {
                currencyValues.forEach(value => {
                    const symbol = value.textContent.charAt(0);
                    let number = parseFloat(value.textContent.substring(1));
                    
                    // تغییرات کوچک تصادفی
                    const change = (Math.random() - 0.5) * 0.02;
                    number = number * (1 + change);
                    
                    // فرمت کردن عدد
                    if (symbol === '₿') {
                        value.textContent = `${symbol}${Math.round(number).toLocaleString()}`;
                    } else {
                        value.textContent = `${symbol}${number.toFixed(2)}`;
                    }
                });
            }

            // شروع شبیه‌سازی
            const rateInterval = setInterval(simulateExchangeRates, 2000);

            function startFakeProgress() {
                fakeProgressInterval = setInterval(() => {
                    progress += Math.random() * 20 + 5;
                    if (progress > 95) progress = 95;
                    progressBar.style.width = progress + '%';
                    
                    // به‌روزرسانی متن پیشرفت
                    if (progress < 30) {
                        progressText.textContent = 'در حال بارگذاری ماژول‌ها...';
                    } else if (progress < 60) {
                        progressText.textContent = 'در حال بارگذاری داده‌های بازار...';
                    } else if (progress < 90) {
                        progressText.textContent = 'آماده‌سازی نهایی...';
                    }
                }, 200);
            }

            startFakeProgress();

            window.addEventListener('load', function() {
                clearInterval(fakeProgressInterval);
                progress = 100;
                progressBar.style.width = progress + '%';
                progressText.textContent = 'بارگذاری کامل شد!';
                clearInterval(rateInterval);

                setTimeout(() => {
                    loader.classList.add('loader-complete');
                    mainContent.style.display = 'block';
                    mainContent.classList.add('content-loaded');

                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 400);
                }, 800);
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