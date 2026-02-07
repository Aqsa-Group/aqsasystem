<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- تست رنگ‌های مختلف -->
    <div class="p-8">
        <h1 class="text-red-500 text-2xl flex justify-center mb-4">
            تست رنگ قرمز 500
        </h1>
        <h1 class="text-red-600 text-2xl flex justify-center mb-4">
            تست رنگ قرمز 600
        </h1>
        <h1 class="text-red-700 text-2xl flex justify-center mb-4">
            تست رنگ قرمز 700
        </h1>
        <h1 class="text-blue-500 text-2xl flex justify-center mb-4">
            تست رنگ آبی (برای مقایسه)
        </h1>
        
        <!-- تست با !important -->
        <h1 class="!text-red-500 text-2xl flex justify-center mb-4">
            تست با !important
        </h1>
    </div>
</body>
</html>