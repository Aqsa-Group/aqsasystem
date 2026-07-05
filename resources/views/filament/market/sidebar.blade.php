<!-- سایدبار ریسپانسیو با پشتیبانی از دارک/لایت -->
<div x-data="{ 
    isOpen: true,
    activeGroup: null,
    toggleGroup(group) {
        this.activeGroup = this.activeGroup === group ? null : group;
    },
    isActive(route) {
        return window.location.pathname === route;
    }
}" x-init="() => {
    if (window.innerWidth < 768) isOpen = false;
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) isOpen = true;
        else isOpen = false;
    });
}" class="relative" x-cloak>

    <!-- دکمه همبرگر (فقط موبایل) -->
    <button @click="isOpen = !isOpen"
        class="fixed top-4 right-4 z-50 md:hidden bg-[#184D6C] dark:bg-[#0f3b52] text-white p-2 rounded-xl shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- اورلی موبایل -->
    <div x-show="isOpen" @click="isOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden"></div>

    <!-- سایدبار -->
    <aside x-show="isOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 right-0 h-full w-72 bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-700 shadow-2xl z-50 overflow-y-auto md:relative md:translate-x-0 md:shadow-none"
        :class="isOpen ? 'block' : 'hidden md:block'">

        <!-- هدر -->
        <div class="sticky top-0 bg-[#184D6C] dark:bg-[#0f3b52] text-white p-4 flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-xl p-2">
                    <i class="fas fa-store-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold yekan dark:text-white">مدیریت مارکت</h2>
                </div>
            </div>
            <button @click="isOpen = false" class="md:hidden text-white hover:bg-white/20 p-1 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>



       <!-- منو -->
<nav class="p-3 space-y-1 mt-4">

    <!-- داشبورد -->
    <a href="/market/dashboard"
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group"
        :class="isActive('/market/dashboard') ? 'bg-[#184D6C] text-white dark:text-white' : ''">
        <div class="w-8 h-8 flex items-center justify-center rounded-lg"
            :class="isActive('/market/dashboard') ? 'bg-white/20' : 'bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20'">
            <i class="fas fa-chart-pie"
                :class="isActive('/market/dashboard') ? 'text-white' : 'text-[#184D6C] dark:text-white group-hover:text-white'"></i>
        </div>
        <span class="font-medium dark:text-white">داشبورد</span>
    </a>

    <!-- اطلاعات مارکت -->
    <div>
        <button @click="toggleGroup('market')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20">
                    <i class="fas fa-store text-[#184D6C] dark:text-white group-hover:text-white"></i>
                </div>
                <span class="font-medium dark:text-white">اطلاعات مارکت</span>
            </div>
            <i class="fas fa-chevron-down text-gray-500 dark:text-white text-xs transition-transform"
                :class="activeGroup === 'market' ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeGroup === 'market'" x-collapse
            class="mr-8 mt-1 space-y-1 border-r-2 border-[#184D6C]/20 pr-2">
            <a href="/market/markets"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">مارکت‌ها</a>
            <a href="/market/shops"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">دوکان‌ها</a>
            <a href="/market/booths"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">غرفه‌ها</a>
            <a href="/market/shopkeepers"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">دوکانداران</a>
            <a href="/market/customers"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">مشتریان</a>
            <a href="/market/documents"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">اسناد قراردادها</a>
        </div>
    </div>

    <!-- بخش مالی -->
    <div>
        <button @click="toggleGroup('financial')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20">
                    <i class="fas fa-coins text-[#184D6C] dark:text-white group-hover:text-white"></i>
                </div>
                <span class="font-medium dark:text-white">بخش مالی</span>
            </div>
            <i class="fas fa-chevron-down text-gray-500 dark:text-white text-xs transition-transform"
                :class="activeGroup === 'financial' ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeGroup === 'financial'" x-collapse
            class="mr-8 mt-1 space-y-1 border-r-2 border-[#184D6C]/20 pr-2">

            <a href="/market/safes"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-wallet text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                موجودی صندوق‌ها
            </a>
            <a href="/market/withdrawals"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-arrow-down text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                برداشت از صندوق
            </a>
            <a href="/market/accountings"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-calculator text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                حسابداری
            </a>
            <a href="/market/deposits"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-hourglass-half text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                تسویه نشده
            </a>
            <a href="/market/outsides"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-hand-holding-usd text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                ثبت عواید بیرونی
            </a>
            <a href="/market/loans"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-file-invoice text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                ثبت بردگی‌ها
            </a>
            <a href="/market/payments"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-receipt text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                ثبت رسیدها
            </a>
            <a href="/market/shopkeeper-receipts"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-user-tie text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                رسید دوکانداران
            </a>
        </div>
    </div>

    <!-- معاملات املاک -->
    <div>
        <button @click="toggleGroup('realestate')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20">
                    <i class="fas fa-building text-[#184D6C] dark:text-white group-hover:text-white"></i>
                </div>
                <span class="font-medium dark:text-white">معاملات املاک</span>
            </div>
            <i class="fas fa-chevron-down text-gray-500 dark:text-white text-xs transition-transform"
                :class="activeGroup === 'realestate' ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeGroup === 'realestate'" x-collapse
            class="mr-8 mt-1 space-y-1 border-r-2 border-[#184D6C]/20 pr-2">
            <a href="/market/advertisments"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">ثبت ملک</a>
            <a href="/market/sells"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">فروش ملک</a>
            <a href="/market/buys"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">خریدها</a>
        </div>
    </div>

    <!-- مدیریت کارمندان -->
    <div>
        <button @click="toggleGroup('staff')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20">
                    <i class="fas fa-user-shield text-[#184D6C] dark:text-white group-hover:text-white"></i>
                </div>
                <span class="font-medium dark:text-white">مدیریت کارمندان</span>
            </div>
            <i class="fas fa-chevron-down text-gray-500 dark:text-white text-xs transition-transform"
                :class="activeGroup === 'staff' ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeGroup === 'staff'" x-collapse
            class="mr-8 mt-1 space-y-1 border-r-2 border-[#184D6C]/20 pr-2">
            <a href="/market/staff"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-user-plus text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                ثبت کارمندان
            </a>
            <a href="/market/salaries"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all flex items-center gap-2">
                <i class="fas fa-money-check-alt text-xs text-[#184D6C]/60 dark:text-white/60"></i>
                پرداخت معاشات
            </a>
        </div>
    </div>

    <!-- گزارش‌ها -->
    <div>
        <button @click="toggleGroup('reports')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20">
                    <i class="fas fa-file-alt text-[#184D6C] dark:text-white group-hover:text-white"></i>
                </div>
                <span class="font-medium dark:text-white">گزارشات</span>
            </div>
            <i class="fas fa-chevron-down text-gray-500 dark:text-white text-xs transition-transform"
                :class="activeGroup === 'reports' ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="activeGroup === 'reports'" x-collapse
            class="mr-8 mt-1 space-y-1 border-r-2 border-[#184D6C]/20 pr-2">
            <a href="/market/general-reports"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">گزارش گیری عمومی</a>
            <a href="/market/deposit-logs"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">گزارش رسید دوکان ها</a>
            <a href="/market/loan-logs"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">گزارش بردگی ها</a>
            <a href="/market/withdraw-logs"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">گزارش برداشت‌ها از صندوق</a>
            <a href="/market/user-log-report"
                class="block px-4 py-2 rounded-lg text-sm text-gray-600 dark:text-white hover:bg-[#184D6C]/10 hover:text-[#184D6C] dark:hover:text-white transition-all">گزارش ورود و خروج کارمند</a>
        </div>
    </div>

    <!-- کاربران (آیتم مستقل) -->
    <a href="/market/users"
        class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-[#184D6C] hover:text-white dark:hover:text-white transition-all group">
        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#184D6C]/10 dark:bg-[#184D6C]/20 group-hover:bg-white/20">
            <i class="fas fa-users text-[#184D6C] dark:text-white group-hover:text-white"></i>
        </div>
        <span class="font-medium dark:text-white">کاربران</span>
    </a>

</nav>


    </aside>

    @push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: "DimaYekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
        }

        @font-face {
            font-family: "yekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
        }

        .yekan {
            font-family: "DimaYekan", "yekan", sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        aside::-webkit-scrollbar {
            width: 4px;
        }

        aside::-webkit-scrollbar-track {
            background: transparent;
        }

        aside::-webkit-scrollbar-thumb {
            background: #184D6C40;
            border-radius: 10px;
        }

        aside::-webkit-scrollbar-thumb:hover {
            background: #184D6C80;
        }
    </style>
    @endpush
</div>