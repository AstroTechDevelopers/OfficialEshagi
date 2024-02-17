<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = BankAccount::all();
        return view('bankaccount.bank-accounts', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = Bank::all();
        return view('bankaccount.add-bank-account', compact('banks'));
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
                'bank'       => 'required',
                'name'        => 'required',
                'branch'        => 'required',
                'account_number'        => 'required',
                'balance'        => 'required',
            ],
            [
                'bank.required'        => 'Where is the bank account located?',
                'name.required'          => 'I need a bank account name.',
                'branch.required'          => 'I need a bank account branch name.',
                'account_number.required'          => 'I need a bank account number.',
                'balance.required'          => 'I need a bank account opening balance.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $account = BankAccount::create([
            'bank_id'             => $request->input('bank'),
            'name'             => $request->input('name'),
            'branch'             => $request->input('branch'),
            'account_number'             => $request->input('account_number'),
            'balance'             => $request->input('balance'),
            'status'             => 0,
        ]);

        $account->save();

        return redirect('bank-accounts')->with('success', 'Bank Account added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banks = Bank::all();
        $bankAccount = BankAccount::findOrFail($id);
        return view('bankaccount.edit-bank-account', compact('bankAccount','banks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        $validator = Validator::make($request->all(),
            [
                'bank'       => 'required',
                'name'        => 'required',
                'branch'        => 'required',
                'account_number'        => 'required',
                'balance'        => 'required',
            ],
            [
                'bank.required'        => 'Where is the bank account located?',
                'name.required'          => 'I need a bank account name.',
                'branch.required'          => 'I need a bank account branch name.',
                'account_number.required'          => 'I need a bank account number.',
                'balance.required'          => 'I need a bank account opening balance.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $bankAccount->bank_id = $request->input('bank');
        $bankAccount->name = $request->input('name');
        $bankAccount->branch = $request->input('branch');
        $bankAccount->account_number = $request->input('account_number');
        $bankAccount->balance = $request->input('balance');

        if ($bankAccount->balance > 0 OR $request->input('balance') > 0){
            $bankAccount->status = 0;
        } elseif ($bankAccount->balance < 0 OR $request->input('balance') < 0){
            $bankAccount->status = 2;
        }

        $bankAccount->save();

        return redirect()->back()->with('success', 'Bank Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount->delete();
        return redirect()->back()->with('success', 'Bank Account deleted successfully.');
    }
}
