<?php

namespace App\Http\Controllers;

use App\Models\Ndaseresponse;
use Illuminate\Http\Request;

class NdaseresponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responses = Ndaseresponse::all();
        return view('responses.responses', compact('responses'));
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
     * @param  \App\Ndaseresponse  $ndaseresponse
     * @return \Illuminate\Http\Response
     */
    public function show(Ndaseresponse $ndaseresponse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ndaseresponse  $ndaseresponse
     * @return \Illuminate\Http\Response
     */
    public function edit(Ndaseresponse $ndaseresponse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ndaseresponse  $ndaseresponse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ndaseresponse $ndaseresponse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ndaseresponse  $ndaseresponse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ndaseresponse $ndaseresponse)
    {
        //
    }
}
