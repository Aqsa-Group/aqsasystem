<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فیت کلاب - سیستم مدیریت</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #2c3e50;
            --secondary: #e74c3c;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --text: #333;
            --text-light: #f8f9fa;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #1a2a3a, #2c3e50);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #e74c3c, #f39c12);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome-text {
            font-size: 1.2rem;
            color: #ecf0f1;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1rem;
            color: #bdc3c7;
            max-width: 600px;
            margin: 0 auto;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 100%;
            margin-bottom: 40px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 300px;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        .card-icon {
            width: 80px;
            height: 90px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 30px;
            color: white;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }

        .card:nth-child(2) .card-icon {
            background: var(--secondary);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }

        .card:nth-child(3) .card-icon {
            background: #2ecc71;
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: white;
        }

        .card-description {
            font-size: 0.95rem;
            color: #ecf0f1;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .card-button {
            display: inline-block;
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-top: auto;
        }

        .card-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }


        .logout-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .logout-button {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .logout-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }

        .logout-button:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.5);
        }

        .logout-button:hover::before {
            left: 100%;
        }

        .logout-button:active {
            transform: translateY(-1px) scale(1.05);
        }

        .logout-tooltip {
            position: absolute;
            top: 50%;
            right: 60px;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .logout-tooltip::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: rgba(0, 0, 0, 0.8);
        }

        .logout-button:hover+.logout-tooltip {
            opacity: 1;
            visibility: visible;
            right: 55px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #95a5a6;
            font-size: 0.9rem;
            padding: 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
        }

        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: 1fr;
            }

            .logo-text {
                font-size: 2rem;
            }

            .card {
                height: 280px;
            }
        }
    </style>
</head>

<body>

  

    <div class="logout-container">
        <form action="<?php echo e(route('gym.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="logout-button">
                <i class="fa-solid fa-power-off"></i>
            </button>
        </form>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo">

                <h1 class="logo-text">سیستم مدیریت کلپ ها</h1>
            </div>
            <p class="welcome-text">خوش آمدید به سیستم مدیریت باشگاه ورزشی</p>
            <p class="subtitle">برای دسترسی به بخش‌های مختلف سیستم، از کارت‌های زیر استفاده کنید</p>
        </div>

        <div class="cards-container">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 class="card-title">فروشات کانتین</h2>
                <p class="card-description">مدیریت و پیگیری فروش محصولات ورزشی و مکمل‌های غذایی</p>
                <a href="#" class="card-button">ورود به بخش فروش</a>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <h2 class="card-title">حسابداری کانتین</h2>
                <p class="card-description">سیستم حسابداری پیشرفته برای مدیریت مالی باشگاه</p>
                <a href="#" class="card-button">ورود به حسابداری</a>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="card-title">حسابداری کلپ</h2>
                <p class="card-description">گزارش‌های تحلیلی و آماری از عملکرد مالی کانتین</p>
                <a href="<?php echo e(route('gym.clubaccounting')); ?>" class="card-button">مشاهده گزارش‌ها</a>
            </div>
        </div>


    </div>
</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/Gym/components/homepage.blade.php ENDPATH**/ ?>