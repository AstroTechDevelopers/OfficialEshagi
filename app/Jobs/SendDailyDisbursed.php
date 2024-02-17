<?php

namespace App\Jobs;

use App\Mail\DisbursedLoansReport;
use App\Models\Repmailinglist;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyDisbursed implements ShouldQueue
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
                Mail::to($recipients)->queue(new DisbursedLoansReport());
                Log::info('I have sent an email with disbursed loans as of now.');
            } catch (\Exception $exception){
                echo 'Error - '.$exception;
            }
        } else {
            Log::info('I did not find any disbursed loans to email as of now.');
        }

    }
}
