<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Loan;
use App\Models\Repayment;
use CodeSmithTech\Amortize\Amortize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class RepaymentController extends Controller
{
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
            ->select('r.id','r.payment','r.paymt_number','r.balance','l.id as lid','c.first_name','c.last_name','c.natid','c.reds_number')
            ->where('r.deleted_at','=', null)
            ->get();

        return view('repayments.repayments', compact('repayments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = DB::table('loans as l')
            ->join('clients as c','c.id','=','l.client_id')
            ->select('l.id as lid','l.amount','c.id as cid','c.first_name','c.last_name','c.natid','c.reds_number')
            ->get();

        return view('repayments.add-repayment', compact('loans'));
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
                'paymt_number'       => 'required',
                'loanid'        => 'required',
                'client_id'     => 'required',
                'reds_number'     => 'required',
                'payment'     => 'required',
                'principal'     => 'required',
                'interest'     => 'required',
                'balance'     => 'required',
            ],
            [
                'paymt_number.required'        => 'Which payment number is this',
                'loanid.required'          => 'What is the loan number is this payment for?',
                'client_id.required'          => 'This loan belongs to which client?',
                'reds_number.required'       => 'We need the Redsphere number associated with this client.',
                'payment.required'       => 'What is the amount being paid?',
                'principal.required'       => 'What is the principal amount being paid?',
                'interest.required'       => 'What is the interest?',
                'balance.required'       => 'What is the loan balance?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $repayment = Repayment::create([
            'paymt_number'             => $request->input('paymt_number'),
            'loanid'             => $request->input('loanid'),
            'client_id'             => $request->input('client_id'),
            'reds_number'             => $request->input('reds_number'),
            'payment'             => $request->input('payment'),
            'principal'             => $request->input('principal'),
            'interest'             => $request->input('interest'),
            'balance'             => $request->input('balance'),
        ]);

        $repayment->save();

        return redirect('repayments')->with('success', 'Repayment added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function show(Repayment $repayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function edit(Repayment $repayment)
    {
        return view('repayments.edit-repayment', compact('repayment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Repayment $repayment)
    {
        $validator = Validator::make($request->all(),
            [
                'paymt_number'       => 'required',
            ],
            [
                'paymt_number.required'        => 'Which payment number is this',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $repayment->paymt_number = $request->input('paymt_number');

        $repayment->save();

        return redirect()->back()->with('success', 'Repayment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Repayment $repayment)
    {
        $repayment->delete();
        return redirect('repayments')->with('success', 'Repayment deleted successfully.');
    }

    public function getCurrentRepayments() {
        $repayments = DB::table('repayments as r')
            ->join('loans as l','l.id','=','r.loanid')
            ->join('clients as c','c.id','=','r.client_id')
            ->select('r.id','r.payment','r.paymt_number','r.balance','l.id as lid','c.first_name','c.last_name','c.natid','c.reds_number', 'r.payment','r.principal','r.interest','r.balance')
            ->where('r.deleted_at','=', null)
            ->where('r.paymt_number', date('F Y'))
            //->whereMonth('r.created_at', date('m'))
            //->whereYear('r.created_at', date('Y'))
            ->get();

        return view('repayments.current-repayments', compact('repayments'));
    }

    function calcOrgPayment(float $loanAmount, int $totalPayments, float $interest) {
        //***********************************************************
        //              INTEREST * ((1 + INTEREST) ^ TOTALPAYMENTS)
        // PMT = LOAN * -------------------------------------------
        //                  ((1 + INTEREST) ^ TOTALPAYMENTS) - 1
        //***********************************************************



        $value1 = $interest * pow((1 + $interest), $totalPayments);
        $value2 = pow((1 + $interest), $totalPayments) - 1;
        $pmt    = $loanAmount * ($value1 / $value2);
        return $pmt;
    }

    function calcPayment() {
        /*$principal = 20000.00;
        $months = 1 * 24;
        $interest = (5 / 100);
        $fixedPayment = $principal / $months;
        $interestRateForMonth = $interest / 12;
        $result = $interest / 12 * pow(1 + $interest / 12, $months) / (pow(1 + $interest / 12, $months) - 1) * $principal;
        printf("Monthly pay is %.2f", $result);*/

        $amortize = new Amortize();
        $amortize->setInterestRate(5.0);
        $amortize->setPrincipal(20000.00);
        $amortize->setTerm(24); // months
echo $amortize->totalAmountDueOverTerm().'<br>';
        //dd($amortize->getBreakdownByMonth());
        foreach ($amortize->getBreakdownByMonth() as $key => $repayment){
            $paym = Repayment::create([
                'paymt_number'             => date_format($repayment->date, 'F Y'),
                'loanid'             => $request->input('loanid'),
                'client_id'             => $request->input('client_id'),
                'reds_number'             => $request->input('reds_number'),
                'payment'             => $repayment->principalDue + $repayment->interestDue,
                'principal'             => $repayment->principalDue,
                'interest'             => $repayment->interestDue,
                'balance'             => $repayment->closingBalance,
            ]);
            $paym->save();
        }
    }

    public function makeLoanAmortization(Request $request){
        $loanid = $request->input('loanid');
        $loan = Loan::findOrFail($loanid);
        $clientid = $loan->client_id;
        $client = Client::findOrFail($clientid);
        $redsnum = $client->reds_number;

        if (Repayment::where('loanid', '=', $loanid)->exists()) {
            return redirect()->back()->with('error', 'Repayment schedule record already exists in the system.');
        }

        $amortize = new Amortize();
        $amortize->setInterestRate(5.0);
        $amortize->setPrincipal($loan->amount);
        $amortize->setTerm($loan->paybackPeriod);

        foreach ($amortize->getBreakdownByMonth() as $key => $repayment){
            $paym = Repayment::create([
                'paymt_number'             => date_format($repayment->date, 'F Y'),
                'loanid'             => $loanid,
                'client_id'             => $clientid,
                'reds_number'             => $redsnum,
                'payment'             => $repayment->principalDue + $repayment->interestDue,
                'principal'             => $repayment->principalDue,
                'interest'             => $repayment->interestDue,
                'balance'             => $repayment->closingBalance,
            ]);
            $paym->save();
        }

        return redirect('repayments')->with('success', 'Repayment schedule added successfully.');

    }

    public function generateRepaymentAmortization($loanid, $clientid, $redsnum){
        $loan = Loan::findOrFail($loanid);
        $amortize = new Amortize();
        $amortize->setInterestRate(5.0);
        $amortize->setPrincipal($loan->amount);
        $amortize->setTerm($loan->paybackPeriod);

        foreach ($amortize->getBreakdownByMonth() as $key => $repayment){
            $paym = Repayment::create([
                'paymt_number'             => date_format($repayment->date, 'F Y'),
                'loanid'             => $loanid,
                'client_id'             => $clientid,
                'reds_number'             => $redsnum,
                'payment'             => $repayment->principalDue + $repayment->interestDue,
                'principal'             => $repayment->principalDue,
                'interest'             => $repayment->interestDue,
                'balance'             => $repayment->closingBalance,
            ]);
            $paym->save();
        }

    }

    public function getMyRepayments(){
        $repayments = DB::table('repayments as r')
            ->join('loans as l','l.id','=','r.loanid')
            ->join('clients as c','c.id','=','r.client_id')
            ->select('r.id','r.payment','r.paymt_number','r.principal','r.interest','r.balance','r.payment','l.id as lid','c.first_name','c.last_name','c.natid','c.reds_number')
            ->where('l.user_id','=', auth()->user()->id)
            ->where('r.deleted_at','=', null)
            ->get();

        return view('repayments.my-repayments', compact('repayments'));
    }
}
