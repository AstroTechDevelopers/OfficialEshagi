<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = DB::table('commissions as c')
            ->join('clients as cl', 'cl.id','=','c.client')
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout')
            ->where('c.deleted_at','=', null)
            ->get();

        return view('commissions.commissions', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = Loan::where('loan_status',12)->get();
        $agents = User::where('utype', 'System')->get();

        return view('commissions.add-commission', compact('loans','agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'agent'       => 'required',
                'client'        => 'required',
                'loanid'     => 'required|unique:commissions',
                'loanamt'     => 'required',
                'commission'     => 'required',
            ],
            [
                'agent.required'        => 'Who will be receiving this commission?',
                'client.required'        => 'Did you select the loan to whom this commission belongs to?',
                'loanid.required'          => 'What loan will be awarding this commission?',
                'loanid.unique'          => 'A commission has already been allocated for this loan.',
                'loanamt.required'       => 'What is the loan amount?',
                'commission.required'       => 'What is the payable commission?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $commission = Commission::create([
            'agent'             => $request->input('agent'),
            'client'             => $request->input('client'),
            'loanid'             => $request->input('loanid'),
            'loanamt'            => $request->input('loanamt'),
            'commission'         => $request->input('commission'),
        ]);

        $commission->save();

        return redirect('commissions')->with('success', 'Commission has been recorded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commission $commission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commission $commission)
    {
        $commission->delete();
        return redirect('commissions')->with('success', 'Commission Record deleted successfully.');
    }

    public function getUnpaidCommissions(){
        $commissions = DB::table('commissions as c')
            ->join('clients as cl', 'cl.id','=','c.client')
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout')
            ->where('c.paidout','=', false)
            ->where('c.deleted_at','=', null)
            ->get();

        return view('commissions.unpaid-commissions', compact('commissions'));
    }

    public function getPaidCommissions(){
        $commissions = DB::table('commissions as c')
            ->join('clients as cl', 'cl.id','=','c.client')
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout')
            ->where('c.paidout','=', true)
            ->where('c.deleted_at','=', null)
            ->get();

        return view('commissions.paid-commissions', compact('commissions'));
    }

    public function registerCommissionPayments() {
        $commissions = DB::table('commissions as c')
            ->join('clients as cl', 'cl.id','=','c.client')
            ->select('c.id','c.agent','cl.natid','c.loanid','c.loanamt','c.commission','c.paidout')
            ->where('c.paidout','=', false)
            ->where('c.deleted_at','=', null)
            ->get();

        return view('commissions.make-payments', compact('commissions'));
    }

    public function paySingleCommission($id){
        $commission = Commission::where('id', $id)->firstOrFail();
        $commission->paidout = true;
        $commission->save();

        return redirect()->back()->with('success','Commission payment recorded successfully.');
    }

    public function payAllCommissions(){
        DB::table('commissions')
            ->where('paidout','=', false)
            ->where('c.deleted_at','=', null)
            ->update(['paidout'=> true, 'updated_at'=>now()]);

        return redirect()->back()->with('success', 'Commission payments recorded successfully');
    }
}
