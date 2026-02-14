<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>اقصی گروپ - Aqsasystem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/aqsa.jpg') }}">

    <!-- فونت‌های فارسی -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&family=Mirza&display=swap"
        rel="stylesheet">
    @include('Sarafi.layouts.links')
    <style>
        body {
            font-family: 'Vazirmatn', 'Mirza', sans-serif;
            background: white;
            overflow-x: hidden;
        }

        .main {
            position: relative;
            z-index: 0;
            min-height: 100vh;
        }

        .main::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('assets/panels/bg.jpg') }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: -1;
        }


        .logo-text {
            background: linear-gradient(to right, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .hero-section {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%230ea5e9" fill-opacity="0.1" d="M0,128L48,144C96,160,192,192,288,186.7C384,181,480,139,576,138.7C672,139,768,181,864,197.3C960,213,1056,203,1152,186.7C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }

        .card-hover {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }



        .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 1.2em;
            background-color: #f59e0b;
            margin-right: 2px;
            animation: blink 1s infinite;
        }


        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }




        @font-face {
            font-family: "DimaYekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }




        .yekan {
            font-family: "DimaYekan", sans-serif;
        }



        @font-face {
            font-family: "times";
            src: url("/fonts/times.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }


        .times {
            font-family: "times", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- ذرات شناور در پس‌زمینه -->
    <div class="particles" id="particles"></div>

    <!-- هدر -->
    <header class="flex flex-col sm:flex-row justify-between items-center mb-5 mt-3 space-y-5 px-4 sm:px-8">
        <div class="flex items-center gap-3">
            <div class="w-[70px] h-[70px] rounded-full  flex items-center justify-center mr-3  bg-white shadow-2xl "
                style="box-shadow: 4px 4px 4px 4px #00000040, 0 0 0 0 #3B82F6;">
                <img src="{{ asset('assets/icon.jpg') }}" class="rounded-full" alt="" class="w-10 h-10">
            </div>
            <div class="flex flex-col space-y-1">
                <p class="text-[#276284] text-[24px] font-bold">اقصی سیستم</p>
                <p class="text-[#6CA0B6] text-[21px]">AQSA SYSTEM</p>

            </div>
        </div>

        <div class="relative w-[320px]">
            <input type="text" wire:model.live="search" placeholder="جستجو پنل...." class="w-full h-12 md:h-[51px]
                           border border-[#D7E5EC]
                           dark:bg-black dark:border-white dark:placeholder:text-white placeholder:text-black
                           rounded-[16px] pl-3 pr-12 text-sm md:text-base
                           bg-transparent relative z-0">

            {{-- آیکون --}}
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 pointer-events-none dark:hidden">
                <path d="M18.5 18.5L22 22" stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" />
                <path
                    d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                    stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <path
                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>


        </div>



    </header>

    <!-- بخش اصلی -->
    <main class="flex-grow  main flex flex-col items-center justify-center py-2 px-4 mt-4 md:mt-2">
        <div class="flex flex-col justify-center items-center mx-auto  space-y-2">
            <p class="text-transparent bg-clip-text bg-gradient-to-l from-[#198ED8] to-[#0D4B72] text-[40px] font-bold">
                به اقصی سیستم خوش آمدید
            </p>
            <p class="text-[24px]">مجموعه کامل پنل های مدیریت کسب و کار _ هر پنلی که نیاز دارید در یک مکان</p>
        </div>
        <!-- کارت‌های پنل‌ها -->
        <div class="grid grid-cols-1 md:grid-cols-4  lg:grid-cols-4 py-4 gap-6 mt-4 mb-16">

            {{-- Sarafi --}}
            <div class="flex flex-col bg-white shadow-md w-[286px] rounded-[12px] ">
                <div>
                    <img src="{{ asset('assets/panels/sarafi.jpg') }}" alt=""
                        class="rounded-tr-[12px] rounded-tl-[12px]">
                </div>
                <div class="text-black px-10 py-2">
                    <p class="font-bold">صرافی</p>
                    <p class="font-bold">Currency Exchange</p>
                </div>
                <div>
                    <p class="text-gray-500 px-10 text-sm">پلتفرم تخصصی مدیریت صرافی و ارز</p>

                </div>

                <div class="flex px-10 py-4">
                    <div class="flex gap-4">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#2983B7]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="6" r="4" stroke="#3F3F3F" stroke-width="1.5" />
                                <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="#3F3F3F"
                                    stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M5.88915 20.5843C6.82627 20.8504 7.88256 21 9 21C12.866 21 16 19.2091 16 17C16 14.7909 12.866 13 9 13C5.13401 13 2 14.7909 2 17C2 17.3453 2.07657 17.6804 2.22053 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                            </svg>

                        </div>
                        <div class="flex flex-col text-black border-l-2 pl-4">
                            <p class="text-gray-500">کاربران</p>
                            <p>43</p>
                        </div>
                    </div>

                    <div class="flex gap-4 pr-4 ">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#2983B7]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                        </div>
                        <div class="flex flex-col text-black ">
                            <p class="text-gray-500">رشد </p>
                            <p>96%</p>
                        </div>
                    </div>
                </div>


                <a href="/sarafi">
                    <div
                        class=" flex  gap-2 w-[253px] mx-auto rounded-[12px] p-2 mb-4  justify-center items-center bg-gradient-to-l from-[#2F96D2] to-[#184D6C]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12C20 7.58172 16.4183 4 12 4M12 20C14.5264 20 16.7792 18.8289 18.2454 17"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 12H14M14 12L11 9M14 12L11 15" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-white"> ورود به پنل</p>
                    </div>
                </a>

            </div>


            {{-- Market --}}
            <div class="flex flex-col bg-white shadow-md w-[286px] rounded-[12px] ">
                <div>
                    <img src="{{ asset('assets/panels/market.jpg') }}" alt=""
                        class="rounded-tr-[12px] rounded-tl-[12px]">
                </div>
                <div class="text-black px-10 py-2">
                    <p class="font-bold">مارکت</p>
                    <p class="font-bold">Market </p>
                </div>
                <div>
                    <p class="text-gray-500 px-10 text-sm">پلتفرم تخصصی مدیریت مارکت ها</p>

                </div>

                <div class="flex px-10 py-4">
                    <div class="flex gap-4">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#6C9CB4]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="6" r="4" stroke="#3F3F3F" stroke-width="1.5" />
                                <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="#3F3F3F"
                                    stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M5.88915 20.5843C6.82627 20.8504 7.88256 21 9 21C12.866 21 16 19.2091 16 17C16 14.7909 12.866 13 9 13C5.13401 13 2 14.7909 2 17C2 17.3453 2.07657 17.6804 2.22053 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                            </svg>

                        </div>
                        <div class="flex flex-col text-black border-l-2 pl-4">
                            <p class="text-gray-500">کاربران</p>
                            <p>43</p>
                        </div>
                    </div>

                    <div class="flex gap-4 pr-4 ">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#6C9CB4]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                        </div>
                        <div class="flex flex-col text-black ">
                            <p class="text-gray-500">رشد </p>
                            <p>96%</p>
                        </div>
                    </div>
                </div>


                <a href="/market">
                    <div
                        class=" flex  gap-2 w-[253px] mx-auto rounded-[12px] p-2 mb-4  justify-center items-center bg-gradient-to-l from-[#6F9EB6] to-[#347289]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12C20 7.58172 16.4183 4 12 4M12 20C14.5264 20 16.7792 18.8289 18.2454 17"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M4 12H14M14 12L11 9M14 12L11 15" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-white"> ورود به پنل</p>
                    </div>
                </a>

            </div>

            {{-- Sale --}}
            <div class="flex flex-col bg-white shadow-md w-[286px] rounded-[12px] ">
                <div>
                    <img src="{{ asset('assets/panels/sale.jpg') }}" alt="" class="rounded-tr-[12px] rounded-tl-[12px]">
                </div>
                <div class="text-black px-10 py-2">
                    <p class="font-bold">فروشگاه</p>
                    <p class="font-bold">Sales </p>
                </div>
                <div>
                    <p class="text-gray-500 px-10 text-sm">پلتفرم تخصصی مدیریت فروشگاه ها</p>

                </div>

                <div class="flex px-10 py-4">
                    <div class="flex gap-4">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#9C7A61]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="6" r="4" stroke="#3F3F3F" stroke-width="1.5" />
                                <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="#3F3F3F"
                                    stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M5.88915 20.5843C6.82627 20.8504 7.88256 21 9 21C12.866 21 16 19.2091 16 17C16 14.7909 12.866 13 9 13C5.13401 13 2 14.7909 2 17C2 17.3453 2.07657 17.6804 2.22053 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                            </svg>

                        </div>
                        <div class="flex flex-col text-black border-l-2 pl-4">
                            <p class="text-gray-500">کاربران</p>
                            <p>43</p>
                        </div>
                    </div>

                    <div class="flex gap-4 pr-4 ">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#9C7A61]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                        </div>
                        <div class="flex flex-col text-black ">
                            <p class="text-gray-500">رشد </p>
                            <p>96%</p>
                        </div>
                    </div>
                </div>


                <a href="/import">
                    <div
                        class=" flex  gap-2 w-[253px] mx-auto rounded-[12px] p-2 mb-4  justify-center items-center bg-gradient-to-l from-[#9E7C63] to-[#4D2D19]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12C20 7.58172 16.4183 4 12 4M12 20C14.5264 20 16.7792 18.8289 18.2454 17"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />4D2D19
                            <path d="M4 12H14M14 12L11 9M14 12L11 15" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-white"> ورود به پنل</p>
                    </div>
                </a>

            </div>



            {{-- Tools --}}
            <div class="flex flex-col bg-white shadow-md w-[286px] rounded-[12px] ">
                <div>
                    <img src="{{ asset('assets/panels/tools.jpg') }}" alt=""
                        class="rounded-tr-[12px] rounded-tl-[12px]">
                </div>
                <div class="text-black px-10 py-2">
                    <p class="font-bold">ابزارآلات</p>
                    <p class="font-bold">Equipment </p>
                </div>
                <div>
                    <p class="text-gray-500 px-10 text-sm">پلتفرم تخصصی مدیریت ابزار آلات</p>

                </div>

                <div class="flex px-10 py-4">
                    <div class="flex gap-4">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#2983B7]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="6" r="4" stroke="#3F3F3F" stroke-width="1.5" />
                                <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="#3F3F3F"
                                    stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M5.88915 20.5843C6.82627 20.8504 7.88256 21 9 21C12.866 21 16 19.2091 16 17C16 14.7909 12.866 13 9 13C5.13401 13 2 14.7909 2 17C2 17.3453 2.07657 17.6804 2.22053 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                            </svg>

                        </div>
                        <div class="flex flex-col text-black border-l-2 pl-4">
                            <p class="text-gray-500">کاربران</p>
                            <p>43</p>
                        </div>
                    </div>

                    <div class="flex gap-4 pr-4 ">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#2983B7]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                        </div>
                        <div class="flex flex-col text-black ">
                            <p class="text-gray-500">رشد </p>
                            <p>96%</p>
                        </div>
                    </div>
                </div>


                <a href="/tools">
                    <div
                        class=" flex  gap-2 w-[253px] mx-auto rounded-[12px] p-2 mb-4  justify-center items-center bg-gradient-to-l from-[#A47037] to-[#BB3F0B]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12C20 7.58172 16.4183 4 12 4M12 20C14.5264 20 16.7792 18.8289 18.2454 17"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />4D2D19
                            <path d="M4 12H14M14 12L11 9M14 12L11 15" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-white"> ورود به پنل</p>
                    </div>
                </a>

            </div>



            {{-- Restaurant --}}
            <div class="flex flex-col bg-white shadow-md w-[286px] rounded-[12px] ">
                <div>
                    <img src="{{ asset('assets/panels/restaurant.jpg') }}" alt=""
                        class="rounded-tr-[12px] rounded-tl-[12px]">
                </div>
                <div class="text-black px-10 py-2">
                    <p class="font-bold">رستورانت</p>
                    <p class="font-bold">Restaurant </p>
                </div>
                <div>
                    <p class="text-gray-500 px-10 text-sm">پلتفرم تخصصی مدیریت رستورانت ها و کافه ها</p>

                </div>

                <div class="flex px-10 py-4">
                    <div class="flex gap-4">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#D2C440]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9" cy="6" r="4" stroke="#3F3F3F" stroke-width="1.5" />
                                <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="#3F3F3F"
                                    stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M5.88915 20.5843C6.82627 20.8504 7.88256 21 9 21C12.866 21 16 19.2091 16 17C16 14.7909 12.866 13 9 13C5.13401 13 2 14.7909 2 17C2 17.3453 2.07657 17.6804 2.22053 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round" />
                            </svg>

                        </div>
                        <div class="flex flex-col text-black border-l-2 pl-4">
                            <p class="text-gray-500">کاربران</p>
                            <p>43</p>
                        </div>
                    </div>

                    <div class="flex gap-4 pr-4 ">
                        <div class="rounded-[12px] w-[40px] h-[40px] justify-center items-center flex bg-[#D2C440]/30">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M22 7V12.5458M22 7H16.4179M22 7L17.5 11.5M14.6203 14.3347C13.6227 15.3263 13.1238 15.822 12.5051 15.822C11.8864 15.8219 11.3876 15.326 10.3902 14.3342L10.1509 14.0962C9.15254 13.1035 8.65338 12.6071 8.03422 12.6074C7.41506 12.6076 6.91626 13.1043 5.91867 14.0977L2 18"
                                    stroke="#3F3F3F" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>


                        </div>
                        <div class="flex flex-col text-black ">
                            <p class="text-gray-500">رشد </p>
                            <p>96%</p>
                        </div>
                    </div>
                </div>


                <a href="/restaurant">
                    <div
                        class=" flex  gap-2 w-[253px] mx-auto rounded-[12px] p-2 mb-4  justify-center items-center bg-gradient-to-l from-[#90872C] to-[#C6B525]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12C20 7.58172 16.4183 4 12 4M12 20C14.5264 20 16.7792 18.8289 18.2454 17"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" />4D2D19
                            <path d="M4 12H14M14 12L11 9M14 12L11 15" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="text-white"> ورود به پنل</p>
                    </div>
                </a>

            </div>
















        </div>
    </main>
    <footer class="grid grid-cols-1 lg:grid-cols-3 p-6 mx-auto mx-w-fu w-full">
        <!-- Company Info -->
        <div class="flex flex-col space-y-2">
            <h1 class="text-xl font-bold">اقصــــی سیستم</h1>
            <p class="text-sm text-gray-600">ارائه دهنده راهکار های نوین کسب و کار با بیش از 5 سال سابقه</p>
        </div>

        <!-- Contact Info -->
        <div class="flex flex-col space-y-2">
            <h1 class="text-lg font-semibold">تماس باما</h1>
            <div class="flex gap-2 items-center">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M0.756552 4.68162C0.684215 6.58976 1.16713 9.83026 4.4177 13.0808C5.20191 13.865 5.98553 14.4882 6.75 14.9813M2.28781 1.68577C3.68076 0.292817 5.90317 0.479972 6.78759 2.06471L7.4366 3.22764C8.0223 4.27712 7.78718 5.65386 6.86471 6.57634C6.86471 6.57634 5.74578 7.69528 7.77451 9.72402C9.80322 11.7527 10.9222 10.6338 10.9222 10.6338C11.8447 9.71135 13.2214 9.47623 14.2709 10.0619L15.4338 10.7109C17.0186 11.5954 17.2057 13.8178 15.8128 15.2107C14.9758 16.0477 13.9504 16.699 12.8169 16.742C12.0029 16.7728 10.9463 16.7026 9.75 16.3596"
                        stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <p>0790506000</p>
            </div>
            <div class="flex gap-2 items-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C21.4816 5.82475 21.7706 6.69989 21.8985 8"
                        stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" />
                    <path
                        d="M18 8L15.8411 9.79908C14.0045 11.3296 13.0861 12.0949 12 12.0949C11.3507 12.0949 10.7614 11.8214 10 11.2744M6 8L6.9 8.75L7.8 9.5"
                        stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <p>info@aqsasystem.com</p>
            </div>
        </div>

        <!-- Social Media -->
        <div class="flex flex-col space-y-2">
            <h1 class="text-lg font-semibold">شبکه های اجتماعی</h1>
            <div class="flex gap-4">
                <!-- Instagram -->
                <a href="#">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="12" fill="#CAD3E4" />
                        <path
                            d="M20 24C21.0609 24 22.0783 23.5786 22.8284 22.8284C23.5786 22.0783 24 21.0609 24 20C24 18.9391 23.5786 17.9217 22.8284 17.1716C22.0783 16.4214 21.0609 16 20 16C18.9391 16 17.9217 16.4214 17.1716 17.1716C16.4214 17.9217 16 18.9391 16 20C16 21.0609 16.4214 22.0783 17.1716 22.8284C17.9217 23.5786 18.9391 24 20 24Z"
                            stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11 24V16C11 14.6739 11.5268 13.4021 12.4645 12.4645C13.4021 11.5268 14.6739 11 16 11H24C25.3261 11 26.5979 11.5268 27.5355 12.4645C28.4732 13.4021 29 14.6739 29 16V24C29 25.3261 28.4732 26.5979 27.5355 27.5355C26.5979 28.4732 25.3261 29 24 29H16C14.6739 29 13.4021 28.4732 12.4645 27.5355C11.5268 26.5979 11 25.3261 11 24Z"
                            stroke="black" stroke-width="1.5" />
                        <path d="M25.5 14.51L25.51 14.499" stroke="black" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
                <!-- Telegram -->
                <a href="#">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="12" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M29.8398 14.0559C29.8834 13.7912 29.8552 13.5197 29.7582 13.2696C29.6612 13.0195 29.4989 12.8 29.2883 12.6339C29.0776 12.4679 28.8262 12.3614 28.5604 12.3255C28.2945 12.2896 28.0239 12.3257 27.7768 12.4299L10.6768 19.6299C9.48478 20.1319 9.42378 21.8559 10.6768 22.3759C11.9177 22.893 13.1765 23.3659 14.4508 23.7939C15.6188 24.1799 16.8928 24.5369 17.9138 24.6379C18.1928 24.9719 18.5438 25.2939 18.9018 25.5879C19.4488 26.0379 20.1068 26.5009 20.7868 26.9449C22.1488 27.8349 23.6598 28.6859 24.6778 29.2399C25.8948 29.8999 27.3518 29.1399 27.5698 27.8129L29.8398 14.0559ZM12.5938 20.9929L27.7178 14.6249L25.5998 27.4649C24.6008 26.9219 23.1618 26.1089 21.8798 25.2709C21.2889 24.8915 20.7185 24.4813 20.1708 24.0419C20.0244 23.9223 19.8823 23.7976 19.7448 23.6679L23.7058 19.7079C23.8934 19.5204 23.9989 19.2661 23.999 19.0008C23.9991 18.7355 23.8938 18.4811 23.7063 18.2934C23.5188 18.1058 23.2644 18.0003 22.9991 18.0002C22.7339 18.0001 22.4794 18.1054 22.2918 18.2929L17.9548 22.6299C17.2208 22.5359 16.1988 22.2639 15.0768 21.8939C14.2415 21.6156 13.4139 21.3148 12.5948 20.9919L12.5938 20.9929Z"
                            fill="black" />
                    </svg>
                </a>
                <!-- Twitter (X) -->
                <a href="#">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="12" fill="white" />
                        <g clip-path="url(#clip0_2759_19149)">
                            <mask id="mask0_2759_19149" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="10"
                                y="10" width="20" height="20">
                                <path d="M10 10H30V30H10V10Z" fill="white" />
                            </mask>
                            <g mask="url(#mask0_2759_19149)">
                                <path
                                    d="M25.75 10.937H28.8171L22.1171 18.6142L30 29.0627H23.8286L18.9914 22.727L13.4629 29.0627H10.3929L17.5586 20.8484L10 10.9384H16.3286L20.6943 16.7284L25.75 10.937ZM24.6714 27.2227H26.3714L15.4 12.6813H13.5771L24.6714 27.2227Z"
                                    fill="black" />
                            </g>
                        </g>
                        <defs>
                            <clipPath id="clip0_2759_19149">
                                <rect width="20" height="20" fill="white" transform="translate(10 10)" />
                            </clipPath>
                        </defs>
                    </svg>
                </a>
                <!-- LinkedIn -->
                <a href="#">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="12" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M17.5365 17.0951H21.0957V18.868C21.6084 17.8484 22.9232 16.9322 24.8984 16.9322C28.6847 16.9322 29.5837 18.962 29.5837 22.686V29.5832H25.7503V23.5342C25.7503 21.4134 25.2376 20.2174 23.9324 20.2174C22.1221 20.2174 21.3698 21.5063 21.3698 23.5332V29.5832H17.5365V17.0951ZM10.9632 29.4203H14.7966V16.9322H10.9632V29.4203ZM15.3457 12.8603C15.3458 13.1816 15.2821 13.4997 15.1582 13.7962C15.0344 14.0926 14.8528 14.3615 14.6241 14.5872C14.3947 14.8154 14.1226 14.9961 13.8233 15.1191C13.524 15.2421 13.2035 15.3049 12.8799 15.304C12.2279 15.3025 11.6024 15.0455 11.1377 14.5881C10.9099 14.3616 10.729 14.0924 10.6053 13.7959C10.4816 13.4995 10.4177 13.1815 10.417 12.8603C10.417 12.2115 10.6757 11.5905 11.1386 11.1324C11.6022 10.6732 12.2284 10.4159 12.8809 10.4165C13.5345 10.4165 14.1612 10.6743 14.6241 11.1324C15.087 11.5905 15.3457 12.2115 15.3457 12.8603Z"
                            fill="black" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // تایپ کردن متن خوش‌آمدگویی
        document.addEventListener('DOMContentLoaded', function() {
            const texts = [
                "سیستم مدیریت یکپارچه کسب و کار",
                "راهکارهای نوین برای موفقیت",
                "پلتفرم جامع اقصی گروپ",
                "به خانواده Aqsasystem خوش آمدید"
            ];
            
            let textIndex = 0;
            let charIndex = 0;
            const typingSpeed = 100;
            const erasingSpeed = 50;
            const newTextDelay = 2000;
            
            const typedTextElement = document.getElementById('typed-text');
            const cursor = document.querySelector('.typing-cursor');
            
            function type() {
                if (charIndex < texts[textIndex].length) {
                    typedTextElement.innerHTML += texts[textIndex].charAt(charIndex);
                    charIndex++;
                    setTimeout(type, typingSpeed);
                } else {
                    cursor.style.animation = 'none';
                    setTimeout(erase, newTextDelay);
                }
            }
            
            function erase() {
                cursor.style.animation = 'blink 1s infinite';
                if (charIndex > 0) {
                    typedTextElement.innerHTML = texts[textIndex].substring(0, charIndex - 1);
                    charIndex--;
                    setTimeout(erase, erasingSpeed);
                } else {
                    textIndex++;
                    if (textIndex >= texts.length) textIndex = 0;
                    setTimeout(type, typingSpeed + 500);
                }
            }
            
            // شروع تایپ پس از تأخیر کوتاه
            setTimeout(type, 1000);
            
            // ایجاد ذرات شناور
            createParticles();
        });
        
       
    </script>
</body>

</html>