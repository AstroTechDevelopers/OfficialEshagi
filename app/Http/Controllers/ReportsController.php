<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanMonthlySummary;
use App\Mail\DisbursedLoansReport;
use App\Mail\MonthlyCommissionsDueReport;
use App\Mail\PendingLoansReport;
use App\Mail\SalesAdminReport;
use App\Mail\WeeklyLoanStatusReport;
use App\Models\Partner;
use App\Models\Repmailinglist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use LaravelFullCalendar\Facades\Calendar;

class ReportsController extends Controller
{
    public const EPSILON = 1e-6;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllDisbursedLoans(){
        return view('reports.disbursed-loans');
    }

    public function fetchAllDisbursedLoans(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('disbursed-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->join('commissions as m', function($join) {
                $join->on('l.id', '=', 'm.loanid');
            })
            ->select('l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.amount','l.monthly','l.paybackPeriod')
            ->whereBetween('m.created_at', [$start, $end])
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.disbursed-loans', compact('loans'));
    }

    public function getAllDeclinedLoans(){
        return view('reports.declined-loans');
    }

    public function fetchDeclinedLoans(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('declined-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.created_at','c.first_name','c.last_name','c.natid','l.loan_type','l.amount','l.monthly','l.paybackPeriod')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('l.loan_status','=', 13)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.declined-loans', compact('loans'));
    }

    public function getAllCommissions(){
        return view('reports.commission-earned');
    }

    public function fetchLoanCommissions(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('commissions-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $commissions = DB::table('commissions as c')
            ->join('clients as cl', function($join) {
                $join->on('cl.id', '=', 'c.client');
            })
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout')
            ->whereBetween('c.created_at', [$start, $end])
            ->where('c.paidout','=', true)
            ->where('c.deleted_at','=', null)
            ->get();

        return view('reports.commission-earned', compact('commissions'));
    }

    public function getAllLoans(){
        return view('reports.applied-loans');
    }

    public function fetchAllLoans(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('all-loans-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.created_at','c.first_name','c.last_name','c.natid','l.loan_type','l.loan_status','l.amount','l.monthly','l.paybackPeriod')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.applied-loans', compact('loans'));
    }

    public function getLoansByPartner(){
        $partners = User::where('utype','Partner')->get();
        return view('reports.loans-by-partner', compact('partners'));
    }

    public function fetchLoansByPartner(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('loans-by-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        if ($_POST['partner'] == null) {
            $loans = DB::table('loans as l')
                ->join('clients as c', function($join) {
                    $join->on('c.id', '=', 'l.client_id');
                })
                ->select('l.created_at','c.first_name','c.last_name','c.natid','l.loan_type','l.loan_status','l.amount','l.monthly','l.paybackPeriod')
                ->whereBetween('l.created_at', [$start, $end])
                ->where('l.deleted_at','=', null)
                ->get();
        } else{
            $loans = DB::table('loans as l')
                ->join('clients as c', function($join) {
                    $join->on('c.id', '=', 'l.client_id');
                })
                ->select('l.created_at','c.first_name','c.last_name','c.natid','l.loan_type','l.loan_status','l.amount','l.monthly','l.paybackPeriod')
                ->whereBetween('l.created_at', [$start, $end])
                ->where('l.partner_id', $_POST['partner'])
                ->where('l.deleted_at','=', null)
                ->get();
        }

        $partners = User::where('utype','Partner')->get();
        $chosenPartner = User::where('id', $_POST['partner'])->first();

        return view('reports.loans-by-partner', compact('loans', 'partners','chosenPartner'));
    }

    public function getRegisteredClients(){
        return view('reports.kyc-registered');
    }

    public function fetchRegisteredClients(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('kyc-reg-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $clients = DB::table('clients')
            ->select('created_at','title','first_name','last_name','natid','mobile','email','nationality')
            ->whereBetween('created_at', [$start, $end])
            ->where('deleted_at','=', null)
            ->get();

        return view('reports.kyc-registered', compact('clients'));
    }

    public function getMyCommissions(){
        return view('reports.my-commission');
    }

    public function fetchMyLoanCommissions(){
        $user = auth()->user();

        if (is_null($user)) {
            return redirect()->back()->with('error', 'Please make sure you\'re logged in.');
        }

        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('commissions-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $commissions = DB::table('commissions as c')
            ->join('clients as cl', function($join) {
                $join->on('cl.id', '=', 'c.client');
            })
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout')
            ->whereBetween('c.created_at', [$start, $end])
            ->where('c.agent','=', $user->name)
            ->where('c.deleted_at','=', null)
            ->get();

        return view('reports.my-commission', compact('commissions'));
    }

    public function partnerProduct(){
        return view('reports.product-performance');
    }

    public function fetchMyPartnerProducts(){
        $user = auth()->user();

        if (is_null($user)) {
            return redirect()->back()->with('error', 'Please make sure you\'re logged in.');
        }

        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('product-performance')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $products = DB::table('loans as l')
            ->join('products as p', function($join) {
                $join->on('p.pcode', '=', 'l.product');
            })
            ->select('l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('p.creator','=', $user->name)
            ->where('l.loan_status','=', 12)
            ->where('p.deleted_at','=', null)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.product-performance', compact('products'));
    }

    public function getLoansByType(){
        return view('reports.loans-by-type');
    }

    public function fetchLoansByType(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('loans-by-type')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.created_at','c.first_name','c.last_name','c.natid','l.loan_status','l.amount','l.monthly','l.paybackPeriod')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('l.loan_type','=', $_POST['loan_type'])
            ->where('l.deleted_at','=', null)
            ->get();

        $loan_type = $_POST['loan_type'];

        return view('reports.loans-by-type', compact('loans', 'loan_type'));
    }

    public function getAllLoansByAgent(){
        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.created_at','c.creator','c.first_name','c.last_name','c.natid','l.loan_type','l.loan_status','l.amount','l.monthly','l.paybackPeriod')
            ->whereDate('l.created_at', Carbon::today())
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.sales-admin', compact('loans'));
    }

    public function loanSalesPerformance(){
        return view('reports.sales-performance');
    }

    public function fetchLoanSalesPerformance(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('sales-performance')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->join('users as u', function($join) {
                $join->on('u.name', '=', 'c.creator');
            })
            ->join('role_user as r', function($join) {
                $join->on('r.user_id', '=', 'u.id');
            })
            ->select(DB::raw('sum(l.amount) as sums'),
                DB::raw("DATE_FORMAT(l.created_at,'%M %Y') as months"),'c.creator','l.amount')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('r.role_id','=', $_POST['agent'])
            ->where('l.deleted_at','=', null)
            ->groupBy('c.creator')
            ->get();

        $agentType = $_POST['agent'];

        return view('reports.sales-performance', compact('loans', 'agentType'));
    }

    public function monthlyRepayments(){
        return view('reports.monthly-repayments');
    }

    public function fetchMonthlyRepayments(){

        $repayments = DB::table('repayments as r')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'r.client_id');
            })
            ->select('r.paymt_number','r.loanid','c.first_name','c.last_name','c.natid','r.reds_number','r.payment','r.principal','r.interest','r.balance')
            ->where('r.paymt_number','=', $_POST['monthly'])
            ->where('r.deleted_at','=', null)
            ->get();

        $monthly = $_POST['monthly'];

        return view('reports.monthly-repayments', compact('repayments', 'monthly'));
    }

    public function getCallCentreWeeklyReports(){
        //current week stats
        /*$days = DB::table('loans as l')
                ->select(DB::raw('DATE(l.created_at) as date'),
                    DB::raw('DAYNAME(l.created_at) as Day'),
                    //DB::raw('SUM(CASE WHEN c.creator THEN actual END) as actual'),
                    DB::raw('SUM(l.amount) as totalAmount'),
                    DB::raw('COUNT(l.id) as actual'),'c.creator'
            )
            ->join('clients as c','c.id', '=', 'l.client_id')
            ->where(DB::raw('date(l.created_at) > DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
            ->where(DB::raw('MONTH(l.created_at) = MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at) = YEAR(CURDATE())'))
            ->where('l.locale','=', 1)
            ->where('l.deleted_at','=', null)
            ->groupBy(DB::raw('DAYNAME(l.created_at)') )
            ->orderBy('l.created_at', 'DESC')
            ->get();*/
//dd($days);
        //yearwise stats
        /*$days = DB::table('loans')
                ->select(DB::raw('COUNT(id) as Count'),
                    DB::raw('YEAR(created_at) as Year')
            )
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->groupBy(DB::raw('YEAR(created_at)') )
            ->get();*/

        $groups = [1 => ['min_range' => 1, 'max_range' => 7],
            2 => ['min_range' => 8, 'max_range' => 14],
            3 => ['min_range' => 15, 'max_range' => 21],
            4 => ['min_range' => 22, 'max_range' => 28],
            5 => ['min_range' => 29, 'max_range' => 31]];


        $output = DB::table('loans as l')
            ->select(DB::raw('COUNT(l.id) as Count'),
                DB::raw('DAY(l.created_at) as Day'),
                DB::raw('DAYNAME(l.created_at) as Weekday'),
                DB::raw('SUM(l.amount) as Total'),
                'c.creator'
            )
            ->join('clients as c', 'c.id','=','l.client_id')
            //->where(DB::raw('MONTH(l.created_at)'),'=', 12)
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            //->where(DB::raw('YEAR(l.created_at)'),'=', 2020)
            ->where('l.locale','=', 1)
            ->where('l.deleted_at','=', null)
            ->groupBy(DB::raw('DAY(l.created_at)'), 'c.creator')
            ->get()
            ->map(function ($user) use ($groups) {
                $age = $user->Day;
                foreach($groups as $key => $breakpoint) {
                    if ( in_array($age, range($breakpoint['min_range'],$breakpoint['max_range'])) ) {
                        $user->Day = $key;
                        $user->Date = $age;
                        break;
                    }
                }
                return $user;
            })
            ->mapToGroups(function ($user, $key) {
                return [$user->Day => $user];
            })
            ->map(function ($group) {
                //return $group[0]->Count;
                return $group;
            })
            ->sortKeys();

    return view('reports.call-centre-weekly', compact('output'));
    }

    public function getPendingLoansReport(){
        return view('reports.pending-loans');
    }

    public function fetchPendingLoansReport(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('pending-loans-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.created_at','c.creator','c.first_name','c.last_name','c.natid','l.loan_type','l.amount','l.monthly','l.paybackPeriod')
            ->whereBetween('l.created_at', [$start, $end])
            ->whereIn('l.loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.pending-loans', compact('loans'));
    }

    public function getCallsReport(){
        return view('reports.calls-report');
    }

    public function fetchCallsReport(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('calls-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $calls = DB::table('calls as c')
            ->join('leads as l', function($join) {
                $join->on('l.id', '=', 'c.lead_id');
            })
            ->select('c.lead_id','c.agent','c.mobile','c.isSale','l.natid','l.first_name','l.last_name','l.assignedOn','l.completedOn')
            ->whereBetween('c.created_at', [$start, $end])
            ->where('c.deleted_at','=', null)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.calls-report', compact('calls'));
    }

    public function getConvertedLeadsReport(){
        return view('reports.leads-converted-loans');
    }

    public function fetchConvertedLeadsLoansReport(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('leads-converted-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $leads = DB::table('leads')
            ->select('id','first_name','last_name','natid','agent','isSale','assignedOn','completedOn','created_at')
            ->whereBetween('created_at', [$start, $end])
            ->where('agent','!=', null)
            ->where('isSale','=', true)
            ->where('deleted_at','=', null)
            ->get();

        return view('reports.leads-converted-loans', compact('leads'));
    }

    public function leadsPerformance(){
        return view('reports.leads-performance');
    }

    public function fetchLeadsPerformance(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('leads-performance')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $leads = DB::table('leads')
            ->select(DB::raw('COUNT(id) as totalLeads'),
                DB::raw("SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(completedOn,assignedOn)))) AS timediff"),'agent','completedOn')
            ->whereBetween('created_at', [$start, $end])
            ->where('agent','!=', null)
            ->where('isSale','=', true)
            ->groupBy('agent')
            ->get();

        return view('reports.leads-performance', compact('leads'));
    }

    public function currentMonthSummary(){
        $eventfulDays = [];
        $loans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();
        if($loans->count()){
            foreach ($loans as $key => $loan) {

                $eventfulDays[] = Calendar::event(
                    $loan->Count.' loans worth: $'.$loan->Total,
                    true,
                    new \DateTime($loan->Date.'-'.date('m-Y')),
                    new \DateTime($loan->Date.'-'.date('m-Y'))
                );
            }
        }
        $calendar_details = Calendar::addEvents($eventfulDays);

        return view('reports.current-month-summary', compact('calendar_details'));
    }

    public function monthlyYearSummary(){
        $loans = DB::table('loans')
                ->select(DB::raw('COUNT(id) as Count'),
                    DB::raw('MONTHNAME(created_at) as Month'),
                    DB::raw('YEAR(created_at) as Year'),
                    DB::raw('SUM(amount) as Total')
            )
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->groupBy(DB::raw('MONTH(created_at)'),DB::raw('YEAR(created_at)') )
            ->get();

        return view('reports.monthly-year-summary', compact('loans'));
    }

    public function cronDailyDaySalesSummary(){
        //$mailingList = Repmailinglist::where('report', 'Sales Admin Report')->firstOrFail();
        $mailingList = Repmailinglist::where('report', 'Testing Report')->firstOrFail();

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

    public function cronWeeklyLoanStatsRepo(){
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

    public function cronPendingLoanStatusRep(){
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

    public function cronMonthlyCommissionsDueSumm(){
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

    public function cronDailyDisbReport(){
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

    public function getExecutiveSummaryReport() {
        //Daily Report
        $dailySales = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average')
            )
            ->whereDate('created_at', Carbon::today())
            ->where('deleted_at','=', null)
            ->get();

        //Month to date
        $monthToDateLoans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        //Year to date
        $yearToDateLoans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        //Disbursed for the Month to date
        $disbursedMonthToDateLoans = DB::table('loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('loan_status','=', 12)
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        //Daily Report
        $dailyUsdSales = DB::table('usd_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average')
            )
            ->whereDate('created_at', Carbon::today())
            ->where('deleted_at','=', null)
            ->get();

        //Month to date
        $monthToDateUsdLoans = DB::table('usd_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        //Year to date
        $yearToDateUsdLoans = DB::table('usd_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        //Disbursed for the Month to date
        $disbursedMonthToDateUsdLoans = DB::table('usd_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(amount) as Total'),
                DB::raw('AVG(amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('loan_status','=', 5)
            ->where('locale','=', 1)
            ->where('deleted_at','=', null)
            ->get();

        $dailyZambiaSales = DB::table('zambia_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('SUM(loan_principal_amount) as Total'),
                DB::raw('AVG(loan_principal_amount) as Average')
            )
            ->whereDate('created_at', Carbon::today())
            ->where('deleted_at','=', null)
            ->get();

        //Month to date
        $monthToDateZambiaLoans = DB::table('zambia_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(loan_principal_amount) as Total'),
                DB::raw('AVG(loan_principal_amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('deleted_at','=', null)
            ->get();

        //Year to date
        $yearToDateZambiaLoans = DB::table('zambia_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(loan_principal_amount) as Total'),
                DB::raw('AVG(loan_principal_amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('deleted_at','=', null)
            ->get();

        //Disbursed for the Month to date
        $disbursedMonthToDateZambiaLoans = DB::table('zambia_loans')
            ->select(DB::raw('COUNT(id) as Count'),
                DB::raw('DAY(created_at) as Date'),
                DB::raw('SUM(loan_principal_amount) as Total'),
                DB::raw('AVG(loan_principal_amount) as Average'),
                DB::raw('DAYNAME(created_at) as Weekday')
            )
            ->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('isDisbursed','=', true)
            ->where('deleted_at','=', null)
            ->get();

        return view('reports.executive-summary', compact('disbursedMonthToDateUsdLoans','yearToDateUsdLoans','monthToDateUsdLoans','dailyUsdSales','disbursedMonthToDateZambiaLoans','yearToDateZambiaLoans','monthToDateZambiaLoans','dailyZambiaSales','dailySales','monthToDateLoans','yearToDateLoans','disbursedMonthToDateLoans'));
    }

    public function getAllDisbursedDevices(){
        return view('reports.disbursed-devices');
    }

    public function fetchAllDisbursedDevices(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('disbursed-devices')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->join('commissions as m', function($join) {
                $join->on('l.id', '=', 'm.loanid');
            })
            ->select('l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.product','l.amount','l.monthly','l.paybackPeriod')
            ->whereBetween('m.created_at', [$start, $end])
            ->where('l.loan_status','=', 12)
            ->where('l.loan_type','=', 1)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.disbursed-devices', compact('loans'));
    }

     public function getZambiaDisbursedLoans(){
         return view('reports.zam-disbursed-loans');
     }

     public function fetchZambiaDisbursedLoans(){
         $daterange = $_POST['date_range'];
         $split = explode('-', $daterange);

         $count = count($split);

         if ($count <> 2) {
             return redirect('zambia-disbursed-report')->with('error', 'Ooops, something went wrong with the date picker.');
         }

         $start = date('Y-m-d h:m:s', strtotime($split[0]));
         $end = date('Y-m-d h:m:s', strtotime($split[1]));

         $loans = DB::table('zambia_loans as l')
             ->join('zambians as c', function($join) {
                 $join->on('c.id', '=', 'l.zambian_id');
             })
             ->select('l.created_at','c.first_name','c.last_name','l.cf_11130_sales_rep','c.nrc','l.loan_principal_amount','l.cf_11353_installment','l.loan_duration')
             ->whereBetween('l.created_at', [$start, $end])
             ->where('l.isDisbursed','=', true)
             ->where('l.deleted_at','=', null)
             ->get();

         return view('reports.zam-disbursed-loans', compact('loans'));
     }

     public function getZambiaDisbursedLoanDevice(){
         return view('reports.zam-disbursed-devices');
     }

    public function fetchZambiaDisbursedLoanDevice(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('zambia-disbursed-devices')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('zambia_loans as l')
            ->join('zambians as c', function($join) {
                $join->on('c.id', '=', 'l.zambian_id');
            })
            ->select('l.created_at','c.first_name','c.last_name','l.cf_11130_sales_rep','l.loan_product_id','c.nrc','l.loan_principal_amount','l.cf_11353_installment','l.loan_duration')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('l.isDisbursed','=', true)
            ->where('l.loan_product_id','!=', 118223)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.zam-disbursed-devices', compact('loans'));
    }

    public function getZambiaProcessingLoans(){
         return view('reports.zam-processing-loans');
     }

    public function fetchZambiaProcessingLoans(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('zambia-processing-loans-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('zambia_loans as l')
            ->join('zambians as c', function($join) {
                $join->on('c.id', '=', 'l.zambian_id');
            })
            ->select('l.created_at','c.first_name','c.last_name','l.cf_11130_sales_rep','l.loan_product_id','c.nrc','l.loan_principal_amount','l.cf_11353_installment','l.loan_duration')
            ->whereBetween('l.created_at', [$start, $end])
            ->whereNotIn('l.loan_status',array(1, 3, 182380, 182383, 9, 120))
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.zam-processing-loans', compact('loans'));
    }


    public function getAllZambiaLoans(){
         return view('reports.all-zambia-loans');
     }

    public function fetchAllZambiaLoans(){
        $daterange = $_POST['date_range'];
        $split = explode('-', $daterange);

        $count = count($split);

        if ($count <> 2) {
            return redirect('all-zambia-loans-report')->with('error', 'Ooops, something went wrong with the date picker.');
        }

        $start = date('Y-m-d h:m:s', strtotime($split[0]));
        $end = date('Y-m-d h:m:s', strtotime($split[1]));

        $loans = DB::table('zambia_loans as l')
            ->join('zambians as c', function($join) {
                $join->on('c.id', '=', 'l.zambian_id');
            })
            ->select('l.created_at','c.first_name','c.last_name','l.cf_11130_sales_rep','l.loan_product_id','c.nrc','l.loan_principal_amount','l.cf_11353_installment','l.loan_duration')
            ->whereBetween('l.created_at', [$start, $end])
            ->where('l.deleted_at','=', null)
            ->get();

        return view('reports.all-zambia-loans', compact('loans'));
    }

    public function getZimCommissions(){

        $loans = DB::table('loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.id','l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.interestRate','l.amount','l.monthly','l.paybackPeriod')
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        $sum = 0;
        foreach ($loans as $loan){
            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                $sum += $loanInts;
            }
        }

        $usdLoans = DB::table('usd_loans as l')
            ->join('clients as c', function($join) {
                $join->on('c.id', '=', 'l.client_id');
            })
            ->select('l.id','l.created_at','c.first_name','c.last_name','c.creator','c.natid','l.loan_type','l.interestRate','l.amount','l.monthly','l.tenure')
            ->where(DB::raw('MONTH(l.created_at)'),'=', DB::raw('MONTH(CURDATE())'))
            ->where(DB::raw('YEAR(l.created_at)'),'=', DB::raw('YEAR(CURDATE())'))
            ->where('l.loan_status','=', 12)
            ->where('l.deleted_at','=', null)
            ->get();

        $usdSum = 0;
        foreach ($usdLoans as $loan){
            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                $usdSum += $loanInts;
            }
        }

//        $loanChannelsData = [];
//
//        $loanChannels = DB::table('loans')
//            ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id','paybackPeriod','interestRate','amount',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
//            //->where(DB::raw('MONTH(created_at)'),'=', DB::raw('MONTH(CURDATE())'))
//            ->where(DB::raw('YEAR(created_at)'),'=', DB::raw('YEAR(CURDATE())'))
//            ->where('loan_status','=', 12)
//            ->where('deleted_at','=', null)
//            ->groupBy('channel_id')
//            ->orderBy('cnt','DESC')
//            ->get();
//        $channelSum = 0;
//        foreach($loanChannels as $loan){
//            $loanChannelsData[$loan->channel_id] = $loan->cnt;
//            for ($i = 1; $i <= $loan->paybackPeriod; $i++){
//                $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
//                $channelSum += $loanInts;
//            }
//            $loanChannelsData["Revenue"] = $channelSum;
//        }
////print_r($channelSum);
//        dd($loanChannelsData);

        return view('reports.zim-commissions-loans', compact('sum', 'loans','usdSum','usdLoans'));
    }


    /**
     * Interest on a financial payment for a loan or annuity with compound interest.
     * Determines the interest payment at a particular period of the annuity. For
     * a typical loan paid down to zero, the amount of interest and principle paid
     * throughout the lifetime of the loan will change, with the interest portion
     * of the payment decreasing over time as the loan principle decreases.
     *
     * Same as the =IPMT() function in most spreadsheet software.
     *
     * See the PMT function for derivation of the formula. For IPMT, we have
     * the payment equal to the interest portion and principle portion of the payment:
     *
     * PMT = IPMT + PPMT
     *
     * The interest portion IPMT on a regular annuity can be calculated by computing
     * the future value of the annuity for the prior period and computing the compound
     * interest for one period:
     *
     * IPMT = FV(p=n-1) * rate
     *
     * For an "annuity due" where payment is at the start of the period, period=1 has
     * no interest portion of the payment because no time has elapsed for compounding.
     * To compute the interest portion of the payment, the future value of 2 periods
     * back needs to be computed, as the definition of a period is different, giving:
     *
     * IPMT = (FV(p=n-2) - PMT) * rate
     *
     * By thinking of the future value at period 0 instead of the present value, the
     * given formulas are computed.
     *
     * Example of regular annuity and annuity due for a loan of $10.00 paid back in 3 periods.
     * Although the principle payments are equal, the total payment and interest portion are
     * lower with the annuity due because a principle payment is made immediately.
     *
     *                       Regular Annuity |  Annuity Due
     * Period   FV       PMT    IPMT   PPMT  |   PMT    IPMT    PPMT
     *   0     -10.00                        |
     *   1      -6.83   -3.67  -0.50  -3.17  |  -3.50   0.00   -3.50
     *   2      -3.50   -3.67  -0.34  -3.33  |  -3.50  -0.33   -3.17
     *   3       0.00   -3.67  -0.17  -3.50  |  -3.50  -0.17   -3.33
     *                -----------------------|----------------------
     *             SUM -11.01  -1.01 -10.00  | -10.50  -0.50  -10.00
     *
     * Examples:
     * The interest on a payment on a 30-year fixed mortgage note of $265000 at 3.5% interest
     * paid at the end of every month, looking at the first payment:
     *   ipmt(0.035/12, 1, 30*12, 265000, 0, false)
     *
     * @param  float $rate
     * @param  int   $period
     * @param  int   $periods
     * @param  float $present_value
     * @param  float $future_value
     * @param  bool  $beginning adjust the payment to the beginning or end of the period
     *
     * @return float
     */
    public function ipmt(float $rate, int $period, int $periods, float $present_value, float $future_value = 0.0, bool $beginning = false): float
    {
        if ($period < 1 || $period > $periods) {
            return \NAN;
        }

        if ($rate == 0) {
            return 0;
        }

        if ($beginning && $period == 1) {
            return 0.0;
        }

        $payment = self::pmt($rate, $periods, $present_value, $future_value, $beginning);
        if ($beginning) {
            $interest = (self::fv($rate, $period - 2, $payment, $present_value, $beginning) - $payment) * $rate;
        } else {
            $interest = self::fv($rate, $period - 1, $payment, $present_value, $beginning) * $rate;
        }

        return self::checkZero($interest);
    }

    private static function checkZero(float $value, float $epsilon = self::EPSILON): float
    {
        return \abs($value) < $epsilon ? 0.0 : $value;
    }

    public static function pmt(float $rate, int $periods, float $present_value, float $future_value = 0.0, bool $beginning = false): float
    {
        $when = $beginning ? 1 : 0;

        if ($rate == 0) {
            return - ($future_value + $present_value) / $periods;
        }

        return - ($future_value + ($present_value * \pow(1 + $rate, $periods)))
            /
            ((1 + $rate * $when) / $rate * (\pow(1 + $rate, $periods) - 1));
    }

    /**
     * Future value for a loan or annuity with compound interest.
     *
     * Same as the =FV() function in most spreadsheet software.
     *
     * The basic future-value formula derivation:
     * https://en.wikipedia.org/wiki/Future_value
     *
     *                   PMT*((1+r)ᴺ - 1)
     * FV = -PV*(1+r)ᴺ - ----------------
     *                          r
     *
     * The (1+r*when) factor adjusts the payment to the beginning or end
     * of the period. In the common case of a payment at the end of a period,
     * the factor is 1 and reduces to the formula above. Setting when=1 computes
     * an "annuity due" with an immediate payment.
     *
     * Examples:
     * The future value in 5 years on a 30-year fixed mortgage note of $265000
     * at 3.5% interest paid at the end of every month. This is how much loan
     * principle would be outstanding:
     *   fv(0.035/12, 5*12, 1189.97, -265000, false)
     *
     * The present_value is negative indicating money borrowed for the mortgage,
     * whereas payment is positive, indicating money that will be paid to the
     * mortgage.
     *
     * @param  float $rate
     * @param  int   $periods
     * @param  float $payment
     * @param  float $present_value
     * @param  bool  $beginning adjust the payment to the beginning or end of the period
     *
     * @return float
     */
    public static function fv(float $rate, int $periods, float $payment, float $present_value, bool $beginning = false): float
    {
        $when = $beginning ? 1 : 0;

        if ($rate == 0) {
            $fv = -($present_value + ($payment * $periods));
            return self::checkZero($fv);
        }

        $initial  = 1 + ($rate * $when);
        $compound = \pow(1 + $rate, $periods);
        $fv       = - (($present_value * $compound) + (($payment * $initial * ($compound - 1)) / $rate));

        return self::checkZero($fv);
    }

}
