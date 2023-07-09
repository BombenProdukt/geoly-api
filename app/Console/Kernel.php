<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\IndexAutonomousSystems;
use App\Console\Commands\IndexCountries;
use App\Console\Commands\IndexInternetProtocolBlocks;
use BombenProdukt\GeoIp2\Commands\DownloadDatabases;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(IndexAutonomousSystems::class)->daily();

        $schedule->command(IndexCountries::class)->daily();

        $schedule->command(IndexInternetProtocolBlocks::class)->daily();

        $schedule->command(IndexRiskyAddresses::class)->daily();

        $schedule->command(DownloadDatabases::class)->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
