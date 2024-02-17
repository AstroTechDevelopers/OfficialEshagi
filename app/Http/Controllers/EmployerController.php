<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Localel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employers = Employer::all();
        return view('employers.employers', compact('employers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $localels = Localel::all();
        return view('employers.add-employer', compact('localels'));
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
                'locale_id'       => 'required',
                'employer'        => 'required',
                'location' => 'required',
                'cutoffdate'     => 'required|numeric|gt:0',
            ],
            [
                'locale_id.required'        => 'Where is the employer based?',
                'employer.required'          => 'What is the name of the employer?',
                'location.required'          => 'Where is the employer located?',
                'cutoffdate.required'       => 'What is the employee cut-off date?',
                'cutoffdate.numeric'       => 'Cut-off date must be an integer',
                'cutoffdate.gt'       => 'Cut-off date must be greater than zero',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employer = Employer::create([
            'locale_id'             => $request->input('locale_id'),
            'employer'             => $request->input('employer'),
            'location'             => $request->input('location'),
            'subgroup'             => $request->input('subgroup'),
            'cutoffdate'             => $request->input('cutoffdate'),
        ]);

        $employer->save();

        return redirect('employers')->with('success', 'Employer added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employer  $employer
     * @return \Illuminate\Http\Response
     */
    public function show(Employer $employer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employer  $employer
     * @return \Illuminate\Http\Response
     */
    public function edit(Employer $employer)
    {
        $localels = Localel::all();
        return view('employers.edit-employer', compact('employer','localels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employer  $employer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employer $employer)
    {
        $validator = Validator::make($request->all(),
            [
                'locale_id'       => 'required',
                'employer'        => 'required',
                'location' => 'required',
                'cutoffdate'     => 'required|numeric|gt:0',
            ],
            [
                'locale_id.required'        => 'Where is the employer based?',
                'employer.required'          => 'What is the name of the employer?',
                'location.required'          => 'Where is the employer located?',
                'cutoffdate.required'       => 'What is the employee cut-off date?',
                'cutoffdate.numeric'       => 'Cut-off date must be an integer',
                'cutoffdate.gt'       => 'Cut-off date must be greater than zero',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employer->locale_id = $request->input('locale_id');
        $employer->employer = $request->input('employer');
        $employer->location = $request->input('location');
        $employer->subgroup = $request->input('subgroup');
        $employer->cutoffdate = $request->input('cutoffdate');

        $employer->save();

        return redirect('employers')->with('success', 'Employer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employer  $employer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employer $employer)
    {
        $employer->delete();
        return redirect('employers')->with('success', 'Bank deleted successfully.');

    }
}
