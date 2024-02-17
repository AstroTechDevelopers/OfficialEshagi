<?php

namespace App\Http\Controllers;

use App\Models\Edisbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EdisbursementController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $edisbursements = Edisbursement::all();
        return view('edisburse.edisbursements', compact('edisbursements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Edisbursement  $edisbursement
     * @return \Illuminate\Http\Response
     */
    public function show(Edisbursement $edisbursement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Edisbursement  $edisbursement
     * @return \Illuminate\Http\Response
     */
    public function edit(Edisbursement $edisbursement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Edisbursement  $edisbursement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Edisbursement $edisbursement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Edisbursement  $edisbursement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Edisbursement $edisbursement)
    {
        //
    }

    public function getEloansToDisburse(){
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.dob','c.emp_sector','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('c.reds_number','=', null)
            ->where('l.deleted_at','=', null)
            ->orderByDesc('l.created_at')
            ->get();

        return view('edisburse.disburse-eloans', compact('loans'));

    }

    public function getCurrentDisbursements(){
        $edisbursements = DB::table('edisbursements as d')
            ->join('loans as l','l.id','=','d.eloan')
            ->join('clients as c','c.id','=','l.client_id')
            ->select('d.id','d.disburser','d.via','d.amount','l.id as lid','c.first_name','c.last_name','c.natid', 'd.reference')
            ->where('d.deleted_at','=', null)
            ->whereMonth('d.created_at', date('m'))
            ->whereYear('d.created_at', date('Y'))
            ->get();

        return view('edisburse.current-edisbursements', compact('edisbursements'));
    }
}
