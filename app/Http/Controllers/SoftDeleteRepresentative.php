<?php

namespace App\Http\Controllers;

use App\Models\Representative;
use App\Models\User;
use Illuminate\Http\Request;

class SoftDeleteRepresentative extends Controller
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
     * Get Soft Deleted Representative.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedRep($id)
    {
        $representative = Representative::onlyTrashed()->where('id', $id)->get();
        if (count($representative) !== 1) {
            return redirect('/representatives/deleted/')->with('error', 'No deleted representatives found so far');
        }

        return $representative[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $representatives = Representative::onlyTrashed()->get();

        return view('reps.deleted-representatives', compact('representatives'));
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
        $representative = self::getDeletedRep($id);
        return view('reps.deleted-representative', compact('representative'));
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
        $representative = self::getDeletedRep($id);
        $representative->restore();
        if ($representative->restore()) {
            $user = User::onlyTrashed()->where('natid', $representative->natid)->first();
            $user->restore();
        }

        return redirect('/representatives/')->with('success', 'Representative restored successfully.');
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
        $representative = self::getDeletedRep($id);
        $representative->forceDelete();

        return redirect('/representatives/deleted/')->with('success', 'Representative completely deleted.');
    }
}
