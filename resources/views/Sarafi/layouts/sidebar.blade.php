<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" name="csrf-token"
        content="{{ csrf_token() }}">
    <title>سیستم صرافی اقصی</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {}
        }
    }
    </script>
    @include('Sarafi.layouts.links')
    <style>
        /* Chat box */


        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

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

        .dark {
            color: white;
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
            background-color: black;
            color: #e2e8f0;
        }

        .dark header {
            background-color: black;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);
        }

        .dark #loader {
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

        .dark aside {
            background-color: #2d3748;
        }

        .dark input {
            background-color: #4a5568;
            color: #e2e8f0;
            border-color: #1a1b1e;
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
    <div id="loader" class="loading">
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
                    <span>{{ mb_substr(Auth::guard('sarafi')->user()->sarafi_name, 0, 1) }}</span>
                </div>
            </div>

            <div class="loader-text">صرافــی {{ Auth::guard('sarafi')->user()->sarafi_name }}</div>
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
            class="bg-white w-full py-4 md:py-0 md:h-[80px] flex items-center dark:shadow-[0_4px_4px_rgba(255,255,255,0.5)] shadow-[0_4px_4px_rgba(17,41,199,0.4)]">
            <div class="header-container">
                <!-- لایه موبایل -->
                <div class="mobile-header-layout">
                    <div class="mobile-header-top">
                        <!-- برند -->
                        <div class="mobile-brand yekan">{{ Auth::guard('sarafi')->user()->sarafi_name }}</div>

                        <!-- اعلان و پروفایل -->
                        <div class="mobile-actions">
                            <button
                                class="btn-mobile-small relative rounded-full bg-[#E5E5E5] hover:bg-gray-300 transition">
                                <img src="{{ asset('assets/sarafi/all_icon/bill-header.svg') }}" alt="اعلان"
                                    class="w-6 h-6">
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                            </button>

                            <div class="header-profile-section">
                                <div class="relative">
                                    <div id="profileBtnMobile"
                                        class="profile-img-mobile border overflow-hidden flex items-center justify-center cursor-pointer transition">
                                        <img src="{{ asset('assets/sarafi/all_icon/man.png') }}" alt="پروفایل"
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
                                <input type="text" placeholder="{{ __('messages.search_placeholder') }}"
                                    class="border border-[#8C8C8C] placeholder:text-black vazir rounded-full px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm">
                                <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                                    class="h-4 w-4 absolute left-2 bottom-3">
                            </div>
                        </div>

                        <!-- زبان و دارک مود -->
                        <div class="mobile-tools">
                            @php $locale = session('locale', config('app.locale')); @endphp
                            <div class="relative dropdown-mobile vazir">
                                <button id="dropdownButtonMobile"
                                    class="border border-[#1129C766] bg-white rounded-lg px-2 py-1 w-full flex items-center justify-between font-vazir text-xs text-[#1129C7]">
                                    <img src="{{ $locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png') }}"
                                        class="w-4 h-4 ml-1" alt="Lang">
                                    <span>
                                        @if ($locale === 'fa') فارسی
                                        @elseif($locale === 'ps') پشتو
                                        @else EN
                                        @endif
                                    </span>
                                </button>

                                <ul id="dropdownMenuMobile"
                                    class="absolute left-5 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                                    <li><a href="{{ route('set-locale', 'fa') }}"
                                            class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                                src="{{ asset('assets/sarafi/all_icon/Flags.png') }}"
                                                class="w-4 h-4 ml-1" alt="fa">
                                            فارسی</a></li>
                                    <li><a href="{{ route('set-locale', 'ps') }}"
                                            class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                                src="{{ asset('assets/sarafi/all_icon/Flags.png') }}"
                                                class="w-4 h-4 ml-1" alt="ps">
                                            پشتو</a></li>
                                    <li><a href="{{ route('set-locale', 'en') }}"
                                            class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                                src="{{ asset('assets/sarafi/all_icon/united.png') }}"
                                                class="w-4 h-4 ml-1" alt="en"> English</a></li>
                                </ul>
                            </div>

                            {{--
                            <!-- سوییچ دارک مود -->
                            <div class="relative dark-mode-toggle-mobile">
                                <input type="checkbox" id="darkModeToggleMobile" class="sr-only">
                                <label for="darkModeToggleMobile"
                                    class="flex items-center w-full h-full bg-gray-300 rounded-full cursor-pointer transition-colors duration-300 ease-in-out dark:bg-gray-700 px-1">
                                    <span id="toggleCircleMobile"
                                        class="flex items-center justify-center bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out">
                                        <!-- آیکون خورشید -->
                                        <svg id="sunIconMobile" class="text-yellow-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <!-- آیکون ماه -->
                                        <svg id="moonIconMobile" class="text-blue-300 hidden" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                                            </path>
                                        </svg>
                                    </span>
                                </label>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- لایه دسکتاپ -->
                <div class="desktop-header-layout">
                    <!-- برند + انتخاب زبان -->
                    <div class="desktop-brand-section">
                        <div class="responsive-text dark:text-white text-[#122EE1] font-bold yekan">صرافی {{
                            Auth::guard('sarafi')->user()->sarafi_name }} </div>

                        @php $locale = session('locale', config('app.locale')); @endphp
                        <div class="relative inline-block w-[145px] h-[56px] p-2 vazir">
                            <button id="dropdownButton"
                                class="border border-[#1129C766] dark:text-white dark:bg-black dark:border-[#FFFFFF] bg-white rounded-lg px-3 py-2 w-full flex items-center justify-between font-vazir text-sm text-[#1129C7]">
                                <img src="{{ $locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png') }}"
                                    class="w-5 h-5 ml-2" alt="Lang">
                                <span>
                                    @if ($locale === 'fa') فارسی
                                    @elseif($locale === 'ps') پشتو
                                    @else English
                                    @endif
                                </span>
                            </button>

                            <ul id="dropdownMenu"
                                class="absolute left-0 mt-1 w-ful dark:text-white dark:bg-black bg-white border border-gray-200 dark:hover:bg-gray-800 rounded-lg hidden z-10">
                                <li><a href="{{ route('set-locale', 'fa') }}"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer"><img
                                            src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2"
                                            alt="fa">
                                        فارسی</a></li>
                                <li><a href="{{ route('set-locale', 'ps') }}"
                                        class="locale-link flex items-center px-3 py-2 dark:text-white dark:bg-black dark:hover:bg-gray-800   hover:bg-gray-100 cursor-pointer"><img
                                            src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2"
                                            alt="ps">
                                        پشتو</a></li>
                                <li><a href="{{ route('set-locale', 'en') }}"
                                        class="locale-link flex items-center px-3 py-2 dark:text-white dark:bg-black hover:bg-gray-100  dark:hover:bg-gray-800 cursor-pointer"><img
                                            src="{{ asset('assets/sarafi/all_icon/united.png') }}" class="w-5 h-5 ml-2"
                                            alt="en"> English</a></li>
                            </ul>
                        </div>


                        <!-- سوییچ دارک مود -->
                        <div class="relative inline-block w-16 h-8 mx-4">
                            <input type="checkbox" id="darkModeToggle" class="sr-only">
                            <label for="darkModeToggle"
                                class="flex items-center w-full h-8 bg-gray-300 rounded-full cursor-pointer transition-colors duration-300 ease-in-out dark:bg-gray-700 px-1">
                                <span id="toggleCircle"
                                    class="flex items-center justify-center w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out">
                                    <!-- آیکون خورشید -->
                                    <svg id="sunIcon" class="w-4 h-4 text-yellow-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <!-- آیکون ماه -->
                                    <svg id="moonIcon" class="w-4 h-4 text-blue-300 hidden" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                                        </path>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- سرچ، اعلان، پروفایل -->
                    <div class="desktop-actions-section">
                        <div class="header-search-section">
                            <div class="relative" x-data="customerSearch()" x-init="init()">

                                <input type="text" x-model="searchQuery" @input.debounce.500ms="performSearch"
                                    placeholder="جستجو مشتری"
                                    class="border border-[#8C8C8C] dark:border-[#FFFFFF] dark:bg-black dark:placeholder:text-white placeholder:text-black vazir rounded-[12px] px-3 py-2 pr-10 text-right font-vazir outline-none w-full">

                                <svg width="24" class="h-5 w-5 absolute left-2 bottom-3" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>


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
                                                    const response = await fetch(`{{ route('api.search-customers') }}?q=${encodeURIComponent(this.searchQuery)}`, {
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
                                                    window.location.href = `{{ route('sarafi.customer-table') }}?customer=${customer.id}`;
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
                                                    const response = await fetch('{{ route("api.link-customer") }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                                                            window.location.href = '{{ route("sarafi.customer-table") }}';
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
                            <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.0262 3.63745C10.8887 3.63745 7.52621 6.99995 7.52621 11.1375V14.75C7.52621 15.5125 7.20121 16.675 6.81371 17.325L5.37621 19.7125C4.48871 21.1875 5.10121 22.825 6.72621 23.375C12.1137 25.175 17.9262 25.175 23.3137 23.375C24.8262 22.875 25.4887 21.0875 24.6637 19.7125L23.2262 17.325C22.8512 16.675 22.5262 15.5125 22.5262 14.75V11.1375C22.5262 7.01245 19.1512 3.63745 15.0262 3.63745Z"
                                    stroke="#404040" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                                <path
                                    d="M17.3359 4.00005C16.9484 3.88755 16.5484 3.80005 16.1359 3.75005C14.9359 3.60005 13.7859 3.68755 12.7109 4.00005C13.0734 3.07505 13.9734 2.42505 15.0234 2.42505C16.0734 2.42505 16.9734 3.07505 17.3359 4.00005Z"
                                    stroke="#404040" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M18.7734 23.825C18.7734 25.8875 17.0859 27.575 15.0234 27.575C13.9984 27.575 13.0484 27.15 12.3734 26.475C11.6984 25.8 11.2734 24.85 11.2734 23.825"
                                    stroke="#404040" stroke-width="1.5" stroke-miterlimit="10" />
                            </svg>


                            {{-- <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                            --}}
                        </button>

                        <div class="header-profile-section">
                            <div class="relative">
                                <div id="profileBtnDesktop"
                                    class="w-[50px] h-[50px] md:w-[60px] md:h-[60px] rounded-full  overflow-hidden flex items-center justify-center cursor-pointer transition">
                                    <img src="{{ asset('assets/sarafi/avatar.svg') }}" alt="پروفایل"
                                        class="w-full h-full object-cover">
                                </div>

                                <!-- منو dropdown -->
                                <div id="profileDropdownDesktop"
                                    style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;"
                                    class="absolute top-full left-0 space-y-3 text-2xl w-72 h-76 bg-white rounded-lg shadow-lg border hidden z-50 p-4">

                                    <div class="p-3 border-b space-y-5">
                                        <div class="flex flex-col justify-center items-center ">
                                            <img src="{{ asset('assets/sarafi/avatar.svg') }}" alt="" class="h-20 w-20">
                                            <p class="font-vazir font-semibold text-gray-700 mt-5">{{
                                                Auth::guard('sarafi')->user()->name }}</p>

                                        </div>

                                    </div>
                                    <div class="flex justify-start items-center  ">
                                        <img src="{{ asset('assets/sarafi/all_icon/account_profile.svg') }}" alt="">

                                        <a href="{{ route('sarafi.users') }}"
                                            class="block px-4 py-2 text-gray-700 vazir">تنظیمات</a>
                                    </div>

                                    <form action="{{ route('sarafi.logout') }}" method="POST">
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
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-col md:flex-row mt-4 min-h-screen dark:text-white dark:bg-black">
            <!-- سایدبار -->
            <div class="sidebar-container dark:bg-black  dark:text-white" id="sidebar">
                <nav class="mt-0 space-y-0 dark:text-white" x-data="{
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
                    <a href="{{ route('sarafi.home') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                        @click="active = 'dashboard'"
                        :class="active === 'dashboard' ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white dark:hover:bg-gray-800 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/element-3.svg') }}" class="w-5 h-5 dark:hidden"
                                :class="active === 'dashboard' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            <svg width="26" height="24" class="hidden dark:block" viewBox="0 0 26 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23.8333 8.52V3.98C23.8333 2.57 23.14 2 21.4175 2H17.0408C15.3183 2 14.625 2.57 14.625 3.98V8.51C14.625 9.93 15.3183 10.49 17.0408 10.49H21.4175C23.14 10.5 23.8333 9.93 23.8333 8.52Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M23.8333 19.77V15.73C23.8333 14.14 23.14 13.5 21.4175 13.5H17.0408C15.3183 13.5 14.625 14.14 14.625 15.73V19.77C14.625 21.36 15.3183 22 17.0408 22H21.4175C23.14 22 23.8333 21.36 23.8333 19.77Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M11.3724 8.52V3.98C11.3724 2.57 10.6791 2 8.95656 2H4.5799C2.8574 2 2.16406 2.57 2.16406 3.98V8.51C2.16406 9.93 2.8574 10.49 4.5799 10.49H8.95656C10.6791 10.5 11.3724 9.93 11.3724 8.52Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M11.3724 19.77V15.73C11.3724 14.14 10.6791 13.5 8.95656 13.5H4.5799C2.8574 13.5 2.16406 14.14 2.16406 15.73V19.77C2.16406 21.36 2.8574 22 4.5799 22H8.95656C10.6791 22 11.3724 21.36 11.3724 19.77Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                            {{ __('messages.dashboard') }}
                        </span>
                    </a>

                    <!-- کاربران -->
                    <a href="{{ route('sarafi.users') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir dark:text-white"
                        @click="active = 'users'"
                        :class="active === 'users' ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white dark:hover:bg-gray-800 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile-2user.svg') }}"
                                class="w-5 h-5 dark:hidden"
                                :class="active === 'users' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            <svg width="25" class="hidden dark:block" height="25" viewBox="0 0 30 30" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.4531 13.5875C11.3281 13.575 11.1781 13.575 11.0406 13.5875C8.06562 13.4875 5.70312 11.05 5.70312 8.05C5.70312 4.9875 8.17813 2.5 11.2531 2.5C14.3156 2.5 16.8031 4.9875 16.8031 8.05C16.7906 11.05 14.4281 13.4875 11.4531 13.5875Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M20.5141 5C22.9391 5 24.8891 6.9625 24.8891 9.375C24.8891 11.7375 23.0141 13.6625 20.6766 13.75C20.5766 13.7375 20.4641 13.7375 20.3516 13.75"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M5.19844 18.2C2.17344 20.225 2.17344 23.525 5.19844 25.5375C8.63594 27.8375 14.2734 27.8375 17.7109 25.5375C20.7359 23.5125 20.7359 20.2125 17.7109 18.2C14.2859 15.9125 8.64844 15.9125 5.19844 18.2Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M22.9219 25C23.8219 24.8125 24.6719 24.45 25.3719 23.9125C27.3219 22.45 27.3219 20.0375 25.3719 18.575C24.6844 18.05 23.8469 17.7 22.9594 17.5"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <span class="dark:text-white"> {{ __('messages.users') }}</span>
                        </span>
                    </a>

                    <!-- مشتریان -->
                    <div>
                        <button @click="openItems.customers = !openItems.customers; active = 'customers'"
                            :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:hover:bg-gray-800 dark:text-white hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/people.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white dark:hover:bg-gray-800 '">
                                <svg width="25" height="25" class="hidden dark:block" viewBox="0 0 30 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M22.5016 8.95C22.4266 8.9375 22.3391 8.9375 22.2641 8.95C20.5391 8.8875 19.1641 7.475 19.1641 5.725C19.1641 3.9375 20.6016 2.5 22.3891 2.5C24.1766 2.5 25.6141 3.95 25.6141 5.725C25.6016 7.475 24.2266 8.8875 22.5016 8.95Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M21.2172 18.05C22.9297 18.3375 24.8172 18.0375 26.1422 17.15C27.9047 15.975 27.9047 14.05 26.1422 12.875C24.8047 11.9875 22.8922 11.6875 21.1797 11.9875"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M7.45625 8.95C7.53125 8.9375 7.61875 8.9375 7.69375 8.95C9.41875 8.8875 10.7937 7.475 10.7937 5.725C10.7937 3.9375 9.35625 2.5 7.56875 2.5C5.78125 2.5 4.34375 3.95 4.34375 5.725C4.35625 7.475 5.73125 8.8875 7.45625 8.95Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M8.74687 18.05C7.03437 18.3375 5.14687 18.0375 3.82188 17.15C2.05938 15.975 2.05938 14.05 3.82188 12.875C5.15938 11.9875 7.07187 11.6875 8.78437 11.9875"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M15.0016 18.2874C14.9266 18.2749 14.8391 18.2749 14.7641 18.2874C13.0391 18.2249 11.6641 16.8124 11.6641 15.0624C11.6641 13.2749 13.1016 11.8374 14.8891 11.8374C16.6766 11.8374 18.1141 13.2874 18.1141 15.0624C18.1016 16.8124 16.7266 18.2374 15.0016 18.2874Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M11.3609 22.225C9.59844 23.4 9.59844 25.3249 11.3609 26.4999C13.3609 27.8374 16.6359 27.8374 18.6359 26.4999C20.3984 25.3249 20.3984 23.4 18.6359 22.225C16.6484 20.9 13.3609 20.9 11.3609 22.225Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.customers') }}
                            </span>
                            <svg :class="[openItems.customers ? 'rotate-180' : '', (active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'text-white' : 'text-gray-500 dark:text-white dark:hover:bg-gray-800 ']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.customers" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.customer-create') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-create', 'customers')"
                                :class="active === 'customer-create' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:hover:bg-gray-800 dark:text-white hover:bg-gray-100'">
                                <i class="fa-solid fa-user-pen w-4 h-4"
                                    :class="active === 'customer-create' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_create') }}
                            </a>

                            <a href="{{ route('sarafi.customer-table') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-table', 'customers')"
                                :class="active === 'customer-table' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:hover:bg-gray-800 dark:text-white hover:bg-gray-100'">
                                <i class="fa-solid fa-users-gear h-4 w-4"
                                    :class="active === 'customer-table' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_list') }}
                            </a>
                        </div>
                    </div>

                    <!-- ثبت حسابات و نرخ ارز -->
                    <div>
                        <button @click="openItems.accounts = !openItems.accounts; active = 'accounts'"
                            :class="(active === 'accounts' || active === 'register-accounts') ? 'bg-[#122EE1] text-white' : 'text-gray-700  dark:hover:bg-gray-800 dark:text-white hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2 ">
                                <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'accounts' || active === 'register-accounts') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">
                                <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.2633 3.59997L5.05327 12.29C4.74327 12.62 4.44327 13.27 4.38327 13.72L4.01327 16.96C3.88327 18.13 4.72327 18.93 5.88327 18.73L9.10327 18.18C9.55327 18.1 10.1833 17.77 10.4933 17.43L18.7033 8.73997C20.1233 7.23997 20.7633 5.52997 18.5533 3.43997C16.3533 1.36997 14.6833 2.09997 13.2633 3.59997Z"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M11.8906 5.05005C12.3206 7.81005 14.5606 9.92005 17.3406 10.2"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3 22H21" stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.accounts') }}
                            </span>
                            <svg :class="[openItems.accounts ? 'rotate-180' : '', (active === 'accounts' || active === 'register-accounts') ? 'text-white' : 'text-gray-500  dark:hover:bg-gray-800 dark:text-white']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.accounts" x-transition class="mr-6 mt-1 space-y-1">
                            {{-- <a href="{{ route('sarafi.exchange-rate') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('register-accounts', 'accounts')"
                                :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <img src="{{ asset('assets/sarafi/all_icon/add.svg') }}" class="w-4 h-4"
                                    :class="active === 'register-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                                ثبت نرخ بیلانس
                            </a> --}}


                            <a href="{{ route('sarafi.profit-rates') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('register-accounts', 'accounts')"
                                :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.5 12.6499V16.3499C18.5 19.4699 15.59 21.9999 12 21.9999C8.41 21.9999 5.5 19.4699 5.5 16.3499V12.6499C5.5 15.7699 8.41 17.9999 12 17.9999C15.59 17.9999 18.5 15.7699 18.5 12.6499Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M18.5 7.65C18.5 8.56 18.25 9.4 17.81 10.12C16.74 11.88 14.54 13 12 13C9.46 13 7.26 11.88 6.19 10.12C5.75 9.4 5.5 8.56 5.5 7.65C5.5 6.09 6.22999 4.68 7.39999 3.66C8.57999 2.63 10.2 2 12 2C13.8 2 15.42 2.63 16.6 3.65C17.77 4.68 18.5 6.09 18.5 7.65Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M18.5 7.65V12.65C18.5 15.77 15.59 18 12 18C8.41 18 5.5 15.77 5.5 12.65V7.65C5.5 4.53 8.41 2 12 2C13.8 2 15.42 2.63 16.6 3.65C17.77 4.68 18.5 6.09 18.5 7.65Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                ثبت نرخ ارزها
                            </a>
                        </div>
                    </div>


                    <!-- کنترول و بررسی معاملات -->
                    <div>
                        <button @click="openItems.transactions = !openItems.transactions; active = 'transactions'"
                            :class="(active === 'transactions' || active === 'control-transactions') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/health.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'transactions' || active === 'control-transactions') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">
                                <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.96875 22H14.9688C19.9688 22 21.9688 20 21.9688 15V9C21.9688 4 19.9688 2 14.9688 2H8.96875C3.96875 2 1.96875 4 1.96875 9V15C1.96875 20 3.96875 22 8.96875 22Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M1.96875 12.7001L7.96875 12.6801C8.71875 12.6801 9.55875 13.2501 9.83875 13.9501L10.9787 16.8301C11.2387 17.4801 11.6487 17.4801 11.9087 16.8301L14.1987 11.0201C14.4187 10.4601 14.8287 10.4401 15.1087 10.9701L16.1487 12.9401C16.4587 13.5301 17.2587 14.0101 17.9187 14.0101H21.9788"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>


                                {{ __('messages.transactions') }}
                            </span>
                            <svg :class="[openItems.transactions ? 'rotate-180' : '', (active === 'transactions' || active === 'control-transactions') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.transactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.remittance-approval') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('control-transactions', 'transactions')"
                                :class="active === 'control-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.24 2H8.76004C5.00004 2 4.71004 5.38 6.74004 7.22L17.26 16.78C19.29 18.62 19 22 15.24 22H8.76004C5.00004 22 4.71004 18.62 6.74004 16.78L17.26 7.22C19.29 5.38 19 2 15.24 2Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                احواله های تایید نشده
                            </a>
                        </div>
                    </div>

                    <!-- بررسی معاملات حذف شده -->
                    <div>
                        <button
                            @click="openItems.deletedTransactions = !openItems.deletedTransactions; active = 'deletedTransactions'"
                            :class="(active === 'deletedTransactions' || active === 'deleted-transactions') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/trash.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'deletedTransactions' || active === 'deleted-transactions') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">
                                <svg width="21" height="21" class="hidden dark:block" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M18.8484 9.13989L18.1984 19.2099C18.0884 20.7799 17.9984 21.9999 15.2084 21.9999H8.78844C5.99844 21.9999 5.90844 20.7799 5.79844 19.2099L5.14844 9.13989"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.3281 16.5H13.6581" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.5 12.5H14.5" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.deleted_transactions') }}
                            </span>
                            <svg :class="[openItems.deletedTransactions ? 'rotate-180' : '', (active === 'deletedTransactions' || active === 'deleted-transactions') ? 'text-white' : 'text-gray-500 dark:text-white']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.deletedTransactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.trash-edit') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('deleted-transactions', 'deletedTransactions')"
                                :class="active === 'deleted-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M17.9 9.04997C15.72 8.82997 13.52 8.71997 11.33 8.71997C10.03 8.71997 8.72997 8.78997 7.43997 8.91997L6.09998 9.04997"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M9.70996 8.38994L9.84996 7.52994C9.94996 6.90994 10.03 6.43994 11.14 6.43994H12.86C13.97 6.43994 14.0499 6.92994 14.1499 7.52994L14.2899 8.37994"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M16.49 9.12988L16.06 15.7299C15.99 16.7599 15.93 17.5599 14.1 17.5599H9.89C8.06 17.5599 7.99999 16.7599 7.92999 15.7299L7.5 9.12988"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                معاملات حذف شده و ویرایش شده
                            </a>
                        </div>
                    </div>


                    <!-- گزارش و آمار حسابات -->
                    <div>
                        <button @click="openItems.reports = !openItems.reports; active = 'reports'"
                            :class="(active === 'reports' || active === 'view-reports') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white dark:hover:bg-gray-800 hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/graph.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'reports' || active === 'view-reports') ? 'filter invert brightness-0' : 'text-gray-500'">
                                <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.32 11.9999C20.92 11.9999 22 10.9999 21.04 7.71994C20.39 5.50994 18.49 3.60994 16.28 2.95994C13 1.99994 12 3.07994 12 5.67994V8.55994C12 10.9999 13 11.9999 15 11.9999H18.32Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M20.0014 14.7C19.0714 19.33 14.6314 22.69 9.5814 21.87C5.7914 21.26 2.7414 18.21 2.1214 14.42C1.3114 9.39001 4.6514 4.95001 9.2614 4.01001"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.reports') }}
                            </span>
                            <svg :class="[openItems.reports ? 'rotate-180' : '', (active === 'reports' || active === 'view-reports') ? 'text-white' : 'text-gray-500 dark:hover:bg-gray-800 dark:text-white']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.reports" x-transition class="mr-6 mt-1 space-y-1">

                            {{-- گزارش حسابات --}}
                            <a href="{{ route('sarafi.account-reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-reports', 'reports')"
                                :class="active === 'view-reports' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 21H7C3 21 2 20 2 16V8C2 4 3 3 7 3H17C21 3 22 4 22 8V16C22 20 21 21 17 21Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14 8H19" stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M15 12H19" stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M17 16H19" stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M8.49994 11.2899C9.49958 11.2899 10.3099 10.4796 10.3099 9.47992C10.3099 8.48029 9.49958 7.66992 8.49994 7.66992C7.50031 7.66992 6.68994 8.48029 6.68994 9.47992C6.68994 10.4796 7.50031 11.2899 8.49994 11.2899Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 16.33C11.86 14.88 10.71 13.74 9.26 13.61C8.76 13.56 8.25 13.56 7.74 13.61C6.29 13.75 5.14 14.88 5 16.33"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                گزارش حسابات
                            </a>



                            {{-- گزارشات عمومی --}}
                            <a href="{{ route('sarafi.general-reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-reports', 'reports')"
                                :class="active === 'view-reports' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 2V19C2 20.66 3.34 22 5 22H22" stroke="#292D32" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5 17L9.59 11.64C10.35 10.76 11.7 10.7 12.52 11.53L13.47 12.48C14.29 13.3 15.64 13.25 16.4 12.37L21 7"
                                        stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>


                                گزارش عمومی
                            </a>



                            {{-- عواید معاملات --}}
                            <a href="{{ route('sarafi.revenue') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-revenue', 'reports')"
                                :class="active === 'view-revenue' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.87988 18.1501V16.0801" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M12 18.15V14.01" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M17.1201 18.1499V11.9299" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M17.1199 5.8501L16.6599 6.3901C14.1099 9.3701 10.6899 11.4801 6.87988 12.4301"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M14.1899 5.8501H17.1199V8.7701" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                عواید معاملات
                            </a>

                        </div>

                    </div>


                    <!-- معاملات بین صرافی ها-->
                    <div>
                        <button @click="openItems.changersdeal = !openItems.changersdeal; active = 'changersdeal'"
                            :class="(active === 'changersdeal' || active === 'edit-changersdeal') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'changersdeal' || active === 'edit-accounts') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">

                                <svg width="26" height="24" class="hidden dark:block" viewBox="0 0 26 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.9141 2H9.7474C4.33073 2 2.16406 4 2.16406 9V15C2.16406 20 4.33073 22 9.7474 22H16.2474C21.6641 22 23.8307 20 23.8307 15V13"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M17.3731 3.02001L8.83645 10.9C8.51145 11.2 8.18645 11.79 8.12145 12.22L7.65562 15.23C7.48228 16.32 8.31645 17.08 9.49728 16.93L12.7581 16.5C13.2131 16.44 13.8523 16.14 14.1881 15.84L22.7248 7.96001C24.1981 6.60001 24.8914 5.02001 22.7248 3.02001C20.5581 1.02001 18.8465 1.66001 17.3731 3.02001Z"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M16.1562 4.1499C16.8821 6.5399 18.9079 8.4099 21.5079 9.0899"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                ارسال و دریافت از صرافان
                            </span>
                            <svg :class="[openItems.changersdeal ? 'rotate-180' : '', (active === 'changersdeal' || active === 'edit-accounts') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.changersdeal') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.5898 7.67993H14.8298V11.9299" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.8299 7.67993L9.16992 13.3399" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 16.51C9.89 17.81 14.11 17.81 18 16.51" stroke="#292D32"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                ارسال به صرافی
                            </a>
                        </div>

                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.changer_recive') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.5898 13.3398H14.8298V9.09985" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.8299 13.3399L9.16992 7.67993" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 16.51C9.89 17.81 14.11 17.81 18 16.51" stroke="#292D32"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                دریافت از صرافی
                            </a>
                        </div>

                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.sarafi_reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 22H22" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z" stroke="#292D32"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                گزارش حسابات صرافی ها
                            </a>
                        </div>



                    </div>





                    <!-- مدیریت و دسترسی -->
                    <div>
                        <button @click="openItems.management = !openItems.management; active = 'management'"
                            :class="(active === 'management' || active === 'user-management') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/Group 1325.svg') }}"
                                    class="w-5 h-5 dark:hidden"
                                    :class="(active === 'management' || active === 'user-management') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">
                                <svg width="26" height="29" class="hidden dark:block" viewBox="0 0 26 29" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.26035 12.2189C8.55226 12.7093 8.94403 13.1629 9.54282 13.7121C9.79976 13.9477 10.1054 14.1236 10.4331 14.2427C11.4478 14.6117 12.5574 14.6267 13.5817 14.2853L13.6244 14.271C14.0364 14.1337 14.4159 13.9098 14.7215 13.6013C15.5548 12.76 16.0138 12.1215 16.3354 11.2158C16.4511 10.8901 16.6969 10.6219 17.0248 10.5126C17.3933 10.3897 17.6686 10.0716 17.7104 9.68551C17.7523 9.29881 17.7584 8.96851 17.6951 8.65701C17.5344 7.86761 17.2993 7.02633 17.6596 6.30581C17.9844 5.65626 17.9235 4.88039 17.5014 4.28944L16.2505 2.53818C15.1687 1.02373 13.2367 0.375249 11.4603 0.930373L8.99198 1.70173C8.6621 1.80481 8.4375 2.11032 8.4375 2.45592C8.4375 2.68263 8.34012 2.89842 8.17012 3.04842L6.31916 4.68163C5.73039 5.20113 5.67857 6.10119 6.20384 6.68483L6.3445 6.84111C6.69339 7.22877 6.73509 7.80362 6.44579 8.23757C6.28022 8.48592 6.21416 8.79062 6.28274 9.08111C6.36258 9.41933 6.4558 9.69886 6.5921 9.9397C7.05231 10.7529 7.78235 11.416 8.26035 12.2189Z"
                                        stroke="white" stroke-width="1.3" />
                                    <path
                                        d="M4.5 26.15C4.85898 26.15 5.15 25.859 5.15 25.5C5.15 25.141 4.85898 24.85 4.5 24.85V25.5V26.15ZM5.26748 17.3371L5.12264 16.7034L5.26748 17.3371ZM1.72357 21.3432L1.12314 21.0942L1.72357 21.3432ZM1.72357 21.3432L2.324 21.5921L2.97072 20.0324L2.37029 19.7834L1.76986 19.5345L1.12314 21.0942L1.72357 21.3432ZM5.26748 17.3371L5.41231 17.9707L7.51861 17.4893L7.37378 16.8556L7.22894 16.222L5.12264 16.7034L5.26748 17.3371ZM7.37378 16.8556L7.51861 17.4893C9.20446 17.104 10.4 15.6043 10.4 13.875H9.75H9.1C9.1 14.998 8.32366 15.9718 7.22894 16.222L7.37378 16.8556ZM2.37029 19.7834L2.97072 20.0324C3.40277 18.9904 4.31265 18.2221 5.41231 17.9707L5.26748 17.3371L5.12264 16.7034C3.61259 17.0486 2.36315 18.1036 1.76986 19.5345L2.37029 19.7834ZM4.5 25.5V24.85C2.82082 24.85 1.68085 23.1433 2.324 21.5921L1.72357 21.3432L1.12314 21.0942C0.125049 23.5014 1.89413 26.15 4.5 26.15V25.5Z"
                                        fill="white" />
                                    <path
                                        d="M15.6529 17.0496C15.9564 17.2413 16.3579 17.1506 16.5496 16.8471C16.7413 16.5436 16.6506 16.1421 16.3471 15.9504L16 16.5L15.6529 17.0496ZM14.8125 13.5H14.1625V14.3455H14.8125H15.4625V13.5H14.8125ZM14.8125 14.3455H14.1625C14.1625 15.4428 14.7251 16.4636 15.6529 17.0496L16 16.5L16.3471 15.9504C15.7964 15.6026 15.4625 14.9968 15.4625 14.3455H14.8125Z"
                                        fill="white" />
                                    <path d="M12.375 15.75V19.3125" stroke="white" stroke-width="1.3"
                                        stroke-linecap="round" />
                                    <path d="M6.375 25.5H6.75" stroke="white" stroke-width="1.3"
                                        stroke-linecap="round" />
                                    <path d="M8.25 25.5H12.75" stroke="white" stroke-width="1.3"
                                        stroke-linecap="round" />
                                    <path
                                        d="M18.1868 23.5227C19.0623 23.5227 19.772 22.813 19.772 21.9375C19.772 21.062 19.0623 20.3523 18.1868 20.3523C17.3113 20.3523 16.6016 21.062 16.6016 21.9375C16.6016 22.813 17.3113 23.5227 18.1868 23.5227Z"
                                        stroke="white" stroke-width="1.3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M22.0977 23.5227C22.0274 23.6821 22.0064 23.8589 22.0375 24.0303C22.0686 24.2017 22.1503 24.3599 22.2721 24.4844L22.3038 24.5161C22.4021 24.6143 22.48 24.7308 22.5332 24.8591C22.5864 24.9874 22.6138 25.125 22.6138 25.2638C22.6138 25.4027 22.5864 25.5402 22.5332 25.6685C22.48 25.7968 22.4021 25.9134 22.3038 26.0115C22.2057 26.1098 22.0891 26.1877 21.9608 26.2409C21.8325 26.2941 21.695 26.3215 21.5561 26.3215C21.4172 26.3215 21.2797 26.2941 21.1514 26.2409C21.0231 26.1877 20.9066 26.1098 20.8084 26.0115L20.7767 25.9798C20.6522 25.858 20.494 25.7763 20.3226 25.7452C20.1512 25.7141 19.9744 25.7351 19.815 25.8055C19.6587 25.8724 19.5254 25.9837 19.4315 26.1254C19.3377 26.2672 19.2873 26.4333 19.2866 26.6034V26.6932C19.2866 26.9735 19.1752 27.2423 18.9771 27.4405C18.7789 27.6387 18.5101 27.75 18.2298 27.75C17.9495 27.75 17.6807 27.6387 17.4825 27.4405C17.2843 27.2423 17.173 26.9735 17.173 26.6932V26.6456C17.1689 26.4707 17.1122 26.3011 17.0105 26.1588C16.9087 26.0165 16.7665 25.9081 16.6023 25.8477C16.4429 25.7774 16.2661 25.7564 16.0947 25.7875C15.9233 25.8186 15.7651 25.9003 15.6406 26.0221L15.6089 26.0538C15.5107 26.1521 15.3942 26.23 15.2659 26.2832C15.1376 26.3364 15 26.3638 14.8612 26.3638C14.7223 26.3638 14.5848 26.3364 14.4565 26.2832C14.3282 26.23 14.2116 26.1521 14.1135 26.0538C14.0152 25.9557 13.9373 25.8391 13.8841 25.7108C13.8309 25.5825 13.8035 25.445 13.8035 25.3061C13.8035 25.1672 13.8309 25.0297 13.8841 24.9014C13.9373 24.7731 14.0152 24.6566 14.1135 24.5584L14.1452 24.5267C14.267 24.4022 14.3487 24.244 14.3798 24.0726C14.4109 23.9012 14.3899 23.7244 14.3195 23.565C14.2526 23.4087 14.1413 23.2754 13.9996 23.1815C13.8578 23.0877 13.6917 23.0373 13.5216 23.0366H13.4318C13.1515 23.0366 12.8827 22.9252 12.6845 22.7271C12.4863 22.5289 12.375 22.2601 12.375 21.9798C12.375 21.6995 12.4863 21.4307 12.6845 21.2325C12.8827 21.0343 13.1515 20.923 13.4318 20.923H13.4794C13.6543 20.9189 13.8239 20.8622 13.9662 20.7605C14.1085 20.6587 14.2169 20.5165 14.2773 20.3523C14.3476 20.1929 14.3686 20.0161 14.3375 19.8447C14.3064 19.6733 14.2247 19.5151 14.1029 19.3906L14.0712 19.3589C13.9729 19.2607 13.895 19.1442 13.8418 19.0159C13.7886 18.8876 13.7612 18.75 13.7612 18.6112C13.7612 18.4723 13.7886 18.3348 13.8418 18.2065C13.895 18.0782 13.9729 17.9616 14.0712 17.8635C14.1693 17.7652 14.2859 17.6873 14.4142 17.6341C14.5425 17.5809 14.68 17.5535 14.8189 17.5535C14.9578 17.5535 15.0953 17.5809 15.2236 17.6341C15.3519 17.6873 15.4684 17.7652 15.5666 17.8635L15.5983 17.8952C15.7228 18.017 15.881 18.0987 16.0524 18.1298C16.2238 18.1609 16.4006 18.1399 16.56 18.0695H16.6023C16.7586 18.0026 16.8918 17.8913 16.9857 17.7496C17.0796 17.6078 17.13 17.4417 17.1307 17.2716V17.1818C17.1307 16.9015 17.242 16.6327 17.4402 16.4345C17.6384 16.2363 17.9072 16.125 18.1875 16.125C18.4678 16.125 18.7366 16.2363 18.9348 16.4345C19.133 16.6327 19.2443 16.9015 19.2443 17.1818V17.2294C19.245 17.3994 19.2954 17.5655 19.3893 17.7073C19.4831 17.8491 19.6164 17.9603 19.7727 18.0273C19.9321 18.0976 20.1089 18.1186 20.2803 18.0875C20.4517 18.0564 20.6099 17.9747 20.7344 17.8529L20.7661 17.8212C20.8643 17.7229 20.9808 17.645 21.1091 17.5918C21.2374 17.5386 21.375 17.5112 21.5138 17.5112C21.6527 17.5112 21.7902 17.5386 21.9185 17.5918C22.0468 17.645 22.1634 17.7229 22.2615 17.8212C22.3598 17.9193 22.4377 18.0359 22.4909 18.1642C22.5441 18.2925 22.5715 18.43 22.5715 18.5689C22.5715 18.7078 22.5441 18.8453 22.4909 18.9736C22.4377 19.1019 22.3598 19.2184 22.2615 19.3166L22.2298 19.3483C22.108 19.4728 22.0263 19.631 21.9952 19.8024C21.9641 19.9738 21.9851 20.1506 22.0555 20.31V20.3523C22.1224 20.5086 22.2337 20.6418 22.3754 20.7357C22.5172 20.8296 22.6833 20.88 22.8534 20.8807H22.9432C23.2235 20.8807 23.4923 20.992 23.6905 21.1902C23.8887 21.3884 24 21.6572 24 21.9375C24 22.2178 23.8887 22.4866 23.6905 22.6848C23.4923 22.883 23.2235 22.9943 22.9432 22.9943H22.8956C22.7256 22.995 22.5595 23.0454 22.4177 23.1393C22.2759 23.2331 22.1647 23.3664 22.0977 23.5227Z"
                                        stroke="white" stroke-width="1.3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>


                                {{ __('messages.management') }}
                            </span>
                            <svg :class="[openItems.management ? 'rotate-180' : '', (active === 'management' || active === 'user-management') ? 'text-white' : 'text-gray-500 dark:text-white']"
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
                                :class="active === 'user-management' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/user.svg') }}" class="w-4 h-4"
                                    :class="active === 'user-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.user_management') }}
                            </a>
                        </div>
                    </div>

                    <!-- مدیریت پیامک ها -->
                    <div>
                        <button @click="openItems.sms = !openItems.sms; active = 'sms'"
                            :class="(active === 'sms' || active === 'sms-management') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/sms.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'sms' || active === 'sms-management') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">

                                <svg width="26" height="24" class="hidden dark:block" viewBox="0 0 26 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.4141 20.5H7.58073C4.33073 20.5 2.16406 19 2.16406 15.5V8.5C2.16406 5 4.33073 3.5 7.58073 3.5H18.4141C21.6641 3.5 23.8307 5 23.8307 8.5V15.5C23.8307 19 21.6641 20.5 18.4141 20.5Z"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M18.4193 9L15.0284 11.5C13.9126 12.32 12.0818 12.32 10.9659 11.5L7.58594 9"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>


                                {{ __('messages.sms') }}
                            </span>
                            <svg :class="[openItems.sms ? 'rotate-180' : '', (active === 'sms' || active === 'sms-management') ? 'text-white' : 'text-gray-500 dark:text-white  dark:hover:bg-gray-800']"
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
                                :class="active === 'sms-management' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/message.svg') }}" class="w-4 h-4"
                                    :class="active === 'sms-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.sms_management') }}
                            </a>
                        </div>
                    </div>

                    <!-- اطلاعیه های آنلاین -->
                    <div>
                        <button @click="openItems.notifications = !openItems.notifications; active = 'notifications'"
                            :class="(active === 'notifications' || active === 'online-notifications') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/wifi.svg') }}" class="w-5 h-5 dark:hidden"
                                    :class="(active === 'notifications' || active === 'online-notifications') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">

                                <svg width="26" height="24" class="hidden dark:block" viewBox="0 0 26 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.32031 11.8401C9.97865 8.5201 16.0345 8.5201 20.6928 11.8401"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M2.16406 8.3601C8.72906 3.6801 17.2657 3.6801 23.8307 8.3601"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.35156 15.4902C10.7641 13.0502 15.2166 13.0502 18.6291 15.4902"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.1797 19.1501C11.8914 17.9301 14.1122 17.9301 15.8239 19.1501"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.notifications') }}
                            </span>
                            <svg :class="[openItems.notifications ? 'rotate-180' : '', (active === 'notifications' || active === 'online-notifications') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform  dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.notifications" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('online-notifications', 'notifications')"
                                :class="active === 'online-notifications' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/notification.svg') }}" class="w-4 h-4"
                                    :class="active === 'online-notifications' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.online_notifications') }}
                            </a>
                        </div>
                    </div>

                    <!-- پشتیبانی سیستم -->
                    <div>
                        <button @click="openItems.support = !openItems.support; active = 'support'"
                            :class="(active === 'support' || active === 'system-support') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/document-copy.svg') }}"
                                    class="w-5 h-5 dark:hidden"
                                    :class="(active === 'support' || active === 'system-support') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">

                                <svg width="26" height="24" class="hidden dark:block" viewBox="0 0 26 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.4141 13.4V16.4C18.4141 20.4 16.6807 22 12.3474 22H8.23073C3.8974 22 2.16406 20.4 2.16406 16.4V12.6C2.16406 8.6 3.8974 7 8.23073 7H11.4807"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M18.4177 13.4H14.951C12.351 13.4 11.4844 12.6 11.4844 10.2V7L18.4177 13.4Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12.5703 2H16.9036" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.58594 5C7.58594 3.34 9.0376 2 10.8359 2H13.6743" stroke="white"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M23.8332 8V14.19C23.8332 15.74 22.4682 17 20.7891 17" stroke="white"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M23.8359 8H20.5859C18.1484 8 17.3359 7.25 17.3359 5V2L23.8359 8Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.support') }}
                            </span>
                            <svg :class="[openItems.support ? 'rotate-180' : '', (active === 'support' || active === 'system-support') ? 'text-white' : 'text-gray-500 dark:text-white  dark:hover:bg-gray-800']"
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
                                :class="active === 'system-support' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/support.svg') }}" class="w-4 h-4"
                                    :class="active === 'system-support' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.system_support') }}
                            </a>
                        </div>
                    </div>

                    <!-- تنظیمات -->
                    <div>
                        <button @click="openItems.settings = !openItems.settings; active = 'settings'"
                            :class="(active === 'settings' || active === 'system-settings') ? 'bg-[#122EE1] text-white' : 'text-gray-700 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/setting-2.svg') }}"
                                    class="w-5 h-5 dark:hidden"
                                    :class="(active === 'settings' || active === 'system-settings') ? 'filter invert brightness-0' : 'text-gray-500 dark:text-white'">
                                <svg width="26" height="24" class="hidden dark:block" viewBox="0 0 26 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13 15C14.7949 15 16.25 13.6569 16.25 12C16.25 10.3431 14.7949 9 13 9C11.2051 9 9.75 10.3431 9.75 12C9.75 13.6569 11.2051 15 13 15Z"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M2.16406 12.8799V11.1199C2.16406 10.0799 3.0849 9.21994 4.2224 9.21994C6.18323 9.21994 6.9849 7.93994 5.99906 6.36994C5.43573 5.46994 5.77156 4.29994 6.7574 3.77994L8.63156 2.78994C9.4874 2.31994 10.5924 2.59994 11.1016 3.38994L11.2207 3.57994C12.1957 5.14994 13.7991 5.14994 14.7849 3.57994L14.9041 3.38994C15.4132 2.59994 16.5182 2.31994 17.3741 2.78994L19.2482 3.77994C20.2341 4.29994 20.5699 5.46994 20.0066 6.36994C19.0207 7.93994 19.8224 9.21994 21.7832 9.21994C22.9099 9.21994 23.8416 10.0699 23.8416 11.1199V12.8799C23.8416 13.9199 22.9207 14.7799 21.7832 14.7799C19.8224 14.7799 19.0207 16.0599 20.0066 17.6299C20.5699 18.5399 20.2341 19.6999 19.2482 20.2199L17.3741 21.2099C16.5182 21.6799 15.4132 21.3999 14.9041 20.6099L14.7849 20.4199C13.8099 18.8499 12.2066 18.8499 11.2207 20.4199L11.1016 20.6099C10.5924 21.3999 9.4874 21.6799 8.63156 21.2099L6.7574 20.2199C5.77156 19.6999 5.43573 18.5299 5.99906 17.6299C6.9849 16.0599 6.18323 14.7799 4.2224 14.7799C3.0849 14.7799 2.16406 13.9199 2.16406 12.8799Z"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>


                                {{ __('messages.settings') }}
                            </span>
                            <svg :class="[openItems.settings ? 'rotate-180' : '', (active === 'settings' || active === 'system-settings') ? 'text-white' : 'text-gray-500 dark:text-white']"
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
                                :class="active === 'system-settings' ? 'bg-[#122EE1] text-white' : 'text-gray-600 dark:text-white hover:bg-gray-100  dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/settings.svg') }}" class="w-4 h-4"
                                    :class="active === 'system-settings' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.system_settings') }}
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- محتوای اصلی -->
            <main class="flex-1 mx-auto main-content-wrapper px-3  w-[500px] overflow-x-hidden">
                @yield('content')
            </main>


            <!-- Chat Widget -->
            <div id="chatWidget" class="fixed bottom-4 right-4 z-[9999] hidden">
                <!-- Chat Button -->
                <button id="chatToggle"
                    class="bg-[#122EE1] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 1C7.03 1 3 5.03 3 10V17C3 18.66 4.34 20 6 20H9V12H5V10C5 6.13 8.13 3 12 3C15.87 3 19 6.13 19 10V12H15V20H18C19.66 20 21 18.66 21 17V10C21 5.03 16.97 1 12 1Z" />
                    </svg>
                    <span id="unreadBadge"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center hidden shadow">0</span>
                </button>

                <!-- Chat Window -->
                <div id="chatWindow" class="
                            fixed sm:absolute
                            bottom-0 sm:bottom-20
                            right-0 sm:right-0
                            left-0 sm:left-auto
                            w-full sm:w-96
                            h-fit sm:h-fit
                            bg-white dark:bg-gray-800
                            rounded-none sm:rounded-lg
                            shadow-2xl
                            hidden
                            flex
                            flex-col
                            border border-gray-200 dark:border-gray-700
                            transform translate-y-full sm:translate-y-0
                            transition-transform duration-300 ease-in-out
                            ">

                    <!-- Chat Header -->
                    <div class="bg-[#122EE1] text-white p-4 rounded-t-lg flex justify-between items-center">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse rtl:space-x-3">
                            <h3 class="font-semibold text-lg">پیام‌رسانی</h3>
                            <button id="markAllReadBtn"
                                class="text-xs bg-white/20 hover:bg-white/30 px-2 py-1 rounded transition">
                                خواندن همه
                            </button>
                        </div>
                        <div class="flex items-center space-x-3 rtl:space-x-reverse rtl:space-x-3">
                            <button id="refreshChatBtn" class="text-white hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                            <button id="closeChatBtn" class="text-white hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Body -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Search Bar -->
                        <div class="p-3 border-b dark:border-gray-700">
                            <div class="relative">
                                <input type="text" id="chatSearchInput" placeholder="جستجوی کاربر..."
                                    class="w-full px-4 py-2 pr-10 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#122EE1]">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="flex border-b dark:border-gray-700 shrink-0">
                            <button id="conversationsTab"
                                class="flex-1 py-3 text-center font-medium border-b-2 border-[#122EE1] text-[#122EE1]">
                                مکالمات
                            </button>
                            <button id="usersTab"
                                class="flex-1 py-3 text-center font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                کاربران
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 overflow-hidden">
                            <!-- Conversations Panel -->
                            <div id="conversationsPanel" class="h-full overflow-y-auto">
                                <div id="conversationsList" class="p-3">
                                    <!-- Conversations will be loaded here -->
                                </div>
                                <div id="noConversations" class="hidden p-6 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <p>هیچ مکالمه‌ای وجود ندارد</p>
                                </div>
                            </div>

                            <!-- Users Panel -->
                            <div id="usersPanel" class="h-full overflow-y-auto hidden">
                                <div id="usersList" class="p-3">
                                    <!-- Users will be loaded here -->
                                </div>
                                <div id="noUsers" class="hidden p-6 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8A8.5 8.5 0 0012 3.5 8.5 8.5 0 003.5 12 8.5 8.5 0 0012 20.5a8.5 8.5 0 008.5-8.5z">
                                        </path>
                                    </svg>
                                    <p>کاربری برای چت پیدا نشد</p>
                                </div>
                            </div>

                            <!-- Messages Panel -->
                            <div id="messagesPanel" class="h-full flex flex-col hidden">
                                <!-- Messages Header -->
                                <div
                                    class="p-3 border-b dark:border-gray-700 flex items-center bg-gray-50 dark:bg-gray-900 shrink-0">
                                    <button id="backToChat" class="ml-3 text-[#122EE1] hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <div id="currentChatUser" class="flex items-center flex-1">
                                        <!-- User info will be loaded here -->
                                    </div>
                                </div>

                                <!-- Messages Container -->
                                <div id="messagesContainer"
                                    class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-4 overscroll-contain">
                                    <!-- Messages will be loaded here -->
                                </div>

                                <!-- Message Input -->
                                <div class="p-3 border-t dark:border-gray-700 shrink-0">
                                    <div class="flex space-x-2 rtl:space-x-reverse">
                                        <button id="sendMessageBtn"
                                            class=" bg-white border border-blue-400 text-white px-6 py-2 rounded-lg  transition flex items-center space-x-2 rtl:space-x-reverse">
                                            <img src="{{ asset('assets/sarafi/paper-plane.png') }}" class="h-5 w-5"
                                                alt="">
                                        </button>
                                        <input type="text" id="messageInput" placeholder="پیام خود را بنویسید..."
                                            class="flex-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#122EE1]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                /* Chat Styles - با قابلیت ریسپانسیو کامل */
                .chat-message {
                    max-width: 85%;
                    padding: 10px 14px;
                    border-radius: 18px;
                    margin-bottom: 8px;
                    word-wrap: break-word;
                    position: relative;
                    word-break: break-word;
                }

                .chat-message.sent {
                    background: linear-gradient(135deg, #122EE1, #4ECDC4);
                    color: white;
                    margin-right: auto;
                    margin-left: 0;
                    border-bottom-right-radius: 4px;
                }

                .chat-message.received {
                    background-color: #f1f1f1;
                    color: #333;
                    margin-left: auto;
                    margin-right: 0;
                    border-bottom-left-radius: 4px;
                }

                .dark .chat-message.received {
                    background-color: #374151;
                    color: #e5e7eb;
                }

                .chat-message .time {
                    font-size: 11px;
                    opacity: 0.8;
                    margin-top: 4px;
                    text-align: left;
                    display: block;
                }

                .chat-message.sent .time {
                    color: rgba(255, 255, 255, 0.9);
                }

                .chat-message.received .time {
                    color: #6b7280;
                }

                .conversation-item {
                    transition: all 0.2s ease;
                    cursor: pointer;
                    border-radius: 10px;
                    padding: 12px;
                    margin-bottom: 8px;
                    border: 1px solid transparent;
                }

                .conversation-item:hover {
                    background-color: #f9fafb;
                    border-color: #e5e7eb;
                }

                .dark .conversation-item:hover {
                    background-color: #374151;
                    border-color: #4b5563;
                }

                .conversation-item.active {
                    background-color: #eff6ff;
                    border-color: #3b82f6;
                }

                .dark .conversation-item.active {
                    background-color: #1e3a8a;
                    border-color: #3b82f6;
                }

                .unread-badge {
                    background-color: #ef4444;
                    color: white;
                    font-size: 12px;
                    min-width: 20px;
                    height: 20px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0 6px;
                }

                .user-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    color: white;
                    font-size: 16px;
                    flex-shrink: 0;
                }

                .avatar-blue {
                    background-color: #3b82f6;
                }

                .avatar-green {
                    background-color: #10b981;
                }

                .avatar-purple {
                    background-color: #8b5cf6;
                }

                .avatar-pink {
                    background-color: #ec4899;
                }

                .avatar-orange {
                    background-color: #f59e0b;
                }

                /* Scrollbar Styling */
                #messagesContainer::-webkit-scrollbar,
                #conversationsPanel::-webkit-scrollbar,
                #usersPanel::-webkit-scrollbar {
                    width: 6px;
                }

                #messagesContainer::-webkit-scrollbar-track,
                #conversationsPanel::-webkit-scrollbar-track,
                #usersPanel::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 3px;
                }

                .dark #messagesContainer::-webkit-scrollbar-track,
                .dark #conversationsPanel::-webkit-scrollbar-track,
                .dark #usersPanel::-webkit-scrollbar-track {
                    background: #374151;
                }

                #messagesContainer::-webkit-scrollbar-thumb,
                #conversationsPanel::-webkit-scrollbar-thumb,
                #usersPanel::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 3px;
                }

                .dark #messagesContainer::-webkit-scrollbar-thumb,
                .dark #conversationsPanel::-webkit-scrollbar-thumb,
                .dark #usersPanel::-webkit-scrollbar-thumb {
                    background: #6b7280;
                }

                /* Loading Animation */
                .chat-loading {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    flex-direction: column;
                }

                .chat-loading-spinner {
                    width: 40px;
                    height: 40px;
                    border: 3px solid #f3f3f3;
                    border-top: 3px solid #122EE1;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* Typing Indicator */
                .typing-indicator {
                    display: flex;
                    align-items: center;
                    padding: 10px;
                    background-color: #f1f1f1;
                    border-radius: 18px;
                    width: fit-content;
                    margin-bottom: 8px;
                }

                .dark .typing-indicator {
                    background-color: #374151;
                }

                .typing-indicator span {
                    height: 8px;
                    width: 8px;
                    border-radius: 50%;
                    background-color: #9ca3af;
                    margin: 0 2px;
                    animation: typing 1.4s infinite ease-in-out;
                }

                .typing-indicator span:nth-child(1) {
                    animation-delay: 0s;
                }

                .typing-indicator span:nth-child(2) {
                    animation-delay: 0.2s;
                }

                .typing-indicator span:nth-child(3) {
                    animation-delay: 0.4s;
                }

                @keyframes typing {

                    0%,
                    60%,
                    100% {
                        transform: translateY(0);
                    }

                    30% {
                        transform: translateY(-10px);
                    }
                }

                /* Mobile First - شروع از موبایل */
                #chatWidget {
                    position: fixed;
                    bottom: 16px;
                    right: 16px;
                    z-index: 9999;
                }

                #chatToggle {
                    width: 56px;
                    height: 56px;
                }

                /* Safe area برای دستگاه‌های ناچ دار */
                @supports (padding: max(0px)) {
                    #chatWidget {
                        bottom: max(16px, env(safe-area-inset-bottom));
                        right: max(16px, env(safe-area-inset-right));
                    }

                    #chatWindow {
                        padding-bottom: env(safe-area-inset-bottom);
                    }
                }

                /* بهبود تجربه موبایل */
                @media (max-width: 768px) {
                    #chatWindow {
                        border-radius: 16px 16px 0 0;
                        height: 85vh !important;
                    }

                    #chatWindow:not(.hidden) {
                        transform: translateY(0) !important;
                    }

                    /* جلوگیری از اسکرول body وقتی چت باز است */
                    body.chat-open {
                        overflow: hidden !important;
                        position: fixed;
                        width: 100%;
                        height: 100%;
                    }

                    /* بهبود سایز عناصر در موبایل */
                    .user-avatar {
                        width: 36px;
                        height: 36px;
                        font-size: 14px;
                    }

                    .conversation-item {
                        padding: 10px;
                    }

                    .chat-message {
                        max-width: 90%;
                        padding: 8px 12px;
                        font-size: 14px;
                    }

                    #messageInput,
                    #chatSearchInput {
                        font-size: 16px !important;
                        /* جلوگیری از زوم در iOS */
                        padding: 12px;
                    }

                    #sendMessageBtn {
                        padding: 12px 16px;
                        font-size: 14px;
                    }

                    /* بهبود ارتفاع در حالت لنداسکیپ */
                    @media (orientation: landscape) {
                        #chatWindow {
                            height: 90vh !important;
                        }
                    }
                }

                /* تبلت */
                @media (min-width: 768px) and (max-width: 1024px) {
                    #chatWindow {
                        width: 380px;
                        height: 500px;
                    }
                }

                /* دسکتاپ */
                @media (min-width: 1024px) {
                    #chatWindow {
                        width: 420px;
                        height: 550px;
                    }
                }

                /* انیمیشن‌ها */
                @keyframes slideUp {
                    from {
                        transform: translateY(100%);
                        opacity: 0;
                    }

                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                @keyframes slideDown {
                    from {
                        transform: translateY(0);
                        opacity: 1;
                    }

                    to {
                        transform: translateY(100%);
                        opacity: 0;
                    }
                }

                .animate-slide-up {
                    animation: slideUp 0.3s ease-out;
                }

                .animate-slide-down {
                    animation: slideDown 0.3s ease-out;
                }

                /* Toast Animation */
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }

                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }

                .animate-slide-in {
                    animation: slideInRight 0.3s ease-out;
                }

                /* Pulse Animation */
                @keyframes pulse {

                    0%,
                    100% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.05);
                    }
                }

                .animate-pulse {
                    animation: pulse 2s infinite;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // DOM Elements
                    const chatWidget = document.getElementById('chatWidget');
                    const chatToggle = document.getElementById('chatToggle');
                    const chatWindow = document.getElementById('chatWindow');
                    const closeChatBtn = document.getElementById('closeChatBtn');
                    const unreadBadge = document.getElementById('unreadBadge');
                    const refreshChatBtn = document.getElementById('refreshChatBtn');
                    const markAllReadBtn = document.getElementById('markAllReadBtn');
                    
                    // Panels
                    const conversationsPanel = document.getElementById('conversationsPanel');
                    const usersPanel = document.getElementById('usersPanel');
                    const messagesPanel = document.getElementById('messagesPanel');
                    
                    // Lists
                    const conversationsList = document.getElementById('conversationsList');
                    const usersList = document.getElementById('usersList');
                    const messagesContainer = document.getElementById('messagesContainer');
                    
                    // Inputs and buttons
                    const messageInput = document.getElementById('messageInput');
                    const sendMessageBtn = document.getElementById('sendMessageBtn');
                    const chatSearchInput = document.getElementById('chatSearchInput');
                    
                    // Tabs
                    const conversationsTab = document.getElementById('conversationsTab');
                    const usersTab = document.getElementById('usersTab');
                    
                    // Back buttons
                    const backToChat = document.getElementById('backToChat');
                    
                    // State variables
                    let currentChatUserId = null;
                    let currentChatUserName = null;
                    let pollingInterval = null;
                    let conversations = [];
                    let users = [];
                    let isChatOpen = false;
                    let currentTab = 'conversations';
                    let touchStartY = 0;
                    let isMobile = window.innerWidth <= 768;
                    let keyboardVisible = false;
                    let initialViewportHeight = window.innerHeight;

                    // Show chat widget
                    chatWidget.classList.remove('hidden');

                    // Update mobile detection on resize
                    window.addEventListener('resize', handleResize);

                    // Event Listeners
                    chatToggle.addEventListener('click', toggleChatWindow);
                    closeChatBtn.addEventListener('click', closeChatWindow);
                    refreshChatBtn.addEventListener('click', refreshChatData);
                    markAllReadBtn.addEventListener('click', markAllAsRead);
                    sendMessageBtn.addEventListener('click', sendMessage);
                    backToChat.addEventListener('click', showChatView);
                    
                    // Tab switching
                    conversationsTab.addEventListener('click', () => switchTab('conversations'));
                    usersTab.addEventListener('click', () => switchTab('users'));
                    
                    // Message input enter key
                    messageInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            sendMessage();
                        }
                    });
                    
                    // Search input
                    chatSearchInput.addEventListener('input', debounce(searchUsers, 300));

                    // Touch events for mobile
                    setupTouchEvents();

                    // Functions
                    function handleResize() {
                        isMobile = window.innerWidth <= 768;
                        
                        // Detect keyboard visibility on mobile
                        if (isMobile) {
                            const newHeight = window.innerHeight;
                            if (Math.abs(newHeight - initialViewportHeight) > 100) {
                                keyboardVisible = newHeight < initialViewportHeight;
                                if (keyboardVisible && isChatOpen && messagesPanel.classList.contains('hidden') === false) {
                                    // Keyboard opened, scroll to bottom
                                    setTimeout(scrollToBottom, 300);
                                }
                            }
                            initialViewportHeight = newHeight;
                        }
                    }

                    function setupTouchEvents() {
                        // Swipe to close on mobile
                        chatWindow.addEventListener('touchstart', handleTouchStart, { passive: true });
                        chatWindow.addEventListener('touchmove', handleTouchMove, { passive: true });
                        chatWindow.addEventListener('touchend', handleTouchEnd, { passive: true });

                        // Close when tapping outside on mobile
                        if (isMobile) {
                            document.addEventListener('touchstart', handleOutsideTap, { passive: true });
                        }
                    }

                    function handleTouchStart(e) {
                        if (!isMobile || !isChatOpen) return;
                        
                        const header = chatWindow.querySelector('.bg-\\[\\#122EE1\\]');
                        if (header && header.contains(e.target)) {
                            touchStartY = e.touches[0].clientY;
                        }
                    }

                    function handleTouchMove(e) {
                        if (!isMobile || !isChatOpen || touchStartY === 0) return;
                        
                        const currentY = e.touches[0].clientY;
                        const diff = currentY - touchStartY;
                        
                        // فقط اگر به پایین کشیده شود
                        if (diff > 0) {
                            e.preventDefault();
                            chatWindow.style.transform = `translateY(${diff}px)`;
                        }
                    }

                    function handleTouchEnd(e) {
                        if (!isMobile || !isChatOpen || touchStartY === 0) return;
                        
                        const touchEndY = e.changedTouches[0].clientY;
                        const diff = touchEndY - touchStartY;
                        
                        if (diff > 100) { // کشیدن بیش از 100 پیکسل
                            closeChatWindow();
                        } else {
                            // بازگشت به حالت عادی
                            chatWindow.style.transform = 'translateY(0)';
                        }
                        
                        touchStartY = 0;
                        chatWindow.style.transform = '';
                    }

                    function handleOutsideTap(e) {
                        if (!isMobile || !isChatOpen) return;
                        
                        if (!chatWindow.contains(e.target) && !chatToggle.contains(e.target)) {
                            closeChatWindow();
                        }
                    }

                    function toggleChatWindow() {
                        isChatOpen = !isChatOpen;
                        
                        if (isMobile) {
                            if (isChatOpen) {
                                // باز کردن چت در موبایل
                                chatWindow.classList.remove('hidden');
                                setTimeout(() => {
                                    chatWindow.style.transform = 'translateY(0)';
                                }, 10);
                                
                                // جلوگیری از اسکرول body
                                document.body.classList.add('chat-open');
                                document.body.style.overflow = 'hidden';
                            } else {
                                // بستن چت در موبایل
                                chatWindow.style.transform = 'translateY(100%)';
                                setTimeout(() => {
                                    chatWindow.classList.add('hidden');
                                    chatWindow.style.transform = '';
                                    
                                    // فعال کردن اسکرول body
                                    document.body.classList.remove('chat-open');
                                    document.body.style.overflow = '';
                                }, 300);
                            }
                        } else {
                            // رفتار عادی برای دسکتاپ
                            chatWindow.classList.toggle('hidden');
                        }
                        
                        if (isChatOpen) {
                            loadConversations();
                            updateUnreadCount();
                            startPolling();
                            switchTab('conversations');
                        } else {
                            stopPolling();
                        }
                    }

                    function closeChatWindow() {
                        if (isMobile) {
                            chatWindow.style.transform = 'translateY(100%)';
                            setTimeout(() => {
                                chatWindow.classList.add('hidden');
                                chatWindow.style.transform = '';
                                isChatOpen = false;
                                
                                // فعال کردن اسکرول body
                                document.body.classList.remove('chat-open');
                                document.body.style.overflow = '';
                            }, 300);
                        } else {
                            isChatOpen = false;
                            chatWindow.classList.add('hidden');
                        }
                        stopPolling();
                    }

                    function switchTab(tabName) {
                        currentTab = tabName;
                        
                        // Update tab styles
                        conversationsTab.classList.remove('border-[#122EE1]', 'text-[#122EE1]');
                        conversationsTab.classList.add('text-gray-500', 'hover:text-gray-700');
                        usersTab.classList.remove('border-[#122EE1]', 'text-[#122EE1]');
                        usersTab.classList.add('text-gray-500', 'hover:text-gray-700');
                        
                        if (tabName === 'conversations') {
                            conversationsTab.classList.add('border-[#122EE1]', 'text-[#122EE1]');
                            conversationsPanel.classList.remove('hidden');
                            usersPanel.classList.add('hidden');
                            loadConversations();
                        } else {
                            usersTab.classList.add('border-[#122EE1]', 'text-[#122EE1]');
                            conversationsPanel.classList.add('hidden');
                            usersPanel.classList.remove('hidden');
                            loadChatUsers();
                        }
                        
                        messagesPanel.classList.add('hidden');
                    }

                    function showChatView() {
                        conversationsPanel.classList.remove('hidden');
                        usersPanel.classList.remove('hidden');
                        messagesPanel.classList.add('hidden');
                        switchTab(currentTab);
                    }

                    // API Functions
                    async function loadConversations() {
                        try {
                            showLoading(conversationsList, 'در حال بارگذاری مکالمات...');
                            
                            const response = await fetch('/chat/conversations', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                conversations = data.conversations;
                                renderConversations(conversations);
                                
                                if (conversations.length === 0) {
                                    document.getElementById('noConversations').classList.remove('hidden');
                                } else {
                                    document.getElementById('noConversations').classList.add('hidden');
                                }
                            }
                        } catch (error) {
                            console.error('Error loading conversations:', error);
                            showError(conversationsList, 'خطا در بارگذاری مکالمات');
                        }
                    }

                    async function loadChatUsers() {
                        try {
                            showLoading(usersList, 'در حال بارگذاری کاربران...');
                            
                            const response = await fetch('/chat/users', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                users = data.users;
                                renderUsers(users);
                                
                                if (users.length === 0) {
                                    document.getElementById('noUsers').classList.remove('hidden');
                                } else {
                                    document.getElementById('noUsers').classList.add('hidden');
                                }
                            }
                        } catch (error) {
                            console.error('Error loading users:', error);
                            showError(usersList, 'خطا در بارگذاری کاربران');
                        }
                    }

                    async function openChat(userId, userName) {
                        currentChatUserId = userId;
                        currentChatUserName = userName;
                        
                        // Show messages panel
                        conversationsPanel.classList.add('hidden');
                        usersPanel.classList.add('hidden');
                        messagesPanel.classList.remove('hidden');
                        
                        // Update header
                        const avatarColors = ['avatar-blue', 'avatar-green', 'avatar-purple', 'avatar-pink', 'avatar-orange'];
                        const colorIndex = userName.length % avatarColors.length;
                        
                        document.getElementById('currentChatUser').innerHTML = `
                            <div class="user-avatar ${avatarColors[colorIndex]}">
                                ${userName.charAt(0)}
                            </div>
                            <div class="mr-3">
                                <h4 class="font-semibold">${userName}</h4>
                                <small class="text-gray-500 dark:text-gray-400 text-sm">آنلاین</small>
                            </div>
                        `;
                        
                        // Load messages
                        await loadMessages();
                        
                        // Focus on input بعد از بارگذاری پیام‌ها
                        setTimeout(() => {
                            messageInput.focus();
                            
                            // در موبایل، اسکرول به پایین
                            if (isMobile) {
                                setTimeout(scrollToBottom, 100);
                            }
                        }, 200);
                    }

                    async function loadMessages() {
                        if (!currentChatUserId) return;
                        
                        try {
                            showLoading(messagesContainer, 'در حال بارگذاری پیام‌ها...');
                            
                            const response = await fetch(`/chat/messages/${currentChatUserId}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                renderMessages(data.messages);
                                updateUnreadCount();
                                
                                // اسکرول به پایین بعد از بارگذاری
                                setTimeout(scrollToBottom, 100);
                            }
                        } catch (error) {
                            console.error('Error loading messages:', error);
                            showError(messagesContainer, 'خطا در بارگذاری پیام‌ها');
                        }
                    }

                    async function sendMessage() {
                        const message = messageInput.value.trim();
                        if (!message || !currentChatUserId) return;
                        
                        // Save current scroll position
                        const scrollPosBefore = messagesContainer.scrollTop;
                        const isAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop === messagesContainer.clientHeight;
                        
                        // Add message to UI immediately
                        const tempMessage = {
                            id: Date.now(),
                            sender_id: {{ Auth::guard('sarafi')->id() }},
                            message: message,
                            created_at: new Date().toISOString(),
                            sender: {
                                name: '{{ Auth::guard("sarafi")->user()->name }}',
                                lastname: '{{ Auth::guard("sarafi")->user()->lastname }}'
                            }
                        };
                        
                        renderMessage(tempMessage, true);
                        messageInput.value = '';
                        
                        // اگر کاربر در پایین بود یا در موبایل هستیم، اسکرول به پایین
                        if (isAtBottom || isMobile) {
                            setTimeout(scrollToBottom, 50);
                        } else {
                            // در غیر این صورت، موقعیت اسکرول را حفظ کن
                            messagesContainer.scrollTop = scrollPosBefore;
                        }
                        
                        // Send to server
                        try {
                            const response = await fetch('/chat/send', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    receiver_id: currentChatUserId,
                                    message: message
                                })
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                // Remove temp message and add real one
                                const lastMsg = messagesContainer.lastChild;
                                if (lastMsg && lastMsg.dataset.messageId == tempMessage.id) {
                                    lastMsg.remove();
                                }
                                renderMessage(data.message, true);
                                loadConversations(); // Refresh conversations list
                                
                                // اگر کاربر در پایین بود یا در موبایل هستیم، اسکرول به پایین
                                if (isAtBottom || isMobile) {
                                    setTimeout(scrollToBottom, 50);
                                }
                            } else {
                                showToast(data.error || 'خطا در ارسال پیام', 'error');
                            }
                        } catch (error) {
                            console.error('Error sending message:', error);
                            showToast('خطا در ارسال پیام', 'error');
                        }
                    }

                    async function searchUsers() {
                        const query = chatSearchInput.value.trim();
                        if (query.length < 2) {
                            if (currentTab === 'conversations') {
                                renderConversations(conversations);
                            } else {
                                renderUsers(users);
                            }
                            return;
                        }
                        
                        try {
                            const response = await fetch('/chat/search', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ query: query })
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                if (currentTab === 'conversations') {
                                    renderUsers(data.users);
                                } else {
                                    renderUsers(data.users);
                                }
                            }
                        } catch (error) {
                            console.error('Error searching users:', error);
                        }
                    }

                    async function updateUnreadCount() {
                        try {
                            const response = await fetch('/chat/unread-count', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                if (data.count > 0) {
                                    unreadBadge.textContent = data.count > 99 ? '99+' : data.count;
                                    unreadBadge.classList.remove('hidden');
                                    chatToggle.classList.add('animate-pulse');
                                } else {
                                    unreadBadge.classList.add('hidden');
                                    chatToggle.classList.remove('animate-pulse');
                                }
                            }
                        } catch (error) {
                            console.error('Error updating unread count:', error);
                        }
                    }

                    async function markAllAsRead() {
                        try {
                            const response = await fetch('/chat/mark-all-read', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                updateUnreadCount();
                                loadConversations();
                                showToast('همه پیام‌ها خوانده شدند', 'success');
                            }
                        } catch (error) {
                            console.error('Error marking all as read:', error);
                            showToast('خطا در خواندن پیام‌ها', 'error');
                        }
                    }

                    function refreshChatData() {
                        if (currentTab === 'conversations') {
                            loadConversations();
                        } else {
                            loadChatUsers();
                        }
                        
                        if (currentChatUserId) {
                            loadMessages();
                        }
                        
                        updateUnreadCount();
                        showToast('اطلاعات بروزرسانی شد', 'success');
                    }

                    // Rendering Functions
                    function renderConversations(conversations) {
                        conversationsList.innerHTML = '';
                        
                        if (!conversations || conversations.length === 0) {
                            document.getElementById('noConversations').classList.remove('hidden');
                            return;
                        }
                        
                        conversations.forEach(conv => {
                            const conversationItem = document.createElement('div');
                            conversationItem.className = 'conversation-item';
                            
                            const avatarColors = ['avatar-blue', 'avatar-green', 'avatar-purple', 'avatar-pink', 'avatar-orange'];
                            const colorIndex = conv.other_user.name.length % avatarColors.length;
                            const displayName = `${conv.other_user.name} ${conv.other_user.lastname}`;
                            
                            conversationItem.innerHTML = `
                                <div class="flex items-center">
                                    <div class="user-avatar ${avatarColors[colorIndex]}">
                                        ${conv.other_user.name.charAt(0)}
                                    </div>
                                    <div class="mr-3 flex-1 min-w-0">
                                        <div class="flex justify-between items-center">
                                            <h4 class="font-semibold truncate">${displayName}</h4>
                                            ${conv.unread_count > 0 ? `<span class="unread-badge flex-shrink-0">${conv.unread_count}</span>` : ''}
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">${conv.last_message || ''}</p>
                                        <small class="text-xs text-gray-500">${conv.last_message_at ? formatDate(conv.last_message_at) : ''}</small>
                                    </div>
                                </div>
                            `;
                            
                            conversationItem.addEventListener('click', () => openChat(conv.other_user.id, displayName));
                            conversationsList.appendChild(conversationItem);
                        });
                        
                        document.getElementById('noConversations').classList.add('hidden');
                    }

                    function renderUsers(users) {
                        usersList.innerHTML = '';
                        
                        if (!users || users.length === 0) {
                            document.getElementById('noUsers').classList.remove('hidden');
                            return;
                        }
                        
                        users.forEach(user => {
                            const userItem = document.createElement('div');
                            userItem.className = 'conversation-item';
                            
                            const avatarColors = ['avatar-blue', 'avatar-green', 'avatar-purple', 'avatar-pink', 'avatar-orange'];
                            const colorIndex = user.name.length % avatarColors.length;
                            const displayName = `${user.name} ${user.lastname}`;
                            const roleText = user.role === 'superadmin' ? 'سوپر ادمین' : 
                                            user.role === 'admin' ? 'ادمین' : 'انباردار';
                            
                            userItem.innerHTML = `
                                <div class="flex items-center">
                                    <div class="user-avatar ${avatarColors[colorIndex]}">
                                        ${user.name.charAt(0)}
                                    </div>
                                    <div class="mr-3 flex-1 min-w-0">
                                        <h4 class="font-semibold truncate">${displayName}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">${roleText} - ${user.sarafi_name}</p>
                                    </div>
                                    <button class="text-[#122EE1] hover:text-blue-700 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </button>
                                </div>
                            `;
                            
                            userItem.addEventListener('click', () => openChat(user.id, displayName));
                            usersList.appendChild(userItem);
                        });
                        
                        document.getElementById('noUsers').classList.add('hidden');
                    }

                    function renderMessages(messages) {
                        messagesContainer.innerHTML = '';
                        
                        const currentUserId = {{ Auth::guard('sarafi')->id() }};
                        
                        if (!messages || messages.length === 0) {
                            messagesContainer.innerHTML = `
                                <div class="text-center text-gray-500 py-8">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                    <p>هیچ پیامی وجود ندارد</p>
                                    <p class="text-sm mt-2">پیام خود را ارسال کنید</p>
                                </div>
                            `;
                            return;
                        }
                        
                        messages.forEach(msg => {
                            renderMessage(msg, msg.sender_id === currentUserId);
                        });
                    }

                    function renderMessage(msg, isSent) {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `flex ${isSent ? 'justify-end' : 'justify-start'}`;
                        messageDiv.dataset.messageId = msg.id;
                        
                        const time = new Date(msg.created_at).toLocaleTimeString('fa-IR', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        
                        messageDiv.innerHTML = `
                            <div class="chat-message ${isSent ? 'sent' : 'received'}">
                                <p>${msg.message}</p>
                                <span class="time">${time}</span>
                            </div>
                        `;
                        
                        messagesContainer.appendChild(messageDiv);
                    }

                    // Utility Functions
                    function showLoading(container, text) {
                        container.innerHTML = `
                            <div class="chat-loading">
                                <div class="chat-loading-spinner"></div>
                                <p class="mt-3 text-gray-500 text-center px-4">${text}</p>
                            </div>
                        `;
                    }

                    function showError(container, text) {
                        container.innerHTML = `
                            <div class="text-center text-red-500 py-8 px-4">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p>${text}</p>
                                <button onclick="refreshChatData()" class="mt-4 text-[#122EE1] hover:text-blue-700 text-sm">
                                    تلاش مجدد
                                </button>
                            </div>
                        `;
                    }

                    function showToast(message, type = 'info') {
                        // Create toast if it doesn't exist
                        let toastContainer = document.getElementById('chatToastContainer');
                        if (!toastContainer) {
                            toastContainer = document.createElement('div');
                            toastContainer.id = 'chatToastContainer';
                            toastContainer.className = 'fixed top-4 right-4 z-[99999]';
                            document.body.appendChild(toastContainer);
                        }
                        
                        const toastId = 'toast-' + Date.now();
                        const bgColor = type === 'success' ? 'bg-green-500' : 
                                    type === 'error' ? 'bg-red-500' : 'bg-blue-500';
                        
                        // موقعیت متفاوت برای موبایل
                        if (isMobile) {
                            toastContainer.className = 'fixed top-4 right-4 left-4 z-[99999]';
                        }
                        
                        const toast = document.createElement('div');
                        toast.id = toastId;
                        toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between ${isMobile ? 'w-full' : 'min-w-[300px]'} animate-slide-in`;
                        
                        toast.innerHTML = `
                            <span class="flex-1">${message}</span>
                            <button onclick="document.getElementById('${toastId}').remove()" class="text-white hover:text-gray-200 mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        `;
                        
                        toastContainer.appendChild(toast);
                        
                        // Auto remove after 3 seconds
                        setTimeout(() => {
                            if (document.getElementById(toastId)) {
                                toast.remove();
                            }
                        }, 3000);
                    }

                    function formatDate(dateString) {
                        const date = new Date(dateString);
                        const now = new Date();
                        const diffMs = now - date;
                        const diffMins = Math.floor(diffMs / 60000);
                        const diffHours = Math.floor(diffMs / 3600000);
                        const diffDays = Math.floor(diffMs / 86400000);
                        
                        if (diffMins < 1) return 'همین الآن';
                        if (diffMins < 60) return `${diffMins} دقیقه پیش`;
                        if (diffHours < 24) return `${diffHours} ساعت پیش`;
                        if (diffDays < 7) return `${diffDays} روز پیش`;
                        
                        return date.toLocaleDateString('fa-IR');
                    }

                    function scrollToBottom() {
                        setTimeout(() => {
                            messagesContainer.scrollTo({
                                top: messagesContainer.scrollHeight,
                                behavior: 'smooth'
                            });
                        }, 100);
                    }

                    function debounce(func, wait) {
                        let timeout;
                        return function executedFunction(...args) {
                            const later = () => {
                                clearTimeout(timeout);
                                func(...args);
                            };
                            clearTimeout(timeout);
                            timeout = setTimeout(later, wait);
                        };
                    }

                    // Polling for new messages
                    function startPolling() {
                        stopPolling();
                        pollingInterval = setInterval(() => {
                            updateUnreadCount();
                            
                            if (currentChatUserId) {
                                loadMessages();
                            } else if (isChatOpen) {
                                loadConversations();
                            }
                        }, 10000); // Poll every 10 seconds
                    }

                    function stopPolling() {
                        if (pollingInterval) {
                            clearInterval(pollingInterval);
                            pollingInterval = null;
                        }
                    }

                    // Initial setup
                    updateUnreadCount();
                    
                    // Auto open chat if there are unread messages
                    setTimeout(() => {
                        if (parseInt(unreadBadge.textContent || 0) > 0) {
                            chatToggle.classList.add('animate-pulse');
                        }
                    }, 1000);

                    // Handle virtual keyboard on mobile
                    if (isMobile) {
                        // Listen for focus on message input
                        messageInput.addEventListener('focus', function() {
                            keyboardVisible = true;
                            // Scroll to bottom when keyboard opens
                            setTimeout(scrollToBottom, 300);
                        });

                        messageInput.addEventListener('blur', function() {
                            keyboardVisible = false;
                        });
                    }
                });
            </script>
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
                    window.location.href = "{{ route('sarafi.users') }}";
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

</html>