<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoftDeleteLedger extends Controller
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
     * Get Soft Deleted Ledger.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedLedger($id)
    {
        $ledger = Ledger::onlyTrashed()->where('id', $id)->get();
        if (count($ledger) !== 1) {
            return redirect('/ledgers/deleted/')->with('error', 'No deleted ledgers found so far');
        }

        return $ledger[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ledgers = DB::table('ledgers as l')
            //->join('loans as l','l.id','=','l.loanid')
            //->join('clients as c','c.id','=','l.client_id')
            //->select('l.id','l.payment','l.paymt_number','l.balance','l.id as lid','c.first_name','c.last_name','c.natid','c.reds_number','l.deleted_at')
            ->where('l.deleted_at','!=', null)
            ->get();

        return view('ledgers.deleted-ledgers', compact('ledgers'));
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
        /*$ledger = self::getDeletedLedger($id);
        return view('ledgers.deleted-client', compact('client'));*/
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
        $ledger = self::getDeletedLedger($id);
        $ledger->restore();

        return redirect('/ledgers/')->with('success', 'Ledger restored successfully.');
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
        $ledger = self::getDeletedLedger($id);
        $ledger->forceDelete();

        return redirect('/ledgers/deleted/')->with('success', 'Ledger completely deleted.');
    }
}
