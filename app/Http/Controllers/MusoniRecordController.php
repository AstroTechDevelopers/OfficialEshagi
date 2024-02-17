<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\MusoniRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MusoniRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = Validator::make($request->all(),
            [
                'business_type'       => 'required',
                'business_start'        => 'required',
                'bus_address'     => 'required',
                'bus_city'     => 'required',
                'bus_country'     => 'required',
                'job_title'     => 'required',
                'kin_address'     => 'required',
                'kin_city'     => 'required',
                'kin_relationship'     => 'required',
                'natid'     => 'required|unique:musoni_records',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('natid', $request->input('natid'))->first();

        if (is_null($user)){
            return redirect()->back()->with('error','Failed to find user record');
        }

        $musRecord = MusoniRecord::create([
            'user_id'             => $user->id,
            'natid'             => $request->input('natid'),
            'status'             => true,
            'reviewer'             => auth()->user()->name,
            'authorizer'             => auth()->user()->name,
            'business_type'             => $request->input('business_type'),
            'business_start'             => $request->input('business_start'),
            'bus_address'             => $request->input('bus_address'),
            'bus_city'             => $request->input('bus_city'),
            'bus_country'             => $request->input('bus_country'),
            'job_title'             => $request->input('job_title'),
            'kin_address'             => $request->input('kin_address'),
            'kin_city'             => $request->input('kin_city'),
            'kin_relationship'             => $request->input('kin_relationship'),
            'notes'             => $request->input('notes'),
        ]);

        $musRecord->save();
        if ($musRecord->save()){

            DB::table("loans")
                ->where("id", $request->input('loanid'))
                ->update(['interestRate' => getOldMutualInterestRate(), 'updated_at' => now()]);

            $kyc = Kyc::where('natid',$request->input('natid'))->first();
            $kyc->cbz_status = true;
            $kyc->cbz_evaluator = auth()->user()->name;
            $kyc->save();
            //return redirect('get-musoni-docs/'.$request->input('clientid').'/'.$request->input('loanid'));
            return redirect('send-client-musoni/'.$request->input('clientid').'/'.$request->input('loanid'));
        } else {
            return redirect()->back()->with('error','Error creating record');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MusoniRecord  $musoniRecord
     * @return \Illuminate\Http\Response
     */
    public function show(MusoniRecord $musoniRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MusoniRecord  $musoniRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(MusoniRecord $musoniRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MusoniRecord  $musoniRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MusoniRecord $musoniRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MusoniRecord  $musoniRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(MusoniRecord $musoniRecord)
    {
        //
    }
}
