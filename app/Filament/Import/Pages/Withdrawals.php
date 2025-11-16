<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;

class Withdrawals extends Page
{
    protected static ?string $navigationIcon = 'iconoir-safe-arrow-right';
    protected static ?string $navigationGroup = 'حسابداری';
    protected static ?string $navigationLabel = 'برداشت از صندوق';
    protected static ?string $title = '';
    protected static string $view = 'filament.import.pages.withdrawals';
}
