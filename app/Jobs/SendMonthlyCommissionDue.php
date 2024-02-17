<?php

namespace App\Jobs;

use App\Mail\MonthlyCommissionsDueReport;
use App\Models\Repmailinglist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMonthlyCommissionDue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
                Mail::to($recipients)->queue(new MonthlyCommissionsDueReport());
                Log::info('I have sent an email with monthly commissions due for loans as of now.');
            } catch (\Exception $exception){
                echo 'Error - '.$exception;
            }
        } else {
            Log::info('I did not find any commissions generated this month to email as of now.');
        }

    }
}
