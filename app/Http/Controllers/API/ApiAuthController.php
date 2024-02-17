<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BotApplication;
use App\Models\Client;
use App\Models\Creditlimit;
use App\Models\Kyc;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\Profile;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use jeremykenedy\LaravelRoles\Models\Role;

class ApiAuthController extends Controller
{
    public function register (Request $request) {

        $parts = explode(' ', $request->full_name);
        $name_first = array_shift($parts);
        $name_last = array_pop($parts);
        $name_middle = trim(implode(' ', $parts));

        $maiden = substr($name_first, 0, 1);
        $middle = substr($name_middle, 0, 1);

        $username = strtolower($maiden . $middle . $name_last);

        $name = $username;
        $i = 0;
        do {
            //Check in the database here
            $exists = User::where('name', '=', $name)->exists();
            if($exists) {
                $i++;
                $name = $username . $i;
            }
        }while($exists);

        $locale = Localel::where('country','Zimbabwe')->firstOrFail();
        $validator = Validator::make(
            $request->all(),
            [
                'full_name'            => 'required',
                'natid'                 => 'required|max:15|unique:users',
                'email'                 => 'nullable|email|max:255|unique:users',
                'mobile'                 => 'required|max:20|unique:users',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_state'        => 'required',
                'employer'        => 'required',
                'address'        => 'required',
                'home_type'        => 'required',
                'selfie_pic'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'national_pic'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'full_name.required'           => 'Your full name is required.',
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with eShagi.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with eShagi.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with eShagi.',
                'dob.required' => 'We need your date of birth',
                'dob.date' => 'The correct date format should be dd-mm-yyyy',
                'gender.required' => 'What is your gender?',
                'marital_state.required' => 'What is your marital status?',
                'employer.required' => 'What is the name  of your employer?',
                'home_type.required' => 'What is the state of your home?',
                'selfie_pic.max'                 => 'Selfie Photo file should not be greater than 4MB.',
                'selfie_pic.mimes'               => 'Selfie Photo file should of the format: jpeg,png,jpg,gif,svg.',
                'national_pic.max'                 => 'National ID Photo file should not be greater than 4MB.',
                'national_pic.mimes'               => 'National ID Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return response([ 'error' => 'There were some problems with your input',
                'errors' => $validator->errors()]);
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $bot_number = $request['mobile'];
        $mobile = substr($request['mobile'], 3);

        $role = Role::where('slug', '=', 'client')->first();

        $fullresult = explode(" ", $request['full_name']);
        $firstName = $fullresult[0];
        $lastName =  $fullresult[1];

        $fullAddress = explode(",", $request['address']);
        $house_num = $fullAddress[0];
        $street =  $fullAddress[1];
        $surburb =  $fullAddress[2];
        $city =  $fullAddress[3];

        $randPassword = str_random(8);
        if($request->hasFile('selfie_pic') AND $request->hasFile('national_pic')) {

            if ($request->file('selfie_pic')->isValid() AND $request->file('national_pic')->isValid()) {

                $client = Client::create([
                    'creator'              => 'Bot',
                    'title'              => 'Mx',
                    'first_name'        => strip_tags($firstName),
                    'last_name'         => strip_tags($lastName),
                    'natid'             => strtoupper($request['natid']),
                    'email'             => $request['email'],
                    'mobile'             => $mobile,
                    'dob'             => $request['dob'],
                    'gender'             => $request['gender'],
                    'marital_state'             => $request['marital_state'],
                    'dependants'             => 1,
                    'nationality'             => 'Zimbabwean',
                    'house_num'             => $house_num,
                    'street'             => $street,
                    'surburb'             => $surburb,
                    'city'             => $city,
                    'province'             => $city,
                    'country'             => 'Zimbabwe',
                    'locale_id'             => $locale->id,
                    'emp_sector'             => 'Government',
                    'employer'             => $request['employer'],
                    'ecnumber'             => $mobile,
                    'gross'             => 0.00,
                    'salary'             => 0.00,
                    'cred_limit'             => 0.00,
                    'home_type'             => $request['home_type'],
                ]);

                $client->save();

                if ($client->save()) {

                    $passPhoto = $request->file('selfie_pic');
                    $nationalId = $request->file('national_pic');

                    $selfiePic = $client->natid . '.' . $passPhoto->getClientOriginalExtension();
                    $nationalIdPic = $client->natid . '.' . $nationalId->getClientOriginalExtension();


                    Storage::disk('public')->put('pphotos/' . $selfiePic, File::get($passPhoto));
                    Storage::disk('public')->put('nationalids/' . $nationalIdPic, File::get($nationalId));


                    $limit = Creditlimit::create([
                        'client_id'             => $client->id,
                        'grossSalary'             => 0.00,
                        'netSalary'             => 0.00,
                        'creditlimit'             => 0.00,
                        'reason'             => 'New Client which did not have credit limit record.',
                    ]);

                    $limit->save();

                    $user = User::create([
                        'name'              => $name,
                        'first_name'        => $firstName,
                        'last_name'         => $lastName,
                        'email'             => $request['email'],
                        'natid'             => strtoupper($request['natid']),
                        'mobile'             => $mobile,
                        'bot_number'             => $bot_number,
                        'utype'             => 'Client',
                        'locale'             => $locale->id,
                        'password'          => Hash::make($randPassword),
                        'token'             => str_random(64),
                        'signup_ip_address' => $ipAddress,
                        'activated'         => false,
                    ]);

                    $user->attachRole($role);

                    $profile = new Profile();
                    $user->profile()->save($profile);
                    $token = $user->createToken('eShagi Access Token')->accessToken;

                    $application = BotApplication::create([
                        'user_id'             => $user->id,
                        'client_id'             => $client->id,
                        'natid'             => $user->natid,
                        'passport_pic'      =>   $selfiePic,
                        'national_pic'      =>   $nationalIdPic
                    ]);

                    $application->save();

                    return response([ 'user' => $user,'password' => $randPassword, 'token' => $token]);
                        }
            } else {
                return response([ 'error' => 'No selfie photo or national ID was invalid.']);
            }
        } else {
            return response([ 'error' => 'No selfie photo or national ID was detected.']);
        }

        return response([ 'error' => 'There was an error processing your request.']);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'natid' => 'required|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('natid', $request->natid)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('eShagi Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function checkMyNumber($mobile){
        $user = User::where('bot_number', $mobile)->first();

        if ($user){
            $response = ['user' => $user];
            return response($response, 200);
        } else {
            $response = ['user' => null];
            return response($response, 404);
        }
    }

    public function generateBotOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'natid' => 'required|max:255',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $natid = $request->input('natid');

        $client = User::where('natid', $natid)->firstOrFail();

        $otp = mt_rand(100000, 999999);
       $getOtp = Http::post("http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&to=+263".$client->mobile."&msg=eShagi OTP: Your requested WhatsApp bot OTP is: ".$otp.". Regards, eShagi.")
           ->body();

       $json = json_decode($getOtp, true);
       $status = $json['data'][0]['status'];
       if ($status == 'OK') {
           DB::table("users")
               ->where("natid", $client->natid)
               ->update(['bot_otp' => Hash::make($otp), 'updated_at' => now()]);
       }

        $response = ['success' => 'OTP generated successfully.'];
        return response($response, 200);
    }

    public function confirmWithOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'bot_number' => 'required|max:255',
            'natid' => 'required|max:255',
            'otp' => 'required|max:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $client = User::where('natid', $request->input('natid'))->firstOrFail();

        if (Hash::check($request->input('otp'), $client->bot_otp)) {
            $client->bot_number = $request->input('bot_number');
            $client->save();
        } else {
            return response([ 'error' => 'Invalid OTP provided, please check your provided OTP.'], 422);
        }

        $response = ['user' => $client, 'success' => 'Your bot number has been updated successfully.'];
        return response($response, 200);
    }

    public function checkMyKycState(){
        $kyc = Kyc::where('natid', auth()->user()->natid)->firstOrFail();

        if ($kyc->cbz_status == 0){
            $response = ['message' => 'KYC has not yet been reviewed.'];
            return response($response, 200);
        } elseif ($kyc->cbz_status == 1) {
            $response = ['message' => 'KYC has been approved. You can apply for your loan.'];
            return response($response, 200);
        } else {
            $response = ['message' => 'Your KYC has been rejected.'];
            return response($response, 200);
        }
    }

    public function whoAmI($mobile){
        $user = User::where('bot_number', $mobile)
            ->where('deleted_at', null)
            ->where('activated', true)
            ->where('password', '!=', null)
            ->first();

        if ($user){
            $token = $user->createToken('eShagi Password Grant Client')->accessToken;
            $response = ['user' => $user, 'token' => $token];
            return response($response, 200);
        } else {
            $response = ['user' => null];
            return response($response, 404);
        }
    }
}
