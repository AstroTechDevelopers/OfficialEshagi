<?php

namespace App\Http\Controllers;

use App\Events\NewZimbabweClient;
use App\Models\Bank;
use App\Models\Client;
use App\Models\Creditlimit;
use App\Models\Kyc;
use App\Models\Lead;
use App\Models\Loan;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\Profile;
use App\Models\SsbDetail;
use App\Models\User;
use App\Models\UserOtp;
use App\Traits\ActivationTrait;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use jeremykenedy\LaravelRoles\Models\Role;
use Auth;
use Mail;

class ClientController extends Controller
{
    use \App\Traits\CaptchaTrait;
    use ActivationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();
        return view('clients.clients', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bank = Bank::where('id', $kyc->bank)->first();
        $ssbDetails = SsbDetail::where('natid', $client->natid)->first();
        return view('clients.client-info', compact('client', 'kyc', 'bank', 'ssbDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $banks = DB::table('banks')
            ->groupBy('bank')
            ->get();

        $kyc = Kyc::where('natid', $client->natid)->first();
        if($kyc == null){
            $client = Client::where('id', $client->id)->first();
            $banks = DB::table('banks')
                ->groupBy('bank')
                ->where('locale_id','=',auth()->user()->locale)
                ->get();
            $locale = Localel::find(auth()->user()->locale);

            return view('clients.register-for-clienttwo', compact('client','banks', 'locale'));
        } elseif ($kyc->national_pic == null OR $kyc->passport_pic == null OR $kyc->payslip_pic== null) {
            $yuser = Kyc::where('natid', $client->natid)->first();
            return view('clients.register-client-kyc', compact( 'yuser'));
        }
        $ssbDetail = SsbDetail::where('natid', $client->natid)->first();

        return view('clients.edit-client', compact('client', 'banks', 'kyc','ssbDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        ActivityLogger::activity(auth()->user()->name . " has attempted to edit client " . $client->natid . " with client ID: " . $client->id);
        $user = User::where('natid', $client->natid)->first();
        if ($client->first_name != $_POST['first_name'] OR $client->last_name != $_POST['last_name']) {
            $parts = explode(' ', $_POST['first_name'].' '.$_POST['last_name']);
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
        } else {
            $name=$user->name;
        }


        $locale = Localel::where('country',$request['country'])->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:clients,natid,'.$client->id,
                'email'                 => 'nullable|email|max:255|unique:clients,email,'.$client->id,
                'mobile'                 => 'required|max:10|unique:clients,mobile,'.$client->id,
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_state'        => 'required',
                'dependants'        => 'required|numeric',
                'emp_sector'        => 'required',
                'employer'        => 'required',
                'ecnumber'        => 'required',
                'gross'        => 'required',
                'salary'        => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with AstroCred.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with AstroCred.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with AstroCred.',
                'dob.required' => 'We need your date of birth',
                'dob.date' => 'The correct date format should be dd-mm-yyyy',
                'gender.required' => 'What is your gender?',
                'marital_state.required' => 'What is your marital status?',
                'dependants.required' => 'Do you have any dependants?',
                'emp_sector.required' => 'Which employment sector are you in?',
                'employer.required' => 'What is the name  of your employer?',
                'ecnumber.required' => 'At your workplace, what is your employment number?',
                'gross.required' => 'What is your gross monthly salary?',
                'salary.required' => 'What is your net monthly salary?',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        if ($client->natid != $request['natid']) {
            $kycUpdate = Kyc::where('natid', $client->natid)->first();
            $kycUpdate->natid = $request['natid'];
            $kycUpdate->save();

            $ssbUpdate = SsbDetail::where('natid', $client->natid)->first();
            $ssbUpdate->natid = $request['natid'];
            $ssbUpdate->save();
        }

        if ($client->ecnumber != $request['ecnumber']) {
            $ssbUpdate2 = SsbDetail::where('natid', $client->natid)->first();
            $ssbUpdate2->ecnumber = $request['ecnumber'];
            $ssbUpdate2->save();
        }

        $client->title = $request['title'];
        $client->first_name = strip_tags($request['first_name']);
        $client->last_name = strip_tags($request['last_name']);
        $client->natid = strtoupper($request['natid']);
        $client->email = $request['email'];
        $client->mobile = $request['mobile'];
        $client->dob = $request['dob'];
        $client->gender = $request['gender'];
        $client->marital_state = $request['marital_state'];
        $client->dependants = $request['dependants'];
        $client->nationality = $request['nationality'];
        $client->house_num = $request['house_num'];
        $client->street = $request['street'];
        $client->surburb = $request['surburb'];
        $client->city = $request['city'];
        $client->province = $request['province'];
        $client->country = $request['country'];
        $client->locale_id = $locale->id;
        $client->emp_sector = $request['emp_sector'];
        $client->employer = $request['employer'];
        $client->ecnumber = $request['ecnumber'];
        $client->home_type = $request['home_type'];

        $client->save();

        if ($client->save()) {
            $user->name = $name;
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->natid = strtoupper($request['natid']);
            $user->mobile = $request['mobile'];
            $user->updated_ip_address = $ipAddress;
            $user->save();
            ActivityLogger::activity(auth()->user()->name . " has modified client " . $client->natid . " with client ID: " . $client->id);
        }

        return redirect()->back()->with('success', 'Client details updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        if ($client->delete()) {
            $user = User::where('natid', $client->natid)->first();
            $user->delete();

            $kyc = Kyc::where('natid', $client->natid)->first();
            $kyc->delete();

            $ssbDetail = SsbDetail::where('natid', $client->natid)->first();
            $ssbDetail->delete();
        }
        return redirect('clients')->with('success', 'Client deleted successfully.');
    }

    public function registerOne(){
        $countries = Localel::all();
        return view('clients.register-client', compact('countries'));
    }

    public function postRegisterOne(Request $request){
        if ($request->input('salary') > $request->input('gross')) {
            return redirect()->back()->with('error', 'Sorry, Net salary cannot be greater than gross salary')->withInput();
        }

        $name = generateUsername($_POST['first_name'], $_POST['last_name']);

        $locale = Localel::where('id',$request['country'])->firstOrFail();
        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:users',
                'email'                 => 'nullable|email|max:255|unique:users',
                'mobile'                 => 'required|max:10|unique:users',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_state'        => 'required',
                'dependants'        => 'required|numeric',
                'emp_sector'        => 'required',
                'employer'        => 'required',
                'ecnumber'        => 'required',
                'gross'        => 'required',
                'salary'        => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with AstroCred.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with AstroCred.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with AstroCred.',
                'dob.required' => 'We need your date of birth',
                'dob.date' => 'The correct date format should be dd-mm-yyyy',
                'gender.required' => 'What is your gender?',
                'marital_state.required' => 'What is your marital status?',
                'dependants.required' => 'Do you have any dependants?',
                'emp_sector.required' => 'Which employment sector are you in?',
                'employer.required' => 'What is the name  of your employer?',
                'ecnumber.required' => 'At your workplace, what is your employment number?',
                'gross.required' => 'What is your gross monthly salary?',
                'salary.required' => 'What is your net monthly salary?',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
                'password.required'             => trans('auth.passwordRequired'),
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $client = Client::create([
            'creator'              => 'Self',
            'title'              => $request['title'],
            'first_name'        => strip_tags($request['first_name']),
            'last_name'         => strip_tags($request['last_name']),
            'natid'             => strtoupper($request['natid']),
            'email'             => $request['email'],
            'mobile'             => $request['mobile'],
            'dob'             => $request['dob'],
            'gender'             => $request['gender'],
            'marital_state'             => $request['marital_state'],
            'dependants'             => $request['dependants'],
            'nationality'             => $request['nationality'],
            'house_num'             => $request['house_num'],
            'street'             => $request['street'],
            'surburb'             => $request['surburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $locale->country,
            'locale_id'             => $locale->id,
            'emp_sector'             => $request['emp_sector'],
            'employer'             => $request['employer'],
            'ecnumber'             => $request['ecnumber'],
            'gross'             => $request['gross'],
            'salary'             => $request['salary'],
            'cred_limit'             => number_format(getCreditRate()*$request['salary'],2,'.',''),
            'usd_cred_limit'       => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
            'home_type'             => $request['home_type'],
        ]);

        $client->save();

        if ($client->save()) {
            $limit = Creditlimit::create([
                'client_id'             => $client->id,
                'grossSalary'             => $request->input('gross'),
                'netSalary'             => $request->input('salary'),
                'creditlimit'             => number_format(getCreditRate()*$request['salary'],2,'.',''),
                // 'usdGrossSalary'             => $request->input('usd_gross'),
                // 'usdNetSalary'             => $request->input('usd_salary'),
                'usdCreditlimit'             => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
                'reason'             => 'New Client which did not have credit limit record.',
            ]);

            $limit->save();

            $user = User::create([
                'name'              => $name,
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'email'             => $request['email'],
                'natid'             => strtoupper($request['natid']),
                'mobile'             => $request['mobile'],
                'utype'             => 'Client',
                'locale'             => $locale->id,
                'password'          => Hash::make($request['password']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress,
                'activated'         => $activated,
            ]);

            $user->attachRole($role);
            //$this->initiateEmailActivation($user);

            $profile = new Profile();
            $user->profile()->save($profile);

            Auth::login($user);
        }

        return redirect('home');
    }

    public function registerTwo(){
        $banks = DB::table('banks')
            //->where('locale_id', '=', auth()->user()->locale)
            //->groupBy('bank')
            ->get();
        $countries = Localel::all();

        return view('clients.register-two', compact('banks', 'countries'));
    }

    public function getBranches($id) {

        $branches = DB::table("bank_branches")
            ->where("bank_id", $id)
            ->select("id", "branch", "branch_code")
            ->orderBy("branch", 'asc')
            ->get();

        return response()->json($branches);
    }

    public function postRegisterTwo(Request $request){
        //echo 486;die();
        $validator = Validator::make($request->all(),
            [
                'kin_title'                  => 'required',
                'kin_fname'            => 'required',
                'kin_lname'             => 'required',
                'kin_email'                 => 'nullable|email|max:255',
                'kin_work'                 => 'required',
                'kin_number'                 => 'required',
                'relationship' => 'required',
                'bank'                 => 'required',
                'bank_acc_name'                 => 'required',
                'branch'                 => 'required',
                'branch_code'                 => 'required',
                'acc_number'                 => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'resduration'        => 'required|numeric',
            ],
            [
                'kin_title.required'           => 'Please select a title.',
                'kin_fname.required'           => 'What is the first name for your next of kin?',
                'kin_lname.required'            => 'What is the last name for your next of kin?',
                'kin_email.email'                   => trans('auth.emailInvalid'),
                'kin_work.required'                   => 'We need to know where they work.',
                'kin_number.required'                   => 'What is your next of kin\'s mobile number?',
                'relationship.required' => 'How Kin is related to you?',
                'bank.required'                   => 'Which bank do you use?',
                'bank_acc_name.required'                   => 'What in the name linked to the bank account?',
                'branch.required'                   => 'Please select your branch',
                'branch_code.required'                   => 'Please select your branch, if your branch code did not show',
                'acc_number.required'                   => 'Please state your account number',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
                'resduration.required' => 'No. of months you have stayed at this residences?',
            ]
        );

        if ($validator->fails()) {
            //echo 533;die();
            return redirect('remaining-details')->withErrors($validator)->withInput();
        }

        $client = Client::where('natid',auth()->user()->natid)->firstOrFail();

        if ($client->mobile == $request['kin_number']) {
            return redirect('remaining-details')->withInput()->with('error', 'You cannot use your mobile number as a next of kin number. Please use another number');
        }
        //echo 542;die();

		$relationship='';
		if($request['relationship']=='other'){
			$relationship = $request['relationship1'];
		} else{
			$relationship = $request['relationship'];
		}

        $kyc = Kyc::create([
            'user_id' => $client->id,
            'natid' => $client->natid,
            'kin_title'              => $request['kin_title'],
            'kin_fname'        => $request['kin_fname'],
            'kin_lname'         => $request['kin_lname'],
            'kin_email'             => $request['kin_email'],
            'kin_work'             => $request['kin_work'],
            'kin_number'             => $request['kin_number'],
            'relationship' => $relationship,
            'bank'             => $request['bank'],
            'bank_acc_name'             => $request['bank_acc_name'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'acc_number'             => $request['acc_number'],
            'house_num'             => $request['house_num'],
            'street'             => $request['street'],
            'surburb'             => $request['surburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $request['country'],
            'home_type'             => $request['home_type'],
            'res_duration' => $request['resduration'],
        ]);
        $kyc->save();

        if ($kyc->save()) {
            $ssbInfo = SsbDetail::create([
                'natid' => auth()->user()->natid,
                'ecnumber' => $client->ecnumber,
            ]);
            $ssbInfo->save();
        }

        return redirect('kyc-documents');
    }

    public function registerThree(){
        $yuser = Kyc::where('natid', auth()->user()->natid)->first();

        return view('clients.register-three', compact('yuser'));
    }

    function uploadNationalID(Request $request){

        $validator = Validator::make($request->all(),
            [
                'natid'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'natid.max'                 => 'Frontside National ID file should not be greater than 4MB.',
                'natid.mimes'               => 'Frontside National ID file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );
        if ($validator->fails()) {
            return redirect('kyc-documents')->withErrors($validator)->withInput();
        }

        if($request->hasFile('natid')) {

            if ($request->file('natid')->isValid()) {

                $nationalId = $request->file('natid');
                if (auth()->user()->locale == '2'){
                    $filename = 'front_' . str_replace("/","_",auth()->user()->natid) . '.' . $nationalId->getClientOriginalExtension();
                } else {
                    $filename = 'front_' . auth()->user()->natid . '.' . $nationalId->getClientOriginalExtension();
                }
                Storage::disk('public')->put('nationalids/' . $filename, File::get($nationalId));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->national_pic = $filename;
                $user->national_stat = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return redirect('kyc-documents')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('kyc-documents')->with('error','No file was detected here.');
        }

        return redirect('kyc-documents')->with('success','Frontside National ID uploaded successfully.');
    }

    function uploadNationalIDBack(Request $request){
        $validator = Validator::make($request->all(),
            [
                'natidback'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'natidback.max'                 => 'Backside National ID file should not be greater than 4MB.',
                'natidback.mimes'               => 'Backside National ID file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect('kyc-documents')->withErrors($validator)->withInput();
        }

        if($request->hasFile('natidback')) {

            if ($request->file('natidback')->isValid()) {

                $nationalIdBack = $request->file('natidback');
                if (auth()->user()->locale == '2'){
                    $filename = 'back_' . str_replace("/","_",auth()->user()->natid) . '.' . $nationalIdBack->getClientOriginalExtension();
                } else {
                    $filename = 'back_' . auth()->user()->natid . '.' . $nationalIdBack->getClientOriginalExtension();
                }
                Storage::disk('public')->put('nationalids/' . $filename, File::get($nationalIdBack));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->national_pic_back = $filename;
                $user->national_stat_back = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return redirect('kyc-documents')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('kyc-documents')->with('error','No file was detected here.');
        }

        return redirect('kyc-documents')->with('success','Backside National ID uploaded successfully.');
    }

    function uploadPassportPhoto(Request $request)
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
            return redirect('kyc-documents')->withErrors($validator)->withInput();
        }

        if($request->hasFile('passport')) {

            if ($request->file('passport')->isValid()) {

                $passPhoto = $request->file('passport');
                if (auth()->user()->locale == '2'){
                    $filename = str_replace("/","_",auth()->user()->natid) . '.' . $passPhoto->getClientOriginalExtension();
                } else {
                    $filename = auth()->user()->natid . '.' . $passPhoto->getClientOriginalExtension();
                }
                Storage::disk('public')->put('pphotos/' . $filename, File::get($passPhoto));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->passport_pic = $filename;
                $user->passport_stat = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return redirect('kyc-documents')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('kyc-documents')->with('error','No file was detected here.');
        }

        return redirect('kyc-documents')->with('success','Passport Photo uploaded successfully.');

    }

    function uploadCurrentPayslip(Request $request)
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
            return redirect('kyc-documents')->withErrors($validator)->withInput();
        }

        if($request->hasFile('payslip')) {

            if ($request->file('payslip')->isValid()) {

                $payslip = $request->file('payslip');
                if (auth()->user()->locale == '2'){
                    $filename = str_replace("/","_",auth()->user()->natid) . '.' . $payslip->getClientOriginalExtension();
                } else {
                    $filename = auth()->user()->natid . '.' . $payslip->getClientOriginalExtension();
                }
                Storage::disk('public')->put('payslips/' . $filename, File::get($payslip));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->payslip_pic = $filename;
                $user->payslip_stat = true;

                if($request->hasFile('payslip2') && $request->file('payslip2')->isValid()) {
                   $payslip2 = $request->file('payslip2');
                   if (auth()->user()->locale == '2'){
                      $filename2 = 'ps_2' . str_replace("/","_",auth()->user()->natid) . '.' . $payslip2->getClientOriginalExtension();
                   } else {
                      $filename2 = 'ps_2' . auth()->user()->natid . '.' . $payslip2->getClientOriginalExtension();
                   }
                   Storage::disk('public')->put('payslips/' . $filename2, File::get($payslip2));
                   $user->payslip_pic_2 = $filename2;
                   $user->payslip_stat_2 = true;
                }

                if($request->hasFile('payslip3') && $request->file('payslip3')->isValid()) {
                    $payslip3 = $request->file('payslip3');
                    if (auth()->user()->locale == '2'){
                       $filename3 = 'ps_3' . str_replace("/","_",auth()->user()->natid) . '.' . $payslip3->getClientOriginalExtension();
                    } else {
                       $filename3 = 'ps_3' . auth()->user()->natid . '.' . $payslip3->getClientOriginalExtension();
                    }
                    Storage::disk('public')->put('payslips/' . $filename3, File::get($payslip3));
                    $user->payslip_pic_3 = $filename3;
                    $user->payslip_stat_3 = true;
                 }

                 if($request->hasFile('payslip4') && $request->file('payslip4')->isValid()) {
                    $payslip4 = $request->file('payslip4');
                    if (auth()->user()->locale == '2'){
                       $filename4 = 'ps_4' . str_replace("/","_",auth()->user()->natid) . '.' . $payslip4->getClientOriginalExtension();
                    } else {
                       $filename4 = 'ps_4' . auth()->user()->natid . '.' . $payslip4->getClientOriginalExtension();
                    }
                    Storage::disk('public')->put('payslips/' . $filename4, File::get($payslip4));
                    $user->payslip_pic_4 = $filename4;
                    $user->payslip_stat_4 = true;
                 }

                $user->updated_at = now();
                $user->save();

            } else {
                return redirect('kyc-documents')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('kyc-documents')->with('error','No file was detected here.');
        }

        return redirect('kyc-documents')->with('success','Payslip uploaded successfully.');

    }

    function uploadProofOfRes(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'proofres'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'proofres.max'                 => 'Proof of residence should not be greater than 4MB.',
                'proofres.mimes'               => 'Proof of residence should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect('kyc-documents')->withErrors($validator)->withInput();
        }

        if($request->hasFile('proofres')) {

            if ($request->file('proofres')->isValid()) {

                $proofres = $request->file('proofres');
                if (auth()->user()->locale == '2'){
                    $filename = str_replace("/","_",auth()->user()->natid) . '.' . $proofres->getClientOriginalExtension();
                } else {
                    $filename = auth()->user()->natid . '.' . $proofres->getClientOriginalExtension();
                }
                Storage::disk('public')->put('proofres/' . $filename, File::get($proofres));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->proofres = $filename;
                $user->proofres_stat = true;
                $user->updated_at = now();

                $user->save();

            } else {
                return redirect('kyc-documents')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('kyc-documents')->with('error','No file was detected here.');
        }

        return redirect('kyc-documents')->with('success','Proof of residence uploaded successfully.');

    }

    function uploadEmpApproval(Request $request){
        $validator = Validator::make($request->all(),
            [
                'emp_letter'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'emp_letter.max' => 'Employee approval letter file should not be greater than 4MB.',
                'emp_letter.mimes' => 'Employee approal letter file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect('kyc-documents')->withErrors($validator)->withInput();
        }

        if($request->hasFile('emp_letter')) {

            if ($request->file('emp_letter')->isValid()) {

                $empLetter = $request->file('emp_letter');
                if (auth()->user()->locale == '2'){
                    $filename = str_replace("/","_",auth()->user()->natid) . '.' . $empLetter->getClientOriginalExtension();
                } else {
                    $filename = auth()->user()->natid . '.' . $empLetter->getClientOriginalExtension();
                }
                Storage::disk('public')->put('empletters/' . $filename, File::get($empLetter));

                $user = Kyc::where('natid', auth()->user()->natid)->first();

                $user->emp_approval_letter = $filename;
                $user->emp_approval_stat = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return redirect('kyc-documents')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('kyc-documents')->with('error','No file was detected here.');
        }

        return redirect('kyc-documents')->with('success','Employee approval letter uploaded successfully.');
    }

    public function quicklyContinueClientReg($natid){
        $user = User::where('natid', $natid)->first();
        $lead = Lead::where('natid',$natid)->first();
        $locale = Localel::find(auth()->user()->locale);
        $countries = Localel::all();
        return view('clients.quickly-continue-client', compact('locale', 'countries', 'user', 'lead'));
    }

    public function registerForClientOne(){
        $locale = Localel::find(auth()->user()->locale);
        $countries = Localel::all();
        return view('clients.register-for-client', compact('locale', 'countries'));
    }

    public function postRegisterOneForm(Request $request) {
        /*if ($request->input('salary') > $request->input('gross')) {
            return redirect()->back()->with('error', 'Sorry, Net salary cannot be greater than gross salary')->withInput();
        }

        $name = generateUsername($_POST['first_name'], $_POST['last_name']);

        $locale = Localel::where('id',1)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:users',
                'email'                 => 'nullable|email|max:255|unique:users',
                'mobile'                 => 'required|max:10|unique:users',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_state'        => 'required',
                'dependants'        => 'required|numeric',
                'children'        => 'required|numeric',
                'emp_sector'        => 'required',
                'employer'        => 'required',
                'designation'        => 'required',
                'ecnumber'        => 'required',
                'gross'        => 'required',
                'salary'        => 'required',
                'emp_nature' => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'resduration'        => 'required|numeric',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with AstroCred.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with AstroCred.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with AstroCred.',
                'dob.required' => 'We need your date of birth',
                'dob.date' => 'The correct date format should be dd-mm-yyyy',
                'gender.required' => 'What is your gender?',
                'marital_state.required' => 'What is your marital status?',
                'dependants.required' => 'Do you have any dependants?',
                'children.required' => 'Do you have any children?',
                'emp_sector.required' => 'Which employment sector are you in?',
                'employer.required' => 'What is the name  of your employer?',
                'ecnumber.required' => 'At your workplace, what is your employment number?',
                'designation.required' => 'At your workplace, what is your designation?',
                'gross.required' => 'What is your gross monthly salary?',
                'salary.required' => 'What is your net monthly salary?',
                'emp_nature.required' => 'What is nature of your employment?',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
                'resduration.required' => 'No. of months you have stayed at this residences?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $newPass = str_random(8);
        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $client = Client::create([
            'creator'              => auth()->user()->name,
            'title'              => $request['title'],
            'first_name'        => strip_tags($request['first_name']),
            'last_name'         => strip_tags($request['last_name']),
            'natid'             => strtoupper($request['natid']),
            'email'             => $request['email'],
            'mobile'             => $request['mobile'],
            'dob'             => $request['dob'],
            'gender'             => $request['gender'],
            'marital_state'             => $request['marital_state'],
            'dependants'             => $request['dependants'],
            'children'             => $request['children'],
            'nationality'             => $request['nationality'],
            'house_num'             => $request['house_num'],
            'street'             => $request['street'],
            'surburb'             => $request['surburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $locale->country,
            'locale_id'             => $locale->id,
            'emp_sector'             => $request['emp_sector'],
            'employer'             => $request['employer'],
            'ecnumber'             => $request['ecnumber'],
            'designation' => $request['designation'],
            'gross'             => $request['gross'],
            'salary'             => $request['salary'],
            'cred_limit'             => number_format(getCreditRate()*$request['salary'],2,'.',''),
            'usd_cred_limit'             => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
            'home_type'             => $request['home_type'],
            'res_duration' => $request['resduration'],
        ]);
        $client->save();

        if ($client->save()) {
            $limit = Creditlimit::create([
                'client_id'             => $client->id,
                'grossSalary'             => $request->input('gross'),
                'netSalary'             => $request->input('salary'),
                'creditlimit'             => number_format(getCreditRate()*$request['salary'],2,'.',''),
                'reason'             => 'New Client which did not have credit limit record.',
            ]);

            $limit->save();

            $user = User::create([
                'name'              => $name,
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'email'             => $request['email'],
                'natid'             => strtoupper($request['natid']),
                'mobile'             => $request['mobile'],
                'utype'             => 'Client',
                'locale'             => auth()->user()->locale,
                'password'          => Hash::make($newPass),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress,
                'activated'         => $activated,
            ]);

            $user->attachRole($role);
            event(new NewZimbabweClient($client, $newPass));
            //$this->initiateEmailActivation($user);

            $profile = new Profile();
            $user->profile()->save($profile);

        }

        $banks = DB::table('banks')->get();
        $locale = Localel::find(auth()->user()->locale);
        */

        if ($request->input('salary') > $request->input('gross')) {
            return redirect()->back()->with('error', 'Sorry, Net salary cannot be greater than gross salary')->withInput();
        }

        // if ($request->input('usd_salary') > $request->input('usd_gross')) {
        //     return redirect()->back()->with('error', 'Sorry, Net USD salary cannot be greater than gross salary')->withInput();
        // }

        $name = generateUsername($_POST['first_name'], $_POST['last_name']);


        $locale = Localel::where('country',$request['country'])->firstOrFail();
        $settings = Masetting::find(1)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:clients',
                'email'                 => 'nullable|email|max:255|unique:clients',
                'mobile'                 => 'required|max:10|unique:clients',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_state'        => 'required',
                'dependants'        => 'required|numeric',
                'children'        => 'required|numeric',
                'emp_sector'        => 'required',
                'employer'        => 'required',
                'designation'        => 'required',
                'ecnumber'        => 'required',
                'gross'        => 'required',
                'salary'        => 'required',
                'emp_nature' => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'resduration'        => 'required|numeric',
                //'loan_purpose'             => 'required',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with AstroCred.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with AstroCred.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with AstroCred.',
                'dob.required' => 'We need your date of birth',
                'dob.date' => 'The correct date format should be dd-mm-yyyy',
                'gender.required' => 'What is your gender?',
                'marital_state.required' => 'What is your marital status?',
                'dependants.required' => 'Do you have any dependants?',
                'children.required' => 'Do you have any children?',
                'emp_sector.required' => 'Which employment sector are you in?',
                'employer.required' => 'What is the name  of your employer?',
                'ecnumber.required' => 'At your workplace, what is your employment number?',
                'designation.required' => 'At your workplace, what is your designation?',
                'gross.required' => 'What is your gross monthly salary?',
                'salary.required' => 'What is your net monthly salary?',
                'emp_nature.required' => 'What is nature of your employment?',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
                'resduration.required' => 'No. of months you have stayed at this residences?',
                //'loan_purpose' => 'What is your purpose to apply for the loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $newPass = str_random(8);
        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $client = Client::create([
            'creator'              => 'Self',
            'title'              => $request['title'],
            'first_name'        => strip_tags($request['first_name']),
            'last_name'         => strip_tags($request['last_name']),
            'natid'             => strtoupper($request['natid']),
            'email'             => $request['email'],
            'mobile'             => $request['mobile'],
            'dob'             => $request['dob'],
            'gender'             => $request['gender'],
            'marital_state'             => $request['marital_state'],
            'dependants'             => $request['dependants'],
            'children'             => $request['children'],
            'nationality'             => $request['nationality'],
            'house_num'             => $request['house_num'],
            'street'             => $request['street'],
            'surburb'             => $request['surburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $request['country'],
            'locale_id'             => $locale->id,
            'emp_sector'             => $request['emp_sector'],
            'employer'             => $request['employer'],
            'ecnumber'             => $request['ecnumber'],
            'designation' => $request['designation'],
            'gross'             => $request['gross'],
            'salary'             => $request['salary'],
            'emp_nature' => $request['emp_nature'],
            'cred_limit'             => number_format($settings->creditRate*$request['salary'],2,'.',''),
            // 'usd_gross'             => $request['usd_gross'],
            // 'usd_salary'             => $request['usd_salary'],
            'usd_cred_limit'             => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
            'home_type'             => $request['home_type'],
            'res_duration' => $request['resduration'],
            //'loan_purpose' => 'default',
        ]);
        $client->save();
        if ($client->save()) {
            $limit = Creditlimit::create([
                'client_id'             => $client->id,
                'grossSalary'             => $request->input('gross'),
                'netSalary'             => $request->input('salary'),
                'creditlimit'             => number_format($settings->creditRate*$request['salary'],2,'.',''),
                // 'usdGrossSalary'             => $request->input('usd_gross'),
                // 'usdNetSalary'             => $request->input('usd_salary'),
                // 'usdCreditlimit'             => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
                'reason'             => 'New Client which did not have credit limit record.',
            ]);

            $limit->save();

            $user = User::create([
               'name'              => $name,
               'first_name'        => $request['first_name'],
               'last_name'         => $request['last_name'],
               'email'             => $request['email'],
               'natid'             => strtoupper($request['natid']),
               'mobile'             => $request['mobile'],
               'utype'             => 'Client',
               'locale'             => auth()->user()->locale,
               'password'          => Hash::make($newPass),
               'token'             => str_random(64),
               'signup_ip_address' => $ipAddress,
               'activated'         => $activated,
               'status'         => $activated,
            ]);

            $user->attachRole($role);
            event(new NewZimbabweClient($client, $newPass));
            //$this->initiateEmailActivation($user);

           $profile = new Profile();
           $user->profile()->save($profile);
        }

        return redirect('step-two-registering/'.$client->id);
        //return redirect('remaining-details');
        //return view('clients.register-for-clienttwo', compact('client', 'banks', 'locale'));
    }

    public function registerForClientTwo($id) {
        $client = Client::where('id', $id)->first();
        $banks = DB::table('banks')
            //->groupBy('bank')
            //->where('locale_id','=',auth()->user()->locale)
            ->get();
        $locale = Localel::find(auth()->user()->locale);
        $countries = Localel::all();

        return view('clients.register-for-clienttwo', compact('client', 'banks', 'locale', 'countries'));
    }

    /*public function registerForClientTwoWithError($id) {
        $client = Client::where('id', $id)->first();
        $banks = DB::table('banks')
            ->groupBy('bank')
            ->where('locale_id','=',auth()->user()->locale)
            ->get();
        $locale = Localel::find(auth()->user()->locale);

        return view('clients.register-for-clienttwo', compact('client','banks', 'locale'));
    }*/

    public function postRegisterTwoForm(Request $request) {
        /*$validator = Validator::make($request->all(),
            [
                'natid'                  => 'required|max:15|unique:kycs',
                'kin_title'                  => 'required',
                'kin_fname'            => 'required',
                'kin_lname'             => 'required',
                'kin_email'                 => 'nullable|email|max:255',
                'kin_work'                 => 'required',
                'kin_number'                 => 'required',
                'relationship' => 'required',
                'bank'                 => 'required',
                'bank_acc_name'                 => 'required',
                'branch'                 => 'required',
                'branch_code'                 => 'required',
                'acc_number'                 => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'resduration'        => 'required|numeric',
            ],
            [
                'natid.required'                => 'We need client National ID Number to proceed. Did you follow the correct steps?',
                'natid.unique'                   => 'We already have the next of kin details for this client.',
                'kin_title.required'           => 'Please select a title.',
                'kin_fname.required'           => 'What is the first name for your next of kin?',
                'kin_lname.required'            => 'What is the last name for your next of kin?',
                'kin_email.email'                   => trans('auth.emailInvalid'),
                'kin_work.required'                   => 'We need to know where they work.',
                'kin_number.required'                   => 'What is your next of kin\'s mobile number?',
                'relationship.required' => 'How Kin is related to you?',
                'bank.required'                   => 'Which bank do you use?',
                'bank_acc_name.required'                   => 'What in the name linked to the bank account?',
                'branch.required'                   => 'Please select your branch',
                'branch_code.required'                   => 'Please select your branch, if your branch code did not show',
                'acc_number.required'                   => 'Please state your account number',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
                'resduration.required' => 'No. of months you have stayed at this residences?',
            ]
        );
        $client = Client::where('natid',$request['natid'])->firstOrFail();

        if ($validator->fails()) {
            return redirect('step-two-registering/'.$client->id)->withErrors($validator)->withInput();
        }


        $banks = DB::table('banks')
            ->get();
        $locale = Localel::find(auth()->user()->locale);

        $data = [
            'banks' => $banks,
            'locale' => $locale,
            'error' => 'You cannot use client mobile number as a next of kin number for client. Please use another number.'
        ];

        if ($client->mobile == $request['kin_number']) {
            return redirect('step-two-registering/'.$client->id)->withInput()->with($data);
        }

        $relationship='';
		if($request['relationship']=='other'){
			$relationship = $request['relationship1'];
		} else{
			$relationship = $request['relationship'];
		}

        $kyc = Kyc::create([
            'user_id' => $client->id,
            'natid' => $request->natid,
            'kin_title'              => $request['kin_title'],
            'kin_fname'        => $request['kin_fname'],
            'kin_lname'         => $request['kin_lname'],
            'kin_email'             => $request['kin_email'],
            'kin_work'             => $request['kin_work'],
            'kin_number'             => $request['kin_number'],
            'relationship' => $relationship,
            'bank'             => $request['bank'],
            'bank_acc_name'             => $request['bank_acc_name'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'acc_number'             => $request['acc_number'],
            'house_num'             => $request['house_num'],
            'street'             => $request['street'],
            'surburb'             => $request['surburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $request['country'],
            'home_type'             => $request['home_type'],
            'res_duration' => $request['resduration'],
        ]);
        $kyc->save();

        if ($kyc->save()) {
            $ssbInfo = SsbDetail::create([
                'natid' => $request['natid'],
                'ecnumber' => $client->ecnumber,
            ]);
            $ssbInfo->save();
        }*/

        $validator = Validator::make($request->all(),
            [
                'natid'                  => 'required|max:15|unique:kycs',
                'kin_title'                  => 'required',
                'kin_fname'            => 'required',
                'kin_lname'             => 'required',
                'kin_email'                 => 'nullable|email|max:255',
                'kin_work'                 => 'required',
                'kin_number'                 => 'required',
                'relationship' => 'required',
                'bank'                 => 'required',
                'bank_acc_name'                 => 'required',
                'branch'                 => 'required',
                'branch_code'                 => 'required',
                'acc_number'                 => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'resduration'        => 'required|numeric',
            ],
            [
                'natid.required'                => 'We need client National ID Number to proceed. Did you follow the correct steps?',
                'natid.unique'                   => 'We already have the next of kin details for this client.',
                'kin_title.required'           => 'Please select a title.',
                'kin_fname.required'           => 'What is the first name for your next of kin?',
                'kin_lname.required'            => 'What is the last name for your next of kin?',
                'kin_email.email'                   => trans('auth.emailInvalid'),
                'kin_work.required'                   => 'We need to know where they work.',
                'kin_number.required'                   => 'What is your next of kin\'s mobile number?',
                'relationship.required' => 'How Kin is related to you?',
                'bank.required'                   => 'Which bank do you use?',
                'bank_acc_name.required'                   => 'What in the name linked to the bank account?',
                'branch.required'                   => 'Please select your branch',
                'branch_code.required'                   => 'Please select your branch, if your branch code did not show',
                'acc_number.required'                   => 'Please state your account number',
                'house_num.required' => 'State your house number where you reside',
                'street.required' => 'State the street name where you reside',
                'surburb.required' => 'Which surburb do you stay in?',
                'city.required' => 'Please state the city you\'re from?',
                'province.required' => 'Which province are you in?',
                'country.required' => 'State your country if?',
                'home_type.required' => 'What is the state of your home?',
                'resduration.required' => 'No. of months you have stayed at this residences?',
            ]
        );

        $client = Client::where('natid',$request['natid'])->firstOrFail();

        if ($validator->fails()) {
            return redirect('remaining-details')->withErrors($validator)->withInput();
        }

        $banks = DB::table('banks')
            ->get();
        $locale = Localel::find(auth()->user()->locale);

        $data = [
            'banks' => $banks,
            'locale' => $locale,
            'error' => 'You cannot use client mobile number as a next of kin number for client. Please use another number.'
        ];

        if ($client->mobile == $request['kin_number']) {
            return redirect('remaining-details')->withInput()->with('error', 'You cannot use your mobile number as a next of kin number. Please use another number');
        }

		$relationship='';
		if($request['relationship']=='other'){
			$relationship = $request['relationship1'];
		} else{
			$relationship = $request['relationship'];
		}

        $kyc = Kyc::create([
            'user_id' => $client->id,
            'natid' => $request->natid,
            'kin_title'              => $request['kin_title'],
            'kin_fname'        => $request['kin_fname'],
            'kin_lname'         => $request['kin_lname'],
            'kin_email'             => $request['kin_email'],
            'kin_work'             => $request['kin_work'],
            'kin_number'             => $request['kin_number'],
            'relationship' => $relationship,
            'bank'             => $request['bank'],
            'bank_acc_name'             => $request['bank_acc_name'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'acc_number'             => $request['acc_number'],
            'house_num'             => $request['house_num'],
            'street'             => $request['street'],
            'surburb'             => $request['surburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $request['country'],
            'home_type'             => $request['home_type'],
            'res_duration' => $request['resduration'],
        ]);
        $kyc->save();

        if ($kyc->save()) {
            $ssbInfo = SsbDetail::create([
                'natid' => $request->natid,
                'ecnumber' => $client->ecnumber,
            ]);
            $ssbInfo->save();
        }

        $yuser = Kyc::where('natid', $client->natid)->first();

        return view('clients.register-client-kyc', compact( 'yuser'));
    }

    public function registerForClientKycWithError($id) {
        $yuser = Kyc::where('id', $id)->first();
        return view('clients.register-client-kyc', compact( 'yuser'));
    }

    function uploadClientNationalID(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnationalid'  => 'required',
                'natid'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnationalid.required'                 => 'Client national ID number is required.',
                'natid.required'                 => 'National ID is required.',
                'natid.image'                 => 'National ID file should be an image.',
                'natid.max'                 => 'National ID file should not be greater than 4MB.',
                'natid.mimes'               => 'National ID file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );
        $yuser = Kyc::where('natid', $request->input('clientnationalid'))->first();
        $userModel = User::where('natid', $request->input('clientnationalid'))->first();

        if ($validator->fails()) {
            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->withErrors($validator)->withInput();
            }
            return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
        }

        if($request->hasFile('natid')) {

            if ($request->file('natid')->isValid()) {
                $nationalId = $request->file('natid');

                if ($userModel->locale == '2'){
                    $filename = str_replace("/","_",$request->input('clientnationalid')) . '.' . $nationalId->getClientOriginalExtension();
                } else {
                    $filename = $request->input('clientnationalid') . '.' . $nationalId->getClientOriginalExtension();
                }

                Storage::disk('public')->put('nationalids/' . $filename, File::get($nationalId));

                $yuser->national_pic = $filename;
                $yuser->national_stat = true;
                $yuser->updated_at = now();

                $yuser->save();
            } else {
                $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer')){
                   return redirect()->back()->with('error', 'Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }
        } else {
            $data = [
                'yuser' => $yuser,
                'error' => 'No file was detected here.'
            ];

            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->with('error', 'Invalid image supplied.');
            }
            return redirect('register-client-kyc')->with($data);
        }

        if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
           return redirect()->back()->with('success','Frontside National ID uploaded successfully.');
        }
        return view('clients.register-client-kyc', compact('yuser'));
    }

    function uploadClientNationalIDBack(Request $request){
        $validator = Validator::make($request->all(),
            [
                'clientnationalid'  => 'required',
                'natidback'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnationalid.required'                 => 'Client national ID number is required.',
                'natidback.max'                 => 'Backside National ID file should not be greater than 4MB.',
                'natidback.mimes'               => 'Backside National ID file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->withErrors($validator)->withInput();
            }
            return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
        }

        if($request->hasFile('natidback')) {
            $yuser = Kyc::where('natid', $request->input('clientnationalid'))->first();
            $userModel = User::where('natid', $request->input('clientnationalid'))->first();

            if ($request->file('natidback')->isValid()) {

                $nationalIdBack = $request->file('natidback');
                if ($userModel->locale == '2'){
                    $filename = 'back_' . str_replace("/","_",auth()->user()->natid) . '.' . $nationalIdBack->getClientOriginalExtension();
                } else {
                    $filename = 'back_' . auth()->user()->natid . '.' . $nationalIdBack->getClientOriginalExtension();
                }
                Storage::disk('public')->put('nationalids/' . $filename, File::get($nationalIdBack));

                $yuser->national_pic_back = $filename;
                $yuser->national_stat_back = true;
                $yuser->updated_at = now();

                $yuser->save();
            } else {
                $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error', 'Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }

        } else {
            $data = [
                    'yuser' => $yuser,
                    'error' => 'No file was detected here.'
                ];
            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->with('error', 'No file was detected here.');
            }
            return redirect('register-client-kyc')->with($data);
        }

        if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
           return redirect()->back()->with('success','Backside National ID uploaded successfully.');
        }
        return view('clients.register-client-kyc', compact('yuser'));
    }

    function uploadClientPassportPhoto(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'clientnationalid'  => 'required',
                'passport'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'passport.max'                 => 'Passport Photo file should not be greater than 4MB.',
                'passport.mimes'               => 'Passport Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        $yuser = Kyc::where('natid', $request->input('clientnationalid'))->first();
        $userModel = User::where('natid', $request->input('clientnationalid'))->first();

        if ($validator->fails()) {
            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->withErrors($validator)->withInput();
            }
            return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
        }

        if($request->hasFile('passport')) {

            if ($request->file('passport')->isValid()) {

                $passPhoto = $request->file('passport');
                if ($userModel->locale == '2'){
                    $filename = str_replace("/","_",$request->input('clientnationalid')) . '.' . $passPhoto->getClientOriginalExtension();
                } else {
                    $filename = $request->input('clientnationalid') . '.' . $passPhoto->getClientOriginalExtension();
                }
                Storage::disk('public')->put('pphotos/' . $filename, File::get($passPhoto));

                $yuser->passport_pic = $filename;
                $yuser->passport_stat = true;
                $yuser->updated_at = now();

                $yuser->save();

            } else {
                $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];
                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error', 'Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }
        } else {
            $data = [
                'yuser' => $yuser,
                'error' => 'No file was detected here.'
            ];

            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->with('error', 'No file was detected here.');
            }
            return redirect('register-client-kyc')->with($data);
        }

        if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
           return redirect()->back()->with('success','Passport size photo of client uploaded successfully.');
        }

        return view('clients.register-client-kyc', compact('yuser'));
    }

    function uploadClientCurrentPayslip(Request $request)
    {
        $yuser = Kyc::where('natid', $request->input('clientnationalid'))->first();
        $userModel = User::where('natid', $request->input('clientnationalid'))->first();

        if($request->hasFile('payslip')) {
            $validator = Validator::make($request->all(),
               [
                  'clientnationalid'  => 'required',
                  'payslip'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
               ],
               [
                  'payslip.max'                 => 'Payslip should not be greater than 4MB.',
                  'payslip.mimes'               => 'Payslip should of the format: jpeg,png,jpg,gif,svg.',
               ]
            );
            if ($validator->fails()) {
               if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                  return redirect()->back()->withErrors($validator)->withInput();
               }
               return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
            }

            if ($request->file('payslip')->isValid()) {
               $payslip = $request->file('payslip');
               if ($userModel->locale == '2'){
                  $filename = str_replace("/","_",$request->input('clientnationalid')) . '.' . $payslip->getClientOriginalExtension();
               } else {
                  $filename = $request->input('clientnationalid') . '.' . $payslip->getClientOriginalExtension();
               }
               Storage::disk('public')->put('payslips/' . $filename, File::get($payslip));

               $yuser->payslip_pic = $filename;
               $yuser->payslip_stat = true;
               $yuser->updated_at = now();
               $yuser->save();
            } else {
               $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error','Invalid image supplied.');
                }

                return redirect('register-client-kyc')->with($data);
            }
         }

         if($request->hasFile('payslip2')){
            $validator = Validator::make($request->all(),
               [
                  'clientnationalid'  => 'required',
                  'payslip2'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
               ],
               [
                  'payslip2.max'                 => 'Payslip should not be greater than 4MB.',
                  'payslip2.mimes'               => 'Payslip should of the format: jpeg,png,jpg,gif,svg.',
               ]
            );
            if ($validator->fails()) {
               if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                  return redirect()->back()->withErrors($validator)->withInput();
               }
               return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
            }

            if($request->file('payslip2')->isValid()) {
               $payslip2 = $request->file('payslip2');
               if ($userModel->locale == '2'){
                  $filename2 = 'ps_2' . str_replace("/","_",$request->input('clientnationalid')) . '.' . $payslip2->getClientOriginalExtension();
               } else {
                  $filename2 = 'ps_2' . $request->input('clientnationalid') . '.' . $payslip2->getClientOriginalExtension();
               }
               Storage::disk('public')->put('payslips/' . $filename2, File::get($payslip2));

               $yuser->payslip_pic_2 = $filename2;
               $yuser->payslip_stat_2 = true;
               $yuser->updated_at = now();
               $yuser->save();
            } else {
               $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error','Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }
         }

         if($request->hasFile('payslip3')){
            $validator = Validator::make($request->all(),
               [
                  'clientnationalid'  => 'required',
                  'payslip3'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
               ],
               [
                  'payslip3.max'                 => 'Payslip should not be greater than 4MB.',
                  'payslip3.mimes'               => 'Payslip should of the format: jpeg,png,jpg,gif,svg.',
               ]
            );
            if ($validator->fails()) {
               if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                  return redirect()->back()->withErrors($validator)->withInput();
               }
               return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
            }

            if($request->hasFile('payslip3') && $request->file('payslip3')->isValid()) {
               $payslip3 = $request->file('payslip3');
               if ($userModel->locale == '2'){
                  $filename3 = 'ps_3' . str_replace("/","_",$request->input('clientnationalid')) . '.' . $payslip3->getClientOriginalExtension();
               } else {
                  $filename3 = 'ps_3' . $request->input('clientnationalid') . '.' . $payslip3->getClientOriginalExtension();
               }
               Storage::disk('public')->put('payslips/' . $filename3, File::get($payslip3));

               $yuser->payslip_pic_3 = $filename3;
               $yuser->payslip_stat_3 = true;
               $yuser->updated_at = now();
               $yuser->save();
             } else {
                $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error','Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
             }
         }

         if($request->hasFile('payslip4')){
            $validator = Validator::make($request->all(),
               [
                  'clientnationalid'  => 'required',
                  'payslip4'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
               ],
               [
                  'payslip4.max'                 => 'Payslip should not be greater than 4MB.',
                  'payslip4.mimes'               => 'Payslip should of the format: jpeg,png,jpg,gif,svg.',
               ]
            );
            if ($validator->fails()) {
               if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                  return redirect()->back()->withErrors($validator)->withInput();
               }
               return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
            }

            if($request->hasFile('payslip4') && $request->file('payslip4')->isValid()) {
               $payslip4 = $request->file('payslip4');
               if ($userModel->locale == '2'){
                  $filename4 = 'ps_4' . str_replace("/","_",$request->input('clientnationalid')) . '.' . $payslip4->getClientOriginalExtension();
               } else {
                  $filename4 = 'ps_4' . $request->input('clientnationalid') . '.' . $payslip4->getClientOriginalExtension();
               }
               Storage::disk('public')->put('payslips/' . $filename4, File::get($payslip4));

               $yuser->payslip_pic_4 = $filename4;
               $yuser->payslip_stat_4 = true;
               $yuser->updated_at = now();
               $yuser->save();
            } else {
               $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error','Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }
         }

        if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
           return redirect()->back()->with('success','Salary slips of client uploaded successfully.');
        }
        return view('clients.register-client-kyc', compact('yuser'));
    }

    function uploadClientEmpApproval(Request $request){
        $yuser = Kyc::where('natid', $request->input('clientnationalid'))->first();
        $userModel = User::where('natid', $request->input('clientnationalid'))->first();

        $validator = Validator::make($request->all(),
            [
                'emp_letter'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'emp_letter.max' => 'Employee approval letter file should not be greater than 4MB.',
                'emp_letter.mimes' => 'Employee approal letter file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->withErrors($validator)->withInput();
            }
            return redirect('register-client-kyc/'.$yuser->id)->withErrors($validator);
        }

        if($request->hasFile('emp_letter')) {

            if ($request->file('emp_letter')->isValid()) {

                $empLetter = $request->file('emp_letter');

                if ($userModel->locale == '2'){
                    $filename = str_replace("/","_",$request->input('clientnationalid')) . '.' . $empLetter->getClientOriginalExtension();
                } else {
                    $filename = $request->input('clientnationalid') . '.' . $empLetter->getClientOriginalExtension();
                }
                Storage::disk('public')->put('empletters/' . $filename, File::get($empLetter));

                $yuser->emp_approval_letter = $filename;
                $yuser->emp_approval_stat = true;
                $yuser->updated_at = now();

                $yuser->save();
            } else {
                $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error','Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }

        } else {
            $data = [
                'yuser' => $yuser,
                'error' => 'No file was detected here.'
            ];

            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->with('error','No file was detected here.');
            }
            return redirect('register-client-kyc')->with($data);
        }

        if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
           return redirect()->back()->with('success','Employer letter uploaded successfully.');
        }
        return view('clients.register-client-kyc', compact('yuser'));
    }

    function uploadClientSignature(Request $request){
        $yuser = Kyc::where('natid', $request->input('clientnationalid'))->first();
        $userModel = User::where('natid', $request->input('clientnationalid'))->first();

        $validator = Validator::make($request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'signature.max' => 'Signature file should not be greater than 4MB.',
                'signature.mimes' => 'Signature should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
               return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {

                $signature = $request->file('signature');

                if ($userModel->locale == '2'){
                    $filename = str_replace("/","_",$request->input('clientnationalid')) . '.' . $signature->getClientOriginalExtension();
                } else {
                    $filename = $request->input('clientnationalid') . '.' . $signature->getClientOriginalExtension();
                }
                Storage::disk('public')->put('signatures/' . $filename, File::get($signature));

                $yuser->sign_pic = $filename;
                $yuser->sign_stat = true;
                $yuser->updated_at = now();

                $yuser->save();
            } else {
                $data = [
                    'yuser' => $yuser,
                    'error' => 'Invalid image supplied.'
                ];

                return redirect()->back()->with('error','Invalid image supplied.');
            }

        } else {
            $data = [
                'yuser' => $yuser,
                'error' => 'No file was detected here.'
            ];

            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect()->back()->with('success','Signature uploaded successfully.');
    }

    function uploadClientProofOfRes(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'clientnationalid'  => 'required',
                'proofres'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'proofres.max'                 => 'Proof of residence should not be greater than 4MB.',
                'proofres.mimes'               => 'Proof of residence should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        $user = Kyc::where('natid', $request->input('clientnationalid'))->first();
        $userModel = User::where('natid', $request->input('clientnationalid'))->first();


        if ($validator->fails()) {
            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->withErrors($validator)->withInput();
            }
            return redirect('register-client-kyc/'.$user->id)->withErrors($validator);
        }

        if($request->hasFile('proofres')) {

            if ($request->file('proofres')->isValid()) {

                $proofres = $request->file('proofres');
                if ($userModel->locale == '2'){
                    $filename = str_replace("/","_",$request->input('clientnationalid')) . '.' . $proofres->getClientOriginalExtension();
                } else {
                    $filename = $request->input('clientnationalid') . '.' . $proofres->getClientOriginalExtension();
                }
                Storage::disk('public')->put('proofres/' . $filename, File::get($proofres));

                $user->proofres_pic = $filename;
                $user->proofres_stat = true;
                $user->updated_at = now();

                $user->save();

            } else {
                $data = [
                    'yuser' => $user,
                    'error' => 'Invalid image supplied.'
                ];

                if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
                   return redirect()->back()->with('error', 'Invalid image supplied.');
                }
                return redirect('register-client-kyc')->with($data);
            }
        } else {
            $data = [
                'yuser' => $user,
                'error' => 'No file was detected here.'
            ];

            if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
               return redirect()->back()->with('error', 'No file was detected here.');
            }
            return redirect('register-client-kyc')->with($data);
        }

        if(auth()->user()->hasRole('loansofficer') || auth()->user()->hasRole('agent')){
           return redirect()->back()->with('success', 'Proof of residence uploaded successfully.');
        }
        return view('clients.register-client-kyc', compact('yuser'));
    }

    public function registerForClientKyc () {
        return view('clients.register-client-kyc');
    }

    public function search(Request $request){
        $search = $request->get('term');

        $result = Client::where('natid', 'LIKE', '%'. $search. '%')->get();

        return response()->json($result);

    }

    public function quickRegisterClient() {
        $countries = Localel::all();
        return view('clients.quick-register', compact('countries'));
    }

    public function postQuicklyRegister(Request $request) {
        $name = generateUsername($_POST['first_name'], $_POST['last_name']);
        $locale = Localel::findOrFail($request['locale']);

        $validations = $this->validateInput($locale);
        $validation  = $validations[0];
        $messages    = $validations[1];
        $validator = Validator::make(
            $request->all(),
            $validation,
            $messages
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = $request->ip();

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        try {

           DB::beginTransaction();
           $user = User::create([
              'name'              => $name,
              'first_name'        => $request['first_name'],
              'last_name'         => $request['last_name'],
              'email'             => $request['email'] ,
              'natid'             => strtoupper($request['natid']),
              'mobile'             => $request['mobile'],
              'utype'             => 'Client',
              'password'          => Hash::make($request['password']),
              'token'             => str_random(64),
              'signup_ip_address' => $ipAddress,
              'activated'         => $activated,
              'locale'            => $locale->id,
              'status' => 0
           ]);

           $user->attachRole($role);

           $profile = new Profile();
           $user->profile()->save($profile);

           $otp = rand(123456, 999999);
           $token = str_random(64);//sha1(time());
           $userOtp = new UserOtp();
           $userOtp->user_id = $user->id;
           $userOtp->user_otp = $otp;
           $userOtp->user_token = $token;
           $otpres = $userOtp->save();

           DB::commit();

           /** todo
           Mail::send('emails.validate', ['otp' => $otp, 'token' => $token,'first_name' => $request['first_name'], 'last_name' => $request['last_name']], function($message) use($request){
            $message->to($request->email);
            $message->subject('Account Verification Mail');
           }); **/

           $data = [
              'useremail' => $request['email'],
              'message' => 'Registration successful! OTP has been sent on your registered email address. Please verify your account to log in.',
           ];
           return view('clients.verify')->with($data);
        } catch(\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return response([
                'message' => $exp->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }

    private function validateInput(Localel $country) : array
    {
        $returnArray = array();
        if($country->country_short == 'ZW') {
            $returnArray[0] = [
                'first_name' => 'required',
                'last_name' => 'required',
                'natid' => array(
                    'required',
                    'max:15',
                    'regex:/^\d{8}[A-Z]\d{2}$/',
                    'unique:users'
                ),
                'email' => 'required|email|max:255|unique:users',
                'mobile' => 'required|max:10|unique:users',
                'password' => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
            ];
            $returnArray[1] = [
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required' => trans('auth.lNameRequired'),
                'natid.required' => 'We need your National ID Number to proceed.',
                'natid.unique' => 'This ID Number is already registered with AstroCred.',
                'natid.regex' => 'This ID Number has an invalid ID Format.',
                'email.required' => trans('auth.emailRequired'),
                'email.email' => trans('auth.emailInvalid'),
                'email.unique' => 'This email is already registered with AstroCred.',
                'mobile.required' => 'We need a phone number to create your account.',
                'mobile.unique' => 'This phone number is already registered with AstroCred.',
                'password.required' => trans('auth.passwordRequired'),
                'password.min' => trans('auth.PasswordMin'),
                'password.max' => trans('auth.PasswordMax'),
            ];
        }else if($country->country_short == 'ZM')
        {
            $returnArray[0] = [
                'first_name' => 'required',
                'last_name' => 'required',
                'natid' => array(
                    'required',
                    'max:15',
                    'regex:/^[0-9]{2}-[0-9]{5,7}-[A-Z]-[0-9]{2}$|\d{6}\/\d{2}\/\d{1}$/',
                    'unique:users'
                ),
                'email' => 'required|email|max:255|unique:users',
                'mobile' => 'required|max:10|unique:users',
                'password' => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
            ];
            $returnArray[1] = [
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required' => trans('auth.lNameRequired'),
                'natid.required' => 'We need your National ID Number to proceed.',
                'natid.unique' => 'This ID Number is already registered with AstroCred.',
                'natid.regex' => 'This ID Number has an invalid ID Format.',
                'email.required' => trans('auth.emailRequired'),
                'email.email' => trans('auth.emailInvalid'),
                'email.unique' => 'This email is already registered with AstroCred.',
                'mobile.required' => 'We need a phone number to create your account.',
                'mobile.unique' => 'This phone number is already registered with AstroCred.',
                'password.required' => trans('auth.passwordRequired'),
                'password.min' => trans('auth.PasswordMin'),
                'password.max' => trans('auth.PasswordMax'),
            ];
        }
        return $returnArray;
    }

    public function contQuicklyRegister() {
        return view('clients.quickly-continue');
    }

    public function contQuicklyPostClient(Request $request) {
        if ($request->input('salary') > $request->input('gross')) {
            return redirect()->back()->with('error', 'Sorry, Net salary cannot be greater than gross salary')->withInput();
        }

        // if ($request->input('usd_salary') > $request->input('usd_gross')) {
        //     return redirect()->back()->with('error', 'Sorry, Net USD salary cannot be greater than gross salary')->withInput();
        // }

        $name = generateUsername($_POST['first_name'], $_POST['last_name']);


        $locale = Localel::where('country',$request['country'])->firstOrFail();
            $settings = Masetting::find(1)->first();
            $validator = Validator::make(
                $request->all(),
                [
                    'title'                  => 'required|max:255',
                    'first_name'            => 'required',
                    'last_name'             => 'required',
                    'natid'                 => 'required|max:15|unique:clients',
                    'mobile'                 => 'required|max:10|unique:clients',
                    'dob'                 => 'required|date',
                    'gender'                 => 'required',
                    'marital_state'        => 'required',
                    'dependants'        => 'required|numeric',
                    'children'        => 'required|numeric',
                    'emp_sector'        => 'required',
                    'employer'        => 'required',
                    'designation'        => 'required',
                    'ecnumber'        => 'required',
                    'gross'        => 'required',
                    'salary'        => 'required',
                    'emp_nature' => 'required',
                    'house_num'        => 'required',
                    'street'        => 'required',
                    'surburb'        => 'required',
                    'city'        => 'required',
                    'province'        => 'required',
                    'country'        => 'required',
                    'home_type'        => 'required',
                    'resduration'        => 'required|numeric',
                    //'loan_purpose'             => 'required',
                ],
                [
                    'title.required'           => 'Please select your title.',
                    'first_name.required'           => trans('auth.fNameRequired'),
                    'last_name.required'            => trans('auth.lNameRequired'),
                    'natid.required'                => 'We need your National ID Number to proceed.',
                    'natid.unique'                   => 'This ID Number is already registered with AstroCred.',
                    'email.required'                => trans('auth.emailRequired'),
                    'email.email'                   => trans('auth.emailInvalid'),
                    'email.unique'                   => 'This email is already registered with AstroCred.',
                    'mobile.required'                   => 'We need a phone number to create your account.',
                    'mobile.unique'                   => 'This phone number is already registered with AstroCred.',
                    'dob.required' => 'We need your date of birth',
                    'dob.date' => 'The correct date format should be dd-mm-yyyy',
                    'gender.required' => 'What is your gender?',
                    'marital_state.required' => 'What is your marital status?',
                    'dependants.required' => 'Do you have any dependants?',
                    'children.required' => 'Do you have any children?',
                    'emp_sector.required' => 'Which employment sector are you in?',
                    'employer.required' => 'What is the name  of your employer?',
                    'ecnumber.required' => 'At your workplace, what is your employment number?',
                    'designation.required' => 'At your workplace, what is your designation?',
                    'gross.required' => 'What is your gross monthly salary?',
                    'salary.required' => 'What is your net monthly salary?',
                    'emp_nature.required' => 'What is nature of your employment?',
                    'house_num.required' => 'State your house number where you reside',
                    'street.required' => 'State the street name where you reside',
                    'surburb.required' => 'Which surburb do you stay in?',
                    'city.required' => 'Please state the city you\'re from?',
                    'province.required' => 'Which province are you in?',
                    'country.required' => 'State your country if?',
                    'home_type.required' => 'What is the state of your home?',
                    'resduration.required' => 'No. of months you have stayed at this residences?',
                    //'loan_purpose' => 'What is your purpose to apply for the loan?',
                ]
            );

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $client = Client::create([
                'creator'              => 'Self',
                'title'              => $request['title'],
                'first_name'        => strip_tags($request['first_name']),
                'last_name'         => strip_tags($request['last_name']),
                'natid'             => strtoupper($request['natid']),
                'email'             => $request['email'],
                'mobile'             => $request['mobile'],
                'dob'             => $request['dob'],
                'gender'             => $request['gender'],
                'marital_state'             => $request['marital_state'],
                'dependants'             => $request['dependants'],
                'children'             => $request['children'],
                'nationality'             => $request['nationality'],
                'house_num'             => $request['house_num'],
                'street'             => $request['street'],
                'surburb'             => $request['surburb'],
                'city'             => $request['city'],
                'province'             => $request['province'],
                'country'             => $request['country'],
                'locale_id'             => $locale->id,
                'emp_sector'             => $request['emp_sector'],
                'employer'             => $request['employer'],
                'ecnumber'             => $request['ecnumber'],
                'designation' => $request['designation'],
                'gross'             => $request['gross'],
                'salary'             => $request['salary'],
                'emp_nature' => $request['emp_nature'],
                'cred_limit'             => number_format($settings->creditRate*$request['salary'],2,'.',''),
                // 'usd_gross'             => $request['usd_gross'],
                // 'usd_salary'             => $request['usd_salary'],
                'usd_cred_limit'             => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
                'home_type'             => $request['home_type'],
                'res_duration' => $request['resduration'],
                //'loan_purpose' => 'default',
            ]);
            $client->save();
            if ($client->save()) {
                $limit = Creditlimit::create([
                    'client_id'             => $client->id,
                    'grossSalary'             => $request->input('gross'),
                    'netSalary'             => $request->input('salary'),
                    'creditlimit'             => number_format($settings->creditRate*$request['salary'],2,'.',''),
                    // 'usdGrossSalary'             => $request->input('usd_gross'),
                    // 'usdNetSalary'             => $request->input('usd_salary'),
                    // 'usdCreditlimit'             => number_format(getUSDCreditRate()*$request['usd_salary'],2,'.',''),
                    'reason'             => 'New Client which did not have credit limit record.',
                ]);

                $limit->save();
            }

            //return redirect('home');
            return redirect('remaining-details');
    }

    public function resendOTPForm(){
        $clients = Client::all();
        return view('clients.resend-otps', compact('clients'));
    }

    public function sendingClientOTP(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'client_id'            => 'required',
            ],
            [
                'client_id.required' => 'Please make sure you\'re selecting a client.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $settings = Masetting::find(1)->first();
        $client = Client::findOrFail($request->input('client_id'));

        $loan = Loan::where('client_id', $client->id)->where('loan_status', 0)->exists();

        if(!$loan) {
            return redirect()->back()->with('error','Could not find any loan which needs to be signed for this client.');
        }

        $otp = mt_rand(100000, 999999);
        $getOtp = Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=AstroCred OTP: Your requested pin is: ".$otp.". Regards, AstroCred.")
            ->body();

        $json = json_decode($getOtp, true);
        $status = $json['data'][0]['status'];

        if ($status == 'OK') {
            DB::table("clients")
                ->where("id", $client->id)
                ->update(['otp' => Hash::make($otp), 'updated_at' => now()]);
        }

        return redirect()->back()->with('success','OTP has been resent to '.$client->mobile.' successfully.');
    }

    public function verifyAccountByToken($token){
        //echo $token;echo "</ br>";
        $userOtp = UserOtp::all()->where('user_token','=',$token)->first();
        //echo"<pre>";print_r($userOtp);echo"</pre>";die();
        $user = User::find($userOtp->user_id);
        $data = [
            'useremail' => $user->email,
            'message' => 'Registration successful! OTP was sent on your registered email address. Please submit OTP to verify your account to log in.',
         ];

         //Auth::login($user);
         return view('clients.verify')->with($data);
    }
    public function verifyAccount(Request $request){
        //$email = $request->useremail;
        //$otp = $request->otp;
        $user = User::all()->where('email','=',$request->useremail)->first();
        $userOtp = UserOtp::all()->where('user_id','=',$user->id)->first();

        if($userOtp->user_otp===$request->otp){
            $user->updated_at = date("Y-m-d H:i:s");
            $user->status = 1;
			$user->save();
            return redirect('login')->with('success', 'Account verified. You can login now.');
        } else {
            //return redirect()->back()->withInput()->withErrors(['otp' => 'Invalid OTP']);
            $data = [
                'useremail' => $request->useremail,
                'message' => 'Invalid OTP. Please submit valid OTP to activate your account.',
             ];

             //Auth::login($user);
             return view('clients.verify')->with($data);
        }
    }

    public function getLightUsers(){
        /*$users = DB::table('users')
            ->leftJoin('clients', 'users.natid', '=', 'clients.natid')
			->whereNull('clients.natid')
            ->get();*/
        $users = DB::table('users')
           ->selectRaw('users.first_name, users.last_name, users.natid, users.mobile, users.email, users.status, users.created_at')
           ->leftJoin('clients', 'users.natid', '=', 'clients.natid')
           ->whereNull('clients.natid')
            ->get();
            //echo "<pre>";print_r($users); echo "</pre>";die();
        //select('SELECT * FROM `users` u LEFT JOIN clients c ON (u.natid=c.natid) WHERE c.natid IS NULL');

        //$users = DB::select('SELECT * FROM `users` u LEFT JOIN clients c ON (u.natid=c.natid) WHERE c.natid IS NULL');
        return view('clients.quickly-registered-users')->with('users', $users);
    }

    public function bankSignup()
    {
        return view('clients.bank_reg');
    }
}
