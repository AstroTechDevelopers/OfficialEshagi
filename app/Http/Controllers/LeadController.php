<?php

namespace App\Http\Controllers;

use App\Imports\SalesLeadsImport;
use App\Imports\SalesLeadsUsersImport;
use App\Models\Call;
use App\Models\Lead;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$leads = Lead::select('id','name', 'natid','mobile', 'agent', 'isContacted')->get();
        /*$leads = DB::table('leads')
            ->select('id','name', 'natid','mobile', 'agent', 'isContacted')
            ->where('deleted_at','=', null)
            ->cursor();*/
        //foreach ($cursor as $e) { }
        /*DB::table('leads')
            ->select('id','name', 'natid','mobile', 'agent', 'isContacted')
            ->chunk(10000, function ($leads) {
                foreach ($leads as $user) {
                    //
                }
        });*/
        if ($request->ajax()) {
            $data = Lead::select('id','name', 'natid','mobile', 'agent', 'isContacted');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = " <form class='btn btn-sm btn-danger' method='POST' action='leads/". $row['id']."'>
                             <input type='hidden' name='_token' value='".csrf_token()."'>
                             <input name='_method' type='hidden' value='DELETE'>
                             <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#confirmDelete'
                                data-title='Delete Lead' data-message='Are you sure you want to delete this lead ?'>
                                <i class='mdi mdi-trash-can-outline' aria-hidden='true'></i>
                                </button>

                             </form>
                            <a class='btn btn-sm btn-success' href='leads/". $row['id']."' >
                                <i class='mdi mdi-eye-outline' aria-hidden='true'></i>
                            </a>

                            <a class='btn btn-sm btn-info' href='leads/".$row['id']."/edit' >
                                <i class='mdi mdi-account-edit-outline' aria-hidden='true'></i>
                            </a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('leads.leads');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $localels = Localel::all();
        return view('leads.add-lead', compact('localels'));
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
                'locale'       => 'required',
                'name'        => 'required|max:255|unique:leads',
                'first_name'     => 'required',
                'last_name'     => 'required',
                'email'     => 'required|email',
                'natid'     => 'required|regex:/^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$/|unique:leads',
                'mobile'     => 'required',
                'ecnumber'     => 'required',
                'address'     => 'required',
                'notes'     => 'nullable',
            ],
            [
                'locale.required'        => 'Where is the seal located?',
                'name.required'          => 'We need a system user name.',
                'name.unique'          => 'User name is already in the system.',
                'first_name.required'       => 'The first name is required to add lead.',
                'last_name.unique'       => 'The last name is required to add lead.',
                'email.required'       => 'Email address is required.',
                'email.email'       => 'Email address has to be a valid email.',
                'natid.required'       => 'The National ID is required to add lead.',
                'natid.unique'       => 'The National ID is already registered with eShagi as a lead.',
                'natid.regex'       => 'The National ID must be of the standard ID Format.',
                'mobile.required'       => 'Mobile number is required to add lead.',
                'ecnumber.required'       => 'EC Number is required to add lead.',
                'address.required'       => 'Physical address is required to add lead.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();

        if (auth()->user()->hasRole('loansofficer') OR auth()->user()->hasRole('salesadmin') OR auth()->user()->isAgent() OR  auth()->user()->isAstrogent()){
            $agent = auth()->user()->name;
            $assignedOn = Carbon::now();
        } else {
            $agent = null;
            $assignedOn = null;
        }

        $lead = Lead::create([
            'locale'             => $request->input('locale'),
            'name'             => $request->input('name'),
            'first_name'             => $request->input('first_name'),
            'last_name'             => $request->input('last_name'),
            'email'             => $request->input('email'),
            'natid'             => $request->input('natid'),
            'mobile'             => $request->input('mobile'),
            'password'         => Hash::make('pass12345'),
            'token'            => str_random(64),
            'ecnumber'             => $request->input('ecnumber'),
            'address'             => $request->input('address'),
            'signup_ip_address' => $ipAddress->getClientIp(),
            'notes'             => $request->input('notes'),
            'agent' => $agent,
            'assignedOn' => $assignedOn,
        ]);

        $lead->save();

        return redirect()->back()->with('success', 'Sales lead added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        $localels = Localel::all();
        $calls = Call::where('client', $lead->natid)->get();
        return view('leads.view-lead', compact('lead', 'localels', 'calls'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead)
    {
        $localels = Localel::all();
        return view('leads.edit-lead', compact('lead', 'localels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        $validator = Validator::make($request->all(),
            [
                'name'        => 'required|max:255|unique:leads,name,'.$lead->id,
                'first_name'     => 'required',
                'last_name'     => 'required',
                'email'     => 'required|email',
                'natid'     => 'required|regex:/^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$/|unique:leads,natid,'.$lead->id,
                'mobile'     => 'required',
                'ecnumber'     => 'required',
                'address'     => 'required',
                'notes'     => 'nullable',
            ],
            [
                'name.required'          => 'We need a system user name.',
                'name.unique'          => 'User name is already in the system.',
                'first_name.required'       => 'The first name is required to add lead.',
                'last_name.unique'       => 'The last name is required to add lead.',
                'email.required'       => 'Email address is required.',
                'email.email'       => 'Email address has to be a valid email.',
                'natid.required'       => 'The National ID is required to add lead.',
                'natid.unique'       => 'The National ID is already registered with eShagi as a lead.',
                'natid.regex'       => 'The National ID must be of the standard ID Format.',
                'mobile.required'       => 'Mobile number is required to add lead.',
                'ecnumber.required'       => 'EC Number is required to add lead.',
                'address.required'       => 'Physical address is required to add lead.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $lead->name = $request->input('name');
        $lead->first_name = $request->input('first_name');
        $lead->last_name = $request->input('last_name');
        $lead->email = $request->input('email');
        $lead->natid = $request->input('natid');
        $lead->mobile = $request->input('mobile');
        $lead->ecnumber = $request->input('ecnumber');
        $lead->address = $request->input('address');
        $lead->notes = $request->input('notes');
        if ($lead->isContacted){
            $lead->isContacted = true;
        }

        $lead->save();

        return redirect()->back()->with('success', 'Lead info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->back()->with('success', 'Lead info deleted successfully.');
    }

    public function manageLeads(Request $request){
        /*$leads = DB::table('leads')
            ->select('id','name', 'natid','mobile', 'agent', 'isContacted')
            ->where('isSale',false)
            ->where('agent', '!=', NULL)
            ->where('deleted_at','=', null)
            ->cursor();*/
        if ($request->ajax()) {
            $data = Lead::select('id','name', 'natid','mobile', 'agent', 'isContacted')
                ->where('isSale',false)
                ->where('agent', '!=', NULL);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = " <form class='btn btn-sm btn-danger' method='POST' action='leads/". $row['id']."'>
                             <input type='hidden' name='_token' value='".csrf_token()."'>
                             <input name='_method' type='hidden' value='DELETE'>
                             <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#confirmDelete'
                                data-title='Delete Lead' data-message='Are you sure you want to delete this lead ?'>
                                <i class='mdi mdi-trash-can-outline' aria-hidden='true'></i>
                                </button>

                             </form>
                            <a class='btn btn-sm btn-success' href='leads/". $row['id']."' >
                                <i class='mdi mdi-eye-outline' aria-hidden='true'></i>
                            </a>

                            <a class='btn btn-sm btn-info' href='leads/".$row['id']."/edit' >
                                <i class='mdi mdi-account-edit-outline' aria-hidden='true'></i>
                            </a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('leads.manage-leads');
    }

    public function uploadLeadsForm(){
        return view('leads.upload-leads');
    }

    public function importLeadsFromExcel(Request $request){
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

        Excel::import(new SalesLeadsImport, request()->file('leads_excel'));
        //Excel::import(new SalesLeadsUsersImport(), request()->file('leads_excel')); **** Not yet necessary to import directly into users table as they have not accepted the loan.

        return redirect()->back()->with('success', 'Leads imported successfully.');
    }

    public function allocateLeads(){
        $availableLeads = Lead::where('agent',null)->count();
        return view('leads.distribute-leads', compact('availableLeads'));
    }

    public function nowSharingLeads(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'share'  => 'required',
                'available'  => 'required',
            ],
            [
                'share.required'  => 'How many leads should I assign to an agent?',
                'available.required'     => 'I was supposed to get the available leads from you.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('share')>$request->input('available')){
            return redirect()->back()->with('error','We cannot share leads which are greater than the available ones.');
        }

        $agents = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('r.role_id','=',6)
            //->orWhere('r.role_id','=',12)
            //->orWhere('r.role_id','=',14)
            ->get();

        foreach ($agents as $agent) {
            $leadsShare = DB::table('leads')
                ->where('agent','=', null)
                ->take($request->input('share') )
                ->cursor();

            foreach ($leadsShare as $lead) {
                DB::table('leads')
                    ->where('agent','=', null)
                    ->where('id','=', $lead->id)
                    ->update(['agent' => $agent->name, 'assignedOn'=>now()]);
            }
        }

        return redirect()->back()->with('success', 'I have allocated '.$request->input('share'). ' leads amongst the available agents successfully.');
    }

    public function myLeads(){
        $leads = DB::table('leads')
            ->select('id','name', 'natid','mobile', 'isSMSed', 'isContacted')
            ->where('deleted_at','=', null)
            ->where('isSale','=', false)
            ->where('agent','=', auth()->user()->name)
            ->cursor();

        return view('leads.my-leads', compact('leads'));
    }

    public function myConvertedLeads(){
        $leads = DB::table('leads')
            ->select('id','name', 'natid','mobile', 'isSMSed', 'isContacted','completedOn')
            ->where('deleted_at','=', null)
            ->where('isSale','=', true)
            ->where('agent','=', auth()->user()->name)
            ->cursor();

        return view('leads.my-converted-leads', compact('leads'));
    }

    public function notesOnLead(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $validator = Validator::make($request->all(),
            [
                'notes'     => 'required',
            ],
            [
                'notes.required'        => 'You will need to type something for the notes.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $lead->notes = $request->input('notes');
        $lead->isContacted = true;

        $lead->save();

        return redirect()->back()->with('success', 'Lead info updated successfully.');
    }

    public function convertedLeads(){
        $leads = DB::table('leads')
            ->select('id','name', 'first_name', 'last_name', 'natid','mobile', 'agent', 'isContacted')
            ->where('isContacted','=', true)
            ->where('isSale','=', true)
            ->where('agent','=', auth()->user()->name)
            ->where('deleted_at','=', null)
            ->cursor();

        return view('leads.converted-leads', compact('leads'));
    }

    public function bulkSMSLeads(){
        $availableLeads = Lead::where('agent',null)->where('isContacted',false)->count();
        return view('leads.sms-leads',compact('availableLeads'));
    }

    public function nowSMSingLeads(Request $request){
        $settings = Masetting::find(1)->first();

        $validator = Validator::make(
            $request->all(),
            [
                'count'  => 'required',
                'available'  => 'required',
            ],
            [
                'count.required'  => 'How many leads should I assign to an agent?',
                'available.required'     => 'I was supposed to get the available leads from you.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('count')>$request->input('available')){
            return redirect()->back()->with('error','I cannot SMS leads which are greater than the available ones.');
        }

        $leads = Lead::where('agent',null)
            ->where('isContacted',false)
            ->where('isSMSed',false)
            ->where('mobile','!=','')
            ->take($request->input('count') )
            ->cursor();

        foreach ($leads as $lead) {
            $textLead = Http::post($settings->bulksmsweb_baseurl."to=+263".$lead->mobile."&msg=Good day ".$lead->last_name.", You qualify for an eShagi account, to apply for loans online. If you want a loan reply yes to number.")
                ->body();

            $json = json_decode($textLead, true);
            $status = $json['data'][0]['status'];

            if ($status == 'OK') {
                DB::table('leads')
                    ->where('id','=', $lead->id)
                    ->update(['isContacted' => true,'isSMSed'=>true ,'notes' => 'SMS was sent to lead at: '.now(),'updated_at' => now()]);
            }
        }

        return redirect()->back()->with('success', 'I have sent text messages to '.$request->input('count'). ' leads successfully.');
    }
}
