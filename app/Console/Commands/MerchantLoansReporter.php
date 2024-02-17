<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\DailyMerchantLoansNotifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MerchantLoansReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:merchantloans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send an email for created merchant loans in the system.';

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
        $mailingList = Repmailinglist::where('report', 'Merchant Loans Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Merchant Loans report is currently disabled, therefore I did not send anything.');
            exit();
        }

        $loans = DB::table('loans as l')
            ->join('users as u', function($join) {
                $join->on('l.partner_id', '=', 'u.id');
            })
            ->whereDate('l.created_at', Carbon::today())
            ->where('u.utype','=', 'Partner')
            ->where('l.deleted_at','=', null)
            ->count();

        if ($loans > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new DailyMerchantLoansNotifier());
                Log::info('I have sent an email with merchant loans as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the merchant loans as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any merchant loans created today to email as of now.');
        }

        return 0;
    }
}
