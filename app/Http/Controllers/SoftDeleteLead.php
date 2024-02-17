<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class SoftDeleteLead extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get Soft Deleted Lead.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedLead($id)
    {
        $lead = Lead::onlyTrashed()->where('id', $id)->get();
        if (count($lead) !== 1) {
            return redirect('/leads/deleted/')->with('error', 'No deleted leads found so far.');
        }

        return $lead[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::onlyTrashed()->get();

        return view('leads.deleted-leads', compact('leads'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = self::getDeletedLead($id);

        return view('leads.deleted-lead', compact('lead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lead = self::getDeletedLead($id);
        $lead->restore();

        return redirect('/leads/')->with('success', 'Lead restored successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = self::getDeletedLead($id);
        $lead->forceDelete();

        return redirect('/leads/deleted/')->with('success', 'Lead completely deleted.');
    }
}
