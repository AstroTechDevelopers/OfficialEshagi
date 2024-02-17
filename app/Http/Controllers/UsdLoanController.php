<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Kyc;
use App\Models\Partner;
use App\Models\Representative;
use App\Models\UsdLoan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsdLoanController extends Controller
{
    public function __construct() {
        $this->middleware('backend', [
            'only' => ['index']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ((auth()->user()->hasRole('root') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('group') )) {
            $loans = DB::table('usd_loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.deleted_at','=', null)
                ->get();
        } else {
            $loans = DB::table('usd_loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.locale','=', auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->get();
        }

        return view('usdloans.usdloans', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Client::where('natid', auth()->user()->natid)->first();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        if (is_null($user)) {
            return redirect('quickly-continue');
        } elseif(is_null($yuser)){
            return redirect('remaining-details');
        } elseif ($yuser->national_pic == null OR $yuser->passport_pic == null OR $yuser->payslip_pic== null) {
            return view('clients.register-three', compact('yuser'));
        }
        $bank = Bank::where('id', $yuser->bank)->first();

        return view('usdloans.apply-usd-loan', compact('user', 'bank','yuser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->input('amount') > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        $request->merge([
            'client_id' => $client->id,
        ]);

        $checkLoan = UsdLoan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('tenure','=',$request->input('tenure'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        if ($client->emp_sector == 'Government') {
            if ($client->reds_number != null AND $yuser->sign_stat == true) {
                $state = 2;
            } else{
                $state = 1;
            }
        } else {
            $state = 1;
        }

        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required',
                'client_id'            => 'required',
                'channel_id'                 => 'required',
                'loan_type'                 => 'required',
                'amount'              => 'required',
                'gross_amount'              => 'required',
                'tenure'         => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'ags_commission'                  => 'required',
                'app_fee'                  => 'required',
                'est_fee'                  => 'required',
                'insurance'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = UsdLoan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => $request->input('partner_id'),
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'gross_amount'         => $request->input('gross_amount'),
            'tenure'            => $request->input('tenure'),
            'interestRate' => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'ags_commission'        => $request->input('ags_commission'),
            'app_fee'        => $request->input('app_fee'),
            'est_fee'        => $request->input('est_fee'),
            'insurance'        => $request->input('insurance'),
            'charges'        => $request->input('insurance')+$request->input('est_fee')+$request->input('app_fee')+$request->input('ags_commission'),
            'notes'        => $request->input('notes'),
            'locale'        => auth()->user()->locale,
        ]);

        $loan->save();

        return redirect('my-usd-loans')->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UsdLoan  $usdLoan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = UsdLoan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $partner = User::where('id', $loan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();
        if (!is_null($agent)){
            if ($agent->utype == 'Representative'){
                $repInfo = Representative::where('natid', $agent->natid)->first();
                $merchant = Partner::where('id',$repInfo->partner_id)->first();
            } else {
                $repInfo = null;
                $merchant = null;
            }
        } else {
            $repInfo = null;
            $merchant = null;
        }

        return view('usdloans.usd-loan-info', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UsdLoan  $usdLoan
     * @return \Illuminate\Http\Response
     */
    public function edit(UsdLoan $usdLoan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsdLoan  $usdLoan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsdLoan $usdLoan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsdLoan  $usdLoan
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsdLoan $usdLoan)
    {
        //
    }

    public function getPartnerUsdLoans(){
        $partner = User::where('id', auth()->user()->id)->first();

        /*if (auth()->user()->hasRole('representative')){
            $rep = Representative::where('natid', auth()->user()->natid)->first();
            $partner = User::where('name', $rep->creator)->first();
        }*/

        $loans = DB::table('usd_loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.first_name','c.last_name','l.loan_type','l.amount','l.gross_amount','l.loan_status','l.created_at')
            ->where('l.partner_id','=', $partner->id)
            ->get();

        return view('usdloans.partner-usd-loans', compact('loans', 'partner'));

    }

    public function usdLoanCalculator(){
        return view('usdloans.usd-loan-calculator');
    }

    public function newPartnerUsdLoan(){
        $partner = User::where('id', auth()->user()->id)->first();

        $clients = DB::table('clients as c')
            ->join('kycs as k', 'k.natid', '=', 'c.natid')
            ->join('users as u', 'u.natid','=','k.natid')
            ->join('banks as b', 'b.id','=','k.bank')
            ->select('u.id as user_id','c.id','c.first_name','c.last_name','c.cred_limit','c.natid','b.bank','k.branch_code','k.branch','k.acc_number','u.locale')
            ->where('k.bank','!=',null)
            ->where('c.creator','=',auth()->user()->name)
            ->orWhere('c.creator','=','Self')
            ->where('c.locale_id','=',auth()->user()->locale)
            ->get();

        return view('usdloans.new-partner-usloan', compact('clients', 'partner'));
    }

    public function postPartnerUsdLoan(Request $request){
        if ($request->input('amount') > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $checkLoan = UsdLoan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('tenure','=',$request->input('tenure'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $client = DB::table('clients as c')
            ->join('users as u', 'u.natid','=','c.natid')
            ->select('u.id', 'c.emp_sector', 'c.reds_number', 'c.natid')
            ->where('c.id','=',$request->input('client_id'))
            ->first();
        $yuser = Kyc::where('natid', $client->natid)->first();

        if ($client->emp_sector == 'Government') {
            if ($client->reds_number != null AND $yuser->sign_stat == true) {
                $state = 2;
            } else{
                $state = 1;
            }
        } else {
            $state = 1;
        }

        $request->merge([
            'user_id' => $client->id,
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required',
                'client_id'            => 'required',
                'channel_id'                 => 'required',
                'loan_type'                 => 'required',
                'amount'              => 'required',
                'gross_amount'              => 'required',
                'tenure'         => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'ags_commission'                  => 'required',
                'app_fee'                  => 'required',
                'est_fee'                  => 'required',
                'insurance'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = UsdLoan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'gross_amount'         => $request->input('gross_amount'),
            'tenure'            => $request->input('tenure'),
            'interestRate' => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'ags_commission'        => $request->input('ags_commission'),
            'app_fee'        => $request->input('app_fee'),
            'est_fee'        => $request->input('est_fee'),
            'insurance'        => $request->input('insurance'),
            'charges'        => $request->input('insurance')+$request->input('est_fee')+$request->input('app_fee')+$request->input('ags_commission'),
            'notes'        => $request->input('notes'),
            'locale'        => auth()->user()->locale,
        ]);

        $loan->save();

        return redirect('partner-usd-loans')->with('success', 'Loan created successfully.');

    }

    public function getMyUsdLoans(){
        $loans = UsdLoan::where('user_id','=', auth()->user()->id)->get();

        $monthlies = $loans->sum("monthly");

        return view('usdloans.my-usloans', compact('loans', 'monthlies'));
    }

    public function getNewUsdLoans(){
        $loans = DB::table('usd_loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('c.reds_number','=', null)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('usdloans.new-usd-loans', compact('loans'));
    }
}
