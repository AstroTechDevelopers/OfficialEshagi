<?php

namespace App\Console\Commands;

use App\Models\Repmailinglist;
use App\Notifications\ZambiaWeeklyStatusReportNotifier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ZambiaWeeklyLoanStatusReporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:zambia-weeklystatusreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to email weekly zambia loan status report';

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
        $mailingList = Repmailinglist::where('report', 'Zambia Loans Weekly Status Report')->firstOrFail();

        if ($mailingList->active == false) {
            Log::info('Zambia Loans Weekly Status report is currently disabled, therefore I did not send anything.');
            exit();
        }

        $loans = DB::table('zambia_loans as l')
            ->join('zambians as c','c.id', '=', 'l.zambian_id')
            ->where(DB::raw('date(l.created_at)'), '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
            ->where(DB::raw('MONTH(l.created_at)'), '=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'), '=', DB::raw('YEAR(CURDATE())'))
            ->where('l.deleted_at','=', null)
            ->groupBy('l.loan_status','l.id' )
            ->orderBy('l.created_at', 'DESC')
            ->count();

        if ($loans > 0){
            $recipients = array_values(explode(',', $mailingList->list));

            try {
                Notification::route('mail', $recipients)->notify(new ZambiaWeeklyStatusReportNotifier());
                Log::info('I have sent an email with zambia weekly stats of loans as of now.');
            } catch (\Exception $exception){
                Log::error('I failed to send the zambia weekly loan status as of now: '.$exception);
            }
        } else {
            Log::info('I did not find any zambia loans created this week to email as of now.');
        }

        return 0;
    }
}
