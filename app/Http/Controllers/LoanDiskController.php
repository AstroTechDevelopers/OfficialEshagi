<?php

namespace App\Http\Controllers;

use App\Models\ZambiaLoan;
use App\Models\Zambian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LoanDiskController extends Controller
{

    public function postClientLoanDisk($id){
        $client = Zambian::where('id','=',$id)->firstOrFail();

        if ($client->ld_borrower_id) {
            $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->put('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower',[
                    'borrower_id' => $client->ld_borrower_id,
                    'borrower_unique_number' => $client->nrc,
                    'borrower_firstname' => $client->first_name,
                    'borrower_lastname' => $client->last_name,
                    'borrower_business_name' => $client->business_employer,
                    'borrower_country' => 'ZM',
                    'borrower_title' => $client->title,
                    'borrower_working_status' => $client->work_status,
                    'borrower_gender' => $client->gender,
                    'borrower_mobile' => $client->mobile,
                    'borrower_dob' => date_format($client->dob, 'd/m/Y'),
                    'borrower_description' => $client->description ?? $client->first_name.' '.$client->middle_name.' '.$client->last_name,
                    'custom_field_11543' => $client->institution,
                    'custom_field_11302' => $client->ec_number,
                    'custom_field_11303' => $client->department,
                    'custom_field_11085' =>  $client->kin_name,
                    'custom_field_11083' => $client->kin_relationship,
                    'custom_field_11082' => $client->kin_address,
                    'custom_field_11084' => $client->kin_number,
                    'custom_field_11789' => $client->bank_name,
                    'custom_field_11788' => $client->bank_account,
                    'custom_field_11790' => $client->branch,
                ])
                ->body();

            $resp=json_decode($details, TRUE);

            if (isset($resp['response']['Errors'])){
                return redirect()->back()->with('errors', collect($resp['response']['Errors']));
            }
        }
        elseif (is_null($client->ld_borrower_id) AND $client->officer_stat == true AND auth()->user()->hasRole('manager')){
            $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower',[
                    'borrower_unique_number' => $client->nrc,
                    'borrower_firstname' => $client->first_name,
                    'borrower_lastname' => $client->last_name,
                    'borrower_business_name' => $client->business_employer,
                    'borrower_country' => 'ZM',
                    'borrower_title' => $client->title,
                    'borrower_working_status' => $client->work_status,
                    'borrower_gender' => $client->gender,
                    'borrower_mobile' => $client->mobile,
                    'borrower_dob' => date_format($client->dob, 'd/m/Y'),
                    'borrower_description' => $client->description ?? $client->first_name.' '.$client->middle_name.' '.$client->last_name,
                    'custom_field_11543' => $client->institution,
                    'custom_field_11302' => $client->ec_number,
                    'custom_field_11303' => $client->department,
                    'custom_field_11085' =>  $client->kin_name,
                    'custom_field_11083' => $client->kin_relationship,
                    'custom_field_11082' => $client->kin_address,
                    'custom_field_11084' => $client->kin_number,
                    'custom_field_11789' => $client->bank_name,
                    'custom_field_11788' => $client->bank_account,
                    'custom_field_11790' => $client->branch,
                ])
                ->body();

            $resp=json_decode($details, TRUE);

            if (isset($resp['response']['Errors'])){
                return redirect()->back()->with('errors', collect($resp['response']['Errors']));
            }

            $client->ld_borrower_id =$resp['response']['borrower_id'];
            $client->manager_stat = true;
            $client->manager = auth()->user()->name;
            $client->save();
        }
        else {
            return redirect()->back()->with('error', 'Make sure Loans Officer has reviewed KYC first');
        }

        return redirect()->back()->with('success', 'Client posted to Loan Disk successfully.');
    }

    public function getLoansToSendToLoanDisk($id){
        $loan = ZambiaLoan::where('id','=',$id)->firstOrFail();
        $client = Zambian::where('id','=',$loan->zambian_id)->firstOrFail();

        if (is_null($client->ld_borrower_id)){
            return redirect()->back()->with('error','Client was not uploaded to LoanDisk successfully');
        }

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/loan',[
                'loan_product_id' => $loan->loan_product_id,
                'borrower_id' => $client->ld_borrower_id,
                'loan_application_id' => 'ZAM'.$loan->loan_application_id,
                'loan_disbursed_by_id' => $loan->loan_disbursed_by_id,
                'loan_principal_amount' => $loan->loan_principal_amount,
                'loan_released_date' => date_format($loan->loan_released_date, 'd/m/Y'),
                'loan_interest_method' => $loan->loan_interest_method,
                'loan_interest_type' => $loan->loan_interest_type,
                'loan_interest_period' => $loan->loan_interest_period,
                'loan_interest' => $loan->loan_interest,
                'loan_duration_period' => $loan->loan_duration_period,
                'loan_duration' => $loan->loan_duration,
                'loan_payment_scheme_id' => $loan->loan_payment_scheme_id,
                'loan_num_of_repayments' => $loan->loan_num_of_repayments,
                'loan_status_id' =>  $loan->loan_status_id,
                'loan_decimal_places' => $loan->loan_decimal_places ?? 'round_off_to_two_decimal',
                'loan_description' => $loan->loan_description,
                'custom_field_11133' => date_format($loan->cf_11133_approval_date, 'd/m/Y'),
                'custom_field_11353' => $loan->cf_11353_installment,
                'custom_field_11132' => $loan->cf_11132_qty,
                'custom_field_11130' => $loan->cf_11130_sales_rep,
                'custom_field_11136' => $loan->cf_11136_account_num,
                'custom_field_11134' => $loan->cf_11134_bank,
                'custom_field_11135' => $loan->cf_11135_branch,
            ])
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('errors', collect($resp['response']['Errors']));
        }

        $loan->ld_loan_id = $resp['response']['loan_id'];
        $loan->loan_decimal_places = 'round_off_to_two_decimal';
        $loan->save();

        return redirect()->back()->with('success','Loan uploaded to Loan Disk successfully.');

    }

    public function loanDiskFormLookup(){
        return view('kycs.loandisk-check');
    }

    public function getLoanDiskCustomerInfo(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'uniqueid'                  => 'required',
            ],
            [
                'uniqueid.required'       => 'I need the ID Number to perform this check.',
            ]
        );

        if ($validator->fails()) {
            return redirect('loan-disk-check')->withErrors($validator)->withInput();
        }
        $client = Zambian::where('nrc', $request->input('uniqueid'))->first();

        if (is_null($client)){
            return redirect('loan-disk-check')->with('error', 'I don\'t do random API checks, hey. This client should be in eShagi first.');
        }

        if (is_null($client->ld_borrower_id)){
            $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->get('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower/borrower_unique_number/'.$request->input('uniqueid'))
                ->body();

            $resp=json_decode($details, TRUE);

            if (is_null($resp)){
                return redirect('loan-disk-check')->with('error', 'I didn\'t find client record on LoanDisk.');
            }

            if (isset($resp['response']['Errors'])){
                return redirect('loan-disk-check')->with('errors', collect($resp['response']['Errors']));
            }

            if (isset($resp['error'])){
                return redirect('loan-disk-check')->with('error', $resp['error']['message']);
            }

            $response = $resp['response']['Results'][0][0];
            return view('kycs.loandisk-check', compact('response'));

        }
        else {
            $details = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
            ])
                ->put('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZAM_BRANCH_ID').'/borrower',[
                    'borrower_id' => $client->ld_borrower_id,
                    'borrower_unique_number' => $client->nrc,
                    'borrower_firstname' => $client->first_name,
                    'borrower_lastname' => $client->last_name,
                    'borrower_business_name' => $client->business_employer,
                    'borrower_country' => 'ZM',
                    'borrower_title' => $client->title,
                    'borrower_working_status' => $client->work_status,
                    'borrower_gender' => $client->gender,
                    'borrower_mobile' => $client->mobile,
                    'borrower_email' => $client->email,
                    'borrower_address' => $client->address,
                    'borrower_city' => $client->city,
                    'borrower_credit_score' => $client->credit_score,
                    'borrower_dob' => date_format($client->dob, 'd/m/Y'),
                    'borrower_description' => $client->description ?? $client->first_name.' '.$client->middle_name.' '.$client->last_name,
                    'custom_field_11543' => $client->institution,
                    'custom_field_11302' => $client->ec_number,
                    'custom_field_11303' => $client->department,
                    'custom_field_11085' =>  $client->kin_name,
                    'custom_field_11083' => $client->kin_relationship,
                    'custom_field_11082' => $client->kin_address,
                    'custom_field_11084' => $client->kin_number,
                    'custom_field_11789' => $client->bank_name,
                    'custom_field_11788' => $client->bank_account,
                    'custom_field_11790' => $client->branch,
                ])
                ->body();

            $resp=json_decode($details, TRUE);

            if (isset($resp['response']['Errors'])){
                return redirect()->back()->with('errors', collect($resp['response']['Errors']));
            }

            return redirect('loan-disk-check')->with('success','Client record synced with LoanDisk');
        }
    }
}
