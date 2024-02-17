<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Lead;
use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calls = DB::table('calls')
            ->select('id','lead_id', 'agent','client', 'mobile', 'isSale', 'created_at')
            ->where('deleted_at','=', NULL)
            ->cursor();

        return view('calls.calls', compact('calls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$calls = Call::where('client', $lead->natid)->where('deleted_at','=', NULL)->get();
        return view('calls.create-call');
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
                'lead_id'       => 'required',
                'agent'        => 'required',
                'client'     => 'required|regex:/^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$/',
                'mobile'     => 'required',
                'isSale'     => 'nullable',
                'setAppointment'     => 'nullable',
                'appointment'     => 'required_if:setAppointment,true',
                'notes'     => 'nullable',
            ],
            [
                'lead_id.required'        => 'What is the lead for this call?',
                'agent.required'          => 'Please make sure you\'re logged in..',
                'client.required'       => 'Please follow the correct process in recording the call',
                'client.regex'       => 'National ID number format is incorrect. Have you, confirmed and updated the client details?',
                'mobile.required'       => 'The mobile phone number is required to record the call.',
                'appointment.required_if'       => 'Since the client wants a follow up, we need to schedule a reminder for you.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->input('isSale')) {
            $sale = true;
        } else {
            $sale = false;
        }

        if ($request->input('setAppointment')) {
            $appointment = true;
            $time = $request->input('appointment');
        } else {
            $appointment = false;
            $time = NULL;
        }

        $call = Call::create([
            'lead_id'            => $request->input('lead_id'),
            'agent'              => $request->input('agent'),
            'client'             => $request->input('client'),
            'mobile'             => $request->input('mobile'),
            'isSale'             => $sale,
            'setAppointment'     => $appointment,
            'appointment'        => $time,
            'notes'              => $request->input('notes'),
        ]);

        $call->save();
        $lead = Lead::findOrFail($request->input('lead_id'));
        if ($call->save() AND $sale) {

            $ipAddress = new CaptureIpTrait();
            $profile = new Profile();

            $user = User::create([
                'name'             => $lead->name,
                'first_name'       => $lead->first_name,
                'last_name'        => $lead->last_name,
                'email'            => $lead->email,
                'natid'            => strtoupper($lead->natid),
                'mobile'            => $lead->mobile,
                'utype'            => 'Client',
                'password'         => Hash::make('pass12345'),
                'token'            => str_random(64),
                'admin_ip_address' => $ipAddress->getClientIp(),
                'activated'        => 1,
                'locale'        => $lead->locale,
            ]);

            $user->profile()->save($profile);
            $user->attachRole(10);
            $user->save();

            $lead->isSale = true;
            $lead->isContacted = true;
            $lead->completedOn = now();
            $lead->save();
            $data = array(
                'lead' => $lead,
                'user' => $user,
                'success' => 'Let\'s continue with the registration process.'
            );

            return redirect('quickly-continue-client/'.$lead->natid)->with($data);
        }

        $lead->isContacted = true;
        $lead->save();

        return redirect('my-calls')->with('success', 'Call logged successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call)
    {
        if ($call->lead_id == null){
            $calls = Call::where('mobile', $call->mobile)->where('deleted_at','=', NULL)->get();

            return view('calls.view-ocall', compact('call', 'calls'));

        }
        $lead = Lead::findOrFail($call->lead_id);
        if (is_null($lead)) {
            return redirect()->back()->with('error','Couldn\'t find the lead for the call associated with the call');
        }

        $calls = Call::where('client', $lead->natid)->where('deleted_at','=', NULL)->get();

        return view('calls.view-call', compact('call', 'calls', 'lead'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Call $call)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call)
    {
        $call->delete();
        return redirect()->back()->with('success','Call log deleted successfully.');
    }

    public function makeCall($id){
        $lead = Lead::findOrFail($id);
        if (is_null($lead)) {
            return redirect()->back()->with('error','For you to make a call you have to open from a lead.');
        }

        $calls = Call::where('client', $lead->natid)->where('deleted_at','=', NULL)->get();

        return view('calls.make-call', compact('lead', 'calls'));
    }

    public function getMyCalls(){
        $calls = DB::table('calls')
            ->select('id','lead_id', 'agent','client', 'mobile', 'isSale', 'created_at')
            ->where('agent','=',auth()->user()->name)
            ->where('deleted_at','=', NULL)
            ->cursor();

        return view('calls.my-calls', compact('calls'));
    }

    public function recordOutgoingCall(Request $request){
        $validator = Validator::make($request->all(),
            [
                'agent'        => 'required',
                //'client'     => 'required|regex:/^[0-9]{2}-[0-9]{6,7}-[A-Z]-[0-9]{2}$/',
                'client'     => 'required',
                'mobile'     => 'required',
                'isSale'     => 'nullable',
                'setAppointment'     => 'nullable',
                'appointment'     => 'required_if:setAppointment,true',
                'notes'     => 'nullable',
            ],
            [
                'agent.required'          => 'Please make sure you\'re logged in..',
                'client.required'       => 'Please enter ID Number as is on the National ID or type Nil',
                'mobile.required'       => 'The mobile phone number is required to record the call.',
                'appointment.required_if'       => 'Since the client wants a follow up, we need to schedule a reminder for you.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->input('isSale')) {
            $sale = true;
        } else {
            $sale = false;
        }

        if ($request->input('setAppointment')) {
            $appointment = true;
            $time = $request->input('appointment');
        } else {
            $appointment = false;
            $time = NULL;
        }

        $call = Call::create([
            'agent'              => $request->input('agent'),
            'client'             => $request->input('client'),
            'mobile'             => $request->input('mobile'),
            'isSale'             => $sale,
            'setAppointment'     => $appointment,
            'appointment'        => $time,
            'notes'              => $request->input('notes'),
        ]);

        $call->save();

        return redirect('my-calls')->with('success', 'Call logged successfully.');
    }
}
