<?php

namespace App\Filament\Market\Pages;

use Filament\Pages\Page;

class StaffProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.market.pages.staff-profile';
      protected static ?string $navigationLabel = 'گزارش گیری عمومی';
    protected static ?string $title = '';

    protected static ?string $slug = 'staff-profile';
}
