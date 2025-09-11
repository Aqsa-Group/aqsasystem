<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array<int, class-string>
     */
    protected $commands = [
        // 📌 دستور تمدید هزینه‌ها
        \App\Console\Commands\RenewShopExpenses::class,
    ];

    /**
     * Define the application's command schedule.
     */
protected function schedule(Schedule $schedule): void
{
    Log::info('🔥 Kernel schedule() method loaded at ' . now());

    $schedule->call(function () {
        Log::info('✅ Schedule test executed at ' . now());
    })->everyMinute();

$schedule->command('expenses:renew')->dailyAt('00:00');
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // 📌 همه دستورهای artisan داخل پوشه Commands اتوماتیک لود می‌شن
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
