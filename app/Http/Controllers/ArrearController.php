<?php

namespace App\Http\Controllers;

use App\Models\Arrear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArrearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrears = Arrear::all();
        return view('arrears.arrears', compact('arrears'));
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
     * @param  \App\Models\Arrear  $arrear
     * @return \Illuminate\Http\Response
     */
    public function show(Arrear $arrear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arrear  $arrear
     * @return \Illuminate\Http\Response
     */
    public function edit(Arrear $arrear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arrear  $arrear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arrear $arrear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arrear  $arrear
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arrear $arrear)
    {
        //
    }

    public function getArrearsComms(){
        $arrears = DB::table('arrears as a')
            ->join('eloans as e', 'e.id','=','a.loan')
            ->join('users as u', 'u.id','=','e.client_id')
            ->select('u.id','u.first_name','u.last_name','u.mobile','u.email','a.maturity_date','a.days_after')
            ->get();
        return view('arrears.comms-arrears', compact('arrears'));
    }
}
