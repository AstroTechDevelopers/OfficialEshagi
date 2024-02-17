<?php

namespace App\Http\Controllers;

use App\Models\Astrogent;
use App\Models\Bank;
use App\Models\Client;
use App\Models\Creditlimit;
use App\Models\Localel;
use App\Models\Masetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Validator;
use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use jeremykenedy\LaravelRoles\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AstrogentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Astrogent::select('id','name', 'first_name', 'last_name', 'natid','mobile','activated');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = " <form class='btn btn-sm btn-danger' method='POST' action='astrogents/". $row['id']."'>
                             <input type='hidden' name='_token' value='".csrf_token()."'>
                             <input name='_method' type='hidden' value='DELETE'>
                             <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#confirmDelete'
                                data-title='Delete Astrogent' data-message='Are you sure you want to delete this astrogent ?'>
                                <i class='mdi mdi-trash-can-outline' aria-hidden='true'></i>
                                </button>

                             </form>
                            <a class='btn btn-sm btn-success' href='astrogents/". $row['id']."' >
                                <i class='mdi mdi-eye-outline' aria-hidden='true'></i>
                            </a>

                            <a class='btn btn-sm btn-info' href='astrogents/".$row['id']."/edit' >
                                <i class='mdi mdi-account-edit-outline' aria-hidden='true'></i>
                            </a>";
                    return $btn;
                })
                ->editColumn('activated', function ($data) {
                    return  ($data->show == 1) ? "Yes":"No";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    return view('astrogents.astrogents');
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
     * @param  \App\Models\Astrogent  $astrogent
     * @return \Illuminate\Http\Response
     */
    public function show(Astrogent $astrogent)
    {
        $bank = Bank::where('id', $astrogent->bank)->first();
        return view('astrogents.astrogent-info', compact('astrogent', 'bank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Astrogent  $astrogent
     * @return \Illuminate\Http\Response
     */
    public function edit(Astrogent $astrogent)
    {
        $localels = Localel::all();
        $banks = Bank::all();
        return view('astrogents.edit-astrogent', compact('astrogent', 'banks','localels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Astrogent  $astrogent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Astrogent $astrogent) {
        ActivityLogger::activity(auth()->user()->name . " has attempted to edit client " . $astrogent->natid . " with agent ID: " . $astrogent->id);
        $user = User::where('natid', $astrogent->natid)->first();
        if ($astrogent->first_name != $_POST['first_name'] OR $astrogent->last_name != $_POST['last_name']) {
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

        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:5',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:astrogents,natid,'.$astrogent->id,
                'email'                 => 'required|email|max:255|unique:astrogents,email,'.$astrogent->id,
                'mobile'                 => 'required|max:10|unique:astrogents,mobile,'.$astrogent->id,
                'gender'                 => 'required',
                'address'               => 'required',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with eShagi.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with eShagi.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with eShagi.',
                'gender.required' => 'What is your gender?',
                'address.required' => 'What is your physical address?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $astrogent->title = $request['title'];
        $astrogent->name = $name;
        $astrogent->first_name = strip_tags($request['first_name']);
        $astrogent->last_name = strip_tags($request['last_name']);
        $astrogent->natid = strtoupper($request['natid']);
        $astrogent->email = $request['email'];
        $astrogent->mobile = $request['mobile'];
        $astrogent->gender = $request['gender'];
        $astrogent->bank_acc_name = $request['bank_acc_name'];
        $astrogent->bank = $request['bank'];
        $astrogent->branch = $request['branch'];
        $astrogent->branch_code = $request['branch_code'];
        $astrogent->accountNumber =  $request['accountNumber'];
        $astrogent->address = $request['address'];
        $astrogent->locale = $request['locale_id'];

        $astrogent->save();

        if ($astrogent->save()) {
            $user->name = $name;
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->natid = strtoupper($request['natid']);
            $user->mobile = $request['mobile'];
            $user->locale = $request['locale_id'];
            $user->save();
            ActivityLogger::activity(auth()->user()->name . " has modified astrogent " . $astrogent->natid . " with agent ID: " . $astrogent->id);
        }

        return redirect()->back()->with('success', 'Astrogent details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Astrogent  $astrogent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Astrogent $astrogent)
    {
        $user = User::where('natid', $astrogent->natid)->first();
        $user->delete();
        if ($user->delete()){
            $astrogent->delete();
        }
        return redirect('astrogents')->with('success', 'Astrogent deleted successfully.');
    }

    public function astrogentRegForm(){
        $banks = DB::table('banks')
            ->groupBy('bank')
            ->get();

        return view('astrogents.astrogent-register', compact('banks'));
    }

    public function postAstrogentInfo(Request $request){
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

        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required|max:5',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:users',
                'email'                 => 'required|email|max:255|unique:users',
                'mobile'                 => 'required|max:10|unique:users',
                'gender'                 => 'required',
                'address'               => 'required',
                'password'              => 'required|min:8|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with eShagi.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with eShagi.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with eShagi.',
                'gender.required' => 'What is your gender?',
                'address.required' => 'What is your physical address?',
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

        $role = Role::where('slug', '=', 'astrogent')->first();

        $astrogent = Astrogent::create([
            'title'              => $request['title'],
            'name'        => $name,
            'first_name'        => strip_tags($request['first_name']),
            'last_name'         => strip_tags($request['last_name']),
            'gender'             => $request['gender'],
            'email'             => $request['email'],
            'natid'             => strtoupper($request['natid']),
            'mobile'             => $request['mobile'],
            'bank_acc_name'             => $request['bank_acc_name'],
            'bank'             => $request['bank'],
            'branch'             => $request['branch'],
            'branch_code'             => $request['branch_code'],
            'accountNumber'             => $request['acc_number'],
            'address'             => $request['address'],
            'activated'             => false,
            'locale'             => 1,
        ]);

        $astrogent->save();

        if ($astrogent->save()) {
            $user = User::create([
                'name'              => $name,
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'email'             => $request['email'],
                'natid'             => strtoupper($request['natid']),
                'mobile'             => $request['mobile'],
                'utype'             => 'Astrogent',
                'locale'             => 1,
                'password'          => Hash::make($request['password']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress,
                'activated'         => false,
            ]);

            $user->attachRole($role);
            //$this->initiateEmailActivation($user);

            $profile = new Profile();
            $user->profile()->save($profile);

            Auth::login($user);
        }

        return redirect('astrogent-kyc');
    }

    public function uploadAstrogentKyc() {
        $agent = Astrogent::where('natid', auth()->user()->natid)->first();

        return view('astrogents.astrogent-upload-kyc', compact('agent'));
    }

    function uploadAstrogentNationalID(Request $request){
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
            return redirect('astrogent-kyc')->withErrors($validator)->withInput();
        }

        if($request->hasFile('natid')) {

            if ($request->file('natid')->isValid()) {

                $nationalId = $request->file('natid');
                $filename = auth()->user()->natid . '.' . $nationalId->getClientOriginalExtension();
                Storage::disk('public')->put('agentids/' . $filename, File::get($nationalId));

                $user = Astrogent::where('natid', auth()->user()->natid)->first();

                $user->natidPic = $filename;
                $user->natidUpload = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return redirect('astrogent-kyc')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('astrogent-kyc')->with('error','No file was detected here.');
        }

        return redirect('astrogent-kyc')->with('success','National ID uploaded successfully.');
    }

    function uploadAstrogentSignature(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'signature'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'signature.max'                 => 'Signature Photo file should not be greater than 4MB.',
                'signature.mimes'               => 'Signature Photo file should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return redirect('astrogent-kyc')->withErrors($validator)->withInput();
        }

        if($request->hasFile('signature')) {

            if ($request->file('signature')->isValid()) {

                $passPhoto = $request->file('signature');
                $filename = auth()->user()->natid . '.' . $passPhoto->getClientOriginalExtension();
                Storage::disk('public')->put('agentssign/' . $filename, File::get($passPhoto));

                $user = Astrogent::where('natid', auth()->user()->natid)->first();

                $user->signaturePic = $filename;
                $user->signUpload = true;
                $user->updated_at = now();

                $user->save();
            } else {
                return redirect('astrogent-kyc')->with('error','Invalid image supplied.');
            }

        } else {
            return redirect('astrogent-kyc')->with('error','No file was detected here.');
        }

        return redirect('astrogent-kyc')->with('success','Signature uploaded successfully.');

    }

    public function manageAstrogents(){
        $astrogents = Astrogent::where('activated','=', false)->get();
        return view('astrogents.review-astrogents', compact('astrogents'));
    }

    public function reviewAgentInfo($id){
        $agent = Astrogent::findOrFail($id);
        $bank = Bank::where('id',$agent->bank)->first();

        return view('astrogents.review-astrogent-kyc', compact('agent','bank'));
    }

    public function approveAgentInfo($id){
        $settings = Masetting::find(1)->first();
        $agent = Astrogent::findOrFail($id);
        $user = User::where('natid', $agent->natid)->first();

        $agent->activated = true;
        $agent->reviewer = auth()->user()->name;
        $agent->save();

        if ($agent->save()) {
            $notify = Http::post($settings->bulksmsweb_baseurl."to=+263" . $agent->mobile . "&msg=Your Astrogent account has been activated. It's time to make money with eShagi. Regards, eShagi.")
                ->body();

            $json = json_decode($notify, true);
            $status = $json['data'][0]['status'];

            $user->activated = true;
            $user->save();
        }

        return redirect()->back()->with('success', 'Astrogent has been activated successfully and has been notified via SMS.');
    }
}
