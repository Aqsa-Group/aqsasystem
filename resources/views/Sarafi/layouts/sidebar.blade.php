<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم صرافی اقصی</title>
    
    <!-- Tailwind CSS CDN با پیکربندی Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#122EE1',
                        secondary: '#FF6B6B',
                    },
                    fontFamily: {
                        'vazir': ['Vazir', 'sans-serif'],
                        'yekan': ['Yekan', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    @include('Sarafi.layouts.links')
    
    <!-- استایل‌های سفارسی -->
    <style>
        /* لودر تمام صفحه */
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
            0%, 100% {
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

        /* محتوای اصلی */
        #mainContent {
            display: none;
            opacity: 1;
        }

        .content-loaded {
            display: block;
            opacity: 1;
        }

        /* استایل‌های ریسپانسیو */
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
            class="bg-white w-full py-4 md:py-0 md:h-[80px] flex items-center shadow-[0_4px_4px_rgba(17,41,199,0.4)]">
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
                        <div class="responsive-text text-[#122EE1] font-bold yekan">{{
                            Auth::guard('sarafi')->user()->sarafi_name }}</div>

                        @php $locale = session('locale', config('app.locale')); @endphp
                        <div class="relative inline-block w-[145px] h-[56px] p-2 vazir">
                            <button id="dropdownButton"
                                class="border border-[#1129C766] bg-white rounded-lg px-3 py-2 w-full flex items-center justify-between font-vazir text-sm text-[#1129C7]">
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
                                class="absolute left-0 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                                <li><a href="{{ route('set-locale', 'fa') }}"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                            src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2"
                                            alt="fa">
                                        فارسی</a></li>
                                <li><a href="{{ route('set-locale', 'ps') }}"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                            src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2"
                                            alt="ps">
                                        پشتو</a></li>
                                <li><a href="{{ route('set-locale', 'en') }}"
                                        class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
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
                                    class="border border-[#8C8C8C] placeholder:text-black vazir rounded-[12px] px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">

                                <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
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
                            <img src="{{ asset('assets/sarafi/all_icon/bill-header.svg') }}" alt="اعلان"
                                class="w-7 h-7">
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                        </button>

                        <div class="header-profile-section">
                            <div class="relative">
                                <div id="profileBtnDesktop"
                                    class="w-[50px] h-[50px] md:w-[60px] md:h-[60px] rounded-full border overflow-hidden flex items-center justify-center cursor-pointer transition">
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
                    <a href="{{ route('sarafi.home') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                        @click="active = 'dashboard'"
                        :class="active === 'dashboard' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/element-3.svg') }}" class="w-5 h-5"
                                :class="active === 'dashboard' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            {{ __('messages.dashboard') }}
                        </span>
                    </a>

                    <!-- کاربران -->
                    <a href="{{ route('sarafi.users') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir dark:text-white"
                        @click="active = 'users'"
                        :class="active === 'users' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="{{ asset('assets/sarafi/all_icon/profile-2user.svg') }}" class="w-5 h-5"
                                :class="active === 'users' ? 'filter invert brightness-0' : 'text-gray-500' ">
                            <span class="dark:text-white"> {{ __('messages.users') }}</span>
                        </span>
                    </a>

                    <!-- مشتریان -->
                    <div>
                        <button @click="openItems.customers = !openItems.customers; active = 'customers'"
                            :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/people.svg') }}" class="w-5 h-5"
                                    :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.customers') }}
                            </span>
                            <svg :class="[openItems.customers ? 'rotate-180' : '', (active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'text-white' : 'text-gray-500']"
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
                                :class="active === 'customer-create' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <i class="fa-solid fa-user-pen w-4 h-4"
                                    :class="active === 'customer-create' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_create') }}
                            </a>

                            <a href="{{ route('sarafi.customer-table') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-table', 'customers')"
                                :class="active === 'customer-table' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <i class="fa-solid fa-users-gear h-4 w-4"
                                    :class="active === 'customer-table' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_list') }}
                            </a>
                        </div>
                    </div>

                    <!-- ثبت حسابات و نرخ ارز -->
                    <div>
                        <button @click="openItems.accounts = !openItems.accounts; active = 'accounts'"
                            :class="(active === 'accounts' || active === 'register-accounts') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2 ">
                                <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" class="w-5 h-5"
                                    :class="(active === 'accounts' || active === 'register-accounts') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.accounts') }}
                            </span>
                            <svg :class="[openItems.accounts ? 'rotate-180' : '', (active === 'accounts' || active === 'register-accounts') ? 'text-white' : 'text-gray-500']"
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
                                :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
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

                    <!-- بارگذاری فایل بانکی -->
                    <div>
                        <button @click="openItems.bankFiles = !openItems.bankFiles; active = 'bankFiles'"
                            :class="(active === 'bankFiles' || active === 'upload-bank') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/receive-square.svg') }}" class="w-5 h-5"
                                    :class="(active === 'bankFiles' || active === 'upload-bank') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.bank_files') }}
                            </span>
                            <svg :class="[openItems.bankFiles ? 'rotate-180' : '', (active === 'bankFiles' || active === 'upload-bank') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.bankFiles" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.remittance') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('upload-bank', 'bankFiles')"
                                :class="active === 'upload-bank' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.54003 11.12C0.860029 11.45 0.860029 18.26 5.54003 18.59H7.46007"
                                        stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5.59003 11.12C2.38003 2.19002 15.92 -1.37998 17.47 8.00002C21.8 8.55002 23.55 14.32 20.27 17.19C19.27 18.1 17.98 18.6 16.63 18.59H16.54"
                                        stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M17 16.53C17 17.27 16.84 17.97 16.54 18.59C16.46 18.77 16.37 18.94 16.27 19.1C15.41 20.55 13.82 21.53 12 21.53C10.18 21.53 8.58998 20.55 7.72998 19.1C7.62998 18.94 7.54002 18.77 7.46002 18.59C7.16002 17.97 7 17.27 7 16.53C7 13.77 9.24 11.53 12 11.53C14.76 11.53 17 13.77 17 16.53Z"
                                        stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.4399 16.53L11.4299 17.5201L13.5599 15.55" stroke="#292D32"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

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
                                <img src="{{ asset('assets/sarafi/all_icon/health.svg') }}" class="w-5 h-5"
                                    :class="(active === 'transactions' || active === 'control-transactions') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.transactions') }}
                            </span>
                            <svg :class="[openItems.transactions ? 'rotate-180' : '', (active === 'transactions' || active === 'control-transactions') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.transactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.remittance-approval') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('control-transactions', 'transactions')"
                                :class="active === 'control-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
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
                            :class="(active === 'deletedTransactions' || active === 'deleted-transactions') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/trash.svg') }}" class="w-5 h-5"
                                    :class="(active === 'deletedTransactions' || active === 'deleted-transactions') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.deleted_transactions') }}
                            </span>
                            <svg :class="[openItems.deletedTransactions ? 'rotate-180' : '', (active === 'deletedTransactions' || active === 'deleted-transactions') ? 'text-white' : 'text-gray-500']"
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
                                :class="active === 'deleted-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
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
                            :class="(active === 'reports' || active === 'view-reports') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/graph.svg') }}" class="w-5 h-5"
                                    :class="(active === 'reports' || active === 'view-reports') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.reports') }}
                            </span>
                            <svg :class="[openItems.reports ? 'rotate-180' : '', (active === 'reports' || active === 'view-reports') ? 'text-white' : 'text-gray-500']"
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
                                :class="active === 'view-reports' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">

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

                            {{-- عواید معاملات --}}
                            <a href="{{ route('sarafi.revenue') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-revenue', 'reports')"
                                :class="active === 'view-revenue' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">

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
                            :class="(active === 'changersdeal' || active === 'edit-changersdeal') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" class="w-5 h-5"
                                    :class="(active === 'changersdeal' || active === 'edit-accounts') ? 'filter invert brightness-0' : 'text-gray-500'">
                                ارسال و دریافت از صرافان
                            </span>
                            <svg :class="[openItems.changersdeal ? 'rotate-180' : '', (active === 'changersdeal' || active === 'edit-accounts') ? 'text-white' : 'text-gray-500']"
                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.changersdeal') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
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
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
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
                                :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
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

                    <!-- ویرایش حسابات و نرخ ارز -->
                    <div>
                        <button @click="openItems.editAccounts = !openItems.editAccounts; active = 'editAccounts'"
                            :class="(active === 'editAccounts' || active === 'edit-accounts') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" class="w-5 h-5"
                                    :class="(active === 'editAccounts' || active === 'edit-accounts') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.edit_accounts') }}
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
                                <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" class="w-4 h-4"
                                    :class="active === 'edit-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.edit_accounts_info') }}
                            </a>
                        </div>
                    </div>





                    <!-- مدیریت و دسترسی -->
                    <div>
                        <button @click="openItems.management = !openItems.management; active = 'management'"
                            :class="(active === 'management' || active === 'user-management') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/Group 1325.svg') }}" class="w-5 h-5"
                                    :class="(active === 'management' || active === 'user-management') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.management') }}
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
                                <img src="{{ asset('assets/sarafi/all_icon/user.svg') }}" class="w-4 h-4"
                                    :class="active === 'user-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.user_management') }}
                            </a>
                        </div>
                    </div>

                    <!-- مدیریت پیامک ها -->
                    <div>
                        <button @click="openItems.sms = !openItems.sms; active = 'sms'"
                            :class="(active === 'sms' || active === 'sms-management') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/sms.svg') }}" class="w-5 h-5"
                                    :class="(active === 'sms' || active === 'sms-management') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.sms') }}
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
                                <img src="{{ asset('assets/sarafi/all_icon/message.svg') }}" class="w-4 h-4"
                                    :class="active === 'sms-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.sms_management') }}
                            </a>
                        </div>
                    </div>

                    <!-- اطلاعیه های آنلاین -->
                    <div>
                        <button @click="openItems.notifications = !openItems.notifications; active = 'notifications'"
                            :class="(active === 'notifications' || active === 'online-notifications') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/wifi.svg') }}" class="w-5 h-5"
                                    :class="(active === 'notifications' || active === 'online-notifications') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.notifications') }}
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
                                <img src="{{ asset('assets/sarafi/all_icon/notification.svg') }}" class="w-4 h-4"
                                    :class="active === 'online-notifications' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.online_notifications') }}
                            </a>
                        </div>
                    </div>

                    <!-- پشتیبانی سیستم -->
                    <div>
                        <button @click="openItems.support = !openItems.support; active = 'support'"
                            :class="(active === 'support' || active === 'system-support') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/document-copy.svg') }}" class="w-5 h-5"
                                    :class="(active === 'support' || active === 'system-support') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.support') }}
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
                                <img src="{{ asset('assets/sarafi/all_icon/support.svg') }}" class="w-4 h-4"
                                    :class="active === 'system-support' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.system_support') }}
                            </a>
                        </div>
                    </div>

                    <!-- تنظیمات -->
                    <div>
                        <button @click="openItems.settings = !openItems.settings; active = 'settings'"
                            :class="(active === 'settings' || active === 'system-settings') ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <img src="{{ asset('assets/sarafi/all_icon/setting-2.svg') }}" class="w-5 h-5"
                                    :class="(active === 'settings' || active === 'system-settings') ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.settings') }}
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

    // مدیریت دارک مود - به روز شده
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

    // تابع اصلی برای اعمال دارک مود
    function applyDarkMode(isDark) {
        if (isDark) {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            
            // به‌روزرسانی آیکون‌ها
            if (sunIcon) sunIcon.classList.add('hidden');
            if (sunIconMobile) sunIconMobile.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            if (moonIconMobile) moonIconMobile.classList.remove('hidden');
            
            // به‌روزرسانی دکمه toggle
            if (toggleCircle) toggleCircle.classList.add('move-dark');
            if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
            
            // به‌روزرسانی وضعیت checkbox
            if (darkModeToggle) darkModeToggle.checked = true;
            if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
            
            // اعمال استایل‌های اضافی برای اطمینان
            document.body.classList.add('dark-mode-active');
            
            // اعمال مجدد استایل‌ها برای اطمینان
            setTimeout(() => {
                document.querySelectorAll('*').forEach(el => {
                    if (el.classList.contains('text-gray-700') || 
                        el.classList.contains('text-gray-800') || 
                        el.classList.contains('text-gray-900')) {
                        el.style.color = '#e2e8f0 !important';
                    }
                });
            }, 100);
        } else {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            
            // به‌روزرسانی آیکون‌ها
            if (sunIcon) sunIcon.classList.remove('hidden');
            if (sunIconMobile) sunIconMobile.classList.remove('hidden');
            if (moonIcon) moonIcon.classList.add('hidden');
            if (moonIconMobile) moonIconMobile.classList.add('hidden');
            
            // به‌روزرسانی دکمه toggle
            if (toggleCircle) toggleCircle.classList.remove('move-dark');
            if (toggleCircleMobile) toggleCircleMobile.classList.remove('move-dark');
            
            // به‌روزرسانی وضعیت checkbox
            if (darkModeToggle) darkModeToggle.checked = false;
            if (darkModeToggleMobile) darkModeToggleMobile.checked = false;
            
            // حذف استایل‌های اضافی
            document.body.classList.remove('dark-mode-active');
        }
    }

    // بارگذاری اولیه تم
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
        applyDarkMode(true);
    } else {
        applyDarkMode(false);
    }

    // رویداد تغییر برای toggle دسکتاپ
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            applyDarkMode(this.checked);
        });
    }

    // رویداد تغییر برای toggle موبایل
    if (darkModeToggleMobile) {
        darkModeToggleMobile.addEventListener('change', function() {
            applyDarkMode(this.checked);
        });
    }

    // نظارت بر تغییرات سیستم
    prefersDarkScheme.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            applyDarkMode(e.matches);
        }
    });
</script>

</body>

</html>