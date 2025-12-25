<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ورود به پنل ابزارآلات</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/sarafi.css')}}">



</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center h-screen p-4 ">
        <div class="flex flex-col w-[1200px] h-[600px] md:flex-row-reverse items-center gap-10 bg-white shadow-lg">


            <div class="w-full md:w-1/2 flex justify-center relative">
                <img src="{{ asset('assets/tools/tools .webp') }}" alt="ورود به پنل صرافی"
                    class="shadow-md w-full h-[600px] object-cover rounded-xl">

                <!-- لایه نیمه شفاف -->
                <div class="absolute inset-0 bg-[#675323]/60 rounded-xl"></div>

                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <h1 class="text-[40px] md:text-[50px] font-bold yekan text-white">
                        اقصی سیستم
                    </h1>
                    <h1 class="text-[40px] md:text-[50px] times text-white">
                        Aqsa System
                    </h1>
                </div>
            </div>



            <div class="md:w-1/2 w-[450px] p-0 md:pr-4 ">
                <form action="{{ route('tools.login') }}" method="POST"
                    class="space-y-5 border border-[#8C8C8C] p-6 rounded-lg bg-white relative">
                    @csrf
                    <h1 class="text-4xl font-bold text-center mb-2 yekan">
                        ورود به پنل ابزارآلات
                    </h1>

                    <!-- نام کاربری -->
                    <div class="flex flex-col relative">
                        <label for="username" class="mb-2 font-semibold vazir text-[#000000]">نام کاربری</label>
                        <input id="username" name="username" value="{{ old('username') }}" type="text"
                            placeholder="نام کاربری خود را وارید کنید!"
                            class="border border-[#8C8C8C] rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="{{ asset('assets/sarafi/all_icon/profile.svg') }}" alt="آیکون پروفایل"
                            class="h-5 w-5 absolute right-3 bottom-3">
                    </div>

                    <!-- رمز عبور -->
                    <div class="flex flex-col relative">
                        <label for="password" class="mb-2 font-semibold vazir">رمز عبور</label>
                        <input id="password" name="password" type="password" placeholder=" رمز عبور خود را وارید کنید!"
                            class="border border-[#8C8C8C] rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="{{ asset('assets/sarafi/all_icon/lock.png') }}" alt="آیکون قفل"
                            class="h-5 w-5 absolute right-3 bottom-3">
                    </div>

                    <!-- دکمه ورود -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 vazir rounded-lg hover:bg-blue-700 transition duration-300">
                        ورود به حساب
                    </button>


                    @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="error-message text-center text-red-500 vazir">
                        {{ session('error') }}
                    </div>
                    @endif


                    @if (session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

</body>

</html>