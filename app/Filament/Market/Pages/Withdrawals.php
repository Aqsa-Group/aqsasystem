<?php

namespace App\Filament\Market\Pages;

use Filament\Pages\Page;

class Withdrawals extends Page
{
    protected static ?string $navigationIcon = 'vaadin-money-withdraw';
    protected static ?string $title = '';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?string $navigationGroup = 'بخش مالی';
    protected static string $view = 'filament.market.pages.withdrawals';
}
