<!DOCTYPE html>
<html lang="{{ session('locale', config('app.locale')) }}" dir="{{ session('locale') === 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" name="csrf-token"
        content="{{ csrf_token() }}">
    <title>سیستم صرافی اقصی</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/aqsa.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {}
        }
    }
    </script>
    @include('Sarafi.layouts.links')
    <style>
        /* Chat box */

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #184D6C 0%, #1C274C 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .loader-container {
            text-align: center;
            animation: fadeInUp 1s ease;
        }

        .spinner-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
        }

        .dark {
            color: white;
        }

        .spinner {
            position: absolute;
            border: 4px solid transparent;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        .spinner-1 {
            width: 120px;
            height: 120px;
            border-top: 4px solid #122EE1;
            border-bottom: 4px solid #122EE1;
            animation-duration: 1.5s;
        }

        .spinner-2 {
            width: 100px;
            height: 100px;
            top: 10px;
            left: 10px;
            border-left: 4px solid #FF6B6B;
            border-right: 4px solid #FF6B6B;
            animation-duration: 2s;
            animation-direction: reverse;
        }

        .spinner-3 {
            width: 80px;
            height: 80px;
            top: 20px;
            left: 20px;
            border-top: 4px solid #4ECDC4;
            border-bottom: 4px solid #4ECDC4;
            animation-duration: 2.5s;
        }

        .logo-loader {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 30px;
            left: 30px;
            box-shadow: 0 0 20px rgba(18, 46, 225, 0.3);
        }

        .logo-loader span {
            font-size: 24px;
            font-weight: bold;
            color: #122EE1;
            font-family: 'Yekan', sans-serif;
        }

        .loader-text {
            color: white;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
            font-family: 'Vazir', sans-serif;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .loader-subtext {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-family: 'Vazir', sans-serif;
        }

        .progress-bar {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            margin: 20px auto 0;
            overflow: hidden;
        }

        .progress {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #122EE1, #4ECDC4);
            border-radius: 2px;
            animation: progress 3s ease-in-out infinite;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .element-1 {
            width: 20px;
            height: 20px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .element-2 {
            width: 15px;
            height: 15px;
            top: 60%;
            left: 80%;
            animation-delay: 1s;
        }

        .element-3 {
            width: 25px;
            height: 25px;
            top: 80%;
            left: 20%;
            animation-delay: 2s;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

    




        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loader-complete {
            opacity: 0;
            visibility: hidden;
        }

        /* افکت‌های اضافی */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* محتوای اصلی - اصلاح شده */
        #mainContent {
            display: none;
            opacity: 1;
        }

        .content-loaded {
            display: block;
            opacity: 1;
        }

        /* استایل‌های دارک مود */
        #toggleCircle {
            transition: transform 0.3s ease-in-out;
        }

        [dir="rtl"] #toggleCircle.move-dark {
            transform: translateX(-2rem);
        }

        [dir="ltr"] #toggleCircle.move-dark {
            transform: translateX(2rem);
        }

        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: black;
            color: #e2e8f0;
        }



        .dark header {
            background-color: black;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.4);
        }

        .dark #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .dark aside {
            background-color: #2d3748;
        }

        .dark input {
            background-color: #4a5568;
            color: #e2e8f0;
            border-color: #1a1b1e;
        }

        /* استایل‌های ریسپانسیو جدید */

        /* هدر ریسپانسیو */
        .header-container {
            position: static;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #fff;
        }


        @media (min-width: 768px) {
            .header-container {
                flex-direction: row;
                justify-content: space-between;
                position: sticky;

                align-items: center;
                padding: 0 1.5rem;
                height: 80px;
                width: 100%;
            }
        }

        /* لایه موبایل */
        .mobile-header-layout {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 1rem;
        }

        .mobile-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .mobile-header-bottom {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 0.5rem;
        }

        .mobile-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #122EE1;
        }

        .mobile-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mobile-search-full {
            flex: 1;
        }

        .mobile-tools {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (min-width: 768px) {
            .mobile-header-layout {
                display: none;
            }
        }

        /* لایه دسکتاپ */
        .desktop-header-layout {
            display: none;
            width: 100%;
            justify-content: space-between;
            align-items: center;
        }

        @media (min-width: 768px) {
            .desktop-header-layout {
                display: flex;
            }
        }

        .desktop-brand-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .desktop-actions-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* منوی همبرگری برای موبایل */
        .mobile-menu-btn {
            display: flex;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9997;
            background: #122EE1;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: #0e22b5;
            transform: scale(1.05);
        }

        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        /* سایدبار ریسپانسیو - کاملا اصلاح شده */
        .sidebar-container {
            position: fixed;
            top: 2px;
            right: -100%;
            height: 100vh;
            width: 280px;
            max-width: 80vw;
            background: #184D6C;
            z-index: 9999;
            transition: right 0.3s ease;
            overflow-y: auto;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }


        .sidebar-container.open {
            right: 0;
        }

        @media (min-width: 768px) {
            .sidebar-container {
                position: fixed;
                top: 10px;
                right: 0;
                width: 296px;
                height: 100vh;
                max-width: none;
                right: 0 !important;
                z-index: 1000;
                transform: none;
                box-shadow: -1px 4px 4px 0px rgba(37, 99, 235, 0.25);
                border-radius: 50px 0 0 0;
                overflow-y: auto;
                padding: 1.25rem;
            }
        }

        .dark .sidebar-container {
            background: #1f2937;
        }

        /* محتوای اصلی ریسپانسیو - اصلاح شده */
        .main-content-wrapper {
            margin-top: 1rem;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
            width: 100%;
        }

        @media (min-width: 768px) {
            .main-content-wrapper {
                margin-top: 2.5rem;
                padding: 0 1rem;
                margin-right: 296px;
                width: calc(100% - 296px);
            }
        }

        /* لایه overlay برای موبایل - اصلاح شده */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
            backdrop-filter: blur(2px);
        }

        .mobile-overlay.open {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (min-width: 768px) {
            .mobile-overlay {
                display: none !important;
            }
        }

        /* بهبود استایل‌های عمومی */
        .responsive-text {
            font-size: 1.5rem;
        }

        @media (min-width: 768px) {
            .responsive-text {
                font-size: 2.5rem;
            }
        }

        /* استایل برای دکمه‌های موبایل */
        .btn-mobile-small {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img-mobile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* استایل برای dropdown موبایل */
        .dropdown-mobile {
            width: 120px;
        }

        .dropdown-mobile button {
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        .dropdown-mobile img {
            width: 16px;
            height: 16px;
        }

        /* دارک مود موبایل */
        .dark-mode-toggle-mobile {
            width: 40px;
            height: 20px;
        }

        .dark-mode-toggle-mobile label {
            height: 20px;
            padding: 0 0.25rem;
        }

        .dark-mode-toggle-mobile span {
            width: 16px;
            height: 16px;
        }

        .dark-mode-toggle-mobile svg {
            width: 12px;
            height: 12px;
        }

        /* هدر ریسپانسیو پیشرفته */
        .header-master {
            position: fixed;
            /* ← همیشه ثابت */
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 9999;
            background: white;
            box-shadow: 0 4px 4px rgba(17, 41, 199, 0.4);
        }

        .dark .header-master {
            background: black;
        }

        eader-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        /* بخش برند */
        .brand-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
        }

        .brand-logo {
            display: none;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: bold;
            color: #122EE1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .dark .brand-text {
            color: white;
        }

        /* بخش جستجو */
        .search-section {
            flex: 1;
            min-width: 0;
            max-width: 400px;
            margin: 0 1rem;
        }

        .search-container {
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            border: 1px solid #8C8C8C;
            border-radius: 12px;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            font-family: 'Vazir', sans-serif;
            font-size: 0.875rem;
            background: white;
            outline: none;
            transition: all 0.3s ease;
        }

        .dark .search-input {
            background: #1f2937;
            border-color: #4b5563;
            color: white;
        }

        .search-input:focus {
            border-color: #122EE1;
            box-shadow: 0 0 0 2px rgba(18, 46, 225, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            pointer-events: none;
        }

        /* بخش ابزارها */
        .tools-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        /* دکمه اعلان */
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #E5E5E5;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .dark .notification-btn {
            background: #374151;
        }

        .notification-btn:hover {
            background: #d1d1d1;
            transform: scale(1.05);
        }

        .dark .notification-btn:hover {
            background: #4b5563;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 0.625rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* انتخاب زبان */
        .language-selector {
            position: relative;
            width: 100px;
            flex-shrink: 0;
        }

        .language-btn {
            width: 100%;
            border: 1px solid rgba(17, 41, 199, 0.4);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            color: #1129C7;
        }

        .dark .language-btn {
            background: #1f2937;
            border-color: #6b7280;
            color: white;
        }

        .language-btn:hover {
            border-color: #122EE1;
        }

        .language-btn img {
            width: 20px;
            height: 20px;
            margin-left: 0.5rem;
        }

        .language-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-top: 0.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }

        .dark .language-menu {
            background: #1f2937;
            border-color: #4b5563;
        }

        .language-menu.open {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .language-option {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: background 0.2s ease;
            font-size: 0.875rem;
            color: #374151;
        }

        .dark .language-option {
            color: #d1d5db;
        }

        .language-option:hover {
            background: #f3f4f6;
        }

        .dark .language-option:hover {
            background: #374151;
        }

        .language-option img {
            width: 20px;
            height: 20px;
            margin-left: 0.5rem;
        }

        /* دارک مود */
        .dark-mode-toggle {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .dark-mode-switch {
            position: relative;
            width: 56px;
            height: 28px;
        }

        .dark-mode-checkbox {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .dark-mode-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d5db;
            border-radius: 34px;
            transition: .4s;
        }

        .dark-mode-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            border-radius: 50%;
            transition: .4s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dark-mode-checkbox:checked+.dark-mode-slider {
            background-color: #122EE1;
        }

        .dark-mode-checkbox:checked+.dark-mode-slider:before {
            transform: translateX(28px);
        }

        /* پروفایل */
        .profile-section {
            position: relative;
        }

        .profile-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .dark .profile-btn {
            border-color: #4b5563;
        }

        .profile-btn:hover {
            border-color: #122EE1;
            transform: scale(1.05);
        }

        .profile-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 280px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            margin-top: 0.5rem;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.2s ease;
            overflow: hidden;
        }

        .dark .profile-menu {
            background: #1f2937;
        }

        .profile-menu.open {
            display: block;
        }

        .profile-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .dark .profile-header {
            border-bottom-color: #4b5563;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1rem;
            border: 3px solid #122EE1;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-weight: 600;
            font-size: 1.125rem;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .dark .profile-name {
            color: white;
        }

        .profile-role {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .profile-links {
            padding: 0.75rem;
        }

        .profile-link {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            color: #4b5563;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .dark .profile-link {
            color: #d1d5db;
        }

        .profile-link:hover {
            background: #f3f4f6;
            color: #122EE1;
        }

        .dark .profile-link:hover {
            background: #374151;
        }

        .profile-link img {
            width: 20px;
            height: 20px;
            margin-left: 0.75rem;
        }

        .profile-logout {
            border-top: 1px solid #e5e7eb;
            padding: 0.75rem;
        }

        .dark .profile-logout {
            border-top-color: #4b5563;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem;
            background: #fef2f2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dark .logout-btn {
            background: rgba(220, 38, 38, 0.1);
            color: #fca5a5;
        }

        .logout-btn:hover {
            background: #fee2e2;
        }

        .dark .logout-btn:hover {
            background: rgba(220, 38, 38, 0.2);
        }

        .logout-btn img {
            width: 20px;
            height: 20px;
            margin-left: 0.5rem;
        }

        /* دکمه منوی موبایل */
        .mobile-menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #122EE1;
            border: none;
            color: white;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .mobile-menu-toggle:hover {
            background: #0e22b5;
            transform: scale(1.05);
        }

        .mobile-menu-toggle svg {
            width: 24px;
            height: 24px;
        }

        /* انیمیشن‌ها */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* رسپانسیو */
        @media (max-width: 1024px) {
            .brand-text {
                max-width: 120px;
                font-size: 1.125rem;
            }

            .search-section {
                max-width: 300px;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 0.5rem;
                gap: 0.5rem;
            }

            .mobile-menu-toggle {
                display: flex;
                order: 1;
            }

            .brand-section {
                order: 2;
                flex: 1;
                justify-content: center;
            }

            .brand-text {
                max-width: none;
                font-size: 1.125rem;
                text-align: center;
            }

            .search-section {
                position: fixed;
                top: 70px;
                left: 1rem;
                right: 1rem;
                max-width: none;
                margin: 0;
                z-index: 999;
                display: none;
            }

            .search-section.active {
                display: block;
                animation: slideDown 0.3s ease;
            }

            .search-toggle {
                display: flex;
                order: 3;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #E5E5E5;
                align-items: center;
                justify-content: center;
                border: none;
                cursor: pointer;
                flex-shrink: 0;
            }

            .dark .search-toggle {
                background: #374151;
            }

            .tools-section {
                order: 4;
                gap: 0.5rem;
            }

            .notification-btn {
                width: 36px;
                height: 36px;
            }

            .language-selector {
                width: 80px;
            }

            .language-btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }

            .language-btn img {
                width: 16px;
                height: 16px;
                margin-left: 0.25rem;
            }

            .profile-btn {
                width: 36px;
                height: 36px;
            }

            .profile-menu {
                position: fixed;
                top: 70px;
                left: 1rem;
                right: 1rem;
                width: auto;
                max-width: 300px;
                margin: 0 auto;
            }

            /* اصلاحات مهم برای موبایل */
            .main-content-wrapper {
                margin-right: 0 !important;
                width: 100% !important;
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .brand-text {
                font-size: 1rem;
            }

            .language-selector {
                display: none;
            }

            .dark-mode-toggle {
                display: none;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* استایل‌های اضافی برای مدیریت منوی موبایل */
        body.menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
        }

        @media (max-width: 767px) {
            .main-content-wrapper {
                position: relative;
                z-index: 1;
                transition: transform 0.3s ease;
            }

            body.menu-open .main-content-wrapper {
                transform: translateX(-20px);
            }
        }

        /* بهبود نمایش در موبایل */
        @media (max-width: 767px) {
            main {
                padding-top: 1rem;
                padding-bottom: 4rem;
            }

            /* دکمه چت در موبایل */
            #chatWidget {
                bottom: 5rem !important;
                right: 1rem;
                z-index: 9995;
            }
        }

        /* استایل برای جلوگیری از نمایش محتوا زیر سایدبار */
        @media (max-width: 767px) {
            #mainContent {
                position: relative;
                min-height: 100vh;
            }

            .dark #mainContent {
                background: black;
            }

            /* محتوای اصلی در موبایل باید قابل کلیک نباشد وقتی منو باز است */
            body.menu-open .main-content-wrapper {
                pointer-events: none;
            }

            body.menu-open .main-content-wrapper::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: transparent;
                z-index: 9996;
            }
        }

        #mainContent {
            width: 100%;
            min-height: 100vh;
            position: relative;
            transition: margin-right 0.3s ease;
        }

        @media (min-width: 768px) {
            #mainContent {
                margin-right: 296px;
                width: calc(100% - 296px);
            }
        }

        @media (max-width: 767px) {
            #mainContent {
                margin-right: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="vazir dark:text-white overflow-x-hidden">
    <header class="bg-white w-full py-4 md:py-0 md:h-[80px] flex  px-14
            shadow-[0_4px_4px_rgba(37,99,235,0.25)]
            dark:shadow-[0_4px_4px_rgba(255,255,255,0.5)]">
        <div class="mobile-header-layout">



            <div class="mobile-header-bottom">

                <!-- جستجو -->
                <div class="mobile-search-full">
                    <div class="relative">
                        <input type="text" placeholder="{{ __('messages.search_placeholder') }}"
                            class="border border-[#8C8C8C] placeholder:text-black vazir rounded-full px-3 py-2 pr-10 text-right font-vazir focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm">
                        <img src="{{ asset('assets/sarafi/all_icon/search-normal.png') }}" alt=""
                            class="h-4 w-4 absolute left-2 bottom-3">
                    </div>
                </div>

                <!-- زبان و دارک مود -->
                <div class="mobile-tools">
                    @php $locale = session('locale', config('app.locale')); @endphp
                    <div class="relative dropdown-mobile vazir">
                        <button id="dropdownButtonMobile"
                            class="border border-[#1129C766] bg-white rounded-lg px-2 py-1 w-full flex items-center justify-between font-vazir text-xs text-[#1129C7]">
                            <img src="{{ $locale === 'en' ? asset('assets/sarafi/all_icon/united.png') : asset('assets/sarafi/all_icon/Flags.png') }}"
                                class="w-4 h-4 ml-1" alt="Lang">
                            <span>
                                @if ($locale === 'fa') فارسی
                                @elseif($locale === 'ps') پشتو
                                @else EN
                                @endif
                            </span>
                        </button>

                        <ul id="dropdownMenuMobile"
                            class="absolute left-5 mt-1 w-full bg-white border border-gray-200 rounded-lg hidden z-10">
                            <li><a href="{{ route('set-locale', 'fa') }}"
                                    class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                        src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-4 h-4 ml-1"
                                        alt="fa">
                                    فارسی</a></li>
                            <li><a href="{{ route('set-locale', 'ps') }}"
                                    class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                        src="{{ asset('assets/sarafi/all_icon/Flags.png') }}" class="w-4 h-4 ml-1"
                                        alt="ps">
                                    پشتو</a></li>
                            <li><a href="{{ route('set-locale', 'en') }}"
                                    class="locale-link flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer text-xs"><img
                                        src="{{ asset('assets/sarafi/all_icon/united.png') }}" class="w-4 h-4 ml-1"
                                        alt="en"> English</a></li>
                        </ul>
                    </div>

                    {{--
                    <!-- سوییچ دارک مود -->
                    <div class="relative dark-mode-toggle-mobile">
                        <input type="checkbox" id="darkModeToggleMobile" class="sr-only">
                        <label for="darkModeToggleMobile"
                            class="flex items-center w-full h-full bg-gray-300 rounded-full cursor-pointer transition-colors duration-300 ease-in-out dark:bg-gray-700 px-1">
                            <span id="toggleCircleMobile"
                                class="flex items-center justify-center bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out">
                                <!-- آیکون خورشید -->
                                <svg id="sunIconMobile" class="text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <!-- آیکون ماه -->
                                <svg id="moonIconMobile" class="text-blue-300 hidden" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                                    </path>
                                </svg>
                            </span>
                        </label>
                    </div> --}}
                </div>

                <div class="mobile-header-top flex justify-between  gap-10  space-x-10  w-full items-center ">

                    <!-- اعلان و پروفایل -->
                    <div class="mobile-actions ">
                        <livewire:sarafi.bell />
                        <div class="header-profile-section">
                            <div class="relative">
                                <div id="profileBtnMobile"
                                    class="profile-img-mobile border overflow-hidden flex items-center justify-center cursor-pointer transition">
                                    <img src="{{ asset('assets/sarafi/all_icon/man.png') }}" alt="پروفایل"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- لایه دسکتاپ -->
        <div class="desktop-header-layout">

            <div class="desktop-brand-section">
            </div>

            <!-- سرچ، اعلان، پروفایل -->
            <div class="desktop-actions-section">


                <div class="flex justify-center items-center  pl-40">
                    <!-- سوییچ دارک مود -->
                    <div class=" shadow-md shadow-[#8eabe8] relative inline-block w-[98px] h-[32px] rounded-[8px] mx-4">

                        <input type="checkbox" id="darkModeToggle" class="sr-only">

                        <label for="darkModeToggle"
                            class="flex items-center w-full h-8 bg-white dark:bg-gray-700 rounded-full cursor-pointer px-1 transition-colors duration-300">

                            <span id="toggleCircle"
                                class="flex items-center justify-center w-6 h-6 bg-white rounded-full transition-transform duration-300 translate-x-0">

                                <!-- Sun -->
                                <svg id="sunIcon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.28451 10.3333C7.10026 10.8546 7 11.4156 7 12C7 14.7614 9.23858 17 12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7C11.4156 7 10.8546 7.10026 10.3333 7.28451"
                                        stroke="#EBA925" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M12 2V4" stroke="#EBA925" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M12 20V22" stroke="#EBA925" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M4 12L2 12" stroke="#EBA925" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M22 12L20 12" stroke="#EBA925" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M19.7773 4.22217L17.5553 6.25375" stroke="#EBA925" stroke-width="1.5" />
                                    <path d="M4.22266 4.22217L6.44467 6.25375" stroke="#EBA925" stroke-width="1.5" />
                                    <path d="M6.44434 17.5557L4.22211 19.7779" stroke="#EBA925" stroke-width="1.5" />
                                    <path d="M19.7773 19.7778L17.5553 17.5555" stroke="#EBA925" stroke-width="1.5" />
                                </svg>

                                <!-- Moon -->
                                <svg id="moonIcon" class="hidden" width="24" height="24" viewBox="0 0 24 24"
                                    fill="#2563EB">
                                    <path d="M12 2a10 10 0 1 0 10 10A8 8 0 0 1 12 2Z" />
                                </svg>

                            </span>
                        </label>
                    </div>

                    @php $locale = session('locale', config('app.locale')); @endphp

                    <div class="relative inline-block w-[145px] h-[56px] p-2 vazir">
                        <!-- دکمه اصلی -->
                        <button id="dropdownButton"
                            class="border vazir dark:text-white dark:bg-black dark:border-white
               bg-[#184D6C] rounded-lg px-3 py-2 w-full flex items-center justify-between font-vazir text-[14px] text-white">

                            <!-- نام زبان -->
                            <span>
                                @if ($locale === 'fa') فارسی
                                @elseif($locale === 'ps') پشتو
                                @else English
                                @endif
                            </span>

                            <!-- فلش پایین بجای پرچم -->
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="block dark:hidden">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" stroke="white" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <svg width="24" height="24" class="hidden dark:block" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.9181 8.94995L13.3981 15.47C12.6281 16.24 11.3681 16.24 10.5981 15.47L4.07812 8.94995"
                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>

                        <!-- منوی Dropdown -->
                        <ul id="dropdownMenu" class="absolute left-0 mt-1 w-full dark:text-white dark:bg-black bg-[#184D6C]
               border border-gray-200 dark:border-white rounded-lg hidden z-10">
                            <!-- فارسی -->
                            <li>
                                <a href="{{ route('set-locale', 'fa') }}"
                                    class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                                    <span class="mr-2 text-white hover:text-black">فارسی</span>
                                </a>
                            </li>
                            <!-- پشتو -->
                            <li>
                                <a href="{{ route('set-locale', 'ps') }}"
                                    class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                                    <span class="mr-2 text-white hover:text-black">پشتو</span>
                                </a>
                            </li>
                            <!-- انگلیسی -->
                            <li>
                                <a href="{{ route('set-locale', 'en') }}"
                                    class="locale-link flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 cursor-pointer">
                                    <span class="mr-2 text-white hover:text-black">انگلیسی</span>
                                </a>
                            </li>
                        </ul>
                    </div>


                    <div class="header-search-section">
                        <div class="relative" x-data="customerSearch()" x-init="init()" x-cloak>

                            <input type="text" x-model="searchQuery" @input.debounce.500ms="performSearch"
                                placeholder="جستجو ......."
                                class="border placeholder:text-[#8C8C8C] vazir justify-center items-center  border-[#DBDBDB] dark:border-[#FFFFFF] dark:bg-black dark:placeholder:text-white  vazir rounded-[16px] w-[290px] h-[42px] pr-10 text-right font-vazir outline-none ">

                            <svg class=" flex text-center items-center  w-6 h-6 justify-center absolute right-3 bottom-2"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.5586 17.4287C20.1468 18.0169 20.1468 18.9704 19.5586 19.5586C18.9704 20.1468 18.0169 20.1468 17.4287 19.5586L16 18.1299C16.8315 17.5539 17.5539 16.8315 18.1299 16L19.5586 17.4287ZM11 4C14.866 4 18 7.13401 18 11C18 14.866 14.866 18 11 18C7.13401 18 4 14.866 4 11C4 7.13401 7.13401 4 11 4ZM11 5.13477C7.76092 5.13477 5.13477 7.76092 5.13477 11C5.13477 14.2391 7.76092 16.8652 11 16.8652C14.2391 16.8652 16.8652 14.2391 16.8652 11C16.8652 7.76092 14.2391 5.13477 11 5.13477Z"
                                    fill="#8C8C8C" />
                            </svg>



                            <!-- آیکون لودینگ -->
                            <div x-show="isLoading" class="absolute left-10 bottom-3">
                                <div
                                    class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin">
                                </div>
                            </div>

                            <!-- نتایج جستجو -->
                            <div x-show="showResults && results.length > 0" @click.outside="closeResults"
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">

                                <div class="p-2">
                                    <template x-for="customer in results" :key="customer.id">
                                        <div class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b last:border-b-0"
                                            @click="handleCustomerClick(customer)">

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <!-- عکس مشتری -->
                                                    <div
                                                        class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                                        <template x-if="customer.image">
                                                            <img :src="getImageUrl(customer.image)"
                                                                :alt="customer.fullname"
                                                                class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="!customer.image">
                                                            <span class="text-gray-600 text-sm font-bold"
                                                                x-text="getFirstLetter(customer.fullname)"></span>
                                                        </template>
                                                    </div>

                                                    <!-- اطلاعات مشتری -->
                                                    <div>
                                                        <div class="font-medium" x-text="customer.fullname"></div>
                                                        <div class="text-xs text-gray-500">
                                                            <span class="mx-1"></span>
                                                            <span class="dir-ltr"
                                                                x-text="customer.account_number"></span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- وضعیت و دکمه عمل -->
                                                <div class="text-xs flex items-center gap-2">
                                                    <template x-if="customer.is_mine">
                                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">دیدن
                                                            مشتری
                                                        </span>
                                                    </template>
                                                    <template x-if="!customer.is_mine && customer.admin_id">
                                                        <button @click.stop="linkCustomer(customer)"
                                                            class="bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200 transition">
                                                            افزدون به مشتریان
                                                        </button>
                                                    </template>
                                                    <template x-if="!customer.admin_id">
                                                        <button @click.stop="linkCustomer(customer)"
                                                            class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-200 transition">
                                                            استفاده مشتری
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- مودال تایید -->
                            <div x-show="showConfirmModal"
                                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4">

                                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">

                                    <!-- هدر مودال -->
                                    <div class="flex justify-between items-center p-6 border-b">
                                        <h3 class="text-xl font-bold text-gray-800">لینک کردن مشتری</h3>
                                        <button @click="showConfirmModal = false"
                                            class="text-gray-500 hover:text-gray-700 text-2xl">
                                            ✕
                                        </button>
                                    </div>

                                    <!-- بدنه مودال -->
                                    <div class="p-6" x-show="selectedCustomer">
                                        <div class="mb-6">
                                            <div class="flex items-center gap-4 mb-4">
                                                <template x-if="selectedCustomer.image">
                                                    <img :src="getImageUrl(selectedCustomer.image)"
                                                        :alt="selectedCustomer.fullname"
                                                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                                </template>
                                                <template x-if="!selectedCustomer.image">
                                                    <div
                                                        class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center border-2 border-gray-200">
                                                        <span class="text-blue-600 text-xl font-bold"
                                                            x-text="getFirstLetter(selectedCustomer.fullname)"></span>
                                                    </div>
                                                </template>

                                                <div>
                                                    <h4 class="text-lg font-bold text-gray-800"
                                                        x-text="selectedCustomer.fullname"></h4>
                                                    <p class="text-gray-600" x-text="selectedCustomer.phone"></p>
                                                    <p class="text-gray-500 text-sm dir-ltr"
                                                        x-text="selectedCustomer.account_number"></p>
                                                </div>
                                            </div>

                                            <div class="p-4 bg-blue-50 rounded-lg">
                                                <p class="text-blue-700 text-sm">
                                                    آیا می‌خواهید این مشتری را به لیست مشتریان خود اضافه کنید؟
                                                    <br>

                                                </p>
                                            </div>
                                        </div>

                                        <!-- دکمه‌ها -->
                                        <div class="flex gap-3">
                                            <button @click="confirmLinkCustomer"
                                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                                <svg x-show="!isLinking" class="w-5 h-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                <div x-show="isLinking"
                                                    class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin">
                                                </div>
                                                <span x-text="isLinking ? 'در حال لینک...' : 'بله، لینک کن'"></span>
                                            </button>

                                            <button @click="showConfirmModal = false" :disabled="isLinking"
                                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 rounded-lg font-medium transition-colors disabled:opacity-50">
                                                انصراف
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <script>
                    function customerSearch() {
                                        return {
                                            searchQuery: '',
                                            results: [],
                                            showResults: false,
                                            isLoading: false,
                                            selectedCustomer: null,
                                            showConfirmModal: false,
                                            isLinking: false,
                                            
                                            init() {
                                                console.log('Customer search initialized');
                                            },
                                            
                                            async performSearch() {
                                                if (this.searchQuery.length < 2) {
                                                    this.showResults = false;
                                                    this.results = [];
                                                    return;
                                                }
                                                
                                                this.isLoading = true;
                                                
                                                try {
                                                    const response = await fetch(`{{ route('api.search-customers') }}?q=${encodeURIComponent(this.searchQuery)}`, {
                                                        headers: {
                                                            'X-Requested-With': 'XMLHttpRequest',
                                                            'Accept': 'application/json'
                                                        }
                                                    });
                                                    
                                                    const data = await response.json();
                                                    
                                                    if (data.error) {
                                                        console.error('Search error:', data.error);
                                                        return;
                                                    }
                                                    
                                                    if (data.customers && data.customers.length > 0) {
                                                        this.results = data.customers;
                                                        this.showResults = true;
                                                    } else {
                                                        this.results = [];
                                                        this.showResults = false;
                                                    }
                                                    
                                                } catch (error) {
                                                    console.error('Search error:', error);
                                                } finally {
                                                    this.isLoading = false;
                                                }
                                            },
                                            
                                            closeResults() {
                                                this.showResults = false;
                                            },
                                            
                                            getImageUrl(imagePath) {
                                                return imagePath ? `/storage/${imagePath}` : '';
                                            },
                                            
                                            getFirstLetter(name) {
                                                return name ? name.charAt(0).toUpperCase() : '?';
                                            },
                                            
                                            handleCustomerClick(customer) {
                                                if (customer.is_mine) {
                                                    // اگر مشتری مال خودتان است، به صفحه مشتری بروید
                                                    window.location.href = `{{ route('sarafi.customer-table') }}?customer=${customer.id}`;
                                                } else {
                                                    // در غیر این صورت، مودال لینک را نشان دهید
                                                    this.selectedCustomer = customer;
                                                    this.showConfirmModal = true;
                                                    this.showResults = false;
                                                }
                                            },
                                            
                                            linkCustomer(customer) {
                                                this.selectedCustomer = customer;
                                                this.showConfirmModal = true;
                                                this.showResults = false;
                                            },
                                            
                                            async confirmLinkCustomer() {
                                                if (!this.selectedCustomer) return;
                                                
                                                this.isLinking = true;
                                                
                                                try {
                                                    const response = await fetch('{{ route("api.link-customer") }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify({ 
                                                            customer_id: this.selectedCustomer.id 
                                                        })
                                                    });
                                                    
                                                    const data = await response.json();
                                                    
                                                    if (data.success) {
                                                        // نمایش پیام موفقیت
                                                        alert(data.message);
                                                        
                                                        // بستن مودال و پاک کردن جستجو
                                                        this.showConfirmModal = false;
                                                        this.searchQuery = '';
                                                        this.results = [];
                                                        
                                                        // ریدایرکت به صفحه مشتریان یا رفرش
                                                        setTimeout(() => {
                                                            window.location.href = '{{ route("sarafi.customer-table") }}';
                                                        }, 1000);
                                                        
                                                    } else {
                                                        alert(data.message);
                                                        this.showConfirmModal = false;
                                                    }
                                                    
                                                } catch (error) {
                                                    console.error('Link error:', error);
                                                    alert('خطا در لینک کردن مشتری');
                                                } finally {
                                                    this.isLinking = false;
                                                }
                                            }
                                        };
                                    }
                </script>

                <livewire:sarafi.bell />

                <div class="header-profile-section">
                    <div class="relative">
                        @php
                        $currentUser = Auth::guard('sarafi')->user();
                        @endphp

                        <div id="profileBtnDesktop"
                            class="w-[32px] h-[30px] md:w-[60px] md:h-[60px] rounded-full overflow-hidden flex items-center justify-center cursor-pointer transition border-2 border-gray-200 hover:border-blue-500">
                            @if($currentUser->user_image)
                            <img src="{{ asset('storage/' . $currentUser->user_image) }}" alt="{{ $currentUser->name }}"
                                class="w-full h-full object-cover">
                            @else
                            <img src="{{ asset('assets/sarafi/avatar.svg') }}" alt="پروفایل"
                                class="w-full h-full object-cover">
                            @endif
                        </div>

                        <!-- منو dropdown -->
                        <div id="profileDropdownDesktop" style="box-shadow: 0px 4px 4px 0px #00000040, 0 0 0 0 #3B82F6;"
                            class="absolute top-full left-0 space-y-3 text-2xl w-72 h-76 bg-white rounded-lg shadow-lg border hidden z-50 p-4">

                            <div class="p-3 border-b space-y-5">
                                <div class="flex flex-col justify-center items-center">
                                    @if($currentUser->user_image)
                                    <img src="{{ asset('storage/' . $currentUser->user_image) }}"
                                        alt="{{ $currentUser->name }}"
                                        class="h-20 w-20 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                    <img src="{{ asset('assets/sarafi/avatar.svg') }}" alt="{{ $currentUser->name }}"
                                        class="h-20 w-20 rounded-full">
                                    @endif
                                    <p class="font-vazir font-semibold text-gray-700 mt-3">{{ $currentUser->name
                                        }}</p>
                                    <p class="font-vazir text-sm text-gray-500">
                                        @php
                                        $roles = [
                                        'superadmin' => 'سوپر ادمین',
                                        'admin' => 'ادمین',
                                        'warehouse_manager' => 'خزانه دار',
                                        'internal_officer' => 'مسوول احواله جات داخلی',
                                        'external_officer' => 'مسوول احواله جات خارجی',
                                        ];
                                        @endphp
                                        {{ $roles[$currentUser->role] ?? $currentUser->role }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <a href="{{ route('sarafi.users') }}"
                                    class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition vazir">
                                    <img src="{{ asset('assets/sarafi/all_icon/account_profile.svg') }}" alt="تنظیمات"
                                        class="ml-2">
                                    <span>تنظیمات پروفایل</span>
                                </a>

                                <a href="{{ route('sarafi.users') . '#edit=' . $currentUser->id }}"
                                    class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition vazir">
                                    <img src="{{ asset('assets/sarafi/all_icon/edit.svg') }}" alt="ویرایش" class="ml-2">
                                    <span>ویرایش اطلاعات</span>
                                </a>
                            </div>

                            <form action="{{ route('sarafi.logout') }}" method="POST" class="pt-2 border-t">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition vazir">
                                    <img src="{{ asset('assets/sarafi/all_icon/logout.svg') }}" alt="خروج" class="ml-2">
                                    <span>خروج از حساب</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- لودر فوق العاده زیبا -->
    <div id="loader" class="loading">
        <div class="floating-elements">
            <div class="floating-element element-1"></div>
            <div class="floating-element element-2"></div>
            <div class="floating-element element-3"></div>
        </div>

        <div class="loader-container pulse">
            <div class="spinner-wrapper">
                <div class="spinner spinner-1"></div>
                <div class="spinner spinner-2"></div>
                <div class="spinner spinner-3"></div>
                <div class="logo-loader">
                    <span>{{ mb_substr(Auth::guard('sarafi')->user()->sarafi_name, 0, 1) }}</span>
                </div>
            </div>

            <div class="loader-text">صرافــی {{ Auth::guard('sarafi')->user()->sarafi_name }}</div>
            <div class="loader-subtext">در حال بارگذاری...</div>

            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div id="mainContent">
        <!-- دکمه منوی موبایل -->
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- لایه overlay برای موبایل -->
        <div class="mobile-overlay" id="mobileOverlay"></div>


        <div class="flex flex-col md:flex-row mt-4 min-h-screen dark:text-white dark:bg-black">
            <!-- سایدبار -->
            <div id="sidebar" class="sidebar-container rounded-tl-[50px] bg-[#184D6C] w-[296px] h-screen shadow-[0_4px_4px_rgba(37,99,235,0.25)]
dark:shadow-[0_4px_4px_rgba(255,255,255,0.5)]">
                <div class="responsive-text text-center mb-6 dark:text-white text-white font-bold yekan"> {{
                    Auth::guard('sarafi')->user()->sarafi_name }} </div>
                <nav class="mt-0 vazir space-y-2 dark:text-white" x-data="{
                    openItems: {
                        customers: false,
                        accounts: false,
                        bankFiles: false,
                        editAccounts: false,
                        reports: false,
                        transactions: false,
                        deletedTransactions: false,
                        management: false,
                        sms: false,
                        notifications: false,
                        support: false,
                        settings: false
                    },
                    active: 'dashboard',
                    
                    setActive(item, parent = null) {
                        this.active = item;
                        if (parent && this.openItems.hasOwnProperty(parent)) {
                            this.openItems[parent] = true;
                        }
                        if (window.innerWidth < 768) {
                            document.getElementById('sidebar').classList.remove('open');
                            document.getElementById('mobileOverlay').classList.remove('open');
                        }
                    }
                }">
                    <!-- داشبورد -->
                    <a href="{{ route('sarafi.home') }}"
                        class="nav-link flex items-center justify-between py-3 px-4 rounded-lg transition vazir"
                        @click="active = 'dashboard'"
                        :class="active === 'dashboard' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white dark:hover:bg-gray-800 hover:bg-gray-600'">
                        <span class="flex items-center gap-2">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">

                                <path
                                    d="M2.55078 4.5C2.61472 3.84994 2.75923 3.41238 3.08582 3.08579C3.67161 2.5 4.61442 2.5 6.50004 2.5C8.38565 2.5 9.32846 2.5 9.91425 3.08579C10.5 3.67157 10.5 4.61438 10.5 6.5C10.5 8.38562 10.5 9.32843 9.91425 9.91421C9.32846 10.5 8.38565 10.5 6.50004 10.5C4.61442 10.5 3.67161 10.5 3.08582 9.91421C2.77645 9.60484 2.63047 9.19589 2.56158 8.60106"
                                    :stroke="active === 'dashboard' ? '#000000' : '#FFFFFF'" stroke-width="1.5"
                                    stroke-linecap="round" />

                                <path
                                    d="M21.4493 15.5C21.3853 14.8499 21.2408 14.4124 20.9142 14.0858C20.3284 13.5 19.3856 13.5 17.5 13.5C15.6144 13.5 14.6716 13.5 14.0858 14.0858C13.5 14.6716 13.5 15.6144 13.5 17.5C13.5 19.3856 13.5 20.3284 14.0858 20.9142C14.6716 21.5 15.6144 21.5 17.5 21.5C19.3856 21.5 20.3284 21.5 20.9142 20.9142C21.2408 20.5876 21.3853 20.1501 21.4493 19.5"
                                    :stroke="active === 'dashboard' ? '#000000' : '#FFFFFF'" stroke-width="1.5"
                                    stroke-linecap="round" />

                                <path
                                    d="M2.5 17.5C2.5 15.6144 2.5 14.6716 3.08579 14.0858C3.67157 13.5 4.61438 13.5 6.5 13.5C8.38562 13.5 9.32843 13.5 9.91421 14.0858C10.5 14.6716 10.5 15.6144 10.5 17.5C10.5 19.3856 10.5 20.3284 9.91421 20.9142C9.32843 21.5 8.38562 21.5 6.5 21.5C4.61438 21.5 3.67157 21.5 3.08579 20.9142C2.5 20.3284 2.5 19.3856 2.5 17.5Z"
                                    :stroke="active === 'dashboard' ? '#000000' : '#FFFFFF'" stroke-width="1.5" />

                                <path
                                    d="M13.5 6.5C13.5 4.61438 13.5 3.67157 14.0858 3.08579C14.6716 2.5 15.6144 2.5 17.5 2.5C19.3856 2.5 20.3284 2.5 20.9142 3.08579C21.5 3.67157 21.5 4.61438 21.5 6.5C21.5 8.38562 21.5 9.32843 20.9142 9.91421C20.3284 10.5 19.3856 10.5 17.5 10.5C15.6144 10.5 14.6716 10.5 14.0858 9.91421C13.5 9.32843 13.5 8.38562 13.5 6.5Z"
                                    :stroke="active === 'dashboard' ? '#000000' : '#FFFFFF'" stroke-width="1.5" />
                            </svg>





                            {{ __('messages.dashboard') }}
                        </span>
                    </a>







                    <!-- مشتریان -->
                    <div>
                        <button @click="openItems.customers = !openItems.customers; active = 'customers'"
                            :class="(active === 'customers' || active === 'customer-create' || active === 'customer-table') ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:hover:bg-gray-800 dark:text-white hover:bg-gray-600'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2 " style="font-weight: 400">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    :class="active === 'customers' ? 'text-black' : 'text-white'">

                                    <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />

                                    <path d="M18 9C19.6569 9 21 7.88071 21 6.5C21 5.11929 19.6569 4 18 4"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />

                                    <path d="M6 9C4.34315 9 3 7.88071 3 6.5C3 5.11929 4.34315 4 6 4"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />

                                    <path
                                        d="M17.1973 15C17.7078 15.5883 18 16.2714 18 17C18 19.2091 15.3137 21 12 21C8.68629 21 6 19.2091 6 17C6 14.7909 8.68629 13 12 13C12.3407 13 12.6748 13.0189 13 13.0553"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />

                                    <path d="M20 19C21.7542 18.6153 23 17.6411 23 16.5C23 15.3589 21.7542 14.3847 20 14"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />

                                    <path d="M4 19C2.24575 18.6153 1 17.6411 1 16.5C1 15.3589 2.24575 14.3847 4 14"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>




                                {{ __('messages.customers') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'customers' || active === 'control-customers')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>
                        <div x-show="openItems.customers" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.customer-create') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-create', 'customers')"
                                :class="active === 'customer-create' ? 'bg-[#122EE1] text-white' : 'text-white dark:hover:bg-gray-800 dark:text-white hover:bg-gray-600'">
                                <i class="fa-solid fa-user-pen w-4 h-4 text-white"
                                    :class="active === 'customer-create' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_create') }}
                            </a>

                            <a href="{{ route('sarafi.customer-table') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('customer-table', 'customers')"
                                :class="active === 'customer-table' ? 'bg-[#122EE1] text-white' : 'text-white dark:hover:bg-gray-800 dark:text-white hover:bg-gray-600'">
                                <i class="fa-solid fa-users-gear h-4 w-4 text-white"
                                    :class="active === 'customer-table' ? 'filter invert brightness-0' : 'text-gray-500'"></i>
                                {{ __('messages.customer_list') }}
                            </a>
                        </div>
                    </div>


                    <!-- ثبت و مدیریت معاملات -->
                    <div x-data="{
                        openItems: {
                            transaction: false,
                            },
                            active: ''
                        }">

                        <!-- دکمه اصلی -->
                        <button @click="openItems.transaction = !openItems.transaction; active = 'transaction'" :class="active === 'transaction'
            ?'bg-[#FFFFFF] text-[#184D6C]'
            : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">

                            <span class="flex items-center gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 12C2 16.714 2 19.0711 3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12V10.5M13.5 2H12C7.28595 2 4.92893 2 3.46447 3.46447C2.49073 4.43821 2.16444 5.80655 2.0551 8"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M16.652 3.45506L17.3009 2.80624C18.3759 1.73125 20.1188 1.73125 21.1938 2.80624C22.2687 3.88124 22.2687 5.62415 21.1938 6.69914L20.5449 7.34795M16.652 3.45506C16.652 3.45506 16.7331 4.83379 17.9497 6.05032C19.1662 7.26685 20.5449 7.34795 20.5449 7.34795M16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9M20.5449 7.34795L17.5625 10.3304M14.5801 13.3128C14.1761 13.7168 13.9741 13.9188 13.7513 14.0926C13.4886 14.2975 13.2043 14.4732 12.9035 14.6166C12.6485 14.7381 12.3775 14.8284 11.8354 15.0091L10.1 15.5876M10.1 15.5876L8.97709 15.9619C8.71035 16.0508 8.41626 15.9814 8.21744 15.7826C8.01862 15.5837 7.9492 15.2897 8.03811 15.0229L8.41242 13.9M10.1 15.5876L8.41242 13.9"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>



                                <span>ثبت و مدیریت معاملات</span>
                            </span>

                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'transaction' || active === 'control-transaction')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>

                        <!-- زیرمنو -->
                        <div x-show="openItems.transaction" x-transition class="mr-6 mt-2 space-y-1">

                            <a href="{{ route('sarafi.transactions') }}" @click="active = 'transactions'" :class="active === 'transactions'
                ? 'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-solid fa-wallet"></i>
                                <span>رسید / برداشت</span>
                            </a>

                            <a href="{{ route('sarafi.account_to_account') }}" @click="active = 'account_to_account'"
                                :class="active === 'account_to_account'
                ? 'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                <span>انتقال حساب به حساب</span>
                            </a>

                            <a href="{{ route('sarafi.buy-sell-currency') }}" @click="active = 'buy_sell'" :class="active === 'buy_sell'
                ? 'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-brands fa-bitcoin" class="w-4 h-4"></i> <span>خرید و فروش ارز</span>
                            </a>

                            <a href="{{ route('sarafi.conversion.in.account') }}" @click="active = 'conversion_account'"
                                :class="active === 'conversion_account'
                ? 'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-solid fa-exchange-alt"></i>
                                <span>تبدیل ارز در حساب</span>
                            </a>

                            <a href="{{ route('sarafi.conversion-transfer') }}" @click="active = 'conversion_transfer'"
                                :class="active === 'conversion_transfer'
                ? 'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                                <span> تبدیل ارز و انتقال از حساب</span>
                            </a>

                            <a href="{{ route('sarafi.remittance') }}" @click="active = 'remittance'" :class="active === 'remittance'
                ?'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-solid fa-book-open"></i>
                                <span>رسید بانکی</span>
                            </a>

                            <a href="{{ route('sarafi.withdrawbank') }}" @click="active = 'withdrawbank'" :class="active === 'withdrawbank'
                ? 'bg-[#FFFFFF] text-[#184D6C]'
                : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm vazir">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>برد بانکی</span>
                            </a>

                        </div>
                    </div>



                    <!-- ثبت حسابات و نرخ ارز -->
                    <div>
                        <button @click="openItems.accounts = !openItems.accounts; active = 'accounts'"
                            :class="(active === 'accounts' || active === 'register-accounts') ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white  dark:hover:bg-gray-800 dark:text-white hover:bg-gray-600'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2 ">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 17V17.5V18" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M12 6V6.5V7" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>




                                {{ __('messages.accounts') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'accounts' || active === 'control-accounts')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>
                        <div x-show="openItems.accounts" x-transition class="mr-6 mt-1 space-y-1">


                            <a href="{{ route('sarafi.profit-rates') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('register-accounts', 'accounts')"
                                :class="active === 'register-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.5 12.6499V16.3499C18.5 19.4699 15.59 21.9999 12 21.9999C8.41 21.9999 5.5 19.4699 5.5 16.3499V12.6499C5.5 15.7699 8.41 17.9999 12 17.9999C15.59 17.9999 18.5 15.7699 18.5 12.6499Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M18.5 7.65C18.5 8.56 18.25 9.4 17.81 10.12C16.74 11.88 14.54 13 12 13C9.46 13 7.26 11.88 6.19 10.12C5.75 9.4 5.5 8.56 5.5 7.65C5.5 6.09 6.22999 4.68 7.39999 3.66C8.57999 2.63 10.2 2 12 2C13.8 2 15.42 2.63 16.6 3.65C17.77 4.68 18.5 6.09 18.5 7.65Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M18.5 7.65V12.65C18.5 15.77 15.59 18 12 18C8.41 18 5.5 15.77 5.5 12.65V7.65C5.5 4.53 8.41 2 12 2C13.8 2 15.42 2.63 16.6 3.65C17.77 4.68 18.5 6.09 18.5 7.65Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                ثبت نرخ ارزها
                            </a>
                        </div>
                    </div>


                    <!-- کنترول و بررسی معاملات -->
                    <div>
                        <button @click="openItems.transactions = !openItems.transactions; active = 'transactions'"
                            :class="(active === 'transactions' || active === 'control-transactions') ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7 10L9.29289 12.2929C9.68342 12.6834 10.3166 12.6834 10.7071 12.2929L12.2929 10.7071C12.6834 10.3166 13.3166 10.3166 13.7071 10.7071L17 14M17 14V11.5M17 14H14.5"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>




                                {{ __('messages.transactions') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'transactions' || active === 'control-transactions')
                            ? '#000000'
                            : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>
                        <div x-show="openItems.transactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.remittance-approval') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('control-transactions', 'transactions')"
                                :class="active === 'control-transactions' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.24 2H8.76004C5.00004 2 4.71004 5.38 6.74004 7.22L17.26 16.78C19.29 18.62 19 22 15.24 22H8.76004C5.00004 22 4.71004 18.62 6.74004 16.78L17.26 7.22C19.29 5.38 19 2 15.24 2Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                احواله های تایید نشده
                            </a>
                        </div>


                        <div x-show="openItems.transactions" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.trash-edit') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('deleted-transactions', 'deletedTransactions')"
                                :class="active === 'deleted-transactions' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M17.9 9.04997C15.72 8.82997 13.52 8.71997 11.33 8.71997C10.03 8.71997 8.72997 8.78997 7.43997 8.91997L6.09998 9.04997"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M9.70996 8.38994L9.84996 7.52994C9.94996 6.90994 10.03 6.43994 11.14 6.43994H12.86C13.97 6.43994 14.0499 6.92994 14.1499 7.52994L14.2899 8.37994"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M16.49 9.12988L16.06 15.7299C15.99 16.7599 15.93 17.5599 14.1 17.5599H9.89C8.06 17.5599 7.99999 16.7599 7.92999 15.7299L7.5 9.12988"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                معاملات حذف شده و ویرایش شده
                            </a>
                        </div>
                    </div>




                    <!-- گزارش و آمار حسابات -->
                    <div>
                        <button @click="openItems.reports = !openItems.reports; active = 'reports'"
                            :class="(active === 'reports' || active === 'view-reports') ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white dark:hover:bg-gray-800 hover:bg-gray-600  '"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21.446 7.06901C20.6342 5.00831 18.9917 3.36579 16.931 2.55398C15.3895 1.94669 14 3.34316 14 5.00002V9.00002C14 9.5523 14.4477 10 15 10H19C20.6569 10 22.0533 8.61055 21.446 7.06901Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M6.22209 4.60105C6.66665 4.304 7.13344 4.04636 7.6171 3.82976C8.98898 3.21539 9.67491 2.9082 10.5875 3.4994C11.5 4.09061 11.5 5.06041 11.5 7.00001V8.50001C11.5 10.3856 11.5 11.3284 12.0858 11.9142C12.6716 12.5 13.6144 12.5 15.5 12.5H17C18.9396 12.5 19.9094 12.5 20.5006 13.4125C21.0918 14.3251 20.7846 15.011 20.1702 16.3829C19.9536 16.8666 19.696 17.3334 19.399 17.7779C18.3551 19.3402 16.8714 20.5578 15.1355 21.2769C13.3996 21.9959 11.4895 22.184 9.64665 21.8175C7.80383 21.4509 6.11109 20.5461 4.78249 19.2175C3.45389 17.8889 2.5491 16.1962 2.18254 14.3534C1.81598 12.5105 2.00412 10.6004 2.72315 8.86451"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>





                                {{ __('messages.reports') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'reports' || active === 'control-reports')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>
                        <div x-show="openItems.reports" x-transition class="mr-6 mt-1 space-y-1">

                            {{-- گزارش حسابات --}}
                            <a href="{{ route('sarafi.account-reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-reports', 'reports')"
                                :class="active === 'view-reports' ?'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 21H7C3 21 2 20 2 16V8C2 4 3 3 7 3H17C21 3 22 4 22 8V16C22 20 21 21 17 21Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14 8H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M15 12H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M17 16H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M8.49994 11.2899C9.49958 11.2899 10.3099 10.4796 10.3099 9.47992C10.3099 8.48029 9.49958 7.66992 8.49994 7.66992C7.50031 7.66992 6.68994 8.48029 6.68994 9.47992C6.68994 10.4796 7.50031 11.2899 8.49994 11.2899Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 16.33C11.86 14.88 10.71 13.74 9.26 13.61C8.76 13.56 8.25 13.56 7.74 13.61C6.29 13.75 5.14 14.88 5 16.33"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                گزارش حسابات
                            </a>



                            {{-- گزارشات عمومی --}}
                            <a href="{{ route('sarafi.general-reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-reports', 'reports')"
                                :class="active === 'view-reports' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 2V19C2 20.66 3.34 22 5 22H22" stroke="currentColor" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5 17L9.59 11.64C10.35 10.76 11.7 10.7 12.52 11.53L13.47 12.48C14.29 13.3 15.64 13.25 16.4 12.37L21 7"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>


                                گزارش عمومی
                            </a>
                            @php
                            $currentUser=Auth::guard('sarafi')->user();
                            @endphp


                            @if ($currentUser && $currentUser->role==='admin')

                            {{-- عواید معاملات --}}
                            <a href="{{ route('sarafi.revenue') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-revenue', 'reports')"
                                :class="active === 'view-revenue' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.87988 18.1501V16.0801" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M12 18.15V14.01" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M17.1201 18.1499V11.9299" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M17.1199 5.8501L16.6599 6.3901C14.1099 9.3701 10.6899 11.4801 6.87988 12.4301"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M14.1899 5.8501H17.1199V8.7701" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                عواید معاملات
                            </a>


                               {{-- عواید معاملات --}}
                            <a href="{{ route('sarafi.safe_deal_reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('view-revenue', 'reports')"
                                :class="active === 'view-revenue' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.87988 18.1501V16.0801" stroke="#292D32" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M12 18.15V14.01" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M17.1201 18.1499V11.9299" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M17.1199 5.8501L16.6599 6.3901C14.1099 9.3701 10.6899 11.4801 6.87988 12.4301"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M14.1899 5.8501H17.1199V8.7701" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                 گزارشات تبادله بین صندوق ها
                            </a>
                            @endif
                        </div>

                    </div>


                    <!-- معاملات بین صرافی ها-->
                    <div>
                        <button @click="openItems.changersdeal = !openItems.changersdeal; active = 'changersdeal'"
                            :class="(active === 'changersdeal' || active === 'edit-changersdeal') ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 4C6.22876 4 4.34315 4 3.17157 5.17157C2 6.34315 2 8.22876 2 12C2 15.7712 2 17.6569 3.17157 18.8284C4.34315 20 6.22876 20 10 20H11.5M14 4C17.7712 4 19.6569 4 20.8284 5.17157C21.8915 6.23467 21.99 7.8857 21.9991 11"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M15.5 14V20M15.5 20L17.5 18M15.5 20L13.5 18M20 20V14M20 14L22 16M20 14L18 16"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M2 10L7 10M22 10L11 10" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>




                                ارسال و دریافت از صرافان
                            </span>
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'changersdeal' || active === 'control-changersdeal')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </button>
                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.changersdeal') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]': 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.5898 7.67993H14.8298V11.9299" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.8299 7.67993L9.16992 13.3399" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 16.51C9.89 17.81 14.11 17.81 18 16.51" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                ارسال به صرافی
                            </a>
                        </div>

                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.changer_recive') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.5898 13.3398H14.8298V9.09985" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.8299 13.3399L9.16992 7.67993" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 16.51C9.89 17.81 14.11 17.81 18 16.51" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                دریافت از صرافی
                            </a>
                        </div>

                        <div x-show="openItems.changersdeal" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.sarafi_reports') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'changersdeal')"
                                :class="active === 'edit-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 22H22" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                گزارش حسابات صرافی ها
                            </a>
                        </div>



                    </div>





                    <!--  بخش مالی-->
                    <div>
                        <button @click="openItems.finance = !openItems.finance; active = 'finance'"
                            :class="(active === 'finance' || active === 'edit-finance') ? 'bg-[#FFFFFF] text-[#184D6C]': 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21 7V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V7C3 4 4.5 2 8 2H16C19.5 2 21 4 21 7Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <path
                                        d="M15.5 2V9.85999C15.5 10.3 14.98 10.52 14.66 10.23L12.34 8.09003C12.15 7.91003 11.85 7.91003 11.66 8.09003L9.34003 10.23C9.02003 10.52 8.5 10.3 8.5 9.85999V2H15.5Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />

                                    <path d="M13.25 14H17.5" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />

                                    <path d="M9 18H17.5" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>




                                بخش مالی صرافی
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'finance' || active === 'control-finance')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>
                        <div x-show="openItems.finance" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.staff') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'finance')"
                                :class="active === 'edit-accounts' ?'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.5898 7.67993H14.8298V11.9299" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.8299 7.67993L9.16992 13.3399" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 16.51C9.89 17.81 14.11 17.81 18 16.51" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                ثبت کارمندان
                            </a>
                        </div>

                        <div x-show="openItems.finance" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.withdraw') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'finance')"
                                :class="active === 'edit-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10.5898 13.3398H14.8298V9.09985" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.8299 13.3399L9.16992 7.67993" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M6 16.51C9.89 17.81 14.11 17.81 18 16.51" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                برداشت ها
                            </a>
                        </div>

                        <div x-show="openItems.finance" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.salary') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'finacne')"
                                :class="active === 'edit-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 22H22" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.75 4V22H14.25V4C14.25 2.9 13.8 2 12.45 2H11.55C10.2 2 9.75 2.9 9.75 4Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3 10V22H7V10C7 8.9 6.6 8 5.4 8H4.6C3.4 8 3 8.9 3 10Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M17 15V22H21V15C21 13.9 20.6 13 19.4 13H18.6C17.4 13 17 13.9 17 15Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                مدیریت معاشات

                            </a>
                        </div>


                        <div x-show="openItems.finance" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.attendances') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('edit-accounts', 'finacne')"
                                :class="active === 'edit-accounts' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <i class="fas fa-id-badge"></i>
                                حاضری کارمندان

                            </a>
                        </div>



                    </div>






                    <!-- مدیریت و دسترسی -->
                    <div>
                        <button @click="openItems.management = !openItems.management; active = 'management'"
                            :class="(active === 'management' || active === 'user-management') ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">
                                <svg width="25" height="29" viewBox="0 0 25 29" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.26035 12.2189C8.55226 12.7093 8.94403 13.1629 9.54282 13.7121C9.79976 13.9477 10.1054 14.1236 10.4331 14.2427C11.4478 14.6117 12.5574 14.6267 13.5817 14.2853L13.6244 14.271C14.0364 14.1337 14.4159 13.9098 14.7215 13.6013C15.5548 12.76 16.0138 12.1215 16.3354 11.2158C16.4511 10.8901 16.6969 10.6219 17.0248 10.5126C17.3933 10.3897 17.6686 10.0716 17.7104 9.68551C17.7523 9.29881 17.7584 8.96851 17.6951 8.65701C17.5344 7.86761 17.2993 7.02633 17.6596 6.30581C17.9844 5.65626 17.9235 4.88039 17.5014 4.28944L16.2505 2.53818C15.1687 1.02373 13.2367 0.375249 11.4603 0.930373L8.99198 1.70173C8.6621 1.80481 8.4375 2.11032 8.4375 2.45592C8.4375 2.68263 8.34012 2.89842 8.17012 3.04842L6.31916 4.68163C5.73039 5.20113 5.67857 6.10119 6.20384 6.68483L6.3445 6.84111C6.69339 7.22877 6.73509 7.80362 6.44579 8.23757C6.28022 8.48592 6.21416 8.79062 6.28274 9.08111C6.36258 9.41933 6.4558 9.69886 6.5921 9.9397C7.05231 10.7529 7.78235 11.416 8.26035 12.2189Z"
                                        stroke="currentColor" stroke-width="1.3" />
                                    <path
                                        d="M4.5 26.15C4.85898 26.15 5.15 25.859 5.15 25.5C5.15 25.141 4.85898 24.85 4.5 24.85V25.5V26.15ZM5.26748 17.3371L5.12264 16.7034L5.26748 17.3371ZM1.72357 21.3432L1.12314 21.0942L1.72357 21.3432ZM1.72357 21.3432L2.324 21.5921L2.97072 20.0324L2.37029 19.7834L1.76986 19.5345L1.12314 21.0942L1.72357 21.3432ZM5.26748 17.3371L5.41231 17.9707L7.51861 17.4893L7.37378 16.8556L7.22894 16.222L5.12264 16.7034L5.26748 17.3371ZM7.37378 16.8556L7.51861 17.4893C9.20446 17.104 10.4 15.6043 10.4 13.875H9.75H9.1C9.1 14.998 8.32366 15.9718 7.22894 16.222L7.37378 16.8556ZM2.37029 19.7834L2.97072 20.0324C3.40277 18.9904 4.31265 18.2221 5.41231 17.9707L5.26748 17.3371L5.12264 16.7034C3.61259 17.0486 2.36315 18.1036 1.76986 19.5345L2.37029 19.7834ZM4.5 25.5V24.85C2.82082 24.85 1.68085 23.1433 2.324 21.5921L1.72357 21.3432L1.12314 21.0942C0.125049 23.5014 1.89413 26.15 4.5 26.15V25.5Z"
                                        fill="currentColor" />
                                    <path
                                        d="M15.6529 17.0496C15.9564 17.2413 16.3579 17.1506 16.5496 16.8471C16.7413 16.5436 16.6506 16.1421 16.3471 15.9504L16 16.5L15.6529 17.0496ZM14.8125 13.5H14.1625V14.3455H14.8125H15.4625V13.5H14.8125ZM14.8125 14.3455H14.1625C14.1625 15.4428 14.7251 16.4636 15.6529 17.0496L16 16.5L16.3471 15.9504C15.7964 15.6026 15.4625 14.9968 15.4625 14.3455H14.8125Z"
                                        fill="currentColor" />
                                    <path d="M12.375 15.75V19.3125" stroke="currentColor" stroke-width="1.3"
                                        stroke-linecap="round" />
                                    <path d="M6.375 25.5H6.75" stroke="currentColor" stroke-width="1.3"
                                        stroke-linecap="round" />
                                    <path d="M8.25 25.5H12.75" stroke="currentColor" stroke-width="1.3"
                                        stroke-linecap="round" />
                                    <path
                                        d="M18.1878 23.5225C19.0633 23.5225 19.773 22.8128 19.773 21.9373C19.773 21.0618 19.0633 20.3521 18.1878 20.3521C17.3123 20.3521 16.6025 21.0618 16.6025 21.9373C16.6025 22.8128 17.3123 23.5225 18.1878 23.5225Z"
                                        stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M22.0977 23.5227C22.0274 23.6821 22.0064 23.8589 22.0375 24.0303C22.0686 24.2017 22.1503 24.3599 22.2721 24.4844L22.3038 24.5161C22.4021 24.6143 22.48 24.7308 22.5332 24.8591C22.5864 24.9874 22.6138 25.125 22.6138 25.2638C22.6138 25.4027 22.5864 25.5402 22.5332 25.6685C22.48 25.7968 22.4021 25.9134 22.3038 26.0115C22.2057 26.1098 22.0891 26.1877 21.9608 26.2409C21.8325 26.2941 21.695 26.3215 21.5561 26.3215C21.4172 26.3215 21.2797 26.2941 21.1514 26.2409C21.0231 26.1877 20.9066 26.1098 20.8084 26.0115L20.7767 25.9798C20.6522 25.858 20.494 25.7763 20.3226 25.7452C20.1512 25.7141 19.9744 25.7351 19.815 25.8055C19.6587 25.8724 19.5254 25.9837 19.4315 26.1254C19.3377 26.2672 19.2873 26.4333 19.2866 26.6034V26.6932C19.2866 26.9735 19.1752 27.2423 18.9771 27.4405C18.7789 27.6387 18.5101 27.75 18.2298 27.75C17.9495 27.75 17.6807 27.6387 17.4825 27.4405C17.2843 27.2423 17.173 26.9735 17.173 26.6932V26.6456C17.1689 26.4707 17.1122 26.3011 17.0105 26.1588C16.9087 26.0165 16.7665 25.9081 16.6023 25.8477C16.4429 25.7774 16.2661 25.7564 16.0947 25.7875C15.9233 25.8186 15.7651 25.9003 15.6406 26.0221L15.6089 26.0538C15.5107 26.1521 15.3942 26.23 15.2659 26.2832C15.1376 26.3364 15 26.3638 14.8612 26.3638C14.7223 26.3638 14.5848 26.3364 14.4565 26.2832C14.3282 26.23 14.2116 26.1521 14.1135 26.0538C14.0152 25.9557 13.9373 25.8391 13.8841 25.7108C13.8309 25.5825 13.8035 25.445 13.8035 25.3061C13.8035 25.1672 13.8309 25.0297 13.8841 24.9014C13.9373 24.7731 14.0152 24.6566 14.1135 24.5584L14.1452 24.5267C14.267 24.4022 14.3487 24.244 14.3798 24.0726C14.4109 23.9012 14.3899 23.7244 14.3195 23.565C14.2526 23.4087 14.1413 23.2754 13.9996 23.1815C13.8578 23.0877 13.6917 23.0373 13.5216 23.0366H13.4318C13.1515 23.0366 12.8827 22.9252 12.6845 22.7271C12.4863 22.5289 12.375 22.2601 12.375 21.9798C12.375 21.6995 12.4863 21.4307 12.6845 21.2325C12.8827 21.0343 13.1515 20.923 13.4318 20.923H13.4794C13.6543 20.9189 13.8239 20.8622 13.9662 20.7605C14.1085 20.6587 14.2169 20.5165 14.2773 20.3523C14.3476 20.1929 14.3686 20.0161 14.3375 19.8447C14.3064 19.6733 14.2247 19.5151 14.1029 19.3906L14.0712 19.3589C13.9729 19.2607 13.895 19.1442 13.8418 19.0159C13.7886 18.8876 13.7612 18.75 13.7612 18.6112C13.7612 18.4723 13.7886 18.3348 13.8418 18.2065C13.895 18.0782 13.9729 17.9616 14.0712 17.8635C14.1693 17.7652 14.2859 17.6873 14.4142 17.6341C14.5425 17.5809 14.68 17.5535 14.8189 17.5535C14.9578 17.5535 15.0953 17.5809 15.2236 17.6341C15.3519 17.6873 15.4684 17.7652 15.5666 17.8635L15.5983 17.8952C15.7228 18.017 15.881 18.0987 16.0524 18.1298C16.2238 18.1609 16.4006 18.1399 16.56 18.0695H16.6023C16.7586 18.0026 16.8918 17.8913 16.9857 17.7496C17.0796 17.6078 17.13 17.4417 17.1307 17.2716V17.1818C17.1307 16.9015 17.242 16.6327 17.4402 16.4345C17.6384 16.2363 17.9072 16.125 18.1875 16.125C18.4678 16.125 18.7366 16.2363 18.9348 16.4345C19.133 16.6327 19.2443 16.9015 19.2443 17.1818V17.2294C19.245 17.3994 19.2954 17.5655 19.3893 17.7073C19.4831 17.8491 19.6164 17.9603 19.7727 18.0273C19.9321 18.0976 20.1089 18.1186 20.2803 18.0875C20.4517 18.0564 20.6099 17.9747 20.7344 17.8529L20.7661 17.8212C20.8643 17.7229 20.9808 17.645 21.1091 17.5918C21.2374 17.5386 21.375 17.5112 21.5138 17.5112C21.6527 17.5112 21.7902 17.5386 21.9185 17.5918C22.0468 17.645 22.1634 17.7229 22.2615 17.8212C22.3598 17.9193 22.4377 18.0359 22.4909 18.1642C22.5441 18.2925 22.5715 18.43 22.5715 18.5689C22.5715 18.7078 22.5441 18.8453 22.4909 18.9736C22.4377 19.1019 22.3598 19.2184 22.2615 19.3166L22.2298 19.3483C22.108 19.4728 22.0263 19.631 21.9952 19.8024C21.9641 19.9738 21.9851 20.1506 22.0555 20.31V20.3523C22.1224 20.5086 22.2337 20.6418 22.3754 20.7357C22.5172 20.8296 22.6833 20.88 22.8534 20.8807H22.9432C23.2235 20.8807 23.4923 20.992 23.6905 21.1902C23.8887 21.3884 24 21.6572 24 21.9375C24 22.2178 23.8887 22.4866 23.6905 22.6848C23.4923 22.883 23.2235 22.9943 22.9432 22.9943H22.8956C22.7256 22.995 22.5595 23.0454 22.4177 23.1393C22.2759 23.2331 22.1647 23.3664 22.0977 23.5227Z"
                                        stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>



                                {{ __('messages.management') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'management' || active === 'control-management')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>


                        </button>
                        <div x-show="openItems.management" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.users') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('user-management', 'management')"
                                :class="active === 'user-management' ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <i class="fas fa-user-alt"></i>
                                <svg width="25" class="hidden dark:block" height="25" viewBox="0 0 30 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.4531 13.5875C11.3281 13.575 11.1781 13.575 11.0406 13.5875C8.06562 13.4875 5.70312 11.05 5.70312 8.05C5.70312 4.9875 8.17813 2.5 11.2531 2.5C14.3156 2.5 16.8031 4.9875 16.8031 8.05C16.7906 11.05 14.4281 13.4875 11.4531 13.5875Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M20.5141 5C22.9391 5 24.8891 6.9625 24.8891 9.375C24.8891 11.7375 23.0141 13.6625 20.6766 13.75C20.5766 13.7375 20.4641 13.7375 20.3516 13.75"
                                        stroke="currentColor" stroke-width="1.5" stroke-lin stroke="currentColor"
                                        ecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M5.19844 18.2C2.17344 20.225 2.17344 23.525 5.19844 25.5375C8.63594 27.8375 14.2734 27.8375 17.7109 25.5375C20.7359 23.5125 20.7359 20.2125 17.7109 18.2C14.2859 15.9125 8.64844 15.9125 5.19844 18.2Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M22.9219 25C23.8219 24.8125 24.6719 24.45 25.3719 23.9125C27.3219 22.45 27.3219 20.0375 25.3719 18.575C24.6844 18.05 23.8469 17.7 22.9594 17.5"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ __('messages.user_management') }}
                            </a>
                        </div>
                    </div>


                    @php
                    use App\Models\Sarafi\OnlineNotifications;
                    use Illuminate\Support\Facades\Auth;

                    $currentUser = Auth::guard('sarafi')->user();

                    // پیام هایی که هنوز کاربر ندیده
                    $newNotifications = OnlineNotifications::whereDoesntHave('seenByUsers', function($q) use
                    ($currentUser){
                    $q->where('user_id', $currentUser->id);
                    })->get();

                    // ثبت پیام‌ها به عنوان دیده شده
                    foreach($newNotifications as $notif){
                    $notif->seenByUsers()->attach($currentUser->id);
                    }

                    // تعداد پیام های جدید برای Badge
                    $newCount = $newNotifications->count();
                    @endphp

                    <!-- اطلاعیه های آنلاین -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir"
                            :class="open ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'">
                            <span class="flex items-center gap-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 9.96004C9.63 7.15004 14.37 7.15004 18 9.96004" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.59961 13.0499C10.2696 10.9899 13.7396 10.9899 16.4096 13.0499"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9.7998 16.1402C11.1298 15.1102 12.8698 15.1102 14.1998 16.1402"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                {{ __('messages.notifications') }}

                                <!-- Badge پیام های جدید -->
                                @if($newCount > 0)
                                <span
                                    class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                    {{ $newCount }}
                                </span>
                                @endif
                            </span>

                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="open ? '#000000' : '#FFFFFF'"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.online') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                :class="open ? 'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'">
                                <i class="fas fa-bell"></i>
                                {{ __('messages.online_notifications') }}
                            </a>
                        </div>
                    </div>


                    {{-- <!-- پشتیبانی سیستم -->
                    <div>
                        <button @click="openItems.support = !openItems.support; active = 'support'"
                            :class="(active === 'support' || active === 'system-support') ? 'bg-[#FFFFFF] text-[#184D6C]': 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 13.4V16.4C17 20.4 15.4 22 11.4 22H7.6C3.6 22 2 20.4 2 16.4V12.6C2 8.6 3.6 7 7.6 7H10.6"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M16.9996 13.4H13.7996C11.3996 13.4 10.5996 12.6 10.5996 10.2V7L16.9996 13.4Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M11.5996 2H15.5996" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7 5C7 3.34 8.34 2 10 2H12.62" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M22.0004 8V14.19C22.0004 15.74 20.7404 17 19.1904 17" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M22 8H19C16.75 8 16 7.25 16 5V2L22 8Z" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>



                                {{ __('messages.support') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'support' || active === 'control-support')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div x-show="openItems.support" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="#"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('system-support', 'support')"
                                :class="active === 'system-support' ?'bg-[#FFFFFF] text-[#184D6C]' : 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/support.svg') }}" class="w-4 h-4"
                                    :class="active === 'system-support' ? 'filter invert brightness-0' : 'text-gray-500'">
                                {{ __('messages.system_support') }}
                            </a>
                        </div>
                    </div> --}}

                    <!-- تنظیمات -->
                    <div>
                        <button @click="openItems.settings = !openItems.settings; active = 'settings'"
                            :class="(active === 'settings' || active === 'system-settings') ? 'bg-[#FFFFFF] text-[#184D6C]': 'text-white dark:text-white hover:bg-gray-600 dark:hover:bg-gray-800'"
                            class="flex items-center justify-between w-full py-3 px-4 rounded-lg transition vazir">
                            <span class="flex items-center gap-2">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M2 12.8799V11.1199C2 10.0799 2.85 9.21994 3.9 9.21994C5.71 9.21994 6.45 7.93994 5.54 6.36994C5.02 5.46994 5.33 4.29994 6.24 3.77994L7.97 2.78994C8.76 2.31994 9.78 2.59994 10.25 3.38994L10.36 3.57994C11.26 5.14994 12.74 5.14994 13.65 3.57994L13.76 3.38994C14.23 2.59994 15.25 2.31994 16.04 2.78994L17.77 3.77994C18.68 4.29994 18.99 5.46994 18.47 6.36994C17.56 7.93994 18.3 9.21994 20.11 9.21994C21.15 9.21994 22.01 10.0699 22.01 11.1199V12.8799C22.01 13.9199 21.16 14.7799 20.11 14.7799C18.3 14.7799 17.56 16.0599 18.47 17.6299C18.99 18.5399 18.68 19.6999 17.77 20.2199L16.04 21.2099C15.25 21.6799 14.23 21.3999 13.76 20.6099L13.65 20.4199C12.75 18.8499 11.27 18.8499 10.36 20.4199L10.25 20.6099C9.78 21.3999 8.76 21.6799 7.97 21.2099L6.24 20.2199C5.33 19.6999 5.02 18.5299 5.54 17.6299C6.45 16.0599 5.71 14.7799 3.9 14.7799C2.85 14.7799 2 13.9199 2 12.8799Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>




                                {{ __('messages.settings') }}
                            </span>
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 0.75L5.75 4.75L10.75 0.75" :stroke="(active === 'settings' || active === 'control-settings')
                                ? '#000000'
                                : '#FFFFFF'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </button>

                        @php
                            $currentUser=Auth::guard('sarafi')->user();
                            @endphp


                            @if ($currentUser && $currentUser->role==='admin')
                        <div x-show="openItems.settings" x-transition class="mr-6 mt-1 space-y-1">
                            <a href="{{ route('sarafi.backup') }}"
                                class="nav-link flex items-center gap-2 py-2 px-3 rounded-md text-sm transition vazir"
                                @click="setActive('system-settings', 'settings')"
                                :class="active === 'system-settings' ? 'bg-[#2563EB] text-white' : 'text-white dark:text-white hover:bg-gray-600  dark:hover:bg-gray-800'">
                                <img src="{{ asset('assets/sarafi/all_icon/settings.svg') }}" class="w-4 h-4"
                                    :class="active === 'system-settings' ? 'filter invert brightness-0' : 'text-gray-500'">
                               بک آپ گیری
                            </a>
                        </div>
                        @endif
                    </div>
                </nav>
            </div>

            <!-- محتوای اصلی -->
            <main class="flex-1 mx-auto main-content-wrapper w-full mt-6 min-w-0
    {{ request()->is('sarafi/home*') ? 'px-10' : 'px-10' }}">
                @yield('content')
            </main>



            <style>
                /* Chat Styles */
                .chat-message {
                    max-width: 85%;
                    padding: 10px 14px;
                    border-radius: 18px;
                    margin-bottom: 8px;
                    word-wrap: break-word;
                    position: relative;
                    word-break: break-word;
                }

                .chat-message.sent {
                    background: linear-gradient(135deg, #184D6C, #4ECDC4);
                    color: white;
                    margin-left: auto;
                    margin-right: 0;
                    border-bottom-left-radius: 4px;
                }

                .chat-message.received {
                    background-color: #f1f1f1;
                    color: #333;
                    margin-right: auto;
                    margin-left: 0;
                    border-bottom-right-radius: 4px;
                }

                .dark .chat-message.received {
                    background-color: #374151;
                    color: #e5e7eb;
                }

                .chat-message .time {
                    font-size: 11px;
                    opacity: 0.8;
                    margin-top: 4px;
                    text-align: left;
                    display: block;
                }

                .chat-message.sent .time {
                    color: rgba(255, 255, 255, 0.9);
                }

                .chat-message.received .time {
                    color: #6b7280;
                }

                .conversation-item {
                    transition: all 0.2s ease;
                    cursor: pointer;
                    border-radius: 10px;
                    padding: 12px;
                    margin-bottom: 8px;
                    border: 1px solid transparent;
                }

                .conversation-item:hover {
                    background-color: #f9fafb;
                    border-color: #e5e7eb;
                }

                .dark .conversation-item:hover {
                    background-color: #374151;
                    border-color: #4b5563;
                }

                .conversation-item.active {
                    background-color: #eff6ff;
                    border-color: rgb(68, 125, 215);
                }

                .dark .conversation-item.active {
                    background-color: #1e3a8a;
                    border-color: #3b82f6;
                }

                .unread-badge {
                    background-color: #ef4444;
                    color: white;
                    font-size: 12px;
                    min-width: 20px;
                    height: 20px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0 6px;
                }

                .user-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    color: white;
                    font-size: 16px;
                    flex-shrink: 0;
                }

                .avatar-blue {
                    background-color: #3b82f6;
                }

                .avatar-green {
                    background-color: #10b981;
                }

                .avatar-purple {
                    background-color: #8b5cf6;
                }

                .avatar-pink {
                    background-color: #ec4899;
                }

                .avatar-orange {
                    background-color: #f59e0b;
                }

                /* Scrollbar Styling */
                #messagesContainer::-webkit-scrollbar,
                #conversationsPanel::-webkit-scrollbar,
                #usersPanel::-webkit-scrollbar {
                    width: 6px;
                }

                #messagesContainer::-webkit-scrollbar-track,
                #conversationsPanel::-webkit-scrollbar-track,
                #usersPanel::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 3px;
                }

                .dark #messagesContainer::-webkit-scrollbar-track,
                .dark #conversationsPanel::-webkit-scrollbar-track,
                .dark #usersPanel::-webkit-scrollbar-track {
                    background: #374151;
                }

                #messagesContainer::-webkit-scrollbar-thumb,
                #conversationsPanel::-webkit-scrollbar-thumb,
                #usersPanel::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 3px;
                }

                .dark #messagesContainer::-webkit-scrollbar-thumb,
                .dark #conversationsPanel::-webkit-scrollbar-thumb,
                .dark #usersPanel::-webkit-scrollbar-thumb {
                    background: #6b7280;
                }

                /* Loading Animation */
                .chat-loading {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    flex-direction: column;
                }

                .chat-loading-spinner {
                    width: 40px;
                    height: 40px;
                    border: 3px solid #f3f3f3;
                    border-top: 3px solid #184D6C;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* Media Message Styles */
                .media-message {
                    max-width: 250px;
                    overflow: hidden;
                }

                .media-message img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 12px;
                    cursor: pointer;
                    transition: transform 0.3s ease;
                }

                .media-message img:hover {
                    transform: scale(1.02);
                }

                .audio-message {
                    min-width: 200px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }

                .audio-player {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 8px 12px;
                    border-radius: 20px;
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(10px);
                }

                .audio-controls {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    flex: 1;
                }

                .play-pause-btn {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background: white;
                    border: none;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #122EE1;
                    transition: all 0.3s ease;
                }

                .play-pause-btn:hover {
                    transform: scale(1.1);
                }

                .progress-bar {
                    flex: 1;
                    height: 4px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 2px;
                    overflow: hidden;
                    cursor: pointer;
                }

                .progress {
                    height: 100%;
                    background: white;
                    width: 0%;
                    transition: width 0.1s linear;
                }

                .audio-time {
                    font-size: 12px;
                    color: white;
                    min-width: 40px;
                    text-align: center;
                }

                .audio-duration {
                    font-size: 10px;
                    color: rgba(255, 255, 255, 0.7);
                    margin-top: 4px;
                    display: block;
                    text-align: center;
                }

                /* Recording Animation */
                @keyframes recording-pulse {

                    0%,
                    100% {
                        opacity: 1;
                    }

                    50% {
                        opacity: 0.5;
                    }
                }

                .recording {
                    animation: recording-pulse 1s infinite;
                }

                /* Mobile Styles */
                @media (max-width: 768px) {
                    #chatWindow {
                        border-radius: 16px 16px 0 0;
                        height: 85vh !important;
                    }

                    .media-message {
                        max-width: 200px;
                    }

                    .chat-message {
                        max-width: 90%;
                        padding: 8px 12px;
                        font-size: 14px;
                    }

                    .input-group {
                        flex-direction: column;
                        gap: 8px;
                    }

                    .input-group .flex {
                        width: 100%;
                    }

                    #messageInput {
                        width: 100% !important;
                    }
                }

                @media (min-width: 769px) {
                    #chatWindow {
                        min-width: 400px;
                    }
                }

                /* Modal for Image Preview */
                .image-modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.9);
                    z-index: 10000;
                    align-items: center;
                    justify-content: center;
                }

                .image-modal img {
                    max-width: 90%;
                    max-height: 90%;
                    object-fit: contain;
                }

                .close-modal {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    color: white;
                    font-size: 30px;
                    cursor: pointer;
                    background: rgba(0, 0, 0, 0.5);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                /* Delete Message Button */
                .delete-message-btn {
                    display: none;
                    position: absolute;
                    top: 5px;
                    left: 5px;
                    background: rgba(239, 68, 68, 0.9);
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 24px;
                    height: 24px;
                    cursor: pointer;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    z-index: 10;
                }

                .chat-message:hover .delete-message-btn {
                    display: flex;
                }

                /* Input Group */
                .input-group {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    width: 100%;
                }

                /* Voice Button */
                .voice-btn {
                    padding: 10px;
                    background: #f3f4f6;
                    border-radius: 10px;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .voice-btn:hover {
                    background: #e5e7eb;
                }

                .voice-btn.recording {
                    background: #fee2e2;
                    color: #dc2626;
                    animation: pulse 1.5s infinite;
                }

                @keyframes pulse {
                    0% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.05);
                    }

                    100% {
                        transform: scale(1);
                    }
                }

                /* Toast Notification */
                .toast {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: #059669;
                    color: white;
                    padding: 12px 20px;
                    border-radius: 8px;
                    z-index: 10000;
                    animation: slideIn 0.3s ease;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }

                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }

                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }

                /* Message Status */
                .message-status {
                    font-size: 10px;
                    margin-top: 2px;
                    text-align: left;
                }

                .message-status.sent {
                    color: rgba(255, 255, 255, 0.7);
                }

                .message-status.received {
                    color: #6b7280;
                }
            </style>

            <!-- Chat Widget -->
            <div id="chatWidget" class="fixed bottom-4 right-4 z-[9999]">
                <!-- Chat Button -->
                <button id="chatToggle"
                    class="bg-[#FFFFFF] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14 7.07026C12.8233 6.38958 11.4571 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2797 2.30049 16.4893 2.83477 17.562C2.97675 17.847 3.02401 18.1729 2.94169 18.4805L2.46521 20.2613C2.25836 21.0344 2.96561 21.7416 3.73868 21.5348L5.51951 21.0583C5.82715 20.976 6.15297 21.0233 6.43802 21.1652C7.51069 21.6995 8.72025 22 10 22C14.4183 22 18 18.4183 18 14C18 12.5429 17.6104 11.1767 16.9297 10"
                            stroke="#184D6C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 7.22592 21.6334 6.03745 21 5.03431M9.5 5.9956C10.4806 3.64899 12.7977 2 15.5 2C16.7886 2 17.9897 2.375 19 3.02182"
                            stroke="#184D6C" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M6.51779 14H6.52679M10.0085 14H10.0175M13.4995 14H13.5085" stroke="#184D6C"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <span id="unreadBadge"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center hidden shadow">0</span>
                </button>

                <!-- Chat Window -->
                <div id="chatWindow" class="
                            fixed sm:absolute
                            bottom-0 sm:bottom-20
                            right-0
                            left-0 sm:left-auto
                            w-full sm:w-[460px] lg:w-[520px]
                            h-[90vh] sm:h-[600px]
                            bg-white dark:bg-gray-800
                            rounded-none sm:rounded-2xl
                            shadow-2xl
                            hidden
                            flex flex-col
                            border border-gray-200 dark:border-gray-700
                            transform translate-y-full sm:translate-y-0
                            transition-transform duration-300 ease-in-out">

                    <!-- Chat Header -->
                    <div class="bg-[#122EE1] text-white p-4 rounded-t-lg flex justify-between items-center">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <h3 class="font-semibold text-lg">پیام‌رسانی</h3>
                            <button id="markAllReadBtn"
                                class="text-xs bg-white/20 hover:bg-white/30 px-2 py-1 rounded transition">
                                خواندن همه
                            </button>
                        </div>
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <button id="refreshChatBtn" class="text-white hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                            <button id="closeChatBtn" class="text-white hover:text-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Body -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Search Bar -->
                        <div class="p-3 border-b dark:border-gray-700">
                            <div class="relative">
                                <input type="text" id="chatSearchInput" placeholder="جستجوی کاربر..."
                                    class="w-full px-4 py-2 pr-10 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#122EE1]">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="flex border-b dark:border-gray-700 shrink-0">
                            <button id="conversationsTab"
                                class="flex-1 py-3 text-center font-medium border-b-2 border-[#122EE1] text-[#122EE1]">
                                مکالمات
                            </button>
                            <button id="usersTab"
                                class="flex-1 py-3 text-center font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                کاربران
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 overflow-hidden">
                            <!-- Conversations Panel -->
                            <div id="conversationsPanel" class="h-full overflow-y-auto">
                                <div id="conversationsList" class="p-3">
                                    <!-- مکالمات در اینجا بارگذاری می‌شوند -->
                                </div>
                                <div id="noConversations" class="hidden p-6 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <p>هیچ مکالمه‌ای وجود ندارد</p>
                                </div>
                            </div>

                            <!-- Users Panel -->
                            <div id="usersPanel" class="h-full overflow-y-auto hidden">
                                <div id="usersList" class="p-3">
                                    <!-- کاربران در اینجا بارگذاری می‌شوند -->
                                </div>
                                <div id="noUsers" class="hidden p-6 text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8A8.5 8.5 0 0012 3.5 8.5 8.5 0 003.5 12 8.5 8.5 0 0012 20.5a8.5 8.5 0 008.5-8.5z">
                                        </path>
                                    </svg>
                                    <p>کاربری برای چت پیدا نشد</p>
                                </div>
                            </div>

                            <!-- Messages Panel -->
                            <div id="messagesPanel" class="h-full flex flex-col hidden">
                                <!-- Messages Header -->
                                <div
                                    class="p-3 border-b dark:border-gray-700 flex items-center bg-gray-50 dark:bg-gray-900 shrink-0">
                                    <button id="backToChat" class="ml-3 text-[#122EE1] hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <div id="currentChatUser" class="flex items-center flex-1">
                                        <!-- اطلاعات کاربر در اینجا بارگذاری می‌شود -->
                                    </div>
                                </div>

                                <!-- Messages Container -->
                                <div id="messagesContainer"
                                    class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-4 overscroll-contain">
                                    <!-- پیام‌ها در اینجا بارگذاری می‌شوند -->
                                </div>

                                <!-- Recording Indicator -->
                                <div id="recordingIndicator"
                                    class="hidden p-3 border-t dark:border-gray-700 bg-red-50 dark:bg-red-900/20">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                            <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                            <span class="text-red-600 dark:text-red-400 font-medium">در حال ضبط
                                                صوت...</span>
                                        </div>
                                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                            <span id="recordingTimer"
                                                class="text-red-600 dark:text-red-400 font-mono">0:00</span>
                                            <button id="cancelRecordingBtn"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Message Input Area -->
                                <div class="p-3 border-t dark:border-gray-700 shrink-0">
                                    <!-- Hidden Inputs -->
                                    <input type="file" id="imageInput" accept="image/*" class="hidden">
                                    <input type="file" id="audioInput" accept="audio/*" class="hidden">

                                    <!-- Media Preview -->
                                    <div id="mediaPreview"
                                        class="hidden mb-3 p-3 border rounded-lg bg-gray-50 dark:bg-gray-900">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                                <span id="mediaFileName" class="text-sm truncate"></span>
                                            </div>
                                            <button id="removeMediaBtn" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div id="imagePreview" class="hidden mt-2">
                                            <img id="previewImage" class="max-w-full h-32 object-cover rounded-lg">
                                        </div>
                                    </div>

                                    <!-- Input Controls -->
                                    <div class="input-group">
                                        <!-- Attach Button -->
                                        <div class="relative group">
                                            <button id="attachButton" class="voice-btn" title="ضمیمه فایل">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                            </button>
                                            <!-- Attach Menu -->
                                            <div class="absolute bottom-full right-0 mb-2
                                                        hidden group-focus-within:block
                                                        z-50">
                                                <div class="bg-white dark:bg-gray-800
                                                        rounded-xl shadow-xl
                                                        border border-gray-200 dark:border-gray-700
                                                        p-2 min-w-[180px]">

                                                    <button id="attachImageBtn" type="button" class="flex items-center w-full px-3 py-2 text-sm
                                                                text-gray-700 dark:text-gray-300
                                                                hover:bg-gray-100 dark:hover:bg-gray-700
                                                                 rounded-lg transition">
                                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        ارسال عکس
                                                    </button>

                                                    <button id="attachAudioBtn" type="button" class="flex items-center w-full px-3 py-2 text-sm
                                                                text-gray-700 dark:text-gray-300
                                                                hover:bg-gray-100 dark:hover:bg-gray-700
                                                                 rounded-lg transition">
                                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2
                                                                1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2
                                                                s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                        </svg>
                                                        ارسال فایل صوتی
                                                    </button>

                                                </div>
                                            </div>


                                        </div>

                                        <!-- Voice Record Button -->
                                        <button id="voiceRecordBtn" class="voice-btn" title="ضبط صوت">
                                            <svg id="voiceIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                                                </path>
                                            </svg>
                                            <svg id="stopVoiceIcon" class="w-5 h-5 hidden" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Message Input -->
                                        <input type="text" id="messageInput" placeholder="پیام خود را بنویسید..."
                                            class="flex-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#122EE1]">

                                        <!-- Send Button -->
                                        <button id="sendMessageBtn"
                                            class="bg-[#122EE1] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                            <svg width="24" height="24" viewBox="0 0 29 24" fill="none"
                                                class="text-white" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5087 4.23001L7.1654 8.51001C2.5254 10.43 2.5254 13.57 7.1654 15.49L17.5087 19.77C24.4687 22.65 27.3083 20.29 23.8283 14.54L22.7771 12.81C22.5112 12.37 22.5112 11.64 22.7771 11.2L23.8283 9.46001C27.3083 3.71001 24.4567 1.35001 17.5087 4.23001Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M22.4267 12H15.9017" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>


                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audio Elements -->
            <audio id="messageSound" preload="auto" style="display: none;">
                <source src="{{ asset('assets/sarafi/message.mp3') }}" type="audio/mpeg">
            </audio>

            <!-- Image Preview Modal -->
            <div id="imageModal" class="image-modal">
                <span class="close-modal">&times;</span>
                <img id="modalImage" src="" alt="تصویر">
            </div>

            <!-- Toast Container -->
            <div id="toastContainer"></div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                                        // -------------------------
                                        // تعاریف اولیه و متغیرهای گلوبال
                                        // -------------------------
                                        const chatWidget = document.getElementById('chatWidget');
                                        const chatToggle = document.getElementById('chatToggle');
                                        const chatWindow = document.getElementById('chatWindow');
                                        const closeChatBtn = document.getElementById('closeChatBtn');
                                        const unreadBadge = document.getElementById('unreadBadge');
                                        const refreshChatBtn = document.getElementById('refreshChatBtn');
                                        const markAllReadBtn = document.getElementById('markAllReadBtn');
                                        
                                        // پنل‌ها
                                        const conversationsPanel = document.getElementById('conversationsPanel');
                                        const usersPanel = document.getElementById('usersPanel');
                                        const messagesPanel = document.getElementById('messagesPanel');
                                        
                                        // لیست‌ها
                                        const conversationsList = document.getElementById('conversationsList');
                                        const usersList = document.getElementById('usersList');
                                        const messagesContainer = document.getElementById('messagesContainer');
                                        
                                        // عناصر ورودی
                                        const messageInput = document.getElementById('messageInput');
                                        const sendMessageBtn = document.getElementById('sendMessageBtn');
                                        const chatSearchInput = document.getElementById('chatSearchInput');
                                        
                                        // دکمه‌های فایل
                                        const attachButton = document.getElementById('attachButton');
                                        const attachImageBtn = document.getElementById('attachImageBtn');
                                        const attachAudioBtn = document.getElementById('attachAudioBtn');
                                        const voiceRecordBtn = document.getElementById('voiceRecordBtn');
                                        const voiceIcon = document.getElementById('voiceIcon');
                                        const stopVoiceIcon = document.getElementById('stopVoiceIcon');
                                        const imageInput = document.getElementById('imageInput');
                                        const audioInput = document.getElementById('audioInput');
                                        
                                        // پیش‌نمایش رسانه
                                        const mediaPreview = document.getElementById('mediaPreview');
                                        const mediaFileName = document.getElementById('mediaFileName');
                                        const imagePreview = document.getElementById('imagePreview');
                                        const previewImage = document.getElementById('previewImage');
                                        const removeMediaBtn = document.getElementById('removeMediaBtn');
                                        
                                        // ضبط صوت
                                        const recordingIndicator = document.getElementById('recordingIndicator');
                                        const recordingTimer = document.getElementById('recordingTimer');
                                        const cancelRecordingBtn = document.getElementById('cancelRecordingBtn');
                                        
                                        // تب‌ها
                                        const conversationsTab = document.getElementById('conversationsTab');
                                        const usersTab = document.getElementById('usersTab');
                                        
                                        // دکمه‌های برگشت
                                        const backToChat = document.getElementById('backToChat');
                                        
                                        // صداها
                                        const messageSound = document.getElementById('messageSound');
                                        
                                        // متغیرهای حالت
                                        let currentChatUserId = null;
                                        let currentChatUserName = null;
                                        let currentChatUserImage = null;
                                        let pollingInterval = null;
                                        let backgroundPollingInterval = null;
                                        let conversations = [];
                                        let users = [];
                                        let isChatOpen = false;
                                        let currentTab = 'conversations';
                                        let isMobile = window.innerWidth <= 768;
                                        let previousUnreadCount = 0;
                                        let lastMessageId = 0;
                                        let selectedMedia = {
                                            type: null,
                                            file: null,
                                            url: null
                                        };
                                        
                                        // ضبط صوت
                                        let mediaRecorder = null;
                                        let audioChunks = [];
                                        let recordingStartTime = 0;
                                        let recordingTimerInterval = null;
                                        let isRecording = false;
                                        
                                        // پخش صوت
                                        let audioPlayers = new Map();
                                        
                                        // مدیریت پیام‌های بارگذاری شده
                                        let loadedMessageIds = new Set();
                                        
                                        // CSRF Token
                                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                        
                                        // -------------------------
                                        // راه‌اندازی اولیه
                                        // -------------------------
                                        setupEventListeners();
                                        updateUnreadCount();
                                        startBackgroundPolling();
                                        
                                        // -------------------------
                                        // رویدادها
                                        // -------------------------
                                        function setupEventListeners() {
                                            // رویدادهای اصلی
                                            chatToggle.addEventListener('click', toggleChatWindow);
                                            closeChatBtn.addEventListener('click', closeChatWindow);
                                            refreshChatBtn.addEventListener('click', refreshChatData);
                                            markAllReadBtn.addEventListener('click', markAllAsRead);
                                            sendMessageBtn.addEventListener('click', sendMessage);
                                            backToChat.addEventListener('click', showChatView);
                                            
                                            // تب‌ها
                                            conversationsTab.addEventListener('click', () => switchTab('conversations'));
                                            usersTab.addEventListener('click', () => switchTab('users'));
                                            
                                            // ورودی پیام
                                            messageInput.addEventListener('keypress', (e) => {
                                                if (e.key === 'Enter' && !e.shiftKey) {
                                                    e.preventDefault();
                                                    sendMessage();
                                                }
                                            });
                                            
                                            // جستجو
                                            chatSearchInput.addEventListener('input', debounce(searchUsers, 300));
                                            
                                            // رسانه
                                            attachImageBtn.addEventListener('click', () => imageInput.click());
                                            attachAudioBtn.addEventListener('click', () => audioInput.click());
                                            imageInput.addEventListener('change', handleImageSelect);
                                            audioInput.addEventListener('change', handleAudioSelect);
                                            removeMediaBtn.addEventListener('click', clearMedia);
                                            voiceRecordBtn.addEventListener('click', toggleRecording);
                                            cancelRecordingBtn.addEventListener('click', cancelRecording);
                                            
                                            // پخش صدا
                                            if (messageSound) {
                                                messageSound.volume = 0.3;
                                            }
                                            
                                            // اندازه پنجره
                                            window.addEventListener('resize', handleResize);
                                            
                                            // وقتی تب غیرفعال می‌شود
                                            document.addEventListener('visibilitychange', handleVisibilityChange);
                                            
                                            // فعال‌سازی صدا با اولین کلیک کاربر
                                            document.addEventListener('click', function initAudio() {
                                                if (messageSound) {
                                                    try {
                                                        messageSound.volume = 0.01;
                                                        const playPromise = messageSound.play();
                                                        if (playPromise !== undefined) {
                                                            playPromise.then(() => {
                                                                messageSound.pause();
                                                                messageSound.currentTime = 0;
                                                                messageSound.volume = 0.3;
                                                            }).catch(e => {
                                                                console.log('فعال‌سازی صدا:', e);
                                                            });
                                                        }
                                                    } catch (error) {
                                                        console.log('خطا در فعال‌سازی صدا:', error);
                                                    }
                                                }
                                                document.removeEventListener('click', initAudio);
                                            }, { once: true });
                                        }
                                        
                                        function handleResize() {
                                            isMobile = window.innerWidth <= 768;
                                        }
                                        
                                        function handleVisibilityChange() {
                                            if (document.hidden) {
                                                stopPolling();
                                                if (backgroundPollingInterval) {
                                                    clearInterval(backgroundPollingInterval);
                                                    backgroundPollingInterval = setInterval(updateUnreadCount, 30000);
                                                }
                                            } else {
                                                updateUnreadCount();
                                                if (isChatOpen) startPolling();
                                                if (backgroundPollingInterval) {
                                                    clearInterval(backgroundPollingInterval);
                                                    backgroundPollingInterval = setInterval(updateUnreadCount, 15000);
                                                }
                                            }
                                        }
                                        
                                        // -------------------------
                                        // توابع مدیریت پنجره چت
                                        // -------------------------
                                        function toggleChatWindow() {
                                            isChatOpen = !isChatOpen;
                                            
                                            if (isMobile) {
                                                if (isChatOpen) {
                                                    chatWindow.classList.remove('hidden');
                                                    setTimeout(() => {
                                                        chatWindow.style.transform = 'translateY(0)';
                                                    }, 10);
                                                    document.body.classList.add('chat-open');
                                                } else {
                                                    chatWindow.style.transform = 'translateY(100%)';
                                                    setTimeout(() => {
                                                        chatWindow.classList.add('hidden');
                                                        chatWindow.style.transform = '';
                                                        document.body.classList.remove('chat-open');
                                                    }, 300);
                                                }
                                            } else {
                                                chatWindow.classList.toggle('hidden');
                                            }
                                            
                                            if (isChatOpen) {
                                                loadConversations();
                                                updateUnreadCount();
                                                startPolling();
                                                switchTab('conversations');
                                            } else {
                                                stopPolling();
                                            }
                                        }
                                        
                                        function closeChatWindow() {
                                            if (isMobile) {
                                                chatWindow.style.transform = 'translateY(100%)';
                                                setTimeout(() => {
                                                    chatWindow.classList.add('hidden');
                                                    chatWindow.style.transform = '';
                                                    isChatOpen = false;
                                                    document.body.classList.remove('chat-open');
                                                }, 300);
                                            } else {
                                                isChatOpen = false;
                                                chatWindow.classList.add('hidden');
                                            }
                                            stopPolling();
                                        }
                                        
                                        function switchTab(tabName) {
                                            currentTab = tabName;
                                            
                                            conversationsTab.classList.remove('border-[#122EE1]', 'text-[#122EE1]');
                                            conversationsTab.classList.add('text-gray-500', 'hover:text-gray-700');
                                            usersTab.classList.remove('border-[#122EE1]', 'text-[#122EE1]');
                                            usersTab.classList.add('text-gray-500', 'hover:text-gray-700');
                                            
                                            if (tabName === 'conversations') {
                                                conversationsTab.classList.add('border-[#122EE1]', 'text-[#122EE1]');
                                                conversationsPanel.classList.remove('hidden');
                                                usersPanel.classList.add('hidden');
                                                loadConversations();
                                            } else {
                                                usersTab.classList.add('border-[#122EE1]', 'text-[#122EE1]');
                                                conversationsPanel.classList.add('hidden');
                                                usersPanel.classList.remove('hidden');
                                                loadChatUsers();
                                            }
                                            
                                            messagesPanel.classList.add('hidden');
                                        }
                                        
                                        function showChatView() {
                                            conversationsPanel.classList.remove('hidden');
                                            usersPanel.classList.remove('hidden');
                                            messagesPanel.classList.add('hidden');
                                            switchTab(currentTab);
                                        }
                                        
                                        // -------------------------
                                        // توابع صدا
                                        // -------------------------
                                        function playMessageSound() {
                                            if (!messageSound) return;
                                            
                                            try {
                                                messageSound.currentTime = 0;
                                                const playPromise = messageSound.play();
                                                if (playPromise !== undefined) {
                                                    playPromise.catch(error => {
                                                        console.log('خطا در پخش صدا:', error);
                                                    });
                                                }
                                            } catch (error) {
                                                console.log('خطا در پخش صدا:', error);
                                            }
                                        }
                                        
                                        // -------------------------
                                        // توابع بارگذاری داده‌ها
                                        // -------------------------
                                        async function loadConversations() {
                                            try {
                                                showLoading(conversationsList, 'در حال بارگذاری مکالمات...');
                                                
                                                const response = await fetch('/chat/conversations', {
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    cache: 'no-cache'
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    conversations = data.conversations;
                                                    renderConversations(conversations);
                                                    
                                                    if (conversations.length === 0) {
                                                        document.getElementById('noConversations').classList.remove('hidden');
                                                    } else {
                                                        document.getElementById('noConversations').classList.add('hidden');
                                                    }
                                                }
                                            } catch (error) {
                                                console.error('Error loading conversations:', error);
                                                showError(conversationsList, 'خطا در بارگذاری مکالمات');
                                            }
                                        }
                                        
                                        async function loadChatUsers() {
                                            try {
                                                showLoading(usersList, 'در حال بارگذاری کاربران...');
                                                
                                                const response = await fetch('/chat/users', {
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    cache: 'no-cache'
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    users = data.users;
                                                    renderUsers(users);
                                                    
                                                    if (users.length === 0) {
                                                        document.getElementById('noUsers').classList.remove('hidden');
                                                    } else {
                                                        document.getElementById('noUsers').classList.add('hidden');
                                                    }
                                                }
                                            } catch (error) {
                                                console.error('Error loading users:', error);
                                                showError(usersList, 'خطا در بارگذاری کاربران');
                                            }
                                        }
                                        
                                        async function loadMessages() {
                                            if (!currentChatUserId) return;
                                            
                                            try {
                                                showLoading(messagesContainer, 'در حال بارگذاری پیام‌ها...');
                                                
                                                const response = await fetch(`/chat/messages/${currentChatUserId}`, {
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    cache: 'no-cache'
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    // پاک کردن لیست IDهای بارگذاری شده
                                                    loadedMessageIds.clear();
                                                    renderMessages(data.messages);
                                                    updateUnreadCount();
                                                    
                                                    if (data.messages.length > 0) {
                                                        lastMessageId = data.messages[data.messages.length - 1].id;
                                                        // اضافه کردن IDهای بارگذاری شده
                                                        data.messages.forEach(msg => loadedMessageIds.add(msg.id));
                                                    }
                                                    
                                                    setTimeout(scrollToBottom, 100);
                                                }
                                            } catch (error) {
                                                console.error('Error loading messages:', error);
                                                showError(messagesContainer, 'خطا در بارگذاری پیام‌ها');
                                            }
                                        }
                                        
                                        async function loadNewMessages() {
                                            if (!currentChatUserId) return;
                                            
                                            try {
                                                const messageElements = messagesContainer.querySelectorAll('[data-message-id]');
                                                let currentLastMessageId = lastMessageId || 0;
                                                
                                                const previousScrollHeight = messagesContainer.scrollHeight;
                                                const previousScrollTop = messagesContainer.scrollTop;
                                                const wasAtBottom = Math.abs(
                                                    messagesContainer.scrollHeight - 
                                                    messagesContainer.scrollTop - 
                                                    messagesContainer.clientHeight
                                                ) < 50;
                                                
                                                const response = await fetch(`/chat/new-messages/${currentChatUserId}?last_message_id=${currentLastMessageId}`, {
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    cache: 'no-cache'
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success && data.messages.length > 0) {
                                                    const currentUserId = {{ Auth::guard('sarafi')->id() ?? 0 }};
                                                    let newMessagesCount = 0;
                                                    
                                                    // فیلتر پیام‌هایی که قبلاً بارگذاری نشده‌اند
                                                    const uniqueMessages = data.messages.filter(msg => !loadedMessageIds.has(msg.id));
                                                    
                                                    uniqueMessages.forEach(msg => {
                                                        renderMessage(msg, msg.sender_id == currentUserId);
                                                        loadedMessageIds.add(msg.id);
                                                        newMessagesCount++;
                                                        
                                                        if (msg.id > currentLastMessageId) {
                                                            currentLastMessageId = msg.id;
                                                        }
                                                    });
                                                    
                                                    lastMessageId = currentLastMessageId;
                                                    updateUnreadCount();
                                                    
                                                    if (wasAtBottom && newMessagesCount > 0) {
                                                        setTimeout(scrollToBottom, 100);
                                                    } else if (newMessagesCount > 0) {
                                                        const newScrollHeight = messagesContainer.scrollHeight;
                                                        const heightDiff = newScrollHeight - previousScrollHeight;
                                                        messagesContainer.scrollTop = previousScrollTop + heightDiff;
                                                    }
                                                }
                                            } catch (error) {
                                                console.error('Error loading new messages:', error);
                                            }
                                        }
                                        
                                        // -------------------------
                                        // توابع رندر
                                        // -------------------------
                                        function renderConversations(conversations) {
                                            conversationsList.innerHTML = '';
                                            
                                            if (!conversations || conversations.length === 0) {
                                                document.getElementById('noConversations').classList.remove('hidden');
                                                return;
                                            }
                                            
                                            conversations.forEach(conv => {
                                                const conversationItem = document.createElement('div');
                                                conversationItem.className = 'conversation-item';
                                                
                                                const displayName = `${conv.other_user.name} ${conv.other_user.lastname}`;
                                                const userImage = conv.other_user.image_url;
                                                
                                                let avatarHtml = '';
                                                if (userImage) {
                                                    avatarHtml = `
                                                        <div class="user-avatar-image">
                                                            <img src="${userImage}" 
                                                                alt="${displayName}" 
                                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                        </div>
                                                    `;
                                                } else {
                                                    const avatarColors = ['avatar-blue', 'avatar-green', 'avatar-purple', 'avatar-pink', 'avatar-orange'];
                                                    const colorIndex = conv.other_user.name.length % avatarColors.length;
                                                    avatarHtml = `
                                                        <div class="user-avatar ${avatarColors[colorIndex]}">
                                                            ${conv.other_user.name.charAt(0)}
                                                        </div>
                                                    `;
                                                }
                                                
                                                conversationItem.innerHTML = `
                                                    <div class="flex items-center">
                                                        ${avatarHtml}
                                                        <div class="mr-3 flex-1 min-w-0">
                                                            <div class="flex justify-between items-center">
                                                                <h4 class="font-semibold truncate">${displayName}</h4>
                                                                ${conv.unread_count > 0 ? `<span class="unread-badge flex-shrink-0">${conv.unread_count}</span>` : ''}
                                                            </div>
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">${conv.last_message || ''}</p>
                                                            <small class="text-xs text-gray-500">${conv.last_message_at ? formatDate(conv.last_message_at) : ''}</small>
                                                        </div>
                                                    </div>
                                                `;
                                                
                                                conversationItem.addEventListener('click', () => openChat(
                                                    conv.other_user.id, 
                                                    displayName, 
                                                    userImage
                                                ));
                                                conversationsList.appendChild(conversationItem);
                                            });
                                            
                                            document.getElementById('noConversations').classList.add('hidden');
                                        }
                                        
                                        function renderUsers(users) {
                                            usersList.innerHTML = '';
                                            
                                            if (!users || users.length === 0) {
                                                document.getElementById('noUsers').classList.remove('hidden');
                                                return;
                                            }
                                            
                                            users.forEach(user => {
                                                const userItem = document.createElement('div');
                                                userItem.className = 'conversation-item';
                                                
                                                const displayName = `${user.name} ${user.lastname}`;
                                                const roleText = user.role === 'superadmin' ? 'سوپر ادمین' : 
                                                                user.role === 'admin' ? 'ادمین' : 'انباردار';
                                                
                                                let avatarHtml = '';
                                                if (user.image_url) {
                                                    avatarHtml = `
                                                        <div class="user-avatar-image">
                                                            <img src="${user.image_url}" 
                                                                alt="${displayName}" 
                                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                        </div>
                                                    `;
                                                } else {
                                                    const avatarColors = ['avatar-blue', 'avatar-green', 'avatar-purple', 'avatar-pink', 'avatar-orange'];
                                                    const colorIndex = user.name.length % avatarColors.length;
                                                    avatarHtml = `
                                                        <div class="user-avatar ${avatarColors[colorIndex]}">
                                                            ${user.name.charAt(0)}
                                                        </div>
                                                    `;
                                                }
                                                
                                                userItem.innerHTML = `
                                                    <div class="flex items-center">
                                                        ${avatarHtml}
                                                        <div class="mr-3 flex-1 min-w-0">
                                                            <h4 class="font-semibold truncate">${displayName}</h4>
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">${roleText} - ${user.sarafi_name}</p>
                                                        </div>
                                                        <button class="text-[#122EE1] hover:text-blue-700 flex-shrink-0">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                `;
                                                
                                                userItem.addEventListener('click', () => openChat(
                                                    user.id, 
                                                    displayName, 
                                                    user.image_url
                                                ));
                                                usersList.appendChild(userItem);
                                            });
                                            
                                            document.getElementById('noUsers').classList.add('hidden');
                                        }
                                        
                                        function renderMessages(messages) {
                                            messagesContainer.innerHTML = '';
                                            
                                            const currentUserId = {{ Auth::guard('sarafi')->id() ?? 0 }};
                                            
                                            if (!messages || messages.length === 0) {
                                                messagesContainer.innerHTML = `
                                                    <div class="text-center text-gray-500 py-8">
                                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                        </svg>
                                                        <p>هیچ پیامی وجود ندارد</p>
                                                        <p class="text-sm mt-2">پیام خود را ارسال کنید</p>
                                                    </div>
                                                `;
                                                return;
                                            }
                                            
                                            messages.forEach(msg => {
                                                renderMessage(msg, msg.sender_id == currentUserId);
                                            });
                                        }
                                        
                                        function renderMessage(msg, isSent) {
                                            // اگر پیام قبلاً نمایش داده شده، نمایش نده
                                            if (document.querySelector(`[data-message-id="${msg.id}"]`)) {
                                                return;
                                            }
                                            
                                            const messageDiv = document.createElement('div');
                                            messageDiv.className = `flex items-end gap-2 ${isSent ? 'justify-end' : 'justify-start'}`;
                                            messageDiv.dataset.messageId = msg.id;
                                            messageDiv.dataset.senderId = msg.sender_id;
                                            
                                            const time = new Date(msg.created_at).toLocaleTimeString('fa-IR', {
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            });
                                            
                                            let content = '';
                                            
                                            switch (msg.type) {
                                                case 'image':
                                                    content = renderImageMessage(msg, isSent);
                                                    break;
                                                case 'audio':
                                                    content = renderAudioMessage(msg, isSent);
                                                    break;
                                                default:
                                                    content = renderTextMessage(msg, isSent);
                                            }
                                            
                                            const userImage = msg.sender?.image_url;
                                            let avatarHtml = '';
                                            
                                            if (!isSent && userImage) {
                                                avatarHtml = `
                                                    <img src="${userImage}" 
                                                        alt="${msg.sender.name}" 
                                                        class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                                `;
                                            }
                                            
                                            messageDiv.innerHTML = `
                                                ${!isSent ? avatarHtml : ''}
                                                <div class="relative">
                                                    ${content}
                                                    <span class="time">${time}</span>
                                                    ${isSent ? `
                                                        <button class="delete-message-btn" onclick="deleteMessage(${msg.id})">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    ` : ''}
                                                </div>
                                                ${isSent ? avatarHtml : ''}
                                            `;
                                            
                                            messagesContainer.appendChild(messageDiv);
                                        }
                                        
                                        function renderTextMessage(msg, isSent) {
                                            return `
                                                <div class="chat-message ${isSent ? 'sent' : 'received'}">
                                                    <p>${escapeHtml(msg.message)}</p>
                                                </div>
                                            `;
                                        }
                                        
                                        function renderImageMessage(msg, isSent) {
                                            const imageUrl = msg.media_url || msg.message;
                                            return `
                                                <div class="chat-message ${isSent ? 'sent' : 'received'} media-message">
                                                    <img src="${imageUrl}" 
                                                        alt="عکس" 
                                                        class="cursor-pointer max-w-full rounded-lg"
                                                        onclick="showImageModal('${imageUrl}')"
                                                        loading="lazy">
                                                </div>
                                            `;
                                        }
                                        
                                        function renderAudioMessage(msg, isSent) {
                                            const audioUrl = msg.media_url || msg.message;
                                            const duration = msg.duration || 0;
                                            const formattedDuration = formatDuration(duration);
                                            const audioId = 'audio-' + msg.id;
                                            
                                            return `
                                                <div class="chat-message ${isSent ? 'sent' : 'received'} audio-message">
                                                    <div class="audio-player">
                                                        <div class="audio-controls">
                                                            <button class="play-pause-btn" onclick="toggleAudioPlay('${audioId}', '${audioUrl}', ${duration})">
                                                                <svg id="play-icon-${audioId}" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M8 5v14l11-7z"></path>
                                                                </svg>
                                                                <svg id="pause-icon-${audioId}" class="w-4 h-4 hidden" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path>
                                                                </svg>
                                                            </button>
                                                            <div class="progress-bar" onclick="seekAudio('${audioId}', event)">
                                                                <div id="progress-${audioId}" class="progress"></div>
                                                            </div>
                                                        </div>
                                                        <div class="audio-time">
                                                            <span id="current-time-${audioId}">0:00</span>
                                                        </div>
                                                    </div>
                                                    <span class="audio-duration">${formattedDuration}</span>
                                                    <audio id="${audioId}" preload="none" ontimeupdate="updateAudioProgress('${audioId}')" onended="onAudioEnded('${audioId}')">
                                                        <source src="${audioUrl}" type="audio/mpeg">
                                                    </audio>
                                                </div>
                                            `;
                                        }
                                        
                                        // -------------------------
                                        // توابع مدیریت رسانه
                                        // -------------------------
                                        function handleImageSelect(e) {
                                            if (e.target.files.length > 0) {
                                                const file = e.target.files[0];
                                                if (file.size > 5 * 1024 * 1024) {
                                                    showToast('حجم عکس نباید بیشتر از 5 مگابایت باشد', 'error');
                                                    return;
                                                }
                                                
                                                selectedMedia = {
                                                    type: 'image',
                                                    file: file,
                                                    url: URL.createObjectURL(file)
                                                };
                                                
                                                showMediaPreview();
                                            }
                                        }
                                        
                                        function handleAudioSelect(e) {
                                            if (e.target.files.length > 0) {
                                                const file = e.target.files[0];
                                                
                                                // بررسی حجم فایل (10MB)
                                                const maxSize = 10 * 1024 * 1024; // 10MB
                                                if (file.size > maxSize) {
                                                    showToast('حجم فایل صوتی نباید بیشتر از 10 مگابایت باشد', 'error');
                                                    audioInput.value = '';
                                                    return;
                                                }
                                                
                                                // بررسی فرمت فایل
                                                const allowedExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'mp4', 'webm'];
                                                const allowedMimeTypes = [
                                                    'audio/mpeg', 
                                                    'audio/wav', 
                                                    'audio/ogg', 
                                                    'audio/mp4',
                                                    'audio/x-m4a',
                                                    'audio/webm',
                                                    'video/mp4'
                                                ];
                                                const fileExtension = file.name.split('.').pop().toLowerCase();
                                                
                                                if (!allowedExtensions.includes(fileExtension) && !allowedMimeTypes.includes(file.type)) {
                                                    showToast('فرمت فایل صوتی نامعتبر است. فرمت‌های مجاز: MP3, WAV, OGG, M4A', 'error');
                                                    audioInput.value = '';
                                                    return;
                                                }
                                                
                                                selectedMedia = {
                                                    type: 'audio',
                                                    file: file,
                                                    url: URL.createObjectURL(file),
                                                    duration: 0
                                                };
                                                
                                                showMediaPreview();
                                                showToast('فایل صوتی آماده ارسال است', 'success');
                                            }
                                        }
                                        
                                        function showMediaPreview() {
                                            mediaPreview.classList.remove('hidden');
                                            mediaFileName.textContent = selectedMedia.file.name;
                                            
                                            if (selectedMedia.type === 'image') {
                                                imagePreview.classList.remove('hidden');
                                                previewImage.src = selectedMedia.url;
                                            } else {
                                                imagePreview.classList.add('hidden');
                                            }
                                            
                                            messageInput.focus();
                                        }
                                        
                                        function clearMedia() {
                                            if (selectedMedia.url) {
                                                URL.revokeObjectURL(selectedMedia.url);
                                            }
                                            
                                            selectedMedia = {
                                                type: null,
                                                file: null,
                                                url: null
                                            };
                                            
                                            mediaPreview.classList.add('hidden');
                                            imagePreview.classList.add('hidden');
                                            imageInput.value = '';
                                            audioInput.value = '';
                                            messageInput.focus();
                                        }
                                        
                                        // -------------------------
                                        // توابع ضبط صوت
                                        // -------------------------
                                        async function toggleRecording() {
                                            if (isRecording) {
                                                stopRecording();
                                            } else {
                                                await startRecording();
                                            }
                                        }
                                        
                                        async function startRecording() {
                                            try {
                                                const stream = await navigator.mediaDevices.getUserMedia({ 
                                                    audio: {
                                                        channelCount: 1,
                                                        sampleRate: 44100,
                                                        echoCancellation: true,
                                                        noiseSuppression: true,
                                                        autoGainControl: true
                                                    }
                                                });
                                                
                                                const options = {
                                                    audioBitsPerSecond: 128000,
                                                    mimeType: 'audio/webm;codecs=opus'
                                                };
                                                
                                                if (!MediaRecorder.isTypeSupported(options.mimeType)) {
                                                    options.mimeType = 'audio/webm';
                                                }
                                                
                                                mediaRecorder = new MediaRecorder(stream, options);
                                                audioChunks = [];
                                                
                                                mediaRecorder.ondataavailable = (event) => {
                                                    if (event.data.size > 0) {
                                                        audioChunks.push(event.data);
                                                    }
                                                };
                                                
                                                mediaRecorder.onstop = async () => {
                                                    try {
                                                        const audioBlob = new Blob(audioChunks, { type: mediaRecorder.mimeType });
                                                        const duration = Math.floor((Date.now() - recordingStartTime) / 1000);
                                                        
                                                        const fileName = `voice-message-${Date.now()}.webm`;
                                                        const file = new File([audioBlob], fileName, { 
                                                            type: mediaRecorder.mimeType,
                                                            lastModified: Date.now()
                                                        });
                                                        
                                                        selectedMedia = {
                                                            type: 'audio',
                                                            file: file,
                                                            url: URL.createObjectURL(audioBlob),
                                                            duration: duration
                                                        };
                                                        
                                                        showMediaPreview();
                                                        
                                                        stream.getTracks().forEach(track => {
                                                            track.stop();
                                                            track.enabled = false;
                                                        });
                                                        
                                                    } catch (error) {
                                                        console.error('Error processing recording:', error);
                                                        showToast('خطا در پردازش ضبط صوت', 'error');
                                                    }
                                                };
                                                
                                                mediaRecorder.onerror = (event) => {
                                                    console.error('MediaRecorder error:', event.error);
                                                    showToast('خطا در ضبط صوت', 'error');
                                                    cancelRecording();
                                                };
                                                
                                                // شروع ضبط
                                                mediaRecorder.start(1000);
                                                recordingStartTime = Date.now();
                                                isRecording = true;
                                                
                                                // نمایش نشانگر ضبط
                                                recordingIndicator.classList.remove('hidden');
                                                voiceRecordBtn.classList.add('recording');
                                                voiceIcon.classList.add('hidden');
                                                stopVoiceIcon.classList.remove('hidden');
                                                
                                                updateRecordingTimer();
                                                recordingTimerInterval = setInterval(updateRecordingTimer, 1000);
                                                
                                                // توقف خودکار بعد از 5 دقیقه
                                                setTimeout(() => {
                                                    if (isRecording) {
                                                        stopRecording();
                                                        showToast('ضبط صوت به صورت خودکار متوقف شد', 'info');
                                                    }
                                                }, 300000);
                                                
                                            } catch (error) {
                                                console.error('Error starting recording:', error);
                                                
                                                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                                                    showToast('دسترسی به میکروفون رد شد. لطفاً مجوز را در تنظیمات مرورگر فعال کنید.', 'error');
                                                } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                                                    showToast('هیچ میکروفونی یافت نشد.', 'error');
                                                } else if (error.name === 'NotSupportedError') {
                                                    showToast('مرورگر شما از ضبط صوت پشتیبانی نمی‌کند.', 'error');
                                                } else {
                                                    showToast('خطا در دسترسی به میکروفون: ' + error.message, 'error');
                                                }
                                            }
                                        }
                                        
                                        function stopRecording() {
                                            if (mediaRecorder && mediaRecorder.state === 'recording') {
                                                mediaRecorder.stop();
                                                recordingIndicator.classList.add('hidden');
                                                voiceRecordBtn.classList.remove('recording');
                                                voiceIcon.classList.remove('hidden');
                                                stopVoiceIcon.classList.add('hidden');
                                                clearInterval(recordingTimerInterval);
                                                isRecording = false;
                                            }
                                        }
                                        
                                        function cancelRecording() {
                                            if (mediaRecorder && mediaRecorder.state === 'recording') {
                                                mediaRecorder.stop();
                                                recordingIndicator.classList.add('hidden');
                                                voiceRecordBtn.classList.remove('recording');
                                                voiceIcon.classList.remove('hidden');
                                                stopVoiceIcon.classList.add('hidden');
                                                clearInterval(recordingTimerInterval);
                                                isRecording = false;
                                                clearMedia();
                                            }
                                        }
                                        
                                        function updateRecordingTimer() {
                                            const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
                                            const minutes = Math.floor(elapsed / 60);
                                            const seconds = elapsed % 60;
                                            recordingTimer.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                        }
                                        
                                        // -------------------------
                                        // توابع ارسال پیام
                                        // -------------------------
                                        async function sendMessage() {
                                            const textMessage = messageInput.value.trim();
                                            
                                            if (!textMessage && !selectedMedia.file) {
                                                showToast('پیام یا فایل را وارد کنید', 'warning');
                                                return;
                                            }
                                            
                                            if (!currentChatUserId) {
                                                showToast('لطفاً ابتدا کاربری را انتخاب کنید', 'warning');
                                                return;
                                            }
                                            
                                            let tempMessage = null;
                                            
                                            // ایجاد پیام موقت
                                            if (selectedMedia.file) {
                                                tempMessage = {
                                                    id: Date.now(),
                                                    sender_id: {{ Auth::guard('sarafi')->id() ?? 0 }},
                                                    type: selectedMedia.type,
                                                    message: selectedMedia.type === 'image' ? 'در حال ارسال عکس...' : 'در حال ارسال صوت...',
                                                    media_url: selectedMedia.url,
                                                    created_at: new Date().toISOString(),
                                                    sender: {
                                                        name: '{{ Auth::guard("sarafi")->user()->name ?? "کاربر" }}',
                                                        lastname: '{{ Auth::guard("sarafi")->user()->lastname ?? "" }}'
                                                    }
                                                };
                                            } else if (textMessage) {
                                                tempMessage = {
                                                    id: Date.now(),
                                                    sender_id: {{ Auth::guard('sarafi')->id() ?? 0 }},
                                                    type: 'text',
                                                    message: textMessage,
                                                    created_at: new Date().toISOString(),
                                                    sender: {
                                                        name: '{{ Auth::guard("sarafi")->user()->name ?? "کاربر" }}',
                                                        lastname: '{{ Auth::guard("sarafi")->user()->lastname ?? "" }}'
                                                    }
                                                };
                                            }
                                            
                                            // نمایش پیام موقت
                                            if (tempMessage) {
                                                renderMessage(tempMessage, true);
                                                setTimeout(scrollToBottom, 50);
                                            }
                                            
                                            // ارسال به سرور
                                            try {
                                                const formData = new FormData();
                                                formData.append('receiver_id', currentChatUserId);
                                                
                                                if (selectedMedia.file) {
                                                    formData.append('type', selectedMedia.type);
                                                    formData.append('media', selectedMedia.file);
                                                    
                                                    if (textMessage) {
                                                        formData.append('message', textMessage);
                                                    } else {
                                                        formData.append('message', selectedMedia.type === 'image' ? 'عکس ارسال شد' : 'پیام صوتی ارسال شد');
                                                    }
                                                } else {
                                                    formData.append('type', 'text');
                                                    formData.append('message', textMessage);
                                                }
                                                
                                                const response = await fetch('/chat/send', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    body: formData
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    // حذف پیام موقت
                                                    const tempMessageElement = messagesContainer.querySelector(`[data-message-id="${tempMessage.id}"]`);
                                                    if (tempMessageElement) {
                                                        tempMessageElement.remove();
                                                    }
                                                    
                                                    // نمایش پیام واقعی
                                                    renderMessage(data.message, true);
                                                    loadedMessageIds.add(data.message.id);
                                                    
                                                    if (data.message.id > lastMessageId) {
                                                        lastMessageId = data.message.id;
                                                    }
                                                    
                                                    loadConversations();
                                                    clearMedia();
                                                    messageInput.value = '';
                                                    
                                                    setTimeout(scrollToBottom, 50);
                                                    showToast('پیام با موفقیت ارسال شد', 'success');
                                                } else {
                                                    if (data.errors) {
                                                        let errorMessage = '';
                                                        Object.values(data.errors).forEach(errors => {
                                                            errors.forEach(error => {
                                                                errorMessage += error + '\n';
                                                            });
                                                        });
                                                        showToast(errorMessage, 'error');
                                                    } else {
                                                        showToast(data.error || 'خطا در ارسال پیام', 'error');
                                                    }
                                                }
                                            } catch (error) {
                                                console.error('Error sending message:', error);
                                                showToast('خطا در ارسال پیام: ' + error.message, 'error');
                                            }
                                        }
                                        
                                        // -------------------------
                                        // توابع مدیریت پخش صوت
                                        // -------------------------
                                        function toggleAudioPlay(audioId, audioUrl, duration) {
                                            const audioElement = document.getElementById(audioId);
                                            const playIcon = document.getElementById('play-icon-' + audioId);
                                            const pauseIcon = document.getElementById('pause-icon-' + audioId);
                                            
                                            if (!audioElement.src) {
                                                audioElement.src = audioUrl;
                                            }
                                            
                                            if (audioElement.paused) {
                                                // توقف تمام پخش‌کننده‌های دیگر
                                                audioPlayers.forEach((player, id) => {
                                                    if (id !== audioId) {
                                                        player.pause();
                                                        const otherPlayIcon = document.getElementById('play-icon-' + id);
                                                        const otherPauseIcon = document.getElementById('pause-icon-' + id);
                                                        if (otherPlayIcon && otherPauseIcon) {
                                                            otherPlayIcon.classList.remove('hidden');
                                                            otherPauseIcon.classList.add('hidden');
                                                        }
                                                    }
                                                });
                                                
                                                audioElement.play();
                                                playIcon.classList.add('hidden');
                                                pauseIcon.classList.remove('hidden');
                                                audioPlayers.set(audioId, audioElement);
                                            } else {
                                                audioElement.pause();
                                                playIcon.classList.remove('hidden');
                                                pauseIcon.classList.add('hidden');
                                                audioPlayers.delete(audioId);
                                            }
                                        }
                                        
                                        function updateAudioProgress(audioId) {
                                            const audioElement = document.getElementById(audioId);
                                            const progressBar = document.getElementById('progress-' + audioId);
                                            const currentTimeSpan = document.getElementById('current-time-' + audioId);
                                            
                                            if (audioElement.duration) {
                                                const progress = (audioElement.currentTime / audioElement.duration) * 100;
                                                progressBar.style.width = progress + '%';
                                                
                                                const currentMinutes = Math.floor(audioElement.currentTime / 60);
                                                const currentSeconds = Math.floor(audioElement.currentTime % 60);
                                                currentTimeSpan.textContent = `${currentMinutes}:${currentSeconds.toString().padStart(2, '0')}`;
                                            }
                                        }
                                        
                                        function seekAudio(audioId, event) {
                                            const audioElement = document.getElementById(audioId);
                                            const progressBar = event.currentTarget;
                                            const clickPosition = event.offsetX;
                                            const progressBarWidth = progressBar.clientWidth;
                                            const percentage = clickPosition / progressBarWidth;
                                            
                                            audioElement.currentTime = percentage * audioElement.duration;
                                        }
                                        
                                        function onAudioEnded(audioId) {
                                            const playIcon = document.getElementById('play-icon-' + audioId);
                                            const pauseIcon = document.getElementById('pause-icon-' + audioId);
                                            
                                            if (playIcon && pauseIcon) {
                                                playIcon.classList.remove('hidden');
                                                pauseIcon.classList.add('hidden');
                                            }
                                            
                                            const progressBar = document.getElementById('progress-' + audioId);
                                            if (progressBar) {
                                                progressBar.style.width = '0%';
                                            }
                                            
                                            const currentTimeSpan = document.getElementById('current-time-' + audioId);
                                            if (currentTimeSpan) {
                                                currentTimeSpan.textContent = '0:00';
                                            }
                                            
                                            audioPlayers.delete(audioId);
                                        }
                                        
                                        // -------------------------
                                        // توابع کمکی
                                        // -------------------------
                                        async function openChat(userId, userName, userImage = null) {
                                            currentChatUserId = userId;
                                            currentChatUserName = userName;
                                            currentChatUserImage = userImage;
                                            
                                            // نمایش پنل پیام‌ها
                                            conversationsPanel.classList.add('hidden');
                                            usersPanel.classList.add('hidden');
                                            messagesPanel.classList.remove('hidden');
                                            
                                            // به‌روزرسانی هدر
                                            let avatarHtml = '';
                                            if (userImage) {
                                                avatarHtml = `
                                                    <img src="${userImage}" 
                                                        alt="${userName}" 
                                                        class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                `;
                                            } else {
                                                const avatarColors = ['avatar-blue', 'avatar-green', 'avatar-purple', 'avatar-pink', 'avatar-orange'];
                                                const colorIndex = userName.length % avatarColors.length;
                                                avatarHtml = `
                                                    <div class="user-avatar ${avatarColors[colorIndex]}">
                                                        ${userName.charAt(0)}
                                                    </div>
                                                `;
                                            }
                                            
                                            document.getElementById('currentChatUser').innerHTML = `
                                                ${avatarHtml}
                                                <div class="mr-3">
                                                    <h4 class="font-semibold">${userName}</h4>
                                                    <small class="text-gray-500 dark:text-gray-400 text-sm">آنلاین</small>
                                                </div>
                                            `;
                                            
                                            // بارگذاری پیام‌ها
                                            await loadMessages();
                                            
                                            setTimeout(() => {
                                                messageInput.focus();
                                                if (isMobile) {
                                                    setTimeout(scrollToBottom, 100);
                                                }
                                            }, 200);
                                        }
                                        
                                        async function searchUsers() {
                                            const query = chatSearchInput.value.trim();
                                            if (query.length < 2) {
                                                if (currentTab === 'conversations') {
                                                    renderConversations(conversations);
                                                } else {
                                                    renderUsers(users);
                                                }
                                                return;
                                            }
                                            
                                            try {
                                                const response = await fetch('/chat/search', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    body: JSON.stringify({ query: query })
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    if (currentTab === 'conversations') {
                                                        renderUsers(data.users);
                                                    } else {
                                                        renderUsers(data.users);
                                                    }
                                                }
                                            } catch (error) {
                                                console.error('Error searching users:', error);
                                            }
                                        }
                                        
                                        async function updateUnreadCount() {
                                            try {
                                                const response = await fetch('/chat/unread-count', {
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'X-CSRF-TOKEN': csrfToken
                                                    },
                                                    cache: 'no-cache'
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    const currentCount = data.count;
                                                    const badgeContent = unreadBadge.textContent || '0';
                                                    const currentBadgeCount = parseInt(badgeContent) || 0;
                                                    
                                                    if (currentCount > 0 && currentCount > previousUnreadCount && !isChatOpen) {
                                                        playMessageSound();
                                                        vibrateIfSupported();
                                                    }
                                                    
                                                    previousUnreadCount = currentCount;
                                                    
                                                    if (currentCount > 0) {
                                                        unreadBadge.textContent = currentCount > 99 ? '99+' : currentCount;
                                                        unreadBadge.classList.remove('hidden');
                                                        chatToggle.classList.add('animate-pulse');
                                                    } else {
                                                        unreadBadge.classList.add('hidden');
                                                        chatToggle.classList.remove('animate-pulse');
                                                    }
                                                }
                                            } catch (error) {
                                                console.error('Error updating unread count:', error);
                                            }
                                        }
                                        
                                        async function markAllAsRead() {
                                            try {
                                                const response = await fetch('/chat/mark-all-read', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    updateUnreadCount();
                                                    loadConversations();
                                                    showToast('همه پیام‌ها خوانده شدند', 'success');
                                                }
                                            } catch (error) {
                                                console.error('Error marking all as read:', error);
                                                showToast('خطا در خواندن پیام‌ها', 'error');
                                            }
                                        }
                                        
                                        async function deleteMessage(messageId) {
                                            if (!confirm('آیا از حذف این پیام مطمئن هستید؟')) {
                                                return;
                                            }
                                            
                                            try {
                                                const response = await fetch(`/chat/message/${messageId}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                });
                                                
                                                const data = await response.json();
                                                
                                                if (data.success) {
                                                    const messageElement = messagesContainer.querySelector(`[data-message-id="${messageId}"]`);
                                                    if (messageElement) {
                                                        messageElement.remove();
                                                        loadedMessageIds.delete(messageId);
                                                    }
                                                    showToast('پیام با موفقیت حذف شد', 'success');
                                                } else {
                                                    showToast(data.error || 'خطا در حذف پیام', 'error');
                                                }
                                            } catch (error) {
                                                console.error('Error deleting message:', error);
                                                showToast('خطا در حذف پیام', 'error');
                                            }
                                        }
                                        
                                        function refreshChatData() {
                                            if (currentTab === 'conversations') {
                                                loadConversations();
                                            } else {
                                                loadChatUsers();
                                            }
                                            
                                            if (currentChatUserId) {
                                                loadMessages();
                                            }
                                            
                                            updateUnreadCount();
                                            showToast('اطلاعات بروزرسانی شد', 'success');
                                        }
                                        
                                        function vibrateIfSupported() {
                                            if (isMobile && 'vibrate' in navigator) {
                                                try {
                                                    navigator.vibrate([100, 50, 100]);
                                                } catch (error) {
                                                    console.log('خطا در ویبره:', error);
                                                }
                                            }
                                        }
                                        
                                        function scrollToBottom() {
                                            setTimeout(() => {
                                                messagesContainer.scrollTo({
                                                    top: messagesContainer.scrollHeight,
                                                    behavior: 'smooth'
                                                });
                                            }, 100);
                                        }
                                        
                                        function formatDate(dateString) {
                                            const date = new Date(dateString);
                                            const now = new Date();
                                            const diffMs = now - date;
                                            const diffMins = Math.floor(diffMs / 60000);
                                            const diffHours = Math.floor(diffMs / 3600000);
                                            const diffDays = Math.floor(diffMs / 86400000);
                                            
                                            if (diffMins < 1) return 'همین الآن';
                                            if (diffMins < 60) return `${diffMins} دقیقه پیش`;
                                            if (diffHours < 24) return `${diffHours} ساعت پیش`;
                                            if (diffDays < 7) return `${diffDays} روز پیش`;
                                            
                                            return date.toLocaleDateString('fa-IR');
                                        }
                                        
                                        function formatDuration(seconds) {
                                            if (!seconds) return '0:00';
                                            const minutes = Math.floor(seconds / 60);
                                            const secs = Math.floor(seconds % 60);
                                            return `${minutes}:${secs.toString().padStart(2, '0')}`;
                                        }
                                        
                                        function escapeHtml(text) {
                                            const div = document.createElement('div');
                                            div.textContent = text;
                                            return div.innerHTML;
                                        }
                                        
                                        function showLoading(container, text) {
                                            container.innerHTML = `
                                                <div class="chat-loading">
                                                    <div class="chat-loading-spinner"></div>
                                                    <p class="mt-3 text-gray-500 text-center px-4">${text}</p>
                                                </div>
                                            `;
                                        }
                                        
                                        function showError(container, text) {
                                            container.innerHTML = `
                                                <div class="text-center text-red-500 py-8 px-4">
                                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <p>${text}</p>
                                                    <button onclick="refreshChatData()" class="mt-4 text-[#122EE1] hover:text-blue-700 text-sm">
                                                        تلاش مجدد
                                                    </button>
                                                </div>
                                            `;
                                        }
                                        
                                        function showToast(message, type = 'info') {
                                            let toastContainer = document.getElementById('toastContainer');
                                            if (!toastContainer) {
                                                toastContainer = document.createElement('div');
                                                toastContainer.id = 'toastContainer';
                                                toastContainer.className = 'fixed top-4 right-4 z-[99999]';
                                                document.body.appendChild(toastContainer);
                                            }
                                            
                                            const toastId = 'toast-' + Date.now();
                                            const bgColor = type === 'success' ? 'bg-green-500' : 
                                                        type === 'error' ? 'bg-red-500' : 
                                                        type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
                                            
                                            if (isMobile) {
                                                toastContainer.className = 'fixed top-4 right-4 left-4 z-[99999]';
                                            }
                                            
                                            const toast = document.createElement('div');
                                            toast.id = toastId;
                                            toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between ${isMobile ? 'w-full' : 'min-w-[300px]'}`;
                                            toast.style.animation = 'slideIn 0.3s ease';
                                            
                                            toast.innerHTML = `
                                                <span class="flex-1">${message}</span>
                                                <button onclick="document.getElementById('${toastId}').remove()" class="text-white hover:text-gray-200 mr-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            `;
                                            
                                            toastContainer.appendChild(toast);
                                            
                                            setTimeout(() => {
                                                if (document.getElementById(toastId)) {
                                                    toast.remove();
                                                }
                                            }, 3000);
                                        }
                                        
                                        function debounce(func, wait) {
                                            let timeout;
                                            return function executedFunction(...args) {
                                                const later = () => {
                                                    clearTimeout(timeout);
                                                    func(...args);
                                                };
                                                clearTimeout(timeout);
                                                timeout = setTimeout(later, wait);
                                            };
                                        }
                                        
                                        function startPolling() {
                                            stopPolling();
                                            pollingInterval = setInterval(() => {
                                                updateUnreadCount();
                                                
                                                if (currentChatUserId) {
                                                    loadNewMessages();
                                                } else if (isChatOpen) {
                                                    loadConversations();
                                                }
                                            }, 3000);
                                        }
                                        
                                        function stopPolling() {
                                            if (pollingInterval) {
                                                clearInterval(pollingInterval);
                                                pollingInterval = null;
                                            }
                                        }
                                        
                                        function startBackgroundPolling() {
                                            stopBackgroundPolling();
                                            backgroundPollingInterval = setInterval(() => {
                                                updateUnreadCount();
                                            }, 15000);
                                        }
                                        
                                        function stopBackgroundPolling() {
                                            if (backgroundPollingInterval) {
                                                clearInterval(backgroundPollingInterval);
                                                backgroundPollingInterval = null;
                                            }
                                        }
                                        
                                        // -------------------------
                                        // توابع گلوبال برای استفاده در HTML
                                        // -------------------------
                                        window.showImageModal = function(imageUrl) {
                                            const modal = document.getElementById('imageModal');
                                            const modalImage = document.getElementById('modalImage');
                                            const closeBtn = document.querySelector('.close-modal');
                                            
                                            modalImage.src = imageUrl;
                                            modal.style.display = 'flex';
                                            
                                            closeBtn.onclick = function() {
                                                modal.style.display = 'none';
                                            };
                                            
                                            modal.onclick = function(event) {
                                                if (event.target === modal) {
                                                    modal.style.display = 'none';
                                                }
                                            };
                                        };
                                        
                                        window.deleteMessage = deleteMessage;
                                        window.toggleAudioPlay = toggleAudioPlay;
                                        window.updateAudioProgress = updateAudioProgress;
                                        window.seekAudio = seekAudio;
                                        window.onAudioEnded = onAudioEnded;
                                        window.refreshChatData = refreshChatData;
                                        
                                        // توقف ضبط صوت هنگام بسته شدن پنجره
                                        window.addEventListener('beforeunload', function() {
                                            if (mediaRecorder && mediaRecorder.state === 'recording') {
                                                mediaRecorder.stop();
                                            }
                                            
                                            // آزاد کردن URLهای ایجاد شده
                                            if (selectedMedia.url) {
                                                URL.revokeObjectURL(selectedMedia.url);
                                            }
                                            
                                            // توقف تمام پخش‌کننده‌های صوت
                                            audioPlayers.forEach(player => {
                                                player.pause();
                                            });
                                        });
                                    });
            </script>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('mainContent');
            const progressBar = document.querySelector('.progress');

            // مدیریت منوی موبایل
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                mobileOverlay.classList.toggle('open');
            });
            
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                mobileOverlay.classList.remove('open');
            });

            // مدیریت پروفایل در موبایل
            const profileBtnMobile = document.getElementById('profileBtnMobile');
            if (profileBtnMobile) {
                profileBtnMobile.addEventListener('click', () => {
                    window.location.href = "{{ route('sarafi.users') }}";
                });
            }

            // مدیریت پروفایل در دسکتاپ
            const profileBtnDesktop = document.getElementById('profileBtnDesktop');
            const profileDropdownDesktop = document.getElementById('profileDropdownDesktop');
            if (profileBtnDesktop && profileDropdownDesktop) {
                profileBtnDesktop.addEventListener('click', () => {
                    profileDropdownDesktop.classList.toggle('hidden');
                });

                document.addEventListener('click', (event) => {
                    if (!profileBtnDesktop.contains(event.target) && !profileDropdownDesktop.contains(event.target)) {
                        profileDropdownDesktop.classList.add('hidden');
                    }
                });
            }

            // محتوا را ابتدا مخفی کن
            mainContent.style.display = 'none';

            let progress = 0;
            let fakeProgressInterval;

            function startFakeProgress() {
                fakeProgressInterval = setInterval(() => {
                    progress += Math.random() * 30;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                },10);
            }

            startFakeProgress();

            window.addEventListener('load', function() {
                clearInterval(fakeProgressInterval);
                progress = 100;
                progressBar.style.width = progress + '%';

                setTimeout(() => {
                    loader.classList.add('loader-complete');
                    mainContent.style.display = 'block';
                    mainContent.classList.add('content-loaded');

                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 400);
                }, 600);
            });

            // مدیریت کلیک روی لینک‌ها
            const navLinks = document.querySelectorAll('.nav-link, .locale-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && !href.startsWith('#')) {
                        e.preventDefault();
                        loader.style.display = 'flex';
                        loader.classList.remove('loader-complete');
                        setTimeout(() => window.location.href = href, 50);
                    }
                });
            });

            // مدیریت dropdown زبان برای دسکتاپ
            const btn = document.getElementById('dropdownButton');
            const menu = document.getElementById('dropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', () => menu.classList.toggle('hidden'));
                document.addEventListener('click', e => {
                    if (!btn.contains(e.target) && !menu.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }

            // مدیریت dropdown زبان برای موبایل
            const btnMobile = document.getElementById('dropdownButtonMobile');
            const menuMobile = document.getElementById('dropdownMenuMobile');
            if (btnMobile && menuMobile) {
                btnMobile.addEventListener('click', () => menuMobile.classList.toggle('hidden'));
                document.addEventListener('click', e => {
                    if (!btnMobile.contains(e.target) && !menuMobile.contains(e.target)) {
                        menuMobile.classList.add('hidden');
                    }
                });
            }
        });

        // مدیریت دارک مود
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        const toggleCircle = document.getElementById('toggleCircle');
        
        const darkModeToggleMobile = document.getElementById('darkModeToggleMobile');
        const sunIconMobile = document.getElementById('sunIconMobile');
        const moonIconMobile = document.getElementById('moonIconMobile');
        const toggleCircleMobile = document.getElementById('toggleCircleMobile');
        
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const html = document.documentElement;

        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
            html.classList.add('dark');
            if (darkModeToggle) darkModeToggle.checked = true;
            if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
            if (sunIcon) sunIcon.classList.add('hidden');
            if (sunIconMobile) sunIconMobile.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
            if (moonIconMobile) moonIconMobile.classList.remove('hidden');
            if (toggleCircle) toggleCircle.classList.add('move-dark');
            if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
        }

        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function() {
                updateDarkMode(this.checked);
            });
        }

        if (darkModeToggleMobile) {
            darkModeToggleMobile.addEventListener('change', function() {
                updateDarkMode(this.checked);
            });
        }

        function updateDarkMode(isDark) {
            if (isDark) {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (sunIcon) sunIcon.classList.add('hidden');
                if (sunIconMobile) sunIconMobile.classList.add('hidden');
                if (moonIcon) moonIcon.classList.remove('hidden');
                if (moonIconMobile) moonIconMobile.classList.remove('hidden');
                if (toggleCircle) toggleCircle.classList.add('move-dark');
                if (toggleCircleMobile) toggleCircleMobile.classList.add('move-dark');
                if (darkModeToggle) darkModeToggle.checked = true;
                if (darkModeToggleMobile) darkModeToggleMobile.checked = true;
            } else {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if (sunIcon) sunIcon.classList.remove('hidden');
                if (sunIconMobile) sunIconMobile.classList.remove('hidden');
                if (moonIcon) moonIcon.classList.add('hidden');
                if (moonIconMobile) moonIconMobile.classList.add('hidden');
                if (toggleCircle) toggleCircle.classList.remove('move-dark');
                if (toggleCircleMobile) toggleCircleMobile.classList.remove('move-dark');
                if (darkModeToggle) darkModeToggle.checked = false;
                if (darkModeToggleMobile) darkModeToggleMobile.checked = false;
            }
        }
    </script>

    <audio id="messageSound" preload="auto">
        <source src="{{ asset('assets/sarafi/message.mp3') }}" type="audio/mpeg">
    </audio>

    <script>
        window.addEventListener('open-new-window', event => {
        window.open(event.detail.url, '_blank');
    });
    </script>

</body>

</html>