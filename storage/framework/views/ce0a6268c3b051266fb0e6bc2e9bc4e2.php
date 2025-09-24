<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>صفحه خوش‌آمدگویی</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- فونت فارسی Mirza -->
    <link href="https://fonts.googleapis.com/css2?family=Mirza&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Mirza', sans-serif;
            background-color: #f9fafb;
            color: #111827;
        }

        #typed-text span {
            display: inline-block;
            opacity: 0;
            animation: fadeIn 0.3s forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .text-container {
            max-height: 300px;
            overflow: hidden;
            text-align: center;
        }

        .button-container a {
            min-width: 250px;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center gap-12">

    <!-- متن خوش‌آمدگویی -->
    <div class="text-container px-6">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-snug">
            <span id="typed-text"></span>
        </h1>
    </div>

    <!-- دو دکمه پنل‌ها -->
    <div class="button-container flex flex-col sm:flex-row gap-6">
        <a href="/market/login"
           class="flex items-center justify-center gap-3 px-10 py-6 rounded-xl text-lg font-semibold
                  bg-blue-600 text-white hover:bg-blue-700 transition duration-300">
            <i class="fa-solid fa-store"></i> ورود به پنل مدیریت مارکت
        </a>

        <a href="/import/login"
           class="flex items-center justify-center gap-3 px-10 py-6 rounded-xl text-lg font-semibold
                  bg-green-600 text-white hover:bg-green-700 transition duration-300">
            <i class="fa-solid fa-cart-shopping"></i> ورود به پنل فروشات
        </a>
    </div>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/welcome.blade.php ENDPATH**/ ?>