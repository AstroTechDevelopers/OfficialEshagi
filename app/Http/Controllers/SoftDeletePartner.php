<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\Request;

class SoftDeletePartner extends Controller
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
     * Get Soft Deleted Partner.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedPartner($id)
    {
        $partner = Partner::onlyTrashed()->where('id', $id)->get();
        if (count($partner) !== 1) {
            return redirect('/partners/deleted')->with('error', 'No deleted partners found so far');
        }

        return $partner[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = Partner::onlyTrashed()->get();

        return view('partners.deleted-partners', compact('partners'));
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
        $partner = self::getDeletedPartner($id);

        return view('partners.deleted-partner', compact('partner'));
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
        $partner = self::getDeletedPartner($id);
        $user = User::where('natid', $partner->regNumber)->withTrashed()->first();
        $partner->restore();
        if ($partner->restore()){
            $user->restore();
        }

        return redirect('/partners/')->with('success', 'Partner restored successfully.');
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
        $partner = self::getDeletedPartner($id);
        $user = User::where('natid', $partner->regNumber)->withTrashed()->first();
        $partner->forceDelete();
        if ($partner->forceDelete()){
            $user->forceDelete();
        }

        return redirect('/partners/deleted/')->with('success', 'Partner completely deleted.');
    }
}
