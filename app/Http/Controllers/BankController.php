<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Localel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::join('localels', 'localels.id','=','banks.locale_id')
          ->select('localels.id','localels.country','banks.id as bid','banks.bank','banks.bank_short','bank_post_address','banks.bank_city','banks.bank_telephone','banks.created_at')
            ->get();
        return view('banks.banks', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $localels = Localel::all();
        return view('banks.add-bank', compact('localels'));
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
                'locale_id'       => 'required',
                'bank'        => 'required|max:255|unique:banks',
                'bank_short'     => 'required|unique:banks',
                'bank_post_address'     => 'nullable',
                'bank_city'     => 'nullable',
                'bank_telephone'     => 'nullable',
            ],
            [
                'locale_id.required'        => 'Where is the bank located?',
                'bank.required'          => 'We need a bank name.',
                'bank.unique'          => 'Bank name is already in the system.',
                'bank_short.required'       => 'The bank short name is needed to proceed',
                'bank_short.unique'       => 'We already have this bank short name in the system',
                'bank_post_address.required'     => 'Enter postal adddres',
                'bank_city.required'       => 'City is required.',
                'bank_telephone'     => 'Telephone has to number',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $bank = Bank::create([
            'locale_id'             => $request->input('locale_id'),
            'bank'             => $request->input('bank'),
            'bank_short'             => $request->input('bank_short'),
            'bank_post_address'             => $request->input('bank_post_address'),
            'bank_city'             => $request->input('bank_city'),
            'bank_telephone'             => $request->input('bank_telephone'),
        ]);

        $bank->save();

        return redirect('banks')->with('success', 'Bank added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        $localels = Localel::all();
        return view('banks.edit-bank', compact('bank', 'localels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $validator = Validator::make($request->all(),
            [
                'locale_id'       => 'required',
                'bank'        => 'required|max:255|unique:banks,bank,'.$bank->id,
                'bank_short'     => 'required|unique:banks,bank_short,'.$bank->id,
                'bank_post_address'     => 'nullable',
                'bank_city'     => 'nullable',
                'bank_telephone'     => 'nullable',
            ],
            [
                'locale_id.required'        => 'Where is the bank located?',
                'bank.required'          => 'We need a bank name.',
                'bank.unique'          => 'Bank name is already in the system.',
                'bank_short.required'       => 'The bank short name is needed to proceed',
                'bank_short.unique'       => 'We already have this bank short name in the system',
                'bank_post_address.required'     => 'Enter postal adddres',
                'bank_city.required'       => 'City is required.',
                'bank_telephone'     => 'Telephone has to number',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $bank->locale_id = $request->input('locale_id');
        $bank->bank = $request->input('bank');
        $bank->bank_short = $request->input('bank_short');
        $bank->bank_post_address = $request->input('bank_post_address');
        $bank->bank_city = $request->input('bank_city');
        $bank->bank_telephone = $request->input('bank_telephone');

        $bank->save();

        return redirect()->back()->with('success', 'Bank info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect('banks')->with('success', 'Bank deleted successfully.');
    }

    public function getBanksByCountry($id){
        $banks = DB::table("banks")
            ->where("locale_id", $id)
            ->groupBy('bank')
            ->orderBy("bank", 'asc')
            ->get();

        return response()->json($banks);
    }
}
