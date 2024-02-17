<?php

namespace App\Http\Controllers;

use App\Events\NewZambian;
use App\Models\Bank;
use App\Models\Client;
use App\Models\Localel;
use App\Models\Profile;
use App\Models\User;
use App\Models\Zambian;
use App\Traits\CaptureIpTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use jeremykenedy\LaravelRoles\Models\Role;

class ZambianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Zambian::all();
        return view('zambians.clients', compact('clients'));
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
        $name = generateUsername($_POST['first_name'], $_POST['last_name']);

        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'nrc'                 => 'required|max:15|unique:zambians',
                'email'                 => 'nullable|email|max:255|unique:zambians',
                'mobile'                 => 'required|max:10|unique:zambians',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'kin_name'        => 'required',
                'kin_relationship'        => 'required',
                'kin_address'        => 'required',
                'kin_number'        => 'required',
                'bank_name'        => 'required',
                'branch'        => 'required',
                'bank_account'        => 'required',
                'pass_photo'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'nrc_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'por_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'pslip_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'files'  => 'nullable|mimes:pdf|max:4096',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $nrcFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('nrc_pic')->getClientOriginalExtension();

        if($request->hasFile('nrc_pic')) {

            if ($request->file('nrc_pic')->isValid()) {
                $nrcPhoto = $request->file('nrc_pic');

                Storage::disk('public')->put('zam_nrcs/' . $nrcFilename, File::get($nrcPhoto));

            } else {
                return redirect()->back()->with('error','Invalid NRC photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','NRC photo not found')->withInput();
        }

        $porFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('por_pic')->getClientOriginalExtension();

        if($request->hasFile('por_pic')) {

            if ($request->file('por_pic')->isValid()) {
                $porPhoto = $request->file('por_pic');

                Storage::disk('public')->put('zam_pors/' . $porFilename, File::get($porPhoto));

            } else {
                return redirect()->back()->with('error','Invalid Proof of Residence photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Proof of Residence photo not found')->withInput();
        }

        $pslipFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('pslip_pic')->getClientOriginalExtension();

        if($request->hasFile('pslip_pic')) {

            if ($request->file('pslip_pic')->isValid()) {
                $pslipPhoto = $request->file('pslip_pic');

                Storage::disk('public')->put('zam_payslips/' . $pslipFilename, File::get($pslipPhoto));

            } else {
                return redirect()->back()->with('error','Invalid Payslip photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Payslip photo not found')->withInput();
        }

        $filename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('pass_photo')->getClientOriginalExtension();

        if($request->hasFile('pass_photo')) {

            if ($request->file('pass_photo')->isValid()) {
                $passphoto = $request->file('pass_photo');

                Storage::disk('public')->put('zam_pphotos/' . $filename, File::get($passphoto));

            } else {
                return redirect()->back()->with('error','Invalid passport photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Passport photo not found')->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $newPass = str_random(8);
        $bank = Bank::where('id','=',$request['bank_name'])->first();

        $client = Zambian::create([
            'creator'              => auth()->user()->name,
            'borrower_id'              => $request['borrower_id'] ?? '',
            'title'              => $request['title'],
            'first_name'        => strip_tags($request['first_name']),
            'middle_name'        => strip_tags($request['middle_name']),
            'last_name'         => strip_tags($request['last_name']),
            'nrc'             => strtoupper($request['nrc']),
            'email'             => $request['email'],
            'mobile'             => $request['mobile'],
            'dob'             => $request['dob'],
            'gender'             => $request['gender'],
            'business_employer'             => $request['business_employer'],
            'address'             => $request['address'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'zip_code'             => $request['zip_code'],
            'landline'             => $request['landline'],
            'work_status'             => $request['work_status'],
            'credit_score'             => $request['credit_score'],
            'pass_photo'             => $filename,
            'nrc_pic'             => $nrcFilename,
            'por_pic'             => $porFilename,
            'pslip_pic'             => $pslipFilename,
            'description'             => $request['description'],
            'loan_officer_access'             => $request['loan_officer_access'],
            'institution'             => $request['institution'],
            'ec_number'             => $request['ec_number'],
            'department'             => $request['department'],
            'kin_name'             => $request['kin_name'],
            'kin_relationship'             => $request['kin_relationship'],
            'kin_address'             => $request['kin_address'],
            'kin_number'             => $request['kin_number'],
            'bank_name'             => $bank->bank,
            'bank_account'             => $request['bank_account'],
            'branch'             => $request['branch'],
        ]);
        $client->save();

        if ($client->save()) {
            $user = User::create([
                'name'              => $name,
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'email'             => $request['email'],
                'natid'             => strtoupper($request['nrc']),
                'mobile'             => $request['mobile'],
                'utype'             => 'Client',
                'locale'             => '2',
                'password'          => Hash::make($newPass),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress,
                'activated'         => $activated,
            ]);

            $user->attachRole($role);
            event(new NewZambian($client, $newPass));

            $profile = new Profile();
            $user->profile()->save($profile);

        }

        return redirect('zambians')->with('success', 'Client onboarded.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zambian  $zambian
     * @return \Illuminate\Http\Response
     */
    public function show(Zambian $zambian)
    {
        return view('zambians.zambian-info', compact('zambian'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zambian  $zambian
     * @return \Illuminate\Http\Response
     */
    public function edit(Zambian $zambian)
    {
        $provinces = DB::table("provinces")
            ->where("country", '=',2)
            ->select("id", "country", "province")
            ->orderBy("province", 'asc')
            ->get();

        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();

        return view('zambians.edit-zambian', compact('zambian', 'banks', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zambian  $zambian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zambian $zambian)
    {
        $user = User::where('natid', $zambian->nrc)->firstOrfail();
        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'nrc'                 => 'required|max:15|unique:zambians,nrc,'.$zambian->id,
                'email'                 => 'nullable|email|max:255|unique:zambians,email,'.$zambian->id,
                'mobile'                 => 'required|max:10|unique:zambians,mobile,'.$zambian->id,
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'kin_name'        => 'required',
                'kin_relationship'        => 'required',
                'kin_address'        => 'required',
                'kin_number'        => 'required',
                'bank_name'        => 'required',
                'branch'        => 'required',
                'bank_account'        => 'required',
                'pass_photo'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        if($request->hasFile('pass_photo')) {
            $filename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('pass_photo')->getClientOriginalExtension();

            if ($request->file('pass_photo')->isValid()) {
                $passphoto = $request->file('pass_photo');

                Storage::disk('public')->put('zam_pphotos/' . $filename, File::get($passphoto));
                $zambian->pass_photo = $filename;
            } else {
                return redirect()->back()->with('error','Invalid passport photo')->withInput();
            }
        }

        $bank = Bank::where('bank','=',$request['bank_name'])->first();

        $zambian->title = $request['title'];
        $zambian->first_name = strip_tags($request['first_name']);
        $zambian->middle_name = strip_tags($request['middle_name']);
        $zambian->last_name = strip_tags($request['last_name']);
        $zambian->nrc = $request['nrc'];
        $zambian->email = $request['email'];
        $zambian->mobile = $request['mobile'];
        $zambian->dob = $request['dob'];
        $zambian->gender = $request['gender'];
        $zambian->business_employer = $request['business_employer'];
        $zambian->address = $request['address'];
        $zambian->city = $request['city'];
        $zambian->province = $request['province'];
        $zambian->zip_code = $request['zip_code'];
        $zambian->landline = $request['landline'];
        $zambian->work_status = $request['work_status'];
        $zambian->credit_score = $request['credit_score'];
        $zambian->pass_photo = $request['title'];

        $zambian->description = $request['description'];
        $zambian->files = $request['files'];
        $zambian->loan_officer_access = $request['loan_officer_access'];
        $zambian->institution = $request['institution'];
        $zambian->ec_number = $request['ec_number'];
        $zambian->department = $request['department'];
        $zambian->kin_name = $request['kin_name'];
        $zambian->kin_relationship = $request['kin_relationship'];
        $zambian->kin_address = $request['kin_address'];
        $zambian->kin_number = $request['kin_number'];
        $zambian->bank_name = $bank->bank;
        $zambian->bank_account = $request['bank_account'];
        $zambian->branch = $request['branch'];

        $zambian->save();

        if ($zambian->save()) {
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->natid = $request['nrc'];
            $user->mobile = $request['mobile'];

            $user->save();
        }

        return redirect()->back()->with('success', 'Client updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zambian  $zambian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zambian $zambian)
    {
        $zambian->delete();
        return redirect()->back()->with('success','Client deleted');
    }

    public function registerOne(){
        $provinces = DB::table("provinces")
            ->where("country", '=',2)
            ->select("id", "country", "province")
            ->orderBy("province", 'asc')
            ->get();

        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();

        return view('zambians.register-zambian', compact('provinces', 'banks'));
    }

    public function registerForZambian(){
        $provinces = DB::table("provinces")
            ->where("country", auth()->user()->locale)
            ->select("id", "country", "province")
            ->orderBy("province", 'asc')
            ->get();

        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();

        return view('zambians.register-for-zambian', compact('provinces', 'banks'));
    }

    public function getZambiansToPostToLoanDisk(){
        $clients = Zambian::where('ld_borrower_id',null)->get();

        return view('zambians.zambians-to-loandisk', compact('clients'));
    }

    public function reviewZambianInfo($id){
        $client = Zambian::where('id','=',$id)->firstOrFail();
        return view('kycs.review-zam-kyc', compact('client'));
    }

    public function updateRecordFromLoanDisk(Request $request){
        $user = User::where('natid', $request->input('borrower_unique_number'))->firstOrfail();
        $zambian = Zambian::where('nrc', $request->input('borrower_unique_number'))->firstOrfail();

        if ($zambian) {
            $zambian->ld_borrower_id = $request->input('borrower_id');
            $zambian->save();
            $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->put('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower',[
                    'borrower_id' => $zambian->ld_borrower_id,
                    'borrower_unique_number' => $zambian->nrc,
                    'borrower_firstname' => $zambian->first_name,
                    'borrower_lastname' => $zambian->last_name,
                    'borrower_business_name' => $zambian->business_employer,
                    'borrower_country' => 'ZM',
                    'borrower_title' => $zambian->title,
                    'borrower_working_status' => $zambian->work_status,
                    'borrower_gender' => $zambian->gender,
                    'borrower_mobile' => $zambian->mobile,
                    'borrower_dob' => date_format($zambian->dob, 'd/m/Y'),
                    'borrower_description' => $zambian->description ?? $zambian->first_name.' '.$zambian->middle_name.' '.$zambian->last_name,
                    'custom_field_11543' => $zambian->institution,
                    'custom_field_11302' => $zambian->ec_number,
                    'custom_field_11303' => $zambian->department,
                    'custom_field_11085' =>  $zambian->kin_name,
                    'custom_field_11083' => $zambian->kin_relationship,
                    'custom_field_11082' => $zambian->kin_address,
                    'custom_field_11084' => $zambian->kin_number,
                    'custom_field_11789' => $zambian->bank_name,
                    'custom_field_11788' => $zambian->bank_account,
                    'custom_field_11790' => $zambian->branch,
                ])
                ->body();

            $resp=json_decode($details, TRUE);

            if (isset($resp['response']['Errors'])){
                return redirect()->back()->with('errors', collect($resp['response']['Errors']));
            }

            return redirect('loan-disk-check')->with('success','Client record synced with LoanDisk');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'                  => 'required',
                'borrower_country'            => 'required',
                'borrower_firstname'             => 'required',
                'borrower_lastname'             => 'required',
                'borrower_gender'             => 'required',
                'borrower_dob'             => 'required',
                'borrower_unique_number'   => 'required|max:15|unique:zambians,nrc,'.$zambian->id,
                'borrower_mobile'                 => 'required|max:10|unique:zambians,mobile,'.$zambian->id,
                'borrower_business_name'                 => 'required',
                'custom_field_11543'                 => 'required',
                'custom_field_11302'        => 'required',
                'custom_field_11303'        => 'required',
                'custom_field_11085'        => 'required',
                'custom_field_11083'        => 'required',
                'custom_field_11082'        => 'required',
                'custom_field_11084'        => 'required',
                'custom_field_11789'        => 'required',
                'custom_field_11788'        => 'required',
                'custom_field_11790'        => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect('loan-disk-check')->withErrors($validator)->withInput();
        }

        $zambian->ld_borrower_id = $request->input('borrower_id');
        $zambian->first_name = $request->input('borrower_firstname');
        $zambian->last_name = $request->input('borrower_lastname');
        $zambian->mobile = $request->input('borrower_mobile');
        $zambian->dob = Carbon::createFromFormat('d/m/Y', $request->input('borrower_dob'))->format('d-m-Y') ;
        $zambian->gender = $request->input('borrower_gender');
        $zambian->business_employer = $request->input('borrower_business_name');
        $zambian->institution = $request->input('custom_field_11543');
        $zambian->ec_number = $request->input('custom_field_11302');
        $zambian->department = $request->input('custom_field_11303');
        $zambian->kin_name = $request->input('custom_field_11085');
        $zambian->kin_relationship = $request->input('custom_field_11083');
        $zambian->kin_address = $request->input('custom_field_11082');
        $zambian->kin_number = $request->input('custom_field_11084');
        $zambian->bank_name = $request->input('custom_field_11789');
        $zambian->bank_account = $request->input('custom_field_11788');
        $zambian->branch = $request->input('custom_field_11790');
        $zambian->save();

        if ($zambian->save()) {
            $user->first_name = $request->input('borrower_firstname');;
            $user->last_name = $request->input('borrower_lastname');
            $user->mobile = $request->input('borrower_mobile');

            $user->save();
        }

        return redirect('loan-disk-check')->with('success','Client record updated');

    }

    public function continueZambianRegistration(){

        $provinces = DB::table("provinces")
        ->where("country", auth()->user()->locale)
        ->select("id", "country", "province")
        ->orderBy("province", 'asc')
        ->get();

        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();
        return view('zambians.continue-zam-account', compact('provinces', 'banks'));
    }

    public function updateZambianRegistration(Request $request){
        $user = User::where('id', auth()->user()->id)->firstOrfail();
        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'nrc'                 => 'required|max:15|unique:zambians',
                'email'                 => 'nullable|email|max:255|unique:zambians,email,'.$user->id,
                'mobile'                 => 'required|max:10|unique:zambians,mobile,'.$user->id,
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'kin_name'        => 'required',
                'kin_relationship'        => 'required',
                'kin_address'        => 'required',
                'kin_number'        => 'required',
                'bank_name'        => 'required',
                'branch'        => 'required',
                'bank_account'        => 'required',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
                'pass_photo'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'files'  => 'nullable|mimes:pdf|max:4096',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $filename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('pass_photo')->getClientOriginalExtension();

        if($request->hasFile('pass_photo')) {

            if ($request->file('pass_photo')->isValid()) {
                $passphoto = $request->file('pass_photo');

                Storage::disk('public')->put('zam_pphotos/' . $filename, File::get($passphoto));

            } else {
                return redirect()->back()->with('error','Invalid passport photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Passport photo not found')->withInput();
        }


        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $bank = Bank::where('id','=',$request['bank_name'])->first();

        $client = Zambian::create([
            'creator'              => 'QCK_'.auth()->user()->name,
            'title'              => $request['title'],
            'first_name'        => strip_tags($request['first_name']),
            'middle_name'        => strip_tags($request['middle_name']),
            'last_name'         => strip_tags($request['last_name']),
            'nrc'             => strtoupper($request['nrc']),
            'email'             => $request['email'],
            'mobile'             => $request['mobile'],
            'dob'             => $request['dob'],
            'gender'             => $request['gender'],
            'business_employer'             => $request['business_employer'],
            'address'             => $request['address'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'zip_code'             => $request['zip_code'],
            'landline'             => $request['landline'],
            'work_status'             => $request['work_status'],
            'credit_score'             => $request['credit_score'],
            'pass_photo'             => $filename,
            'description'             => $request['description'],
            'files'             => $filename,
            'loan_officer_access'             => $request['loan_officer_access'],
            'institution'             => $request['institution'],
            'ec_number'             => $request['ec_number'],
            'department'             => $request['department'],
            'kin_name'             => $request['kin_name'],
            'kin_relationship'             => $request['kin_relationship'],
            'kin_address'             => $request['kin_address'],
            'kin_number'             => $request['kin_number'],
            'bank_name'             => $bank->bank,
            'bank_account'             => $request['bank_account'],
            'branch'             => $request['branch'],
        ]);
        $client->save();

        if ($client->save()) {
            $user->first_name =  $request['first_name'];
            $user->last_name =  $request['last_name'];
            $user->email =  $request['email'];
            $user->natid =  strtoupper($request['nrc']);
            $user->mobile =  $request['mobile'];

            $user->save();
        }

        return redirect('new-zambia-cash')->with('success', 'Account created!');

    }

    public function storeNewZambian(Request $request)
    {
        $name = generateUsername($_POST['first_name'], $_POST['last_name']);

        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'nrc'                 => 'required|max:15|unique:zambians',
                'email'                 => 'nullable|email|max:255|unique:zambians',
                'mobile'                 => 'required|max:10|unique:zambians',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'kin_name'        => 'required',
                'kin_relationship'        => 'required',
                'kin_address'        => 'required',
                'kin_number'        => 'required',
                'bank_name'        => 'required',
                'branch'        => 'required',
                'bank_account'        => 'required',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
                'pass_photo'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'nrc_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'por_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'pslip_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'files'  => 'nullable|mimes:pdf|max:4096',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $nrcFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('nrc_pic')->getClientOriginalExtension();

        if($request->hasFile('nrc_pic')) {

            if ($request->file('nrc_pic')->isValid()) {
                $nrcPhoto = $request->file('nrc_pic');

                Storage::disk('public')->put('zam_nrcs/' . $nrcFilename, File::get($nrcPhoto));

            } else {
                return redirect()->back()->with('error','Invalid NRC photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','NRC photo not found')->withInput();
        }

        $porFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('por_pic')->getClientOriginalExtension();

        if($request->hasFile('por_pic')) {

            if ($request->file('por_pic')->isValid()) {
                $porPhoto = $request->file('por_pic');

                Storage::disk('public')->put('zam_pors/' . $porFilename, File::get($porPhoto));

            } else {
                return redirect()->back()->with('error','Invalid Proof of Residence photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Proof of Residence photo not found')->withInput();
        }

        $pslipFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('pslip_pic')->getClientOriginalExtension();

        if($request->hasFile('pslip_pic')) {

            if ($request->file('pslip_pic')->isValid()) {
                $pslipPhoto = $request->file('pslip_pic');

                Storage::disk('public')->put('zam_payslips/' . $pslipFilename, File::get($pslipPhoto));

            } else {
                return redirect()->back()->with('error','Invalid Payslip photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Payslip photo not found')->withInput();
        }

        $filename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('pass_photo')->getClientOriginalExtension();

        if($request->hasFile('pass_photo')) {

            if ($request->file('pass_photo')->isValid()) {
                $passphoto = $request->file('pass_photo');

                Storage::disk('public')->put('zam_pphotos/' . $filename, File::get($passphoto));

            } else {
                return redirect()->back()->with('error','Invalid passport photo')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Passport photo not found')->withInput();
        }
        $crbFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('files')->getClientOriginalExtension();

        if($request->hasFile('files')) {

            if ($request->file('files')->isValid()) {
                $crbDoc = $request->file('files');

                Storage::disk('public')->put('zam_crb_reports/' . $crbFilename, File::get($crbDoc));

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $bank = Bank::where('id','=',$request['bank_name'])->first();

        $client = Zambian::create([
            'creator'              => 'Self',
            'title'              => $request['title'],
            'first_name'        => strip_tags($request['first_name']),
            'middle_name'        => strip_tags($request['middle_name']),
            'last_name'         => strip_tags($request['last_name']),
            'nrc'             => strtoupper($request['nrc']),
            'email'             => $request['email'],
            'mobile'             => $request['mobile'],
            'dob'             => $request['dob'],
            'gender'             => $request['gender'],
            'business_employer'             => $request['business_employer'],
            'address'             => $request['address'],
            'city'             => $request['city'],
            'province'             => $request['province'],
            'zip_code'             => $request['zip_code'],
            'landline'             => $request['landline'],
            'work_status'             => $request['work_status'],
            'credit_score'             => $request['credit_score'],
            'pass_photo'             => $filename,
            'nrc_pic'             => $nrcFilename,
            'por_pic'             => $porFilename,
            'pslip_pic'             => $pslipFilename,
            'description'             => $request['description'],
            'files'             => $crbFilename,
            'loan_officer_access'             => $request['loan_officer_access'],
            'institution'             => $request['institution'],
            'ec_number'             => $request['ec_number'],
            'department'             => $request['department'],
            'kin_name'             => $request['kin_name'],
            'kin_relationship'             => $request['kin_relationship'],
            'kin_address'             => $request['kin_address'],
            'kin_number'             => $request['kin_number'],
            'bank_name'             => $bank->bank,
            'bank_account'             => $request['bank_account'],
            'branch'             => $request['branch'],
        ]);
        $client->save();

        if ($client->save()) {
            $user = User::create([
                'name'              => $name,
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'email'             => $request['email'],
                'natid'             => strtoupper($request['nrc']),
                'mobile'             => $request['mobile'],
                'utype'             => 'Client',
                'locale'             => '2',
                'password'          => Hash::make($request->input('password')),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress,
                'activated'         => $activated,
            ]);

            $user->attachRole($role);
            event(new NewZambian($client, $request->input('password')));

            $profile = new Profile();
            $user->profile()->save($profile);
            Auth::login($user);
        }

        return redirect('home');
    }

    public function uploadZambiaNRC(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnrc'  => 'required',
                'nrc'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnrc.required'                 => 'Client NRC number is required.',
                'nrc.required'                 => 'NRC is required.',
                'nrc.image'                 => 'NRC file should be an image.',
                'nrc.max'                 => 'NRC file should not be greater than 4MB.',
                'nrc.mimes'               => 'NRC file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $zambian = Zambian::where('nrc', $request->input('clientnrc'))->first();

        $nrcFilename = str_replace("/","_",$request->input('clientnrc')) . '.' . $request->file('nrc')->getClientOriginalExtension();

        if($request->hasFile('nrc')) {

            if ($request->file('nrc')->isValid()) {
                $nrcDoc = $request->file('nrc');

                Storage::disk('public')->put('zam_nrcs/' . $nrcFilename, File::get($nrcDoc));
                $zambian->nrc_pic = $nrcFilename;
                $zambian->save();

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        } else {
            return redirect()->back()->with('error','NRC file not found')->withInput();
        }

        return redirect()->back()->with('success','NRC Uploaded successfully.');

    }

    public function uploadZambiaPassportPhoto(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnrc'  => 'required',
                'passport'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnrc.required'                 => 'Client NRC number is required.',
                'passport.required'                 => 'Passport Photo is required.',
                'passport.image'                 => 'Passport Photo file should be an image.',
                'passport.max'                 => 'Passport Photo file should not be greater than 4MB.',
                'passport.mimes'               => 'Passport Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $zambian = Zambian::where('nrc', $request->input('clientnrc'))->first();

        $pphotoFilename = str_replace("/","_",$request->input('clientnrc')) . '.' . $request->file('passport')->getClientOriginalExtension();

        if($request->hasFile('passport')) {

            if ($request->file('passport')->isValid()) {
                $pphotoDoc = $request->file('passport');

                Storage::disk('public')->put('zam_pphotos/' . $pphotoFilename, File::get($pphotoDoc));
                $zambian->pass_photo = $pphotoFilename;
                $zambian->save();

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Passport Photo file not found')->withInput();
        }

        return redirect()->back()->with('success','Passport Photo Uploaded successfully.');

    }

    public function uploadZambiaCurrentPayslip(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnrc'  => 'required',
                'payslip'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnrc.required'                 => 'Client NRC number is required.',
                'payslip.required'                 => 'Payslip Photo is required.',
                'payslip.image'                 => 'Payslip Photo file should be an image.',
                'payslip.max'                 => 'Payslip Photo file should not be greater than 4MB.',
                'payslip.mimes'               => 'Payslip Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $zambian = Zambian::where('nrc', $request->input('clientnrc'))->first();

        $pslipFilename = str_replace("/","_",$request->input('clientnrc')) . '.' . $request->file('payslip')->getClientOriginalExtension();

        if($request->hasFile('payslip')) {

            if ($request->file('payslip')->isValid()) {
                $pslipDoc = $request->file('payslip');

                Storage::disk('public')->put('zam_payslips/' . $pslipFilename, File::get($pslipDoc));
                $zambian->pslip_pic = $pslipFilename;
                $zambian->save();

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Payslip Photo file not found')->withInput();
        }

        return redirect()->back()->with('success','Payslip Photo Uploaded successfully.');

    }

    public function uploadZambiaProofOfRes(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnrc'  => 'required',
                'proofres'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnrc.required'                 => 'Client NRC number is required.',
                'proofres.required'                 => 'Proof of Residence Photo is required.',
                'proofres.image'                 => 'Proof of Residence Photo file should be an image.',
                'proofres.max'                 => 'Proof of Residence Photo file should not be greater than 4MB.',
                'proofres.mimes'               => 'Proof of Residence Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $zambian = Zambian::where('nrc', $request->input('clientnrc'))->first();

        $porFilename = str_replace("/","_",$request->input('clientnrc')) . '.' . $request->file('proofres')->getClientOriginalExtension();

        if($request->hasFile('proofres')) {

            if ($request->file('proofres')->isValid()) {
                $porDoc = $request->file('proofres');

                Storage::disk('public')->put('zam_pors/' . $porFilename, File::get($porDoc));
                $zambian->por_pic = $porFilename;
                $zambian->save();

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Proof of Residence Photo file not found')->withInput();
        }

        return redirect()->back()->with('success','Proof of Residence Photo Uploaded successfully.');

    }

    public function uploadZambiaCrbDocument(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnrc'  => 'required',
                'crb_pic'  => 'required|mimes:pdf|max:4096'
            ],
            [
                'clientnrc.required'                 => 'Client NRC number is required.',
                'crb_pic.required'                 => 'CRB Document is required.',
                'crb_pic.image'                 => 'CRB Document file should be a PDF.',
                'crb_pic.max'                 => 'CRB Document file should not be greater than 4MB.',
                'crb_pic.mimes'               => 'CRB Document file should of the format: pdf.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $zambian = Zambian::where('nrc', $request->input('clientnrc'))->first();

        $crbFilename = str_replace("/","_",$request->input('clientnrc')) . '.' . $request->file('crb_pic')->getClientOriginalExtension();

        if($request->hasFile('crb_pic')) {

            if ($request->file('crb_pic')->isValid()) {
                $crbDoc = $request->file('crb_pic');

                Storage::disk('public')->put('zam_crb_reports/' . $crbFilename, File::get($crbDoc));
                $zambian->files = $crbFilename;
                $zambian->save();

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        } else {
            return redirect()->back()->with('error','CRB Document file not found')->withInput();
        }

        return redirect()->back()->with('success','CRB Document Uploaded successfully.');

    }

    public function uploadZambiaSignature(Request $request){

        $validator = Validator::make($request->all(),
            [
                'clientnrc'  => 'required',
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'clientnrc.required'                 => 'Client NRC number is required.',
                'signature.required'                 => 'Signature Photo is required.',
                'signature.image'                 => 'Signature file should be an image.',
                'signature.max'                 => 'Signature file should not be greater than 4MB.',
                'signature.mimes'               => 'Signature file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $zambian = Zambian::where('nrc', $request->input('clientnrc'))->first();

        $signFilename = str_replace("/","_",$request->input('clientnrc')) . '.' . $request->file('signature')->getClientOriginalExtension();

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {
                $signDoc = $request->file('signature');

                Storage::disk('public')->put('zam_signs/' . $signFilename, File::get($signDoc));
                $zambian->sign_pic = $signFilename;
                $zambian->save();

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        } else {
            return redirect()->back()->with('error','Signature file not found')->withInput();
        }

        return redirect()->back()->with('success','Signature Uploaded successfully.');

    }

    public function checkLoanAffordability(){
        return view('zambians.agent-check-afford');
    }

    public function createSavingsAccount(Request $request){

        $validator = Validator::make($request->all(),
            [
                'id'  => 'required',
                'limit'  => 'required',
            ],
            [
                'limit.required'                 => 'Credit limit is required.',
                'id.required'                 => 'Client ID is required.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $client = Zambian::where('id','=',$request->input('id'))->firstOrFail();
        $randSaveAcc = Str::uuid();

        if ($client->ld_borrower_id) {
            $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/saving',[
                    'borrower_id' => $client->ld_borrower_id,
                    'savings_product_id' => '4781',
                    'savings_account_number' => $randSaveAcc,
                    'savings_description' => "Virtual Credit Savings account",
                ])
                ->body();

            $resp=json_decode($details, TRUE);

            if (isset($resp['response']['Errors'])){
                return redirect()->back()->with('errors', collect($resp['response']['Errors']));
            }

            if (isset($resp['response']['savings_id'])){
                $details = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
                ])
                    ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/saving_transaction',[
                        'savings_id' => $resp['response']['savings_id'],
                        'transaction_date' => Carbon::now()->format('d/m/Y'),
                        'transaction_time' => Carbon::now()->format('H:m:ss A'),
                        'transaction_type_id' => "1",
                        'transaction_amount' => $request->input('limit'),
                        'transaction_description' => "Initial Savings Account Deposit",
                    ])
                    ->body();

                $balResp=json_decode($details, TRUE);

                if (isset($balResp['response']['Errors'])){
                    return redirect()->back()->with('errors', collect($balResp['response']['Errors']));
                }
            }

        } else {
            return redirect()->back()->with('error', 'Make sure Loans Officer and Manager have reviewed KYC first and client exists in Loan Disk.');
        }

        $client->savings_acc = $randSaveAcc;
        $client->savings_id = $resp['response']['savings_id'];
        $client->save();

        return redirect()->back()->with('success', 'Savings Account Created');
    }

    public function getSavingsAccounts(){
        $kycs = DB::table('zambians as z')
            ->select('z.id','z.ld_borrower_id','z.first_name','z.last_name','z.nrc','z.savings_acc','z.savings_id')
            ->where('z.officer_stat','=',true)
            ->where('z.manager_stat','=',true)
            ->where('z.savings_acc','!=',null)
            ->where('z.savings_id','!=',null)
            ->where('z.deleted_at', '=',null)
            ->get();

        return view('zambians.zambia-savings', compact('kycs'));
    }
}
