<?php

namespace App\Filament\Import\Pages;
use Illuminate\Contracts\Support\Htmlable;


use Filament\Pages\Page;

class ReportPanel extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'گزارشات';
    protected static ?string $navigationGroup = 'گزارشات و تنظیمات';
    protected static ?int $navigationSort = 9;
    protected static ?string $slug = 'reports';
    
    protected static string $view = 'filament.import.pages.report-panel';

            public function getTitle(): string|Htmlable
    {
        return '';
    }
}
