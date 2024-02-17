<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DeleteExpiredActivations::class,
        Commands\NdasendaPostingCron::class,
        Commands\FcbChecker::class,
        Commands\LeadsAutoAllocator::class,
        Commands\PendingLoansReporter::class,
        Commands\DailyDisbursedReporter::class,
        Commands\MonthlyCommissionsDueReporter::class,
        Commands\SalesAdminReporter::class,
        Commands\WeeklyLoanStatusReporter::class,
        Commands\Strongswan::class,
        Commands\ExecutiveSummary::class,
        Commands\MerchantLoansReporter::class,
        Commands\ZambiaDailyDisbursedReporter::class,
        Commands\ZambiaWeeklyLoanStatusReporter::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('activations:clean')->daily();
        //$schedule->command('ndasendapost:cron')->everyFiveMinutes();
        //$schedule->command('fcbcheck:updatefcb')->everyFifteenMinutes()->weekdays()->between('8:00', '17:00');
        //$schedule->command('allocate:salesleads')->weekdays()->at('04:00');
        $schedule->command('email:pendingloans')->weekdays()->at('16:00');
        $schedule->command('email:disbursedloans')->weekdays()->at('16:30');
        $schedule->command('email:monthlycommissions')->lastDayOfMonth()->at('07:00');
        $schedule->command('email:salesadmin')->weekdays()->at('17:00');
        $schedule->command('email:weeklystatusreport')->weekly()->fridays()->at('16:10');
        $schedule->command('backup:run')->dailyAt('02:00');
        $schedule->command('send:executive-summary')->weekdays()->at('16:45');
        $schedule->command('email:merchantloans')->weekdays()->at('16:30');
        $schedule->command('email:zambia-disbursedloans')->weekdays()->at('16:30');
        $schedule->command('email:zambia-weeklystatusreport')->weekly()->fridays()->at('16:10');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
