<?php

namespace App\Jobs;

use App\Mail\SalesAdminReport;
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

class SendSalesAdminDaily implements ShouldQueue
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
        $mailingList = Repmailinglist::where('report', 'Sales Admin Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Sales Admin report is currently disabled, therefore I did not send anything.');
            exit();
        }
        $sales = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->whereDate('l.created_at', Carbon::today())
            ->where('l.deleted_at','=', null)
            ->count();

        if ($sales > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Mail::to($recipients)->queue(new SalesAdminReport());
                Log::info('I have sent an email with sales records as of now.');
            } catch (\Exception $exception){
                echo 'Error - '.$exception;
            }
        } else {
            Log::info('I did not find any sales records to email as of now.');
        }
    }
}
