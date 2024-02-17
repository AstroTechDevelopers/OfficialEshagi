<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use Illuminate\Http\Request;
use Validator;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charges = Charge::all();
        return view('charges.charges', compact('charges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('charges.add-charge');
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
                'arrangement'       => 'required',
                'application'        => 'required',
                'insurance'     => 'required',
                'tax'     => 'required',
            ],
            [
                'arrangement.required'        => 'This is the administration fee for the loan',
                'application.required'          => 'Application fee for the loan',
                'insurance.required'       => 'What is the insurance on the loan?',
                'tax.required'       => 'What is the payable tax on the loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $charge = Charge::create([
            'arrangement'             => $request->input('arrangement'),
            'application'             => $request->input('application'),
            'insurance'             => $request->input('insurance'),
            'tax'             => $request->input('tax'),
        ]);

        $charge->save();

        return redirect('charges')->with('success', 'Charge added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function show(Charge $charge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function edit(Charge $charge)
    {
        return view('charges.edit-charge', compact('charge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Charge $charge)
    {
        $validator = Validator::make($request->all(),
            [
                'arrangement'       => 'required',
                'application'        => 'required',
                'insurance'     => 'required',
                'tax'     => 'required',
            ],
            [
                'arrangement.required'        => 'This is the administration fee for the loan',
                'application.required'          => 'Application fee for the loan',
                'insurance.required'       => 'What is the insurance on the loan?',
                'tax.required'       => 'What is the payable tax on the loan?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $charge->arrangement = $request->input('arrangement');
        $charge->application = $request->input('application');
        $charge->insurance = $request->input('insurance');
        $charge->tax = $request->input('tax');

        $charge->save();

        return redirect()->back()->with('success', 'Charge info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charge $charge)
    {
        $charge->delete();
        return redirect('charges')->with('success', 'Charge deleted successfully.');
    }
}
