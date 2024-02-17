<?php

namespace App\Http\Controllers;

use App\Events\NewLOZambiaLoanApproval;
use App\Events\NewManagerZambiaLoanApproval;
use App\Events\ZamManagerApprovedLoan;
use App\Models\Bank;
use App\Models\Product;
use App\Models\User;
use App\Models\ZambiaLoan;
use App\Models\Zambian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;

class ZambiaLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.deleted_at','=', null)
            ->get();
        return view('zamloans.all-loans', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loRole = Role::where('slug','=','agent')->firstOrFail();
        $agents = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$loRole->id)
            ->where('users.locale','=','2')
            ->get();

        $clients = Zambian::all();
        $products = Product::where('loandevice',true)->where('pcode','!=','118223')->get();
        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();
        return view('zamloans.apply-for-client', compact('clients','banks','products','agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkLoan = ZambiaLoan::where('loan_principal_amount','=',$request->input('loan_principal_amount'))
            ->where('zambian_id','=',$request->input('borrower_id'))
            ->where('loan_product_id','=',$request->input('loan_product_id'))
            ->where('loan_disbursed_by_id','=',$request->input('loan_disbursed_by_id'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'              => 'required',
                'channel_id'             => 'required',
                'loan_product_id'                  => 'required',
                'loan_disbursed_by_id'                  => 'required',
                'loan_principal_amount'                  => 'required',
                'loan_released_date'                  => 'required|date',
                'loan_interest_period'                  => 'required',
                'loan_duration_period'                  => 'required',
                'loan_duration'                  => 'required',
                'loan_payment_scheme_id'                  => 'required',
                'loan_num_of_repayments'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $zambian = Zambian::where('id',$request->input('borrower_id'))->firstOrFail();
        $user =User::where('natid',$zambian->nrc)->firstOrFail();

        $loan = ZambiaLoan::create([
            'user_id'             => $user->id,
            'zambian_id'       => $request->input('borrower_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => 'www.eshagi.com',
            'loan_status'            => 0,
            'loan_product_id'         => $request->input('loan_product_id'),
            'ld_borrower_id'            => $request->input('ld_borrower_id'),
            'loan_application_id'      => '777',
            'loan_disbursed_by_id'        => $request->input('loan_disbursed_by_id'),
            'loan_principal_amount'        => $request->input('loan_principal_amount'),
            'loan_released_date'        => $request->input('loan_released_date'),
            'loan_interest_method'        => getSelfLoanInterestMethod(),
            'loan_interest_type'        => 'percentage',
            'loan_interest_period'        => $request->input('loan_interest_period'),
            'loan_interest'        => getDeviceInterestRate(),
            'loan_duration_period'        => $request->input('loan_duration_period'),
            'loan_duration'        => $request->input('loan_duration'),
            'loan_payment_scheme_id'        => $request->input('loan_payment_scheme_id'),
            'loan_num_of_repayments'        => $request->input('loan_num_of_repayments'),
            'loan_decimal_places'        => $request->input('loan_decimal_places'),
            'loan_interest_start_date'        => $request->input('loan_interest_start_date'),
            'loan_fees_pro_rata'        => $request->input('loan_fees_pro_rata'),
            'loan_do_not_adjust_remaining_pro_rata'        => $request->input('loan_do_not_adjust_remaining_pro_rata'),
            'loan_first_repayment_pro_rata'        => $request->input('loan_first_repayment_pro_rata'),
            'loan_first_repayment_date'        => $request->input('loan_first_repayment_date'),
            'first_repayment_amount'        => $request->input('first_repayment_amount'),
            'last_repayment_amount'        => $request->input('last_repayment_amount'),
            'loan_override_maturity_date'        => $request->input('loan_override_maturity_date'),
            'override_each_repayment_amount'        => $request->input('override_each_repayment_amount'),
            'loan_interest_each_repayment_pro_rata'        => $request->input('loan_interest_each_repayment_pro_rata'),
            'loan_interest_schedule'        => $request->input('loan_interest_schedule'),
            'loan_principal_schedule'        => $request->input('loan_principal_schedule'),
            'loan_balloon_repayment_amount'        => $request->input('loan_balloon_repayment_amount'),
            'automatic_payments'        => $request->input('automatic_payments'),
            'payment_posting_period'        => $request->input('payment_posting_period'),
            'total_amount_due'        => $request->input('total_amount_due'),
            'balance_amount'        => $request->input('balance_amount'),
            'due_date'        => $request->input('due_date'),
            'total_paid'        => $request->input('total_paid'),
            'child_status_id'        => $request->input('child_status_id'),
            'loan_override_sys_gen_penalty'        => $request->input('loan_override_sys_gen_penalty'),
            'loan_manual_penalty_amount'        => $request->input('loan_manual_penalty_amount'),
            'loan_status_id'        => $request->input('loan_status_id'),
            'loan_title'        => $request->input('loan_title'),
            'loan_description'        => $request->input('loan_description'),
            'cf_11133_approval_date'        => $request->input('cf_11133_approval_date'),
            'cf_11353_installment'        => $request->input('cf_11353_installment'),
            'cf_11132_qty'        => $request->input('cf_11132_qty'),
            'cf_11130_sales_rep'        => $request->input('loan_agent'),
            'cf_11136_account_num'        => $request->input('cf_11136_account_num'),
            'cf_11134_bank'        => $request->input('cf_11134_bank'),
            'cf_11135_branch'        => $request->input('cf_11135_branch'),
        ]);
        $loan->save();

        if ($loan->save()){
            $loan->loan_application_id = $loan->id;
            $loan->save();

            event(new NewLOZambiaLoanApproval($loan));
        }

        return redirect('my-zambia-loans')->with('success', 'Loan Created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ZambiaLoan  $zambiaLoan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = ZambiaLoan::findOrFail($id);
        $client = Zambian::where('id','=',$loan->zambian_id)->firstOrFail();

        return view('zamloans.view-zamloan', compact('loan','client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ZambiaLoan  $zambiaLoan
     * @return \Illuminate\Http\Response
     */
    public function edit(ZambiaLoan $zambiaLoan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ZambiaLoan  $zambiaLoan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ZambiaLoan $zambiaLoan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZambiaLoan  $zambiaLoan
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZambiaLoan $zambiaLoan)
    {
        //
    }

    public function getMyZambianLoans(){
        $loans = DB::table('zambia_loans as l')
            ->join('zambians as c', 'c.id','=','l.zambian_id')
            ->select('l.id','c.first_name','c.last_name','c.nrc','l.loan_principal_amount','l.cf_11353_installment','l.total_amount_due','l.balance_amount','l.created_at')
            ->where('l.partner_id','=', auth()->user()->id)
            ->get();

        return view('zamloans.agent-loans', compact('loans'));
    }

    public function reviewZambianLoans()
    {
        $loans = DB::table('zambia_loans as l')
            ->join('zambians as c', 'c.id','=','l.zambian_id')
            ->select('l.id','c.first_name','c.last_name','c.nrc','l.loan_principal_amount','l.cf_11353_installment','l.total_amount_due','l.balance_amount','l.created_at')
            ->where('l.partner_id','=', auth()->user()->id)
            ->get();

        return view('zamloans.review-ld-loans', compact('loans'));
    }

    public function newZambianLoans(){
        $loans =  DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.loan_status_id','=', 1)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('zamloans.new-zamloans', compact('loans'));
    }

    public function verifyZambianLoans(){
        $loans =  DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.lo_approved','=', false)
            ->where('l.loan_status_id','=', 1)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('zamloans.verify-zamloans', compact('loans'));
    }

    public function loadLoanToVerify($id){
        $zambiaLoan = ZambiaLoan::findOrFail($id);
        $zambian = Zambian::findOrFail($zambiaLoan->zambian_id);
        $zambiaLoans = ZambiaLoan::where('user_id',$zambiaLoan->user_id)->get();

        return view('zamloans.zam-loan-details', compact('zambian','zambiaLoan','zambiaLoans'));
    }

    public function verifyingTheLoan($id){
        $zambiaLoan = ZambiaLoan::findOrFail($id);

        $zambiaLoan->lo_approved = true;
        $zambiaLoan->lo_approver = auth()->user()->name;
        $zambiaLoan->loan_status = 114;
        $zambiaLoan->save();

        if ($zambiaLoan->save()){
            event(new NewManagerZambiaLoanApproval($zambiaLoan));
        }

        return redirect()->back()->with('success', 'Loan Approved successfully');
    }

    public function authorizeLoanReq($id){
        $zambiaLoan = ZambiaLoan::findOrFail($id);
        $zambian = Zambian::findOrFail($zambiaLoan->zambian_id);
        $zambiaLoans = ZambiaLoan::where('user_id',$zambiaLoan->user_id)->get();

        return view('zamloans.authorize-loan', compact('zambian','zambiaLoan','zambiaLoans'));

    }

    public function authorizeTheLoanAndPostLoanDisk($id){
        $loan = ZambiaLoan::where('id','=',$id)->firstOrFail();
        $client = Zambian::where('id','=',$loan->zambian_id)->firstOrFail();

        if (is_null($client->ld_borrower_id)){
            return redirect()->back()->with('error','Borrower was not submitted to LoanDisk successfully.');
        }

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/loan',[
                'loan_product_id' => $loan->loan_product_id,
                'borrower_id' => $client->ld_borrower_id,
                'loan_application_id' => 'ZAM'.$loan->loan_application_id,
                'loan_disbursed_by_id' => $loan->loan_disbursed_by_id,
                'loan_principal_amount' => $loan->loan_principal_amount,
                'loan_released_date' => date_format($loan->loan_released_date, 'd/m/Y'),
                'loan_interest_method' => $loan->loan_interest_method,
                'loan_interest_type' => $loan->loan_interest_type,
                'loan_interest_period' => $loan->loan_interest_period,
                'loan_interest' => $loan->loan_interest,
                'loan_duration_period' => $loan->loan_duration_period,
                'loan_duration' => $loan->loan_duration,
                'loan_payment_scheme_id' => $loan->loan_payment_scheme_id,
                'loan_num_of_repayments' => $loan->loan_num_of_repayments,
                'loan_status_id' =>  $loan->loan_status_id,
                'loan_decimal_places' => $loan->loan_decimal_places ?? 'round_off_to_two_decimal',
                'loan_description' => $loan->loan_description,
                'custom_field_11133' => date_format($loan->cf_11133_approval_date, 'd/m/Y'),
                'custom_field_11353' => $loan->cf_11353_installment,
                'custom_field_11132' => $loan->cf_11132_qty,
                'custom_field_11130' => $loan->cf_11130_sales_rep,
                'custom_field_11136' => $loan->cf_11136_account_num,
                'custom_field_11134' => $loan->cf_11134_bank,
                'custom_field_11135' => $loan->cf_11135_branch,
            ])
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('errors', collect($resp['response']['Errors']));
        }
        $loan->ld_loan_id = $resp['response']['loan_id'];
        $loan->ld_borrower_id = $client->ld_borrower_id;
        $loan->loan_decimal_places = 'round_off_to_two_decimal';
        $loan->manager_approved = true;
        $loan->manager_approver = auth()->user()->name;
        $loan->loan_status = 115;
        $loan->save();

        if ($loan->save()){
            event(new ZamManagerApprovedLoan($loan));
        }

        return redirect()->back()->with('success','Loan uploaded to Loan Disk successfully.');

    }

    public function pullingFromLoanDisk($id){
        $loan = ZambiaLoan::where('ld_loan_id','=',$id)->firstOrFail();

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->get('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/loan/'.$loan->ld_loan_id)
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('error', 'Got this error from Loan Disk: '.$resp['response']['Errors'][0]);
        }

        $loan->balance_amount = $resp["response"]["Results"][0]["balance_amount"];
        $loan->due_date = $resp["response"]["Results"][0]["due_date"];
        $loan->total_paid = $resp["response"]["Results"][0]["total_paid"];
        $loan->child_status_id = $resp["response"]["Results"][0]["child_status_id"];
        $loan->loan_status_id = $resp["response"]["Results"][0]["loan_status_id"];
        $loan->loan_description = $resp["response"]["Results"][0]["loan_description"];
        $loan->loan_manual_penalty_amount = $resp["response"]["Results"][0]["loan_manual_penalty_amount"];

        $loan->save();
        return redirect()-back()->with('success','Loan updated successfully.');

    }

    public function zambiaLoanCalculator(){
        return view('zamloans.zam-calculator');
    }

    public function zamDevicesToEnroll(){
        $loans = DB::table('zambia_loans as l')
            ->join('zambians as u', 'u.id', '=', 'l.zambian_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.nrc', 'l.loan_interest', 'l.loan_duration', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.loan_status')
            ->where('l.loan_status','=', 115)
            ->where('l.lo_approved','=', true)
            ->where('l.manager_approved','=', true)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('zamloans.zam-enroll-paytrigger', compact('loans'));
    }

    public function enrollThisDevice($id) {
        $loan = ZambiaLoan::where('id','=',$id)->firstOrFail();
        $zambian =  Zambian::where('id', $loan->zambian_id)->firstOrFail();
        $agent =  User::where('id', $loan->partner_id)->first();


        return view('zamloans.paytrigger-zam-enroll', compact('loan','agent','zambian'));
    }

    public function enrollZamLoanOnPayTrigger(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'imei'            => 'required',
                'serial'              => 'required',
                'next_payment'             => 'required',
            ],
            [
                'imei.required' => 'Device IMEI number required',
                'serial.required'         => 'Device serial number required',
                'next_payment.required'         => 'When is the next payment date?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = ZambiaLoan::findOrFail($request->input('loan_id'));
        $loan->imei = $request->input('imei');
        $loan->serial_num = $request->input('serial');
        $loan->next_payment = $request->input('next_payment');
        $loan->save();

        $devices = DB::table('zambia_loans')
            ->select('imei',DB::raw("UNIX_TIMESTAMP(DATE(next_payment)) as expiration"))
            ->where('loan_status','=', 115)
            ->where('id','=', $loan->id)
            ->get()
            ->toArray();

        $data = [
            "apiKey"=>Config::get('configs.PAYTRIGGER_KEY'),
            "imeiInfo" => json_encode($devices),
            "preLockFlag"=>true,
        ];

        $str_json = json_encode($devices, JSON_UNESCAPED_SLASHES);

        $dataToSign="apiKey=".Config::get('configs.PAYTRIGGER_KEY')."&imeiInfo=$str_json&preLockFlag=true";
        $sig = strtoupper(hash_hmac('sha256', $dataToSign, Config::get('configs.PAYTRIGGER_KEY')));
        $signature = base64_encode($sig);

        $response = Http::withHeaders([
            'sign' => $signature,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'])
            ->withBody(json_encode($data), 'application/json; charset=UTF-8')
            ->post('https://paytrigger.shalltry.com/PayTrigger/api/partner/lock/v1/imei/input');

        $resp=json_decode($response, TRUE);

        if(isset($resp['error']) AND $resp['error'] != '200'){
            if(isset($resp['message'])){
                return redirect()->back()->with('error', 'An error occurred while enrolling: '. $resp['message']);
            }
        }

        $loan->loan_status = 117;
        $loan->enrollment_date = Carbon::now();
        $loan->next_payment = $request->input('next_payment');
        $loan->save();

        return redirect('set-zam-lock-parameters/'.$loan->id)->with('success', 'Device enrolled successfully.');
    }

    public function gettingZamLockParameters($id) {
        $loan = ZambiaLoan::where('id','=',$id)->firstOrFail();
        $zambian =  Zambian::where('id', $loan->zambian_id)->firstOrFail();
        $agent =  User::where('id', $loan->partner_id)->first();


        return view('zamloans.zam-lock-parameters', compact('loan','agent','zambian'));
    }

    public function setZamLockSchedule(Request $request){
        $loan = ZambiaLoan::findOrFail($request->input('loan_id'));
        $zambian =  Zambian::where('id', $loan->zambian_id)->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
                'currentTerm'  => 'required',
                'totalTerm'  => 'required',
                'description'  => 'required',
                'nextRepayAmt'  => 'required',
                'nextRepayTime'  => 'required',
                'repayedAmt'  => 'required',
                'totalAmt'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $nextRepayAmtTimeStamp = strtotime($request->input('nextRepayTime'));
        $data = [
            "currencyType"=>"$",
            "currentTerm"=>$request->input('currentTerm'),
            "description"=>$request->input('description'),
            "deviceTag"=>$loan->serial_num,
            "imei"=> $loan->imei,
            "nextRepayAmt"=>$request->input('nextRepayAmt'),
            "nextRepayTime"=>$nextRepayAmtTimeStamp,
            "orderNum"=>"eShagi Zambia Device Loan ID: ".$loan->id,
            "phoneNum"=>"+263".$zambian->mobile,
            "relatedMerchant"=>Config::get('configs.PAYTRIGGER_KEY'),
            "repayedAmt"=>$request->input('repayedAmt'),
            "totalAmt"=>$request->input('totalAmt'),
            "totalTerm"=>$request->input('totalTerm'),
        ];

        $dataToSign="currencyType=$&currentTerm=".$request->input('currentTerm')."&description=".$request->input('description')."&deviceTag=".$loan->serial_num."&imei=".$loan->imei."&nextRepayAmt=".$request->input('nextRepayAmt')."&nextRepayTime=".$nextRepayAmtTimeStamp."&orderNum=eShagi Device Loan ID: ".$loan->id."&phoneNum=+263".$zambian->mobile."&relatedMerchant=".Config::get('configs.PAYTRIGGER_KEY')."&repayedAmt=".$request->input('repayedAmt')."&totalAmt=".$request->input('totalAmt')."&totalTerm=".$request->input('totalTerm');
        $sig = strtoupper(hash_hmac('sha256', $dataToSign, Config::get('configs.PAYTRIGGER_KEY')));
        $signature = base64_encode($sig);

        $response = Http::withHeaders([
            'sign' => $signature,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'])
            ->withBody(json_encode($data), 'application/json; charset=UTF-8')
            ->post('https://paytrigger.shalltry.com/PayTrigger/api/partner/lock/v1/updateRepayInfo');

        $resp=json_decode($response, TRUE);

        if(isset($resp['error']) AND $resp['error'] != '200'){
            if(isset($resp['message'])){
                return redirect()->back()->with('error', 'An error occurred while setting lock parameters: '. $resp['message']);
            }
        }

        $loan->loan_status = 118;
        $loan->save();

        return redirect('zam-enroll-paytrigger')->with('success', 'Lock parameters set successfully');
    }

    public function newZambiaCashLoan(){
        $client = Zambian::where('nrc',auth()->user()->natid)->first();
        if (is_null($client)){
            return redirect('continue-zambia-reg')->with('error','Please create your full account to apply for a loan.');
        }

        return view('zamloans.new-zm-cash', compact('client'));
    }

    public function saveNewCashLoan(Request $request){
        $checkLoan = ZambiaLoan::where('loan_principal_amount','=',$request->input('loan_principal_amount'))
            ->where('zambian_id','=',$request->input('borrower_id'))
            ->where('loan_product_id','=',$request->input('loan_product_id'))
            ->where('loan_disbursed_by_id','=',$request->input('loan_disbursed_by_id'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'              => 'required',
                'channel_id'             => 'required',
                'loan_disbursed_by_id'                  => 'required',
                'loan_principal_amount'                  => 'required',
                'loan_released_date'                  => 'required|date',
                'loan_interest_period'                  => 'required',
                'loan_duration_period'                  => 'required',
                'loan_duration'                  => 'required',
                'loan_payment_scheme_id'                  => 'required',
                'loan_num_of_repayments'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $zambian = Zambian::where('id',$request->input('borrower_id'))->firstOrFail();
        $user =User::where('natid',$zambian->nrc)->firstOrFail();


        $loan = ZambiaLoan::create([
            'user_id'             => $user->id,
            'zambian_id'       => $request->input('borrower_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_status'            => 0,
            'loan_product_id'         => '118223',
            'ld_borrower_id'            => $request->input('ld_borrower_id'),
            'loan_application_id'      => '777',
            'loan_disbursed_by_id'        => $request->input('loan_disbursed_by_id'),
            'loan_principal_amount'        => $request->input('loan_principal_amount'),
            'loan_released_date'        => $request->input('loan_released_date'),
            'loan_interest_method'        => getSelfLoanInterestMethod(),
            'loan_interest_type'        => 'percentage',
            'loan_interest_period'        => $request->input('loan_interest_period'),
            'loan_interest'        => getSelfInterestRate(),
            'loan_duration_period'        => $request->input('loan_duration_period'),
            'loan_duration'        => $request->input('loan_duration'),
            'loan_payment_scheme_id'        => $request->input('loan_payment_scheme_id'),
            'loan_num_of_repayments'        => $request->input('loan_num_of_repayments'),
            'loan_decimal_places'        => $request->input('loan_decimal_places'),
            'loan_interest_start_date'        => $request->input('loan_interest_start_date'),
            'loan_fees_pro_rata'        => $request->input('loan_fees_pro_rata'),
            'loan_do_not_adjust_remaining_pro_rata'        => $request->input('loan_do_not_adjust_remaining_pro_rata'),
            'loan_first_repayment_pro_rata'        => $request->input('loan_first_repayment_pro_rata'),
            'loan_first_repayment_date'        => $request->input('loan_first_repayment_date'),
            'first_repayment_amount'        => $request->input('first_repayment_amount'),
            'last_repayment_amount'        => $request->input('last_repayment_amount'),
            'loan_override_maturity_date'        => $request->input('loan_override_maturity_date'),
            'override_each_repayment_amount'        => $request->input('override_each_repayment_amount'),
            'loan_interest_each_repayment_pro_rata'        => $request->input('loan_interest_each_repayment_pro_rata'),
            'loan_interest_schedule'        => $request->input('loan_interest_schedule'),
            'loan_principal_schedule'        => $request->input('loan_principal_schedule'),
            'loan_balloon_repayment_amount'        => $request->input('loan_balloon_repayment_amount'),
            'automatic_payments'        => $request->input('automatic_payments'),
            'payment_posting_period'        => $request->input('payment_posting_period'),
            'total_amount_due'        => $request->input('total_amount_due'),
            'balance_amount'        => $request->input('balance_amount'),
            'due_date'        => $request->input('due_date'),
            'total_paid'        => $request->input('total_paid'),
            'child_status_id'        => $request->input('child_status_id'),
            'loan_override_sys_gen_penalty'        => $request->input('loan_override_sys_gen_penalty'),
            'loan_manual_penalty_amount'        => $request->input('loan_manual_penalty_amount'),
            'loan_status_id'        => $request->input('loan_status_id'),
            'loan_title'        => $request->input('loan_title'),
            'loan_description'        => $request->input('loan_description'),
            'cf_11133_approval_date'        => $request->input('cf_11133_approval_date'),
            'cf_11353_installment'        => $request->input('cf_11353_installment'),
            'cf_11132_qty'        => $request->input('cf_11132_qty'),
            'cf_11130_sales_rep'        => 'ESHAGI',
            'cf_11136_account_num'        => $request->input('cf_11136_account_num'),
            'cf_11134_bank'        => $request->input('cf_11134_bank'),
            'cf_11135_branch'        => $request->input('cf_11135_branch'),
        ]);
        $loan->save();

        if ($loan->save()){
            $loan->loan_application_id = $loan->id;
            $loan->save();

            event(new NewLOZambiaLoanApproval($loan));
        }

        return redirect('zambia-me-loans')->with('success', 'Loan Created successfully.');

    }

    public function getMyZambianCashLoans(){
        $loans = DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.user_id','=', auth()->user()->id)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('zamloans.client-loans', compact('loans'));
    }

    public function viewClientLoan($id)
    {
        $loan = ZambiaLoan::findOrFail($id);
        $client = Zambian::where('id','=',$loan->zambian_id)->firstOrFail();

        return view('zamloans.view-zamloan', compact('loan','client'));
    }

    public function deleteClientzLoan($id){
        $loan = ZambiaLoan::findOrFail($id);
        if ($loan->user_id != auth()->user()->id){
            return redirect()->back()->with('error', 'You can only delete your loan');
        }
        $loan->delete();

        return redirect()->back()->with('success', 'Loan has been trashed.');
    }

    public function createCashLoan()
    {
        $loRole = Role::where('slug','=','agent')->firstOrFail();
        $agents = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$loRole->id)
            ->where('users.locale','=','2')
            ->get();

        $clients = Zambian::all();
        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();
        return view('zamloans.apply-for-client_cash', compact('clients','banks','agents'));
    }


    public function storeCashLoan(Request $request)
    {
        $checkLoan = ZambiaLoan::where('loan_principal_amount','=',$request->input('loan_principal_amount'))
            ->where('zambian_id','=',$request->input('borrower_id'))
            ->where('loan_product_id','=',$request->input('loan_product_id'))
            ->where('loan_disbursed_by_id','=',$request->input('loan_disbursed_by_id'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'              => 'required',
                'loan_disbursed_by_id'                  => 'required',
                'loan_principal_amount'                  => 'required',
                'loan_released_date'                  => 'required|date',
                'loan_interest_period'                  => 'required',
                'loan_duration_period'                  => 'required',
                'loan_duration'                  => 'required',
                'loan_payment_scheme_id'                  => 'required',
                'loan_num_of_repayments'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $zambian = Zambian::where('id',$request->input('borrower_id'))->firstOrFail();
        $user =User::where('natid',$zambian->nrc)->firstOrFail();

        $loan = ZambiaLoan::create([
            'user_id'             => $user->id,
            'zambian_id'       => $request->input('borrower_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => 'www.eshagi.com',
            'loan_status'            => 0,
            'loan_product_id'         => '118223',
            'ld_borrower_id'            => $request->input('ld_borrower_id'),
            'loan_application_id'      => '777',
            'loan_disbursed_by_id'        => $request->input('loan_disbursed_by_id'),
            'loan_principal_amount'        => $request->input('loan_principal_amount'),
            'loan_released_date'        => $request->input('loan_released_date'),
            'loan_interest_method'        => getSelfLoanInterestMethod(),
            'loan_interest_type'        => 'percentage',
            'loan_interest_period'        => $request->input('loan_interest_period'),
            'loan_interest'        => getSelfInterestRate(),
            'loan_duration_period'        => $request->input('loan_duration_period'),
            'loan_duration'        => $request->input('loan_duration'),
            'loan_payment_scheme_id'        => $request->input('loan_payment_scheme_id'),
            'loan_num_of_repayments'        => $request->input('loan_num_of_repayments'),
            'loan_decimal_places'        => $request->input('loan_decimal_places'),
            'loan_interest_start_date'        => $request->input('loan_interest_start_date'),
            'loan_fees_pro_rata'        => $request->input('loan_fees_pro_rata'),
            'loan_do_not_adjust_remaining_pro_rata'        => $request->input('loan_do_not_adjust_remaining_pro_rata'),
            'loan_first_repayment_pro_rata'        => $request->input('loan_first_repayment_pro_rata'),
            'loan_first_repayment_date'        => $request->input('loan_first_repayment_date'),
            'first_repayment_amount'        => $request->input('first_repayment_amount'),
            'last_repayment_amount'        => $request->input('last_repayment_amount'),
            'loan_override_maturity_date'        => $request->input('loan_override_maturity_date'),
            'override_each_repayment_amount'        => $request->input('override_each_repayment_amount'),
            'loan_interest_each_repayment_pro_rata'        => $request->input('loan_interest_each_repayment_pro_rata'),
            'loan_interest_schedule'        => $request->input('loan_interest_schedule'),
            'loan_principal_schedule'        => $request->input('loan_principal_schedule'),
            'loan_balloon_repayment_amount'        => $request->input('loan_balloon_repayment_amount'),
            'automatic_payments'        => $request->input('automatic_payments'),
            'payment_posting_period'        => $request->input('payment_posting_period'),
            'total_amount_due'        => $request->input('total_amount_due'),
            'balance_amount'        => $request->input('balance_amount'),
            'due_date'        => $request->input('due_date'),
            'total_paid'        => $request->input('total_paid'),
            'child_status_id'        => $request->input('child_status_id'),
            'loan_override_sys_gen_penalty'        => $request->input('loan_override_sys_gen_penalty'),
            'loan_manual_penalty_amount'        => $request->input('loan_manual_penalty_amount'),
            'loan_status_id'        => $request->input('loan_status_id'),
            'loan_title'        => $request->input('loan_title'),
            'loan_description'        => $request->input('loan_description'),
            'cf_11133_approval_date'        => $request->input('cf_11133_approval_date'),
            'cf_11353_installment'        => $request->input('cf_11353_installment'),
            'cf_11132_qty'        => $request->input('cf_11132_qty'),
            'cf_11130_sales_rep'        => $request->input('loan_agent'),
            'cf_11136_account_num'        => $request->input('cf_11136_account_num'),
            'cf_11134_bank'        => $request->input('cf_11134_bank'),
            'cf_11135_branch'        => $request->input('cf_11135_branch'),
        ]);
        $loan->save();

        if ($loan->save()){
            $loan->loan_application_id = $loan->id;
            $loan->save();

            event(new NewLOZambiaLoanApproval($loan));
        }

        return redirect('my-zambia-loans')->with('success', 'Loan Created successfully.');

    }

    public function newZambiaDeviceLoan(){
        $client = Zambian::where('nrc',auth()->user()->natid)->first();
        if (is_null($client)){
            return redirect('continue-zambia-reg')->with('error','Please create your full account to apply for a loan.');
        }
        $products = Product::where('loandevice',true)->where('pcode','!=','118223')->get();

        return view('zamloans.new-zm-device', compact('client','products'));
    }

    public function saveNewDeviceLoan(Request $request){
        $checkLoan = ZambiaLoan::where('loan_principal_amount','=',$request->input('loan_principal_amount'))
            ->where('zambian_id','=',$request->input('borrower_id'))
            ->where('loan_product_id','=',$request->input('loan_product_id'))
            ->where('loan_disbursed_by_id','=',$request->input('loan_disbursed_by_id'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'              => 'required',
                'channel_id'             => 'required',
                'loan_disbursed_by_id'                  => 'required',
                'loan_principal_amount'                  => 'required',
                'loan_released_date'                  => 'required|date',
                'loan_interest_period'                  => 'required',
                'loan_duration_period'                  => 'required',
                'loan_duration'                  => 'required',
                'loan_payment_scheme_id'                  => 'required',
                'loan_num_of_repayments'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $zambian = Zambian::where('id',$request->input('borrower_id'))->firstOrFail();
        $user =User::where('natid',$zambian->nrc)->firstOrFail();


        $loan = ZambiaLoan::create([
            'user_id'             => $user->id,
            'zambian_id'       => $request->input('borrower_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_status'            => 0,
            'loan_product_id'         => $request->input('loan_product_id'),
            'ld_borrower_id'            => $request->input('ld_borrower_id'),
            'loan_application_id'      => '777',
            'loan_disbursed_by_id'        => $request->input('loan_disbursed_by_id'),
            'loan_principal_amount'        => $request->input('loan_principal_amount'),
            'loan_released_date'        => $request->input('loan_released_date'),
            'loan_interest_method'        => getSelfLoanInterestMethod(),
            'loan_interest_type'        => 'percentage',
            'loan_interest_period'        => $request->input('loan_interest_period'),
            'loan_interest'        => getDeviceInterestRate(),
            'loan_duration_period'        => $request->input('loan_duration_period'),
            'loan_duration'        => $request->input('loan_duration'),
            'loan_payment_scheme_id'        => $request->input('loan_payment_scheme_id'),
            'loan_num_of_repayments'        => $request->input('loan_num_of_repayments'),
            'loan_decimal_places'        => $request->input('loan_decimal_places'),
            'loan_interest_start_date'        => $request->input('loan_interest_start_date'),
            'loan_fees_pro_rata'        => $request->input('loan_fees_pro_rata'),
            'loan_do_not_adjust_remaining_pro_rata'        => $request->input('loan_do_not_adjust_remaining_pro_rata'),
            'loan_first_repayment_pro_rata'        => $request->input('loan_first_repayment_pro_rata'),
            'loan_first_repayment_date'        => $request->input('loan_first_repayment_date'),
            'first_repayment_amount'        => $request->input('first_repayment_amount'),
            'last_repayment_amount'        => $request->input('last_repayment_amount'),
            'loan_override_maturity_date'        => $request->input('loan_override_maturity_date'),
            'override_each_repayment_amount'        => $request->input('override_each_repayment_amount'),
            'loan_interest_each_repayment_pro_rata'        => $request->input('loan_interest_each_repayment_pro_rata'),
            'loan_interest_schedule'        => $request->input('loan_interest_schedule'),
            'loan_principal_schedule'        => $request->input('loan_principal_schedule'),
            'loan_balloon_repayment_amount'        => $request->input('loan_balloon_repayment_amount'),
            'automatic_payments'        => $request->input('automatic_payments'),
            'payment_posting_period'        => $request->input('payment_posting_period'),
            'total_amount_due'        => $request->input('total_amount_due'),
            'balance_amount'        => $request->input('balance_amount'),
            'due_date'        => $request->input('due_date'),
            'total_paid'        => $request->input('total_paid'),
            'child_status_id'        => $request->input('child_status_id'),
            'loan_override_sys_gen_penalty'        => $request->input('loan_override_sys_gen_penalty'),
            'loan_manual_penalty_amount'        => $request->input('loan_manual_penalty_amount'),
            'loan_status_id'        => $request->input('loan_status_id'),
            'loan_title'        => $request->input('loan_title'),
            'loan_description'        => $request->input('loan_description'),
            'cf_11133_approval_date'        => $request->input('cf_11133_approval_date'),
            'cf_11353_installment'        => $request->input('cf_11353_installment'),
            'cf_11132_qty'        => $request->input('cf_11132_qty'),
            'cf_11130_sales_rep'        => 'ESHAGI',
            'cf_11136_account_num'        => $request->input('cf_11136_account_num'),
            'cf_11134_bank'        => $request->input('cf_11134_bank'),
            'cf_11135_branch'        => $request->input('cf_11135_branch'),
        ]);
        $loan->save();

        if ($loan->save()){
            $loan->loan_application_id = $loan->id;
            $loan->save();

            event(new NewLOZambiaLoanApproval($loan));
        }

        return redirect('zambia-me-loans')->with('success', 'Loan Created successfully.');

    }

    public function lookupLoansFromNrc($id){
        $client = Zambian::where('id','=',$id)->firstOrFail();

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->get('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/loan/borrower/'.$client->ld_borrower_id.'/from/1/count/25')
            ->body();

        $resp=json_decode($details, TRUE);
        print_r(count($resp['response']['Results'][0]));
        dd($resp['response']['Results'][0]);
        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('error', 'Got this error from Loan Disk: '.$resp['response']['Errors'][0]);
        }
    }

    public function pendingZambianLoans() {
        $loans =  DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.lo_approved','=', false)
            ->orWhere('l.manager_approved','=', false)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('zamloans.pending-zambian-loans', compact('loans'));
    }

    public function authorizedZambianLoans() {
        $loans =  DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->join('loan_approvals as a', 'a.loan_id', '=', 'l.id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.lo_approved','=', true)
            ->where('l.manager_approved','=', true)
            ->where('a.verified','=', true)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('zamloans.zambia-authorized', compact('loans'));
    }

    public function issueOutLoan($id) {

        $loan = ZambiaLoan::findOrFail($id);
        $client = Zambian::where('id', $loan->zambian_id)->first();

        if ($client->lo_approved == 0 || $client->manager_approved == 0) {
            return redirect()->back()->with('error', 'Seems as if eShagi has not yet approved the client KYC information. Please wait for them to approve.');
        }

        if ($loan->lo_approved == 0 || $loan->manager_approved == 0) {
            return redirect()->back()->with('error', 'Seems as if eShagi has not yet approved the client Loan application. Please wait for them to approve.');
        }

        DB::table("zambia_loans")
            ->where("id", $loan->id)
            ->update(['loan_status' => 8,'isDisbursed' => true,'disbursed_at' => now(), 'updated_at' => now()]);

//        Http::post(getBulkSmsUrl() . "to=+260".$client->mobile."&msg=Great News ".$client->first_name.", Your loan application for ZMK ".$loan->loan_principal_amount." has been approved and issued out. eShagi.")
//            ->body();

        return redirect()->back()->with('success', 'Loan has been issued out successfully.');
    }

    public function getZambiaDisbursedLoans () {
        $loans =  DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->join('loan_approvals as a', 'a.loan_id', '=', 'l.id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.lo_approved','=', true)
            ->where('l.manager_approved','=', true)
            ->where('a.verified','=', true)
            ->where('l.isDisbursed','=', true)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('zamloans.zambia-disbursed', compact('loans'));
    }

    public function getZambiaDeclinedLoans () {
        $loans =  DB::table('zambia_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->join('loan_approvals as a', 'a.loan_id', '=', 'l.id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_principal_amount', 'l.cf_11353_installment', 'l.total_amount_due', 'l.balance_amount')
            ->where('l.loan_status','=', 9)
            ->where('l.isDisbursed','=', false)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('zamloans.zambia-declined', compact('loans'));
    }

    public function clientAffordability(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_principal_amount'              => 'required',
                'gross_salary'             => 'required',
                'net_salary'                  => 'required',
                'loan_duration'                  => 'required',
                'eligibility'                  => 'required',
                'loan_limit'                  => 'required',
                'limit_test'                  => 'required',
                'interest'                  => 'required',
                'monthly'                  => 'required',
                'total_charges'                  => 'required',
                'net_after_charge'                  => 'required',
                'admin_fee'                  => 'required',
                'insurance_fee'                  => 'required',
                'app_fee'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('eligibility') !== 'Pass'){
            return redirect()->back()->with('error', 'Person is not eligible for loan.');
        }

        if ($request->input('limit_test') !== 'Pass'){
            return redirect()->back()->with('error', 'Loan Limit exceeded for client');
        }

        $deductions = $request->input('net_salary') - $request->input('monthly');
        $lawAllowed = 0.4*$request->input('gross_salary');
        $loanAmt = $request->input('loan_principal_amount');

        $eligible="";
        $loanLimiter="";
        $loanLimit = 3*$request->input('net_salary');

        if (number_format($deductions, 2,'.') > number_format($lawAllowed, 2,'.')){
            $eligible = "Pass";
        } else {
            $eligible = "Fail";
        }

        if ($loanAmt < $loanLimit){
            $loanLimiter = "Pass";
        } else {
            $loanLimiter = "Fail";
        }

        if ($eligible == "Fail" || $loanLimiter == "Fail"){
            return redirect()->back()->with('error', 'Client is not eligible for applying');
        }

        $provinces = DB::table("provinces")
            ->where("country", auth()->user()->locale)
            ->select("id", "country", "province")
            ->orderBy("province", 'asc')
            ->get();

        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();

        return view('zambians.register-for-zambian', compact('provinces', 'banks', 'eligible', 'loanLimiter'));
    }

    public function drawDownOnSavingsAccount($id){
        $client = Zambian::where('id', $id)->firstOrFail();

        $loRole = Role::where('slug','=','agent')->firstOrFail();
        $agents = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$loRole->id)
            ->where('users.locale','=','2')
            ->get();

        return view('zamloans.draw-down-loan', compact('client','agents'));
    }

    public function processDrawDownSavings(Request $request){
        $checkLoan = ZambiaLoan::where('loan_principal_amount','=',$request->input('loan_principal_amount'))
            ->where('zambian_id','=',$request->input('borrower_id'))
            ->where('loan_product_id','=',$request->input('loan_product_id'))
            ->where('loan_disbursed_by_id','=',$request->input('loan_disbursed_by_id'))
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'              => 'required',
                'loan_disbursed_by_id'                  => 'required',
                'loan_principal_amount'                  => 'required',
                'loan_released_date'                  => 'required|date',
                'loan_interest_period'                  => 'required',
                'loan_duration_period'                  => 'required',
                'loan_duration'                  => 'required',
                'loan_payment_scheme_id'                  => 'required',
                'loan_num_of_repayments'                  => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $zambian = Zambian::where('id',$request->input('borrower_id'))->firstOrFail();
        $user =User::where('natid',$zambian->nrc)->firstOrFail();

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/saving_transaction',[
                'savings_id' => $zambian->savings_id,
                'transaction_date' => Carbon::now()->format('d/m/Y'),
                'transaction_time' => Carbon::now()->format('H:m:ss A'),
                'transaction_type_id' => "2",
                'transaction_amount' => $request->input('loan_principal_amount'),
                'transaction_description' => "Loan draw down from Savings",
            ])
            ->body();

        $balResp=json_decode($details, TRUE);

        if (isset($balResp['response']['Errors'])){
            return redirect()->back()->with('errors', collect($balResp['response']['Errors']));
        }

        if (isset($balResp['response']['transaction_id'])){
            $loan = ZambiaLoan::create([
                'user_id'             => $user->id,
                'zambian_id'       => $request->input('borrower_id'),
                'partner_id'        => auth()->user()->id,
                'channel_id'            => 'www.eshagi.com',
                'loan_status'            => 1,
                'loan_product_id'         => '118223',
                'ld_borrower_id'            => $request->input('ld_borrower_id'),
                'loan_application_id'      => '777',
                'loan_disbursed_by_id'        => $request->input('loan_disbursed_by_id'),
                'loan_principal_amount'        => $request->input('loan_principal_amount'),
                'loan_released_date'        => $request->input('loan_released_date'),
                'loan_interest_method'        => getSelfLoanInterestMethod(),
                'loan_interest_type'        => 'percentage',
                'loan_interest_period'        => $request->input('loan_interest_period'),
                'loan_interest'        => getSelfInterestRate(),
                'loan_duration_period'        => $request->input('loan_duration_period'),
                'loan_duration'        => $request->input('loan_duration'),
                'loan_payment_scheme_id'        => $request->input('loan_payment_scheme_id'),
                'loan_num_of_repayments'        => $request->input('loan_num_of_repayments'),
                'loan_decimal_places'        => $request->input('loan_decimal_places'),
                'loan_interest_start_date'        => $request->input('loan_interest_start_date'),
                'loan_fees_pro_rata'        => $request->input('loan_fees_pro_rata'),
                'loan_do_not_adjust_remaining_pro_rata'        => $request->input('loan_do_not_adjust_remaining_pro_rata'),
                'loan_first_repayment_pro_rata'        => $request->input('loan_first_repayment_pro_rata'),
                'loan_first_repayment_date'        => $request->input('loan_first_repayment_date'),
                'first_repayment_amount'        => $request->input('first_repayment_amount'),
                'last_repayment_amount'        => $request->input('last_repayment_amount'),
                'loan_override_maturity_date'        => $request->input('loan_override_maturity_date'),
                'override_each_repayment_amount'        => $request->input('override_each_repayment_amount'),
                'loan_interest_each_repayment_pro_rata'        => $request->input('loan_interest_each_repayment_pro_rata'),
                'loan_interest_schedule'        => $request->input('loan_interest_schedule'),
                'loan_principal_schedule'        => $request->input('loan_principal_schedule'),
                'loan_balloon_repayment_amount'        => $request->input('loan_balloon_repayment_amount'),
                'automatic_payments'        => $request->input('automatic_payments'),
                'payment_posting_period'        => $request->input('payment_posting_period'),
                'total_amount_due'        => $request->input('total_amount_due'),
                'balance_amount'        => $request->input('balance_amount'),
                'due_date'        => $request->input('due_date'),
                'total_paid'        => $request->input('total_paid'),
                'child_status_id'        => $request->input('child_status_id'),
                'loan_override_sys_gen_penalty'        => $request->input('loan_override_sys_gen_penalty'),
                'loan_manual_penalty_amount'        => $request->input('loan_manual_penalty_amount'),
                'loan_status_id'        => $request->input('loan_status_id'),
                'loan_title'        => $request->input('loan_title'),
                'loan_description'        => $request->input('loan_description'),
                'cf_11133_approval_date'        => $request->input('cf_11133_approval_date'),
                'cf_11353_installment'        => $request->input('cf_11353_installment'),
                'cf_11132_qty'        => $request->input('cf_11132_qty'),
                'cf_11130_sales_rep'        => $request->input('loan_agent'),
                'cf_11136_account_num'        => $request->input('cf_11136_account_num'),
                'cf_11134_bank'        => $request->input('cf_11134_bank'),
                'cf_11135_branch'        => $request->input('cf_11135_branch'),
            ]);
            $loan->save();

            if ($loan->save()){
                $loan->loan_application_id = $loan->id;
                $loan->save();

                $details = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
                ])
                    ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/loan',[
                        'loan_product_id' => $loan->loan_product_id,
                        'borrower_id' => $zambian->ld_borrower_id,
                        'loan_application_id' => 'ZAM'.$loan->loan_application_id,
                        'loan_disbursed_by_id' => $loan->loan_disbursed_by_id,
                        'loan_principal_amount' => $loan->loan_principal_amount,
                        'loan_released_date' => date_format($loan->loan_released_date, 'd/m/Y'),
                        'loan_interest_method' => $loan->loan_interest_method,
                        'loan_interest_type' => $loan->loan_interest_type,
                        'loan_interest_period' => $loan->loan_interest_period,
                        'loan_interest' => $loan->loan_interest,
                        'loan_duration_period' => $loan->loan_duration_period,
                        'loan_duration' => $loan->loan_duration,
                        'loan_payment_scheme_id' => $loan->loan_payment_scheme_id,
                        'loan_num_of_repayments' => $loan->loan_num_of_repayments,
                        'loan_status_id' =>  $loan->loan_status_id,
                        'loan_decimal_places' => $loan->loan_decimal_places ?? 'round_off_to_two_decimal',
                        'loan_description' => $loan->loan_description,
                        'custom_field_11133' => date_format($loan->cf_11133_approval_date, 'd/m/Y'),
                        'custom_field_11353' => $loan->cf_11353_installment,
                        'custom_field_11132' => $loan->cf_11132_qty,
                        'custom_field_11130' => $loan->cf_11130_sales_rep,
                        'custom_field_11136' => $loan->cf_11136_account_num,
                        'custom_field_11134' => $loan->cf_11134_bank,
                        'custom_field_11135' => $loan->cf_11135_branch,
                    ])
                    ->body();

                $resp=json_decode($details, TRUE);

                if (isset($resp['response']['Errors'])){
                    return redirect()->back()->with('errors', collect($resp['response']['Errors']));
                }
                $loan->ld_loan_id = $resp['response']['loan_id'];
                $loan->ld_borrower_id = $zambian->ld_borrower_id;
                $loan->loan_decimal_places = 'round_off_to_two_decimal';
                $loan->manager_approved = true;
                $loan->manager_approver = auth()->user()->name;
                $loan->loan_status = 115;
                $loan->save();
            }
        }

        return redirect('my-zambia-loans')->with('success', 'Draw down added successfully.');
    }


}
