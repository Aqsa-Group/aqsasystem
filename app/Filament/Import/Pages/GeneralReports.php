<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;

class GeneralReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'گزارش گیری عمومی';
    protected static ?string $title = '';

    protected static string $view = 'filament.import.pages.general-reports';
}
