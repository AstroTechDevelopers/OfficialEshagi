<?php

namespace App\Http\Controllers;

use App\Models\Zambian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CRBController extends Controller
{
    public function postClientToCrb($id){
        $client = Zambian::where('id','=',$id)->firstOrFail();

        $authDetails = Http::post('https://secure3.crbafrica.com/duv2/login',[
            'infinityCode' => Config::get('configs.INFINITY_CODE'),
            'username' => Config::get('configs.CRB_USERNAME'),
            'password' => Config::get('configs.CRB_PASSWORD')])
            ->body();
        $resp=json_decode($authDetails, TRUE);
        if(isset($resp['error'])){
            return redirect()->back()->with('error', 'An error occurred while authenticating eShagi with CRB: '. $resp['message']);
        }

        dd($resp);

        $details = Http::withToken($resp['token'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'])
            ->get(''.$id)
            ->body();

        $resp=json_decode($details, TRUE);


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

        $client->ld_borrower_id =$resp['response']['borrower_id'];
        $client->save();

        return redirect()->back()->with('success', 'Client posted to Loan Disk successfully.');

    }
}
