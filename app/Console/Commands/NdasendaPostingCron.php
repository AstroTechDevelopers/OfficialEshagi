<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NdasendaPostingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndasendapost:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking if the loans applied meet the Ndasenda Terms and conditions.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //DB::table('users')->increment('leave_days', 1.833);
        //        Log::info('Monthly Leave accruals processed successfully.');
        return 0;
    }
}
