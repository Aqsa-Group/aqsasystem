<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Table;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Table::$defaultDateTimeDisplayFormat = 'Y/m/d H:i:s';
        DateTimePicker::$defaultDateTimeWithSecondsDisplayFormat = 'Y/m/d H:i:s';
        DateTimePicker::$defaultDateDisplayFormat = 'Y/m/d';
    }
}
