<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\DisbursedLoansNotifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DailyDisbursedReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:disbursedloans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send an email for disbursed loans in the system.';

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
        //SendDailyDisbursed::dispatchNow();
        $mailingList = Repmailinglist::where('report', 'Disbursed Loans Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Disbursed loans report is currently disabled, therefore I did not send anything.');
            exit();
        }

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->join('commissions as m', function($join) {
                $join->on('l.id', '=', 'm.loanid');
            })
            ->whereDate('m.created_at', Carbon::today())
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->count();

        if ($loans > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new DisbursedLoansNotifier());
                Log::info('I have sent an email with disbursed loans as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the daily disbursed as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any disbursed loans to email as of now.');
        }

        return 0;
    }
}
