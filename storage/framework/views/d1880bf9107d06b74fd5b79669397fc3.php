<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ورود به پنل صرافی</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('assets/aqsa.jpg')); ?>">

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/sarafi.css')); ?>">


    <!-- تعریف فونت -->

</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center h-screen px-4 md:px-0  ">
        <div class="flex flex-col w-[1200px] h-fit p-4 md:p-0 md:flex-row-reverse items-center gap-10 bg-white shadow-lg">


            <div class="md:w-1/2 w-full flex justify-center relative">
                <img src="<?php echo e(asset('assets/sarafi/login-bg.jpg')); ?>" alt="ورود به پنل صرافی"
                    class="shadow-md w-full h-[600px] object-cover rounded-xl">

                <!-- لایه نیمه شفاف -->
                

                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <h1 class="text-[50px] font-bold yekan text-white">
                        اقصی سیستم
                    </h1>
                    <h1 class="text-[50px] times text-white">
                        Aqsa System
                    </h1>
                </div>
            </div>


            <div class="md:w-1/2 w-full pr-0 md:pr-6">
                <form action="<?php echo e(route('sarafi.login')); ?>" method="POST" class="space-y-5 border border-[#8C8C8C] p-6 rounded-lg bg-white relative">
            <?php echo csrf_field(); ?>                                
                    <h1 class="text-4xl font-bold text-center mb-2 yekan">
                        ورود به پنل صرافی
                    </h1>

                    <!-- نام کاربری -->
                    <div class="flex flex-col relative">
                        <label for="username" class="mb-2 font-semibold vazir text-[#000000]">نام کاربری</label>
                        <input id="username" name="username" value="<?php echo e(old('username')); ?>" type="text" placeholder="نام کاربری خود را وارید کنید!"
                            class="border border-[#8C8C8C] rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/profile.svg')); ?>" alt="آیکون پروفایل"
                            class="h-5 w-5 absolute right-3 bottom-3">
                    </div>

                    <!-- رمز عبور -->
                    <div class="flex flex-col relative">
                        <label for="password" class="mb-2 font-semibold vazir">رمز عبور</label>
                        <input id="password" name="password" type="password" placeholder=" رمز عبور خود را وارید کنید!"
                            class="border border-[#8C8C8C] rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="<?php echo e(asset('assets/sarafi/all_icon/lock.png')); ?>" alt="آیکون قفل"
                            class="h-5 w-5 absolute right-3 bottom-3">
                    </div>

                    <!-- دکمه ورود -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 vazir rounded-lg hover:bg-blue-700 transition duration-300">
                        ورود به حساب
                    </button>


                      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="error-message">
                        <?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                    <div class="error-message text-center text-red-500 vazir">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="success-message">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </form>
            </div>
        </div>
    </div>

</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Sarafi/Auth/login.blade.php ENDPATH**/ ?>