<?php

namespace App\Http\Controllers;

use App\Events\LONewLoanApproval;
use App\Models\Bank;
use App\Models\Batch;
use App\Models\Client;
use App\Models\Commission;
use App\Models\Kyc;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\OrderItem;
use App\Models\Partner;
use App\Models\MaterialsGroup;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Representative;
use App\Models\SsbDetail;
use App\Models\User;
use App\Notifications\LoanPaymentNotReceived;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class LoanController extends Controller
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
            $loans = DB::table('loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.deleted_at','=', null)
                ->get();
        } else {
            $loans = DB::table('loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.locale','=', auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->get();
        }

        return view('loans.loans', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $loan = Loan::where('user_id', auth()->user()->id)
        ->where('loan_status','<',13)
        ->get();
        $loanCount = count($loan);
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

        if($orderId = request()->get('id')){
            $total = OrderItem::where('order_id', $orderId)
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->sum(DB::raw('products.price * order_items.quantity'));

            return view('loans.apply-loan', compact('user', 'bank', 'yuser', 'total'));

        }

        if($loanCount===0){
            return view('loans.apply-loan', compact('user', 'bank','yuser'));
        }else {
            return view('loans.can-not-apply', compact('user'));
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        if ($request->input('amount') > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        if ($client->emp_sector == 'Government') {
            if ($client->reds_number != null AND $yuser->sign_stat == true) {
                $state = 8;
            } else{
                $state = 0;
            }
        } else {
            $state = 0;
        }

        $request->merge([
            'client_id' => $client->id,
        ]);

        $checkLoan = Loan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',13)
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        if($request->input('loan_type')==1){
            $validator = Validator::make(
                $request->all(),
                [
                    'user_id'                  => 'required',
                    'client_id'            => 'required',
                    'partner_id'             => 'nullable',
                    'channel_id'                 => 'required',
                    'loan_type'                 => 'required',
                    'amount'              => 'required',
                    'paybackPeriod'             => 'required',
                    'interestRate'                  => 'required',
                    'monthly'                  => 'required',
                    'disbursed'                  => 'required',
                    'charges'                  => 'required',
                ],
                [
                    'user_id.required'       => 'Please make sure you\'re logged in.',
                    'client_id.required' => 'Please make sure you\'re logged in.',
                    'channel_id.required'  => 'How was this loan applied?',
                    'loan_type.required'      => 'Please advise the type of loan',
                    'amount.required'         => 'The amount for the loan is needed.',
                    'paybackPeriod.required'         => 'How long are you planning on paying back this loan?',
                    'interestRate.required'         => 'What is the proposed loan rate?.',
                    'monthly.required'         => 'What are the proposed loan repayment amounts?',
                    'disbursed.required'         => 'What is the proposed amount to be received by you?',
                    'charges.required'       => 'What are the charges that come with processing this loan?',
                ]
            );
        } else{
            $validator = Validator::make(
                $request->all(),
                [
                    'user_id'                  => 'required',
                    'client_id'            => 'required',
                    'partner_id'             => 'nullable',
                    'channel_id'                 => 'required',
                    'loan_type'                 => 'required',
                    'amount'              => 'required',
                    'paybackPeriod'             => 'required',
                    'interestRate'                  => 'required',
                    'managementRate' => 'required',
                    'monthly'                  => 'required',
                    'disbursed'                  => 'required',
                    'managementFee'                  => 'required',
                    'charges'                  => 'required',
                ],
                [
                    'user_id.required'       => 'Please make sure you\'re logged in.',
                    'client_id.required' => 'Please make sure you\'re logged in.',
                    'channel_id.required'  => 'How was this loan applied?',
                    'loan_type.required'      => 'Please advise the type of loan',
                    'amount.required'         => 'The amount for the loan is needed.',
                    'paybackPeriod.required'         => 'How long are you planning on paying back this loan?',
                    'interestRate.required'         => 'What is the proposed loan rate?.',
                    'managementRate.required'         => 'What is the proposed management rate?.',
                    'monthly.required'         => 'What are the proposed loan repayment amounts?',
                    'disbursed.required'         => 'What is the proposed amount to be received by you?',
                    'managementFee.required'   => 'What is the management fee?',
                    'charges.required'       => 'What are the charges that come with processing this loan?',
                ]
            );
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->input('loan_type')==1){
            $mf = 0;
            $mr = 0;
            $pd = $request->input('prod_descrip');
            $pp = $request->input('amount');
        }else{
            $mf = $request->input('managementFee');
            $mr = $request->input('managementRate');
            $pd = NULL;
            $pp = NULL;
        }

        $loan = Loan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => $request->input('partner_id'),
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate'      => $request->input('interestRate'),
            'managementRate'      => $mr,
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            'managementFee'        => $mf,
            'charges'        => $request->input('charges'),
            'product'        => $pd,
            'pprice'        => $pp,
            'notes'        => $request->input('prod_descrip'),
            'locale'        => auth()->user()->locale,
        ]);
        $loan->save();

        if ($loan->save()) {
            event(new LONewLoanApproval($loan));
        }
        return redirect('sign-loan/'.$loan->id.'/'.$yuser->id)->with('success', 'Please sign your loan to begin processing it.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        $client = Client::where('id', $loan->client_id)->first();
        $partner = User::where('id', $loan->partner_id)->first();
        $agent = User::where('name', $client->creator)->first();
        // if ($agent->utype == 'Representative'){
        //     $repInfo = Representative::where('natid', $agent->natid)->first();
        //     $merchant = Partner::where('id',$repInfo->partner_id)->first();
        // } else {
        //     $repInfo = null;
        //     $merchant = null;
        // }

        //return view('loans.loan-info', compact('loan', 'client', 'partner','agent', 'repInfo', 'merchant'));

        return view('loans.loan-info', compact('loan', 'client'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        $user = Client::where('id', $loan->client_id)->first();
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

        return view('loans.edit-loan', compact('loan','user','yuser','bank','products','sysuser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        $client = Client::findOrFail($loan->client_id);
        $settings = Masetting::find(1)->first();
        if ($loan->locale == 1 AND $client->emp_sector == 'Government'){
            if ($loan->loan_status >= 10){
                return redirect()->back()->with('error', 'You cannot edit a loan after it has been sent to its current state.');
            }
        } elseif ($loan->locale == 1 AND $client->emp_sector == 'Private'){
            if ($loan->loan_status >= 3){
                return redirect()->back()->with('error', 'You cannot edit a loan after it has been sent to its current state.');
            }
        } elseif ($loan->locale == 2){
            if ($loan->loan_status >= 17){
                return redirect()->back()->with('error', 'You cannot edit a loan after it has been issued.');
            }
        }

        if ($loan->loan_status == 14){
            return redirect()->back()->with('error', 'You cannot edit a loan after it has been paid off.');
        }

        if (number_format($request->input('amount'),2,'.','') > number_format($settings->creditRate*$client->salary,2,'.','')){
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
                'paybackPeriod'             => 'required',
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

        $loan->loan_type = $request->input('loan_type');
        $loan->amount = $request->input('amount');
        $loan->paybackPeriod = $request->input('paybackPeriod');
        $loan->interestRate = $request->input('interestRate');
        $loan->monthly = $request->input('monthly');
        $loan->disbursed = $request->input('disbursed');
        $loan->appFee = $request->input('appFee');
        $loan->charges = $request->input('charges');
        $loan->product = $request->input('product');
        $loan->pprice = $request->input('pprice');
        $loan->dd_approval_ref = $request->input('dd_approval_ref');
        $loan->ndasendaBatch = $request->input('ndasendaBatch');
        $loan->ndasendaRef1 = $request->input('ndasendaRef1');
        $loan->ndasendaRef2 = $request->input('ndasendaRef2');
        $loan->ndasendaState = $request->input('ndasendaState');
        $loan->ndasendaMessage = $request->input('ndasendaMessage');
        $loan->notes = $request->input('notes');

        $loan->save();

        if ($loan->save()) {
            $newlimit = $client->cred_limit-$request->input('amount');
            DB::table('clients')
                ->where('id',$client->id)
                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Loan details have been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        if ($loan->loan_status > 1){
            return redirect()->back()->with('error', 'You cannot delete a loan that has been actioned.');
        } else{
            $loan->delete();
        }

        return redirect('loans')->with('success', 'Loan deleted successfully.');
    }

    public function getSignature($loanId, $kycInfo){
        $loan = Loan::findOrFail($loanId);
        $yuser = Kyc::findOrFail($kycInfo);

        return view('loans.sign-my-loan', compact('loan','yuser'));
    }

    public function getClientSignature($loanId, $kycInfo){
        $loan = Loan::findOrFail($loanId);
        $yuser = Kyc::findOrFail($kycInfo);
        $settings = Masetting::find(1)->first();
        return view('loans.sign-for-client', compact('loan','yuser', 'settings'));
    }

    public function getClientUnsignedSignature($loanId, $kycInfo){
        $loan = Loan::findOrFail($loanId);
        $yuser = Kyc::findOrFail($kycInfo);
        $client = Client::where('natid', $yuser->natid)->first();
        /*$otp = mt_rand(100000, 999999);
       $getOtp = Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263".$client->mobile."&msg=eShagi OTP: Your requested pin is: ".$otp.". Regards, eShagi.")
           ->body();

       $json = json_decode($getOtp, true);
       $status = $json['data'][0]['status'];
       if ($status == 'OK') {
           DB::table("clients")
               ->where("natid", $client->natid)
               ->update(['otp' => Hash::make($otp), 'updated_at' => now()]);
       }*/

        return view('loans.sign-for-client', compact('loan','yuser'));
    }

    public function getMyLoans(){
        $loans = Loan::where('user_id','=', auth()->user()->id)->get();

        $monthlies = $loans->sum("monthly");

        return view('loans.my-loans', compact('loans', 'monthlies'));
    }

    public function loanInfoSignature ($id) {
        $loan =Loan::findOrFail($id);
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        return view('loans.sign-my-loan', compact('loan', 'yuser'));
    }

    function uploadSignature(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'loan_id'  => 'required',
                'tnc'  => 'required',
            ],
            [
                'signature.required'       => 'Your signature picture is required.',
                'signature.max'                 => 'Signature should not be greater than 4MB.',
                'signature.mimes'               => 'Signature should of the format: jpeg,png,jpg,gif,svg.',
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                'tnc.required' => 'Please make sure you agree to terms and conditions.',
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
                    $loan = Loan::where('id', $request->input('loan_id'))->first();
                    $client = Client::where('id', $loan->client_id)->first();

                    if ($client->emp_sector == 'Government') {
                        if ($client->reds_number != null) {
                            $loan->loan_status = 8;
                        } else{
                            $loan->loan_status = 1;
                        }
                    } elseif($client->emp_sector == 'Zambian Military') {
                        $loan->loan_status = 15;
                    }else {
                        $loan->loan_status = 1;
                    }

                    $loan->save();
                }
            } else {
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect(route('list.myloans'))->with('success','Your loan application has been submitted for processing.');
    }

    function uploadClientSignature(Request $request) {
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
                $loan = Loan::where('id', $request->input('loan_id'))->first();
                $client = Client::where('id', $loan->client_id)->first();
                //if (Hash::check($request->input('otp'), $client->otp)) {

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
                        if ($client->emp_sector == 'Government') {
                            if ($client->reds_number != null) {
                                $loan->loan_status = 8;
                            } else{
                                $loan->loan_status = 1;
                            }
                        } else {
                            $loan->loan_status = 1;
                        }

                        $loan->save();
                    }
//                } else {
//                    $yuser = Kyc::where('natid', $client->natid)->first();
//
//                    return view('loans.sign-for-client', compact('loan', 'yuser'))->with('error', 'Invalid OTP provided, please check your provided OTP.');
//
//                }
            } else {
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect(route('partner.loans'))->with('success','Loan application has been submitted for processing.');

    }

    public function completeLoan(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'loan_id'  => 'required',
                'tnc'  => 'required',
            ],
            [
                'loan_id.required' => 'Please make sure you\'re logged in and you followed the proper application process.',
                'tnc.required' => 'Please make sure you agree to terms and conditions.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = Loan::where('id',$request->input('loan_id'))->first();
        $client = Client::where('id', $loan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        if ($client->emp_sector == 'Government') {
            if ($client->reds_number != null AND $kyc->sign_stat) {
                $loan->loan_status = 8;
            } else{
                $loan->loan_status = 1;
            }
        } else {
            $loan->loan_status = 1;
        }
        $loan->save();

        return redirect(route('list.myloans'))->with('success','Your loan application has been submitted for processing.');

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

        $loan = Loan::where('id',$request->input('loan_id'))->first();
        $client = Client::where('id', $loan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        if (Hash::check($request->input('otp'), $client->otp)) {
            if ($client->emp_sector == 'Government') {
                if ($client->reds_number != null AND $kyc->sign_stat) {
                    $loan->loan_status = 8;
                } else{
                    $loan->loan_status = 1;
                }
            } else {
                $loan->loan_status = 1;
            }
            $loan->save();
        } else {
            $yuser = Kyc::where('natid', $client->natid)->first();

            $data = [
                'loan' => $loan,
                'yuser' => $yuser,
                'settings' => $settings,
                'error' => 'Invalid OTP provided, please check your provided OTP.'
            ];

            return view('loans.sign-for-client', compact('loan', 'yuser'))->with('error', 'Invalid OTP provided, please check your provided OTP.');
        }

        return redirect(route('partner.loans'))->with('success','Your loan application has been submitted for processing.');
    }

    public function getPartnerLoans() {
        $partner = User::where('id', auth()->user()->id)->first();

        /*if (auth()->user()->hasRole('representative')){
            $rep = Representative::where('natid', auth()->user()->natid)->first();
            $partner = User::where('name', $rep->creator)->first();
        }*/

        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.first_name','c.last_name','l.loan_type','l.amount','l.disbursed','l.product','l.loan_status','l.created_at')
            ->where('l.partner_id','=', $partner->id)
            ->get();

        return view('loans.partner-loans', compact('loans', 'partner'));
    }

    public function newPartnerLoan(){
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


        return view('loans.new-partner-loan', compact('clients', 'partner'));
    }

    public function postPartnerLoan(Request $request) {

        if ($request->input('amount') > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $checkLoan = Loan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',13)
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
                $state = 8;
            } else{
                $state = 0;
            }
        } else {
            $state = 0;
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
                'paybackPeriod'         => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'disbursed'                  => 'required',
                //'appFee'                  => 'required',
                'charges'                  => 'required',
            ],
            [
                'user_id.required'       => 'Please make sure you\'re logged in.',
                'client_id.required' => 'Please make sure you\'re logged in.',
                'channel_id.required'  => 'How was this loan applied?',
                'loan_type.required'      => 'Please advise the type of loan',
                'amount.required'         => 'The amount for the loan is needed.',
                'paybackPeriod.required'         => 'How long are you planning on paying back this loan?',
                'interestRate.required'         => 'What is the proposed loan rate?.',
                'monthly.required'         => 'What are the proposed loan repayment amounts?',
                'disbursed.required'         => 'What is the proposed amount to be received by you?',
                //'appFee.required'   => 'What is the application fee?',
                'charges.required'       => 'What are the charges that come with processing this loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = Loan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate' => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            //'appFee'        => $request->input('appFee'),
            'charges'        => $request->input('charges'),
            'notes'        => $request->input('notes'),
            'locale'        => auth()->user()->locale,
        ]);

        $loan->save();

        $client = Client::where('id', $loan->client_id)->first();

        if ($loan->save()) {
            $otp = mt_rand(100000, 999999);
//            $getOtp = Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263".$client->mobile."&msg=eShagi OTP: Your requested pin is: ".$otp.". Regards, eShagi.")
//                ->body();
//
//            $json = json_decode($getOtp, true);
//            $status = $json['data'][0]['status'];

            event(new LONewLoanApproval($loan));

            //if ($status == 'OK') {
                DB::table("clients")
                    ->where("natid", $client->natid)
                    ->update(['otp' => Hash::make($otp),'updated_at' => now()]);
            //}

        }

        return redirect('sign-for-client/'.$loan->id.'/'.$yuser->id)->with('success', 'Please sign the loan for your client, to begin processing it.');
    }

    public function newPartnerCredit(){
        $partner = User::where('id', auth()->user()->id)->first();

        $clients = DB::table('clients as c')
            ->join('kycs as k', 'k.natid', '=', 'c.natid')
            ->join('users as u', 'u.natid','=','k.natid')
            ->select('u.id as user_id','c.id','c.first_name','c.last_name','c.cred_limit','c.natid','c.fsb_score','c.fsb_status','c.fsb_rating')
            ->where('k.bank','!=',null)
            ->where('c.locale_id','=',auth()->user()->locale)
            ->get();

        if (auth()->user()->hasRole('agent') || auth()->user()->hasRole('fielder') || auth()->user()->hasRole('salesadmin')|| auth()->user()->hasRole('astrogent')) {
            $products = Product::all();
        } else {
            $products = Product::where('creator', auth()->user()->name)->get();
        }

        //echo 853;
        //die();

        return view('loans.new-partner-credit',compact('partner', 'clients', 'products'));
    }

    public function postPartnerCreditLoan(Request $request) {
        /*if ($request->input('amount') > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }*/

        $checkLoan = Loan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',13)
            ->exists();

        if ($checkLoan){
            return redirect()->back()->with('error', 'A loan with the same details already exists, no need to recreate it.');
        }

        $client = DB::table('clients as c')
            ->join('users as u', 'u.natid','=','c.natid')
            ->select('u.id', 'c.emp_sector', 'c.reds_number', 'c.natid')
            ->where('c.id','=',$request->input('client_id'))
            ->first();

        $locale = Localel::findOrFail(auth()->user()->locale);
        $yuser = Kyc::where('natid', $client->natid)->first();

        if ($client->emp_sector == 'Government') {
            if ($client->reds_number != null AND $yuser->sign_stat == true) {
                $state = 8;
            } else{
                $state = 0;
            }
        } else {
            $state = 0;
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
                'prod_descrip'              => 'required',
                'paybackPeriod'         => 'required',
                'interestRate'                  => 'required',
                'monthly'                  => 'required',
                'disbursed'                  => 'required',
                //'appFee'                  => 'required',
                'charges'                  => 'required',
            ],
            [
                'user_id.required'       => 'Please make sure you\'re logged in.',
                'client_id.required' => 'Please make sure you\'re logged in.',
                'channel_id.required'  => 'How was this loan applied?',
                'loan_type.required'      => 'Please advise the type of loan',
                'amount.required'         => 'The amount for the loan is needed.',
                'prod_descrip.required'         => 'What is this loan used to purchase?',
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
        //echo auth()->user()->natid;
        $rep = Representative::where('natid', auth()->user()->natid)->first();
        $partner = Partner::findOrFail($rep->partner_id);
        /*echo "<pre>";
        print_r($partner);
        echo "</pre>";die();*/

        $loan = Loan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => $partner->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate' => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            'appFee'        => $request->input('appFee'),
            'charges'        => $request->input('charges'),
            'notes'        => $request->input('prod_descrip'),
            'product'        => $request->input('prod_descrip'),
            'pprice'        => $request->input('amount'),
            'locale'        => auth()->user()->locale,
        ]);

        $loan->save();

        $client = Client::where('id', $loan->client_id)->first();

        if ($loan->save()) {
            $otp = mt_rand(100000, 999999);

            $getOtp = Http::post("https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/9fdf069005cbfee64181ec5904e4a1a6/contacts/" . $locale->country_code.$client->mobile."/senderId/AstroCred/message/AstroCred OTP: Your pin for AstroCred Product Loan is: ".$otp.". Regards, AstroCred.")->body();
            //$getOtp = Http::post("https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/9fdf069005cbfee64181ec5904e4a1a6/contacts/260956021372/senderId/AstroCred/message/AstroCred OTP: Your pin for AstroCred Product Loan is: ".$otp.". Regards, AstroCred.")->body();


            $json = json_decode($getOtp);
            //$status = $json['data'][0]['status'];
            //print_r($json);die();

            if ($json->success) {
                event(new LONewLoanApproval($loan));

                DB::table("clients")
                    ->where("natid", $client->natid)
                    ->update(['otp' => Hash::make($otp), 'updated_at' => now()]);
            }

        }
        return redirect('sign-for-client/'.$loan->id.'/'.$yuser->id)->with('success', 'Please sign the loan for your client, to begin processing it.');
    }

    public function createCreditLoan(){
        $loan = Loan::where('user_id', auth()->user()->id)
        ->where('loan_status','<',13)
        ->get();
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
        $categories = Category::all();
        $products = Product::all();

        //return view('loans.apply-store-credit', compact('user', 'userInfo', 'partners', 'categories', 'products'));
        if(!empty($loan)){
            return view('loans.can-not-apply', compact('user'));
        }else {
            return view('loans.apply-store-credit', compact('user', 'userInfo', 'partners', 'categories', 'products'));
        }
    }

    public function getProductsByMerchant(Request $request){
        $merchantID = $request->merchantid;
        $catID = $request->catid;
        $merchantProducts = Product::where('partner_id', $merchantID)->where('product_category_id', $catID)->get();
        return response()->json($merchantProducts);
    }

    public function generateInstructionPdf($id) {
        $client = Client::findOrFail($id);

        $pdf = \PDF::loadView('loans.salary-deduction', compact('client'));
        return $pdf->stream("SalaryDeduction".$client->natid.".pdf");
    }

    public function getForm(){
        return view('partners.agreement-form');
    }

    public function loanCalculator(){
        return view('loans.loan-calculator');
    }

    public function getLoanAmortizationSchedule(){
        return view('loans.loan-amortization');
    }

    public function unSignedLoans() {
        if ((auth()->user()->hasRole('root') ||auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('group') || auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('salesadmin') )) {
            $loans = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->join('kycs as k', 'k.natid','=','c.natid')
                ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
                ->where('l.loan_status','=',0)
                ->where('l.locale','=',auth()->user()->locale)
                ->where('c.reds_number','=', null)
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
                ->where('c.reds_number','=', null)
                ->where('l.deleted_at','=', null)
                ->orderByDesc('l.created_at')
                ->get();
        }

        return view('loans.unsigned', compact('loans'));
    }

    public function newLoans() {
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('c.reds_number','=', null)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('loans.new-loans', compact('loans'));
    }

    public function getPendingPrivateLoans() {
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid','c.first_name','c.last_name','l.amount','l.monthly','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_type','l.created_at')
            ->whereIn('l.loan_status',array(2, 3, 4, 5, 6))
            ->where('c.emp_sector','=','Private')
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.pending-private', compact('loans'));
    }

    public function pendingLoans() {
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid','c.ecnumber','l.monthly','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_type','l.created_at')
            ->where('l.loan_status','=',8)
            ->where('c.emp_sector','=','Government')
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.pending-loans', compact('loans'));
    }

    public function approvedLoans() {
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_number','=',null)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.approved-loans', compact('loans'));
    }

    public function getDisbursedLoans () {
        $loans = DB::table('loans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_status','=',12)
            ->orWhere('l.loan_status','=',18)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.disbursed-loans', compact('loans'));
    }

    public function declinedLoans() {
        $loans = DB::table('loans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_status','=',13)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.declined-loans', compact('loans'));
    }

    public function loansPaidInFull() {
        $loans = DB::table('loans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_status','=',14)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.cleared-loans', compact('loans'));
    }

    public function getDynamicOfferLetterForClient($loanId,$id){
        $client = Client::where('id', $id)->first();
        $loan = Loan::where('id', $loanId)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        $signatureUrl = public_path('signatures/'.$kyc->sign_pic) ;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $type = pathinfo($signatureUrl, PATHINFO_EXTENSION);
        $signatureData = file_get_contents($signatureUrl, false, stream_context_create($arrContextOptions));
        $signatureBase64Data = base64_encode($signatureData);
        $encodedSignature = 'data:image/' . $type . ';base64,' . $signatureBase64Data;
        $settings = Masetting::find(1)->first();

        $pdf = \PDF::loadView('loans.offer-letter', compact('client', 'loan', 'kyc','encodedSignature','settings'));
        return $pdf->stream("OfferLetter".$client->natid.".pdf");
    }

    public function getOfferLetterForClient($loanId,$id){
        $client = Client::where('id', $id)->first();
        $loan = Loan::where('id', $loanId)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        $signatureUrl = public_path('signatures/'.$kyc->sign_pic) ;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $type = pathinfo($signatureUrl, PATHINFO_EXTENSION);
        $signatureData = file_get_contents($signatureUrl, false, stream_context_create($arrContextOptions));
        $signatureBase64Data = base64_encode($signatureData);
        $encodedSignature = 'data:image/' . $type . ';base64,' . $signatureBase64Data;
        $settings = Masetting::find(1)->first();

        $pdf = \PDF::loadView('loans.store-offer-letter', compact('client', 'loan', 'kyc','encodedSignature','settings'));

        Storage::disk('public')->put("offerletters/OfferLetter".$client->natid.".pdf",$pdf->output(), []) ;

        return redirect()->back()->with('success', 'Offer letter generated.');
    }

    public function getTyForClient($loanId,$id){
        $client = Client::where('id', $id)->first();
        $loan = Loan::where('id', $loanId)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        $signatureUrl = public_path('signatures/'.$kyc->sign_pic) ;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $type = pathinfo($signatureUrl, PATHINFO_EXTENSION);
        $signatureData = file_get_contents($signatureUrl, false, stream_context_create($arrContextOptions));
        $signatureBase64Data = base64_encode($signatureData);
        $encodedSignature = 'data:image/' . $type . ';base64,' . $signatureBase64Data;
        $settings = Masetting::find(1)->first();

        $pdf = \PDF::loadView('loans.store-ty-theti', compact('client', 'loan', 'kyc','encodedSignature','settings'));

        Storage::disk('public')->put("ty30/".$client->natid.".pdf",$pdf->output(), []) ;

        return redirect()->back()->with('success', 'Ty30 generated.');

    }

    public function getTyThetisForClient($loanId,$id){
        $client = Client::where('id', $id)->first();
        $loan = Loan::where('id', $loanId)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();

        $signatureUrl = public_path('signatures/'.$kyc->sign_pic) ;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $type = pathinfo($signatureUrl, PATHINFO_EXTENSION);
        $signatureData = file_get_contents($signatureUrl, false, stream_context_create($arrContextOptions));
        $signatureBase64Data = base64_encode($signatureData);
        $encodedSignature = 'data:image/' . $type . ';base64,' . $signatureBase64Data;
        $settings = Masetting::find(1)->first();

        $pdf = \PDF::loadView('loans.store-ty-theti', compact('client', 'loan', 'kyc','encodedSignature','settings'));
        return $pdf->stream("TY30 ".$client->natid.".pdf");
    }

    public function listCurrentOfferLetters(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','l.amount','l.monthly','l.loan_status','l.loan_type','l.created_at')
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->whereMonth('l.created_at', date('m'))
            ->whereYear('l.created_at', date('Y'))
            ->get();

        return view('loans.current-offer-letters', compact('loans'));
    }

    public function listAllOfferLetters(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->get();

        return view('loans.offer-letters', compact('loans'));
    }

    public function getPendingLoanInfo($id) {
        $loan = Loan::findOrFail($id);
        $client = Client::where('client_id',$loan->client_id)->first();

        return view('loans.pending-loan-info', compact('loan', 'client'));
    }

    public function uploadLoansToNdasendaAPI() {

        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid as idNumber','c.ecnumber as ecNumber','l.monthly','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_status','l.loan_type','l.created_at','c.first_name as name','c.last_name as surname')
            ->where('c.emp_sector','=','Government')
            ->where('l.loan_status','=', 8)
            ->where('l.deleted_at','=', null)
            ->get();

        if($loans->isEmpty()){
            return redirect()->back()->with('error', 'I did not pick any loans ready to submit to Ndasenda');
        }

        foreach ($loans as $loan) {
            $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
                'grant_type' => 'password',
                'username' => 'takunda@astroafrica.tech',
                'password' => 'Takunda16#'])
                ->body();
            $resp=json_decode($authDetails, TRUE);
            if(isset($resp['error'])){
                return redirect()->back()->with('error', 'An error occurred while authenticating eShagi with Ndasenda: '. $resp['error_description']);
            }

            $recordToSend = json_encode([
                "recordsCount" => 1,
                "totalAmount" => (int)($loan->monthly)*100,
                "securityToken" => "111111",
                "deductionCode" => "800081211",
                "records" => [["idNumber" => str_replace("-", "", $loan->idNumber),
                    "ecNumber" => $loan->ecNumber,
                    "type" => "NEW",
                    "reference" => "ES".$loan->id,
                    "startDate" => Carbon::parse($loan->created_at)->firstOfMonth()->addMonth(1)->format('d-m-Y'),
                    "endDate" => Carbon::parse($loan->created_at)->addMonths($loan->paybackPeriod)->endOfMonth()->format('d-m-Y'),
                    "name" => $loan->name,
                    "surname" => $loan->surname,
                    "amount" => (int)($loan->monthly)*100,
                    "totalAmount" => (int)($loan->monthly)*100]]
            ], TRUE);

            $details = Http::withToken($resp['access_token'])
                ->withHeaders(['Content-Type' => 'application/json',
                    'Accept ' => 'application/json'])
                ->withBody($recordToSend, 'application/json')
                ->post('https://demo.ndasenda.co.zw/api/v1/deductions/requests/')
                ->body();

            $resp=json_decode($details, TRUE);

            if(isset($resp['error'])){
                return redirect()->back()->with('error', 'An error occurred: '. $resp['error']);
            }

            $currentBatch = Batch::where('batchid', $resp['id'])->exists();

            if ($currentBatch) {
                DB::table('batches')
                    ->where("batchid", $resp['id'])
                    ->update(['recordsCount' => $resp['recordsCount'],'totalAmount' => $resp['totalAmount'],'status'=>$resp['status'], 'updated_at' => now()]);

            } else {
                $ndasendaBatch = Batch::create([
                    'batchid' => $resp['id'],
                    'recordsCount' => $resp['recordsCount'],
                    'totalAmount' => $resp['totalAmount'],
                    'deductionCode' => $resp['deductionCode'],
                    'status' => $resp['status'],
                    'creationDate' => $resp['creationDate'],
                    'records' => json_encode($resp['records']),
                ]);
                $ndasendaBatch->save();
            }

            DB::table('loans')
                ->where('id','=', $loan->id)
                ->update(['loan_status' => 9,'dd_approval'=>true,'ndasendaBatch'=> $resp['id'], 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Ndasenda batch update was successful.');
    }

    /*public function uploadLoansToNdasendaAPI() {
        $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
            'grant_type' => 'password',
            'username' => 'takunda@astroafrica.tech',
            'password' => 'Takunda16#'])
            ->body();
        $authResp=json_decode($authDetails, TRUE);

        if(isset($authResp['error'])){
            return redirect()->back()->with('error', 'An error occurred while authenticating eShagi with Ndasenda: '. $authResp['error_description']);
        }

        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid as idNumber','c.ecnumber as ecNumber','l.monthly','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_status','l.loan_type','l.created_at','c.first_name as name','c.last_name as surname')
            ->where('c.emp_sector','=','Government')
            ->where('l.loan_status','=', 8)
            ->get();

        $loansToUpdate = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid as idNumber','c.ecnumber as ecNumber','l.monthly','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_status','l.loan_type','l.created_at','c.first_name as name','c.last_name as surname')
            ->where('c.emp_sector','=','Government')
            ->where('l.loan_status','=', 8)
            ->pluck('id');

        if($loans->isEmpty()){
            return redirect()->back()->with('error', 'I did not pick any loans ready to submit to Ndasenda');
        }

        $newContent = array();
        foreach ($loans as $loan) {
            $newContent[] = array("idNumber" => str_replace("-", "", $loan->idNumber),
                "ecNumber" => $loan->ecNumber,
                "type" => "NEW",
                "reference" => "ES".$loan->id,
                "startDate" => Carbon::parse($loan->created_at)->firstOfMonth()->addMonth(1)->format('d-m-Y'),
                "endDate" => Carbon::parse($loan->created_at)->addMonths($loan->paybackPeriod)->endOfMonth()->format('d-m-Y'),
                "name" => $loan->name,
                "surname" => $loan->surname,
                "amount" => number_format(($loan->monthly)*100, 0,'','' ),
                "totalAmount" => number_format(($loan->monthly)*100, 0,'',''),
            );
        }

        $recordToSend = json_encode([
            "recordsCount" => $loans->count(),
            "totalAmount" => number_format($loans->sum('monthly')*100, 0,'',''),
            "securityToken" => "111111",
            "deductionCode" => "800081211",
            "records" => $newContent
        ], TRUE);


        $details = Http::withToken($authResp['access_token'])
            ->withHeaders(['Content-Type' => 'application/json',
                'Accept ' => 'application/json'])
            ->withBody($recordToSend, 'application/json')
            ->post('https://demo.ndasenda.co.zw/api/v1/deductions/requests/')
            ->body();

        $resp=json_decode($details, TRUE);
dd($resp);
        if(isset($resp['error'])){
            return redirect()->back()->with('error', 'An error occurred: '. $resp['error']);
        }

        $currentBatch = Batch::where('batchid', $resp['id'])->exists();

        if ($currentBatch) {
            DB::table('batches')
                ->where("batchid", $resp['id'])
                ->update(['recordsCount' => $resp['recordsCount'],'totalAmount' => $resp['totalAmount'],'status'=>$resp['status'], 'updated_at' => now()]);

        } else {
            $ndasendaBatch = Batch::create([
                'batchid' => $resp['id'],
                'recordsCount' => $resp['recordsCount'],
                'totalAmount' => $resp['totalAmount'],
                'deductionCode' => $resp['deductionCode'],
                'status' => $resp['status'],
                'creationDate' => $resp['creationDate'],
                'records' => json_encode($resp['records']),
            ]);
            $ndasendaBatch->save();
        }

        DB::table('loans')
            ->whereIn('id',$loansToUpdate->toArray())
            ->update(['loan_status' => 9,'dd_approval'=>true,'ndasendaBatch'=> $resp['id'], 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Ndasenda batch creation was successful.');
    }*/

    public function sendLoansNdasenda() {
        $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
            'grant_type' => 'password',
            'username' => 'takunda@astroafrica.tech',
            'password' => 'Takunda16#'])
            ->body();
        $resp=json_decode($authDetails, TRUE);

        $details = Http::withToken($resp['access_token'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Access' => '*/*',
                'accept ' => 'text/plain'])
            ->post('https://demo.ndasenda.co.zw/api/v1/deductions/responses/REQ20050058')
            ->body();
        dd($details);

    }

    public function getAllLoanLoanDetails($id) {
        $loan = Loan::findOrFail($id);

        $client = Client::where('id', $loan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bank = Bank::where('id',$kyc->bank)->first();

        $loans = Loan::where('client_id', $client->id)->take(10)->orderBy('created_at', 'DESC')->get();

        $ssbInfo = SsbDetail::where('natid', $client->natid)->first();

        return view('loans.loan-details', compact('loan', 'client', 'kyc', 'loans', 'ssbInfo','bank'));
    }

    public function postLoanToRedSphere($id) {

        $loan = Loan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bank = Bank::where('id',$kyc->bank)->first();
        $settings = Masetting::find(1)->first();
        $creator = User::where('name',$client->creator)->first();

//        if ($kyc->cbz_status == 0 AND $kyc->cbz_evaluator == null) {
//            return redirect()->back()->with('error', 'Seems as if CBZ have not yet approved the client KYC information. Please wait for them to approve.');
//        }

        if ($client->reds_number == null) {
            return redirect()->back()->with('error', 'Seems I did not record this client\'s RedSphere number or you might have forgotten to upload their KYC to RedSphere.');
        }

//        elseif ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s FCB status just verify the details are there.');
//        }

        if($loan->loan_status == 0){
            return redirect()->back()->with('error', 'Loan has not yet been signed. Please make sure it has been signed.');
        }

        if ($client->fsb_status == 'ADVERSE') {
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 13, 'notes' => 'Loan declined because of Open adverse item(s).', 'updated_at' => now()]);

            Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Good day ".$client->first_name.", Your loan application of $".$loan->amount." has been declined. You have other running loan(s).")
                ->body();

            return redirect()->back()->with('error', 'The client has open adverse item(s) and will not be able to borrow at this time. The loan has been declined and notified the client.');
        }

        $signatureUrl = public_path('signatures/'.$kyc->sign_pic) ;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $type = pathinfo($signatureUrl, PATHINFO_EXTENSION);
        $signatureData = file_get_contents($signatureUrl, false, stream_context_create($arrContextOptions));
        $signatureBase64Data = base64_encode($signatureData);
        $encodedSignature = 'data:image/' . $type . ';base64,' . $signatureBase64Data;

        $pdf = \PDF::loadView('loans.store-offer-letter', compact('client', 'loan', 'kyc','encodedSignature','settings'));

        Storage::disk('public')->put("offerletters/OfferLetter".$client->natid.".pdf",$pdf->output(), []) ;

        $offerLetterPath = public_path('offerletters/OfferLetter'.$client->natid.'.pdf');

        $day = date("Y-m-d");
        $time = date ("H:i:s");
        $fromToday= $day."T".$time."Z";

        $ch = curl_init("192.168.145.54");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode !== 0){
            if ($loan->loan_type == 1){
                if ($creator->utype != 'System'){
                    if ($creator->utype == 'Representative'){
                        $rep = Representative::where('natid', $creator->natid)->first();
                        $partner = Partner::where('id',$rep->partner_id)->first();
                    } elseif ($creator->utype == 'Partner') {
                        $partner = Partner::where('regNumber',$creator->natid)->first();
                    } else {
                        return redirect()->back()->with('error', 'Failed to get the partner banking details');
                    }
                $bank = Bank::where('id',$partner->bank)->first();
                $details = Http::post('http://192.168.145.54/cbzapi/api/SaveLoan/PostNewLoan',[
                    'CUSTOMER_NUMBER' => $client->reds_number,
                    'Currency' => 'ZWL',
                    'GUARANTOR_NAME' => $client->first_name.' '.$client->last_name,
                    'GUARANTOR_DOB' => date_format($client->dob, 'Y-m-d'),
                    'GUARANTOR_IDNO' => str_replace("-", "", $client->natid),
                    'GUARANTOR_PHONE' => $client->mobile,
                    'GUARANTOR_ADD' => $client->house_num.' '.$client->street.' '.$client->surburb,
                    'GUARANTOR_CITY' => $client->city,
                    'GUARANTOR_HOME_TYPE' => $client->home_type,
                    'GUARANTOR_RENT' => null,
                    'GUARANTOR_HOME_LENGTH' => null,
                    'GUARANTOR_EMPLOYER' => $client->employer,
                    'GUARANTOR_EMP_ADD' => $client->city,
                    'GUARANTOR_EMP_LENGTH' => '5',
                    'GUARANTOR_EMP_PHONE' => '0552587963',
                    'GUARANTOR_EMP_EMAIL' => 'niachos@zimasco.co.zw',
                    'GUARANTOR_EMP_FAX' => null,
                    'GUARANTOR_EMP_POSTN' => null,
                    'GUARANTOR_EMP_SALARY' => $client->gross,
                    'GUARANTOR_EMP_INCOME' => $client->salary,
                    'GUARANTOR_REL_NAME' => $kyc->kin_fname.' '.$kyc->kin_lname,
                    'GUARANTOR_REL_ADD' => null,
                    'GUARANTOR_REL_CITY' => null,
                    'GUARANTOR_REL_PHONE' => $kyc->kin_number,
                    'GUARANTOR_REL_RELTNSHP' => $client->marital_state,
                    'FIN_AMT' => $loan->amount,
                    'FIN_TENOR' => $loan->paybackPeriod,
                    'FIN_INT_RATE' => $loan->interestRate,
                    'FIN_ADMIN' => 4,
                    'FIN_PURPOSE' => 'Consumption',
                    'FIN_SRC_REPAYMT' => 'BUSINESS AND SALARY',
                    'FIN_SEC_OFFER' => "",
                    'FIN_REPAY_DATE' => Carbon::parse($fromToday)->addMonth(1)->endOfMonth()->format('Y-m-d'),
                    //'FIN_REPAY_DATE' => Carbon::parse($loan->created_at)->addMonth(1)->addMonths($loan->paybackPeriod)->endOfMonth()->format('Y-m-d'),
                    'QUES_HOW' => 'Others',
                    'QUES_EMPLOYEE' => "",
                    'QUES_AGENT' => "",
                    'APPL_SIGNATURE' => null,
                    'JOINT_APPL_SIGNATURE' => null,
                    'RECOMMENDED_AMT' => $loan->amount,
                    'INT_RATE' => $settings->interest,
                    'INSURANCE_RATE' => null,
                    'ADMIN_RATE' => 4,
                    'ASSET_NAME' => null,
                    'PDACode' => null,
                    'LO_ID' => '42',
                    'FinProductType' => '4',
                    'Sector' => 'DISTRIBUTION',
                    'RepaymentIntervalNum' => 1,
                    'RepaymentIntervalUnit' => 'Months',
                    'Bank' => $bank->bank_short,
                    'BankBranch' => $partner->branch_code,
                    'BankAccountNo' => $partner->acc_number,
                    'CurrBorrowings' => 'Not borrowed',
                    'PrevBorrowings' => 'nill',
                    'ndasendaBatch' => $loan->ndasendaBatch,
                    'ndasendaRef1' => $loan->ndasendaRef1,
                    'ndasendaRef2' => $loan->ndasendaRef2,
                    'ndasendaStatus' => $loan->ndasendaState,
                    'fcbStatus' => $client->fsb_status,
                    'fcbMessage' => $client->fsb_rating,
                    'fcbScore' => $client->fsb_score,
                    'OfferLetter' => $offerLetterPath
                ])
                    ->body();
                } else {
                    $details = Http::post('http://192.168.145.54/cbzapi/api/SaveLoan/PostNewLoan',[
                        'CUSTOMER_NUMBER' => $client->reds_number,
                        'Currency' => 'ZWL',
                        'GUARANTOR_NAME' => $client->first_name.' '.$client->last_name,
                        'GUARANTOR_DOB' => date_format($client->dob, 'Y-m-d'),
                        'GUARANTOR_IDNO' => str_replace("-", "", $client->natid),
                        'GUARANTOR_PHONE' => $client->mobile,
                        'GUARANTOR_ADD' => $client->house_num.' '.$client->street.' '.$client->surburb,
                        'GUARANTOR_CITY' => $client->city,
                        'GUARANTOR_HOME_TYPE' => $client->home_type,
                        'GUARANTOR_RENT' => null,
                        'GUARANTOR_HOME_LENGTH' => null,
                        'GUARANTOR_EMPLOYER' => $client->employer,
                        'GUARANTOR_EMP_ADD' => $client->city,
                        'GUARANTOR_EMP_LENGTH' => '5',
                        'GUARANTOR_EMP_PHONE' => '0552587963',
                        'GUARANTOR_EMP_EMAIL' => 'niachos@zimasco.co.zw',
                        'GUARANTOR_EMP_FAX' => null,
                        'GUARANTOR_EMP_POSTN' => null,
                        'GUARANTOR_EMP_SALARY' => $client->gross,
                        'GUARANTOR_EMP_INCOME' => $client->salary,
                        'GUARANTOR_REL_NAME' => $kyc->kin_fname.' '.$kyc->kin_lname,
                        'GUARANTOR_REL_ADD' => null,
                        'GUARANTOR_REL_CITY' => null,
                        'GUARANTOR_REL_PHONE' => $kyc->kin_number,
                        'GUARANTOR_REL_RELTNSHP' => $client->marital_state,
                        'FIN_AMT' => $loan->amount,
                        'FIN_TENOR' => $loan->paybackPeriod,
                        'FIN_INT_RATE' => $loan->interestRate,
                        'FIN_ADMIN' => 4,
                        'FIN_PURPOSE' => 'Consumption',
                        'FIN_SRC_REPAYMT' => 'BUSINESS AND SALARY',
                        'FIN_SEC_OFFER' => "",
                        'FIN_REPAY_DATE' => Carbon::parse($fromToday)->addMonth(1)->endOfMonth()->format('Y-m-d'),
                        'QUES_HOW' => 'Others',
                        'QUES_EMPLOYEE' => "",
                        'QUES_AGENT' => "",
                        'APPL_SIGNATURE' => null,
                        'JOINT_APPL_SIGNATURE' => null,
                        'RECOMMENDED_AMT' => $loan->amount,
                        'INT_RATE' => $settings->interest,
                        'INSURANCE_RATE' => null,
                        'ADMIN_RATE' => 4,
                        'ASSET_NAME' => null,
                        'PDACode' => null,
                        'LO_ID' => '42',
                        'FinProductType' => '4',
                        'Sector' => 'DISTRIBUTION',
                        'RepaymentIntervalNum' => 1,
                        'RepaymentIntervalUnit' => 'Months',
                        'Bank' => 'CBZ',
                        'BankBranch' => 0000,
                        'BankAccountNo' => 61404200018,
                        'CurrBorrowings' => 'Not borrowed',
                        'PrevBorrowings' => 'nill',
                        'ndasendaBatch' => $loan->ndasendaBatch,
                        'ndasendaRef1' => $loan->ndasendaRef1,
                        'ndasendaRef2' => $loan->ndasendaRef2,
                        'ndasendaStatus' => $loan->ndasendaState,
                        'fcbStatus' => $client->fsb_status,
                        'fcbMessage' => $client->fsb_rating,
                        'fcbScore' => $client->fsb_score,
                        'OfferLetter' => $offerLetterPath
                    ])
                        ->body();
                }
            } else{
                $details = Http::post('http://192.168.145.54/cbzapi/api/SaveLoan/PostNewLoan',[
                    'CUSTOMER_NUMBER' => $client->reds_number,
                    'Currency' => 'ZWL',
                    'GUARANTOR_NAME' => $client->first_name.' '.$client->last_name,
                    'GUARANTOR_DOB' => date_format($client->dob, 'Y-m-d'),
                    'GUARANTOR_IDNO' => str_replace("-", "", $client->natid),
                    'GUARANTOR_PHONE' => $client->mobile,
                    'GUARANTOR_ADD' => $client->house_num.' '.$client->street.' '.$client->surburb,
                    'GUARANTOR_CITY' => $client->city,
                    'GUARANTOR_HOME_TYPE' => $client->home_type,
                    'GUARANTOR_RENT' => null,
                    'GUARANTOR_HOME_LENGTH' => null,
                    'GUARANTOR_EMPLOYER' => $client->employer,
                    'GUARANTOR_EMP_ADD' => $client->city,
                    'GUARANTOR_EMP_LENGTH' => '5',
                    'GUARANTOR_EMP_PHONE' => '0552587963',
                    'GUARANTOR_EMP_EMAIL' => 'niachos@zimasco.co.zw',
                    'GUARANTOR_EMP_FAX' => null,
                    'GUARANTOR_EMP_POSTN' => null,
                    'GUARANTOR_EMP_SALARY' => $client->gross,
                    'GUARANTOR_EMP_INCOME' => $client->salary,
                    'GUARANTOR_REL_NAME' => $kyc->kin_fname.' '.$kyc->kin_lname,
                    'GUARANTOR_REL_ADD' => null,
                    'GUARANTOR_REL_CITY' => null,
                    'GUARANTOR_REL_PHONE' => $kyc->kin_number,
                    'GUARANTOR_REL_RELTNSHP' => $client->marital_state,
                    'FIN_AMT' => $loan->amount,
                    'FIN_TENOR' => $loan->paybackPeriod,
                    'FIN_INT_RATE' => $loan->interestRate,
                    'FIN_ADMIN' => 4,
                    'FIN_PURPOSE' => 'Consumption',
                    'FIN_SRC_REPAYMT' => 'BUSINESS AND SALARY',
                    'FIN_SEC_OFFER' => "",
                    'FIN_REPAY_DATE' => Carbon::parse($fromToday)->addMonth(1)->endOfMonth()->format('Y-m-d'),
                    'QUES_HOW' => 'Others',
                    'QUES_EMPLOYEE' => "",
                    'QUES_AGENT' => "",
                    'APPL_SIGNATURE' => null,
                    'JOINT_APPL_SIGNATURE' => null,
                    'RECOMMENDED_AMT' => $loan->amount,
                    'INT_RATE' => $settings->interest,
                    'INSURANCE_RATE' => null,
                    'ADMIN_RATE' => 4,
                    'ASSET_NAME' => null,
                    'PDACode' => null,
                    'LO_ID' => '42',
                    'FinProductType' => '4',
                    'Sector' => 'DISTRIBUTION',
                    'RepaymentIntervalNum' => 1,
                    'RepaymentIntervalUnit' => 'Months',
                    'Bank' => $bank->bank_short,
                    'BankBranch' => $kyc->branch_code,
                    'BankAccountNo' => $kyc->acc_number,
                    'CurrBorrowings' => 'Not borrowed',
                    'PrevBorrowings' => 'nill',
                    'ndasendaBatch' => $loan->ndasendaBatch,
                    'ndasendaRef1' => $loan->ndasendaRef1,
                    'ndasendaRef2' => $loan->ndasendaRef2,
                    'ndasendaStatus' => $loan->ndasendaState,
                    'fcbStatus' => $client->fsb_status,
                    'fcbMessage' => $client->fsb_rating,
                    'fcbScore' => $client->fsb_score,
                    'OfferLetter' => $offerLetterPath
                ])
                    ->body();
            }
        } else {
            //exec("strongswan restart");
            shell_exec('sudo strongswan restart');
            Log::info('I have run strongswan restart on Post loan to RedSphere connection attempt.');
            return redirect()->back()->with('error', 'Error '.$httpcode.': Redsphere server appears to be offline, I have tried to start the connection. Try after 30 seconds.');
        }

        $resp=json_decode($details, TRUE);

        if (isset($resp['Message'])) {
            if($resp['Message'] == 'Customer number was not found. Please check if the kyc was approved by RedSphere.'){
                return redirect()->back()->with('error', 'RedSphere Number for this customer was not found. Please check if the kyc was approved.');
            } else {
                return redirect()->back()->with('error', 'Something unusual happened here, I got the following error: '.$resp['Message']);
            }
        }

        $loanNumber = $resp['LoanId'];
        $IsDisbursed = $resp['IsDisbursed'];

        if (isset($resp['LoanId'])) {
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_number' => $loanNumber,'funder_id' => 1, 'funder_acc_number' => '02124652580021','isDisbursed' => $IsDisbursed, 'updated_at' => now()]);
        } else {
            return redirect()->back()->with('error', 'I did not get the Loan number successfully. Maybe try again?');
        }

        return redirect()->back()->with('success', 'Loan posted to CBZ successfully.');
    }

    public function updateLoanFromBatchProcessed($id){
        DB::table('loans')
            ->where('ndasendaBatch', $id)
            ->where('ndasendaRef1', '!=', null)
            ->where('ndasendaRef2', '!=', null)
            ->update(['loan_status' => 11, 'dd_approval'=>true, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Loans processed successfully');
    }

    public function newHybridLoan(){
        $partner = Partner::where('regNumber', auth()->user()->natid)->first();

        if (auth()->user()->hasRole('agent') || auth()->user()->hasRole('fielder') || auth()->user()->hasRole('salesadmin')) {
            $partner = User::where('natid', auth()->user()->natid)->first();
        }
        if (is_null($partner) ) {
            $rep = Representative::where('natid', auth()->user()->natid)->first();
            $user = User::where('name', $rep->creator)->first();
            $partner = Partner::where('regNumber', $user->natid)->first();
        }

        $clients = DB::table('clients as c')
            ->join('kycs as k', 'k.natid', '=', 'c.natid')
            ->join('users as u', 'u.natid','=','k.natid')
            ->select('u.id as user_id','c.id','c.first_name','c.last_name','c.cred_limit','c.natid','c.fsb_score','c.fsb_status','c.fsb_rating')
            ->where('k.bank','!=',null)
            ->where('c.creator','=',auth()->user()->name)
            ->orWhere('c.creator','=','Self')
            ->get();

        if (auth()->user()->hasRole('agent') || auth()->user()->hasRole('fielder') || auth()->user()->hasRole('salesadmin')) {
            $products = Product::all();
        } else {
            $products = Product::where('creator', auth()->user()->name)->get();
        }

        if(is_null($partner)){
            return redirect('/home')->with('error', 'We dont think you\'re capable of applying for loans.');
        }

        $settings = Masetting::find(1)->first();
        $locale = Localel::findOrFail(auth()->user()->locale);

        return view('loans.new-partner-hybrid',compact('partner', 'clients', 'products','settings','locale'));
    }

    public function postHybridLoan(Request $request) {
        if (($request->input('amount') + $request->input('cashamount')) > $request->input('cred_limit')) {
            return redirect()->back()->with('error', 'Sorry, you cannot apply for a loan that is above the credit limit for the client.')->withInput();
        } elseif(($request->input('cred_limit') - $request->input('amount')) < 0) {
            return redirect()->back()->with('error', 'Sorry, the amount requested exceeds the allowable credit limit.')->withInput();
        } elseif ($request->input('monthly') == 0){
            return redirect()->back()->with('error', 'Sorry, the monthly repayment cannot be 0. Please try again')->withInput();
        }

        $checkLoan = Loan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$request->input('client_id'))
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',13)
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
                $state = 8;
            } else{
                $state = 0;
            }
        } else {
            $state = 0;
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
                'prod_descrip'              => 'required',
                'paybackPeriod'         => 'required',
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

        $total = $request->input('amount') + $request->input('cashamount');

        $loan = Loan::create([
            'user_id'             => $request->input('user_id'),
            'client_id'       => $request->input('client_id'),
            'partner_id'        => auth()->user()->id,
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $total,
            'paybackPeriod'   => $request->input('paybackPeriod'),
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

        $loan->save();

        $client = Client::where('id', $loan->client_id)->first();

        if ($loan->save()) {
            $otp = mt_rand(100000, 999999);
            $getOtp = Http::post(getBulkSmsUrl() . "to=+263".$client->mobile."&msg=eShagi OTP: Your requested pin is: ".$otp.". Regards, eShagi.")
                ->body();

            $json = json_decode($getOtp, true);
            $status = $json['data'][0]['status'];
            if ($status == 'OK') {
                event(new LONewLoanApproval($loan));

                DB::table("clients")
                    ->where("natid", $client->natid)
                    ->update(['otp' => Hash::make($otp), 'updated_at' => now()]);
            }
        }

        return redirect('sign-for-client/'.$loan->id.'/'.$yuser->id)->with('success', 'Please sign the loan for your client, to begin processing it.');
    }

    public function pendingPartnerLoans() {
        $rep = Representative::where('natid', auth()->user()->natid)->first();
        $user = User::where('name', $rep->creator)->first();
        $partner = Partner::where('regNumber', $user->natid)->first();

        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid','c.ecnumber','l.amount','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_type','l.created_at')
            ->whereIn('l.loan_status',array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11))
            ->where('l.partner_id','=',$partner->id)
            ->where('l.deleted_at','=',null)
            ->get();

        return view('loans.pending-partner-loans', compact('loans'));
    }

    public function disbursedPartnerLoans() {
        $rep = Representative::where('natid', auth()->user()->natid)->first();
        $user = User::where('name', $rep->creator)->first();
        $partner = Partner::where('regNumber', $user->natid)->first();

        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid','c.ecnumber','l.amount','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_type','l.created_at')
            ->where('l.loan_status','=',12)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.partner_id','=',$partner->id)
            ->where('l.deleted_at','=',null)
            ->get();

        return view('loans.disbursed-partner-loans', compact('loans'));
    }

    public function getDisbursedLoansTemplate(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.dob')
            ->where('l.loan_number','=',null)
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->where('l.deleted_at','=',null)
            ->get();

        return view('loans.email-disbursed', compact('loans'));
    }

    public function generateDisbursementEmailPackage(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.dob')
            ->where('l.loan_number','=',null)
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->where('l.deleted_at','=',null)
            ->get();

        $letters = array();
        foreach ($loans as $loaner){
            $loan = Loan::where('id', $loaner->id)->first();
            $client = Client::where('id', $loan->client_id)->first();

            $letters[] = asset('project/public/offerletters/OfferLetter'.$client->natid.'.pdf');
        }

        $fileName = 'offerletters/OfferLetters'.now().'.zip';

        $zip = new ZipArchive();
        $zip->open($fileName,  ZipArchive::CREATE);
        foreach ($letters as $file) {
            $path = $file;
            //if(Storage::disk('public')->exists($path)){
            $zip->addFromString(basename($path),  file_get_contents($path));
            //}
            //else{
            //echo "file does not exist";
            //}
        }
        $zip->close();

        return response()->download($fileName);

    }
    /*public function generateDisbursementEmailPackage(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.dob')
            ->where('l.loan_number','=',null)
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->where('l.deleted_at','=',null)
            ->get();

        function createZipArchive($files = array(), $destination = '', $overwrite = false) {

            if(file_exists($destination) && !$overwrite) { return false; }

            $validFiles = array();
            if(is_array($files)) {
                foreach($files as $file) {
                    if(file_exists($file)) {
                        $validFiles[] = $file;
                    }
                }
            }

            if(count($validFiles)) {
                $zip = new ZipArchive();
                if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) == true) {
                    foreach($validFiles as $file) {
                        $zip->addFile($file,$file);
                    }
                    $zip->close();
                    return file_exists($destination);
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        $letters = array();
        foreach ($loans as $loaner){
            $loan = Loan::where('id', $loaner->id)->first();
            $client = Client::where('id', $loan->client_id)->first();

            $letters[] = 'offerletters/OfferLetter'.$client->natid.'.pdf';
        }

        $fileName = 'OfferLetters'.now().'.zip';
        createZipArchive($letters, $fileName);

        header("Content-Disposition: attachment; filename=\"".$fileName."\"");
        header("Content-Length: ".filesize($fileName));
        readfile($fileName);
        unlink($fileName);
    }*/

    public function getLoanRecords(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.creator','c.first_name','c.last_name','c.natid','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.locale','=',auth()->user()->locale)
            ->get();

    return view('loans.loan-records', compact('loans'));
    }

    public function ndasendaProcessing(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.natid','c.ecnumber','l.monthly','c.emp_sector','l.loan_status','l.paybackPeriod','l.loan_type','l.created_at')
            ->where('l.loan_status','=',9)
            ->where('c.emp_sector','=','Government')
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.ndasenda-processing', compact('loans'));
    }

    public function exportLoansToJson(){
        $settings = Masetting::find(1)->first();
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','l.amount','l.monthly','l.loan_status','l.loan_type')
            ->where('l.loan_status','=',11)
            ->orWhere('l.loan_status','=',7)
            ->where('l.deleted_at','=', null)
            ->where('c.reds_number','!=', null)
            ->where('c.fsb_score','!=', null)
            ->get();

        if ($loans->isEmpty()){
            return redirect()->back()->with('error', 'No loans were found to export');
        }

        $jsonLoans = array();
        foreach ($loans as $loan){
            $loan = Loan::findOrFail($loan->id);
            $client = Client::where('id', $loan->client_id)->first();
            $kyc = Kyc::where('natid', $client->natid)->first();
            $bank = Bank::where('id',$kyc->bank)->first();

            $offerLetterPath = public_path('offerletters/OfferLetter'.$client->natid.'.pdf');
            $jsonLoans[] = [
                'CUSTOMER_NUMBER' => $client->reds_number,
                'Currency' => 'ZWL',
                'GUARANTOR_NAME' => $client->first_name.' '.$client->last_name,
                'GUARANTOR_DOB' => date_format($client->dob, 'Y-m-d'),
                'GUARANTOR_IDNO' => str_replace("-", "", $client->natid),
                'GUARANTOR_PHONE' => $client->mobile,
                'GUARANTOR_ADD' => $client->house_num.' '.$client->street.' '.$client->surburb,
                'GUARANTOR_CITY' => $client->city,
                'GUARANTOR_HOME_TYPE' => $client->home_type,
                'GUARANTOR_RENT' => null,
                'GUARANTOR_HOME_LENGTH' => null,
                'GUARANTOR_EMPLOYER' => $client->employer,
                'GUARANTOR_EMP_ADD' => $client->city,
                'GUARANTOR_EMP_LENGTH' => '5',
                'GUARANTOR_EMP_PHONE' => '0552587963',
                'GUARANTOR_EMP_EMAIL' => 'niachos@zimasco.co.zw',
                'GUARANTOR_EMP_FAX' => null,
                'GUARANTOR_EMP_POSTN' => null,
                'GUARANTOR_EMP_SALARY' => $client->gross,
                'GUARANTOR_EMP_INCOME' => $client->salary,
                'GUARANTOR_REL_NAME' => $kyc->kin_fname.' '.$kyc->kin_lname,
                'GUARANTOR_REL_ADD' => null,
                'GUARANTOR_REL_CITY' => null,
                'GUARANTOR_REL_PHONE' => $kyc->kin_number,
                'GUARANTOR_REL_RELTNSHP' => $client->marital_state,
                'FIN_AMT' => $loan->amount,
                'FIN_TENOR' => $loan->paybackPeriod,
                'FIN_INT_RATE' => $loan->interestRate,
                'FIN_ADMIN' => 4,
                'FIN_PURPOSE' => 'Consumption',
                'FIN_SRC_REPAYMT' => 'BUSINESS AND SALARY',
                'FIN_SEC_OFFER' => "",
                'FIN_REPAY_DATE' => Carbon::parse($loan->created_at)->addMonth(1)->addMonths($loan->paybackPeriod)->endOfMonth()->format('Y-m-d'),
                'QUES_HOW' => 'Others',
                'QUES_EMPLOYEE' => "",
                'QUES_AGENT' => "",
                'APPL_SIGNATURE' => null,
                'JOINT_APPL_SIGNATURE' => null,
                'RECOMMENDED_AMT' => $loan->amount,
                'INT_RATE' => $settings->interest,
                'INSURANCE_RATE' => null,
                'ADMIN_RATE' => 4,
                'ASSET_NAME' => null,
                'PDACode' => null,
                'LO_ID' => '42',
                'FinProductType' => '4',
                'Sector' => 'DISTRIBUTION',
                'RepaymentIntervalNum' => 1,
                'RepaymentIntervalUnit' => 'Months',
                'Bank' => $bank->bank_short,
                'BankBranch' => $kyc->branch_code,
                'BankAccountNo' => $kyc->acc_number,
                'CurrBorrowings' => 'Not borrowed',
                'PrevBorrowings' => 'nill',
                'ndasendaBatch' => $loan->ndasendaBatch,
                'ndasendaRef1' => $loan->ndasendaRef1,
                'ndasendaRef2' => $loan->ndasendaRef2,
                'ndasendaStatus' => $loan->ndasendaState,
                'fcbStatus' => $client->fsb_status,
                'fcbMessage' => $client->fsb_rating,
                'fcbScore' => $client->fsb_score,
                'OfferLetter' => $offerLetterPath
            ];
        }

        $data = json_encode($jsonLoans, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
        $fileName = time() . '_approvedLoans.json';
        File::put(public_path('/uploads/'.$fileName),$data);
        return response()->download(public_path('/uploads/'.$fileName));

    }

    public function updateLoansFromJson(){
        $json = Storage::disk('public')->get('uploads/1608738394_datafile.json');
        $json = json_decode($json, true);

        dd($json);
    }

    public function getPendingDisbursement(){
        $loans = DB::table('loans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->rightJoin('banks as b', 'b.id','=','k.bank')
            ->select('l.id','u.first_name','u.last_name','u.natid','c.ecnumber','b.bank_short','k.branch','k.acc_number','l.loan_number','l.amount','l.monthly','l.paybackPeriod','l.loan_status','l.loan_type','l.isDisbursed')
            ->where('l.loan_number','!=',null)
            ->where('l.loan_status','=',11)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.pending-disbursement', compact('loans'));
    }

    public function checkIfLoanIsDisbursed($id){

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept ' => 'application/json'])
            ->get('http://192.168.145.54/cbzapi/api/SaveLoan/GetLoanApplication/'.$id)
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['Message'])) {
            if($resp['Message'] == 'Customer number was not found. Please check if the kyc was approved by RedSphere.'){
                return redirect()->back()->with('error', 'RedSphere Number for this customer was not found. Please check if the kyc was approved.');
            } else {
                return redirect()->back()->with('error', 'RedSphere responded with: '.$resp['Message']);
            }
        }

        $loan = Loan::where('loan_number', $id)->first();
        $client = Client::where('id', $loan->client_id)->first();

        if ($resp['IsDisbursed'] == true) {
            $commission = Commission::create([
                'agent'             => $client->creator,
                'client'             => $client->id,
                'loanid'             => $loan->id,
                'loanamt'            => $loan->amount,
                'commission'         => number_format(($loan->amount*0.03), 2, '.', ''),
            ]);

            $commission->save();

            (new RepaymentController)->generateRepaymentAmortization($loan->id, $client->id, $client->reds_number);

            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 12, 'updated_at' => now()]);

            Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263".$client->mobile."&msg=Great News ".$client->first_name.", We have checked and confirmed that your loan application for ZWL$".$loan->amount." has been approved. It should reflect in your account, anytime from now. eShagi.")
                ->body();
        } else{
            if ($resp['LoanStatus'] == 'REJECTED'){
                DB::table("loans")
                    ->where("id", $loan->id)
                    ->update(['loan_status' => 13, 'updated_at' => now()]);

                Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263".$client->mobile."&msg=Hello ".$client->first_name.", We have checked and confirmed that your loan application for ZWL$".$loan->amount." has been declined. eShagi.")
                    ->body();
            } else {
                return redirect()->back()->with('success', 'Loan status is currently at: '. $resp['LoanStatus']);
            }
        }

        return redirect()->back()->with('success', 'Loan details have been updated successfully.');
    }

    public function loansPendingPayment(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.creator','c.first_name','c.last_name','c.natid','l.amount','l.loan_type','l.disbursed','l.created_at')
            ->where('c.creator','=', auth()->user()->name)
            ->whereIn('l.loan_type',array(1, 4))
            ->whereIn('l.loan_status',array(0, 1, 15, 16))
            ->get();

        return view('loans.pending-paym-loans', compact('loans'));
    }

    public function pushLoanForPayment($id){
        $loan = Loan::findOrFail($id);

        $admins = User::where('utype','=','System')->get();
        foreach ($admins as $admin) {
            $admin->notify(new LoanPaymentNotReceived($loan));
        }

        return redirect()->back()->with('success', 'eShagi Personnel have been notified successfully.');
    }

    public function loanSettleForm(){
        $loans = DB::table('loans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.loan_number','l.monthly','l.loan_status','l.loan_type','l.notes')
            ->where('l.loan_status','=',12)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('loans.settle-loans', compact('loans'));
    }

    public function settleOffLoan($id){
        $loan = Loan::findOrFail($id);
        $client = Client::find($loan->client_id);
        $settings = Masetting::find(1)->first();

        DB::table("loans")
            ->where("id", $loan->id)
            ->update(['loan_status' => 14, 'updated_at' => now()]);

        DB::table("clients")
            ->where("id", $client->id)
            ->update(['cred_limit' => number_format($settings->creditRate*$client->salary,2,'.',''), 'updated_at' => now()]);

        DB::table("creditlimits")
            ->where("client_id", $client->id)
            ->update(['creditlimit' => number_format($settings->creditRate*$client->salary,2,'.',''), 'updated_at' => now()]);

        Http::post(getBulkSmsUrl() . "to=+263".$client->mobile."&msg=Hello ".$client->first_name.". Your loan for ZWL$".$loan->amount." has been successfully settled off. eShagi.")
            ->body();

        return redirect()->back()->with('success', 'Loan has been settled off successfully.');
    }

    public function checkingDisburseStatus($loan) {
        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'])
            ->get('http://192.168.145.54/cbzapi/api/SaveLoan/GetLoanApplication/'.$loan)
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['Message'])) {
            return redirect()->back()->with('error', 'RedSphere responded with: '.$resp['Message']);
        }
        $message = 'I got this loan info at RedSphere, please confirm with the disbursement schedule if this info is correct.';
        $loanInfo = Loan::where('loan_number','=',$loan)->first();
        $client = Client::where('id','=',$loanInfo->client_id)->first();
        return view('loans.loan-status', compact('resp', 'message', 'loanInfo', 'client'));
    }

    public function checkLoanDisbursementForm(){
        return view('loans.loan-status');
    }

    public function updateDisbursementStatus(Request $request){
        $loan = Loan::where('loan_number','=',$request->input('loan_number'))->first();
        $client = Client::where('id','=',$loan->client_id)->first();
        $settings = Masetting::find(1)->first();

        $commission = Commission::create([
            'agent'             => $client->creator,
            'client'             => $client->id,
            'loanid'             => $loan->id,
            'loanamt'            => $loan->amount,
            'commission'         => number_format(($loan->amount*0.03), 2, '.', ''),
        ]);

        $commission->save();

        (new RepaymentController)->generateRepaymentAmortization($loan->id, $client->id, $client->reds_number);

        DB::table("loans")
            ->where("id", $loan->id)
            ->update(['loan_status' => 12,'disbursed_at' => now(), 'updated_at' => now()]);

        if ($loan->loan_type == 1){
            Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Great News ".$client->first_name.", We have checked and confirmed that your product loan application for ".$loan->product." has been approved. eShagi.")
                ->body();
        } else {
            Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Great News ".$client->first_name.", We have checked and confirmed that your loan application for ZWL$".$loan->amount." has been approved. It should reflect in your account, anytime from now. eShagi.")
                ->body();
        }

        return redirect('pending-disbursement')->with('success', 'Loan details have been updated successfully.');
    }

    public function updateDeclinedStatus(Request $request){
        $loan = Loan::where('loan_number','=',$request->input('loan_number'))->first();
        $client = Client::where('id','=',$loan->client_id)->first();
        $settings = Masetting::find(1)->first();

        DB::table("loans")
            ->where("id", $loan->id)
            ->update(['loan_status' => 13, 'updated_at' => now()]);


        if ($loan->loan_type == 1){
            Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Hello ".$client->first_name.", We have checked and confirmed that your loan application for ".$loan->product." has been declined. eShagi.")
                ->body();
        } else {
            Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Hello ".$client->first_name.", We have checked and confirmed that your loan application for ".$loan->product." has been declined. eShagi.")
                ->body();
        }

        return redirect('pending-disbursement')->with('success', 'Loan details have been updated successfully.');
    }

    public function reassignLoan(){
        return view('loans.reassign-loan');
    }

    public function manageLoans(){
        if (Auth::user()->hasRole('loansofficer')){
            $loans = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','l.amount','l.monthly','l.loan_status','l.loan_type')
                ->where('l.locale','=',auth()->user()->locale)
                ->where('l.loan_status','<',12)
                ->where('l.deleted_at','=', null)
                ->get();
        } elseif (Auth::user()->isManager()){
            $loans = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->select('l.id','c.id as cid','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','l.amount','l.monthly','l.loan_status','l.loan_type')
                ->where('l.locale','=',auth()->user()->locale)
                ->where('l.loan_status','<',12)
                ->where('l.deleted_at','=', null)
                ->get();
        } else{
            echo 'Sorry you cant do that';
            return redirect()->back();
        }

        return view('loans.manage-loans', compact('loans'));
    }

    public function selectSearchLoan(Request $request){
        $loans = [];

        if($request->has('q')){
            $search = $request->q;
            $loans = DB::table('loans as l')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->select('l.id','l.amount','c.first_name','c.last_name','c.natid')
                ->where('c.first_name', 'LIKE', "%{$search}%")
                ->orWhere('c.last_name', 'LIKE', "%{$search}%")
                ->orWhere('c.natid', 'LIKE', "%{$search}%")
                ->get();
        }
        return response()->json($loans);
    }

    public function getLoansForMusoni(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.first_name','c.last_name','c.natid','l.amount','c.dob','l.monthly','l.loan_type')
            ->where('l.loan_number','=',null)
            ->where('l.loan_status','=',11)
            ->where('l.deleted_at','=',null)
            ->get();

        return view('loans.musoni-loans', compact('loans'));
    }

}
