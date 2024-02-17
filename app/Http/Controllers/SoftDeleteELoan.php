<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Eloan;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoftDeleteELoan extends Controller
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
     * Get Soft Deleted eLoan.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedLoan($id)
    {
        $loan = eLoan::onlyTrashed()->where('id', $id)->get();
        if (count($loan) !== 1) {
            return redirect('/eloans/deleted/')->with('error', 'No deleted eloans found so far');
        }

        return $loan[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = DB::table('loans as l')
            ->join('users as u', 'u.id','=','l.user_id')
            ->select('l.id','u.first_name','u.last_name','u.natid','l.amount','l.monthly','l.loan_status','l.loan_type','l.deleted_at')
            ->where('l.deleted_at', '!=', null)
            ->get();

        return view('eloans.deleted-eloans', compact('loans'));
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
        $loan = self::getDeletedLoan($id);
        $client = Client::where('id', $loan->client_id)->first();
        $partner = Partner::where('id', $loan->partner_id)->first();

        return view('eloans.deleted-eloan', compact('loan','client','partner'));
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
        $loan = self::getDeletedLoan($id);
        $loan->restore();

        return redirect('/eloans/')->with('success', 'eLoan restored successfully.');
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
        $loan = self::getDeletedLoan($id);
        $loan->forceDelete();

        return redirect('/eloans/deleted/')->with('success', 'eLoan completely deleted.');
    }
}
