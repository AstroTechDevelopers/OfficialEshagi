<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\ExecutivesNotifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ExecutiveSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:executive-summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send executive summary from the system.';

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
        $mailingList = Repmailinglist::where('report', 'Executive Summary Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Executive summary report is currently disabled, therefore I did not send anything.');
            exit();
        }

            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new ExecutivesNotifier());
                Log::info('I have sent the executive summary as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the executive summary as of now: '.$exception);
            }

        return 0;
    }
}
