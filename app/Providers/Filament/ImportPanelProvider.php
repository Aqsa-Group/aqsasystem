<?php

namespace App\Providers\Filament;

use App\Filament\Auth\ImportLogin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ImportPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('import')
            ->path('import')
            ->sidebarCollapsibleOnDesktop()
            ->font('Scheherazade New')
            // ->viteTheme('resources/css/filament/import/theme.css') // این خط را کامنت کنید
            ->colors([
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Red,
                'info' => Color::Sky,
                'gray' => Color::Gray, 
            ])
            ->login(ImportLogin::class)
            ->authGuard('import')
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->brandName("حبیب یونس لمتید")
            ->discoverResources(in: app_path('Filament/Import/Resources'), for: 'App\\Filament\\Import\\Resources')
            ->discoverPages(in: app_path('Filament/Import/Pages'), for: 'App\\Filament\\Import\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->renderHook(
                'panels::head.end',
                fn () => <<<HTML
                    <style>
                        /* فونت‌های فارسی */
                        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css');
                        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/shabnam-font@v5.0.1/dist/font-face.css');
                        
                        /* اعمال فونت فارسی برای کل پنل */
                        body, .fi-body, .fi-sidebar, .fi-topbar {
                            font-family: Vazir, Tahoma, sans-serif !important;
                            
                        }
                        
                        
                        
                        /* کلاس‌های فونت سفارشی */
                        .vazir { font-family: Vazir, Tahoma, sans-serif !important; }
                        .shabnam { font-family: Shabnam, Tahoma, sans-serif !important; }
                        .yekan { font-family: Vazir, Tahoma, sans-serif !important; }
                        
                        /* 🔹 Topbar */
                        .fi-topbar > nav {
                            background-color: #ffffff;
                            color: #1f2937;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                            border-bottom: 1px solid #c7d2fe;
                            transition: all 0.3s;
                        }

                        /* 🔹 Sidebar */
                        .fi-sidebar {
                            background-color: #ffffff;
                            border-left: 1px solid #e5e7eb;
                            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                            border-top-right-radius: 1rem;
                            transition: all 0.3s;
                        }

                        .fi-sidebar-header {
                            background-color: #ffffff;
                            border-bottom: 1px solid #e5e7eb;
                            transition: all 0.3s;
                        }

                        .fi-sidebar .fi-sidebar-item {
                            color: #374151;
                            border-radius: 0.5rem;
                            transition: all 0.2s;
                        }

                        .fi-sidebar .fi-sidebar-item:hover {
                            color: #4b5563;
                            background-color: #fecaca;
                        }

                        .fi-sidebar .fi-sidebar-item-active {
                            background-color: #000000;
                            color: #4338ca;
                            font-weight: 600;
                        }

                        /* 🔹 Body */
                        .fi-body {
                            background-color: #ebeceff0;
                            transition: background-color 0.3s;
                        }

                        /* 🔹 Dark Mode */
                        .dark .fi-topbar > nav {
                            background-color: #1f2937;
                            color: #f9fafb;
                            border-color: #374151;
                        }

                        .dark .fi-sidebar {
                            background-color: #111827;
                            border-color: #374151;
                        }

                        .dark .fi-sidebar-header {
                            background-color: #111827;
                            border-color: #374151;
                        }

                        .dark .fi-sidebar .fi-sidebar-item {
                            color: #d1d5db;
                        }

                        .dark .fi-sidebar .fi-sidebar-item:hover {
                            color: #818cf8;
                            background-color: #1f2937;
                        }

                        .dark .fi-sidebar .fi-sidebar-item-active {
                            background-color: #1e3a8a;
                            color: #c7d2fe;
                            font-weight: 600;
                        }

                        .dark .fi-body {
                            background-color: #1e1e2f;
                        }

                        /* Light mode background */
                        body {
                            background: url("/images/bg-light.png") no-repeat center center fixed;
                            background-size: cover;
                        }

                        /* Dark mode background */
                        .dark body {
                            background: url("/images/bg-dark2.png") no-repeat center center fixed;
                            background-size: cover;
                        }
                    </style>
                    
                    <!-- اضافه کردن Tailwind برای کامپوننت‌های Livewire -->
                    <script src="https://cdn.tailwindcss.com"></script>
                    <script>
                        tailwind.config = {
                            darkMode: 'class',
                            theme: {
                                extend: {
                                    colors: {
                                        primary: {
                                            50: '#EEF2FF',
                                            500: '#6366F1',
                                            600: '#4F46E5',
                                        },
                                    },
                                    fontFamily: {
                                        vazir: ['Vazir', 'sans-serif'],
                                        shabnam: ['Shabnam', 'sans-serif'],
                                    },
                                },
                            },
                        }
                    </script>
                    
                    <!-- اضافه کردن Font Awesome -->
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
                    
                    <!-- اضافه کردن Persian Datepicker -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
                    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
                HTML
            )
            ->discoverWidgets(in: app_path('Filament/Import/Widgets'), for: 'App\\Filament\\Import\\Widgets')
            ->widgets([
                // ویجت‌ها
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}