<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\PendingLoansNotifier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PendingLoansReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:pendingloans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send an email for pending loans in the system.';

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
        //SendPendingLoans::dispatchNow();
        $mailingList = Repmailinglist::where('report', 'Pending Loans Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Pending loans report is currently disabled, therefore I did not send anything.');
            exit();
        }

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->whereIn('l.loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
            ->where('l.deleted_at','=', null)
            ->count();

        if ($loans > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new PendingLoansNotifier());
                Log::info('I have sent an email with pending loans as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the pending loans as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any pending loans to email as of now.');
        }

        return 0;
    }
}
