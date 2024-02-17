<?php

namespace App\Http\Controllers;

use App\Models\Chief;
use Illuminate\Http\Request;

class ChiefController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:chief');
    }
    /**
     * show dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin');
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
     * @param  \App\Models\Chief  $chief
     * @return \Illuminate\Http\Response
     */
    public function show(Chief $chief)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chief  $chief
     * @return \Illuminate\Http\Response
     */
    public function edit(Chief $chief)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chief  $chief
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chief $chief)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chief  $chief
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chief $chief)
    {
        //
    }
}
