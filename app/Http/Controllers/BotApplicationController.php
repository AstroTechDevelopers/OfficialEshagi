<?php

namespace App\Http\Controllers;

use App\Models\BotApplication;
use App\Models\Client;
use Illuminate\Http\Request;
use Kapouet\Notyf\Facades\Notyf;

class BotApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = BotApplication::all();
        return view('botapplications.applications', compact('applications'));
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
     * @param  \App\Models\BotApplication  $botApplication
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $botApplication = BotApplication::findOrFail($id);
        $client = Client::findOrFail($botApplication->client_id);

        return view('botapplications.view-botapplication', compact('botApplication', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BotApplication  $botApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(BotApplication $botApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BotApplication  $botApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BotApplication $botApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BotApplication  $botApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $botApplication = BotApplication::findOrFail($id);
        $botApplication->delete();

        Notyf::success('Application deleted');
        return redirect()->back();
    }
}
