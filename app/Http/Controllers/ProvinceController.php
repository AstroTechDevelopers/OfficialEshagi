<?php

namespace App\Http\Controllers;

use App\Models\Localel;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();
        return view('provinces.provinces', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Localel::all();
        return view('provinces.add-province', compact('countries'));
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
                'country'       => 'required',
                'country_name'        => 'required',
                'province'     => 'required',
            ],
            [
                'country.required'        => 'Where is the province located?',
                'country_name.required'          => 'What is the country name?',
                'province.required'       => 'We need the province name.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $province = Province::create([
            'country'             => $request->input('country'),
            'country_name'             => $request->input('country_name'),
            'province'             => $request->input('province'),
        ]);

        $province->save();

        return redirect('provinces')->with('success', 'Province added successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $province)
    {
        $countries = Localel::all();
        return view('provinces.edit-province', compact('province','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Province $province)
    {
        $validator = Validator::make($request->all(),
            [
                'country'       => 'required',
                'country_name'        => 'required',
                'province'     => 'required',
            ],
            [
                'country.required'        => 'Where is the province located?',
                'country_name.required'          => 'What is the country name?',
                'province.required'       => 'We need the province name.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $province->country = $request->input('country');
        $province->country_name = $request->input('country_name');
        $province->province = $request->input('province');

        $province->save();

        return redirect()->back()->with('success', 'Province info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        $province->delete();
        return redirect('provinces')->with('success', 'Province deleted successfully.');
    }

    public function getProvinces($id) {

        $provinces = DB::table("provinces")
            ->where("country", $id)
            ->select("id", "country", "province")
            ->orderBy("province", 'asc')
            ->get();

        return response()->json($provinces);
    }
}
