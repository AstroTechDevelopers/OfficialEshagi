<?php

namespace App\Http\Controllers;

use App\Imports\SalesRepsImport;
use App\Imports\UsersSalesRepImport;
use App\Models\Partner;
use App\Models\Profile;
use App\Models\Representative;
use App\Models\MerchantBranches;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reps = DB::table('representatives as r')
        ->join('partners as p', 'p.id','=','r.partner_id')
        ->select('r.id','r.first_name','r.last_name','r.natid','r.mobile','r.email','p.partner_name')
        ->where('r.deleted_at','=',null)
        ->get();

        return view('reps.reps', compact('reps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partners = Partner::all();
		$partner = Partner::where('regNumber', auth()->user()->natid)->first();
		$branches = MerchantBranches::where('partner_id', $partner->id)->get();
        return view('reps.add-sales-rep', compact('partners', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

        $ipAddress = new CaptureIpTrait();
        $role = Role::where('slug', '=', 'representative')->first();

        $validator = Validator::make($request->all(),
            [
                'partner_id'       => 'required',
                'first_name'        => 'required',
                'last_name'        => 'required',
                'email'     => 'required|unique:representatives|email',
                'natid'     => 'required|unique:representatives',
                'mobile'     => 'required|unique:representatives',
                'branch'     => 'nullable',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
            ],
            [
                'partner_id.required'        => 'What company is being represented by the rep?',
                'first_name.required'          => 'What is the first name of the representative?',
                'last_name.required'          => 'What is the surname of the representative?',
                'email.required'       => 'Representative email address is required.',
                'email.unique'       => 'Representative email address is already within the system.',
                'email.email'       => 'Representative email must be of a valid format.',
                'natid.required'       => 'Representative national ID number is required.',
                'natid.unique'       => 'Representative national ID has to be unique in the system.',
                'mobile.required'       => 'Representative telephone number is required.',
                'mobile.unique'       => 'Representative telephone number is has to be unique in the system.',
                'password.required'             => trans('auth.passwordRequired'),
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $rep = Representative::create([
            'creator'             => auth()->user()->name,
            'partner_id'             => $request->input('partner_id'),
            'first_name'             => $request->input('first_name'),
            'last_name'             => $request->input('last_name'),
            'email'             => $request->input('email'),
            'natid'             => $request->input('natid'),
            'mobile'             => $request->input('mobile'),
            'branch'             => $request->input('branch'),
        ]);

        $rep->save();

        if ($rep->save()) {
            $user = User::create([
                'name'              => 'rep_'.$name,
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'email'             => $request['email'],
                'natid'             => strtoupper($request['natid']),
                'mobile'             => $request['mobile'],
                'utype'             => 'Representative',
                'password'          => Hash::make($request['password']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress->getClientIp(),
                'activated'         => true,
                'status'         => true,
                'locale'            => auth()->user()->locale,
            ]);

            $user->attachRole($role);
            //$this->initiateEmailActivation($user);

            $profile = new Profile();
            $user->profile()->save($profile);

        }

        return redirect('my-representatives')->with('success', 'Representative added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Representative  $representative
     * @return \Illuminate\Http\Response
     */
    public function show(Representative $representative)
    {
        $partner = Partner::where('id',$representative->partner_id)->first();
        $user = User::where('natid', $partner->regNumber)->first();
		$branch = MerchantBranches::where('partner_id', $representative->partner_id)->first();
        return view('reps.show-sales-rep', compact('representative', 'partner', 'user', 'branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Representative  $representative
     * @return \Illuminate\Http\Response
     */
    public function edit(Representative $representative)
    {   $partners = Partner::all();
        $currentPartner = Partner::where('id',$representative->partner_id)->first();
		$branches = MerchantBranches::where('partner_id', $currentPartner->id)->get();
        return view('reps.edit-sales-rep', compact('representative', 'partners','currentPartner', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Representative  $representative
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Representative $representative)
    {
        $validator = Validator::make($request->all(),
            [
                'partner_id'       => 'required',
                'first_name'        => 'required',
                'last_name'        => 'required',
                'email'     => 'required|email|unique:representatives,email,'.$representative->id,
                'natid'     => 'required|unique:representatives,natid,'.$representative->id,
                'mobile'     => 'required|unique:representatives,mobile,'.$representative->id,
                'branch'     => 'nullable',
                'password'              => 'nullable|min:6|max:30|confirmed',
                'password_confirmation' => 'nullable|same:password',
            ],
            [
                'partner_id.required'        => 'What company is being represented by the rep?',
                'first_name.required'          => 'What is the first name of the representative?',
                'last_name.required'          => 'What is the surname of the representative?',
                'email.required'       => 'Representative email address is required.',
                'email.unique'       => 'Representative email address is already within the system.',
                'email.email'       => 'Representative email must be of a valid format.',
                'natid.required'       => 'Representative national ID number is required.',
                'natid.unique'       => 'Representative national ID has to be unique in the system.',
                'mobile.required'       => 'Representative telephone number is required.',
                'mobile.unique'       => 'Representative telephone number is has to be unique in the system.',
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
		
		$user = User::where('natid', $representative->natid)->first();
		
        if ($representative->first_name != $request->input('first_name') || $representative->last_name != $request->input('last_name')) {
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
        } else{
			$name = $user->name;
        }

        $representative->partner_id = $request->input('partner_id');
        $representative->first_name = $request->input('first_name');
        $representative->last_name = $request->input('last_name');
        $representative->email = $request->input('email');
        $representative->natid = $request->input('natid');
        $representative->mobile = $request->input('mobile');
        $representative->branch = $request->input('branch');

        $representative->save();

        if ($representative->save()) {

            $user->name = $name;
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->natid = strtoupper($request['natid']);
            $user->mobile = $request['mobile'];

            if ($request['password'] != null) {
                $user->password = Hash::make($request['password']);
            }
            $user->save();

        }

        return redirect()->back()->with('success','Representative info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Representative  $representative
     * @return \Illuminate\Http\Response
     */
    public function destroy(Representative $representative)
    {
        $representative->delete();
        if ($representative->delete()) {
            $user = User::where('natid', $representative->natid)->first();
            $user->delete();
        }

        return redirect('representatives')->with('success', 'Representative deleted successfully.');
    }

    public function mySalesRep(){
        $reps = DB::table('representatives as r')
            ->join('partners as p', 'p.id','=','r.partner_id')
            ->select('r.id','r.first_name','r.last_name','r.natid','r.mobile','r.email','p.partner_name')
            ->where('r.creator','=',auth()->user()->name)
            ->where('r.deleted_at','=',null)
            ->get();

        return view('reps.my-reps', compact('reps'));
    }

    public function uploadSalesRepresentatives(){
        $partners = Partner::all();
        return view('reps.bulk-import-reps', compact('partners'));
    }

    public function bulkImportSalesRep(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'reps_excel'  => 'required|mimes:csv,xlsx',
                'partner_id' => 'required',
            ],
            [
                'reps_excel.required'  => 'No import file was found here.',
                'reps_excel.mimes'     => 'Import file should of the format: csv,xlsx.',
                'partner_id.required'  => 'We need to know the partner who has these representatives.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $partner = Partner::findOrFail($request->input('partner_id'));

        Excel::import(new SalesRepsImport($partner), request()->file('reps_excel'));
        Excel::import(new UsersSalesRepImport(), request()->file('reps_excel'));

        return redirect()->back()->with('success', 'Sales Reps imported successfully.');
    }
}
