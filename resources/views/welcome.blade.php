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
            background:white;
            overflow-x: hidden;
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
    <header class="py-4 px-4 sm:px-8 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div
                class="w-[70px] h-[70px] rounded-full  flex items-center justify-center mr-3  bg-white shadow-2xl "   style="box-shadow: 4px 4px 4px 4px #00000040, 0 0 0 0 #3B82F6;">
                <img src="{{ asset('assets/icon.jpg') }}" class="rounded-full" alt="" class="w-10 h-10">
            </div>
             <div class="flex flex-col space-y-1">
                <p class="text-[#276284] text-[24px]">اقصی سیستم</p>
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
                <path d="M18.5 18.5L22 22" stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"/>
<path d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75" stroke="#8C8C8C" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <path
                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M22 22L20 20" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>


        </div>

 

    </header>

    <!-- بخش اصلی -->
    <main class="flex-grow  flex flex-col items-center justify-center py-2 px-4" >
       
        <!-- کارت‌های پنل‌ها -->
        <div class="grid grid-cols-1 md:grid-cols-4  lg:grid-cols-4 py-4 gap-6 mt-2 mb-16">

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
                        <p> ورود به پنل</p>
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
                        <p> ورود به پنل</p>
                    </div>
                </a>

            </div>

              {{-- Sale --}}
            <div class="flex flex-col bg-white shadow-md w-[286px] rounded-[12px] ">
                <div>
                    <img src="{{ asset('assets/panels/sale.jpg') }}" alt=""
                        class="rounded-tr-[12px] rounded-tl-[12px]">
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
                        <p> ورود به پنل</p>
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
                        <p> ورود به پنل</p>
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
                        <p> ورود به پنل</p>
                    </div>
                </a>

            </div>





       

           

       





         
        </div>
    </main>



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