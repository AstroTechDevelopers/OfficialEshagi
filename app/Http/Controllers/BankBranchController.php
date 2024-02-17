<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankBranch;
use Illuminate\Http\Request;
use Validator;

class BankBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = BankBranch::join('banks', 'banks.id','=','bank_branches.bank_id')
            ->select('banks.id','banks.bank','bank_branches.id as bid','bank_branches.branch','bank_branches.branch_code','bank_branches.created_at')
            ->get();
        return view('branches.branches', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = Bank::all();
        return view('branches.add-branch', compact('banks'));
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
                'bank_id'       => 'required',
                'branch'        => 'required|max:255',
                'branch_code'     => 'required',
            ],
            [
                'bank_id.required'        => 'This branch is for which bank?',
                'branch.required'          => 'We obviuosly need a branch name.',
                'branch_code.required'       => 'What is the branch code for this branch?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $branch = BankBranch::create([
            'bank_id'             => $request->input('bank_id'),
            'branch'             => $request->input('branch'),
            'branch_code'             => $request->input('branch_code'),
        ]);

        $branch->save();

        return redirect('branches')->with('success', 'Branch added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankBranch  $bankBranch
     * @return \Illuminate\Http\Response
     */
    public function show(BankBranch $bankBranch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankBranch  $bankBranch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankBranch = BankBranch::findOrFail($id);
        $banks = Bank::all();
        $currentBank = Bank::where('id', $bankBranch->bank_id)->first();

        return view('branches.edit-branch', compact('bankBranch', 'banks', 'currentBank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankBranch  $bankBranch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bankBranch = BankBranch::findOrFail($id);
        $validator = Validator::make($request->all(),
            [
                'bank_id'       => 'required',
                'branch'        => 'required',
                'branch_code'     => 'required',
            ],
            [
                'bank_id.required'        => 'This branch is for which bank?',
                'branch.required'          => 'We obviuosly need a branch name.',
                'branch_code.required'       => 'What is the branch code for this branch?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $bankBranch->bank_id = $request->input('bank_id');
        $bankBranch->branch = $request->input('branch');
        $bankBranch->branch_code = $request->input('branch_code');

        $bankBranch->save();

        return back()->with('success', 'Branch info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankBranch  $bankBranch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bankBranch = BankBranch::findOrFail($id);
        $bankBranch->delete();
        return redirect('branches')->with('success', 'Branch deleted successfully.');
    }
}
