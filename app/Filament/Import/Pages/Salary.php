<?php

namespace App\Filament\Import\Pages;

use Filament\Pages\Page;

class Salary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
     protected static ?string $navigationLabel = 'پرداخت معاشات';
    protected static ?string $title = '';
      protected static ?string $navigationGroup = 'حسابداری';
    protected static ?int $navigationSort = 9;



    protected static string $view = 'filament.import.pages.salary';
}
