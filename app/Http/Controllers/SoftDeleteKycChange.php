<?php

namespace App\Http\Controllers;

use App\Models\Kycchange;
use Illuminate\Http\Request;

class SoftDeleteKycChange extends Controller
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
     * Get Soft Deleted Kycchange.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedKycchange($id)
    {
        $kycchange = Kycchange::onlyTrashed()->where('id', $id)->get();
        if (count($kycchange) !== 1) {
            return redirect('/kycchanges/deleted/')->with('error', 'No deleted kyc change requests found so far');
        }

        return $kycchange[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kycchanges = Kycchange::onlyTrashed()->get();

        return view('kycchanges.deleted-kycchanges', compact('kycchanges'));
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
        $kycchange = self::getDeletedKycchange($id);
        return view('kycchanges.deleted-kycchange', compact('kycchange'));
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
        $kycchange = self::getDeletedKycchange($id);
        $kycchange->restore();

        return redirect('/kycchanges/')->with('success', 'Kyc change request restored successfully.');
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
        $kycchange = self::getDeletedKycchange($id);
        $kycchange->forceDelete();

        return redirect('/kycchanges/deleted/')->with('success', 'Kyc change request completely deleted.');
    }
}
