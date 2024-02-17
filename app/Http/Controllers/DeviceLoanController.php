<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\DeviceLoan;
use App\Models\Kyc;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Representative;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeviceLoanController extends Controller
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
            $loans = DB::table('device_loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.deleted_at','=', null)
                ->get();
        } else {
            $loans = DB::table('device_loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.locale','=', auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->get();
        }

        return view('deviceloans.all-deviceloans', compact('loans'));
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
        $products = Product::where('loandevice',true)->get();

        return view('deviceloans.apply-device-loan', compact('user', 'bank','yuser','products'));
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
        $device = Product::where('id', $request->input('device'))->first();

        $request->merge([
            'client_id' => $client->id,
        ]);

        $checkLoan = DeviceLoan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',11)
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'client_id'            => 'required',
                'amount'              => 'required',
                'paybackPeriod'             => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'disbursed'                  => 'required',
                'appFee'                  => 'required',
                'charges'                  => 'required',
                'device'                  => 'required',
            ],
            [
                'client_id.required' => 'Please make sure you\'re logged in.',
                'amount.required'         => 'The amount for the loan is needed.',
                'paybackPeriod.required'         => 'How long are you planning on paying back this loan?',
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

        $loan = DeviceLoan::create([
            'user_id'             => auth()->user()->id,
            'client_id'       => $request->input('client_id'),
            'partner_id'        => $request->input('partner_id'),
            'channel_id'            => 'www.eshagi.com',
            'loan_type'            => 1,
            'loan_status'            => 0,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate'      => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            'appFee'        => $request->input('appFee'),
            'charges'        => $request->input('charges'),
            'notes'        => $request->input('device'),
            'locale'        => auth()->user()->locale,
            'device'        => $device->pname,
            'device_model'        => $device->model,
        ]);
        $loan->save();

        if ($loan->save()) {
//            $newlimit = $client->cred_limit-$request->input('amount');
//            DB::table('clients')
//                ->where('id',$request->input('client_id'))
//                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }
        return redirect('getdeviceloaninfo/'.$loan->id)->with('success', 'Please read your agreement and sign your loan to begin processing it.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeviceLoan  $deviceLoan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = DeviceLoan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $partner = User::where('id', $loan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();

        if (is_null($agent)){
            $repInfo = null;
            $merchant = null;
            return view('deviceloans.deviceloan-info', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));

        }

        if ($agent->utype == 'Representative'){
            $repInfo = Representative::where('natid', $agent->natid)->first();
            $merchant = Partner::where('id',$repInfo->partner_id)->first();
        } else {
            $repInfo = null;
            $merchant = null;
        }

        return view('deviceloans.deviceloan-info', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeviceLoan  $deviceLoan
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceLoan $deviceLoan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeviceLoan  $deviceLoan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeviceLoan $deviceLoan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeviceLoan  $deviceLoan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeviceLoan $deviceLoan)
    {
        //
    }

    public function deviceLoanInfoSignature ($id) {
        $loan =DeviceLoan::findOrFail($id);
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        return view('deviceloans.sign-my-devloan', compact('loan', 'yuser'));
    }

    public function uploadSignature(Request $request) {

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
                    $loan = DeviceLoan::where('id', $request->input('loan_id'))->first();

                    $loan->loan_status = 1;
                    $loan->save();
                }
            } else {
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect(route('list.mydeviceloans'))->with('success','Your loan application has been submitted for processing.');
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

        $loan = DeviceLoan::where('id',$request->input('loan_id'))->first();

        $loan->loan_status = 1;
        $loan->save();

        return redirect(route('list.mydeviceloans'))->with('success','Your loan application has been submitted for processing.');

    }

    public function getMyDeviceLoans(){
        $loans = DeviceLoan::where('user_id','=', auth()->user()->id)->get();

        $monthlies = $loans->sum("monthly");

        return view('deviceloans.my-deviceloans', compact('loans', 'monthlies'));

    }

    public function enrollThisDevice($id) {
        $loan = DeviceLoan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $partner = User::where('id', $loan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();

        if (is_null($agent)){
            $repInfo = null;
            $merchant = null;
            return view('deviceloans.paytrigger-enroll', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
        }

        if ($agent->utype == 'Representative'){
            $repInfo = Representative::where('natid', $agent->natid)->first();
            $merchant = Partner::where('id',$repInfo->partner_id)->first();
        } else {
            $repInfo = null;
            $merchant = null;
        }

        return view('deviceloans.paytrigger-enroll', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
    }

    public function enrollOnPayTrigger(Request $request){
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

        $loan = DeviceLoan::findOrFail($request->input('loan_id'));
        $loan->imei = $request->input('imei');
        $loan->serial_num = $request->input('serial');
        $loan->next_payment = $request->input('next_payment');
        $loan->save();

        $devices = DB::table('device_loans')
            ->select('imei',DB::raw("UNIX_TIMESTAMP(DATE(next_payment)) as expiration"))
            ->where('loan_status','=', 6)
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

        $loan->loan_status = 7;
        $loan->enrollment_date = Carbon::now();
        $loan->disbursed_at = Carbon::now();
        //$loan->next_payment = Carbon::now()->addMonth();
        $loan->save();

        return redirect('set-lock-parameters/'.$loan->id)->with('success', 'Device enrolled successfully.');
    }

    public function setLockSchedule(Request $request){
        $loan = DeviceLoan::findOrFail($request->input('loan_id'));
        $client = Client::where('id', $loan->client_id)->first();

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
            "orderNum"=>"eShagi Device Loan ID: ".$loan->id,
            "phoneNum"=>"+263".$client->mobile,
            "relatedMerchant"=>Config::get('configs.PAYTRIGGER_KEY'),
            "repayedAmt"=>$request->input('repayedAmt'),
            "totalAmt"=>$request->input('totalAmt'),
            "totalTerm"=>$request->input('totalTerm'),
        ];

        $dataToSign="currencyType=$&currentTerm=".$request->input('currentTerm')."&description=".$request->input('description')."&deviceTag=".$loan->serial_num."&imei=".$loan->imei."&nextRepayAmt=".$request->input('nextRepayAmt')."&nextRepayTime=".$nextRepayAmtTimeStamp."&orderNum=eShagi Device Loan ID: ".$loan->id."&phoneNum=+263".$client->mobile."&relatedMerchant=".Config::get('configs.PAYTRIGGER_KEY')."&repayedAmt=".$request->input('repayedAmt')."&totalAmt=".$request->input('totalAmt')."&totalTerm=".$request->input('totalTerm');
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

        $loan->loan_status = 8;
        $loan->save();

        return redirect('to-enroll-paytrigger')->with('success', 'Lock parameters set successfully');
    }

    public function postDeviceLoanToLoanDisk($id) {
        $loan = DeviceLoan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $partner = User::where('id', $loan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();

        if (is_null($agent)){
            $repInfo = null;
            $merchant = null;
            return view('deviceloans.postloan-loandisk', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
        }

        if ($agent->utype == 'Representative'){
            $repInfo = Representative::where('natid', $agent->natid)->first();
            $merchant = Partner::where('id',$repInfo->partner_id)->first();
        } else {
            $repInfo = null;
            $merchant = null;
        }

        return view('deviceloans.postloan-loandisk', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
    }

    public function sendLoanToLoanDisk(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'            => 'required',
                'imei'            => 'required',
                'serial'              => 'required',
                'next_payment'             => 'required',
                'release_date'             => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = DeviceLoan::where('id','=',$request->input('loan_id'))->firstOrFail();
        $client = Client::where('id','=',$loan->client_id)->firstOrFail();

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZIM_USD_BRANCH').'/loan',[
                'loan_product_id' => $loan->loan_product_id,
                'borrower_id' => $client->reds_number,
                'loan_application_id' => 'ZIM'.$loan->id,
                'loan_disbursed_by_id' => $loan->loan_disbursed_by_id,
                'loan_principal_amount' => $loan->loan_principal_amount,
                'loan_released_date' => date('d/m/Y',strtotime($request->input('release_date'))),
                'loan_interest_method' => $loan->loan_interest_method,
                'loan_interest_type' => $loan->loan_interest_type,
                'loan_interest_period' => $loan->loan_interest_period,
                'loan_interest' => $loan->loan_interest,
                'loan_duration_period' => $loan->loan_duration_period,
                'loan_duration' => $loan->loan_duration,
                'loan_payment_scheme_id' => $loan->loan_payment_scheme_id,
                'loan_num_of_repayments' => $loan->loan_num_of_repayments,
                'loan_status_id' =>  $loan->loan_status_id,
                'loan_decimal_places' => 'round_off_to_two_decimal',
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
        $loan->loan_number = $resp['response']['loan_id'];
        $loan->loan_decimal_places = 'round_off_to_two_decimal';
        $loan->loan_status = 6;
        $loan->next_payment = $request->input('next_payment');
        $loan->loan_released_date = $request->input('release_date');
        $loan->save();

        return redirect()->back()->with('success','Loan uploaded to Loan Disk successfully.');

    }

    public function newDevLoans(){
        $loans = DB::table('device_loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('deviceloans.new-deviceloans', compact('loans'));
    }

    public function loansUnderKycCheck(){
        $loans = DB::table('device_loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->whereIn('l.loan_status',array(3,4))
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('deviceloans.kyccheck-loan', compact('loans'));
    }

    public function getLoansToSendToLd(){
        $loans = DB::table('device_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.loan_interest', 'l.paybackPeriod', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
            ->where('l.loan_status','=', 5)
            //->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('deviceloans.initialize-deviceloans', compact('loans'));

    }

    public function devicesToEnroll(){
        $loans = DB::table('device_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.interestRate', 'l.paybackPeriod', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
            ->where('l.loan_status','=', 6)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('deviceloans.to-enroll-paytrigger', compact('loans'));
    }

    public function devicesEnrolledOnPayTrigger(){
        $loans = DB::table('device_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.interestRate', 'l.paybackPeriod', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
            ->where('l.loan_status','=', 7)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('deviceloans.enrolled-paytrigger', compact('loans'));
    }

    public function myAgentDeviceLoans(){
        $loans = DB::table('device_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.interestRate', 'l.paybackPeriod', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
            ->where('l.partner_id','=', auth()->user()->id)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('deviceloans.agent-loans', compact('loans'));
    }

    public function newAgentDeviceLoan(){
        $bank = Bank::where('locale_id', auth()->user()->locale)->get();
        $products = Product::all();
        $partner = User::where('id', auth()->user()->id)->first();

        $clients = DB::table('clients as c')
            ->join('kycs as k', 'k.natid', '=', 'c.natid')
            ->join('users as u', 'u.natid','=','k.natid')
            ->select('u.id as user_id','c.id','c.first_name','c.last_name','c.cred_limit','c.natid','c.fsb_score','c.fsb_status','c.fsb_rating')
            ->where('k.bank','!=',null)
            ->where('c.locale_id','=',auth()->user()->locale)
            ->get();

        return view('deviceloans.apply-deviceloan-client', compact('bank','products','partner','clients'));

    }

    public function createPartnerDeviceLoan(Request $request){
        if ($request->input('amount') > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above your credit limit.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $client = Client::where('id',$request->input('client_id'))->firstOrFail();
        $userReco = User::where('natid', $client->natid)->first();
        $yuser = Kyc::where('natid', $userReco->natid)->first();
        $device = Product::where('id', $request->input('device'))->first();
        $bank = Bank::where('id', $yuser->bank)->first();
        $request->merge([
            'client_id' => $client->id,
        ]);

        $checkLoan = DeviceLoan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',11)
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'client_id'            => 'required',
                'amount'              => 'required',
                'paybackPeriod'             => 'required',
                'loan_interest'                  => 'required',
                'monthly'                  => 'required',
                //'disbursed'                  => 'required',
                //'appFee'                  => 'required',
                //'charges'                  => 'required',
                'device'                  => 'required',
            ],
            [
                'client_id.required' => 'Please make sure you\'re logged in.',
                'amount.required'         => 'The amount for the loan is needed.',
                'paybackPeriod.required'         => 'How long are you planning on paying back this loan?',
                'loan_interest.required'         => 'What is the proposed loan rate?.',
                'monthly.required'         => 'What are the proposed loan repayment amounts?',
                //'disbursed.required'         => 'What is the proposed amount to be received by you?',
                //'appFee.required'   => 'What is the application fee?',
                //'charges.required'       => 'What are the charges that come with processing this loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = DeviceLoan::create([
            'user_id'             => $userReco->id,
            'client_id'       => $request->input('client_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => 'www.eshagi.com',
            'loan_type'            => 1,
            'loan_status'            => 0,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate'      => $request->input('loan_interest'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => number_format(0.84*$request->input('amount'),2),
            'appFee'        => number_format(0.065*$request->input('amount'),2),
            'charges'        => number_format(0.16*$request->input('amount'),2),
            'notes'        => "Loan disk product ID: ".$request->input('device'),
            'locale'        => auth()->user()->locale,
            'device'        =>  $request->input('device'),
            'loan_product_id'        => $request->input('device'),
            'loan_disbursed_by_id'        => $request->input('loan_disbursed_by_id'),
            'loan_principal_amount'        => $request->input('amount'),
            'loan_released_date'        => $request->input('loan_released_date'),
            'loan_interest_method'        => $request->input('loan_interest_method'),
            'loan_interest_type'        => 'percentage',
            'loan_interest_period'        => $request->input('loan_interest_period'),
            'loan_interest'        => $request->input('loan_interest'),
            'loan_duration_period'        => $request->input('loan_duration_period'),
            'loan_duration'        => $request->input('paybackPeriod'),
            'loan_payment_scheme_id'        => $request->input('loan_payment_scheme_id'),
            'loan_num_of_repayments'        => $request->input('loan_num_of_repayments'),
            'loan_status_id'        => $request->input('loan_status_id'),
            'loan_decimal_places'        => $loan->loan_decimal_places ?? 'round_off_to_two_decimal',
            'loan_description'        => "Loan disk product ID: ".$request->input('device'),
            'cf_11133_approval_date'        => $request->input('cf_11133_approval_date'),
            'cf_11353_installment'        => $request->input('monthly'),
            'cf_11130_sales_rep'        => auth()->user()->name,
            'cf_11132_qty'        => 1,
            'cf_11136_account_num'        => $yuser->acc_number,
            'cf_11134_bank'        => $bank->bank,
            'cf_11135_branch'        => $yuser->branch,
        ]);
        $loan->save();

        if ($loan->save()) {
//            $newlimit = $client->cred_limit-$request->input('amount');
//            DB::table('clients')
//                ->where('id',$request->input('client_id'))
//                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }
        return redirect('getdeviceloaninfo/'.$loan->id.'/'.$yuser->id)->with('success', 'Please avail credit agreement for your client, to begin processing it.');
    }

    public function devClientLoanInfoSignature($id, $kycInfo){
        $loan =DeviceLoan::findOrFail($id);
        $yuser = Kyc::findOrFail($kycInfo);

        return view('deviceloans.sign-for-client', compact('loan', 'yuser'));
    }

    public function devLoanInfoSignature(){

    }

    public function loanInfoSignature ($id) {

    }

    function uploadDeviceClientSignature(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'loan_id'  => 'required',
                //'otp'  => 'required',
            ],
            [
                'signature.required'       => 'Your signature picture is required.',
                'signature.max'                 => 'Signature should not be greater than 4MB.',
                'signature.mimes'               => 'Signature should of the format: jpeg,png,jpg,gif,svg.',
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                //'otp.required' => 'OTP from client is need as consent from client to apply for the loan.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {
                $loan = DeviceLoan::where('id', $request->input('loan_id'))->first();
                $client = Client::where('id', $loan->client_id)->first();

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
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect('my-agent-device-loans')->with('success','Loan application has been submitted for processing.');

    }

    public function completeDeviceLoanForClient(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
                //'otp'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                //'otp.required' => 'OTP from client is need as consent from client to apply for the loan.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = DeviceLoan::where('id',$request->input('loan_id'))->first();

        $loan->loan_status = 1;
        $loan->save();

        return redirect('my-agent-device-loans')->with('success','Client loan application has been submitted for processing.');

    }

    public function gettingLockParameters($id){
        $loan = DeviceLoan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $partner = User::where('id', $loan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();

        if (is_null($agent)){
            $repInfo = null;
            $merchant = null;
            return view('deviceloans.set-lock-parameters', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
        }

        if ($agent->utype == 'Representative'){
            $repInfo = Representative::where('natid', $agent->natid)->first();
            $merchant = Partner::where('id',$repInfo->partner_id)->first();
        } else {
            $repInfo = null;
            $merchant = null;
        }

        return view('deviceloans.set-lock-parameters', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));
    }


}
