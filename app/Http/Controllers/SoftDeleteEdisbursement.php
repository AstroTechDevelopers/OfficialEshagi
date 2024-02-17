<?php

namespace App\Http\Controllers;

use App\Models\Edisbursement;
use Illuminate\Http\Request;

class SoftDeleteEdisbursement extends Controller
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
     * Get Soft Deleted Edisbursement.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedEdisbursement($id)
    {
        $employer = Edisbursement::onlyTrashed()->where('id', $id)->get();
        if (count($employer) !== 1) {
            return redirect('/edisbursements/deleted/')->with('error', 'No deleted edisbursements found so far');
        }

        return $employer[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $edisbursements = Edisbursement::onlyTrashed()->get();

        return view('edisburse.deleted-edisbursements', compact('edisbursements'));
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
        $employer = self::getDeletedEdisbursement($id);
        $employer->restore();

        return redirect('/edisbursements/')->with('success', 'Edisbursement restored successfully.');
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
        $employer = self::getDeletedEdisbursement($id);
        $employer->forceDelete();

        return redirect('/edisbursements/deleted/')->with('success', 'Edisbursement completely deleted.');
    }
}
