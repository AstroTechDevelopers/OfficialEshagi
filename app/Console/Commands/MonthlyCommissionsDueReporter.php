<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\MonthlyCommissionsNotifier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MonthlyCommissionsDueReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:monthlycommissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to email monthly commissions due report.';

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
        //SendMonthlyCommissionDue::dispatchNow();
        $mailingList = Repmailinglist::where('report', 'Monthly Commissions Due Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Monthly Commissions Due report is currently disabled, therefore I did not send anything.');
            exit();
        }

        $commissions = DB::table('commissions as c')
            ->join('clients as cl', function($join) {
                $join->on('cl.id', '=', 'c.client');
            })
            ->select('c.id','c.agent','cl.first_name','cl.last_name','cl.natid','c.loanid','c.loanamt','c.commission')
            ->where(DB::raw('MONTH(c.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(c.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('c.paidout','=', false)
            ->where('c.deleted_at','=', null)
            ->count();

        if ($commissions > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new MonthlyCommissionsNotifier());
                Log::info('I have sent an email with monthly commissions due for loans as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the monthly commissions due as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any commissions generated this month to email as of now.');
        }
        return 0;
    }
}
