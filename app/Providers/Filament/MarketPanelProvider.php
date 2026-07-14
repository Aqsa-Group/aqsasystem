<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CustomLogin;
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

class MarketPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('market')
            ->path('market')
            ->font('Scheherazade New')
            ->theme(asset('css/filament/market/theme.css'))
            ->login(CustomLogin::class)
            ->authGuard('market')
            ->colors([
                'primary' => '#6366f1',

            ])
            ->navigationGroups(
                [
                    'اطلاعات مارکت'
                ]
            )
            ->brandName("فردوسی")
            ->renderHook(
                'panels::head.end',
                fn() => <<<HTML
        <style>
            /* فونت‌های فارسی */
            @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css');
            @import url('https://cdn.jsdelivr.net/gh/rastikerdar/shabnam-font@v5.0.1/dist/font-face.css');

            /* فونت پیش‌فرض */
            body,
            .fi-body,
            .fi-sidebar,
            .fi-topbar {
                font-family: Vazir, Tahoma, sans-serif !important;
            }

            /* کلاس‌های فونت */
            .vazir {
                font-family: Vazir, Tahoma, sans-serif !important;
            }

            .shabnam {
                font-family: Shabnam, Tahoma, sans-serif !important;
            }

            .yekan {
                font-family: Vazir, Tahoma, sans-serif !important;
            }
                    .fi-main,
                                .fi-main-ctn,
                                .fi-page,
                                .fi-page-content {
                                    max-width: 100% !important;
                                    width: 100% !important;
                                }

                                .fi-page-content {
                                    padding-inline: 1.5rem !important;
                                }
            /* اندازه فونت جدول */
            .fi-ta-cell,
            .fi-ta-header-cell {
                font-size: 20px !important;
                font-weight: 500 !important;
            }

            /* اندازه فونت گروه‌های سایدبار */
            .fi-sidebar-group-label,
            .fi-sidebar-group-button span,
            .fi-sidebar-group-header span {
                font-size: 18px !important;
                font-weight: 700 !important;
            }

            /* اندازه فونت آیتم‌های سایدبار */
            .fi-sidebar .fi-sidebar-item-label,
            .fi-sidebar .fi-sidebar-group-label,
            .fi-sidebar .fi-sidebar-item a,
            .fi-sidebar .fi-sidebar-item span {
                font-size: 20px !important;
            }
            
        </style>

                    <script>
                            document.addEventListener('livewire:init', () => {
                    console.log('Livewire loaded');

                    Livewire.on('open-print', (event) => {
                        console.log(event);

                        const url = event.url ?? event[0]?.url;

                        if (url) {
                            window.open(url, '_blank');
                        }
                    });
                });
            </script>
    HTML
            )
            ->discoverResources(in: app_path('Filament/Market/Resources'), for: 'App\\Filament\\Market\\Resources')
            ->discoverPages(in: app_path('Filament/Market/Pages'), for: 'App\\Filament\\Market\\Pages')
            ->pages([
                \App\Filament\Market\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Market/Widgets'), for: 'App\\Filament\\Market\\Widgets')
            ->widgets([])
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
