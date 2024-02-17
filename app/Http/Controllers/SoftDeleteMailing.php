<?php

namespace App\Http\Controllers;

use App\Models\Repmailinglist;
use Illuminate\Http\Request;

class SoftDeleteMailing extends Controller
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
     * Get Soft Deleted Repmailinglist.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedMailinglist($id)
    {
        $mailinglist = Repmailinglist::onlyTrashed()->where('id', $id)->get();
        if (count($mailinglist) !== 1) {
            return redirect('/mailings/deleted')->with('error', 'No deleted mailing lists found so far');
        }

        return $mailinglist[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repmailinglists = Repmailinglist::onlyTrashed()->get();

        return view('mailinglists.deleted-mailinglists', compact('repmailinglists'));
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
        $repmailinglist = self::getDeletedMailinglist($id);
        return view('mailinglists.deleted-mailinglist', compact('repmailinglist'));
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
        $mailinglist = self::getDeletedMailinglist($id);
        $mailinglist->restore();

        return redirect('/mailings/')->with('success', 'Mailing list restored successfully.');
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
        $mailinglist = self::getDeletedMailinglist($id);
        $mailinglist->forceDelete();

        return redirect('/mailings/deleted')->with('success', 'Mailing list completely deleted.');
    }
}
