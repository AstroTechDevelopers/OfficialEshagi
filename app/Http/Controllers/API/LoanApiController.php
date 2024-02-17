<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\Masetting;
use App\Models\Product;
use App\Models\SsbDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LoanApiController extends Controller
{
    public function loanCalculator(Request $request){
        $validator = Validator::make($request->all(), [
            'tenure' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $tenure = $request->tenure;
        $amount = $request->amount;

        function pmt($rate_per_period, $number_of_payments, $present_value, $future_value, $type){
            $future_value =  0;
            $type =  0;

            if($rate_per_period != 0.0){
                $q = pow(1 + $rate_per_period, $number_of_payments);
                return -($rate_per_period * ($future_value + ($q * $present_value))) / ((-1 + $q) * (1 + $rate_per_period * ($type)));

            } else if($number_of_payments != 0.0){
                return -($future_value + $present_value) / $number_of_payments;
            }

            return 0;
        }

        $payment =  -1*pmt(getInterestRate()/100,$tenure,$amount,0,0);
        $total_charges = 0.16*$amount;
        $tax = 0.02*$amount;
        $insurance = 0.025*$amount;
        $appfee = 0.065*$amount;
        $arrangement = 0.05*$amount;
        $amountDibursed = 0.84*$amount;

        $response = [
            'payment' => $payment,
            'total_charges' => $total_charges,
            'tax' => $tax,
            'insurance' => $insurance,
            'appfee' => $appfee,
            'arrangement' => $arrangement,
            'amountDibursed' => $amountDibursed,
        ];

        return response($response, 200);
    }

    public function applyLoan(Request $request) {
        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();


        if ($request->input('amount') > $client->cred_limit) {
            return response(['error'=>'Sorry, you cannot apply for a loan that is above your credit limit.'], 422);
        } elseif(($client->cred_limit - $request->input('amount')) < 0) {
            return response(['error' => 'Sorry, the amount requested exceeds the allowable credit limit.'], 422);
        } elseif ($request->input('monthly') == 0){
            return response(['error' => 'Sorry, the monthly repayment cannot be 0. Please try again'], 422);
        }


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
            return response(['error' => 'A loan with the same details already exists, no need to recreate it.'], 422);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'channel_id'                 => 'required',
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
                'channel_id.required'  => 'How was this loan applied?',
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
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $loan = Loan::create([
            'user_id'             => auth()->user()->id,
            'client_id'       => $request->input('client_id'),
            'partner_id'        => $request->input('partner_id'),
            'channel_id'            => $request->input('channel_id'),
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate'      => $request->input('interestRate'),
            'monthly'        => $request->input('monthly'),
            'disbursed'        => $request->input('disbursed'),
            'appFee'        => $request->input('appFee'),
            'charges'        => $request->input('charges'),
            'notes'        => $request->input('prod_descrip'),
            'locale'        => auth()->user()->locale,
        ]);
        $loan->save();

        if ($loan->save()) {
            $newlimit = $client->cred_limit-$request->input('amount');
            DB::table('clients')
                ->where('id',$request->input('client_id'))
                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
        }

        $response = ['loan' => $loan, 'kycInfo' => $yuser, 'success'=> 'Please sign your loan to begin processing it.'];
        return response($response, 200);
    }

    public function kycDocsLoan(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'loan_type' => 'required',
                'amount' => 'required',
                'paybackPeriod' => 'required',
                'natid' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'passport' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'payslip' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',

            ],
            [
                'loan_type.required' => 'Please advise the type of loan',
                'amount.required' => 'The amount for the loan is needed.',
                'paybackPeriod.required' => 'How long are you planning on paying back this loan?',
                'natid.max' => 'National ID file should not be greater than 4MB.',
                'natid.mimes' => 'National ID file should of the format: jpeg,png,jpg,gif,svg.',
                'passport.max' => 'Passport Photo file should not be greater than 4MB.',
                'passport.mimes' => 'Passport Photo file should of the format: jpeg,png,jpg,gif,svg.',
                'payslip.max' => 'Payslip should not be greater than 4MB.',
                'payslip.mimes' => 'Payslip should of the format: jpeg,png,jpg,gif,svg.',
                'signature.required' => 'Your signature picture is required.',
                'signature.max' => 'Signature should not be greater than 4MB.',
                'signature.mimes' => 'Signature should of the format: jpeg,png,jpg,gif,svg.',

            ]
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        if ($request->hasFile('natid') and $request->hasFile('passport') and $request->hasFile('payslip') and $request->hasFile('signature')) {
            if ($request->file('natid')->isValid() and $request->file('passport')->isValid() and $request->file('payslip')->isValid() and $request->file('signature')->isValid()) {
                $nationalId = $request->file('natid');
                $passPhoto = $request->file('passport');
                $payslip = $request->file('payslip');
                $signature = $request->file('signature');

                $natidFilename = auth()->user()->natid . '.' . $nationalId->getClientOriginalExtension();
                $selfieFilename = auth()->user()->natid . '.' . $passPhoto->getClientOriginalExtension();
                $paySlipfilename = auth()->user()->natid . '.' . $payslip->getClientOriginalExtension();
                $signFilename = auth()->user()->natid . '.' . $signature->getClientOriginalExtension();

                Storage::disk('public')->put('nationalids/' . $natidFilename, File::get($nationalId));
                Storage::disk('public')->put('pphotos/' . $selfieFilename, File::get($passPhoto));
                Storage::disk('public')->put('payslips/' . $paySlipfilename, File::get($payslip));
                Storage::disk('public')->put('signatures/' . $signFilename, File::get($signature));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->sign_id = auth()->user()->natid;
                $user->national_pic = $natidFilename;
                $user->passport_pic = $selfieFilename;
                $user->payslip_pic = $paySlipfilename;
                $user->sign_pic = $signFilename;
                $user->national_stat = true;
                $user->passport_stat = true;
                $user->payslip_stat = true;
                $user->sign_stat = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return response([ 'error' => 'Invalid image supplied.']);
            }
        } else {
            return response([ 'error' => 'No file was detected here.']);
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

        $checkLoan = Loan::where('amount','=',$request->input('amount'))
            ->where('client_id','=',$client->id)
            ->where('loan_type','=',$request->input('loan_type'))
            ->where('paybackPeriod','=',$request->input('paybackPeriod'))
            ->where('loan_status','!=',13)
            ->exists();

        if ($checkLoan){
            return response(['error' => 'A loan with the same details already exists, no need to recreate it.'], 422);
        }

         $tenure = $request->input('paybackPeriod');
        $amount = $request->input('amount');

        function pmt($rate_per_period, $number_of_payments, $present_value, $future_value, $type){
            $future_value =  0;
            $type =  0;

            if($rate_per_period != 0.0){
                $q = pow(1 + $rate_per_period, $number_of_payments);
                return -($rate_per_period * ($future_value + ($q * $present_value))) / ((-1 + $q) * (1 + $rate_per_period * ($type)));

            } else if($number_of_payments != 0.0){
                return -($future_value + $present_value) / $number_of_payments;
            }

            return 0;
        }

        $payment =  -1*pmt(getInterestRate()/100,$tenure,$amount,0,0);
        $total_charges = 0.16*$amount;
        $appfee = 0.065*$amount;
        $amountDibursed = 0.84*$amount;

        if ($request->input('amount') > $client->cred_limit) {
            return response(['error'=>'Sorry, you cannot apply for a loan that is above your credit limit.'], 422);
        } elseif(($client->cred_limit - $request->input('amount')) < 0) {
            return response(['error' => 'Sorry, the amount requested exceeds the allowable credit limit.'], 422);
        } elseif ($payment == 0){
            return response(['error' => 'Sorry, the monthly repayment cannot be 0. Please try again'], 422);
        }

        $loan = Loan::create([
            'user_id'             => auth()->user()->id,
            'client_id'       => $client->id,
            'channel_id'            => 'WhatsApp Bot',
            'loan_type'            => $request->input('loan_type'),
            'loan_status'            => $state,
            'amount'         => $request->input('amount'),
            'paybackPeriod'            => $request->input('paybackPeriod'),
            'interestRate'      => getInterestRate(),
            'monthly'        => number_format($payment,2,'.',''),
            'disbursed'        => number_format($amountDibursed,2,'.',''),
            'appFee'        => number_format($appfee,2,'.',''),
            'charges'        => number_format($total_charges,2,'.',''),
            'notes'        => $request->input('prod_descrip'),
            'locale'        => auth()->user()->locale,
        ]);

        $loan->save();

        if ($loan->save()) {
            $newlimit = $client->cred_limit-$request->input('amount');
            DB::table('clients')
                ->where('id',$request->input('client_id'))
                ->update(['cred_limit' => $newlimit,'updated_at' => now()]);
            $response = ['loan' => $loan,
                'kycInfo' => $yuser,
                'success'=> 'Your loan application has been successful. These are the details of your loan. Amount: $'.$loan->amount.', Tenure: '.$loan->paybackPeriod.' months, Monthly instalment: $'.$loan->monthly];
            return response($response, 200);
        } else{
            $response = ['loan' => $loan,
                'kycInfo' => $yuser,
                'error'=> 'Your loan application has not been successful. Please call, SMS or WhatsApp a call centre agent on +263 714 575 817 | +263 712 399 843 | +263 715 069 167'];
            return response($response, 422);
        }
    }

    public function getApiLoanRepayments($loan, $redsNumber) {

        /*$validator = Validator::make(
            $request->all(),
            [
                'loan' => 'required',
                'redsnumber' => 'required',
            ],
            [
                'loan.required' => 'What is the loan number of you want to check?',
                'redsnumber.required' => 'What is the client\'s RedSphere Number?',
            ]
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $loan = $request->input('loan');
        $redsNumber = $request->input('redsnumber');*/

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept ' => 'application/json'])
            ->get('http://192.168.145.54/cbzapi/api/SaveLoan/GetRepayments?loanId='.$loan.'&CustomerNumber='.$redsNumber)
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['Message'])) {
            return redirect()->back()->with('error', 'RedSphere responded with: '.$resp['Message']);
        }
        $loanInfo = Loan::where('loan_number','=',$loan)->first();
        $client = Client::where('id','=',$loanInfo->client_id)->first();

        $response = ['message' => 'I got these loan repayments at RedSphere.',
            'client' => $client,
            'repayments' => $resp];
        return response($response, 200);
    }

    public function getAllMyLoans($id) {
        $loans = DB::table("loans")
            ->where("user_id", $id)
            ->select("id", "amount", "paybackPeriod","disbursed",
                DB::raw('(CASE WHEN loan_type = 1 THEN "Store Credit" WHEN loan_type = 2 THEN "Cash Loan" WHEN loan_type = 3 THEN "Recharge Credit" WHEN loan_type = 4 THEN "Hybrid Loan" ELSE 0 END) AS loan_type'),
                DB::raw('(CASE WHEN loan_status = 0 THEN "Not Signed"
                WHEN loan_status = 1 THEN "New Loan"
                 WHEN loan_status = 2 THEN "KYC sent to CBZ for review"
                 WHEN loan_status = 3 THEN "Loan sent for Stop Order"
                 WHEN loan_status = 4 THEN "Loan sent for MOU"
                 WHEN loan_status = 5 THEN "Loan sent to client bank"
                 WHEN loan_status = 6 THEN "Loan sent to HR for processing"
                 WHEN loan_status = 7 THEN "Loan sent to CBZ Banking"
                 WHEN loan_status = 8 THEN "Loan sent to RedSphere processing"
                 WHEN loan_status = 9 THEN "KYC sent to CBZ for review"
                 WHEN loan_status = 10 THEN "Loan sent to Ndasenda for payroll processing"
                 WHEN loan_status = 11 THEN "Loan sent to CBZ Banking"
                 WHEN loan_status = 12 THEN "Loan was disbursed"
                 WHEN loan_status = 13 THEN "Loan was declined"
                 WHEN loan_status = 14 THEN "Loan was paid back"
                 WHEN loan_status = 15 THEN "KYC at CRB/Local Board Check"
                 WHEN loan_status = 16 THEN "KYC at eShagi Check"
                 WHEN loan_status = 17 THEN "Loan/Device to be issued"
                 WHEN loan_status = 18 THEN "Loan in repayment phase"
                 ELSE 0 END) AS loan_status'))
            ->orderBy("id", 'desc')
            ->get();

        $response = ['loans' => $loans];
        return response($response, 200);
    }

    public function getLoanDeviceList(){
        $devices = Product::where('loandevice', true)->get();
        $response = ['devices' => $devices];
        return response($response, 200);
    }
}
