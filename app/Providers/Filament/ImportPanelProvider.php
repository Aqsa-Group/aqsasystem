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
            ->font('Scheherazade New')
            ->theme(asset('css/filament/import/theme.css'))
            ->colors([
                'primary' => Color::Green,
            ])
            ->login(ImportLogin::class)
            ->authGuard('import')
            ->brandName("حبیب یونس لمتید")
            ->discoverResources(in: app_path('Filament/Import/Resources'), for: 'App\\Filament\\Import\\Resources')
            ->discoverPages(in: app_path('Filament/Import/Pages'), for: 'App\\Filament\\Import\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
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
