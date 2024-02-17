<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoftDeleteCommission extends Controller
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
     * Get Soft Deleted Commission.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedCommission($id)
    {
        $commission = Commission::onlyTrashed()->where('id', $id)->get();
        if (count($commission) !== 1) {
            return redirect('/commissions/deleted/')->with('error', 'No deleted commissions found so far');
        }

        return $commission[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = DB::table('commissions as c')
            ->join('clients as cl', 'cl.id','=','c.client')
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout','c.deleted_at')
            ->where('c.deleted_at','!=', null)
            ->get();

        return view('commissions.deleted-commissions', compact('commissions'));
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
        $commission = self::getDeletedCommission($id);
        $commission->restore();

        return redirect('/commissions/')->with('success', 'Commission restored successfully.');
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
        $commission = self::getDeletedCommission($id);
        $commission->forceDelete();

        return redirect('/commissions/deleted/')->with('success', 'Commission completely deleted.');
    }
}
