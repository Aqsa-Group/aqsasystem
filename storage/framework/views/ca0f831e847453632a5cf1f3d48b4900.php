<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RTL</title>
    <?php echo $__env->make('Sarafi.layouts.links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="bg-white    font-vazir">

   <header class="bg-white w-full h-[80px] flex items-center justify-between px-6 shadow-[0_4px_4px_rgba(17,41,199,0.4)]">
    
    <!-- سمت راست: برند + زبان -->
    <div class="flex items-center space-x-4 rtl:space-x-reverse">
        <div class="text-[40px] text-[#122EE1] font-bold yekan">صرافی زرین</div>

        <!-- انتخاب زبان -->
        <div class="relative inline-block w-[145px] h-[56px] p-4 vazir">
            <button id="dropdownButton"
                    class="border border-[#1129C766] bg-white rounded-lg pl-3 pr-3 py-2 w-full flex items-center justify-between font-vazir text-sm text-[#1129C7]">
                <img src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2" alt="Dari">
                فارسی
            </button>
            <ul id="dropdownMenu"
                class="absolute left-0 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                <li class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2" alt="Dari">
                    فارسی
                </li>
                <li class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/Flags.png')); ?>" class="w-5 h-5 ml-2" alt="Pashto">
                    پشتو
                </li>
                <li class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                    <img src="<?php echo e(asset('assets/sarafi/all_icon/united.png')); ?>" class="w-5 h-5 ml-2" alt="English">
                    English
                </li>
            </ul>
        </div>
    </div>

    <!-- سمت چپ: سرچ، هشدار، پروفایل -->
    <div class="flex items-center space-x-4 rtl:space-x-reverse">
        <!-- سرچ -->
        <div class="relative">
            <input type="text" placeholder="جستجو..."
                   class="border rounded-lg px-3 py-1 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </div>

        <!-- هشدار -->
        <button class="relative">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
        </button>

        <!-- پروفایل -->
        <div class="w-8 h-8 rounded-full overflow-hidden">
            <img src="https://i.pravatar.cc/300" alt="Profile" class="w-full h-full object-cover">
        </div>
    </div>

</header>




    <!-- بخش اصلی: سایدبار + محتوا -->
    <div class="flex flex-1 min-h-screen">

        <!-- سایدبار -->
        <aside class="w-64 bg-white shadow hidden md:block mt-10">
            <div class="p-6 text-xl font-bold yekan border-b border-gray-200 text-right">
                منوی اصلی
            </div>
            <nav class="mt-4">
                <a href="#" class="block py-3 px-6 hover:bg-blue-100 font-vazir text-right">داشبورد</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-100 font-vazir text-right">کاربران</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-100 font-vazir text-right">تنظیمات</a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-100 font-vazir text-right">گزارش‌ها</a>
            </nav>
        </aside>

        <!-- محتوای اصلی -->
        <main class="flex-1 p-6 font-vazir text-right">
            <h1 class="text-2xl font-bold mb-4">داشبورد</h1>
            <p>این بخش محتوای اصلی سایت است...</p>
        </main>
    </div>


    <script>
        const btn = document.getElementById('dropdownButton');
  const menu = document.getElementById('dropdownMenu');
  btn.addEventListener('click', () => menu.classList.toggle('hidden'));
  menu.querySelectorAll('li').forEach(li => {
    li.addEventListener('click', () => {
      btn.innerHTML = li.innerHTML;
      menu.classList.add('hidden');
    });
  });
    </script>

</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Sarafi/layouts/sidebar.blade.php ENDPATH**/ ?>