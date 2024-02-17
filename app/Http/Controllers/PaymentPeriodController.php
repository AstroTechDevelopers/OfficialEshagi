<?php

namespace App\Http\Controllers;

use App\Models\PaymentPeriod;
use Illuminate\Http\Request;
use Validator;

class PaymentPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periods = PaymentPeriod::all();
        return view('pperiod.periods', compact('periods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pperiod.add-period');
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
                'period'       => 'required',
            ],
            [
                'period.required'        => 'What is the payment period?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $period = PaymentPeriod::create([
            'period'             => $request->input('period'),
        ]);

        $period->save();

        return redirect('periods')->with('success', 'Payment period added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentPeriod  $paymentPeriod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentPeriod $paymentPeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentPeriod  $paymentPeriod
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentPeriod = PaymentPeriod::where('id', $id)->first();
        return view('pperiod.edit-period', compact('paymentPeriod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentPeriod  $paymentPeriod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $paymentPeriod = PaymentPeriod::where('id', $id)->first();
        $validator = Validator::make($request->all(),
            [
                'period'       => 'required',
            ],
            [
                'period.required'        => 'What is the payment period?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $paymentPeriod->period = $request->input('period');

        $paymentPeriod->save();

        return redirect('periods')->with('success', 'Payment period updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentPeriod  $paymentPeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentPeriod = PaymentPeriod::where('id', $id)->first();
        $paymentPeriod->delete();

        return redirect('periods')->with('success', 'Payment period deleted successfully.');
    }
}
