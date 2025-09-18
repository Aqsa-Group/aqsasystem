<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل صرافی | ورود / ثبت‌نام</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/login.css')); ?>">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Vazir', sans-serif;
        }

        @font-face {
            font-family: 'Vazir';
            src: url('<?php echo e(asset('fonts/amiri-regular.ttf')); ?>') format('truetype');
            font-weight: 300;
            font-style: normal;
        }

        body {
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .text-center {
            margin-bottom: 30px;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 850px;
            /* عرض بیشتر */
            max-width: 70%;
            min-height: 400px;
            /* ارتفاع کمتر */
            display: flex;
        }

        /* فرم سمت راست */
        .form-container.sign-in {
            flex: 1;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 40px;
        }

        .form-container input {
            background-color: #eee;
            border: none;
            margin: 15px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container button {
            background-color: #512da8;
            color: #fff;
            font-size: 14px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        /* سمت چپ: عکس */
        .toggle-panel.toggle-right {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* عکس کامل پر می‌کنه */
        }

        .image-wrapper .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* نیمه شفاف */
        }

        .image-wrapper .text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-align: center;
        }

        .image-wrapper .text h1 {
            font-size: 28px;
            font-weight: bold;
        }

        .image-wrapper .text p {
            font-size: 16px;
        }

        /* استایل خطاهای validation */
        .error-message {
            color: #ef4444;
            /* قرمز (مثل Tailwind text-red-500) */
            font-size: 0.875rem;
            /* معادل text-sm */
            text-align: center;
            margin-top: 0.5rem;
            /* معادل mt-2 */
        }

        /* اگر بخوای استایل جذاب‌تر باشه */
        .error-message {
            background: #fee2e2;
            /* قرمز خیلی روشن */
            border: 1px solid #fca5a5;
            padding: 8px 12px;
            border-radius: 6px;
            color: #b91c1c;
            /* قرمز تیره */
            font-size: 14px;
            text-align: center;
            margin: 8px auto;
            width: fit-content;
            max-width: 80%;
        }

        /* برای پیام موفقیت */
        .success-message {
            background: #dcfce7;
            border: 1px solid #86efac;
            padding: 8px 12px;
            border-radius: 6px;
            color: #166534;
            font-size: 14px;
            text-align: center;
            margin: 8px auto;
            width: fit-content;
            max-width: 80%;
        }

        .text-h1{
            font-size: 90px
        }
    </style>
</head>

<body>
    <div class="container" id="container">

        <!-- فرم ورود -->
        <div class="form-container sign-in">
            <form action="<?php echo e(route('sarafi.login')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <h1 class="text-center mb-4">ورود به پنل صرافی</h1>
                <input type="text" name="username" placeholder="نام کاربری" value="<?php echo e(old('username')); ?>" required>
                <input type="password" name="password" placeholder="رمز عبور" required>
                <button type="submit">ورود به حساب</button>

                <?php if($errors->any()): ?>
                    <div class="error-message">
                        <?php echo e($errors->first()); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="error-message">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>


                <?php if(session('success')): ?>
                    <div class="success-message">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

            </form>
        </div>

        <!-- عکس و متن -->
        <div class="toggle-panel toggle-right">
            <div class="image-wrapper">
                <img src="<?php echo e(asset('assets/login.jpg')); ?>" alt="">
                <div class="overlay"></div>
                <div class="text">
                    <h1>اقصی سیستم</h1>
                    <h2>صرافی امن و سریع</h2>
                    <p>تمام تراکنش‌های شما در یک سیستم امن و حرفه‌ای</p>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo e(asset('assets/js/login.js')); ?>"></script>
</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Sarafi/Auth/login.blade.php ENDPATH**/ ?>