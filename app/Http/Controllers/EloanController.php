<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Eloan;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\Masetting;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EloanController extends Controller
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
            $loans = DB::table('eloans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.deleted_at','=', null)
                ->get();
        } else {
            $loans = DB::table('eloans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.locale','=', auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->get();
        }

        return view('eloans.eloans', compact('loans'));
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

        return view('eloans.apply-eloan', compact('user', 'bank','yuser'));
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
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above your credit limit.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        $request->merge([
            'client_id' => $client->id,
            'employer_id' => $client->employer_id,
        ]);

        $checkLoan = Eloan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('tenure','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',6)
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required',
                'client_id'            => 'required',
                'partner_id'             => 'nullable',
                'channel_id'                 => 'required',
                'loan_type'                 => 'required',
                'amount'              => 'required',
                'tenure'             => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'disbursed'                  => 'required',
                'appFee'                  => 'required',
                'charges'                  => 'required',
            ],
            [
                'user_id.required'       => 'Please make sure you\'re logged in.',
                'client_id.required' => 'Please make sure you\'re logged in.',
                'channel_id.required'  => 'How was this loan applied?',
                'loan_type.required'      => 'Please advise the type of loan',
                'amount.required'         => 'The amount for the loan is needed.',
                'tenure.required'         => 'How long are you planning on paying back this loan?',
                'interestRate.required'         => 'What is the proposed loan rate?.',
                'monthly.required'         => 'What are the proposed loan repayment amounts?',
                'disbursed.required'         => 'What is the proposed amount to be received by you?',
                'appFee.required'   => 'What is the application fee?',
                'charges.required'       => 'What are the charges that come with processing this loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eloan = Eloan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => $request->input('partner_id'),
            'employer_id'        => $request->input('employer_id'),
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => 0,
            'amount'         => $request->input('amount'),
            'tenure'            => $request->input('paybackPeriod'),
            'interestRate'      => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            'appFee'        => $request->input('appFee'),
            'charges'        => $request->input('charges'),
            'notes'        => $request->input('prod_descrip'),
            'product'        => $request->input('prod_descrip'),
            'pprice'        => $request->input('amount'),
            'locale'        => auth()->user()->locale,
        ]);
        $eloan->save();

        if ($eloan->save()) {
            $newlimit = $client->cred_limit-$request->input('amount');
            DB::table('clients')
                ->where('id',$request->input('client_id'))
                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }
        return redirect('sign-eloan/'.$eloan->id.'/'.$yuser->id)->with('success', 'Please sign your loan to begin processing it.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloan  $eloan
     * @return \Illuminate\Http\Response
     */
    public function show(Eloan $eloan)
    {
        $client = Client::where('id', $eloan->client_id)->first();
        $partner = User::where('id', $eloan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();
        if (is_null($agent)){
            $agent = null;
            $repInfo = null;
            $merchant = null;
        } elseif ($agent->utype == 'Representative'){
            $repInfo = Representative::where('natid', $agent->natid)->first();
            $merchant = Partner::where('id',$repInfo->partner_id)->first();
        } else {
            $agent = null;
            $repInfo = null;
            $merchant = null;
        }

        return view('eloans.loan-info', compact('eloan', 'client', 'partner','agent', 'repInfo', 'merchant'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloan  $eloan
     * @return \Illuminate\Http\Response
     */
    public function edit(Eloan $eloan)
    {
        $user = Client::where('id', $eloan->client_id)->first();
        $yuser = Kyc::where('natid', $user->natid)->first();

        if (is_null($user)) {
            return redirect()->back()->with('error', 'Client was not fully onboarded onto eShagi, they should finish all registration steps.');
        } elseif(is_null($yuser)){
            return redirect()->back()->with('error', 'Client was not fully onboarded onto eShagi, they should finish all registration steps.');
        } elseif ($yuser->national_pic == null OR $yuser->passport_pic == null OR $yuser->payslip_pic== null) {
            return redirect()->back()->with('error', 'Client was not fully onboarded onto eShagi, they should finish all registration steps.');
        }

        $bank = Bank::where('id', $yuser->bank)->first();
        $products = Product::all();
        $sysuser = User::where('natid', $user->natid)->first();

        return view('eloans.edit-eloan', compact('eloan','user','yuser','bank','products','sysuser'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eloan  $eloan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eloan $eloan)
    {
        $client = Client::findOrFail($eloan->client_id);
        if ($eloan->loan_status >= 5){
            return redirect()->back()->with('error', 'You cannot edit a loan after it has been authorized.');
        }

        if ($eloan->loan_status >= 8){
            return redirect()->back()->with('error', 'You cannot edit a loan after it has been disbursed.');
        }

        if (number_format($request->input('amount'),2,'.','') > number_format(getCreditRate()*$client->salary,2,'.','')){
            return redirect()->back()->with('error', 'You cannot edit a loan to an amount that is greater than the permitted credit limit.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required',
                'client_id'            => 'required',
                'partner_id'             => 'nullable',
                'loan_type'                 => 'required',
                'amount'              => 'required',
                'tenure'             => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'disbursed'                  => 'required',
                'appFee'                  => 'required',
                'charges'                  => 'required',
            ],
            [
                'user_id.required'       => 'Please make sure you\'re logged in.',
                'client_id.required' => 'Please make sure you\'re logged in.',
                'loan_type.required'      => 'Please advise the type of loan',
                'amount.required'         => 'The amount for the loan is needed.',
                'tenure.required'         => 'How long are you planning on paying back this loan?',
                'interestRate.required'         => 'What is the proposed loan rate?.',
                'monthly.required'         => 'What are the proposed loan repayment amounts?',
                'disbursed.required'         => 'What is the proposed amount to be received by you?',
                'appFee.required'   => 'What is the application fee?',
                'charges.required'       => 'What are the charges that come with processing this loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eloan->loan_type = $request->input('loan_type');
        $eloan->amount = $request->input('amount');
        $eloan->tenure = $request->input('tenure');
        $eloan->interestRate = $request->input('interestRate');
        $eloan->monthly = $request->input('monthly');
        $eloan->disbursed = $request->input('disbursed');
        $eloan->appFee = $request->input('appFee');
        $eloan->charges = $request->input('charges');
        $eloan->product = $request->input('product');
        $eloan->pprice = $request->input('pprice');
        $eloan->install_date = $request->input('install_date');
        $eloan->maturity_date = $request->input('maturity_date');
        $eloan->isDisbursed = $request->input('isDisbursed');
        $eloan->notes = $request->input('notes');

        $eloan->save();

        if ($eloan->save()) {
            $newlimit = $client->cred_limit-$request->input('amount');
            DB::table('clients')
                ->where('id',$client->id)
                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'eLoan details have been updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloan  $eloan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eloan $eloan)
    {
        if ($eloan->loan_status > 2){
            return redirect()->back()->with('error', 'You cannot delete a loan that has been actioned.');
        } else{
            $eloan->delete();
        }

        return redirect('loans')->with('success', 'eLoan deleted successfully.');
    }

    public function getSignature($loanId, $kycInfo){
        $eloan = Eloan::findOrFail($loanId);
        $yuser = Kyc::findOrFail($kycInfo);

        return view('eloans.sign-my-eloan', compact('eloan','yuser'));
    }

    public function getClientSignature($loanId, $kycInfo){
        $eloan = Eloan::findOrFail($loanId);
        $yuser = Kyc::findOrFail($kycInfo);
        return view('eloans.esign-for-client', compact('eloan','yuser'));
    }

    public function completeLoan(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eloan = Eloan::where('id',$request->input('loan_id'))->first();

        $eloan->loan_status = 1;
        $eloan->save();

        return redirect(route('list.myeloans'))->with('success','Your loan application has been submitted for processing.');
    }

    public function completeLoanForClient(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
                'otp'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                'otp.required' => 'OTP from client is need as consent from client to apply for the loan.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eloan = Eloan::where('id',$request->input('loan_id'))->first();
        $client = Client::where('id', $eloan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        if (Hash::check($request->input('otp'), $client->otp)) {
            if ($client->emp_sector == 'Government') {
                if ($client->reds_number != null AND $kyc->sign_stat) {
                    $eloan->loan_status = 8;
                } else{
                    $eloan->loan_status = 1;
                }
            } else {
                $eloan->loan_status = 1;
            }
            $eloan->save();
        } else {
            $yuser = Kyc::where('natid', $client->natid)->first();

            return view('loans.sign-for-client', compact('eloan', 'yuser'))->with('error', 'Invalid OTP provided, please check your provided OTP.');
        }

        return redirect(route('partner.loans'))->with('success','Your loan application has been submitted for processing.');
    }

    public function loanCalculator(){
        return view('eloans.eloan-calculate');
    }

    public function getLoanAmortizationSchedule(){
        return view('eloans.eloan-amortization');
    }

    public function unSignedLoans() {
        if ((auth()->user()->hasRole('root') ||auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('group') || auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('salesadmin') )) {
            $loans = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
                ->where('l.loan_status','=',0)
                ->where('l.locale','=',auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->orderByDesc('l.created_at')
                ->get();
        } else {
            $loans = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
                ->where('l.loan_status','=',0)
                ->where('c.creator','=',auth()->user()->name)
                ->where('l.locale','=',auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->orderByDesc('l.created_at')
                ->get();
        }

        return view('eloans.eloan-unsigned', compact('loans'));
    }

    public function getClientUnsignedSignature($loanId, $kycInfo){
        $eloan = Eloan::findOrFail($loanId);
        $yuser = Kyc::findOrFail($kycInfo);

        return view('eloans.sign-client-eloan', compact('eloan','yuser'));
    }

    public function completeELoan(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eloan = Eloan::where('id',$request->input('loan_id'))->first();
        $client = Client::where('id', $eloan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        if ($kyc->sign_stat) {
            $eloan->loan_status = 1;
        } else {
            $data = [
                'eloan' => $eloan,
                'yuser' => $client,
                'error' => 'Loan signature must be uploaded first.'
            ];
            return redirect()->back()->with($data);
        }
        $eloan->save();

        return redirect(route('list.myeloans'))->with('success','Your loan application has been submitted for processing.');
    }

    public function completeELoanForClient(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
                'otp'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                'otp.required' => 'OTP from client is need as consent from client to apply for the loan.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eloan = Loan::where('id',$request->input('loan_id'))->first();
        $client = Client::where('id', $eloan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        if (Hash::check($request->input('otp'), $client->otp)) {
            if ($kyc->sign_stat) {
                $eloan->loan_status = 1;
            } else {
                return view('eloans.sign-client-eloan', compact('eloan', 'yuser'))->with('error', 'Loan signature must be uploaded first.');
            }
            $eloan->save();
        } else {
            $yuser = Kyc::where('natid', $client->natid)->first();

            return view('eloans.sign-client-eloan', compact('eloan', 'yuser'))->with('error', 'Invalid OTP provided, please check your provided OTP.');
        }

        return redirect(route('partner.eloans'))->with('success','Your loan application has been submitted for processing.');

    }

    public function newELoans(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('c.reds_number','=', null)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('eloans.new-eloans', compact('loans'));
    }

    public function pendingELoans(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',3)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('k.status','=', true)
            ->where('c.reds_number','!=', null)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('eloans.pending-eloans', compact('loans'));
    }

    public function authorizedELoans(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',5)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('eloans.authorized-eloans', compact('loans'));
    }

    public function pendingEDisbursement(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.disbursed','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',7)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('eloans.awaiting-edisburse', compact('loans'));
    }

    public function disbursedELoans(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',8)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('eloans.disbursed-eloans', compact('loans'));
    }

    public function declinedELoans() {
        $loans = DB::table('eloans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_status','=',6)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('eloans.declined-eloans', compact('loans'));
    }

    public function eLoansPaidInFull() {
        $loans = DB::table('eloans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_status','=',10)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('eloans.cleared-eloans', compact('loans'));
    }

    public function getELoanRecords(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.creator','c.first_name','c.last_name','c.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.locale','=',auth()->user()->locale)
            ->get();

        return view('eloans.eloan-records', compact('loans'));
    }

    public function eLoanSettleForm(){
        $loans = DB::table('eloans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type','l.notes')
            ->where('l.loan_status','=',9)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('eloans.settle-eloans', compact('loans'));
    }

    public function settleOffELoan($id){
        $eloan = Eloan::findOrFail($id);
        $client = Client::find($eloan->client_id);

        DB::table("eloans")
            ->where("id", $eloan->id)
            ->update(['loan_status' => 10, 'updated_at' => now()]);

        DB::table("clients")
            ->where("id", $client->id)
            ->update(['cred_limit' => number_format(getCreditRate()*$client->salary,2,'.',''), 'updated_at' => now()]);

        DB::table("creditlimits")
            ->where("client_id", $client->id)
            ->update(['creditlimit' => number_format(getCreditRate()*$client->salary,2,'.',''), 'updated_at' => now()]);

        Http::post(getBulkSmsUrl() . "to=+263".$client->mobile."&msg=Hello ".$client->first_name.". Your loan for ZWL$".$eloan->amount." has been successfully settled off. eShagi.")
            ->body();

        return redirect()->back()->with('success', 'eLoan has been settled off successfully.');
    }

    public function getMyEloans(){
        $loans = Eloan::where('user_id','=', auth()->user()->id)->get();

        $monthlies = $loans->sum("monthly");

        return view('eloans.my-eloans', compact('loans', 'monthlies'));
    }

    function uploadSignature(Request $request) {

        $validator = Validator::make(
            $request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'loan_id'  => 'required',
            ],
            [
                'signature.required'       => 'Your signature picture is required.',
                'signature.max'                 => 'Signature should not be greater than 4MB.',
                'signature.mimes'               => 'Signature should of the format: jpeg,png,jpg,gif,svg.',
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {

                $signature = $request->file('signature');
                $filename = auth()->user()->natid . '.' . $signature->getClientOriginalExtension();
                Storage::disk('public')->put('signatures/' . $filename, File::get($signature));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->sign_id = auth()->user()->natid;
                $user->sign_pic = $filename;
                $user->sign_stat = true;
                $user->updated_at = now();

                $user->save();

                if ($user->save()) {
                    $loan = Eloan::where('id', $request->input('loan_id'))->first();

                    $loan->loan_status = 1;
                    $loan->save();
                }
            } else {
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect(route('list.myeloans'))->with('success','Your loan application has been submitted for processing.');
    }

    function uploadClientESignature(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'loan_id'  => 'required',
                'otp'  => 'required',
            ],
            [
                'signature.required'       => 'Your signature picture is required.',
                'signature.max'                 => 'Signature should not be greater than 4MB.',
                'signature.mimes'               => 'Signature should of the format: jpeg,png,jpg,gif,svg.',
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                'otp.required' => 'OTP from client is need as consent from client to apply for the loan.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {
                $loan = Eloan::where('id', $request->input('loan_id'))->first();
                $client = Client::where('id', $loan->client_id)->first();
                if (Hash::check($request->input('otp'), $client->otp)) {

                    $signature = $request->file('signature');
                    $filename = $request->input('client_id') . '.' . $signature->getClientOriginalExtension();
                    Storage::disk('public')->put('signatures/' . $filename, File::get($signature));

                    $user = Kyc::where('natid', $request->input('client_id'))->first();

                    $user->sign_id = $request->input('client_id');
                    $user->sign_pic = $filename;
                    $user->sign_stat = true;
                    $user->updated_at = now();

                    $user->save();

                    if ($user->save()) {
                        $loan->loan_status = 1;
                        $loan->save();
                    }
                } else {
                    $yuser = Kyc::where('natid', $client->natid)->first();

                    return view('eloans.esign-for-client', compact('loan', 'yuser'))->with('error', 'Invalid OTP provided, please check your provided OTP.');

                }
            } else {
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect(route('partner.loans'))->with('success','Loan application has been submitted for processing.');
    }

    public function eloanInfoSignature ($id) {
        $eloan =Eloan::findOrFail($id);
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        return view('eloans.sign-my-eloan', compact('eloan', 'yuser'));
    }

    public function pushForEDisbursement($id){
        $eloan = Eloan::findOrFail($id);
        $eloan->loan_status = 7;
        $eloan->updated_at = now();
        $eloan->save();
        return redirect()->back()->with('success','eLoan pushed for disbursement');
    }

    public function pushAllEDisbursement(){
        $loans = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',5)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        foreach ($loans as $loan) {
            DB::table('eloans')
                ->where('id','=', $loan->id)
                ->update(['loan_status' => 7,'updated_at' => now()]);
        }

        return redirect()->back()->with('success','eLoans pushed for disbursement.');
    }

    public function authorizeEdisbursement($id){
        $eloan = Eloan::findOrFail($id);
        $client = Client::where('id', $eloan->client_id)->first();
        $otp = mt_rand(10000000, 99999999);
        $getOtp = Http::post(getBulkSmsUrl() . "to=+263" . auth()->user()->mobile . "&msg=Hie " . auth()->user()->first_name . ", The OTP for eLoan Disbursement is " . $otp . " with the following details. Loan ID: ".$id.", for ".$client->first_name." ".$client->last_name.".")
            ->body();

        $json = json_decode($getOtp, true);
        $status = $json['data'][0]['status'];
        if ($status == 'OK') {
            DB::table("users")
                ->where("id", auth()->user()->id)
                ->update(['google2fa_secret' => Hash::make($otp), 'updated_at' => now()]);
        }

        return view('eloans.authenticate-disburse', compact('eloan', 'client'));
    }

    public function disburseLoan(Request $request){
        if (Hash::check($request->input('otp'), auth()->user()->google2fa_secret)) {
            DB::table('eloans')
                ->where('id','=', $request->input('loan_id'))
                ->update(['loan_status' => 8,'updated_at' => now()]);

            DB::table("users")
                ->where("id", auth()->user()->id)
                ->update(['google2fa_secret' => null, 'updated_at' => now()]);

            dd('Loan is disbursed but currently, No Bank API is provided so far.');
        } else {
            $data = [
                'id' => $request->input('loan_id'),
                'error' => 'Incorrect OTP provided. Another one has been generated.'
            ];
            return redirect(route('auth.money.out'))->with($data);
        }

    }

    public function createCreditEloan(){
        $user = Client::where('natid', auth()->user()->natid)->first();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        if(is_null($yuser)){
            return redirect('remaining-details');
        } elseif ($yuser->national_pic == null OR $yuser->passport_pic == null OR $yuser->payslip_pic== null) {
            return view('clients.register-three', compact('yuser'));
        } else {
            $userInfo = DB::table('kycs as k')
                ->join('banks as b', 'b.id','=','k.bank')
                ->select('b.bank','k.branch','k.branch_code','k.acc_number')
                ->where('k.bank','=',$yuser->bank)
                ->first();
        }

        $partners = Partner::where('partner_type', 'Merchant')->get();
        $products = Product::all();

        return view('eloans.apply-estore-credit', compact('user', 'userInfo', 'partners', 'products'));
    }

    public function createEhybridLoan(){
        $user = Client::where('natid', auth()->user()->natid)->first();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        if(is_null($yuser)){
            return redirect('remaining-details');
        } elseif ($yuser->national_pic == null OR $yuser->passport_pic == null OR $yuser->payslip_pic== null) {
            return view('clients.register-three', compact('yuser'));
        } else {
            $userInfo = DB::table('kycs as k')
                ->join('banks as b', 'b.id','=','k.bank')
                ->select('b.bank','k.branch','k.branch_code','k.acc_number')
                ->where('k.bank','=',$yuser->bank)
                ->first();
        }

        $partners = Partner::where('partner_type', 'Merchant')->get();
        $products = Product::all();

        return view('eloans.apply-ehybrid', compact('user', 'userInfo', 'partners', 'products'));

    }

    public function storeMyHybridLoan(Request $request){
        if (($request->input('amount') + $request->input('cashamount')) > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - ($request->input('amount') + $request->input('cashamount'))) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        $request->merge([
            'client_id' => $client->id,
            'employer_id' => $client->employer_id,
            'user_id' => auth()->user()->id,
        ]);

        $checkLoan = Eloan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('tenure','=',$request->input('tenure'))
            ->where('loan_status','!=',6)
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required',
                'client_id'            => 'required',
                'channel_id'                 => 'required',
                'loan_type'                 => 'required',
                'amount'              => 'required',
                'prod_descrip'              => 'required',
                'tenure'         => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'disbursed'                  => 'required',
                'appFee'                  => 'required',
                'charges'                  => 'required',
            ],
            [
                'user_id.required'       => 'Please make sure you\'re logged in.',
                'client_id.required' => 'Please make sure you\'re logged in.',
                'channel_id.required'  => 'How was this loan applied?',
                'loan_type.required'      => 'Please advise the type of loan',
                'amount.required'         => 'The amount for the loan is needed.',
                'prod_descrip.required'         => 'What is this loan used to purchase?',
                'tenure.required'         => 'How long are you planning on paying back this loan?',
                'interestRate.required'         => 'What is the proposed loan rate?.',
                'monthly.required'         => 'What are the proposed loan repayment amounts?',
                'disbursed.required'         => 'What is the proposed amount to be received by you?',
                'appFee.required'   => 'What is the application fee?',
                'charges.required'       => 'What are the charges that come with processing this loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $total = $request->input('amount') + $request->input('cashamount');

        $eloan = Eloan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => 0,
            'amount'         => $total,
            'tenure'   => $request->input('tenure'),
            'interestRate'  => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            'appFee'        => $request->input('appFee'),
            'charges'        => $request->input('charges'),
            'notes'        => $request->input('prod_descrip'),
            'product'        => $request->input('prod_descrip'),
            'pprice'        => $request->input('amount'),
            'locale'        => auth()->user()->locale,
        ]);

        $eloan->save();

        if ($eloan->save()) {
            $newlimit = $client->cred_limit-$total;
            DB::table('clients')
                ->where('id',$request->input('client_id'))
                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }
        return redirect('sign-eloan/'.$eloan->id.'/'.$yuser->id)->with('success', 'Please sign your loan to begin processing it.');

    }

    public function createEBusinessLoan(){
        $user = Client::where('natid', auth()->user()->natid)->first();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        if(is_null($yuser)){
            return redirect('remaining-details');
        } elseif ($yuser->national_pic == null OR $yuser->passport_pic == null OR $yuser->payslip_pic== null) {
            return view('clients.register-three', compact('yuser'));
        } else {
            $userInfo = DB::table('kycs as k')
                ->join('banks as b', 'b.id','=','k.bank')
                ->select('b.bank','k.branch','k.branch_code','k.acc_number')
                ->where('k.bank','=',$yuser->bank)
                ->first();
        }

        $partners = Partner::where('partner_type', 'Merchant')->get();
        $products = Product::all();

        return view('eloans.new-business-eloan', compact('user', 'userInfo', 'partners', 'products'));

    }

    public function createRechargeELoan(){
        $user = Client::where('natid', auth()->user()->natid)->first();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        if(is_null($yuser)){
            return redirect('remaining-details');
        } elseif ($yuser->national_pic == null OR $yuser->passport_pic == null OR $yuser->payslip_pic== null) {
            return view('clients.register-three', compact('yuser'));
        } else {
            $userInfo = DB::table('kycs as k')
                ->join('banks as b', 'b.id','=','k.bank')
                ->select('b.bank','k.branch','k.branch_code','k.acc_number')
                ->where('k.bank','=',$yuser->bank)
                ->first();
        }

        return view('eloans.new-recharge-eloan', compact('user', 'userInfo'));

    }
}
