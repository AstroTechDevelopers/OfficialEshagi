<?php

namespace App\Jobs;

use App\Mail\PendingLoansReport;
use App\Models\Repmailinglist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPendingLoans implements ShouldQueue
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
                Mail::to($recipients)->queue(new PendingLoansReport());
                Log::info('I have sent an email with pending loans as of now.');
            } catch (\Exception $exception){
                echo 'Error - '.$exception;
            }
        } else {
            Log::info('I did not find any pending loans to email as of now.');
        }
    }
}
