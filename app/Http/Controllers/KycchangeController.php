<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Kyc;
use App\Models\Kycchange;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KycchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kycchanges = DB::table('kycchanges as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.id as cid','c.first_name','c.last_name','c.natid','k.mobile_no','k.gross','k.net','k.id','k.status','k.reviewer','k.created_at')
            ->where('k.deleted_at', '=', null)
            ->get();

        return view('kycchanges.kycchanges', compact('kycchanges'));
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
     * @param  \App\Models\Kycchange  $kycchange
     * @return \Illuminate\Http\Response
     */
    public function show(Kycchange $kycchange)
    {
        $client = Client::where('natid', $kycchange->natid)->first();
        $kyc = Kyc::where('natid', $kycchange->natid)->first();
        return view('kycchanges.review-kyc-change', compact('kycchange', 'client', 'kyc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kycchange  $kycchange
     * @return \Illuminate\Http\Response
     */
    public function edit(Kycchange $kycchange)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kycchange  $kycchange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kycchange $kycchange)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kycchange  $kycchange
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kycchange $kycchange)
    {
        $kycchange->delete();
        return redirect('pending-kycchanges')->with('success', 'Change Request deleted successfully.');
    }

    public function getKycChangeRequests() {
        $kycchanges = DB::table('kycchanges as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.id as cid','c.first_name','c.last_name','c.natid','k.mobile_no','k.gross','k.net','k.id','k.status','k.reviewer','k.created_at')
            ->where('k.status','=', false)
            ->where('k.deleted_at', '=', null)
            ->get();

        return view('kycchanges.pending-changes', compact('kycchanges'));
    }

    public function getKycApprovedChangeRequests() {
        $kycchanges = DB::table('kycchanges as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.id as cid','c.first_name','c.last_name','c.natid','k.mobile_no','k.gross','k.net','k.id','k.status','k.reviewer','k.created_at')
            ->where('k.status','=', true)
            ->where('k.deleted_at', '=', null)
            ->get();

        return view('kycchanges.approved-changes', compact('kycchanges'));
    }

    public function approveKycChange($id) {
        $kycChange = Kycchange::findOrFail($id);
        $ipAddress = new CaptureIpTrait();
        $client = Client::where('natid', $kycChange->natid)->first();
        $user = User::where('natid', $client->natid)->first();

        if ($kycChange->mobile_no != null) {
            $user->mobile = $kycChange->mobile_no;
            $user->updated_ip_address = $ipAddress->getClientIp();

            $user->save();
        }

        if ($kycChange->mobile_no != null) {
            $client->mobile = $kycChange->mobile_no;
        }

        if ($kycChange->gross != null) {
            $client->gross = $kycChange->gross;
        }

        if ($kycChange->net != null) {
            $client->salary = $kycChange->net;
            $client->cred_limit = number_format(3.1 * $kycChange->net, 2, '.', '');
        }

        $client->save();

        $kycChange->status = true;
        $kycChange->reviewer = auth()->user()->name;
        $kycChange->save();

        return redirect()->back()->with('success', 'Change Request has been processed successfully.');

    }
}
