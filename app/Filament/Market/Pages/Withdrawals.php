<?php

namespace App\Filament\Market\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Withdrawals extends Page
{
    protected static ?string $navigationIcon = 'vaadin-money-withdraw';
    protected static ?string $title = '';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static string $view = 'filament.market.pages.withdrawals';

   public static function canView(): bool
{
    $user = Auth::user();
    if (!$user) {
        return false;
    }

    return in_array(strtolower($user->role), ['admin', 'superadmin']);
}

public static function shouldRegisterNavigation(): bool
{
    $user = Auth::user();
    return $user && in_array(strtolower($user->role), ['admin', 'superadmin']);
}

}
