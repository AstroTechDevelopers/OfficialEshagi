<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanApproval;
use App\Models\User;
use App\Models\ZambiaLoan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoanApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\LoanApproval  $loanApproval
     * @return \Illuminate\Http\Response
     */
    public function show(LoanApproval $loanApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanApproval  $loanApproval
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanApproval $loanApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanApproval  $loanApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanApproval $loanApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanApproval  $loanApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanApproval $loanApproval)
    {
        //
    }

    public function lookupLoanApproval($id){
        $loanApproval = LoanApproval::findOrFail($id);
        $user = User::where('id', $loanApproval->user_id)->firstOrFail();
        return view('loanapprovals.lookup-approval', compact('loanApproval','user'));
    }

    public function lookupProcessLoan(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'nrc'              => 'required',
                'otp'             => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $loanApproval = LoanApproval::findOrFail($request->input('lookup_id'));

        if ($loanApproval->created_at < Carbon::now()->subHours(24)->toDateTimeString()) {
            return redirect()->back()->with('error','OTP is no longer valid.');
        }

        if (Hash::check($request->input('otp'), $loanApproval->otp)) {
            $loanApproval->verified = true;
            $loanApproval->save();
        } else {
            return redirect()->back()->with('error','Invalid OTP provided, please check your provided OTP.');
        }

        $user = User::where('natid', $request->input('nrc'))->first();
        if (is_null($user)){
            return redirect()->back()->with('error','User not found.');
        }

        if ($loanApproval->locale == '2'){
            $loan = ZambiaLoan::where('id','=',$loanApproval->loan_id)->firstOrFail();
            $loan->loan_status = 8;
        } else {
            $loan = Loan::where('id','=',$loanApproval->loan_id)->firstOrFail();
            $loan->loan_status = 12;
        }

        $loan->save();

        return redirect()->back()->with('success','Loan has been approved! Thank you.');
    }
}
