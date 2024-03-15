<?php

namespace App\Http\Controllers;

use App\Models\Funder;
use App\Models\Localel;
use Illuminate\Http\Request;
use Validator;

class FunderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funders = Funder::join('localels', 'localels.id','=','funders.locale_id')
        ->select('localels.id','localels.country',
            'funders.id as fid','funders.funder',
            'funders.funder_acc_num','funders.contact_fname',
            'funders.contact_lname','funders.email',
            'funders.tel_no','funders.support_email',
            'funders.created_at',
            'funders.interest_rate_percentage',
            'funders.max_repayment_month'
        )
        ->get();
        return view('funders.funders', compact('funders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $localels = Localel::all();
        return view('funders.add-funder', compact('localels'));
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
                'funder'        => 'required|max:255|unique:funders',
                'funder_acc_num'        => 'required|max:255|unique:funders',
                'contact_fname'     => 'required',
                'contact_lname'     => 'required',
                'email'     => 'required|unique:funders|email',
                'tel_no'     => 'required|unique:funders',
                'support_email'     => 'required|email',
                'max_repayment_month' => 'required',
                'interest_rate_percentage' =>'required',

            ],
            [
                'locale_id.required'        => 'Where is the funder based?',
                'funder.required'          => 'What is the name of the funder?',
                'funder.unique'          => 'This funder is already listed with eShagi.',
                'funder_acc_num.required'          => 'What is the funder account number?',
                'funder_acc_num.unique'          => 'This funder account number is already listed with eShagi.',
                'contact_fname.required'       => 'Funder\'s point person first name is required',
                'contact_lname.required'       => 'Funder\'s point person surname is required',
                'email.required'       => 'Funder email address is required.',
                'email.unique'       => 'Funder email address is already within the system.',
                'email.email'       => 'Funder email must be of a valid format.',
                'tel_no.required'       => 'Funder telephone number is required.',
                'tel_no.unique'       => 'Funder telephone number is has to be unique in the system.',
                'support_email.required'       => 'Funder support email address is required.',
                'support_email.email'       => 'Funder support email must be of a valid format.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
                'locale_id'      => $request->input('locale_id'),
                'funder'         => $request->input('funder'),
                'funder_acc_num' => $request->input('funder_acc_num'),
                'contact_fname'  => $request->input('contact_fname'),
                'contact_lname'  => $request->input('contact_lname'),
                'email'          => $request->input('email'),
                'tel_no'         => $request->input('tel_no'),
                'support_email'  => $request->input('support_email'),
                'max_repayment_month' => (int)$request->input('max_repayment_month'),
                'interest_rate_percentage' => $request->input('interest_rate_percentage'),
        ];

        if($request->input('require_deposit') == 1){
            $data[] =  [
                'require_deposit'=>true,
                'initial_deposit_percentage'=> $request->input('initial_deposit_percentage')
            ];
        }

        $funder = Funder::create($data);
        $funder->save();
        return redirect('funders')->with('success', 'Funder added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function show(Funder $funder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function edit(Funder $funder)
    {
        $localels = Localel::all();

        return view('funders.edit-funder', compact('funder', 'localels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Funder $funder)
    {
        $validator = Validator::make($request->all(),
            [
                'locale_id'       => 'required',
                'funder'        => 'required|max:255|unique:funders,funder,'.$funder->id,
                'funder_acc_num'        => 'required|max:255|unique:funders,funder_acc_num,'.$funder->id,
                'contact_fname'     => 'required',
                'contact_lname'     => 'required',
                'email'     => 'required|email|unique:funders,email,'.$funder->id,
                'tel_no'     => 'required|unique:funders,tel_no,'.$funder->id,
                'support_email'     => 'required|email',
            ],
            [
                'locale_id.required'        => 'Where is the funder based?',
                'funder.required'          => 'What is the name of the funder?',
                'funder.unique'          => 'This funder is already listed with eShagi.',
                'funder_acc_num.required'          => 'What is the funder account number?',
                'funder_acc_num.unique'          => 'This funder account number is already listed with eShagi.',
                'contact_fname.required'       => 'Funder\'s point person first name is required',
                'contact_lname.required'       => 'Funder\'s point person surname is required',
                'email.required'       => 'Funder email address is required.',
                'email.unique'       => 'Funder email address is already within the system.',
                'email.email'       => 'Funder email must be of a valid format.',
                'tel_no.required'       => 'Funder telephone number is required.',
                'tel_no.unique'       => 'Funder telephone number is has to be unique in the system.',
                'support_email.required'       => 'Funder support email address is required.',
                'support_email.email'       => 'Funder support email must be of a valid format.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $funder->locale_id = $request->input('locale_id');
        $funder->funder = $request->input('funder');
        $funder->funder_acc_num = $request->input('funder_acc_num');
        $funder->contact_fname = $request->input('contact_fname');
        $funder->contact_lname = $request->input('contact_lname');
        $funder->email = $request->input('email');
        $funder->tel_no = $request->input('tel_no');
        $funder->support_email = $request->input('support_email');
        $funder->max_repayment_month = $request->input('max_repayment_month');
        $funder->interest_rate_percentage = $request->input('interest_rate_percentage');

        if($request->input('interest_rate_percentage') == 1 )
            $funder->require_deposit_percentage = $request->input('interest_rate_percentage');
            $funder->initial_deposit_percentage = $request->input('initial_deposit');
        $funder->save();

        return back()->with('success', 'Funder info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Funder  $funder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funder $funder)
    {
        $funder->delete();

        return redirect('funders')->with('success', 'Funder deleted successfully.');
    }

    public function getFunder($id)
    {
        return response(Funder::findOrFail($id), 200);
    }
}
