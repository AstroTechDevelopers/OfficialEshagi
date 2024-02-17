<?php

namespace App\Http\Controllers;

use App\Models\SsbDetail;
use Illuminate\Http\Request;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Validator;

class SsbDetailController extends Controller
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
     * @param  \App\Models\SsbDetail  $ssbDetail
     * @return \Illuminate\Http\Response
     */
    public function show(SsbDetail $ssbDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SsbDetail  $ssbDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(SsbDetail $ssbDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SsbDetail  $ssbDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ssbDetail = SsbDetail::where('natid', $id)->first();
        ActivityLogger::activity(auth()->user()->name . " has attempted to edit client " . $ssbDetail->natid . " with SSB Detail ID: " . $ssbDetail->id);

        $validator = Validator::make($request->all(),
            [
                'dateJoined'                  => 'date',
                'ecnumber'                  => 'unique:ssb_details,ecnumber,'.$ssbDetail->id,
            ],
            [
                'dateJoined.required'           => 'Date joined must be a valid date.',
                'ecnumber.unique'           => 'This EC Number is already associated to another employee',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ssbDetail->profession = $request['profession'];
        $ssbDetail->sourcesOfIncome = $request['sourcesOfIncome'];
        $ssbDetail->currentNetSalary = $request['currentNetSalary'];
        $ssbDetail->grossSalary = $request['grossSalary'];
        $ssbDetail->hr_contact_name = $request['hr_contact_name'];
        $ssbDetail->hr_position = $request['hr_position'];
        $ssbDetail->hr_email = $request['hr_email'];
        $ssbDetail->hr_telephone = $request['hr_telephone'];
        $ssbDetail->ecnumber = $request['ecnumber'];
        $ssbDetail->payrollAreaCode = $request['payrollAreaCode'];
        $ssbDetail->dateJoined = $request['dateJoined'];
        $ssbDetail->accountType = $request['accountType'];
        $ssbDetail->yearsWithCurrentBank = $request['yearsWithCurrentBank'];
        $ssbDetail->otherBankAccountName = $request['otherBankAccountName'];
        $ssbDetail->otherBankAccountNumber = $request['otherBankAccountNumber'];
        $ssbDetail->otherBankName = $request['otherBankName'];
        $ssbDetail->bankStatement = $request['bankStatement'];
        $ssbDetail->highestQualification = $request['highestQualification'];
        $ssbDetail->maidenSurname = $request['maidenSurname'];
        $ssbDetail->offerLetter = $request['offerLetter'];
        $ssbDetail->spouseEmployer = $request['spouseEmployer'];
        $ssbDetail->spouseName = $request['spouseName'];
        $ssbDetail->spousePhoneNumber = $request['spousePhoneNumber'];

        $ssbDetail->save();

        if ($ssbDetail->save()) {
            ActivityLogger::activity(auth()->user()->name . " has modified client " . $ssbDetail->natid . " with SSB Detail ID: " . $ssbDetail->id);
        }

        return redirect()->back()->with('success', 'Info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SsbDetail  $ssbDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(SsbDetail $ssbDetail)
    {
        //
    }
}
