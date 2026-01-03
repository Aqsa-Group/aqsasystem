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
                <p class="text-lg text-blue-100 max-w-2xl mx-auto">
                    سیستم یکپارچه مدیریت کسب و کار با امکانات کامل برای مدیریت مالی، فروش، صرافی و ابزارهای تخصصی
                </p>
            </div>


        </div>
        <!-- کارت‌های پنل‌ها -->
        <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-5 gap-6 mt-8 mb-16">
            <!-- پنل مدیریت مارکت -->
            <a href="/market" class="card-hover rounded-2xl p-6 flex flex-col items-center text-center group">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-blue-700 flex items-center justify-center mb-4 group-hover:glow transition-all">
                    <i class="fas fa-store text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">پنل مدیریت مارکت</h3>
                <p class="text-blue-200 text-sm">مدیریت کامل مارکت ها تجاری </p>
                <div class="mt-4 text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-left ml-1"></i> ورود به پنل
                </div>
            </a>

            <!-- پنل فروشات -->
            <a href="/import" class="card-hover rounded-2xl p-6 flex flex-col items-center text-center group">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-green-500 to-green-700 flex items-center justify-center mb-4 group-hover:glow transition-all">
                    <i class="fas fa-cart-shopping text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">پنل فروشات</h3>
                <p class="text-blue-200 text-sm">مدیریت سفارشات و تراکنش‌ها</p>
                <div class="mt-4 text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-left ml-1"></i> ورود به پنل
                </div>
            </a>

            <!-- پنل صرافی -->
            <a href="/sarafi" class="card-hover rounded-2xl p-6 flex flex-col items-center text-center group">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-500 to-purple-700 flex items-center justify-center mb-4 group-hover:glow transition-all">
                    <i class="fas fa-money-bill-transfer text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">پنل صرافی</h3>
                <p class="text-blue-200 text-sm">مدیریت ارز و تراکنش‌های مالی</p>
                <div class="mt-4 text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-left ml-1"></i> ورود به پنل
                </div>
            </a>

            <!-- پنل ابزارآلات -->
            <a href="/tools" class="card-hover rounded-2xl p-6 flex flex-col items-center text-center group">
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-orange-500 to-orange-700 flex items-center justify-center mb-4 group-hover:glow transition-all">
                    <i class="fas fa-screwdriver-wrench text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">پنل ابزارآلات</h3>
                <p class="text-blue-200 text-sm">ابزارهای تخصصی و کاربردی</p>
                <div class="mt-4 text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-left ml-1"></i> ورود به پنل
                </div>
            </a>

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
            </a>


                  <!-- پنل کلپ -->

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
            </a>
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