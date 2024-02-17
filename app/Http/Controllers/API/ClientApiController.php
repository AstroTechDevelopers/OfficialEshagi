<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BotApplication;
use App\Models\Client;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\Localel;
use App\Models\SsbDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientApiController extends Controller
{

    public function getBanksInfo(){
        $banks = DB::table('banks')
            ->where('locale_id', '=', auth()->user()->locale)
            ->groupBy('bank')
            ->get();
        $countries = Localel::all();

        $response = ['banks' => $banks, 'countries' => $countries];
        return response($response, 200);
    }

    public function demBankBranches($id) {
        $branches = DB::table("bank_branches")
            ->where("bank_id", $id)
            ->select("id", "branch", "branch_code")
            ->orderBy("branch", 'asc')
            ->get();

        $response = ['branches' => $branches];
        return response($response, 200);
    }

    public function submitKycInfo(Request $request){
        $validator = Validator::make($request->all(),
            [
                'kin_title'                  => 'required',
                'kin_fname'            => 'required',
                'kin_lname'             => 'required',
                'kin_email'                 => 'nullable|email|max:255',
                'kin_work'                 => 'required',
                'kin_number'                 => 'required',
                'bank'                 => 'required',
                'bank_acc_name'                 => 'required',
                'branch'                 => 'required',
                'branch_code'                 => 'required',
                'acc_number'                 => 'required',
            ],
            [
                'kin_title.required'           => 'Please select a title.',
                'kin_fname.required'           => 'What is the first name for your next of kin?',
                'kin_lname.required'            => 'What is the last name for your next of kin?',
                'kin_email.email'                   => trans('auth.emailInvalid'),
                'kin_work.required'                   => 'We need to know where they work.',
                'kin_number.required'                   => 'What is your next of kin\'s mobile number?',
                'bank.required'                   => 'Which bank do you use?',
                'bank_acc_name.required'                   => 'What in the name linked to the bank account?',
                'branch.required'                   => 'Please select your branch',
                'branch_code.required'                   => 'Please select your branch, if your branch code did not show',
                'acc_number.required'                   => 'Please state your account number',

            ]
        );

        if ($validator->fails()) {
            return response([ 'error' => 'There were some problems with your input',
                'errors' => $validator->errors()]);
        }

        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();

        if ($client->mobile == $request['kin_number']) {
            return response([ 'error' => 'You cannot use your mobile number as a next of kin number. Please use another number',
            ]);
        }

        $kyc = Kyc::create([
            'user_id' => $client->id,
            'natid' => auth()->user()->natid,
            'kin_title'              => $request['kin_title'],
            'kin_fname'        => $request['kin_fname'],
            'kin_lname'         => $request['kin_lname'],
            'kin_email'             => $request['kin_email'],
            'kin_work'             => $request['kin_work'],
            'kin_number'             => $request['kin_number'],
            'bank'             => $request['bank'],
            'bank_acc_name'             => $request['bank_acc_name'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'acc_number'             => $request['acc_number'],
        ]);
        $kyc->save();

        if ($kyc->save()) {
            $ssbInfo = SsbDetail::create([
                'natid' => auth()->user()->natid,
                'ecnumber' => $client->ecnumber,
            ]);
            $ssbInfo->save();
        }

        $response = [ 'user' => auth()->user(), 'kyc' => $kyc];
        return response($response, 200);
    }

    function uploadMyNationalID(Request $request){
        $validator = Validator::make($request->all(),
            [
                'natid'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'natid.max'                 => 'National ID file should not be greater than 4MB.',
                'natid.mimes'               => 'National ID file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return response([ 'error' => 'There were some problems with your input',
                'errors' => $validator->errors()]);
        }

        if($request->hasFile('natid')) {

            if ($request->file('natid')->isValid()) {

                $nationalId = $request->file('natid');
                $filename = auth()->user()->natid . '.' . $nationalId->getClientOriginalExtension();
                Storage::disk('public')->put('nationalids/' . $filename, File::get($nationalId));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->national_pic = $filename;
                $user->national_stat = true;
                $user->updated_at = now();

                $user->save();

                $application = BotApplication::where('natid', auth()->user()->natid)->first();
                $application->national_pic = $filename;
                $application->save();
            } else {
                return response([ 'error' => 'Invalid image supplied.']);
            }

        } else {
            return response([ 'error' => 'No file was detected here.']);
        }

        $response = [ 'success' => 'National ID uploaded successfully.'];
        return response($response, 200);
    }

    function uploadMyPassportPhoto(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'passport'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'passport.max'                 => 'Passport Photo file should not be greater than 4MB.',
                'passport.mimes'               => 'Passport Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return response([ 'error' => 'There were some problems with your input',
                'errors' => $validator->errors()]);
        }

        if($request->hasFile('passport')) {

            if ($request->file('passport')->isValid()) {

                $passPhoto = $request->file('passport');
                $filename = auth()->user()->natid . '.' . $passPhoto->getClientOriginalExtension();
                Storage::disk('public')->put('pphotos/' . $filename, File::get($passPhoto));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->passport_pic = $filename;
                $user->passport_stat = true;
                $user->updated_at = now();

                $user->save();

                $application = BotApplication::where('natid', auth()->user()->natid)->first();
                $application->passport_pic = $filename;
                $application->save();

            } else {
                return response([ 'error' => 'Invalid image supplied.']);
            }

        } else {
            return response([ 'error' => 'No file was detected here.']);
        }

        $response = [ 'success' => 'Passport Photo uploaded successfully..'];
        return response($response, 200);
    }

    function uploadMyCurrentPayslip(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'payslip'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'payslip.max'                 => 'Payslip should not be greater than 4MB.',
                'payslip.mimes'               => 'Payslip should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return response([ 'error' => 'There were some problems with your input',
                'errors' => $validator->errors()]);
        }

        if($request->hasFile('payslip')) {

            if ($request->file('payslip')->isValid()) {

                $payslip = $request->file('payslip');
                $filename = auth()->user()->natid . '.' . $payslip->getClientOriginalExtension();
                Storage::disk('public')->put('payslips/' . $filename, File::get($payslip));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->payslip_pic = $filename;
                $user->payslip_stat = true;
                $user->updated_at = now();

                $user->save();

            } else {
                return response([ 'error' => 'Invalid image supplied.']);
            }

        } else {
            return response([ 'error' => 'No file was detected here.']);
        }

        $response = [ 'success' => 'Payslip uploaded successfully.'];
        return response($response, 200);
    }

    function uploadMySignature(Request $request) {

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
            return response([ 'error' => 'There were some problems with your input',
                'errors' => $validator->errors()]);
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
                return response([ 'error' => 'Invalid image supplied.']);
            }
        } else {
            return response([ 'error' => 'No file was detected here.']);
        }

        $response = [ 'success' => 'Your loan application has been submitted for processing.'];
        return response($response, 200);
    }

    public function initialLoanApplication(Request $request){
        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'kin_fullname'            => 'required',
                'kin_email'                 => 'nullable|email|max:255',
                'kin_work'                 => 'required',
                'kin_number'                 => 'required',
                'bank'                 => 'required',
                'bank_acc_name'                 => 'required',
                'branch'                 => 'required',
                'branch_code'                 => 'required',
                'acc_number'                 => 'required',
            ],
            [
                'kin_fullname.required'           => 'What is the full name for your next of kin?',
                'kin_email.email'                   => trans('auth.emailInvalid'),
                'kin_work.required'                   => 'We need to know where they work.',
                'kin_number.required'                   => 'What is your next of kin\'s mobile number?',
                'bank.required'                   => 'Which bank do you use?',
                'bank_acc_name.required'                   => 'What in the name linked to the bank account?',
                'branch.required'                   => 'Please select your branch',
                'branch_code.required'                   => 'Please select your branch, if your branch code did not show',
                'acc_number.required'                   => 'Please state your account number',
            ]
        );

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        if ($client->mobile == $request['kin_number']) {
            return response(['error' => 'You cannot use your mobile number as a next of kin number. Please use another number'], 422);
        }

        $fullresult = explode(" ", $request['kin_fullname']);
        $firstName = $fullresult[0];
        $lastName =  $fullresult[1];

        $kyc = Kyc::create([
            'user_id' => $client->id,
            'natid' => auth()->user()->natid,
            'kin_title'              => 'Mx',
            'kin_fname'        => $firstName,
            'kin_lname'         => $lastName,
            'kin_email'             => $request['kin_email'],
            'kin_work'             => $request['kin_work'],
            'kin_number'             => $request['kin_number'],
            'bank'             => $request['bank'],
            'bank_acc_name'             => $request['bank_acc_name'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'acc_number'             => $request['acc_number'],
        ]);
        $kyc->save();

        if ($kyc->save()) {
            $ssbInfo = SsbDetail::create([
                'natid' => auth()->user()->natid,
                'ecnumber' => $client->ecnumber,
            ]);
            $ssbInfo->save();

            $client->gross = $request['gross'];
            $client->salary = $request['salary'];
            $client->cred_limit = number_format(getCreditRate()*$request['salary'],2,'.','');
            $client->emp_sector = $request['emp_sector'];
            $client->ecnumber = $request['ecnumber'];
            $client->save();
        }

        $response = [ 'user' => auth()->user(), 'kyc' => $kyc, 'client' => $client];
        return response($response, 200);
    }

    public function checkNatID(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'natid' =>
                    array(
                        'required',
                        'regex:/^[0-9]{2}-[0-9]{5,7}-[A-Z]-[0-9]{2}$/'
                    )
            ],
            [
                'natid.required'           => 'National ID to be processed is needed!',
                'natid.regex'           => 'National ID has to be in the valid format!',
            ]
        );

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $client = User::where('natid', $request->input('natid'))->first();

        if (is_null($client)){
            $response = [ 'message' => 'User not registered.Do you want to register now?'];

            return response($response, 422);
        }

        $otp = mt_rand(100000, 999999);
        $getOtp = Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263".$client->mobile."&msg=eShagi OTP: Your WhatsApp bot OTP is: ".$otp.". Regards, eShagi.")
            ->body();

        $json = json_decode($getOtp, true);
        $status = $json['data'][0]['status'];
        if ($status == 'OK') {
            DB::table("users")
                ->where("natid", $client->natid)
                ->update(['bot_otp' => Hash::make($otp), 'updated_at' => now()]);
        }

        $response = [ 'message' => ' We have send an OTP to this number : '.substr($client->mobile, 0, 4) . "****" . substr($client->mobile, 7, 2).'* . Please type the OTP to confirm'];
        return response($response, 200);
    }

}
