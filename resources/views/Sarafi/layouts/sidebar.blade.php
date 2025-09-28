<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RTL</title>
    @include('Sarafi.layouts.links')

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

        
    </style>
</head>

<body class="vazir">

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
                    <span>ز</span>
                </div>
            </div>

            <div class="loader-text">صرافی زرین</div>
            <div class="loader-subtext">در حال بارگذاری...</div>

            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div id="mainContent">
        <header
            class="bg-white w-full h-[80px] flex items-center justify-between px-6 shadow-[0_4px_4px_rgba(17,41,199,0.4)]">
            <!-- برند + انتخاب زبان -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse gap-6 justify-center ">
                <div class="text-[40px] text-[#122EE1] font-bold yekan">صرافی زرین</div>

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
                                    src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2" alt="fa">
                                فارسی</a></li>
                        <li><a href="{{ route('set-locale', 'ps') }}"
                                class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                    src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-5 h-5 ml-2" alt="ps">
                                پشتو</a></li>
                        <li><a href="{{ route('set-locale', 'en') }}"
                                class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer"><img
                                    src="{{ asset('assets/sarafi/all_icon/united.png') }}" class="w-5 h-5 ml-2"
                                    alt="en"> English</a></li>
                    </ul>
                </div>
            </div>

            <!-- سرچ، اعلان، پروفایل -->
            <div class="flex items-center space-x-4 gap-1 pl-10 rtl:space-x-reverse">
                <div class="relative">
                    <input type="text" placeholder="جستجو..."
                        class="border border-[#8C8C8C] placeholder:text-black vazir rounded-full px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                        class="h-5 w-5 absolute left-2 bottom-3">
                </div>

                <button
                    class="relative flex items-center justify-center w-10 h-10 rounded-full bg-[#E5E5E5] hover:bg-gray-300 transition">
                    <img src="{{ asset('assets/sarafi/all_icon/notification.png') }}" alt="اعلان" class="w-5 h-5">
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                </button>

                <div
                    class="w-10 h-10 rounded-full overflow-hidden bg-[#E5E5E5] flex items-center justify-center hover:bg-gray-300 transition">
                    <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="پروفایل"
                        class="w-7 h-7 object-cover">
                </div>
            </div>
        </header>

       <div class="flex flex-1 mt-10 min-h-screen">
    <aside class="w-72 hidden md:block p-5">
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
            active: 'dashboard'
        }">
            
            <!-- داشبورد -->
            <a href="{{ route('sarafi.home') }}"
                class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
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

            <!-- مشتریان -->
            <div>
                <button @click="openItems.customers = !openItems.customers; active = 'customers'"
                    :class="active === 'customers' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/people.svg') }}" class="w-5 h-5"
                            :class="active === 'customers' ? 'filter invert brightness-0' : 'text-gray-500'">
                        مشتریان
                    </span>
                    <svg :class="[openItems.customers ? 'rotate-180' : '', active === 'customers' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.customers" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('sarafi.customer-create') }}"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'customer-create'"
                        :class="active === 'customer-create' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" class="w-4 h-4"
                            :class="active === 'customer-create' ? 'filter invert brightness-0' : 'text-gray-500'">
                        ثبت مشتری
                    </a>


                        <a href="{{ route('sarafi.customer-table') }}"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'customer-table'"
                        :class="active === 'customer-table' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" class="w-4 h-4"
                            :class="active === 'customer-table' ? 'filter invert brightness-0' : 'text-gray-500'">
                        لیست مشتریان
                    </a>
                </div>
            </div>

            <!-- ثبت حسابات و نرخ ارز -->
            <div>
                <button @click="openItems.accounts = !openItems.accounts; active = 'accounts'"
                    :class="active === 'accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2 ">
                        <img src="{{ asset('assets/sarafi/all_icon/edit-2.svg') }}" class="w-5 h-5"
                            :class="active === 'accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                        ثبت حسابات و نرخ ارز
                    </span>
                    <svg :class="[openItems.accounts ? 'rotate-180' : '', active === 'accounts' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.accounts" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'register-accounts'"
                        :class="active === 'register-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/add.svg') }}" class="w-4 h-4"
                            :class="active === 'register-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                        ثبت جدید
                    </a>
                </div>
            </div>

            <!-- بارگذاری فایل بانکی -->
            <div>
                <button @click="openItems.bankFiles = !openItems.bankFiles; active = 'bankFiles'"
                    :class="active === 'bankFiles' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/receive-square.svg') }}" class="w-5 h-5"
                            :class="active === 'bankFiles' ? 'filter invert brightness-0' : 'text-gray-500'">
                        بارگذاری فایل بانکی
                    </span>
                    <svg :class="[openItems.bankFiles ? 'rotate-180' : '', active === 'bankFiles' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.bankFiles" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'upload-bank'"
                        :class="active === 'upload-bank' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/upload.svg') }}" class="w-4 h-4"
                            :class="active === 'upload-bank' ? 'filter invert brightness-0' : 'text-gray-500'">
                        آپلود فایل
                    </a>
                </div>
            </div>

            <!-- ویرایش حسابات و نرخ ارز -->
            <div>
                <button @click="openItems.editAccounts = !openItems.editAccounts; active = 'editAccounts'"
                    :class="active === 'editAccounts' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" class="w-5 h-5"
                            :class="active === 'editAccounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                        ویرایش حسابات و نرخ ارز
                    </span>
                    <svg :class="[openItems.editAccounts ? 'rotate-180' : '', active === 'editAccounts' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.editAccounts" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'edit-accounts'"
                        :class="active === 'edit-accounts' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" class="w-4 h-4"
                            :class="active === 'edit-accounts' ? 'filter invert brightness-0' : 'text-gray-500'">
                        ویرایش اطلاعات
                    </a>
                </div>
            </div>

            <!-- گزارش و آمار حسابات -->
            <div>
                <button @click="openItems.reports = !openItems.reports; active = 'reports'"
                    :class="active === 'reports' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/graph.svg') }}" class="w-5 h-5"
                            :class="active === 'reports' ? 'filter invert brightness-0' : 'text-gray-500'">
                        گزارش و آمار حسابات
                    </span>
                    <svg :class="[openItems.reports ? 'rotate-180' : '', active === 'reports' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.reports" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'view-reports'"
                        :class="active === 'view-reports' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/chart.svg') }}" class="w-4 h-4"
                            :class="active === 'view-reports' ? 'filter invert brightness-0' : 'text-gray-500'">
                        مشاهده گزارشات
                    </a>
                </div>
            </div>

            <!-- کنترول و بررسی معاملات -->
            <div>
                <button @click="openItems.transactions = !openItems.transactions; active = 'transactions'"
                    :class="active === 'transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/health.svg') }}" class="w-5 h-5"
                            :class="active === 'transactions' ? 'filter invert brightness-0' : 'text-gray-500'">
                        کنترول و بررسی معاملات
                    </span>
                    <svg :class="[openItems.transactions ? 'rotate-180' : '', active === 'transactions' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.transactions" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'control-transactions'"
                        :class="active === 'control-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/eye.svg') }}" class="w-4 h-4"
                            :class="active === 'control-transactions' ? 'filter invert brightness-0' : 'text-gray-500'">
                        بررسی معاملات
                    </a>
                </div>
            </div>

            <!-- بررسی معاملات حذف شده -->
            <div>
                <button @click="openItems.deletedTransactions = !openItems.deletedTransactions; active = 'deletedTransactions'"
                    :class="active === 'deletedTransactions' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/trash.svg') }}" class="w-5 h-5"
                            :class="active === 'deletedTransactions' ? 'filter invert brightness-0' : 'text-gray-500'">
                        بررسی معاملات حذف شده
                    </span>
                    <svg :class="[openItems.deletedTransactions ? 'rotate-180' : '', active === 'deletedTransactions' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.deletedTransactions" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'deleted-transactions'"
                        :class="active === 'deleted-transactions' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/archive.svg') }}" class="w-4 h-4"
                            :class="active === 'deleted-transactions' ? 'filter invert brightness-0' : 'text-gray-500'">
                        معاملات حذف شده
                    </a>
                </div>
            </div>

            <!-- مدیریت و دسترسی -->
            <div>
                <button @click="openItems.management = !openItems.management; active = 'management'"
                    :class="active === 'management' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/Group 1325.svg') }}" class="w-5 h-5"
                            :class="active === 'management' ? 'filter invert brightness-0' : 'text-gray-500'">
                        مدیریت و دسترسی
                    </span>
                    <svg :class="[openItems.management ? 'rotate-180' : '', active === 'management' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.management" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'user-management'"
                        :class="active === 'user-management' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/user.svg') }}" class="w-4 h-4"
                            :class="active === 'user-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                        مدیریت کاربران
                    </a>
                </div>
            </div>

            <!-- مدیریت پیامک ها -->
            <div>
                <button @click="openItems.sms = !openItems.sms; active = 'sms'"
                    :class="active === 'sms' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/sms.svg') }}" class="w-5 h-5"
                            :class="active === 'sms' ? 'filter invert brightness-0' : 'text-gray-500'">
                        مدیریت پیامک ها
                    </span>
                    <svg :class="[openItems.sms ? 'rotate-180' : '', active === 'sms' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.sms" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'sms-management'"
                        :class="active === 'sms-management' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/message.svg') }}" class="w-4 h-4"
                            :class="active === 'sms-management' ? 'filter invert brightness-0' : 'text-gray-500'">
                        ارسال پیامک
                    </a>
                </div>
            </div>

            <!-- اطلاعیه های آنلاین -->
            <div>
                <button @click="openItems.notifications = !openItems.notifications; active = 'notifications'"
                    :class="active === 'notifications' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/wifi.svg') }}" class="w-5 h-5"
                            :class="active === 'notifications' ? 'filter invert brightness-0' : 'text-gray-500'">
                        اطلاعیه های آنلاین
                    </span>
                    <svg :class="[openItems.notifications ? 'rotate-180' : '', active === 'notifications' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.notifications" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'online-notifications'"
                        :class="active === 'online-notifications' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/notification.svg') }}" class="w-4 h-4"
                            :class="active === 'online-notifications' ? 'filter invert brightness-0' : 'text-gray-500'">
                        اطلاعیه جدید
                    </a>
                </div>
            </div>

            <!-- پشتیبانی سیستم -->
            <div>
                <button @click="openItems.support = !openItems.support; active = 'support'"
                    :class="active === 'support' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/document-copy.svg') }}" class="w-5 h-5"
                            :class="active === 'support' ? 'filter invert brightness-0' : 'text-gray-500'">
                        پشتیبانی سیستم
                    </span>
                    <svg :class="[openItems.support ? 'rotate-180' : '', active === 'support' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.support" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'system-support'"
                        :class="active === 'system-support' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/support.svg') }}" class="w-4 h-4"
                            :class="active === 'system-support' ? 'filter invert brightness-0' : 'text-gray-500'">
                        تیکت پشتیبانی
                    </a>
                </div>
            </div>

            <!-- تنظیمات -->
            <div>
                <button @click="openItems.settings = !openItems.settings; active = 'settings'"
                    :class="active === 'settings' ? 'bg-[#122EE1] text-white' : 'text-gray-700 hover:bg-gray-100'"
                    class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                    <span class="flex items-center gap-2">
                        <img src="{{ asset('assets/sarafi/all_icon/setting-2.svg') }}" class="w-5 h-5"
                            :class="active === 'settings' ? 'filter invert brightness-0' : 'text-gray-500'">
                        تنظیمات
                    </span>
                    <svg :class="[openItems.settings ? 'rotate-180' : '', active === 'settings' ? 'text-white' : 'text-gray-500']"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openItems.settings" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="#"
                        class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                        @click="active = 'system-settings'"
                        :class="active === 'system-settings' ? 'bg-[#122EE1] text-white' : 'text-gray-600 hover:bg-gray-100'">
                        <img src="{{ asset('assets/sarafi/all_icon/settings.svg') }}" class="w-4 h-4"
                            :class="active === 'system-settings' ? 'filter invert brightness-0' : 'text-gray-500'">
                        تنظیمات سیستم
                    </a>
                </div>
            </div>

        </nav>
    </aside>

    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>


        
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader');
    const mainContent = document.getElementById('mainContent');
    const progressBar = document.querySelector('.progress');

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
    </script>

</body>

</html>