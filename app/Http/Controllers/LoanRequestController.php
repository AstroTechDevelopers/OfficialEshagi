<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Kapouet\Notyf\Facades\Notyf;

class LoanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = DB::table('loans as l')
            ->join('loan_requests as r', 'r.loan_id','=','l.id')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('r.id','l.id as lid','c.id as cid','c.natid','l.amount','r.requestor','r.request','r.approver','r.approved_at')
            ->where('l.locale','=',auth()->user()->locale)
            ->where('r.deleted_at','=', null)
            ->get();

        return view('loanrequests.requests', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('loanrequests.place-loan-request');
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
                'loan_id'       => 'required',
                'request'        => 'required',
                'explanation'     => 'required',
            ],
            [
                'loan_id.required'        => 'What is the loan being referenced?',
                'request.required'          => 'What type of request it this?',
                'explanation.unique'          => 'What is the reason for this request?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = Loan::findOrFail($request->input('loan_id'));

        if ($loan->loan_status == 0 || $loan->loan_status == 1){
           $level = 1;
        } else {
            $level = 2;
        }

        $loanrequest = LoanRequest::create([
            'loan_id'             => $request->input('loan_id'),
            'level'             => $level,
            'requestor'             => auth()->user()->name,
            'request'             => $request->input('request'),
            'explanation'             => $request->input('explanation'),
        ]);

        $loanrequest->save();

        Notyf::success('Loan Request placed successfully.');
        return redirect('loan-requests');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function show(LoanRequest $loanRequest)
    {
        $loan = Loan::findOrFail($loanRequest->loan_id);
        return view('loanrequests.request-details', compact('loanRequest','loan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanRequest $loanRequest)
    {
        return view('loanrequests.edit-loan-request', compact('loanRequest'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanRequest $loanRequest)
    {
        $validator = Validator::make($request->all(),
            [
                'loan_id'       => 'required',
                'request'        => 'required',
                'explanation'     => 'required',
            ],
            [
                'loan_id.required'        => 'What is the loan being referenced?',
                'request.required'          => 'What type of request it this?',
                'explanation.unique'          => 'What is the reason for this request?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = Loan::findOrFail($request->input('loan_id'));

        if ($loan->loan_status == 0 || $loan->loan_status == 1){
            $level = 1;
        } else {
            $level = 2;
        }

        $loanRequest->level = $level;
        $loanRequest->request = $request->input('request');
        $loanRequest->explanation = $request->input('explanation');

        $loanRequest->save();

        Notyf::success('Loan Request updated.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanRequest  $loanRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanRequest $loanRequest)
    {
        $loanRequest->delete();
        Notyf::success('Loan Request deleted.');
        return redirect()->back();
    }

    public function getMyLoanRequests(){
        $requests = DB::table('loans as l')
            ->join('loan_requests as r', 'r.loan_id','=','l.id')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('r.id','l.id as lid','c.id as cid','c.natid','l.amount','r.requestor','r.request','r.approver','r.approved_at')
            ->where('r.requestor','=',auth()->user()->name)
            ->where('l.locale','=',auth()->user()->locale)
            ->where('r.deleted_at','=', null)
            ->get();

        return view('loanrequests.my-requests', compact('requests'));
    }

    public function getPendingLoanRequests(){
        if(Auth::User()->hasRole('loansofficer')){
            $requests = DB::table('loans as l')
                ->join('loan_requests as r', 'r.loan_id','=','l.id')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->select('r.id','l.id as lid','c.id as cid','c.natid','l.amount','r.requestor','r.request','r.approver','r.approved_at')
                ->where('r.level','=',1)
                ->where('r.approver','=',null)
                ->where('l.locale','=',auth()->user()->locale)
                ->where('r.deleted_at','=', null)
                ->get();
        } elseif (Auth::User()->hasRole('manager')){
            $requests = DB::table('loans as l')
                ->join('loan_requests as r', 'r.loan_id','=','l.id')
                ->join('clients as c', 'c.id','=','l.client_id')
                ->select('r.id','l.id as lid','c.id as cid','c.natid','l.amount','r.requestor','r.request','r.approver','r.approved_at')
                ->where('r.level','=',2)
                ->where('r.approver','=',null)
                ->where('l.locale','=',auth()->user()->locale)
                ->where('r.deleted_at','=', null)
                ->get();
        } else {
            Notyf::error('Not Authorized.');
            return redirect()->back();
        }

        return view('loanrequests.actionable-requests', compact('requests'));
    }

    public function reviewLoanReq($id){
        $loanRequest = LoanRequest::findOrFail($id);
        $loan = Loan::findOrFail($loanRequest->loan_id);
        return view('loanrequests.review-request', compact('loanRequest', 'loan'));
    }

    public function approveLoanRequest($id){
        $loanRequest = LoanRequest::findOrFail($id);
        $loan = Loan::findOrFail($loanRequest->loan_id);

        $loanRequest->approver = auth()->user()->name;
        $loanRequest->approved_at = now();
        $loanRequest->comment = $loan->notes;
        $loanRequest->save();

        if ($loanRequest->request == 'Decline' AND $loanRequest->save()){
            $loan->loan_status = 13;
            $loan->notes = $loanRequest->comment.'. Loan declined with reference loan request ID: '.$loanRequest->id;
            $loan->save();
        }

        Notyf::success('Loan Request approved.');
        return redirect()->back();
    }
}
