<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\ZambiaDisbursedLoansNotifier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ZambiaDailyDisbursedReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:zambia-disbursedloans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send an email for zambia disbursed loans in the system.';

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
        $mailingList = Repmailinglist::where('report', 'Zambia Disbursed Loans Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Zambia Disbursed loans report is currently disabled, therefore I did not send anything.');
            exit();
        }

        $loans = DB::table('zambia_loans as l')
            ->whereDate('l.disbursed_at', Carbon::today())
            ->where('l.isDisbursed','=', true)
            ->where('l.deleted_at','=', null)
            ->count();

        if ($loans > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new ZambiaDisbursedLoansNotifier());
                Log::info('I have sent an email with zambia disbursed loans as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the zambia daily disbursed as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any zambia disbursed loans to email as of now.');
        }

        return 0;
    }
}
