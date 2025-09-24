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
            ->theme(asset('css/filament/import/theme.css'))
                  ->colors([
                'primary' => Color::Blue,  // Blue for primary actions
                'success' => Color::Green, // Green for success states
                'warning' => Color::Amber, // Amber/Yellow for warnings
                'danger' => Color::Red,    // Red for errors/danger
                'info' => Color::Sky,      // Light blue for info
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
            /* 🔹 Topbar */
            .fi-topbar > nav {
                background-color: #ffffff;
                color: #1f2937; /* text-gray-900 */
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                border-bottom: 1px solid #c7d2fe; /* border-indigo-200 */
                transition: all 0.3s;
            }

            /* 🔹 Sidebar */
            .fi-sidebar {
                background-color: #ffffff;
                border-left: 1px solid #e5e7eb; /* border-gray-200 */
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                border-top-right-radius: 1rem; /* rounded-r-4xl */
                transition: all 0.3s;
            }

            .fi-sidebar-header {
                background-color: #ffffff;
                border-bottom: 1px solid #e5e7eb;
                transition: all 0.3s;
            }

            .fi-sidebar .fi-sidebar-item {
                color: #374151; /* text-gray-700 */
                border-radius: 0.5rem; /* rounded-lg */
                transition: all 0.2s;
            }

            .fi-sidebar .fi-sidebar-item:hover {
                color: #4b5563; /* text-gray-600 */
                background-color: #fecaca; /* red-200 */
            }

            .fi-sidebar .fi-sidebar-item-active {
                background-color: #000000; /* bg-black */
                color: #4338ca; /* text-indigo-700 */
                font-weight: 600;
            }

            /* 🔹 Body */
            .fi-body {
                background-color: #ebeceff0;
                transition: background-color 0.3s;
            }

            /* 🔹 Header Buttons Container */
            .flex-header-container {
                display: flex;
                justify-content: center;
                gap: 0.5rem;
            }

            /* 🔹 Dark Mode */
            .dark .fi-topbar > nav {
                background-color: #1f2937; /* gray-800 */
                color: #f9fafb; /* gray-100 */
                border-color: #374151; /* border-gray-700 */
            }

            .dark .fi-sidebar {
                background-color: #111827; /* gray-900 */
                border-color: #374151;
            }

            .dark .fi-sidebar-header {
                background-color: #111827;
                border-color: #374151;
            }

            .dark .fi-sidebar .fi-sidebar-item {
                color: #d1d5db; /* gray-300 */
            }

            .dark .fi-sidebar .fi-sidebar-item:hover {
                color: #818cf8; /* indigo-400 */
                background-color: #1f2937; /* gray-800 */
            }

            .dark .fi-sidebar .fi-sidebar-item-active {
                background-color: #1e3a8a; /* indigo-900 */
                color: #c7d2fe; /* indigo-300 */
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
    HTML
)

            ->discoverWidgets(in: app_path('Filament/Import/Widgets'), for: 'App\\Filament\\Import\\Widgets')
            ->widgets([

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
