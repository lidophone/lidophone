<?php

namespace App\Console;

use App\Console\Commands\DbDump;
use App\Console\Commands\Offers\Callexchange;
use App\Console\Commands\Offers\Cpacoin;
use App\Console\Commands\Offers\Marketcall;
use App\Console\Commands\PriceParsing\Download as PriceParsingDownload;
use App\Console\Commands\PriceParsing\Sync as PriceParsingSync;
use App\Console\Commands\Uis\SyncCalls as UisSyncCalls;
use App\Console\Commands\Uis\SyncEmployees as UisSyncEmployees;
use App\Console\Commands\Uis\SyncGroups as UisSyncGroups;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(Callexchange::class)->everyMinute()->withoutOverlapping();
        $schedule->command(Cpacoin::class)->everyMinute()->withoutOverlapping();
        $schedule->command(Marketcall::class)->everyThreeMinutes()->withoutOverlapping();
        $schedule->command(DbDump::class)->cron('0 0 * * *')->withoutOverlapping();
        $schedule->command(DbDump::class)->cron('0 12 * * *')->withoutOverlapping();

        $schedule->command(
            PriceParsingSync::class,
            [PriceParsingDownload::DEVELOPER_KVS, '--download-and-parse']
        )->cron('0 * * * *')->withoutOverlapping();
        $schedule->command(
            PriceParsingSync::class,
            [PriceParsingDownload::DEVELOPER_PIK_MOSCOW, '--download-and-parse']
        )->cron('0 * * * *')->withoutOverlapping();
        $schedule->command(
            PriceParsingSync::class,
            [PriceParsingDownload::DEVELOPER_PIK_SPB, '--download-and-parse']
        )->cron('0 * * * *')->withoutOverlapping();

        $schedule->command(UisSyncGroups::class)->everyMinute()->withoutOverlapping();
        $schedule->command(UisSyncEmployees::class)->everyFiveMinutes()->withoutOverlapping();
        // [!] Why wasn't there `withoutOverlapping()` for the `UisSyncCalls`?
        $schedule->command(UisSyncCalls::class)->everyMinute()->withoutOverlapping();
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
