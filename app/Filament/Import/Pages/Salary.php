<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;

class Salary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
     protected static ?string $navigationLabel = 'پرداخت معاشات';
    protected static ?string $title = '';


    protected static string $view = 'filament.import.pages.salary';
}
