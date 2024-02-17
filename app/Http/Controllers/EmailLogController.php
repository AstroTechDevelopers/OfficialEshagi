<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = EmailLog::all();
        return view('maillogs.maillogs', compact('logs'));
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
     * @param  \App\Models\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function show(EmailLog $emailLog)
    {
        return view('maillogs.maillog', compact('emailLog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailLog $emailLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailLog $emailLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();
        return redirect('maillogs')->with('success', 'Log entry has been deleted successfully.');
    }
}
