<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\EshagiAccount;
use App\Models\Ledger;
use App\Models\Localel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ledgers = Ledger::all();
        return view('ledgers.ledgers', compact('ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $localels = Localel::all();
        return view('ledgers.create-ledger', compact('localels'));
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
                'locale_id'        => 'required',
                'ledger'       => 'required|unique:ledgers',
                'balance'       => 'required',
            ],
            [
                'locale_id.required'          => 'Where is the ledger from?.',
                'ledger.required'        => 'What is the name of the ledger?',
                'ledger.unique'        => 'This ledger is already in the system.',
                'balance.required'       => 'What is opening balance for the ledger?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->input('ledger'))) );
        $locale = Localel::findOrFail($request->input('locale_id'));
        $ledger = Ledger::create([
            'creator'             => auth()->user()->name,
            'locale_id'             => $request->input('locale_id'),
            'shortname'             => $slug,
            'currency'             => $locale->currency_code,
            'ledger'             => $request->input('ledger'),
            'balance'             => $request->input('balance'),
            'active'             => true,
            'notes'             => $request->input('notes'),
        ]);

        $ledger->save();

        return redirect('ledgers')->with('success', 'Ledger added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function show(Ledger $ledger)
    {
        $locale = Localel::findOrFail($ledger->locale_id);
        $recently = EshagiAccount::where('ledger',$ledger->id)->get();
        return view('ledgers.view-ledger', compact('ledger','locale','recently'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function edit(Ledger $ledger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ledger $ledger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ledger $ledger)
    {
        $ledger->delete();
        return redirect()->back()->with('success', 'Ledger has been deleted successfully');
    }
}
