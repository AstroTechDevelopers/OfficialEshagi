<?php

namespace App\Http\Controllers;

use App\Events\MerchantSignedUp;
use App\Models\Client;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\MerchantKyc;
use App\Models\Partner;
use App\Models\MerchantBranches;
use App\Models\Profile;
use App\Models\User;
use App\Models\Bank;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use jeremykenedy\LaravelRoles\Models\Role;
use Auth;
use Mail;

use function Ramsey\Uuid\v1;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = Partner::all();
        return view('partners.partners', compact('partners'));
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
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        $user = User::where('natid', $partner->regNumber)->first();
        $mkyc = MerchantKyc::where('partner_id', $partner->id)->first();
        $bank = Bank::where('id',$partner->bank)->first();

        return view('partners.review-merchant-kyc', compact('partner', 'user', 'mkyc', 'bank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {
        $banks = DB::table('banks')
            ->groupBy('bank')
            ->get();
        $mkyc = MerchantKyc::where('partner_id', $partner->id)->first();

        return view('partners.edit-partner', compact('partner', 'banks', 'mkyc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        ActivityLogger::activity(auth()->user()->name . " has attempted to edit partner " . $partner->partner_name . " with partner ID: " . $partner->id);
        $user = User::where('natid', $partner->regNumber)->first();

        if ($partner->partner_name != strtolower($_POST['partner_name'])) {
            $name = strtolower($_POST['partner_name']);
            $i = 0;
            do {
                //Check in the database here
                $exists = User::where('name', '=', $name)->exists();
                if($exists) {
                    $i++;
                    $name = strtolower($_POST['partner_name']) . $i;
                }
            }while($exists);
        } else {
            $name=$user->name;
        }

        if ($request['regNumber'] == null) {

            $i = 0;
            do {
                $tletter = chr(64+rand(0,14));
                $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999);

                $regNum = $tletter.$pin;

                $voucherNum = Partner::where('regNumber', '=', $regNum)->exists();

                if($voucherNum) {
                    $i++;
                    $tletter = chr(64+rand(0,14));
                    $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999);

                    $regNum = $tletter.$pin;
                }
            }while($voucherNum);

            $request->merge([
                'regNumber' => $regNum,
                'bpNumber' => $regNum,
            ]);
        }


        $locale = Localel::where('country',$request['country'])->firstOrFail();

        $validator = Validator::make(
            $request->all(),
            [
                'partner_name'                  => 'required|max:255|unique:partners,partner_name,'.$partner->id,
                'partner_type'            => 'required',
                'business_type'                 => 'required|max:255',
                'partnerDesc'                 => 'required|max:255',
                'yearsTrading'                 => 'required',
                'regNumber'                 => 'unique:partners,regNumber,'.$partner->id,
                'bpNumber'                 => 'unique:partners,bpNumber,'.$partner->id,
                'propNumber'        => 'required',
                'street'        => 'required',
                'suburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'cfullname'        => 'required',
                'cdesignation'        => 'required',
                'telephoneNo'        => 'required',
                'cemail'        => 'required|email|unique:partners,cemail,'.$partner->id,
                'bank'        => 'required',
                'branch'        => 'required',
                'branch_code'        => 'required',
                'acc_number'        => 'required',
            ],
            [
                'partner_name.required'           => 'What is your Partner name?',
                'partner_type.required'           => 'What is your relationship to AstroCred like?',
                'business_type.required'          => 'What is the nature of your company?',
                'partnerDesc.required'            => 'What is the nature of your business?',
                'yearsTrading.required'           => 'How many years have you been trading?',
                'regNumber.unique'                   => 'This company registration number is already registered with AstroCred.',
                'bpNumber.unique'                   => 'This business partner number is already registered with AstroCred.',
                'propNumber.required' => 'What is the address number for your business premises?',
                'street.required' => 'What is the street address for your business premises?',
                'suburb.required' => 'What is the area you conduct business from?',
                'city.required' => 'In what city, do you operate from?',
                'province.required' => 'In which province do you operate in?',
                'country.required' => 'In which country is this province in?',
                'cfullname.required' => 'Who will be your contact person?',
                'cdesignation.required' => 'What is the designation for the contact person?',
                'telephoneNo.required' => 'What the contactable phone number for the contact person ',
                'cemail.required'                   => 'The email address for the contact person is mandatory.',
                'cemail.email'                   => trans('auth.emailInvalid'),
                'cemail.unique'                   => 'This email is already registered with AstroCred.',
                'bank.required' => 'What is your primary bank?',
                'branch.required' => 'Bank branch is required.',
                'branch_code.required' => 'What is your branch code?',
                'acc_number.required' => 'What is your account number with this bank?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $partner->partner_name = strip_tags($request['partner_name']);
        $partner->partner_type = strip_tags($request['partner_type']);
        $partner->merchantname = strip_tags($request['merchantname']);
        $partner->business_type = $request['business_type'];
        $partner->partnerDesc = $request['partnerDesc'];
        $partner->yearsTrading = $request['yearsTrading'];
        $partner->regNumber = $request['regNumber'];
        $partner->bpNumber = $request['bpNumber'];
        $partner->propNumber = $request['propNumber'];
        $partner->street = $request['street'];
        $partner->suburb = $request['suburb'];
        $partner->city = $request['city'];
        $partner->province = $request['province'];
        $partner->country = $request['country'];
        $partner->cfullname = $request['cfullname'];
        $partner->cdesignation = $request['cdesignation'];
        $partner->telephoneNo = $request['telephoneNo'];
        $partner->locale_id = $locale->id;
        $partner->cemail = $request['cemail'];
        $partner->bank = $request['bank'];
        $partner->branch = $request['branch'];
        $partner->branch_code = $request['branch_code'];
        $partner->acc_number = $request['acc_number'];

        $partner->save();

        if ($partner->save()) {
            $user->name = $name;
            $user->first_name = $request['partner_name'];
            $user->last_name = $request['partner_type'];
            $user->email = $request['cemail'];
            $user->natid = $request['regNumber'];
            $user->mobile = $request['telephoneNo'];
            $user->save();
            ActivityLogger::activity(auth()->user()->name . " has modified partner " . $partner->partner_name . " with partner ID: " . $partner->id);
        }

        return redirect()->back()->with('success', 'Partner details updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        $user = User::where('natid', $partner->regNumber)->first();

        $partner->delete();
        if ($partner->delete()){
            $user->delete();
        }

        return redirect('partners')->with('success', 'Partner deleted successfully.');
    }

    public function fetchAllAgents() {
        $agents = Partner::where('partner_type', 'Agent')->get();

        return view('partners.agents', compact('agents'));
    }

    public function fetchAllMerchants() {
        $merchants = Partner::where('partner_type', 'Merchant')->get();

        return view('partners.merchants', compact('merchants'));
    }

    public function getMerchantsToAprove(){
        $merchants = Partner::where('partner_type', 'Merchant')->where('status', 0)->get();

        return view('partners.merchants', compact('merchants'));
    }

    public function partnerLogin(){
        return view('partners.partner-login');
    }

    public function regPartner(){
        $banks = DB::table('banks')
            ->groupBy('bank')
            ->get();

        $countries = Localel::all();

        return view('partners.register-partner', compact('banks','countries'));
    }

    public function authenticatePatina(Request $request){

        $username = convertToSlug($_POST['partner_name']);

        $locale = Localel::where('id',$request['country'])->firstOrFail();

        if ($request->input('business_type') == 'Sole Trader') {
            $validator = Validator::make(
                $request->all(),
                [
                    'partner_name'                  => 'required|max:255|unique:partners',
                    'partner_type'            => 'required',
                    'business_type'                 => 'required|max:255',
                    'partnerDesc'                 => 'required|max:255',
                    'yearsTrading'                 => 'required',
                    'propNumber'        => 'required',
                    'street'        => 'required',
                    'suburb'        => 'required',
                    'city'        => 'required',
                    'province'        => 'required',
                    'country'        => 'required',
                    'cfullname'        => 'required',
                    'cdesignation'        => 'required',
                    'telephoneNo'        => 'required',
                    'cemail'        => 'required|email|unique:partners',
                    'bank'        => 'required',
                    'branch'        => 'required',
                    'branch_code'        => 'required',
                    'acc_number'        => 'required',
                    'password'              => 'required|min:6|max:50|confirmed',
                    'password_confirmation' => 'required|same:password',
                    'vatRegNumber'        => 'required',
                ],
                [
                    'partner_name.required'           => 'What is your Partner name?',
                    'partner_type.required'           => 'What is your relationship to AstroCred like?',
                    'business_type.required'          => 'What is the nature of your company?',
                    'partnerDesc.required'            => 'What is the nature of your business?',
                    'yearsTrading.required'           => 'How many years have you been trading?',
                    'propNumber.required' => 'What is the address number for your business premises?',
                    'street.required' => 'What is the street address for your business premises?',
                    'suburb.required' => 'What is the area you conduct business from?',
                    'city.required' => 'In what city, do you operate from?',
                    'province.required' => 'In which province do you operate in?',
                    'country.required' => 'In which country is this province in?',
                    'cfullname.required' => 'Who will be your contact person?',
                    'cdesignation.required' => 'What is the designation for the contact person?',
                    'telephoneNo.required' => 'What the contactable phone number for the contact person ',
                    'cemail.required'                   => 'The email address for the contact person is mandatory.',
                    'cemail.email'                   => trans('auth.emailInvalid'),
                    'cemail.unique'                   => 'This email is already registered with AstroCred.',
                    'bank.required' => 'What is your primary bank?',
                    'branch.required' => 'Bank branch is required.',
                    'branch_code.required' => 'What is your branch code?',
                    'acc_number.required' => 'What is your account number with this bank?',
                    'password.required'             => trans('auth.passwordRequired'),
                    'password.min'                  => trans('auth.PasswordMin'),
                    'password.max'                  => trans('auth.PasswordMax'),
                    'vatRegNumber.required' => 'Is your company / business VAT registered?',
                ]
            );
        }
        else{
            $validator = Validator::make(
                $request->all(),
                [
                    'partner_name'                  => 'required|max:255|unique:partners',
                    'partner_type'            => 'required',
                    'business_type'                 => 'required|max:255',
                    'partnerDesc'                 => 'required|max:255',
                    'yearsTrading'                 => 'required',
                    'regNumber'                 => 'unique:partners',
                    'bpNumber'                 => 'unique:partners',
                    'vatRegNumber' => 'required',
                    'propNumber'        => 'required',
                    'street'        => 'required',
                    'suburb'        => 'required',
                    'city'        => 'required',
                    'province'        => 'required',
                    'country'        => 'required',
                    'cfullname'        => 'required',
                    'cdesignation'        => 'required',
                    'telephoneNo'        => 'required',
                    'cemail'        => 'required|email|unique:partners',
                    'bank'        => 'required',
                    'branch'        => 'required',
                    'branch_code'        => 'required',
                    'acc_number'        => 'required',
                    'password'              => 'required|min:6|max:50|confirmed',
                    'password_confirmation' => 'required|same:password',
                ],
                [
                    'partner_name.required'           => 'What is your Partner name?',
                    'partner_type.required'           => 'What is your relationship to AstroCred like?',
                    'business_type.required'          => 'What is the nature of your company?',
                    'partnerDesc.required'            => 'What is the nature of your business?',
                    'yearsTrading.required'           => 'How many years have you been trading?',
                    'regNumber.unique'                   => 'This company registration number is already registered with AstroCred.',
                    'bpNumber.unique'                   => 'This business partner number is already registered with AstroCred.',
                    'vatRegNumber.required' => 'Is your company / busiess VAT registered?',
                    'propNumber.required' => 'What is the address number for your business premises?',
                    'street.required' => 'What is the street address for your business premises?',
                    'suburb.required' => 'What is the area you conduct business from?',
                    'city.required' => 'In what city, do you operate from?',
                    'province.required' => 'In which province do you operate in?',
                    'country.required' => 'In which country is this province in?',
                    'cfullname.required' => 'Who will be your contact person?',
                    'cdesignation.required' => 'What is the designation for the contact person?',
                    'telephoneNo.required' => 'What the contactable phone number for the contact person ',
                    'cemail.required'                   => 'The email address for the contact person is mandatory.',
                    'cemail.email'                   => trans('auth.emailInvalid'),
                    'cemail.unique'                   => 'This email is already registered with AstroCred.',
                    'bank.required' => 'What is your primary bank?',
                    'branch.required' => 'Bank branch is required.',
                    'branch_code.required' => 'What is your branch code?',
                    'acc_number.required' => 'What is your account number with this bank?',
                    'password.required'             => trans('auth.passwordRequired'),
                    'password.min'                  => trans('auth.PasswordMin'),
                    'password.max'                  => trans('auth.PasswordMax'),
                ]
            );
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $users = DB::table('users')
            ->select('name', 'email as user_email')
            ->where('email',$request->cemail)
            ->first();

        $data = [
           'message' => ''
        ];

        if(!empty($users)){
			if($users->user_email==$request['cemail']){
				//return redirect('register-partner')->with('message', 'This email is already registered with AstroCred.')->withInput();
				return back()->withErrors(['cemail'=>'This email is already registered with AstroCred.'])->withInput();
			}
		}

        $ipAddress = new CaptureIpTrait();

        $role = Role::where('slug', '=', 'partner')->first();

        if ($request['regNumber'] == null) {
            $tletter = chr(64+rand(0,14));
            $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999);

            $voucher = $tletter.$pin;

            $i = 0;
            do {
                $voucherNum = Partner::where('regNumber', '=', $voucher)->exists();
                if($voucherNum) {
                    $i++;
                    $tletter = chr(64+rand(0,14));
                    $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999);

                    $voucher = $tletter.$pin;
                }
            }while($voucherNum);

            $request->merge([
                'regNumber' => $voucher,
                'bpNumber' => $voucher,
            ]);
        }

        $partner = Partner::create([
            'partner_name'      => strip_tags($request['partner_name']),
            'partner_type'        => strip_tags($request['partner_type']),
            'merchantname'         => strip_tags($request['merchantname']),
            'business_type'             => $request['business_type'],
            'partnerDesc'             => $request['partnerDesc'],
            'yearsTrading'             => $request['yearsTrading'],
            'regNumber'             => $request['regNumber'],
            'bpNumber'             => $request['bpNumber'],
            'is_vat_registered' => $request['vatRegNumber'],
            'propNumber'             => $request['propNumber'],
            'street'             => $request['street'],
            'suburb'             => $request['suburb'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'country'             => $locale->country,
            'cfullname'             => $request['cfullname'],
            'cdesignation'             => $request['cdesignation'],
            'telephoneNo'             => $request['telephoneNo'],
            'locale_id'             => $locale->id,
            'cemail'             => $request['cemail'],
            'bank'             => $request['bank'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'acc_number'             => $request['acc_number'],
            'password'          => Hash::make($request['password']),
            'status'            => 0,
        ]);
        $partner->save();

        if ($partner->save()) {
            $user = User::create([
                'name'              => $username,
                'first_name'        => $request['partner_name'],
                'last_name'         => $request['partner_type'],
                'email'             => $request['cemail'],
                'natid'             => $request['regNumber'],
                'mobile'             => $request['telephoneNo'],
                'utype'             => 'Partner',
                'password'          => Hash::make($request['password']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress->getClientIp(),
                'activated'         => false,
                'locale'            => $locale->id,
                'status'            => 0,
            ]);

            $user->attachRole($role);
            //$this->initiateEmailActivation($user);
            //event(new MerchantSignedUp($partner));
            $profile = new Profile();
            $user->profile()->save($profile);

            $merchantKyc = MerchantKyc::create([
                'partner_id' => $partner->id,
                'natid' => $user->natid,
            ]);
            $merchantKyc->save();

            $yuser = MerchantKyc::findOrFail($merchantKyc->id);

            $uid = $user->id;
            $partnerid = $merchantKyc->id;
            $natid = $user->natid;
            $yuser = $yuser;

            return view('partners.merchant-add-business-kyc', compact('partnerid'));
        }

        //return redirect('merchant-agreement');
    }

    public function uploadBusinessKyc(Request $request){

       $merchantKYC = MerchantKyc::findOrFail($request->mid);

       $validator = Validator::make($request->all(),
          [
             'inc_cert' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,docx|max:4096',
             //'resproof' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
            // 'cr14' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
             //'bizlicense' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
          ],
          [
             'inc_cert.max' => 'Certificate of Incorporation file should not be greater than 4MB.',
             'inc_cert.mimes' => 'Certificate of Incorporation file should of the format: jpeg,png,jpg,gif,svg,pdf.',
             //'resproof.max' => 'Prood of Residence file should not be greater than 4MB.',
             //'resproof.mimes' => 'Prood of Residence file should of the format: jpeg,png,jpg,gif,svg,pdf.',
            // 'cr14.max' => 'CR14 file should not be greater than 4MB.',
            // 'cr14.mimes' => 'CR14 file should of the format: jpeg,png,jpg,gif,svg,pdf.',
            // 'bizlicense.max' => 'Business License file should not be greater than 4MB.',
            // 'bizlicense.mimes' => 'Business License file should of the format: jpeg,png,jpg,gif,svg,pdf.',
          ]
       );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('inc_cert') /*&& $request->hasFile('cr14') && $request->hasFile('bizlicense')*/) {

            if ($request->file('inc_cert')->isValid() /* && $request->file('cr14')->isValid() && $request->file('bizlicense')->isValid()*/) {

                $incCert = $request->file('inc_cert');
                //$resProof = $request->file('resproof');
                $cr14 = $request->file('cr14');
                $bizlicense = $request->file('bizlicense');

                $incCertFile = 'inccert_' . time() . '.' . $incCert->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/inccerts/' . $incCertFile, File::get($incCert));
                //$resProofFile = 'resproofs_' . time() . '.' . $resProof->getClientOriginalExtension();
                //Storage::disk('public')->put('merchants/resproofs/' . $resProofFile, File::get($resProof));
                $cr14File = 'cr14_' . time() . '.' . $cr14->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/cr14/' . $cr14File, File::get($cr14));
                $bizLicenseFile = 'bizlicense_' . time() . '.' . $bizlicense->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/bizlicense/' . $bizLicenseFile, File::get($bizlicense));

                $merchantKYC->cert_incorp = $incCertFile;
                //$merchantKYC->proof_of_res = $resProofFile;
                $merchantKYC->cr14 = $cr14File;
                $merchantKYC->bus_licence = $bizLicenseFile;
                $merchantKYC->cert_incorp_stat = 1;
                //$merchantKYC->proof_of_res_stat = 1;
                $merchantKYC->cr14_stat = 1;
                $merchantKYC->bus_licence_stat = 1;
                $merchantKYC->updated_at = now();

                $merchantKYC->save();
                $partnerid = $merchantKYC->id;
            } else {
                return back()->with('error','Invalid document file / image supplied.');
            }

        } else {
            return back()->with('error','No file / image was detected here.');
        }

        //return view('partners.merchant-add-director-one-kyc', compact('partnerid'));
        $message = 'Your request to become a partner has been sent for approval. You will be notified on the registered email address once it is approved.';
        $data = [
           'message' => $message
        ];
        return view('partners.register-merchant-success')->with($data);
    }

    public function uploadDirectorOneKyc(Request $request){
        $merchantKYC = MerchantKyc::findOrFail($request->mid);
        $validator = Validator::make($request->all(),
          [
            'dir_one_name' => 'required',
            'dir_one_lname' => 'required',
            'dir_one_nid' => array(
                'required',
                'max:15',
                'regex:/^[0-9]{2}-[0-9]{5,7}-[A-Z]-[0-9]{2}$|\d{6}\/\d{2}\/\d{1}$/',
                'unique:merchant_kycs'
            ),
            'natid_front_dir_one' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'natid_back_dir_one' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'passport_one' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
          ],
          [
            'dir_one_name.required' => 'We need First Name of Director to proceed.',
            'dir_one_lname.required' => 'We need Last Name of Director to proceed.',
            'dir_one_nid.required' => 'We need National ID Number of Director to proceed.',
            'dir_one_nid.max' => 'National ID Number should not be greater than 15 digits',
            'dir_one_nid.unique' => 'This National ID Number is already registered with AstroCred.',
            'dir_one_nid.regex' => 'This National ID Number has an invalid ID Format.',
            'natid_front_dir_one.max' => 'National ID frontside file should not be greater than 4MB.',
            'natid_front_dir_one.mimes' => 'National ID frontside file should of the format: jpeg,png,jpg,gif,svg.',
            'natid_back_dir_one.max' => 'National ID backside file should not be greater than 4MB.',
            'natid_back_dir_one.mimes' => 'National ID backside file should of the format: jpeg,png,jpg,gif,svg.',
            'passport_one.max' => 'Photo should not be greater than 4MB.',
            'passport_one.mimes' => 'Photo should of the format: jpeg,png,jpg,gif,svg.',
          ]
        );

        if ($validator->fails()) {
            //return back()->withErrors($validator)->withInput();
            $errors = $validator->errors();
            $partnerid = $request->mid;
            session()->flashInput($request->input());
            return view('partners.merchant-add-director-one-kyc', compact('errors', 'partnerid'));
        }

        if($request->hasFile('natid_front_dir_one') && $request->hasFile('natid_back_dir_one') && $request->hasFile('passport_one')) {

            if ($request->file('natid_front_dir_one')->isValid() && $request->file('natid_back_dir_one')->isValid() && $request->file('passport_one')->isValid()) {

                $nidFrontOne = $request->file('natid_front_dir_one');
                $nidFrontTwo = $request->file('natid_back_dir_one');
                $photoOne = $request->file('passport_one');


                $nidFrontOneFile = 'nid_' . time() . '.' . $nidFrontOne->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/nationalids/' . $nidFrontOneFile, File::get($nidFrontOne));
                $nidFrontTwoFile = 'nid_' . time() . '.' . $nidFrontTwo->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/nationalids/' . $nidFrontTwoFile, File::get($nidFrontTwo));
                $photoOneFile = 'nid_' . time() . '.' . $photoOne->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/photos/' . $photoOneFile, File::get($photoOne));

                $merchantKYC->dir_one_name = $request->dir_one_name;
                $merchantKYC->dir_one_lname = $request->dir_one_lname;
                $merchantKYC->dir_one_nid = $request->dir_one_nid;
                $merchantKYC->national_id1 = $nidFrontOneFile;
                $merchantKYC->national_id1_back_pic = $nidFrontTwoFile;
                $merchantKYC->pphoto1 = $photoOneFile;
                $merchantKYC->national_id1_stat = 1;
                $merchantKYC->national_id1_back_stat = 1;
                $merchantKYC->pphoto1_stat = 1;
                $merchantKYC->updated_at = now();

                $merchantKYC->save();
                $partnerid = $merchantKYC->id;
            } else {
                return back()->with('error','Invalid image supplied.');
            }

        } else {
            return back()->with('error','No file was detected here.');
        }

        return view('partners.merchant-add-director-two-kyc', compact('partnerid'));
    }

    public function uploadDirectorTwoKyc(Request $request){
        $merchantKYC = MerchantKyc::findOrFail($request->mid);
        $validator = Validator::make($request->all(),
          [
            'dir_two_name' => 'required',
            'dir_two_lname' => 'required',
            'dir_two_nid' => array(
                'required',
                'max:15',
                'regex:/^[0-9]{2}-[0-9]{5,7}-[A-Z]-[0-9]{2}$|\d{6}\/\d{2}\/\d{1}$/',
                'unique:merchant_kycs'
            ),
            'natid_front_dir_two' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'natid_back_dir_two' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'passport_two' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
          ],
          [
            'dir_two_name.required' => 'We need First Name of Director to proceed.',
            'dir_two_lname.required' => 'We need Last Name of Director to proceed.',
            'dir_two_nid.required' => 'We need National ID Number of Director to proceed.',
            'dir_two_nid.max' => 'National ID Number should not be greater than 15 digits',
            'dir_two_nid.unique' => 'This National ID Number is already registered with AstroCred.',
            'dir_two_nid.regex' => 'This National ID Number has an invalid ID Format.',
            'natid_front_dir_two.max' => 'National ID frontside file should not be greater than 4MB.',
            'natid_front_dir_two.mimes' => 'National ID frontside file should of the format: jpeg,png,jpg,gif,svg.',
            'natid_back_dir_two.max' => 'National ID backside file should not be greater than 4MB.',
            'natid_back_dir_two.mimes' => 'National ID backside file should of the format: jpeg,png,jpg,gif,svg.',
            'passport_two.max' => 'Photo should not be greater than 4MB.',
            'passport_two.mimes' => 'Photo should of the format: jpeg,png,jpg,gif,svg.',
          ]
        );

        if ($validator->fails()) {
            //return back()->withErrors($validator)->withInput();
            $errors = $validator->errors();
            $partnerid = $request->mid;
            session()->flashInput($request->input());
            return view('partners.merchant-add-director-two-kyc', compact('errors', 'partnerid'));
        }

        if($request->hasFile('natid_front_dir_two') && $request->hasFile('natid_back_dir_two') && $request->hasFile('passport_two')) {

            if ($request->file('natid_front_dir_two')->isValid() && $request->file('natid_back_dir_two')->isValid() && $request->file('passport_two')->isValid()) {

                $nidFrontTwo = $request->file('natid_front_dir_two');
                $nidBackTwo = $request->file('natid_back_dir_two');
                $photoTwo = $request->file('passport_two');

                $nidFrontTwoFile = 'nid_' . time() . '.' . $nidFrontTwo->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/nationalids/' . $nidFrontTwoFile, File::get($nidFrontTwo));
                $nidBackTwoFile = 'nid_' . time() . '.' . $nidBackTwo->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/nationalids/' . $nidBackTwoFile, File::get($nidBackTwo));
                $photoTwoFile = 'nid_' . time() . '.' . $photoTwo->getClientOriginalExtension();
                Storage::disk('public')->put('merchants/photos/' . $photoTwoFile, File::get($photoTwo));

                $merchantKYC->dir_two_name = $request->dir_two_name;
                $merchantKYC->dir_two_lname = $request->dir_two_lname;
                $merchantKYC->dir_two_nid = $request->dir_two_nid;
                $merchantKYC->national_pic_front_dir_two = $nidFrontTwoFile;
                $merchantKYC->national_pic_back_dir_two = $nidBackTwoFile;
                $merchantKYC->pphoto2 = $photoTwoFile;
                $merchantKYC->national_id2_front_stat = 1;
                $merchantKYC->national_id2_back_stat = 1;
                $merchantKYC->pphoto2_stat = 1;
                $merchantKYC->updated_at = now();

                $merchantKYC->save();
            } else {
                return back()->with('error','Invalid image supplied.');
            }

        } else {
            return back()->with('error','No file was detected here.');
        }

        $message = 'Your request to become a partner has been sent for approval. You will be notified on the registered email address once it is approved.';
        $data = [
           'message' => $message
        ];
        return view('partners.register-merchant-success')->with($data);
    }

    public function updateBusinessKyc(Request $request){
        if($request->mid==='NA12092023'){
            // Create merchant
            $partner = Partner::findOrFail($request->pid);
            $merchantKYC = MerchantKyc::create([
                'partner_id' => $partner->id,
                'natid' => $partner->regNumber,
            ]);
            $merchantKYC->save();
        } else{
            // Get merchant
            $merchantKYC = MerchantKyc::findOrFail($request->mid);
        }

        $validator = Validator::make($request->all(),
           [
              'inc_cert' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
              'resproof' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
              'cr14' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
              'bizlicense' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:4096',
           ],
           [
              'inc_cert.max' => 'Certificate of Incorporation file should not be greater than 4MB.',
              'inc_cert.mimes' => 'Certificate of Incorporation file should of the format: jpeg,png,jpg,gif,svg,pdf.',
              'resproof.max' => 'Prood of Residence file should not be greater than 4MB.',
              'resproof.mimes' => 'Prood of Residence file should of the format: jpeg,png,jpg,gif,svg,pdf.',
              'cr14.max' => 'CR14 file should not be greater than 4MB.',
              'cr14.mimes' => 'CR14 file should of the format: jpeg,png,jpg,gif,svg,pdf.',
              'bizlicense.max' => 'Business License file should not be greater than 4MB.',
              'bizlicense.mimes' => 'Business License file should of the format: jpeg,png,jpg,gif,svg,pdf.',
           ]
        );

         if ($validator->fails()) {
             return back()->withErrors($validator)->withInput();
         }

         if($request->hasFile('inc_cert') || $request->hasFile('resproof') || $request->hasFile('cr14') || $request->hasFile('bizlicense')) {

             if($request->hasFile('inc_cert')) {
                 if ($request->file('inc_cert')->isValid()){
                    $incCert = $request->file('inc_cert');
                    $incCertFile = 'inccert_' . time() . '.' . $incCert->getClientOriginalExtension();
                    Storage::disk('public')->put('merchants/inccerts/' . $incCertFile, File::get($incCert));
                    $merchantKYC->cert_incorp = $incCertFile;
                    $merchantKYC->cert_incorp_stat = 1;
                 }else {
                    return back()->with('error','Invalid document file / image supplied.');
                 }
             }

             if($request->hasFile('resproof')) {
                 if($request->file('resproof')->isValid()){
                    $resProof = $request->file('resproof');
                    $resProofFile = 'resproofs_' . time() . '.' . $resProof->getClientOriginalExtension();
                    Storage::disk('public')->put('merchants/resproofs/' . $resProofFile, File::get($resProof));
                    $merchantKYC->proof_of_res = $resProofFile;
                    $merchantKYC->proof_of_res_stat = 1;
                 }else {
                    return back()->with('error','Invalid document file / image supplied.');
                 }
             }


             if($request->hasFile('cr14')) {
                 if($request->file('cr14')->isValid()){
                    $cr14 = $request->file('cr14');
                    $cr14File = 'cr14_' . time() . '.' . $cr14->getClientOriginalExtension();
                    Storage::disk('public')->put('merchants/cr14/' . $cr14File, File::get($cr14));
                    $merchantKYC->cr14 = $cr14File;
                    $merchantKYC->cr14_stat = 1;
                 }else {
                    return back()->with('error','Invalid document file / image supplied.');
                 }
             }

             if($request->hasFile('bizlicense')) {
                 if($request->file('bizlicense')->isValid()){
                    $bizlicense = $request->file('bizlicense');
                    $bizLicenseFile = 'bizlicense_' . time() . '.' . $bizlicense->getClientOriginalExtension();
                    Storage::disk('public')->put('merchants/bizlicense/' . $bizLicenseFile, File::get($bizlicense));
                    $merchantKYC->bus_licence = $bizLicenseFile;
                    $merchantKYC->bus_licence_stat = 1;
                 }else {
                    return back()->with('error','Invalid document file / image supplied.');
                 }
             }

                 $merchantKYC->updated_at = now();
                 $merchantKYC->save();
                 $partnerid = $merchantKYC->id;
             /*} else {
                 return back()->with('error','Invalid document file / image supplied.');
             }*/

         } else {
             return back()->with('error','No file / image was detected here.');
         }

         return back()->with('success','Business kyc documents updated successfully.');
    }

    public function updateDirectorOneKyc(Request $request){
        if($request->mid==='NA12092023'){
            // Create merchant
            $partner = Partner::findOrFail($request->pid);
            $merchantKYC = MerchantKyc::create([
                'partner_id' => $partner->id,
                'natid' => $partner->regNumber,
            ]);
            $merchantKYC->save();
        } else{
            // Get merchant
            $merchantKYC = MerchantKyc::findOrFail($request->mid);
        }

        $validator = Validator::make($request->all(),
          [
            'dir_one_name' => 'nullable',
            'dir_one_lname' => 'nullable',
            'dir_one_nid' => array(
                'nullable',
                'max:15',
                'regex:/^[0-9]{2}-[0-9]{5,7}-[A-Z]-[0-9]{2}$|\d{6}\/\d{2}\/\d{1}$/'
            ),
            'natid_front_dir_one' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'natid_back_dir_one' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'passport_one' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
          ],
          [
            'dir_one_name.required' => 'We need First Name of Director to proceed.',
            'dir_one_lname.required' => 'We need Last Name of Director to proceed.',
            'dir_one_nid.required' => 'We need National ID Number of Director to proceed.',
            'dir_one_nid.max' => 'National ID Number should not be greater than 15 digits',
            'dir_one_nid.regex' => 'This National ID Number has an invalid ID Format.',
            'natid_front_dir_one.max' => 'National ID frontside file should not be greater than 4MB.',
            'natid_front_dir_one.mimes' => 'National ID frontside file should of the format: jpeg,png,jpg,gif,svg.',
            'natid_back_dir_one.max' => 'National ID backside file should not be greater than 4MB.',
            'natid_back_dir_one.mimes' => 'National ID backside file should of the format: jpeg,png,jpg,gif,svg.',
            'passport_one.max' => 'Photo should not be greater than 4MB.',
            'passport_one.mimes' => 'Photo should of the format: jpeg,png,jpg,gif,svg.',
          ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('natid_front_dir_one') || $request->hasFile('natid_back_dir_one') || $request->hasFile('passport_one')) {

            //if ($request->file('natid_front_dir_one')->isValid() && $request->file('natid_back_dir_one')->isValid() && $request->file('passport_one')->isValid()) {

                if($request->hasFile('natid_front_dir_one')){
                   if ($request->file('natid_front_dir_one')->isValid()) {
                      $nidFrontOne = $request->file('natid_front_dir_one');
                      $nidFrontOneFile = 'nid_' . time() . '.' . $nidFrontOne->getClientOriginalExtension();
                      Storage::disk('public')->put('merchants/nationalids/' . $nidFrontOneFile, File::get($nidFrontOne));
                      $merchantKYC->national_id1 = $nidFrontOneFile;
                      $merchantKYC->national_id1_stat = 1;
                   }else {
                      return back()->with('error','Invalid image supplied.');
                   }
                }

                if($request->hasFile('natid_back_dir_one')) {
                   if ($request->file('natid_back_dir_one')->isValid()) {
                      $nidFrontTwo = $request->file('natid_back_dir_one');
                      $nidFrontTwoFile = 'nid_' . time() . '.' . $nidFrontTwo->getClientOriginalExtension();
                      Storage::disk('public')->put('merchants/nationalids/' . $nidFrontTwoFile, File::get($nidFrontTwo));
                      $merchantKYC->national_id1_back_pic = $nidFrontTwoFile;
                      $merchantKYC->national_id1_back_stat = 1;
                   }else {
                      return back()->with('error','Invalid image supplied.');
                   }
                }

                if($request->hasFile('passport_one')){
                   if ($request->file('passport_one')->isValid()) {
                      $photoOne = $request->file('passport_one');
                      $photoOneFile = 'nid_' . time() . '.' . $photoOne->getClientOriginalExtension();
                      Storage::disk('public')->put('merchants/photos/' . $photoOneFile, File::get($photoOne));
                      $merchantKYC->pphoto1 = $photoOneFile;
                      $merchantKYC->pphoto1_stat = 1;
                   }else {
                      return back()->with('error','Invalid image supplied.');
                   }
                }

                $merchantKYC->dir_one_name = $request->dir_one_name;
                $merchantKYC->dir_one_lname = $request->dir_one_lname;
                $merchantKYC->dir_one_nid = $request->dir_one_nid;
                $merchantKYC->updated_at = now();

                $merchantKYC->save();
                $partnerid = $merchantKYC->id;
            /*} else {
                return back()->with('error','Invalid image supplied.');
            }*/

        } else {
            return back()->with('error','No file was detected here.');
        }

        return back()->with('success','Director 1 kyc documents updated successfully.');
    }

    public function updateDirectorTwoKyc(Request $request){
        if($request->mid==='NA12092023'){
            // Create merchant
            $partner = Partner::findOrFail($request->pid);
            $merchantKYC = MerchantKyc::create([
                'partner_id' => $partner->id,
                'natid' => $partner->regNumber,
            ]);
            $merchantKYC->save();
        } else{
            // Get merchant
            $merchantKYC = MerchantKyc::findOrFail($request->mid);
        }

        $validator = Validator::make($request->all(),
          [
            'dir_two_name' => 'nullable',
            'dir_two_lname' => 'nullable',
            'dir_two_nid' => array(
                'nullable',
                'max:15',
                'regex:/^[0-9]{2}-[0-9]{5,7}-[A-Z]-[0-9]{2}$|\d{6}\/\d{2}\/\d{1}$/'
            ),
            'natid_front_dir_two' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'natid_back_dir_two' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'passport_two' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
          ],
          [
            'dir_two_name.required' => 'We need First Name of Director to proceed.',
            'dir_two_lname.required' => 'We need Last Name of Director to proceed.',
            'dir_two_nid.required' => 'We need National ID Number of Director to proceed.',
            'dir_two_nid.max' => 'National ID Number should not be greater than 15 digits',
            'dir_two_nid.regex' => 'This National ID Number has an invalid ID Format.',
            'natid_front_dir_two.max' => 'National ID frontside file should not be greater than 4MB.',
            'natid_front_dir_two.mimes' => 'National ID frontside file should of the format: jpeg,png,jpg,gif,svg.',
            'natid_back_dir_two.max' => 'National ID backside file should not be greater than 4MB.',
            'natid_back_dir_two.mimes' => 'National ID backside file should of the format: jpeg,png,jpg,gif,svg.',
            'passport_two.max' => 'Photo should not be greater than 4MB.',
            'passport_two.mimes' => 'Photo should of the format: jpeg,png,jpg,gif,svg.',
          ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('natid_front_dir_two') || $request->hasFile('natid_back_dir_two') || $request->hasFile('passport_two')) {

            //if ($request->file('natid_front_dir_two')->isValid() && $request->file('natid_back_dir_two')->isValid() && $request->file('passport_two')->isValid()) {
            if($request->hasFile('natid_front_dir_two')) {
                if ($request->file('natid_front_dir_two')->isValid()) {
                   $nidFrontTwo = $request->file('natid_front_dir_two');
                   $nidFrontTwoFile = 'nid_' . time() . '.' . $nidFrontTwo->getClientOriginalExtension();
                   Storage::disk('public')->put('merchants/nationalids/' . $nidFrontTwoFile, File::get($nidFrontTwo));
                   $merchantKYC->national_pic_front_dir_two = $nidFrontTwoFile;
                   $merchantKYC->national_id2_front_stat = 1;
                }
            }

            if($request->hasFile('natid_back_dir_two')) {
                if ($request->file('natid_back_dir_two')->isValid()) {
                   $nidBackTwo = $request->file('natid_back_dir_two');
                   $nidBackTwoFile = 'nid_' . time() . '.' . $nidBackTwo->getClientOriginalExtension();
                   Storage::disk('public')->put('merchants/nationalids/' . $nidBackTwoFile, File::get($nidBackTwo));
                   $merchantKYC->national_pic_back_dir_two = $nidBackTwoFile;
                   $merchantKYC->national_id2_back_stat = 1;
                }
            }

            if($request->hasFile('passport_two')) {
                if ($request->file('passport_two')->isValid()) {
                   $photoTwo = $request->file('passport_two');
                   $photoTwoFile = 'nid_' . time() . '.' . $photoTwo->getClientOriginalExtension();
                   Storage::disk('public')->put('merchants/photos/' . $photoTwoFile, File::get($photoTwo));
                   $merchantKYC->pphoto2 = $photoTwoFile;
                   $merchantKYC->pphoto2_stat = 1;
                }
            }

            $merchantKYC->dir_two_name = $request->dir_two_name;
            $merchantKYC->dir_two_lname = $request->dir_two_lname;
            $merchantKYC->dir_two_nid = $request->dir_two_nid;
            $merchantKYC->updated_at = now();
            $merchantKYC->save();
            /*} else {
                return back()->with('error','Invalid image supplied.');
            }*/

        } else {
            return back()->with('error','No file was detected here.');
        }

        return back()->with('success','Director 2 kyc documents updated successfully.');
    }

    public function agreeWithPartner(){
        $yuser = Partner::where('regNumber', auth()->user()->natid)->first();

        return view('partners.merchant-agreement', compact('yuser'));
    }

    function uploadPartnerSignature(Request $request)
    {
        $rules = array(
            'signature'  => 'required|image|max:2048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('signature');

        $new_name = auth()->user()->name . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('partnersign'), $new_name);

        $output = array(
            'success' => 'Signature uploaded successfully.',
            'image'  => '<img src="/partnersign/'.$new_name.'" class="img-thumbnail" />'
        );

        $partner = Partner::where('regNumber', auth()->user()->natid)->first();

        $partner->signature = $new_name;
        $partner->partner_sign = true;
        $partner->updated_at = now();

        $partner->save();

        return redirect()->back()->with('success','Signature uploaded successfully.');
    }

    public function getPartnerUsers() {
        $clients = Client::where('creator', auth()->user()->name)->get();

        return view('partners.partner-users', compact('clients'));
    }

    public function getMerchantKyc() {
        $merchants = Partner::where('partner_type','Merchant')->get();

        return view('partners.merchant-kycs', compact('merchants'));
    }

    public function generateAgreementPdf($id) {
        $partner = Partner::findOrFail($id);
        $settings = Masetting::find(1)->first();

        $pdf = \PDF::loadView('partners.agreement-form', compact('partner', 'settings'));

        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(522, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream("Agreement - ".$partner->partner_name.".pdf");
    }

    function updatePartnerSignature(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'regNumber'  => 'required',
            ],
            [
                'signature.required'       => 'Your signature picture is required.',
                'signature.max'                 => 'Signature should not be greater than 4MB.',
                'signature.mimes'               => 'Signature should of the format: jpeg,png,jpg,gif,svg.',
                'regNumber.required' => 'Please make sure you\'re logged in and you followed the proper process for changing signature.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {
                $image = $request->file('signature');
                $partner = Partner::where('regNumber', $request->input('regNumber'))->first();

                $new_name = strtolower($partner->partner_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('partnersign'), $new_name);

                $partner->signature = $new_name;
                $partner->partner_sign = true;
                $partner->updated_at = now();

                $partner->save();

            } else {
                return redirect()->back()->with('error','Invalid image supplied.');
            }
        } else {
            return redirect()->back()->with('error','No file was detected here.');
        }

        return redirect()->back()->with('success','Signature updated successfully.');

    }

    public function uploadMerchantKyc(){
        $yuser = Partner::where('regNumber', auth()->user()->natid)->first();
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        if ($yuser->partner_type == 'Merchant'){
            return view('partners.upload-merchant-kyc', compact('yuser','kyc'));
        } else {
            return view('partners.upload-agent-kyc', compact('yuser','kyc'));
        }
    }

    public function approveMerchant($partnerid){
        $partner = Partner::findOrFail($partnerid);
        $emails = [$partner->cemail, 'loanszam@astroafrica.tech'];
        $data = [];

        $agreemntFile = "agreement_".$partner->regNumber.".pdf";
        $pdfFileName = storage_path() . DIRECTORY_SEPARATOR . 'app/downloads/merchants/agreement' . DIRECTORY_SEPARATOR . $agreemntFile;
        $pdf = \PDF::loadView('partners.partner-agreement-form', compact('partner'));
        $pdf->save($pdfFileName);

        $partner->status=1;
        $partner->partner_agreement=$pdfFileName;
        $partner->save();

        Mail::send('emails.new-merchant-agreement', $data, function($message)use($emails, $pdfFileName) {
            $message->to($emails)->subject('Merchant Agreement with AstroCred');
            $message->attach($pdfFileName);
        });

        return redirect('partners/'.$partnerid)->with('success', 'Merchant approved successfully and agreement mailed to the merchant.');
    }

    public function activateMerchant($partnerid){
        $partner = Partner::findOrFail($partnerid);
        $emails = [$partner->cemail, 'loanszam@astroafrica.tech'];
        $plainpassword = Str::random(10);
        $data = ['partner_name' => $partner->partner_name, 'mobile' => $partner->telephoneNo, 'password' => $plainpassword];
        $password = Hash::make($plainpassword);

        DB::statement("UPDATE users SET `password`='". $password ."', `activated`=true, `status`=1 WHERE email='". $partner->cemail . "'");

        Mail::send('emails.new-merchant-activation', $data, function($message)use($emails) {
            $message->to($emails)->subject('Merchant Account Activation');
        });

        return redirect()->back()->with('success', 'Merchant activated successfully.');
    }

    public function rejectMerchant(Request $request, $partnerid){
        $partner = Partner::findOrFail($partnerid);
        $emails = [$partner->cemail, 'loanszam@astroafrica.tech'];
        $rejectedFor = $request->rejectReason;
        $data = [ 'partner_name' => $partner->partner_name];

        $data = ['rejectedFor' => $rejectedFor, 'partner_name' => $partner->partner_name];
        Mail::send('emails.merchant-rejected', $data, function($message)use($emails) {
           $message->to($emails)->subject('Applicaion to Register as Merchant on Astrocred Rejected');
        });
        return redirect()->back()->with('success', 'Merchant application rejected successfully.');
    }

    public function myBranches(){
        $branches = DB::table('merchant_branches as mb')
            ->join('partners as p', 'p.id','=','mb.partner_id')
            ->select('mb.id','mb.name','mb.location', 'mb.contact_no', 'mb.address', 'p.partner_name')
            ->where('mb.creator','=',auth()->user()->name)
            ->where('mb.deleted_at','=',null)
            ->get();

        return view('mb-branch.my-branches', compact('branches'));
    }

    public function newBranch()
    {
        $partners = Partner::all();
        return view('mb-branch.add-branch');
    }

    public function addNewBranch(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'partner_id' => 'required',
                'name' => 'required|unique:merchant_branches',
                'location' => 'required',
                'contact_no' => 'required|unique:merchant_branches',
                'address' => 'required',
            ],
            [
                'partner_id.required'        => 'What company is being represented by the rep?',
                'name.required'          => 'What is the name of the branch?',
                'name.unique'       => 'Branch is already within the system.',
                'location.required'          => 'What is the location of the branch?',
                'contact_no.required'       => 'Branch telephone number is required.',
                'contact_no.unique'       => 'Branch telephone number is already within the system.',
                'address.required'       => 'Branch address is required.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $mybranch = MerchantBranches::create([
            'partner_id' => $request->input('partner_id'),
            'creator' => auth()->user()->name,
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'contact_no' => $request->input('contact_no'),
            'address' => $request->input('address'),
        ]);

        $mybranch->save();

        return redirect('my-branches')->with('success', 'Branch added successfully.');
    }

    public function getBranchById($id){
        $branch = MerchantBranches::findOrFail($id);
        return view('mb-branch.view-branch', compact('branch'));
    }

    public function editBranch($id){
        $branch = MerchantBranches::findOrFail($id);
        return view('mb-branch.edit-branch', compact('branch'));
    }

    public function saveBranch(Request $request){
        $validator = Validator::make($request->all(),
            [
                'bid' => 'required',
                'name' => 'required',
                'location' => 'required',
                'contact_no' => 'required',
                'address' => 'required',
            ],
            [
                'bid.required'        => 'Branch is required',
                'name.required'          => 'What is the name of the branch?',
                'location.required'          => 'What is the location of the branch?',
                'contact_no.required'       => 'Branch telephone number is required.',
                'address.required'       => 'Branch address is required.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $branch = MerchantBranches::findOrFail($request->input('bid'));
        $branch->name = $request->input('name');
        $branch->location = $request->input('location');
        $branch->contact_no = $request->input('contact_no');
        $branch->address = $request->input('address');
        $branch->save();

        return redirect('my-branches')->with('success', 'Branch updated successfully.');
    }

    public function deleteBranch(Request $request){
        $branch = MerchantBranches::findOrFail($request->input('bid'));
        $branch->deleted_at = now();
        $branch->save();
    }
}
