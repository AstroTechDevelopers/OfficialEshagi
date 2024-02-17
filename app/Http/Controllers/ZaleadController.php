<?php

namespace App\Http\Controllers;

use App\Imports\ZaleadImport;
use App\Models\Bank;
use App\Models\Profile;
use App\Models\User;
use App\Models\Zalead;
use App\Models\Zambian;
use App\Traits\CaptureIpTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ZaleadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Zalead::select('id','nrc', 'business','first_name', 'last_name', 'mobile');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = " <form class='btn btn-sm' method='POST' action='zaleads/". $row['id']."'>
                             <input type='hidden' name='_token' value='".csrf_token()."'>
                             <input name='_method' type='hidden' value='DELETE'>
                             <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#confirmDelete'
                                data-title='Delete Lead' data-message='Are you sure you want to delete this lead ?'>
                                <i class='mdi mdi-trash-can-outline' aria-hidden='true'></i>
                                </button>

                             </form>

                             <a class='btn btn-sm btn-success' href='lookup-zalead/". $row['id']."' type='button' data-toggle='tooltip' data-placement='top' title='Lookup on LoanDisk'>
                                <i class='mdi mdi-selection-search' aria-hidden='true'></i>
                            </a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('zaleads.zambia_leads');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('zaleads.create-lead');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'nrc'     => 'required|unique:zaleads',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lead = Zalead::create([
            'nrc'             => $request->input('nrc'),
            'business'             => $request->input('business'),
            'first_name'             => $request->input('first_name'),
            'last_name'             => $request->input('last_name'),
            'mobile'             => $request->input('mobile'),
            'notes'             => $request->input('notes'),
        ]);

        $lead->save();

        return redirect()->back()->with('success', 'Sales lead added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zalead  $zalead
     * @return \Illuminate\Http\Response
     */
    public function show(Zalead $zalead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zalead  $zalead
     * @return \Illuminate\Http\Response
     */
    public function edit(Zalead $zalead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zalead  $zalead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zalead $zalead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zalead  $zalead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zalead $zalead)
    {
        $zalead->delete();
        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }

    public function importZambiaLeads(){
        return view('zaleads.import-zaleads');
    }

    public function importZambiaLeadsFromExcel(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'leads_excel'  => 'required|mimetypes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt',
            ],
            [
                'leads_excel.required'  => 'No import file was found here.',
                'leads_excel.mimes'     => 'Import file should of the format: csv,xlsx.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Excel::import(new ZaleadImport, request()->file('leads_excel'));

        return redirect()->back()->with('success', 'Leads imported successfully.');
    }

    public function lookupForExistingClient($id){
        $client = Zalead::where('id','=',$id)->firstOrFail();

        $zambian = Zambian::where('nrc', $client->nrc)->first();

        if (!is_null($zambian)){
            return redirect()->back()->with('error','Client already exists in eShagi!');
        }

         $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->get('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower/borrower_unique_number/'.$client->nrc)
                ->body();

            $resp=json_decode($details, TRUE);

            if (is_null($resp)){
                return redirect()->back()->with('error', 'I didn\'t find client record on LoanDisk.');
            }

            if (isset($resp['response']['Errors'])){
                return redirect()->back()->with('errors', collect($resp['response']['Errors']));
            }

            if (isset($resp['error'])){
                return redirect()->back()->with('error', $resp['error']['message']);
            }

            $response = $resp['response']['Results'][0][0];
            return view('zaleads.lead-found', compact('response','id'));

    }

    public function uploadImportZambianLead(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'borrower_id'                  => 'required',
                'borrower_country'            => 'required',
                'borrower_firstname'             => 'required',
                'borrower_lastname'             => 'required',
                'borrower_gender'             => 'required',
                'borrower_dob'             => 'required',
                'borrower_unique_number'   => 'required|max:15|unique:zambians,nrc,',
                'borrower_mobile'                 => 'required|max:10|unique:zambians,mobile,',
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
            $client = Zalead::where('id','=',$request->input('lead_id'))->firstOrFail();
            $provinces = DB::table("provinces")
                ->where("country", auth()->user()->locale)
                ->select("id", "country", "province")
                ->orderBy("province", 'asc')
                ->get();

            $banks = DB::table('banks')
                ->where('locale_id','=',2)
                ->groupBy('bank')
                ->get();

            $data = [
                'request' => $request->all(),
                'client' => $client,
                'provinces' => $provinces,
                'banks' => $banks,
                'error' => 'Some information is missing from the client.'
            ];
            //dd($data);
            return view('zaleads.update-lead-found', compact('data'));
            //return redirect('/zalead-missing/'.$request->input('lead_id'))->with($data);
        }
        $name = generateUsername($_POST['borrower_firstname'], $_POST['borrower_lastname']);

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;
        $filename = str_replace("/","_",$request->input('borrower_unique_number')) . '.jpeg';

        $client = Zambian::create([
            'creator'              => 'SYS_'.auth()->user()->name,
            'ld_borrower_id'              => $request['borrower_id'],
            'title'              => $request['borrower_title'],
            'first_name'        => strip_tags($request['borrower_firstname']),
            'middle_name'        => strip_tags($request['middle_name']),
            'last_name'         => strip_tags($request['borrower_lastname']),
            'nrc'             => strtoupper($request['borrower_unique_number']),
            'email'             => $request['borrower_email'] ?? $name.'@zambia.co.zm',
            'mobile'             => $request['borrower_mobile'],
            'dob'             => $request['borrower_dob'],
            'gender'             => $request['borrower_gender'],
            'business_employer'             => $request['borrower_business_name'],
            'address'             => $request['borrower_address'],
            'city'             => $request['borrower_city'],
            'province'             => $request['borrower_province'],
            'zip_code'             => $request['borrower_zipcode'],
            'landline'             => $request['borrower_landline'],
            'work_status'             => $request['borrower_working_status'],
            'credit_score'             => $request['borrower_credit_score'],
            'pass_photo'             => $filename,
            'description'             => $request['borrower_description'],
            'files'             => $filename,
            'loan_officer_access'             => $request['loan_officer_access'],
            'institution'             => $request['custom_field_11543'],
            'ec_number'             => $request['custom_field_11302'],
            'department'             => $request['custom_field_11303'],
            'kin_name'             => $request['custom_field_11085'],
            'kin_relationship'             => $request['custom_field_11083'],
            'kin_address'             => $request['custom_field_11082'],
            'kin_number'             => $request['custom_field_11084'],
            'bank_name'             => $request['custom_field_11789'],
            'bank_account'             => $request['custom_field_11788'],
            'branch'             => $request['custom_field_11790'],
        ]);
        $client->save();

        if ($client->save()) {
            $user = User::create([
                'name'              => $name,
                'first_name'        => $request['borrower_firstname'],
                'last_name'         => $request['borrower_lastname'],
                'email'             => $request['email'] ?? $name.'@zambia.co.zm',
                'natid'             => strtoupper($request['borrower_unique_number']),
                'mobile'             => $request['borrower_mobile'],
                'utype'             => 'Client',
                'locale'             => auth()->user()->locale,
                'password'          => Hash::make($request['borrower_unique_number']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress,
                'activated'         => $activated,
            ]);

            $user->attachRole($role);

            $profile = new Profile();
            $user->profile()->save($profile);

            $zalead = Zalead::where('nrc',$request['borrower_unique_number'])->first();
            $zalead->delete();
        }

        return redirect('zambians')->with('success','Client Account created');

    }

    public function updateZambianLead(Request $request){
        $client = Zalead::where('id','=',$id)->firstOrFail();
        $provinces = DB::table("provinces")
            ->where("country", auth()->user()->locale)
            ->select("id", "country", "province")
            ->orderBy("province", 'asc')
            ->get();

        $banks = DB::table('banks')
            ->where('locale_id','=',2)
            ->groupBy('bank')
            ->get();
        return view('zaleads.update-lead-found', compact('client','provinces','banks'));
    }
}
