<?php

namespace App\Http\Controllers;

use App\Mail\LoanDisbursements;
use App\Models\Client;
use App\Models\DeviceLoan;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Representative;
use App\Models\UsdLoan;
use App\Models\User;
use App\Models\ZambiaLoan;
use App\Models\Zambian;
use App\Notifications\KYCChangeRequest;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isRoot()) {
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('loan_type','=',2)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('loan_type','=',1)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $cashLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $creditLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($cashloans as $order){
                $cashLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($creditloans as $order){
                $creditLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $merchloans = DB::table('loans as l')->select(
                DB::raw('sum(l.amount) as sums'),
                DB::raw("DATE_FORMAT(l.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(l.created_at,'%m') as monthKey")
            )
                ->join('users as u', 'u.id','=','l.partner_id')
                ->where('l.loan_type','=',2)
                ->where('l.loan_status','=',12)
                ->where('u.utype','=','Partner')
                ->where('u.last_name','=','Merchant')
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('l.created_at', 'ASC')
                ->get();

            $agentloans = DB::table('loans as l')->select(
                DB::raw('sum(l.amount) as sums'),
                DB::raw("DATE_FORMAT(l.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(l.created_at,'%m') as monthKey")
            )
                ->join('users as u', 'u.id','=','l.partner_id')
                ->where('l.loan_type','=',2)
                ->where('l.loan_status','=',12)
                ->where('u.utype','=','Partner')
                ->where('u.last_name','=','Agent')
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('l.created_at', 'ASC')
                ->get();

            $merchantLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $agentLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($merchloans as $order){
                $merchantLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($agentloans as $order){
                $agentLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $newLoans = DB::table('loans')
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->count();

            $newLoansValue = DB::table('loans')
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $pendingLoans = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->count();

            $pendingLoansValue = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $disbursedLoans = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->count();

            $disbursedLoansValue = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $paidBackLoans = DB::table('loans')
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->count();

            $paidBackLoansValue = DB::table('loans')
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $systemUsers = DB::table('users')
                ->where('utype','=', 'System')
                ->where('deleted_at','=',null)
                ->count();

            $partnersUsers = DB::table('users')
                ->where('utype','=', 'Partner')
                ->where('deleted_at','=',null)
                ->count();

            $clientUsers = DB::table('users')
                ->where('utype','=', 'Client')
                ->where('deleted_at','=',null)
                ->count();

            $funders = DB::table('funders')
                ->where('deleted_at','=',null)
                ->count();

            $merchantsCount = DB::table('partners')
                ->where('partner_type','=', 'Merchant')
                ->where('deleted_at','=',null)
                ->count();

            $agentsCount = DB::table('partners')
                ->where('partner_type','=', 'Agent')
                ->where('deleted_at','=',null)
                ->count();

            $commissions = DB::table('commissions')
                ->select(
                DB::raw('sum(commission) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('paidout','=', true)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $commissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            $unPaidCommissions = DB::table('commissions')
                ->select(
                DB::raw('sum(commission) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('paidout','=', false)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $unPaidCommissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($commissions as $commission){
                $commissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            foreach($unPaidCommissions as $commission){
                $unPaidCommissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            $bestsellersData =[];
            $bestsellers = DB::table('loans as l')
                ->join('products as p','p.pcode','=','l.product')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 12)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($bestsellers as $order){
                $bestsellersData[$order->pcode] = $order->cnt;
            }

            $bestloansData = [];

            $bestloans = DB::table('loans') // 1: Store Credit; 2: Cash Loan; 3: Recharge Credit; 4: Hybrid
                ->select(DB::raw('COUNT(id) as cnt'),'id','loan_status',DB::raw('(CASE WHEN loan_type = 1 THEN "Store Credit" WHEN loan_type = 2 THEN "Cash Loan" WHEN loan_type = 3 THEN "Recharge Credit" WHEN loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'))
                ->where('loan_status','=', 12)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('loan_type')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($bestloans as $order){
                $bestloansData[$order->loan_type] = $order->cnt;
            }

            $mostFinancedData =[];
            $mostFinanced = DB::table('zambia_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinanced as $order){
                $mostFinancedData[$order->pcode] = $order->cnt;
            }

            $mostFinancedZimData =[];
            $mostFinancedZim = DB::table('device_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinancedZim as $order){
                $mostFinancedZimData[$order->pcode] = $order->cnt;
            }

            $leadsAllocated = DB::table('leads')
                ->select(
                    DB::raw('COUNT(id) as allocated'),
                    DB::raw("DATE_FORMAT(assignedOn,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(assignedOn,'%m') as monthKey")
                )
                ->where('agent','!=', null)
                ->where('isSale','=', false)
                ->whereRaw('YEAR(assignedOn) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->where('deleted_at','=', null)
                ->orderBy('assignedOn', 'ASC')
                ->get();

            $leadsAllocatedData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($leadsAllocated as $lead){
                $leadsAllocatedData[$lead->monthKey-1] = $lead->allocated;
            }

            $callsMade = DB::table('calls')
                ->select(
                    DB::raw('COUNT(id) as called'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('agent','!=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->where('deleted_at','=', null)
                ->orderBy('created_at', 'ASC')
                ->get();

            $callsMadeData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($callsMade as $call){
                $callsMadeData[$call->monthKey-1] = $call->called;
            }

            $leadsConversionData = [];

            $leadsData = DB::table('leads')
            ->select(DB::raw('COUNT(id) as cnt'),'id',DB::raw('(CASE WHEN isSale = 1 THEN "Converted" WHEN isSale = 0 THEN "Not yet a Sale" ELSE 0 END) AS classifi'))
                ->where('agent','!=', null)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('classifi')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($leadsData as $leadClass){
                $leadsConversionData[$leadClass->classifi] = $leadClass->cnt;
            }

            $callsConversionData = [];

            $callsData = DB::table('calls')
            ->select(DB::raw('COUNT(id) as cnt'),'id',DB::raw('(CASE WHEN isSale = 1 THEN "Sale" WHEN isSale = 0 THEN "Not yet a Sale" ELSE 0 END) AS classifi'))
                ->where('agent','!=', null)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('classifi')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($callsData as $callClass){
                $callsConversionData[$callClass->classifi] = $callClass->cnt;
            }

            $zimLoansToday = Loan::whereDate('created_at', Carbon::today())->count();
            $zimUsdLoansToday = UsdLoan::whereDate('created_at', Carbon::today())->count();
            $zimMonthToDate= DB::table('loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimUsdMonthToDate= DB::table('usd_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zamLoansToday = ZambiaLoan::whereDate('created_at', Carbon::today())->count();
            $zamMonthToDate= DB::table('zambia_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimDeviceLoansToday = DeviceLoan::whereDate('created_at', Carbon::today())->count();
            $zimDeviceLoansMonthDate= DB::table('device_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();

            $loanChannelsData = [];

            $loanChannels = DB::table('loans')
            ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($loanChannels as $channel){
                $loanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $usdLoanChannelsData = [];

            $usdLoanChannels = DB::table('usd_loans')
            ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($usdLoanChannels as $channel){
                $usdLoanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $avgTurnTimes = DB::table('loans')
                ->selectRaw("AVG(TIMESTAMPDIFF(HOUR, created_at, disbursed_at)) as diffday, DATE_FORMAT(created_at,'%M %Y') as months, DATE_FORMAT(created_at,'%m') as monthKey")
                ->where('loan_status','=', 12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            $turnAroundData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($avgTurnTimes as $timing){
                $turnAroundData[$timing->monthKey-1] = number_format($timing->diffday, 2,'.', '');
            }

            $zwlLoans = DB::table('loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zwlLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zwlLoans as $order){
                $zwlLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $zmkLoans = DB::table('zambia_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zmkLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zmkLoans as $order){
                $zmkLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $devLoans = DB::table('device_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $devLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($devLoans as $order){
                $devLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $usdLoans = DB::table('usd_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $usdLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($usdLoans as $order){
                $usdLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $cumLoans = DB::table('loans as l')
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
            foreach ($cumLoans as $loan){
                for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                    $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                    $sum += $loanInts;
                }
            }

            $cumUsdLoans = DB::table('usd_loans as l')
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
            foreach ($cumUsdLoans as $loan){
                for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                    $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                    $usdSum += $loanInts;
                }
            }

            return view('pages.root.home', compact('sum','cumLoans','cumUsdLoans','usdSum','zwlLoansData','zmkLoansData','devLoansData','usdLoansData','turnAroundData','mostFinancedZimData','mostFinancedData','agentLoansData','merchantLoansData','usdLoanChannelsData','loanChannelsData','merchantsCount','agentsCount','zimDeviceLoansMonthDate','zimDeviceLoansToday','zamMonthToDate','zamLoansToday','zimMonthToDate','zimUsdMonthToDate','zimUsdLoansToday','zimLoansToday','cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans','newLoansValue','pendingLoansValue','disbursedLoansValue','paidBackLoansValue', 'systemUsers', 'partnersUsers', 'clientUsers', 'funders', 'commissionData', 'unPaidCommissionData','bestsellersData', 'bestloansData', 'leadsAllocatedData', 'callsMadeData','leadsConversionData', 'callsConversionData'));
        }
        elseif ($user->isAdmin()) {
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('loan_type','=',2)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('loan_type','=',1)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $cashLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $creditLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($cashloans as $order){
                $cashLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($creditloans as $order){
                $creditLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $merchloans = DB::table('loans as l')->select(
                DB::raw('sum(l.amount) as sums'),
                DB::raw("DATE_FORMAT(l.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(l.created_at,'%m') as monthKey")
            )
                ->join('users as u', 'u.id','=','l.partner_id')
                ->where('l.loan_type','=',2)
                ->where('l.loan_status','=',12)
                ->where('u.utype','=','Partner')
                ->where('u.last_name','=','Merchant')
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('l.created_at', 'ASC')
                ->get();

            $agentloans = DB::table('loans as l')->select(
                DB::raw('sum(l.amount) as sums'),
                DB::raw("DATE_FORMAT(l.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(l.created_at,'%m') as monthKey")
            )
                ->join('users as u', 'u.id','=','l.partner_id')
                ->where('l.loan_type','=',2)
                ->where('l.loan_status','=',12)
                ->where('u.utype','=','Partner')
                ->where('u.last_name','=','Agent')
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('l.created_at', 'ASC')
                ->get();

            $merchantLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $agentLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($merchloans as $order){
                $merchantLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($agentloans as $order){
                $agentLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $newLoans = DB::table('loans')
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->count();

            $newLoansValue = DB::table('loans')
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $pendingLoans = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->count();

            $pendingLoansValue = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $disbursedLoans = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->count();

            $disbursedLoansValue = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $paidBackLoans = DB::table('loans')
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->count();

            $paidBackLoansValue = DB::table('loans')
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $systemUsers = DB::table('users')
                ->where('utype','=', 'System')
                ->where('deleted_at','=',null)
                ->count();

            $partnersUsers = DB::table('users')
                ->where('utype','=', 'Partner')
                ->where('deleted_at','=',null)
                ->count();

            $clientUsers = DB::table('users')
                ->where('utype','=', 'Client')
                ->where('deleted_at','=',null)
                ->count();

            $funders = DB::table('funders')
                ->where('deleted_at','=',null)
                ->count();

            $merchantsCount = DB::table('partners')
                ->where('partner_type','=', 'Merchant')
                ->where('deleted_at','=',null)
                ->count();

            $agentsCount = DB::table('partners')
                ->where('partner_type','=', 'Agent')
                ->where('deleted_at','=',null)
                ->count();

            $commissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', true)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $commissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            $unPaidCommissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', false)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $unPaidCommissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($commissions as $commission){
                $commissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            foreach($unPaidCommissions as $commission){
                $unPaidCommissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            $bestsellersData =[];
            $bestsellers = DB::table('loans as l')
                ->join('products as p','p.pcode','=','l.product')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 12)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($bestsellers as $order){
                $bestsellersData[$order->pcode] = $order->cnt;
            }

            $bestloansData = [];

            $bestloans = DB::table('loans') // 1: Store Credit; 2: Cash Loan; 3: Recharge Credit; 4: Hybrid
            ->select(DB::raw('COUNT(id) as cnt'),'id','loan_status',DB::raw('(CASE WHEN loan_type = 1 THEN "Store Credit" WHEN loan_type = 2 THEN "Cash Loan" WHEN loan_type = 3 THEN "Recharge Credit" WHEN loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'))
                ->where('loan_status','=', 12)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('loan_type')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($bestloans as $order){
                $bestloansData[$order->loan_type] = $order->cnt;
            }

            $mostFinancedData =[];
            $mostFinanced = DB::table('zambia_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinanced as $order){
                $mostFinancedData[$order->pcode] = $order->cnt;
            }

            $mostFinancedZimData =[];
            $mostFinancedZim = DB::table('device_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinancedZim as $order){
                $mostFinancedZimData[$order->pcode] = $order->cnt;
            }

            $leadsAllocated = DB::table('leads')
                ->select(
                    DB::raw('COUNT(id) as allocated'),
                    DB::raw("DATE_FORMAT(assignedOn,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(assignedOn,'%m') as monthKey")
                )
                ->where('agent','!=', null)
                ->where('isSale','=', false)
                ->whereRaw('YEAR(assignedOn) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->where('deleted_at','=', null)
                ->orderBy('assignedOn', 'ASC')
                ->get();

            $leadsAllocatedData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($leadsAllocated as $lead){
                $leadsAllocatedData[$lead->monthKey-1] = $lead->allocated;
            }

            $callsMade = DB::table('calls')
                ->select(
                    DB::raw('COUNT(id) as called'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('agent','!=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->where('deleted_at','=', null)
                ->orderBy('created_at', 'ASC')
                ->get();

            $callsMadeData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($callsMade as $call){
                $callsMadeData[$call->monthKey-1] = $call->called;
            }

            $leadsConversionData = [];

            $leadsData = DB::table('leads')
                ->select(DB::raw('COUNT(id) as cnt'),'id',DB::raw('(CASE WHEN isSale = 1 THEN "Converted" WHEN isSale = 0 THEN "Not yet a Sale" ELSE 0 END) AS classifi'))
                ->where('agent','!=', null)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('classifi')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($leadsData as $leadClass){
                $leadsConversionData[$leadClass->classifi] = $leadClass->cnt;
            }

            $callsConversionData = [];

            $callsData = DB::table('calls')
                ->select(DB::raw('COUNT(id) as cnt'),'id',DB::raw('(CASE WHEN isSale = 1 THEN "Sale" WHEN isSale = 0 THEN "Not yet a Sale" ELSE 0 END) AS classifi'))
                ->where('agent','!=', null)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('classifi')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($callsData as $callClass){
                $callsConversionData[$callClass->classifi] = $callClass->cnt;
            }

            $zimLoansToday = Loan::whereDate('created_at', Carbon::today())->count();
            $zimUsdLoansToday = UsdLoan::whereDate('created_at', Carbon::today())->count();
            $zimMonthToDate= DB::table('loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimUsdMonthToDate= DB::table('usd_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zamLoansToday = ZambiaLoan::whereDate('created_at', Carbon::today())->count();
            $zamMonthToDate= DB::table('zambia_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimDeviceLoansToday = DeviceLoan::whereDate('created_at', Carbon::today())->count();
            $zimDeviceLoansMonthDate= DB::table('device_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();

            $loanChannelsData = [];

            $loanChannels = DB::table('loans')
                ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($loanChannels as $channel){
                $loanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $usdLoanChannelsData = [];

            $usdLoanChannels = DB::table('usd_loans')
                ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($usdLoanChannels as $channel){
                $usdLoanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $avgTurnTimes = DB::table('loans')
                ->selectRaw("AVG(TIMESTAMPDIFF(HOUR, created_at, disbursed_at)) as diffday, DATE_FORMAT(created_at,'%M %Y') as months, DATE_FORMAT(created_at,'%m') as monthKey")
                ->where('loan_status','=', 12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            $turnAroundData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($avgTurnTimes as $timing){
                $turnAroundData[$timing->monthKey-1] = number_format($timing->diffday, 2,'.', '');
            }

            $zwlLoans = DB::table('loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zwlLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zwlLoans as $order){
                $zwlLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $zmkLoans = DB::table('zambia_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zmkLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zmkLoans as $order){
                $zmkLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $devLoans = DB::table('device_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $devLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($devLoans as $order){
                $devLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $usdLoans = DB::table('usd_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $usdLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($usdLoans as $order){
                $usdLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $cumLoans = DB::table('loans as l')
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
            foreach ($cumLoans as $loan){
                for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                    $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                    $sum += $loanInts;
                }
            }

            $cumUsdLoans = DB::table('usd_loans as l')
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
            foreach ($cumUsdLoans as $loan){
                for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                    $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                    $usdSum += $loanInts;
                }
            }

            return view('pages.admin.home', compact('sum','cumLoans','cumUsdLoans','usdSum','zwlLoansData','zmkLoansData','devLoansData','usdLoansData','turnAroundData','mostFinancedZimData','mostFinancedData','agentLoansData','merchantLoansData','usdLoanChannelsData','loanChannelsData','merchantsCount','agentsCount','zimDeviceLoansMonthDate','zimDeviceLoansToday','zamMonthToDate','zamLoansToday','zimMonthToDate','zimUsdMonthToDate','zimUsdLoansToday','zimLoansToday','cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans','newLoansValue','pendingLoansValue','disbursedLoansValue','paidBackLoansValue', 'systemUsers', 'partnersUsers', 'clientUsers', 'funders', 'commissionData', 'unPaidCommissionData','bestsellersData', 'bestloansData', 'leadsAllocatedData', 'callsMadeData','leadsConversionData', 'callsConversionData'));
        }
        elseif ($user->isGroup()) {
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('loan_type','=',2)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('loan_type','=',1)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $cashLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $creditLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($cashloans as $order){
                $cashLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($creditloans as $order){
                $creditLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $newLoans = DB::table('loans')
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->count();

            $newLoansValue = DB::table('loans')
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $pendingLoans = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->count();

            $pendingLoansValue = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $disbursedLoans = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->count();

            $disbursedLoansValue = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $paidBackLoans = DB::table('loans')
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->count();

            $paidBackLoansValue = DB::table('loans')
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $systemUsers = DB::table('users')
                ->where('utype','=', 'System')
                ->where('deleted_at','=',null)
                ->count();

            $partnersUsers = DB::table('users')
                ->where('utype','=', 'Partner')
                ->where('deleted_at','=',null)
                ->count();

            $clientUsers = DB::table('users')
                ->where('utype','=', 'Client')
                ->where('deleted_at','=',null)
                ->count();

            $funders = DB::table('funders')
                ->where('deleted_at','=',null)
                ->count();

            $commissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', true)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $commissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($commissions as $commission){
                $commissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            $unPaidCommissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', false)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $unPaidCommissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($unPaidCommissions as $commission){
                $unPaidCommissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            $bestsellersData =[];
            $bestsellers = DB::table('loans as l')
                ->join('products as p','p.pcode','=','l.product')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 12)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($bestsellers as $order){
                $bestsellersData[$order->pcode] = $order->cnt;
            }

            $bestloansData =[];
            $bestloans = DB::table('loans') // 1: Store Credit; 2: Cash Loan; 3: Recharge Credit; 4: Hybrid
            ->select(DB::raw('COUNT(id) as cnt'),'id','loan_status',DB::raw('(CASE WHEN loan_type = 1 THEN "Store Credit" WHEN loan_type = 2 THEN "Cash Loan" WHEN loan_type = 3 THEN "Recharge Credit" WHEN loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'))
                ->where('loan_status','=', 12)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('loan_type')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($bestloans as $order){
                $bestloansData[$order->loan_type] = $order->cnt;
            }

            $leadsAllocated = DB::table('leads')
                ->select(
                    DB::raw('COUNT(id) as allocated'),
                    DB::raw("DATE_FORMAT(assignedOn,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(assignedOn,'%m') as monthKey")
                )
                ->where('agent','!=', null)
                ->where('isSale','=', false)
                ->whereRaw('YEAR(assignedOn) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->where('deleted_at','=', null)
                ->orderBy('assignedOn', 'ASC')
                ->get();

            $leadsAllocatedData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($leadsAllocated as $lead){
                $leadsAllocatedData[$lead->monthKey-1] = $lead->allocated;
            }

            $callsMade = DB::table('calls')
                ->select(
                    DB::raw('COUNT(id) as called'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('agent','!=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->where('deleted_at','=', null)
                ->orderBy('created_at', 'ASC')
                ->get();

            $callsMadeData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($callsMade as $call){
                $callsMadeData[$call->monthKey-1] = $call->called;
            }

            $leadsConversionData = [];

            $leadsData = DB::table('leads')
                ->select(DB::raw('COUNT(id) as cnt'),'id',DB::raw('(CASE WHEN isSale = 1 THEN "Converted" WHEN isSale = 0 THEN "Not yet a Sale" ELSE 0 END) AS classifi'))
                ->where('agent','!=', null)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('classifi')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($leadsData as $leadClass){
                $leadsConversionData[$leadClass->classifi] = $leadClass->cnt;
            }

            $callsConversionData = [];

            $callsData = DB::table('calls')
                ->select(DB::raw('COUNT(id) as cnt'),'id',DB::raw('(CASE WHEN isSale = 1 THEN "Sale" WHEN isSale = 0 THEN "Not yet a Sale" ELSE 0 END) AS classifi'))
                ->where('agent','!=', null)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('classifi')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($callsData as $callClass){
                $callsConversionData[$callClass->classifi] = $callClass->cnt;
            }

            $zimLoansToday = Loan::whereDate('created_at', Carbon::today())->count();
            $zimUsdLoansToday = UsdLoan::whereDate('created_at', Carbon::today())->count();
            $zimMonthToDate= DB::table('loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimUsdMonthToDate= DB::table('usd_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zamLoansToday = ZambiaLoan::whereDate('created_at', Carbon::today())->count();
            $zamMonthToDate= DB::table('zambia_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimDeviceLoansToday = DeviceLoan::whereDate('created_at', Carbon::today())->count();
            $zimDeviceLoansMonthDate= DB::table('device_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();

            return view('pages.group.home', compact('zimDeviceLoansMonthDate','zimDeviceLoansToday','zamMonthToDate','zamLoansToday','zimMonthToDate','zimUsdMonthToDate','zimUsdLoansToday','zimLoansToday','cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans','newLoansValue','pendingLoansValue','disbursedLoansValue','paidBackLoansValue', 'systemUsers', 'partnersUsers', 'clientUsers', 'funders', 'commissionData', 'unPaidCommissionData','bestsellersData', 'bestloansData', 'leadsAllocatedData', 'callsMadeData','leadsConversionData', 'callsConversionData'));
        }
        elseif ($user->isManager()){
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_type','=',2)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_type','=',1)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $cashLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $creditLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($cashloans as $order){
                $cashLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($creditloans as $order){
                $creditLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $newLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->count();

            $newLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $pendingLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->count();

            $pendingLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $disbursedLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->count();

            $disbursedLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $paidBackLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->count();

            $paidBackLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $commissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', true)
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $commissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($commissions as $commission){
                $commissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }
            $bestsellersData =[];
            $bestsellers = DB::table('loans as l')
                ->join('products as p','p.pcode','=','l.product')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 12)
                ->where('l.locale','=',auth()->user()->locale)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($bestsellers as $order){
                $bestsellersData[$order->pcode] = $order->cnt;
            }

            $bestloansData =[];
            $bestloans = DB::table('loans') // 1: Store Credit; 2: Cash Loan; 3: Recharge Credit; 4: Hybrid
            ->select(DB::raw('COUNT(id) as cnt'),'id','loan_status',DB::raw('(CASE WHEN loan_type = 1 THEN "Store Credit" WHEN loan_type = 2 THEN "Cash Loan" WHEN loan_type = 3 THEN "Recharge Credit" WHEN loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'))
                ->where('loan_status','=', 12)
                ->where('locale','=',auth()->user()->locale)
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('loan_type')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($bestloans as $order){
                $bestloansData[$order->loan_type] = $order->cnt;
            }

            $zwlLoans = DB::table('loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zwlLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zwlLoans as $order){
                $zwlLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $zmkLoans = DB::table('zambia_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zmkLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zmkLoans as $order){
                $zmkLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $devLoans = DB::table('device_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $devLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($devLoans as $order){
                $devLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $usdLoans = DB::table('usd_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $usdLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($usdLoans as $order){
                $usdLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $avgTurnTimes = DB::table('loans')
                ->selectRaw("AVG(TIMESTAMPDIFF(HOUR, created_at, disbursed_at)) as diffday, DATE_FORMAT(created_at,'%M %Y') as months, DATE_FORMAT(created_at,'%m') as monthKey")
                ->where('loan_status','=', 12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();
            $turnAroundData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($avgTurnTimes as $timing){
                $turnAroundData[$timing->monthKey-1] = number_format($timing->diffday, 2,'.', '');
            }

            $mostFinancedData =[];
            $mostFinanced = DB::table('zambia_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinanced as $order){
                $mostFinancedData[$order->pcode] = $order->cnt;
            }

            $mostFinancedZimData =[];
            $mostFinancedZim = DB::table('device_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinancedZim as $order){
                $mostFinancedZimData[$order->pcode] = $order->cnt;
            }

            $loanChannelsData = [];

            $loanChannels = DB::table('loans')
                ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($loanChannels as $channel){
                $loanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $usdLoanChannelsData = [];

            $usdLoanChannels = DB::table('usd_loans')
                ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($usdLoanChannels as $channel){
                $usdLoanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $merchantsCount = DB::table('partners')
                ->where('partner_type','=', 'Merchant')
                ->where('deleted_at','=',null)
                ->count();

            $agentsCount = DB::table('partners')
                ->where('partner_type','=', 'Agent')
                ->where('deleted_at','=',null)
                ->count();

            $zimLoansToday = Loan::whereDate('created_at', Carbon::today())->count();
            $zimUsdLoansToday = UsdLoan::whereDate('created_at', Carbon::today())->count();
            $zimMonthToDate= DB::table('loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimUsdMonthToDate= DB::table('usd_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zamLoansToday = ZambiaLoan::whereDate('created_at', Carbon::today())->count();
            $zamMonthToDate= DB::table('zambia_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimDeviceLoansToday = DeviceLoan::whereDate('created_at', Carbon::today())->count();
            $zimDeviceLoansMonthDate= DB::table('device_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();

            $cumLoans = DB::table('loans as l')
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
            foreach ($cumLoans as $loan){
                for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                    $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                    $sum += $loanInts;
                }
            }

            $cumUsdLoans = DB::table('usd_loans as l')
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
            foreach ($cumUsdLoans as $loan){
                for ($i = 1; $i <= $loan->paybackPeriod; $i++){
                    $loanInts = (-1)*self::ipmt(($loan->interestRate/100)/12, $i, $loan->paybackPeriod, $loan->amount, 0, false);
                    $usdSum += $loanInts;
                }
            }

            return view('pages.manager.home', compact('sum','cumLoans','cumUsdLoans','usdSum','zimDeviceLoansMonthDate','zimDeviceLoansToday','zamMonthToDate','zamLoansToday','zimMonthToDate','zimUsdMonthToDate','zimUsdLoansToday','zimLoansToday','agentsCount','merchantsCount','loanChannelsData','usdLoanChannelsData','mostFinancedData','mostFinancedZimData','turnAroundData','zwlLoansData','zmkLoansData','devLoansData','usdLoansData','cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans','newLoansValue','pendingLoansValue','disbursedLoansValue','paidBackLoansValue', 'commissionData', 'bestsellersData', 'bestloansData'));
        }
        elseif ($user->hasRole('loansofficer')){
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_type','=',2)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_type','=',1)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $cashLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $creditLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($cashloans as $order){
                $cashLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($creditloans as $order){
                $creditLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $newLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->count();

            $newLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $pendingLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->count();

            $pendingLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $disbursedLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->count();

            $disbursedLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $paidBackLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->count();

            $paidBackLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $commissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', true)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $commissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($commissions as $commission){
                $commissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            $zwlLoans = DB::table('loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zwlLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zwlLoans as $order){
                $zwlLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $zmkLoans = DB::table('zambia_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $zmkLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($zmkLoans as $order){
                $zmkLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $devLoans = DB::table('device_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $devLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($devLoans as $order){
                $devLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $usdLoans = DB::table('usd_loans')->select(
                DB::raw('COUNT(id) as count'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('deleted_at','=',null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $usdLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($usdLoans as $order){
                $usdLoansData[$order->monthKey-1] = number_format($order->count, 2,'.', '');
            }

            $mostFinancedData =[];
            $mostFinanced = DB::table('zambia_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinanced as $order){
                $mostFinancedData[$order->pcode] = $order->cnt;
            }

            $mostFinancedZimData =[];
            $mostFinancedZim = DB::table('device_loans as l')
                ->join('products as p','p.pcode','=','l.loan_product_id')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.loan_status','=', 8)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(10)
                ->get();

            foreach($mostFinancedZim as $order){
                $mostFinancedZimData[$order->pcode] = $order->cnt;
            }

            $loanChannelsData = [];

            $loanChannels = DB::table('loans')
                ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($loanChannels as $channel){
                $loanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $usdLoanChannelsData = [];

            $usdLoanChannels = DB::table('usd_loans')
                ->select(DB::raw('COUNT(id) as cnt'),'id','channel_id',DB::raw('(CASE WHEN channel_id = "www.eshagi.com" THEN "eshagi Website" WHEN loan_type = "WhatsApp Bot" THEN "WhatsApp Bot" ELSE 0 END) AS channel'))
                ->where('deleted_at','=', null)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('channel_id')
                ->orderBy('cnt','DESC')
                ->get();

            foreach($usdLoanChannels as $channel){
                $usdLoanChannelsData[$channel->channel_id] = $channel->cnt;
            }

            $merchantsCount = DB::table('partners')
                ->where('partner_type','=', 'Merchant')
                ->where('deleted_at','=',null)
                ->count();

            $agentsCount = DB::table('partners')
                ->where('partner_type','=', 'Agent')
                ->where('deleted_at','=',null)
                ->count();

            $zimLoansToday = Loan::whereDate('created_at', Carbon::today())->count();
            $zimUsdLoansToday = UsdLoan::whereDate('created_at', Carbon::today())->count();
            $zimMonthToDate= DB::table('loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimUsdMonthToDate= DB::table('usd_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zamLoansToday = ZambiaLoan::whereDate('created_at', Carbon::today())->count();
            $zamMonthToDate= DB::table('zambia_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();
            $zimDeviceLoansToday = DeviceLoan::whereDate('created_at', Carbon::today())->count();
            $zimDeviceLoansMonthDate= DB::table('device_loans')
                ->where('created_at', '>', Carbon::now()->startOfMonth())
                ->where('created_at', '<', Carbon::now()->endOfMonth())
                ->count();

            return view('pages.supervisor.home', compact('zimDeviceLoansMonthDate','zimDeviceLoansToday','zamMonthToDate','zamLoansToday','zimMonthToDate','zimUsdMonthToDate','zimUsdLoansToday','zimLoansToday','agentsCount','merchantsCount','loanChannelsData','usdLoanChannelsData','mostFinancedData','mostFinancedZimData','zwlLoansData','zmkLoansData','devLoansData','usdLoansData','cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans','newLoansValue','pendingLoansValue','disbursedLoansValue','paidBackLoansValue', 'commissionData'));
        }
        elseif ($user->hasRole('salesadmin')){
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_type','=',2)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_type','=',1)
                ->where('loan_status','=',12)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $cashLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];
            $creditLoansData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($cashloans as $order){
                $cashLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            foreach($creditloans as $order){
                $creditLoansData[$order->monthKey-1] = number_format($order->sums, 2,'.', '');
            }

            $newLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->count();

            $newLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(0, 1))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $pendingLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->count();

            $pendingLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('deleted_at','=',null)
                ->sum("amount");

            $disbursedLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->count();

            $disbursedLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 12)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $paidBackLoans = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->count();

            $paidBackLoansValue = DB::table('loans')
                ->where('locale','=',auth()->user()->locale)
                ->where('loan_status','=', 14)
                ->where('deleted_at','=',null)
                ->sum("amount");

            $commissions = DB::table('commissions')
                ->select(
                    DB::raw('sum(commission) as sums'),
                    DB::raw("DATE_FORMAT(created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
                )
                ->where('paidout','=', true)
                ->whereRaw('YEAR(created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('created_at', 'ASC')
                ->get();

            $commissionData = [0,0,0,0,0,0,0,0,0,0,0,0];

            foreach($commissions as $commission){
                $commissionData[$commission->monthKey-1] = number_format($commission->sums, 2,'.', '');
            }

            return view('pages.salesadmin.home', compact('cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans','newLoansValue','pendingLoansValue','disbursedLoansValue','paidBackLoansValue', 'commissionData'));
        }
        elseif ($user->isPartner()){
            $user = User::where('natid', auth()->user()->natid)->first();
            $partner = Partner::where('regNumber', auth()->user()->natid)->first();

            $pending = Loan::whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))->where('partner_id',$user->id)->count();
            $review = Loan::where('loan_status',0)->where('partner_id',$user->id)->count();
            $successful = Loan::where('loan_status',12)->where('partner_id',$user->id)->count();
            $declined = Loan::where('loan_status',13)->where('partner_id',$user->id)->count();
            $salesrep = Representative::where('partner_id',$user->id)->count();
            $products = Product::where('creator',$user->name)->count();

            //$loans = Loan::where('partner_id',$user->id)->get();
            $loans = DB::table('loans as l')
                ->join('clients as c','c.id','=','l.client_id')
                ->select('l.id','c.first_name', 'c.last_name', 'l.loan_type', 'l.amount', 'l.disbursed', 'l.loan_status', 'l.created_at')
                ->where('partner_id',$user->id)
                ->orderByDesc('l.id')
                ->get();

            $bestsellers = DB::table('loans as l')
                ->join('products as p','p.pcode','=','l.product')
                ->select(DB::raw('COUNT(p.id) as cnt'),'l.id','p.creator','p.pcode','p.serial','p.pname','p.model','p.descrip','p.price','l.loan_status')
                ->where('l.partner_id',$user->id)
                ->where('p.creator','=', $user->name)
                ->where('l.loan_status','=', 12)
                ->where('p.deleted_at','=', null)
                ->where('l.deleted_at','=', null)
                ->whereRaw('YEAR(l.created_at) = ?', date('Y'))
                ->groupBy('p.pcode')
                ->orderBy('cnt','DESC')
                ->take(5)
                ->get();

            $bestsellersData = [];

            foreach($bestsellers as $order){
                $bestsellersData[$order->pcode] = $order->cnt;
            }

            return view('pages.partner.home', compact('partner','user', 'pending', 'review', 'successful', 'declined', 'loans', 'salesrep','products', 'bestsellers', 'bestsellersData'));
        }
        elseif ($user->isRepresentative()){
            $rep = Representative::where('natid', auth()->user()->natid)->first();
            $user = User::where('name', $rep->creator)->first();
            $partner = Partner::where('regNumber', $user->natid)->first();

            $pending = DB::table('loans')
                ->whereIn('loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('partner_id','=',$user->id)
                ->where('deleted_at','=',null)
                ->count();

            $review = DB::table('loans')
                ->where('loan_status','=', 0)
                ->where('partner_id','=',$user->id)
                ->where('deleted_at','=',null)
                ->count();

            $successful = DB::table('loans')
                ->where('loan_status','=', 12)
                ->where('partner_id','=',$user->id)
                ->where('deleted_at','=',null)
                ->count();

            $declined = DB::table('loans')
                ->where('loan_status','=', 13)
                ->where('partner_id','=',$user->id)
                ->where('deleted_at','=',null)
                ->count();

            $loans = DB::table('loans as l')
                ->join('clients as c','c.id','=','l.client_id')
                ->select('l.id','c.first_name', 'c.last_name', 'l.loan_type', 'l.amount', 'l.disbursed', 'l.loan_status', 'l.created_at')
                ->where('partner_id',$user->id)
                ->orderByDesc('l.id')
                ->get();

            return view('pages.rep.home', compact('pending', 'review', 'successful', 'declined', 'loans'));
        }
        elseif ($user->isAgent() OR $user->hasRole('fielder')) {
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(loans.amount) as sums'),
                DB::raw("DATE_FORMAT(loans.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(loans.created_at,'%m') as monthKey")
            )
                ->join('clients as c', 'loans.client_id', '=', 'c.id')
                ->where('loans.loan_type', '=', 2)
                ->where('loans.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->whereRaw('YEAR(loans.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('loans.created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(loans.amount) as sums'),
                DB::raw("DATE_FORMAT(loans.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(loans.created_at,'%m') as monthKey")
            )
                ->join('clients as c', 'loans.client_id', '=', 'c.id')
                ->where('loans.loan_type', '=', 1)
                ->where('loans.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->whereRaw('YEAR(loans.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('loans.created_at', 'ASC')
                ->get();

            $cashLoansData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $creditLoansData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            foreach ($cashloans as $order) {
                $cashLoansData[$order->monthKey - 1] = number_format($order->sums, 2, '.', '');
            }

            foreach ($creditloans as $order) {
                $creditLoansData[$order->monthKey - 1] = number_format($order->sums, 2, '.', '');
            }

            $newLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(0, 1))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $newLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(0, 1))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            $pendingLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $pendingLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            $disbursedLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $disbursedLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            $paidBackLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 14)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $paidBackLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 14)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            if (auth()->user()->locale == 1){
                $yearlyLoans = DB::table('loans')->select(
                    DB::raw('sum(loans.amount) as sums'),
                    DB::raw("DATE_FORMAT(loans.created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(loans.created_at,'%m') as monthKey")
                )
                    ->where('loans.partner_id', '=', auth()->user()->id)
                    ->whereRaw('YEAR(loans.created_at) = ?', date('Y'))
                    ->groupBy('months', 'monthKey')
                    ->orderBy('loans.created_at', 'ASC')
                    ->get();

                $yearlyLoansData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

                foreach ($yearlyLoans as $order) {
                    $yearlyLoansData[$order->monthKey - 1] = number_format($order->sums, 2, '.', '');
                }
            } else {
                $yearlyLoans = DB::table('zambia_loans')->select(
                    DB::raw('sum(zambia_loans.loan_principal_amount) as sums'),
                    DB::raw("DATE_FORMAT(zambia_loans.created_at,'%M %Y') as months"),
                    DB::raw("DATE_FORMAT(zambia_loans.created_at,'%m') as monthKey")
                )
                    ->where('zambia_loans.partner_id', '=', auth()->user()->id)
                    ->whereRaw('YEAR(zambia_loans.created_at) = ?', date('Y'))
                    ->groupBy('months', 'monthKey')
                    ->orderBy('zambia_loans.created_at', 'ASC')
                    ->get();

                $yearlyLoansData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

                foreach ($yearlyLoans as $order) {
                    $yearlyLoansData[$order->monthKey - 1] = number_format($order->sums, 2, '.', '');
                }
            }


            return view('pages.user.home', compact('yearlyLoansData','cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans', 'newLoansValue', 'pendingLoansValue', 'disbursedLoansValue', 'paidBackLoansValue'));
        }
        elseif ($user->isAstrogent()) {
            $cashloans = DB::table('loans')->select(
                DB::raw('sum(loans.amount) as sums'),
                DB::raw("DATE_FORMAT(loans.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(loans.created_at,'%m') as monthKey")
            )
                ->join('clients as c', 'loans.client_id', '=', 'c.id')
                ->where('loans.loan_type', '=', 2)
                ->where('loans.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->whereRaw('YEAR(loans.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('loans.created_at', 'ASC')
                ->get();

            $creditloans = DB::table('loans')->select(
                DB::raw('sum(loans.amount) as sums'),
                DB::raw("DATE_FORMAT(loans.created_at,'%M %Y') as months"),
                DB::raw("DATE_FORMAT(loans.created_at,'%m') as monthKey")
            )
                ->join('clients as c', 'loans.client_id', '=', 'c.id')
                ->where('loans.loan_type', '=', 1)
                ->where('loans.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->whereRaw('YEAR(loans.created_at) = ?', date('Y'))
                ->groupBy('months', 'monthKey')
                ->orderBy('loans.created_at', 'ASC')
                ->get();

            $cashLoansData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $creditLoansData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            foreach ($cashloans as $order) {
                $cashLoansData[$order->monthKey - 1] = number_format($order->sums, 2, '.', '');
            }

            foreach ($creditloans as $order) {
                $creditLoansData[$order->monthKey - 1] = number_format($order->sums, 2, '.', '');
            }

            $newLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(0, 1))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $newLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(0, 1))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            $pendingLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $pendingLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->whereIn('l.loan_status', array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            $disbursedLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $disbursedLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 12)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            $paidBackLoans = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 14)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->count();

            $paidBackLoansValue = DB::table('loans as l')
                ->join('clients as c', 'l.client_id', '=', 'c.id')
                ->where('l.loan_status', '=', 14)
                ->where('c.creator', '=', auth()->user()->name)
                ->where('l.deleted_at', '=', null)
                ->sum("l.amount");

            return view('pages.astrogent.home', compact('cashLoansData', 'creditLoansData', 'pendingLoans', 'newLoans', 'disbursedLoans', 'paidBackLoans', 'newLoansValue', 'pendingLoansValue', 'disbursedLoansValue', 'paidBackLoansValue'));
        }
        elseif ($user->isRedsphere() ){

            $kycsPending = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
                ->where('k.cbz_status','=', '0')
                ->where('k.status', '=',true)
                ->where('l.locale','=',1)
                ->orderByDesc('l.created_at')
                ->distinct()
                ->count();

            $kycsCompleted = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
                ->where('k.cbz_status','=', '1')
                ->where('k.status', '=',true)
                ->where('l.locale','=',1)
                ->orderByDesc('l.created_at')
                ->distinct()
                ->count();

            $kycsRejected = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
                ->where('k.cbz_status','=', '2')
                ->where('k.status', '=',true)
                ->where('l.locale','=',1)
                ->orderByDesc('l.created_at')
                ->distinct()
                ->count();

            return view('pages.redsphere.home', compact('kycsPending', 'kycsCompleted', 'kycsRejected'));
        }
        elseif ($user->hasRole('womensbank') ){

            $kycsPending = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
                ->where('k.cbz_status','=', '0')
                ->where('k.status', '=',true)
                ->where('l.locale','=',1)
                ->where('c.flag','=','ZWMB')
                ->orderByDesc('l.created_at')
                ->distinct()
                ->count();

            $kycsCompleted = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
                ->where('k.cbz_status','=', '1')
                ->where('k.status', '=',true)
                ->where('l.locale','=',1)
                ->where('c.flag','=','ZWMB')
                ->orderByDesc('l.created_at')
                ->distinct()
                ->count();

            $kycsRejected = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
                ->where('k.cbz_status','=', '2')
                ->where('k.status', '=',true)
                ->where('l.locale','=',1)
                ->where('c.flag','=','ZWMB')
                ->orderByDesc('l.created_at')
                ->distinct()
                ->count();

            return view('pages.womensbank.home', compact('kycsPending', 'kycsCompleted', 'kycsRejected'));
        }
        else{

        if (auth()->user()->locale == 2){
            $score = 0;
            $status = 'ZMB';
            $scoreMeans = 'CRB Meaning';
            $yuza = User::where('natid', auth()->user()->natid)->first();
            $zambian = Zambian::where('nrc', $user->natid)->first();
            $devLoans = DeviceLoan::where('user_id','=', $yuza->id)->take(10)->orderBy('id', 'DESC')->get();
            $cashLoans = DB::table('zambia_loans as l')
                ->select('l.id','l.loan_status','l.loan_principal_amount','l.loan_duration','l.loan_interest','l.cf_11353_installment')
                ->where('l.user_id','=', $yuza->id)
                ->take(10)
                ->orderBy('l.id', 'DESC')
                ->get();


            return view('pages.client.zm-home', compact('zambian','user', 'score', 'status', 'scoreMeans','cashLoans','devLoans'));

        } else{
            $user = Client::where('natid', auth()->user()->natid)->first();
            $localels = Localel::find(auth()->user()->locale);
            if (is_null($user)) {
                $yuza = User::where('natid', auth()->user()->natid)->first();
                $salloans = Loan::where('user_id','=', $yuza->id)->where('loan_type','=', 2)->take(10)->orderBy('id', 'DESC')->get();
                $creloans = DB::table('loans as l')
                    ->join('partners as p', 'l.partner_id','=','p.id')
                    ->select('l.id','l.loan_status','l.amount','l.paybackPeriod','l.interestRate','l.monthly','p.partner_name')
                    ->where('l.user_id','=', $yuza->id)
                    ->where('l.loan_type','=', 1)
                    ->take(10)
                    ->orderBy('l.id', 'DESC')
                    ->get();
                $usdloans = DB::table('usd_loans as l')
                    ->join('partners as p', 'l.partner_id','=','p.id')
                    ->select('l.id','l.loan_status','l.amount','l.tenure','l.interestRate','l.monthly','p.partner_name')
                    ->where('l.user_id','=', $yuza->id)
                    ->take(10)
                    ->orderBy('l.id', 'DESC')
                    ->get();

                $devfinloans = DB::table('device_loans as l')
                    ->join('partners as p', 'l.partner_id','=','p.id')
                    ->select('l.id','l.loan_status','l.amount','l.paybackPeriod','l.interestRate','l.monthly','p.partner_name')
                    ->where('l.user_id','=', $yuza->id)
                    ->take(10)
                    ->orderBy('l.id', 'DESC')
                    ->get();

                return view('pages.client.home', compact('salloans', 'creloans','usdloans','devfinloans'));
            }
            elseif (is_null($user->fsb_score)) {
//                $dob = DATE_FORMAT($user->dob, 'd-m-Y');
//                $result = explode("-", $user->natid);
//                $idFormated = $result[0] . '-' . $result[1] . $result[2] . $result[3];
//                if ($user->gender == 'Male') {
//                    $gender = 'M';
//                } else {
//                    $gender = 'F';
//                }
//
//                if ($user->marital_state == 'Single') {
//                    $marital = 'S';
//                }
//                elseif ($user->marital_state == 'Married') {
//                    $marital = 'M';
//                }
//                elseif ($user->marital_state == 'Widowed') {
//                    $marital = 'W';
//                }
//                elseif ($user->marital_state == 'Divorced') {
//                    $marital = 'D';
//                }
//
//                $ownership = '';
//                if ($user->home_type == 'Owned') {
//                    $ownership = '1';
//                } elseif ($user->home_type == 'Rented') {
//                    $ownership = '2';
//                } elseif ($user->home_type == 'Mortgaged') {
//                    $ownership = '3';
//                } elseif ($user->home_type == 'Parents') {
//                    $ownership = '4';
//                } elseif ($user->home_type == 'Employer Owned') {
//                    $ownership = '5';
//                }
//
//                $details = Http::asForm()->post('https://www.fcbureau.co.zw/api/newIndividual',[
//                        'dob' => $dob,
//                        'names' => $user->first_name,
//                        'surname' => $user->last_name,
//                        'national_id' => $idFormated,
//                        'gender' => $gender,
//                        'search_purpose' => 1,
//                        'email' => getFcbUsername(),
//                        'password' => getFcbPassword(),
//                        'drivers_licence' => 'NA',
//                        'passport' => 'NA',
//                        'married' => $marital,
//                        'nationality' => 3,
//                        'streetno' => $user->house_num,
//                        'streetname' => $user->street,
//                        'building' => 'NA',
//                        'surbub' => $user->surburb,
//                        'pbag' => '',
//                        'city' => $user->city,
//                        'telephone' => '',
//                        'mobile' => $user->mobile,
//                        'ind_email' => $user->email,
//                        'property_density' => 2,
//                        'property_status' => $ownership,
//                        'occupation_class' => 0,
//                        'employer' => $user->employer,
//                        'employer_industry' => 0,
//                        'salary_band' => 8,
//                        'loan_purpose' => 14,
//                        'loan_amount' => 0,
//                    ]
//                )
//                    ->body();
//
//                $json = json_decode($details, true);
//                $code = $json['code'];
//
//                if ($code == 206) {
//                    dd('There is some missing info: ' . $json['missing information']);
//                } elseif ($code == 401) {
//                    dd('Authorization error: ' . $json['error'] . '. Please check account status with FCB.');
//                } elseif ($code == 200) {
//                    $status = $json['searches'][0]['status'];
//                    $score = $json['searches'][0]['score'];
//
//                    if ($score >= 0 && $score <= 200) {
//                        $scoreMeans = 'Extremely High Risk';
//                    } elseif ($score >= 201 && $score <= 250) {
//                        $scoreMeans = 'High Risk';
//                    } elseif ($score >= 251 && $score <= 300) {
//                        $scoreMeans = 'Medium to High Risk';
//                    } elseif ($score >= 301 && $score <= 350) {
//                        $scoreMeans = 'Medium to Low Risk';
//                    } else {
//                        $scoreMeans = 'Low Risk'; //($score >= 351 && $score <= 400)
//                    }
                $score = 0;
                $status = 'FAIR';
                $scoreMeans = 'Extremely High Risk';
                    DB::table("clients")
                        ->where("natid", auth()->user()->natid)
                        ->update(['fsb_score' => $score, 'fsb_status' => $status, 'fsb_rating' => $scoreMeans, 'updated_at' => now()]);

//                    Http::post(getBulkSmsUrl() . "to=+263" . $user->mobile . "&msg=Great News " . $user->first_name . ", You qualify for a loan or store credit of up to $" . $user->cred_limit . " with eShagi subject to financial approval. Login and complete the application.")
//                        ->body();
//                }



            } else{
                $score = $user->fsb_score;
                $status = $user->fsb_status;
                $scoreMeans = $user->fsb_rating;
            }

            if ($status == 'GOOD') {
                $message = 'Clean History.';
            } elseif ($status == 'GREEN') {
                $message = 'No history with FCB.';
            } elseif ($status == 'ADVERSE') {
                $message = 'Open Adverse item(s).';
            } elseif ($status == 'PEP') {
                $message = 'Politically Exposed.';
            } elseif ($status == 'FAIR') {
                $message = 'Prior adverse items.';
            } else {
                $message = "No Records at FCB, you may have to verify National ID.";
            }

            $yuza = User::where('natid', $user->natid)->first();

            $salloans = Loan::where('user_id','=', $yuza->id)->where('loan_type','=', 2)->take(10)->orderBy('id', 'DESC')->get();
            $creloans = DB::table('loans as l')
                ->join('partners as p', 'l.partner_id','=','p.id')
                ->select('l.id','l.loan_status','l.amount','l.paybackPeriod','l.interestRate','l.monthly','p.partner_name')
                ->where('l.user_id','=', $yuza->id)
                ->where('l.loan_type','=', 1)
                ->take(10)
                ->orderBy('l.id', 'DESC')
                ->get();

            $usdloans = DB::table('usd_loans as l')
                ->join('partners as p', 'l.partner_id','=','p.id')
                ->select('l.id','l.loan_status','l.amount','l.tenure','l.interestRate','l.monthly','p.partner_name')
                ->where('l.user_id','=', $yuza->id)
                ->take(10)
                ->orderBy('l.id', 'DESC')
                ->get();

            $devfinloans = DB::table('device_loans as l')
                ->join('partners as p', 'l.partner_id','=','p.id')
                ->select('l.id','l.loan_status','l.amount','l.paybackPeriod','l.interestRate','l.monthly','p.partner_name')
                ->where('l.user_id','=', $yuza->id)
                ->take(10)
                ->orderBy('l.id', 'DESC')
                ->get();

            /*$smsnotify = Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263773418009&msg=Great News ".$user->first_name." You qualify for a loan or store credit of up to $".$user->cred_limit." with eShagi subject to financial approval. Login and complete the application.")
                ->body();*/
                return view('pages.client.home', compact('user', 'score', 'status', 'scoreMeans', 'message', 'salloans', 'creloans', 'localels','usdloans','devfinloans'));

        }


        }

        return view('pages.client.home');
    }
}
