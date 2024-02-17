<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DeviceLoan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();

        return view('payments.payments', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $loans = DB::table('device_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
            //->where('l.loan_status','=', 10)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('payments.make-payment', compact('loans'));
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
                'amount'       => 'required',
                'payment_method'       => 'required',
                'collection_date'       => 'required|date',
                'collector_id'       => 'required',
                'rem_schedule'       => 'required',
                'reference_num'       => 'required|unique:payments',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $loan = DeviceLoan::where('id','=',$request->input('loan_id'))->firstOrFail();
        $client = Client::where('id','=',$loan->client_id)->firstOrFail();

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZIM_USD_BRANCH').'/repayment',[
                'loan_id' => $loan->ld_loan_id,
                'repayment_amount' => $request->input('amount'),
                'loan_repayment_method_id' => $request->input('payment_method'),
                'collector_id' => $request->input('collector_id'),
                'repayment_collected_date' => date_format($request->input('collection_date'), 'd/m/Y'),
                'repayment_adjust_remaining_schedule' => $request->input('rem_schedule'),
                'repayment_description' => $request->input('description'),
            ])
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('error', 'Got this error from Loan Disk: '.$resp['response']['Errors'][0]);
        }

        $payment = Payment::create([
            'creator'             => auth()->user()->name,
            'locale'             => auth()->user()->locale,
            'ld_repayment_id'             => $resp['response']['repayment_id'],
            'ld_loan_id'             => $loan->ld_loan_id,
            'loan_id'             => $request->input('loan_id'),
            'amount'             => $request->input('amount'),
            'payment_method'             => $request->input('payment_method'),
            'collection_date'             => $request->input('collection_date'),
            'collector_id'             => $request->input('collector_id'),
            'rem_schedule'             => $request->input('rem_schedule'),
            'description'             => $request->input('description'),
            'reference_num'             => $request->input('reference_num'),
        ]);

        $payment->save();

        return redirect('payments')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->reference_num = 'DEL_'.$payment->reference_num;
        $payment->save();

        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }
}
