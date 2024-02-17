<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Creditlimit;
use App\Models\Masetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CreditlimitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ((auth()->user()->hasRole('root') || auth()->user()->hasRole('admin') )) {
            $limits = DB::table('creditlimits as c')
            ->join('clients as cl', 'cl.id','=','c.client_id')
            ->select('c.id','c.client_id','cl.natid','cl.first_name','cl.last_name','c.grossSalary','c.netSalary','c.creditlimit')
            ->get();
        } else {
            $limits = DB::table('creditlimits as c')
                ->join('clients as cl', 'cl.id','=','c.client_id')
                ->select('c.id','c.client_id','cl.natid','cl.first_name','cl.last_name','c.grossSalary','c.netSalary','c.creditlimit')
                ->where('cl.locale_id','=', auth()->user()->locale)
                ->get();
        }

        return view('creditlimits.limits', compact('limits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('creditlimits.add-limit', compact('clients'));
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
                'client_id'       => 'required|unique:creditlimits',
                'grossSalary'        => 'required',
                'netSalary'     => 'required',
                'creditlimit'     => 'required',
            ],
            [
                'client_id.required'          => 'Who is the client I am dealing with?',
                'grossSalary.required'          => 'Gross salary is required to add credit record.',
                'netSalary.required'       => 'Net salary is required to add credit record.',
                'creditlimit.required'       => 'Credit limit is required to add credit record.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $limit = Creditlimit::create([
            'client_id'             => $request->input('client_id'),
            'grossSalary'             => $request->input('grossSalary'),
            'netSalary'             => $request->input('netSalary'),
            'creditlimit'             => $request->input('creditlimit'),
            'reason'             => 'New Client which did not have credit limit record.',
        ]);

        $limit->save();

        return redirect('limits')->with('success', 'Credit Limit added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Creditlimit  $creditlimit
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $creditlimit = Creditlimit::find($id);
        $client = Client::findOrFail($creditlimit->client_id);
        return view('creditlimits.show-credit-limit', compact('creditlimit','client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Creditlimit  $creditlimit
     * @return \Illuminate\Http\Response
     */
    public function edit(Creditlimit $creditlimit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Creditlimit  $creditlimit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->input('netSalary') > $request->input('grossSalary')) {
            return redirect()->back()->with('error', 'Sorry, Net salary cannot be greater than gross salary')->withInput();
        }

        $validator = Validator::make($request->all(),
            [
                'grossSalary'        => 'required',
                'netSalary'     => 'required',
                'reason'     => 'required',
            ],
            [
                'client_id.required'          => 'Who is the client I am dealing with?',
                'grossSalary.required'          => 'Gross salary is required to add credit record.',
                'netSalary.required'       => 'Net salary is required to add credit record.',
                'reason.required'       => 'We need to know the reason why the credit limit was adjusted.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $creditlimit = Creditlimit::find($id);
        $client = Client::findOrFail($creditlimit->client_id);
        $settings = Masetting::find(1)->first();

        $creditlimit->grossSalary = number_format($request->input('grossSalary'),2, '.', '');
        $creditlimit->netSalary = number_format($request->input('netSalary'),2, '.', '');
        $creditlimit->creditlimit = number_format($settings->creditRate*$request->input('netSalary'),2, '.', '');
        $creditlimit->reason = $request->input('reason');

        $creditlimit->save();

        if ($creditlimit->save()){
            $client->gross = number_format($request->input('grossSalary'),2, '.', '');
            $client->salary = number_format($request->input('netSalary'),2, '.', '');
            $client->cred_limit = number_format($settings->creditRate*$request->input('netSalary'),2, '.', '');
            $client->save();
        }

        return redirect()->back()->with('success', 'Credit limit updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Creditlimit  $creditlimit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Creditlimit $creditlimit)
    {
        //
    }

}
