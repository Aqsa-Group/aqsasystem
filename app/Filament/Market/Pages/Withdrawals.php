<?php

namespace App\Filament\Market\Pages;

use Filament\Pages\Page;

class Withdrawals extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?string $title = '';
    protected static string $view = 'filament.market.pages.withdrawals';
}
