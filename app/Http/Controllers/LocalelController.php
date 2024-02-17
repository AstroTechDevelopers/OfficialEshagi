<?php

namespace App\Http\Controllers;

use App\Models\Localel;
use Illuminate\Http\Request;
use Validator;

class LocalelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localels = Localel::all();

        return view('localels.localels', compact('localels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('localels.add-locale');
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
                'country'        => 'required|max:255|unique:localels',
                'country_short'        => 'required|unique:localels',
                'currency_code'       => 'required',
                'currency_name'       => 'required',
                'symbol'    => 'required',
                'country_code'     => 'required|unique:localels',
            ],
            [
                'country.unique'          => 'We already have this country in the system.',
                'country.required'        => 'We obviously need a country name.',
                'country_short.unique'          => 'We already have this country code in the system.',
                'country_short.required'        => 'We need a country code name.',
                'currency_code.required'       => 'What is the currency code for the locale?',
                'currency_name.required'       => 'What is the currency locale?',
                'symbol.required'    => 'Which is the symbol they use?',
                'country_code.unique'     => 'This country code is already in the system.',
                'country_code.required'     => 'We need a country code for this locale.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $localel = Localel::create([
            'country'             => $request->input('country'),
            'country_short'             => $request->input('country_short'),
            'currency_code'             => $request->input('currency_code'),
            'currency_name'             => $request->input('currency_name'),
            'symbol'             => $request->input('symbol'),
            'country_code'             => $request->input('country_code'),
        ]);

        $localel->save();

        return redirect('localels')->with('success', 'Locale added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Localel  $localel
     * @return \Illuminate\Http\Response
     */
    public function show(Localel $localel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Localel  $localel
     * @return \Illuminate\Http\Response
     */
    public function edit(Localel $localel)
    {
        return view('localels.edit-locale', compact('localel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Localel  $localel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Localel $localel)
    {
        $validator = Validator::make($request->all(),
            [
                'country'                  => 'required|max:255|unique:localels,country,'.$localel->id,
                'country_short'                  => 'required|max:255|unique:localels,country_short,'.$localel->id,
                'currency_code'                  => 'required',
                'currency_name'                  => 'required',
                'symbol'                  => 'required',
                'country_code'                  => 'required|unique:localels,country_code,'.$localel->id,
            ],
            [
                'country.unique'          => 'We already have this country in the system.',
                'country.required'        => 'We obviously need a country name.',
                'country_short.unique'          => 'We already have this country code in the system.',
                'country_short.required'        => 'We need a country code name.',
                'currency_code.required'       => 'What is the currency code for the locale?',
                'currency_name.required'       => 'What is the currency locale?',
                'symbol.required'    => 'Which is the symbol they use?',
                'country_code.unique'     => 'This country code is already in the system.',
                'country_code.required'     => 'We need a country code for this locale.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $localel->country = $request->input('country');
        $localel->country_short = $request->input('country_short');
        $localel->currency_code = $request->input('currency_code');
        $localel->currency_name = $request->input('currency_name');
        $localel->symbol = $request->input('symbol');
        $localel->country_code = $request->input('country_code');

        $localel->save();

        return redirect()->back()->with('success', 'Locale info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Localel  $localel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Localel $localel)
    {
        $localel->delete();

        return redirect('localels')->with('success', 'Locale info deleted successful.');
    }
}
