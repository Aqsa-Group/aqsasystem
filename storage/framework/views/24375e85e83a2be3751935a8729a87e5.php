<!DOCTYPE html>
<html lang="<?php echo e(session('locale', config('app.locale'))); ?>" dir="<?php echo e(session('locale') === 'en' ? 'ltr' : 'rtl'); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RTL</title>
    <?php echo $__env->make('Sarafi.layouts.links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
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
        
        /* استایل برای آیتم‌های اکتیو */
        .nav-item.active {
            background: linear-gradient(135deg, #4f6bff, #122EE1) !important;
            color: white !important;
        }
        
        .nav-item.active img {
            filter: invert(1) brightness(100);
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
            <?php
                $locale = session('locale', config('app.locale'));
            ?>

            <div class="relative inline-block w-[145px] h-[56px] p-2 vazir" x-data="{ open: false }">
                <button @click="open = !open"
                    class="border border-[#1129C766] bg-white rounded-lg px-3 py-2 w-full flex items-center justify-between font-vazir text-sm text-[#1129C7]">
                    <img src="<?php echo e($locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png')); ?>"
                        class="w-5 h-5 ml-2" alt="Lang">
                    <span>
                        <?php if($locale === 'fa'): ?>
                            فارسی
                        <?php elseif($locale === 'ps'): ?>
                            پشتو
                        <?php else: ?>
                            English
                        <?php endif; ?>
                    </span>
                </button>

                <ul x-show="open" @click.away="open = false"
                    class="absolute left-0 mt-1 w-full bg-white border border-gray-200 rounded-lg z-10">
                    <li>
                        <a href="<?php echo e(route('set-locale', 'fa')); ?>" class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2" alt="fa">
                            فارسی
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('set-locale', 'ps')); ?>" class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2" alt="ps">
                            پشتو
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('set-locale', 'en')); ?>" class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/united.png')); ?>" class="w-5 h-5 ml-2" alt="en">
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
                <img src="<?php echo e(asset('assets/sarafi/all_icon/search-normal.png')); ?>" alt=""
                    class="h-5 w-5 absolute left-2 bottom-3 transform">
            </div>

            <!-- دکمه اعلان -->
            <button
                class="relative flex items-center justify-center w-10 h-10 rounded-full bg-[#E5E5E5] hover:bg-gray-300 transition">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/notification.png')); ?>" alt="اعلان" class="w-5 h-5">
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">
                    3
                </span>
            </button>

            <!-- پروفایل -->
            <div
                class="w-10 h-10 rounded-full overflow-hidden bg-[#E5E5E5] flex items-center justify-center hover:bg-gray-300 transition">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="پروفایل" class="w-7 h-7 object-cover">
            </div>
        </div>
    </header>

    <!-- بخش اصلی: سایدبار + محتوا -->
    <div class="flex flex-1 min-h-screen">
        <!-- سایدبار -->
        <aside class="w-64 hidden md:block p-5">
            <nav class="mt-4 space-y-1" x-data="navigationState()">
                <!-- داشبورد -->
                <a href="<?php echo e(route('sarafi.home')); ?>"
                    class="nav-item flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                    :class="active === 'dashboard' ? 'active' : 'text-gray-700 hover:bg-gray-100'"
                    @click="setActive('dashboard')">
                    <span class="flex items-center gap-2">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/element-3.svg')); ?>" class="w-5 h-5">
                        داشبورد
                    </span>
                </a>

                <!-- کاربران -->
                <a href="#" class="nav-item flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                    :class="active === 'users' ? 'active' : 'text-gray-700 hover:bg-gray-100'"
                    @click="setActive('users')">
                    <span class="flex items-center gap-2">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/profile-2user.svg')); ?>" class="w-5 h-5">
                        کاربران
                    </span>
                </a>

                <!-- مشتریان -->
                <div>
                    <button @click="toggleSection('customers')"
                        class="nav-item flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir"
                        :class="active === 'customers' ? 'active' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/people.svg')); ?>" class="w-5 h-5">
                            مشتریان
                        </span>
                        <svg :class="[openCustomers ? 'rotate-180' : '']"
                            class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openCustomers" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="<?php echo e(route('sarafi.customer-create')); ?>"
                            class="nav-item flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                            :class="active === 'customer-create' ? 'active' : 'text-gray-600 hover:bg-gray-100'"
                            @click="setActive('customer-create')">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" class="w-4 h-4">
                            ثبت مشتری
                        </a>
                    </div>
                </div>

                <!-- حسابداری -->
                <div>
                    <button @click="toggleSection('accounting')"
                        class="nav-item flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir"
                        :class="active === 'accounting' ? 'active' : 'text-gray-700 hover:bg-gray-100'">
                        <span class="flex items-center gap-2">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/people.svg')); ?>" class="w-5 h-5">
                            حسابداری
                        </span>
                        <svg :class="[openAccounting ? 'rotate-180' : '']"
                            class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="openAccounting" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="<?php echo e(route('sarafi.accounting-report')); ?>"
                            class="nav-item flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                            :class="active === 'accounting-report' ? 'active' : 'text-gray-600 hover:bg-gray-100'"
                            @click="setActive('accounting-report')">
                            <img src="<?php echo e(asset('assets/sarafi/all_icon/edit-2.svg')); ?>" class="w-4 h-4">
                            گزارش حسابداری
                        </a>
                    </div>
                </div>
            </nav>
        </aside>
        
        <main class="">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script>
        // مدیریت وضعیت navigation
        function navigationState() {
            return {
                active: 'dashboard',
                openCustomers: false,
                openAccounting: false,
                
                init() {
                    // تنظیم وضعیت اولیه بر اساس URL فعلی
                    this.setInitialState();
                },
                
                setInitialState() {
                    const path = window.location.pathname;
                    
                    if (path.includes('customer-create')) {
                        this.active = 'customer-create';
                        this.openCustomers = true;
                    } else if (path.includes('accounting-report')) {
                        this.active = 'accounting-report';
                        this.openAccounting = true;
                    } else if (path.includes('home')) {
                        this.active = 'dashboard';
                    }
                    // می‌توانید شرایط بیشتری بر اساس URL اضافه کنید
                },
                
                setActive(item) {
                    this.active = item;
                    
                    // اگر آیتم مربوط به یک بخش است، آن بخش را باز کنید
                    if (item === 'customer-create') {
                        this.openCustomers = true;
                    } else if (item === 'accounting-report') {
                        this.openAccounting = true;
                    } else if (item === 'customers') {
                        this.openCustomers = !this.openCustomers;
                    } else if (item === 'accounting') {
                        this.openAccounting = !this.openAccounting;
                    }
                },
                
                toggleSection(section) {
                    if (section === 'customers') {
                        this.openCustomers = !this.openCustomers;
                        this.active = this.openCustomers ? 'customers' : this.active;
                    } else if (section === 'accounting') {
                        this.openAccounting = !this.openAccounting;
                        this.active = this.openAccounting ? 'accounting' : this.active;
                    }
                }
            }
        }

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
            const navLinks = document.querySelectorAll('.nav-item');
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

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/sidebar.blade.php ENDPATH**/ ?>