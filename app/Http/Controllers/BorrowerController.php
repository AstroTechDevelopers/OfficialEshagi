<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Client;
use App\Models\Eloan;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borrowers = Borrower::all();
        return view('borrowers.borrowers', compact('borrowers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        $loans = Eloan::whereNotIn('loan_status', array(0,6,10))->get();
        return view('borrowers.add-borrower', compact('clients','loans'));
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
                'currency'       => 'required',
                'natid'        => 'required',
                'amount'        => 'required',
                'reason'        => 'required',
            ],
            [
                'currency.required'        => 'What is the default borrowing currency?',
                'natid.required'          => 'Who is the borrower?',
                'amount.required'          => 'What is being borrowed?',
                'reason.required'          => 'Which loan is associated to the borrower?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $borrower = Borrower::create([
            'currency'             => $request->input('currency'),
            'natid'             => $request->input('natid'),
            'amount'             => $request->input('amount'),
            'balance'             => $request->input('amount'),
            'reason'             => $request->input('reason'),
            'status'             => 1,
        ]);

        $borrower->save();

        return redirect('borrowers')->with('success', 'Borrower added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function show(Borrower $borrower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function edit(Borrower $borrower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Borrower $borrower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Borrower $borrower)
    {
        //
    }
}
