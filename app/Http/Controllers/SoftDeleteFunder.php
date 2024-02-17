<?php

namespace App\Http\Controllers;

use App\Models\Funder;
use Illuminate\Http\Request;
use jeremykenedy\LaravelRoles\Models\Role;

class SoftDeleteFunder extends Controller
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
     * Get Soft Deleted Funder.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedFunder($id)
    {
        $funder = Funder::onlyTrashed()->where('id', $id)->get();
        if (count($funder) !== 1) {
            return redirect('/funders/deleted/')->with('error', 'No deleted funders found so far');
        }

        return $funder[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funders = Funder::onlyTrashed()->get();

        return view('funders.deleted-funders', compact('funders'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

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
        $funder = self::getDeletedFunder($id);
        $funder->restore();

        return redirect('/funders/')->with('success', 'Funder restored successfully.');
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
        $funder = self::getDeletedFunder($id);
        $funder->forceDelete();

        return redirect('/funders/deleted/')->with('success', 'Funder completely deleted.');
    }
}
