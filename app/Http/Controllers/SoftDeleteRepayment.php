<?php

namespace App\Http\Controllers;

use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoftDeleteRepayment extends Controller
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
     * Get Soft Deleted Repayment.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedRepayment($id)
    {
        $repayment = Repayment::onlyTrashed()->where('id', $id)->get();
        if (count($repayment) !== 1) {
            return redirect('/repayments/deleted/')->with('error', 'No deleted repayments found so far');
        }

        return $repayment[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repayments = DB::table('repayments as r')
            ->join('loans as l','l.id','=','r.loanid')
            ->join('clients as c','c.id','=','r.client_id')
            ->select('r.id','r.payment','r.paymt_number','r.balance','l.id as lid','c.first_name','c.last_name','c.natid','c.reds_number','r.deleted_at')
            ->where('r.deleted_at','!=', null)
            ->get();

        return view('repayments.deleted-repayments', compact('repayments'));
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
        /*$repayment = self::getDeletedRepayment($id);
        return view('repayments.deleted-client', compact('client'));*/
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
        $repayment = self::getDeletedRepayment($id);
        $repayment->restore();

        return redirect('/repayments/')->with('success', 'Repayment restored successfully.');
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
        $repayment = self::getDeletedRepayment($id);
        $repayment->forceDelete();

        return redirect('/repayments/deleted/')->with('success', 'Repayment completely deleted.');
    }
}
