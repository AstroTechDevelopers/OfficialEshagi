<?php

namespace App\Jobs;

use App\Mail\WeeklyLoanStatusReport;
use App\Models\Repmailinglist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWeeklyStatus implements ShouldQueue
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
        $mailingList = Repmailinglist::where('report', 'Loans Weekly Status Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Loans Weekly Status report is currently disabled, therefore I did not send anything.');
            exit();
        }

            $loans = DB::table('loans as l')
                ->join('clients as c','c.id', '=', 'l.client_id')
                ->where(DB::raw('date(l.created_at)'), '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
                ->where(DB::raw('MONTH(l.created_at)'), '=', DB::raw('MONTH(CURDATE())'))
                ->where(DB::raw('YEAR(l.created_at)'), '=', DB::raw('YEAR(CURDATE())'))
                ->where('l.locale','=', 1)
                ->where('l.deleted_at','=', null)
                ->groupBy('l.loan_status','l.id' )
                ->orderBy('l.created_at', 'DESC')
                ->count();

        if ($loans > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Mail::to($recipients)->queue(new WeeklyLoanStatusReport());
                Log::info('I have sent an email with weekly stats of loans as of now.');
            } catch (\Exception $exception){
                echo 'Error - '.$exception;
            }
        } else {
            Log::info('I did not find any loans created this week to email as of now.');
        }
    }
}
