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
        :root {
            --primary: #1e3a8a;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
        }

        body {
            font-family: 'Vazirmatn', 'Mirza', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--light);
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

        .glow {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }

        .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 1.2em;
            background-color: #f59e0b;
            margin-right: 2px;
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
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

        .particle {
            position: absolute;
            background: rgba(59, 130, 246, 0.5);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            to {
                transform: translateY(-1000px) rotate(360deg);
            }
        }

        .footer-wave {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%230f172a" fill-opacity="1" d="M0,128L48,144C96,160,192,192,288,186.7C384,181,480,139,576,138.7C672,139,768,181,864,197.3C960,213,1056,203,1152,186.7C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
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
                class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center mr-3 glow">
                <img src="{{ asset('assets/icon.jpg') }}" class="rounded-full" alt="">
            </div>
            <h1 class="text-2xl font-bold">
                <span class="logo-text text-3xl yekan">اقصی گروپ</span>
            </h1>
        </div>

        <div class="text-2xl text-blue-200 times">
            Aqsasystem.com
        </div>
    </header>

    <!-- بخش اصلی -->
    <main class="flex-grow hero-section flex flex-col items-center justify-center py-2 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <!-- لوگو و عنوان -->
            <div class="mb-2 floating ">
                <div
                    class="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-amber-400 to-amber-600 flex items-center justify-center mb-4 glow">
                    <img src="{{ asset('assets/icon.jpg') }}" class="rounded-full" alt="">
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-2 yekan">
                    به <span class="logo-text ">اقصی گروپ</span> خوش آمدید
                </h2>
                <p class="text-xl text-blue-200 yekan"> <span class="times">Aqsasystem</span> - پلتفرم جامع مدیریت کسب و
                    کار</p>
            </div>

            <!-- متن خوش‌آمدگویی -->
            <div class="mb-12">
                <div
                    class="text-2xl md:text-3xl text-center mb-8 py-4 px-6 rounded-xl bg-gradient-to-r from-blue-900/30 to-cyan-900/30 border border-blue-800/30 inline-block">
                    <span id="typed-text"></span><span class="typing-cursor"></span>
                </div>
             
            </div>


        </div>
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





       

           

       





            {{--
            <!-- پنل کلپ -->

            <a href="/gym" class="card-hover rounded-2xl p-6 flex flex-col items-center text-center group">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-700 flex items-center justify-center mb-4 group-hover:glow transition-all">
                    <i class="fas fa-dumbbell text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">پنل کلپ های ورزشی</h3>
                <p class="text-blue-200 text-sm">مدیریت حسابداری و کانتین</p>
                <div class="mt-4 text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-left ml-1"></i> ورود به پنل
                </div>
            </a> --}}


            {{--
            <!-- پنل مارکت -->

            <a href="/update" class="card-hover rounded-2xl p-6 flex flex-col items-center text-center group">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-700 flex items-center justify-center mb-4 group-hover:glow transition-all">
                    <i class="fas fa-dumbbell text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">پنل مارکت آبدیت</h3>
                <p class="text-blue-200 text-sm">مدیریت مارکت ها </p>
                <div class="mt-4 text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-left ml-1"></i> ورود به پنل
                </div>
            </a> --}}
        </div>
    </main>

    <!-- فوتر -->
    <footer class="footer-wave py-12 text-center relative">
        <div class="max-w-4xl mx-auto px-4">
            <div class="mb-6">
                <h3 class="text-xl font-bold mb-4">اقصی گروپ - Aqsasystem</h3>
                <p class="text-blue-200 max-w-2xl mx-auto">
                    ارائه دهنده راهکارهای نوین مدیریت کسب و کار با بیش از ۵ سال تجربه در زمینه توسعه نرم‌افزارهای تخصصی
                </p>
            </div>

            <div class="flex justify-center space-x-6 space-x-reverse mb-6">
                <a href="#" class="text-blue-300 hover:text-amber-400 transition-colors">
                    <i class="fab fa-telegram text-2xl"></i>
                </a>
                <a href="#" class="text-blue-300 hover:text-amber-400 transition-colors">
                    <i class="fab fa-instagram text-2xl"></i>
                </a>
                <a href="#" class="text-blue-300 hover:text-amber-400 transition-colors">
                    <i class="fab fa-linkedin text-2xl"></i>
                </a>
                <a href="#" class="text-blue-300 hover:text-amber-400 transition-colors">
                    <i class="fab fa-twitter text-2xl"></i>
                </a>
            </div>

            <div class="text-sm text-blue-300">
                <p>تمامی حقوق برای اقصی گروپ محفوظ است © ۱۴۰۳</p>
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
        
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // اندازه تصادفی
                const size = Math.random() * 5 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // موقعیت تصادفی
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                
                // شفافیت تصادفی
                particle.style.opacity = Math.random() * 0.5 + 0.2;
                
                // مدت زمان انیمیشن تصادفی
                const duration = Math.random() * 20 + 10;
                particle.style.animationDuration = `${duration}s`;
                
                // تأخیر تصادفی
                particle.style.animationDelay = `${Math.random() * 5}s`;
                
                particlesContainer.appendChild(particle);
            }
        }
    </script>
</body>

</html>