<?php

namespace App\Filament\Market\Pages;

use Filament\Pages\Page;

class CurrencyConversion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.market.pages.currency-conversion';
    protected static ?string $navigationLabel = 'تبدیل ارز در حساب مشتری';

    protected static ?string $title = '';
}
