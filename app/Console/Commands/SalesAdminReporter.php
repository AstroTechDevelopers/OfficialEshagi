<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\DailySalesNotifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SalesAdminReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:salesadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send an email for sales performance in the system.';

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

        //SendSalesAdminDaily::dispatchNow();
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
                Notification::route('mail', $recipients)->notify(new DailySalesNotifier());
                Log::info('I have sent an email with sales records as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the sales admin report as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any sales records to email as of now.');
        }
        return 0;
    }
}
