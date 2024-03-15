<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Client;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\MusoniRecord;
use App\Models\User;
use Carbon\Carbon;
use CURLFile;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OldMutualController extends Controller
{
    public function listLoanProducts(){
        $details = Http::withHeaders([
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'])
            ->get('https://demo.musonisystem.com:8443/api/v1/loanproducts/')
            ->body();

        $arr=json_decode($details, TRUE);
        dd($details);
        return view('oldmutual.list-loan-products', compact('arr'));
    }

    public function getAllClients(){
        $allClientsDetails = Http::withHeaders([
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'])
            ->get('https://demo.musonisystem.com:8443/api/v1/clients/')
            ->body();

        $arr=json_decode($allClientsDetails, TRUE);

        return view('oldmutual.old-mutual-clients', compact('arr'));
    }

    public function gettingSingleMutualClient(){
        return view('oldmutual.mutual-client-info');
    }

    public function getASingleClient(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'client_id'                  => 'required',
            ],
            [
                'client_id.required'       => 'I need the client ID to perform this check.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $singleClientDetails = Http::withHeaders([
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'])
            ->get('https://demo.musonisystem.com:8443/api/v1/clients/'.$request->input('client_id'))
            ->body();

        $singleClientResp=json_decode($singleClientDetails, TRUE);
        $message = 'Client Info fetched as of '. now();
        return view('oldmutual.mutual-client-info', compact('singleClientResp', 'message'));

    }

    public function pendingOldMutual(){
        $kycs = DB::table('clients as c')
            ->join('loans as l', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('c.id','l.id as lid','c.first_name','c.last_name','c.natid','c.mobile','k.status')
            ->where('c.reds_number','=', null)
            ->where('l.loan_number','=',null)
            ->where('k.status','=',false)
            ->orderByDesc('c.created_at')
            ->distinct()
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('c.deleted_at', '=',null)
            ->get();

        return view('oldmutual.pending-oldmutual', compact('kycs'));
    }

    public function addAdditionalMusoniInfo($id,$loanId){
        $client = Client::findOrFail($id);
        $client->flag = 'MUSONI';
        $client->save();

        $kyc = Kyc::where('natid',$client->natid)->first();
        $kyc->status = true;
        $kyc->reviewer = auth()->user()->name;
        $kyc->save();

        $bank = Bank::where('id', $kyc->bank)->first();

        return view('oldmutual.additional-musoni', compact('client','kyc','bank','loanId'));
    }

    public function sendClientToMusoni($id,$loanId){
        $headers = [
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'];
        $client = Client::findOrFail($id);
        $clientKyc = Kyc::where('natid',$client->natid)->firstOrFail();
        $kyc = Kyc::where('natid',$client->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();

        if ($client->gender == 'Male'){
            $gender = 218;
        } else {
            $gender = 219;
        }

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        }

        $singleClientDetails = Http::withHeaders($headers)
            ->post('https://demo.musonisystem.com:8443/api/v1/clients',[
                'officeId' => Config::get('configs.OLD_MUTUAL_OFFICEID'),
                'savingsProductId'=> 1,
                'firstname' => $client->first_name,
                'lastname' => $client->last_name,
                'externalId' => $client->id,
                'genderId' => $gender,
                'dateOfBirth' => date('d F Y', strtotime($client->dob)),
                'mobileNo' => '0'.$client->mobile,
                'emailAddress' => $client->email,
                'dateFormat' => 'dd MMMM yyyy',
                'locale' => 'en',
                'active' => false,
                'staffId' => Config::get('configs.ESHAGI_LOANOFFICER')
            ])
            ->body();

        $singleClientResp=json_decode($singleClientDetails, TRUE);

        if (!isset($singleClientResp['clientId'])){
            if (isset($singleClientResp['defaultUserMessage'])){
                return redirect()->back()->with('error', '1'.$singleClientResp['errors'][0]['defaultUserMessage']);
            }
        }
        DB::table("loans")
            ->where("id", $loanId)
            ->update(['loan_status' => 11, 'updated_at' => now()]);

        return redirect('client-mandatory/'.$singleClientResp['clientId'].'/'.$client->id)->with('success', 'Client record has been posted to Musoni, with CLIENT ID: '.$singleClientResp['clientId']);
    }

    public function sendClientMandatory($id, $clientId){
        $headers = [
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'];

        $client = Client::findOrFail($clientId);
        $clientKyc = Kyc::where('natid',$client->natid)->firstOrFail();

        $addKycDetails = Http::withHeaders($headers)
            ->post('https://demo.musonisystem.com:8443/api/v1/datatables/ml_client_details/'.$id.'?tenantIdentifier=oldmutual',[
                "town"=> $client->city,
                "address"=> $client->house_num.' '.$client->street.' '.$client->surburb,
                "MaritalStatus_cv_maritalStatus"=> $client->marital_state,
                "Country7"=> "ZIMBABWE",
                "locale"=> "en",
                "Identification_7"=> str_replace("-","",$client->natid),
                "Document Type_cd_Document_Type8"=> "397",
                "EC_NUMBER9"=> $client->ecnumber,
                "Title_cd_Title10"=> "573"
            ])
            ->body();
        $clientKycResponse=json_decode($addKycDetails, TRUE);

        if (!isset($clientKycResponse['clientId'])){
            return redirect('post-client')->with('error', '2'.$clientKycResponse['defaultUserMessage']);
        }

        return redirect('client-business-info/'.$clientKycResponse['clientId'].'/'.$client->id)->with('success', 'Client Details have been recorded.');
    }

    public function sendClientBusinessInfo($id, $clientId){
        $headers = [
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'];

        $client = Client::findOrFail($clientId);
        $clientKyc = Kyc::where('natid',$client->natid)->firstOrFail();
        $musKyc = MusoniRecord::where('natid',$client->natid)->firstOrFail();

        $addEmployDetails = Http::withHeaders($headers)
            ->post('https://demo.musonisystem.com:8443/api/v1/datatables/ml_client_business/'.$id.'?tenantIdentifier=oldmutual',[
                "businessName"=> "SALARY BASED ",
                "BusinessType_cv_businessType"=> $musKyc->business_type,
                "dateFormat"=> "dd MMMM yyyy",
                "businessStartDate"=> date('d F Y', strtotime($musKyc->business_start)),
                "address"=> $musKyc->bus_address,
                "city"=> $musKyc->bus_city,
                "country"=> "ZIMBABWE",
                "postalCode"=> "+263",
                "Name_Of_Employer10"=> $client->employer,
                "Job_Title11"=> $musKyc->job_title,
                "Engagement_Date12"=> date('d F Y', strtotime($client->created_at)),
                "locale"=> "en",
                "Net_Income13"=> $client->salary
            ])
            ->body();
        $employDetailsResponse=json_decode($addEmployDetails, TRUE);

        if (!isset($employDetailsResponse['clientId'])){
            return redirect('post-client')->with('error', '3'.$employDetailsResponse['defaultUserMessage']);
        }

        return redirect('client-kin-info/'.$employDetailsResponse['clientId'].'/'.$client->id)->with('success', 'Client Employment Details have been captured.');
    }

    public function sendClientNextKinInfo($id, $clientId){
        $headers = [
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'];

        $client = Client::findOrFail($clientId);
        $clientKyc = Kyc::where('natid',$client->natid)->firstOrFail();
        $musKyc = MusoniRecord::where('natid',$client->natid)->firstOrFail();

        $addKinDetails = Http::withHeaders($headers)
            ->post('https://demo.musonisystem.com:8443/api/v1/datatables/ml_client_next_of_kin/'.$id.'?tenantIdentifier=oldmutual',[
                "name"=> $clientKyc->kin_fname.' '.$clientKyc->kin_lname,
                "dateFormat"=> "dd MMMM yyyy",
                "dateOfBirth"=> date('d F Y', strtotime($client->dob)),
                "address"=> $musKyc->kin_address,
                "city"=> $musKyc->kin_city,
                "phoneNumber"=> $clientKyc->kin_number,
                "GuarantorRelationship_cd_relation_to_client"=> $musKyc->kin_relationship,
                "Country9"=> "ZIMBABWE",
                "locale"=> "en"
            ])
            ->body();
        $nextKinResponse=json_decode($addKinDetails, TRUE);

        $client->reds_id = $nextKinResponse['clientId'];
        $client->save();

        if ($client->save()){
            DB::table("kycs")
                ->where("natid", $client->natid)
                ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

            $activateClientDetails = Http::withHeaders($headers)
                ->post('https://demo.musonisystem.com:8443/api/v1/clients/'.$id.'?command=activate&tenantIdentifier=oldmutual',[
                    "locale"=> "en",
                    "dateFormat"=> "dd MMMM yyyy",
                    "activationDate"=> date('d F Y', strtotime(now()))
                ])
                ->body();
            $activationResponse=json_decode($activateClientDetails, TRUE);

            if (!isset($activationResponse['clientId'])){
                return redirect('post-client')->with('error', '4'.$activationResponse['defaultUserMessage']);
            }
        }
        return redirect('post-client')->with('success',"Client has been activated in Musoni, with CLIENT ID: ".$activationResponse['clientId']);

    }

    public function getKycsToUpload($id, $loanId){
        $client = Client::findOrFail($id);
        $client->flag = 'MUSONI';
        $client->save();

        $kyc = Kyc::where('natid',$client->natid)->first();
        $kyc->status = true;
        $kyc->reviewer = auth()->user()->name;
        $kyc->save();

        $bank = Bank::where('id', $kyc->bank)->first();
        $musoniKyc = MusoniRecord::where('natid', $client->natid)->firstOrFail();

        return view('oldmutual.verify-kyc-docs', compact('client','kyc','bank','loanId','musoniKyc'));
    }

    public function postKycDocs(Request $request){

        $normalTimeLimit = ini_get('max_execution_time');
        ini_set('max_execution_time', 600);

        $headers = [
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br'
        ];
        $client = Client::where('reds_id',$request->input('clientid'))->firstOrFail();
        $kyc = Kyc::where('natid',$client->natid)->firstOrFail();
        //dd(file_get_contents(asset('nationalids/'.$kyc->national_pic)));
        //if ($request->hasFile('natid') && $request->file('natid')->isValid()) {
        // get Illuminate\Http\UploadedFile instance
        //$image = $request->file('natid');

//        $sendPassPhoto = Http::withHeaders($headers)
//            //->attach('PassportPhoto',fopen(asset('nationalids/'.$kyc->national_pic), 'r'), $kyc->national_pic)
//            ->post('https://demo.musonisystem.com:8443/api/v1/clients/'.$client->reds_id.'/documents?tenantIdentifier=oldmutual',
//                [
//                    [
//                        'name'     => 'file',
//                        'contents' => fopen(asset('nationalids/'.$kyc->national_pic), 'r'),
//                    ],
//                    [
//                        'name'     => 'name',
//                        'contents' => 'Pphoto',
//                    ],
//                ]
//            )
//            ->body();
//        //dd($sendPassPhoto);
//        $pphotoResponse=json_decode($sendPassPhoto, TRUE);

//        if (!isset($pphotoResponse['clientId'])){
//            return redirect()->back()->with('error', '6'.$pphotoResponse['message']);
//        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://demo.musonisystem.com:8443/api/v1/clients/'.$client->reds_id.'/documents?tenantIdentifier=oldmutual',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'name' => $kyc->national_pic,
                'file'=> new CURLFILE(asset('nationalids/'.$kyc->national_pic))
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: '.'Basic '.Config::get('configs.OLD_MUTUAL_AUTH')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://demo.musonisystem.com:8443/api/v1/clients/10595/documents?tenantIdentifier=oldmutual',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'POST',
//            CURLOPT_POSTFIELDS => array('file' => new CURLFILE(file_get_contents(asset('nationalids/'.$kyc->national_pic))), 'name' => $kyc->national_pic),
//            CURLOPT_HTTPHEADER => array(
//                'Authorization: Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
//            ),
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
        dd($response);

//        $response = '';
//        foreach ($applications as $k => $application) {
//            $application_requirement['application_id'] = $application['application_id'];
//            $application_requirement['payment_item_id'] = $application['payment_item_id'];
//            //get the application id
//            $application_id = $application['application_id'];
//            //store files
//            $files[] = $application['file'];
//            //store data when looping
//            $data[$k] = $application_requirement;
//        }
//        //dd($application_requirement);
//        $response = Http::withHeaders(['Accept' => 'application/json']);
//        foreach ($files as $k => $file) {
//            $name = $file->getClientOriginalName();
//            $response = $response->attach('file[' . $k . ']', fopen($file, 'r'), $name);
//        }
//        $response = $response->post('http://localhost:8000/api/applications/submit_documents',
//            $application_requirement
//        );
//        $payment_response = json_decode($response);

//            $client = new Client();
//            $headers = [
//                'X-Fineract-Platform-TenantId' => 'oldmutual',
//                'Authorization' => 'Basic e3t1c2VybmFtZX19Ont7cGFzc3dvcmR9fQ=='
//            ];
//            $options = [
//                'multipart' => [
//                    [
//                        'name' => 'file',
//                        'contents' => Utils::tryFopen(asset('nationalids/'.$kyc->national_pic), 'r'),
//                        'filename' => $kyc->national_pic,
//                        'headers'  => [
//                            'Content-Type' => '<Content-type header>'
//                        ]
//                    ],
//                    [
//                        'name' => 'name',
//                        'contents' => $kyc->national_pic
//                    ]
//                ]];
//            $request = new Request('POST', 'https://demo.musonisystem.com:8443/api/v1/clients/10595/documents?tenantIdentifier=oldmutual', $headers);
//            $res = $client->sendAsync($request, $options)->wait();
//            echo $res->getBody();

        // post request with attachment
//            Http::attach('attachment', file_get_contents($image), 'image.jpg')
//                ->post('example.com/v1/blog/store', $request->all());

//            $sendNatId = Http::withHeaders($headers)
//                ->attach('file', file_get_contents(asset('nationalids/'.$kyc->national_pic)), 'national_id.'.$image->getClientOriginalExtension())
//                ->asForm()
//                ->post('https://demo.musonisystem.com:8443/api/v1/clients/'.$client->red_id.'/documents?tenantIdentifier=oldmutual',[
//                    "name"=> "NationalID"
//                ])
//                ->body();
//            $activationResponse=json_decode($sendNatId, TRUE);
//
//            if (!isset($activationResponse['clientId'])){
//                return redirect()->back()->with('error', '5'.$activationResponse['message']);
//            }
        //}

        if ($request->hasFile('pphoto') && $request->file('pphoto')->isValid()) {
            // get Illuminate\Http\UploadedFile instance
            $image = $request->file('pphoto');

            $sendPassPhoto = Http::withHeaders($headers)
                ->attach('',fopen(asset('nationalids/'.$kyc->national_pic), 'r'), $kyc->national_pic)
                ->post('https://demo.musonisystem.com:8443/api/v1/clients/'.$client->red_id.'/documents?tenantIdentifier=oldmutual',[
                    "file"=> file_get_contents($image),
                    "name"=> "PassportPhoto"
                ])
                ->body();
            $pphotoResponse=json_decode($sendPassPhoto, TRUE);

            if (!isset($pphotoResponse['clientId'])){
                return redirect()->back()->with('error', '6'.$pphotoResponse['message']);
            }
        }

        if ($request->hasFile('payslip') && $request->file('payslip')->isValid()) {
            // get Illuminate\Http\UploadedFile instance
            $image = $request->file('payslip');

            $sendPayslip = Http::withHeaders($headers)
                ->post('https://demo.musonisystem.com:8443/api/v1/clients/'.$client->red_id.'/documents?tenantIdentifier=oldmutual',[
                    "file"=> file_get_contents($image),
                    "name"=> "Payslip"
                ])
                ->body();
            $payslipResponse=json_decode($sendPayslip, TRUE);

            if (!isset($payslipResponse['clientId'])){
                return redirect()->back()->with('error', '7'.$payslipResponse['message']);
            }
        }

        ini_set('max_execution_time', $normalTimeLimit);

        //return redirect('post-client')->with('success',"Client has been activated in Musoni, with CLIENT ID: ".$activationResponse['clientId']);
        return redirect('post-client')->with('success',"Client documents uploaded successfully.");
    }

    public function getLoanToSendMusoni(Request $request){
        return view('oldmutual.process-musoni-loan');
    }

    public function postLoanToMusoni($id){
        $loan = Loan::findOrFail($id);
        $client = Client::where('id', $loan->client_id)->first();
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bank = Bank::where('id',$kyc->bank)->first();
        $creator = User::where('name',$client->creator)->first();

//        $validator = Validator::make(
//            $request->all(),
//            [
//                'locale'                  => 'required',
//                'dateFormat'            => 'required',
//                'loanType'             => 'required',
//                'clientId'                 => 'required',
//                'groupId'                 => 'required',
//                'calendarId'                 => 'required',
//                'syncDisbursementWithMeeting'              => 'required',
//                'productId' => 'required',
//                'accountNo'                  => 'required',
//                'externalId'       => 'required',
//                'fundId'       => 'required',
//                'loanOfficerId'       => 'required',
//                'principal'       => 'required',
//                'loanTermFrequency'       => 'required',
//                'loanTermFrequencyType'       => 'required',
//                'numberOfRepayments'       => 'required',
//                'repaymentEvery'       => 'required',
//                'repaymentFrequencyType'       => 'required',
//                'interestType'       => 'required',
//                'interestCalculationPeriodType'       => 'required',
//                'amortizationType'       => 'required',
//                'expectedDisbursementDate'       => 'required',
//                'submittedOnDate'       => 'required',
//                'transactionProcessingStrategyId'       => 'required',
//            ]
//        );
//
//        if ($validator->fails()) {
//            return back()->withErrors($validator)->withInput();
//        }

        $headers = [
            'Authorization' => 'Basic '.Config::get('configs.OLD_MUTUAL_AUTH'),
            'Content-Type' => 'application/json',
            'X-Fineract-Platform-TenantId' => Config::get('configs.OLD_MUTUAL_TENANTID'),
            'Accept' => 'application/json'];

        //$client = Client::findOrFail($clientId);
        //$clientKyc = Kyc::where('natid',$client->natid)->firstOrFail();
        if($loan->paybackPeriod < 7){
            $chargeId = 1;
            $insuranceCharge = 0.85;
        } else {
            $chargeId = 4;
            $insuranceCharge = 1.35;
        }

        $postLoanApplication = Http::withHeaders($headers)
            ->post('https://demo.musonisystem.com:8443/api/v1/loans?tenantIdentifier=oldmutual',[
                "locale"=> "en_GB",
                "dateFormat"=> "dd MMMM yyyy",
                "loanType"=> "individual",
                "clientId"=> $client->reds_id,
                "groupId"=> null,
                "calendarId"=> 1,
                "syncDisbursementWithMeeting"=> false,
                "productId"=> 33,
                "accountNo"=> null,
                "externalId"=> null,
                "fundId"=> 1,
                "loanOfficerId"=> Config::get('configs.ESHAGI_LOANOFFICER'),
                //"loanPurposeId"=> 1,
                "principal"=> $loan->amount,
                "loanTermFrequency"=> $loan->paybackPeriod,
                "loanTermFrequencyType"=> 2,
                "numberOfRepayments"=> $loan->paybackPeriod,
                "repaymentEvery"=> 1,
                "repaymentFrequencyType"=> 2,
                "interestType"=> 0,
                "interestCalculationPeriodType"=> 1,
                "interestRatePerPeriod"=> getOldMutualInterestRate(),
                "interestRateDifferential"=> 2.5,
                "amortizationType"=> 1,
                "expectedDisbursementDate"=> date('d F Y', strtotime(now())),
                "interestChargedFromDate"=> date('d F Y', strtotime($loan->created_at)),
                "repaymentsStartingFromDate"=> date('d F Y', strtotime(Carbon::now()->addWeeks(4))),
                "graceOnPrincipalPayment"=> 0,
                "graceOnInterestPayment"=> 0,
                "graceOnInterestCharged"=> 0,
                "graceOnArrearsAgeing"=> 0,
                "inArrearsTolerance"=> 0,
                "submittedOnDate"=> date('d F Y', strtotime(now())),
                "submittedOnNote"=> "Loan application completed and submitted for approval by manager.",
                "allowPartialPeriodInterestCalcualtion"=> false,
                "transactionProcessingStrategyId"=> 1,
                "charges"=> []
                //"linkAccountId"=> 1
//                "charges"=> array([
//                    "chargeId"=> 32,
//                    "amount"=> 16.00,
//                    //"chargeTimeType"=> 2,
//                    //"chargeCalculationType"=> 2,
//                    //"dueDate"=> "16-11-2020"
//                ],
//                    [
//                        "chargeId"=> $chargeId,
//                        "amount"=> $insuranceCharge,
////                    "chargeTimeType"=> 2,
////                    "chargeCalculationType"=> 2,
////                    "dueDate"=> "16-11-2020"
//                    ]),
            ])
            ->body();
        $postLoanResponse=json_decode($postLoanApplication, TRUE);

        if(!isset($postLoanResponse['clientId'])){
            return redirect('start-musoni-loan')->with('error', '3'.$postLoanResponse['errors'][0]['defaultUserMessage']);
        }elseif (isset($postLoanResponse['status']) AND $postLoanResponse['status'] == 500){
            return redirect()->back()->with('error', '3'.$postLoanResponse['exception'].' : '.$postLoanResponse['message']);
        } elseif(isset($postLoanResponse['error'])){
            return redirect()->back()->with('error', '3'.$postLoanResponse['exception'].' : '.$postLoanResponse['message']);
        }

        if (isset($postLoanResponse['loanId'])){
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_number' => $postLoanResponse['loanId'], 'loan_status' => 12, 'updated_at' => now()]);

            $postLoanCollateral = Http::withHeaders($headers)
                ->post('https://demo.musonisystem.com:8443/api/v1/datatables/m_loan_collateral/'.$postLoanResponse['loanId'].'?tenantIdentifier=oldmutual',[
                    "value"=> number_format(($loan->amount*2), 2),
                    "description"=> "SALARY",
                    "Other6"=> "",
                    "dateFormat"=> "dd-MM-yyyy",
                    "locale"=> "en_GB",
                    "type_cv_id"=> "425",
                ])
                ->body();
            $postLoanCollaResponse=json_decode($postLoanCollateral, TRUE);

        }


        return redirect()->back()->with('success', 'Loan has been sent to Musoni for processing.');

    }
}
