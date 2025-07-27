<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\FirebaseWipe::class,
        \App\Console\Commands\FirebaseSetup::class,
        \App\Console\Commands\SeedSuperadminFirebase::class,
        \App\Console\Commands\CheckPagePermissionMiddleware::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Define scheduled tasks here
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
