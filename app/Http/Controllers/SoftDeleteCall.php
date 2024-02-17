<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class SoftDeleteCall extends Controller
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
     * Get Soft Deleted Call.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedCall($id)
    {
        $call = Call::onlyTrashed()->where('id', $id)->get();
        if (count($call) !== 1) {
            return redirect('/calls/deleted/')->with('error', 'No deleted calls found so far');
        }

        return $call[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calls = Call::onlyTrashed()->get();

        return view('calls.deleted-calls', compact('calls'));
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
        $call = self::getDeletedCall($id);
        return view('calls.deleted-call', compact('call'));
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
        $call = self::getDeletedCall($id);
        $call->restore();

        return redirect('/calls/')->with('success', 'Call restored successfully.');
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
        $call = self::getDeletedCall($id);
        $call->forceDelete();

        return redirect('/calls/deleted/')->with('success', 'Call completely deleted.');
    }
}
