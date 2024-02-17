<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Kyc;
use App\Models\User;
use App\Models\Zwmb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ZwmbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zwmbs = DB::table('zwmbs as z')
            ->join('clients as c','z.natid','=','c.natid')
            ->select('c.first_name','c.last_name','c.email','c.mobile','z.id','z.natid','z.created_at')
            ->where('c.flag','=','ZWMB')
            ->where('c.deleted_at','=',null)
            ->get();

        return view('zwmb.zwmbs', compact('zwmbs'));
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
        $validator = Validator::make(
            $request->all(),
            [
                'account_type'                  => 'required',
                'user_id'            => 'required|unique:zwmbs',
                'natid'            => 'required|unique:zwmbs',
                'occupation'             => 'required',
                'employer_name'                 => 'required',
                'employer_contact_person'                 => 'required',
                'designation'                 => 'required',
                'nature_employer'                 => 'required',
                'kin_name'                 => 'required',
                'kin_relationship'        => 'required',
                'kin_address'        => 'required',
                'proof_res'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'proof_of_income'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('mobile_banking') =='on'){
            $mobileBanking = true;
        } else {
            $mobileBanking = false;
        }

        if ($request->input('internet_banking') =='on'){
            $internetBanking = true;
        } else {
            $internetBanking = false;
        }

        if ($request->input('sms_alerts') =='on'){
            $smsAlerts = true;
        } else {
            $smsAlerts = false;
        }

        if ($request->input('bank_card_local') =='on'){
            $bankCards = true;
        } else {
            $bankCards = false;
        }

        if($request->hasFile('proof_res') AND $request->hasFile('proof_of_income')) {

            if ($request->file('proof_res')->isValid() AND $request->file('proof_of_income')->isValid()) {

                $proof_res = $request->file('proof_res');
                $porFilename = $request->input('natid') . '.' . $proof_res->getClientOriginalExtension();
                Storage::disk('public')->put('client_por/' . $porFilename, File::get($proof_res));

                $proof_of_income = $request->file('proof_of_income');
                $poiFilename = $request->input('natid') . '.' . $proof_of_income->getClientOriginalExtension();
                Storage::disk('public')->put('client_poincome/' . $poiFilename, File::get($proof_of_income));

                $client = Zwmb::create([
                    'user_id'              => $request->input('user_id'),
                    'natid'              => $request->input('natid'),
                    'reviewer'              => auth()->user()->name,
                    'agent'              => auth()->user()->name,
                    'account_type'              => $request['account_type'],
                    'maiden_name'        => strip_tags($request['maiden_name']),
                    'passport_number'        => strip_tags($request['passport_number']),
                    'driver_licence'         => strip_tags($request['driver_licence']),
                    'mobile_banking_num'             => $request['mobile_banking_num'],
                    'race'             => $request['race'],
                    'occupation'             => $request['occupation'],
                    'employer_name'             => $request['employer_name'],
                    'employer_contact_person'             => $request['employer_contact_person'],
                    'designation'             => $request['designation'],
                    'nature_employer'             => $request['nature_employer'],
                    'kin_name'             => $request['kin_name'],
                    'kin_relationship'             => $request['kin_relationship'],
                    'kin_address'             => $request['kin_address'],
                    'mobile_banking'             => $mobileBanking,
                    'internet_banking'             => $internetBanking,
                    'sms_alerts'             => $smsAlerts,
                    'bank_card_local'             => $bankCards,
                    'proof_res'             => $porFilename,
                    'proof_of_res_stat'             => true,
                    'proof_of_income'             => $poiFilename,
                    'proof_of_income_stat'             => true,
                ]);
                $client->save();


            } else {
                return redirect()->back()->with('error', 'Invalid image supplied');
            }
        } else {
            return redirect()->back()->with('error', 'No file was detected here.');
        }

        return redirect('zwmbs')->with('success', 'Client onboarded.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zwmb  $zwmb
     * @return \Illuminate\Http\Response
     */
    public function show(Zwmb $zwmb)
    {
        $client = Client::where('natid', $zwmb->natid)->firstOrFail();
        $kyc = Kyc::where('natid', $zwmb->natid)->firstOrFail();
        return view('zwmb.zwmb-info', compact('zwmb','client','kyc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zwmb  $zwmb
     * @return \Illuminate\Http\Response
     */
    public function edit(Zwmb $zwmb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zwmb  $zwmb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zwmb $zwmb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zwmb  $zwmb
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zwmb $zwmb)
    {
        $user = User::where('natid',$zwmb->natid)->firstOrFail();
        $user->forceDelete();
        $client = Client::where('natid',$zwmb->natid)->firstOrFail();
        $client->forceDelete();
        $kyc = Kyc::where('natid',$zwmb->natid)->firstOrFail();
        $kyc->forceDelete();
        $zwmb->delete();
        return redirect('zwmbs')->with('success', 'Client deleted successfully.');
    }

    public function addZwmbKyc($id){
        $client = Client::findOrFail($id);
        $client->flag = 'ZWMB';
        $client->save();

        $kyc = Kyc::where('natid',$client->natid)->firstOrFail();
        $kyc->status = true;
        $kyc->reviewer = auth()->user()->name;
        $kyc->save();

        $bank = Bank::where('id', $kyc->bank)->first();

        $user = User::where('natid',$client->natid)->firstOrFail();

        return view('zwmb.add-new-zwmb', compact('client','kyc','bank','user'));

    }

    public function loadClientToVet($id){
        $zwmb = Zwmb::findOrFail($id);
        $client = Client::where('natid', $zwmb->natid)->firstOrFail();
        $kyc = Kyc::where('natid', $zwmb->natid)->firstOrFail();
        $user = User::where('natid',$client->natid)->firstOrFail();

        return view('zwmb.kyc-to-authorize', compact('zwmb','client','kyc','user'));

    }

    public function getReviewedKyc(){
        $zwmbs = DB::table('zwmbs as z')
            ->join('clients as c','z.natid','=','c.natid')
            ->select('c.first_name','c.last_name','z.reviewer','c.mobile','z.id','z.natid','z.created_at')
            ->where('c.flag','=','ZWMB')
            ->where('z.reviewer','!=',null)
            ->where('c.deleted_at','=',null)
            ->get();

        return view('zwmb.reviewed-zwmb', compact('zwmbs'));

    }

    public function authorizingZwmbClient(Request $request, $id){
        $validator = Validator::make(
            $request->all(),
            [
                'customer_number'           => 'required|unique:zwmbs',
                'account_number'            => 'required|unique:zwmbs',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $zwmb = Zwmb::findOrFail($id);
        $zwmb->customer_number = $request->input('customer_number');
        $zwmb->account_number = $request->input('account_number');
        $zwmb->checked_by = auth()->user()->name;
        $zwmb->authorized = auth()->user()->name;
        $zwmb->save();

        return redirect('zwmbs')->with('success','Client has been authorized successfully.');

    }
}
