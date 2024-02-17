<?php

namespace App\Http\Controllers;

use App\Events\MerchantSignedUp;
use App\Models\Client;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\MerchantKyc;
use App\Models\Partner;
use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;
use Auth;

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
        return view('partners.partner-info', compact('partner', 'user'));
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

        return view('partners.edit-partner', compact('partner', 'banks'));
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
                'partner_type.required'           => 'What is your relationship to eShagi like?',
                'business_type.required'          => 'What is the nature of your company?',
                'partnerDesc.required'            => 'What is the nature of your business?',
                'yearsTrading.required'           => 'How many years have you been trading?',
                'regNumber.unique'                   => 'This company registration number is already registered with eShagi.',
                'bpNumber.unique'                   => 'This business partner number is already registered with eShagi.',
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
                'cemail.unique'                   => 'This email is already registered with eShagi.',
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
                ],
                [
                    'partner_name.required'           => 'What is your Partner name?',
                    'partner_type.required'           => 'What is your relationship to eShagi like?',
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
                    'cemail.unique'                   => 'This email is already registered with eShagi.',
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
                    'partner_type.required'           => 'What is your relationship to eShagi like?',
                    'business_type.required'          => 'What is the nature of your company?',
                    'partnerDesc.required'            => 'What is the nature of your business?',
                    'yearsTrading.required'           => 'How many years have you been trading?',
                    'regNumber.unique'                   => 'This company registration number is already registered with eShagi.',
                    'bpNumber.unique'                   => 'This business partner number is already registered with eShagi.',
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
                    'cemail.unique'                   => 'This email is already registered with eShagi.',
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

            Auth::login($user);
        }

        return redirect('merchant-agreement');
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
}
