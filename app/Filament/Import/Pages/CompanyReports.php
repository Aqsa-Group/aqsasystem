<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;

class CompanyReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.import.pages.company-reports';
    protected static ?string $navigationLabel = 'گزارش خرید ها';

    protected static ?string $title = '';
    protected static ?string $navigationGroup = 'بخش خرید و فروش';
    protected static ?int $navigationSort = 12;
}
