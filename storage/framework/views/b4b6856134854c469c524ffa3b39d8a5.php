<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به صرافی</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @font-face {
            font-family: 'Vazir';
            src: url('<?php echo e(asset('fonts/Scheherazade-Regular.ttf')); ?>') format('truetype');
            font-weight: 300;
            font-style: normal;
        }

        @font-face {
            font-family: 'nastaliq';
            src: url('<?php echo e(asset('fonts/shabnam/Shabnam-Bold.ttf')); ?>') format('truetype');
            font-weight: 300;
            font-style: normal;
        }

        body {
            font-family: 'Vazir', sans-serif;
            background: white
        }

        .brand {
            font-family: 'nastaliq', sans-serif;
        }

        .input-icon {
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-5xl h-full flex flex-col md:flex-row  overflow-hidden shadow-2xl ">

        <!-- Left: Form -->
        <div class="md:w-1/2  p-10 flex flex-col justify-center form-container  *:">
            <h1 class="text-4xl font-bold text-black mb-8 text-center">ورود به صرافی</h1>

            <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <?php if(session('error')): ?>
                    <div class="bg-red-500/80 text-black p-3 rounded-md shadow text-center">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <!-- Username -->
                <div class="relative">
                    <label class="block text-black font-bold mb-2">نام کاربری</label>
                    <input type="text" name="username"
                        class="w-full rounded-xl px-4 py-3 pr-12 outline-none border border-gray-300 focus:ring-2 focus:ring-blue-400 bg-white text-black placeholder-gray-500"
                        placeholder="نام کاربری خود را وارد کنید">
                        <img src="<?php echo e(asset('assets/user.png')); ?>" class="absolute right-3 bottom-1 transform -translate-y-1/2 h-5 w-5">
                    </div>

                <!-- Password -->
                <div class="relative mt-4">
                    <label class="block text-black font-bold mb-2">رمز عبور</label>
                    <input type="password" name="password"
                        class="w-full rounded-xl px-4 py-3 pr-12 outline-none border border-gray-300 focus:ring-2 focus:ring-blue-400 bg-white text-black placeholder-gray-500"
                        placeholder="رمز عبور خود را وارد کنید">
                  <img src="<?php echo e(asset('assets/password.png')); ?>" class="absolute right-3 bottom-1 transform -translate-y-1/2 h-5 w-5">
                </div>


                <!-- Login button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-indigo-600 hover:to-blue-500 text-white  py-3 rounded-xl font-bold shadow-lg transition-all">
                    ورود به حساب
                </button>
            </form>
        </div>

        <div
            class="md:w-1/2 relative hidden md:flex rounded-tr-[10%] rounded-br-[10%]  z-10 overflow-hidden items-center justify-center bg-gradient-to-br from-purple-600 to-blue-500">

            <img src="<?php echo e(asset('assets/money.jpg')); ?>" class="absolute w-full h-full object-cover" alt="">
            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                <h1 class="text-white text-4xl font-bold mb-2 brand">اقصی سیستم</h1>
                <h1 class="text-white text-xl mb-2">صرافی امن و سریع</h1>
                <p class="text-white text-lg">تمام تراکنش‌های شما در یک سیستم امن و حرفه‌ای</p>
            </div>
        </div>

    </div>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/filament/auth/pages/sarafi_login.blade.php ENDPATH**/ ?>