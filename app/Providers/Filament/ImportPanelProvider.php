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
                fn() => <<<HTML
                    <style>
                        /* فونت‌های فارسی */
                        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css');
                        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/shabnam-font@v5.0.1/dist/font-face.css');
                        
                    


                          body, .fi-body, .fi-sidebar, .fi-topbar {
                                font-family: Vazir, Tahoma, sans-serif !important;
                            }
                            /* .fi-sidebar {
                                            background-color: #2c1a3a !important;
                                            border-left: 1px solid #e5e7eb !important;
                                            box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
                                            border-radius: 0 !important;
                                        } */
                                    /* 🔹 Sidebar - بنفش تیره با متن سفید */
                                    .fi-sidebar {
                                        background-color: #2c1a3a !important;
                                        border-left: 1px solid #4a3058 !important;
                                        box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important;
                                        border-radius: 0 !important;
                                    }
                                        .fi-simple-main-ctn {
                                            align-items: center;
                                            justify-content: center;
                                            background-size: cover;
                                            background-position: center;
                                            background-repeat: no-repeat;   
                                            background-image: url('/bg.jpg');                                            background-blend-mode: overlay;
                                        }
                                       
                                                                                        .fi-sidebar-group-label {
                                                    font-size: 20px !important;
                                                    font-weight: 700 !important;
                                                    line-height: 32px !important;
                                                    color: #ffffff !important;
                                                }
                                                                                    .fi-sidebar,
                                            .fi-sidebar * {
                                                color: #ffffff !important;
                                            }
                                                        .fi-ta-cell,
                                                        .fi-ta-header-cell {
                                                            font-size: 20px !important;
                                                            font-weight: 500;
                                                        }

                                                        .fi-sidebar-group-label,
                                                        .fi-sidebar-group-button span,
                                                        .fi-sidebar-group-header span {
                                                            font-size: 18px !important;
                                                            font-weight: 700 !important;
                                                        }                                .fi-sidebar .fi-sidebar-item-label,
                                                    .fi-sidebar .fi-sidebar-group-label,
                                                    .fi-sidebar .fi-sidebar-item a,
                                                    .fi-sidebar .fi-sidebar-item span {
                                                        font-size: 20px !important; /* 16px */
                                                    }
                                                .fi-sidebar .fi-sidebar-item:hover,
                                                .fi-sidebar .fi-sidebar-item:hover a,
                                                .fi-sidebar .fi-sidebar-item:hover span,
                                                .fi-sidebar .fi-sidebar-item:hover button {
                                                    background-color: #ffffff !important;
                                                    color: #000000 !important;
                                                }

                                                                                                .fi-sidebar .fi-sidebar-item:hover .fi-sidebar-item-icon,
                                                    .fi-sidebar .fi-sidebar-item:hover svg {
                                                        color: #000000 !important;
                                                        stroke: #000000 !important;
                                                        fill: #000000 !important;
                                                    }
                                                    

                                                    
                                    .fi-sidebar-header {
                                        background-color: #1f1230 !important;
                                        border-bottom: 1px solid #4a3058 !important;
                                    }


                                ]
                                    /* همه متن‌های سایدبار سفید */
                                    .fi-sidebar,
                                    .fi-sidebar *,
                                    .fi-sidebar .fi-sidebar-item,
                                    .fi-sidebar .fi-sidebar-group-label,
                                    .fi-sidebar .fi-sidebar-header,
                                    .fi-sidebar .fi-brand,
                                    .fi-sidebar .fi-sidebar-item a,
                                    .fi-sidebar .fi-sidebar-item span,
                                    .fi-sidebar .fi-sidebar-item button,
                                    .fi-sidebar .fi-sidebar-group button,
                                    .fi-sidebar .fi-sidebar-group span,
                                    .fi-sidebar [class*="text-"] {
                                        color: #ffffff !important;
                                    }

                                    /* فونت بزرگتر برای همه آیتم‌ها */
                                    .fi-sidebar .fi-sidebar-item {
                                        border-radius: 0.5rem !important;
                                        transition: all 0.2s !important;
                                        font-size: 1.05rem !important;
                                    }

                                    /* hover مشکی */
                                    .fi-sidebar .fi-sidebar-item:hover {
                                        background-color: #000000 !important;
                                        color: #ffffff !important;
                                    }

                                   
                                    .fi-sidebar .fi-sidebar-item:hover .fi-sidebar-item-icon,
                                    .fi-sidebar .fi-sidebar-item:hover svg {
                                        color: #a78bfa !important;
                                    }

                                    /* آیتم فعال - بک‌گراند سفید با متن مشکی */
                                    .fi-sidebar .fi-sidebar-item-active {
                                        background-color: #ffffff !important;
                                        font-weight: 600 !important;
                                        border-right: 3px solid #8b5cf6 !important;
                                        font-size: 1.1rem !important;
                                    }

                                    /* متن آیتم فعال مشکی */
                                    .fi-sidebar .fi-sidebar-item-active,
                                    .fi-sidebar .fi-sidebar-item-active *,
                                    .fi-sidebar .fi-sidebar-item-active a,
                                    .fi-sidebar .fi-sidebar-item-active span,
                                    .fi-sidebar .fi-sidebar-item-active button {
                                        color: #000000 !important;
                                    }

                                    /* hover روی آیتم فعال */
                                    .fi-sidebar .fi-sidebar-item-active:hover {
                                        background-color: #000000 !important;
                                    }

                                    .fi-sidebar .fi-sidebar-item-active:hover,
                                    .fi-sidebar .fi-sidebar-item-active:hover *,
                                    .fi-sidebar .fi-sidebar-item-active:hover a,
                                    .fi-sidebar .fi-sidebar-item-active:hover span,
                                    .fi-sidebar .fi-sidebar-item-active:hover button {
                                        color: #ffffff !important;
                                    }

                                    .fi-sidebar .fi-sidebar-item-active:hover .fi-sidebar-item-icon,
                                    .fi-sidebar .fi-sidebar-item-active:hover svg {
                                        color: #a78bfa !important;
                                    }

                                    /* آیکون آیتم فعال بنفش */
                                    .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon,
                                    .fi-sidebar .fi-sidebar-item-active svg {
                                        color: #6d28d9 !important;
                                    }

                                    /* آیکون‌های غیرفعال سفید */
                                    .fi-sidebar .fi-sidebar-item-icon,
                                    .fi-sidebar .fi-sidebar-item svg,
                                    .fi-sidebar svg {
                                        color: #ffffff !important;
                                    }

                                 

                                    /* Dark Mode */
                                    .dark .fi-sidebar .fi-sidebar-item:hover {
                                        background-color: #000000 !important;
                                    }

                                    .dark .fi-sidebar .fi-sidebar-item-active {
                                        background-color: #f8fafc !important;
                                    }

                                    .dark .fi-sidebar .fi-sidebar-item-active,
                                    .dark .fi-sidebar .fi-sidebar-item-active *,
                                    .dark .fi-sidebar .fi-sidebar-item-active a,
                                    .dark .fi-sidebar .fi-sidebar-item-active span,
                                    .dark .fi-sidebar .fi-sidebar-item-active button {
                                        color: #000000 !important;
                                    }

                                    .dark .fi-sidebar .fi-sidebar-item-active:hover {
                                        background-color: #000000 !important;
                                    }

                                    .dark .fi-sidebar .fi-sidebar-item-active:hover,
                                    .dark .fi-sidebar .fi-sidebar-item-active:hover * {
                                        color: #ffffff !important;
                                    }

                                    /* اسکرول‌بار سایدبار */
                                    .fi-sidebar::-webkit-scrollbar {
                                        width: 6px;
                                    }

                                    .fi-sidebar::-webkit-scrollbar-track {
                                        background: #1f1230;
                                    }

                                    .fi-sidebar::-webkit-scrollbar-thumb {
                                        background: #4a3058;
                                        border-radius: 3px;
                                    }

                                    .fi-sidebar::-webkit-scrollbar-thumb:hover {
                                        background: #6b4d7a;
                                    }

                                    .fi-body {
                                    background: #e5e7eb !important;
                                    background-attachment: fixed !important;
                                    min-height: 100vh !important;
                                }

                                .dark .fi-body {
                                    background: #111827 !important; /* gray-900 */
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
