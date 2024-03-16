<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BotApplication;
use App\Models\Client;
use App\Models\DeviceLoan;
use App\Models\Eloan;
use App\Models\Funder;
use App\Models\Kyc;
use App\Models\Loan;
use App\Models\Localel;
use App\Models\Masetting;
use App\Models\MerchantKyc;
use App\Models\Partner;
use App\Models\SsbDetail;
use App\Models\User;
use App\Models\ZambiaLoan;
use App\Models\Zambian;
use App\Notifications\CbzRejectedKyc;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Illuminate\Support\Str;
use Kapouet\Notyf\Facades\Notyf;
use Mail;

class KycController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kycs = DB::table('kycs')
            ->select('users.first_name','users.last_name','kycs.id','kycs.natid','kycs.status','kycs.created_at')
            ->join('users','kycs.natid','=','users.natid')
            ->where('users.locale','=',auth()->user()->locale)
            ->get();

        return view('kycs.kycs', compact('kycs'));
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
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function show(Kyc $kyc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function edit(Kyc $kyc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kyc = Kyc::where('user_id', $id)->first();
        //print_r($kyc);die();
        ActivityLogger::activity(auth()->user()->name . " has attempted to edit client " . $kyc->natid . " with KYC ID: " . $kyc->id);

        $validator = Validator::make($request->all(),
            [
                'kin_title'                  => 'required',
                'kin_fname'            => 'required',
                'kin_lname'             => 'required',
                'kin_email'                 => 'nullable|email|max:255',
                'kin_work'                 => 'required',
                'kin_number'                 => 'required',
                'bank'                 => 'required',
                'bank_acc_name'                 => 'required',
                'branch'                 => 'required',
                'branch_code'                 => 'required',
                'acc_number'                 => 'required',
            ],
            [
                'kin_title.required'           => 'Please select a title.',
                'kin_fname.required'           => 'What is the first name for your next of kin?',
                'kin_lname.required'            => 'What is the last name for your next of kin?',
                'kin_email.email'                   => trans('auth.emailInvalid'),
                'kin_email.unique'                   => 'This email is already registered with eShagi for another Next of Kin.',
                'kin_work.required'                   => 'We need to know where they work.',
                'kin_number.required'                   => 'What is your next of kin\'s mobile number?',
                'bank.required'                   => 'Which bank do you use?',
                'bank_acc_name.required'                   => 'What in the name linked to the bank account?',
                'branch.required'                   => 'Please select your branch',
                'branch_code.required'                   => 'Please select your branch, if your branch code did not show',
                'acc_number.required'                   => 'Please state your account number',

            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = Client::where('natid',$kyc->natid)->firstOrFail();

        if ($client->mobile == $request['kin_number']) {
            return redirect()->back()->withInput()->with('error', 'You cannot use client mobile number as a next of kin number for client. Please use another number.');
        }

        $kyc->kin_title = $request['kin_title'];
        $kyc->kin_fname = $request['kin_fname'];
        $kyc->kin_lname = $request['kin_lname'];
        $kyc->kin_email = $request['kin_email'];
        $kyc->kin_work = $request['kin_work'];
        $kyc->kin_number = $request['kin_number'];
        $kyc->bank = $request['bank'];
        $kyc->bank_acc_name = $request['bank_acc_name'];
        $kyc->branch = $request['branch'];
        $kyc->branch_code = $request['branch_code'];
        $kyc->acc_number = $request['acc_number'];
        $kyc->kin_title = $request['kin_title'];

        $kyc->save();

        if ($kyc->save()) {
            ActivityLogger::activity(auth()->user()->name . " has modified client " . $kyc->natid . " with KYC ID: " . $kyc->id);
        }

        return redirect()->back()->with('success', 'KYC Info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kyc $kyc)
    {
        //
    }

    public function funderKycs(){
        if (auth()->user()->hasRole('admin')) {
            $kycs = DB::table('kycs as k')
                ->join('clients as c','k.natid','=','c.natid')
                ->select('c.first_name','c.last_name','c.reds_number','k.id','k.natid','k.status','k.created_at', 'k.reviewer')
                ->where('k.status', '=',true)
                ->get();
        } else {
            $user = User::where('natid', auth()->user()->natid)->first();
            $funder = Funder::where('funder_acc_num',$user->natid)->first();
            $kycs = DB::table('kycs as k')
                ->join('clients as c','k.natid','=','c.natid')
                ->join('loans as l',$funder->id,'=','l.funder_id')
                ->select('c.first_name','c.last_name','c.reds_number','k.id','k.natid','k.status','k.created_at', 'k.reviewer')
                ->where('c.locale_id','=',auth()->user()->locale)
                ->where('k.status', '=',true)
                ->get();
        }


        return view('kycs.funder-kycs', compact('kycs'));
    }

    public function getPendingKyc(){

        /*$kycs = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
            ->whereIn('l.loan_status',array(1, 2))
            ->where('c.reds_number','=', null)
            ->where('k.status', '=',false)
            ->orderByDesc('l.created_at')
            ->distinct()
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('l.deleted_at', '=',null)
            ->get();*/

        /*$kycs = DB::table('kycs as k')
        ->join('clients as c','k.natid','=','c.natid')
        ->select('c.first_name','c.last_name','c.natid', 'c.reds_number', 'k.id as kid', 'k.status','k.created_at')
        ->where('c.locale_id','=',auth()->user()->locale)
        ->get();*/
        /*$users = DB::table('kycs as k')
            ->leftJoin('clients as c', 'k.natid', '=', 'c.natid')
            ->select('c.first_name','c.last_name','c.natid', 'c.reds_number', 'k.id as kid', 'k.status','k.created_at')
			->where('clients.natid', 'IS NULL')
            ->get();*/

        $kycs = DB::table('kycs as k')
            ->leftJoin('clients as c', 'k.natid', '=', 'c.natid')
            ->select('c.first_name', 'c.last_name', 'c.natid', 'c.reds_number', 'k.id as kid', 'k.status', 'k.kyc_status', 'k.created_at')
            ->whereRaw("(k.national_stat = 0 OR k.passport_stat = 0 OR k.sign_stat = 0 OR k.payslip_stat = 0 OR k.emp_approval_stat = 0) AND k.kyc_status = 0 AND c.deleted_at IS NULL AND k.deleted_at IS NULL")
            ->get();


        return view('kycs.pending-kycs', compact('kycs'));
    }

    //public function evaluateKyc($id, $loanId){
    public function evaluateKyc($id){
        //echo 245;die();
        $kyc = Kyc::findOrFail($id);
        //echo 247;die();
        $bank = Bank::where('id',$kyc->bank)->first();
        //echo 249;die();
        $client = Client::where('natid',$kyc->natid)->first();
        //echo 251;die();
        //$loan = Loan::where('id', $loanId)->first();
        $loan = Loan::where('client_id', $client->id)->first();
        /*echo "<pre>";
        print_r($loan);
        echo "</pre>";*/
        //echo 254;die();
        //echo $kyc->user_id;print_r($loan);die();
        //$loan = Loan::where('user_id', $kyc->user_id)->whereIn('loan_status',array(1, 2))->where('deleted_at', '=',null)->first();
        /*$loan = DB::table('loans')
            ->whereIn('loan_status',array(1, 2))
            ->where('user_id','=', $kyc->user_id)
            ->distinct()
            ->where('deleted_at', '=',null)
            ->get();*/
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();
        //echo 264;die();
        if($loan->loan_type==1){
        //echo 266;die();
           $partner = Partner::findOrFail($loan->partner_id);
           if(!empty($partner->partner_name)){
              $partnerName = $partner->partner_name;
           }//echo 270;die();
        }else {
            //echo 272;die();
           $partnerName = 'NA';
        }
        //echo 275;die();

        return view('kycs.review-kyc', compact('kyc', 'client', 'bank', 'loan', 'ssbInfo', 'partnerName'));
    }

    public function evaluateZambiaKyc($id, $loanId){
        $client = Zambian::where('id',$id)->first();
        $loan = ZambiaLoan::where('id', $loanId)->first();

        return view('kycs.review-zam-kyc', compact('client','loan'));
    }

    public function getForm(){
        return view('kycs.kyc-form');
    }

    public function printKycPdf($id) {
        echo $id;die();
        $client = Client::findOrFail($id);
        $kyc = Kyc::where('natid',$client->natid)->first();
        $bank = Bank::where('id',$kyc->bank)->first();
        $loan = Loan::where('client_id',$id)->first();

        $emails = [$client->email, 'tech@idigitalise.co.in'];
        $natids = explode("/",$client->natid);

        $pdfFileName = storage_path() . DIRECTORY_SEPARATOR . 'app/downloads' . DIRECTORY_SEPARATOR . "KYCForm_".$natids[0]."_".$natids[1]."_".$natids[1].".pdf";
        $pdf = \PDF::loadView('kycs.kyc-form', compact('client','kyc', 'bank', 'loan'));
        $pdf->save($pdfFileName);
        $data=[];
        $attachNP='http://localhost/AstroCredZambiaLive/public/nationalids/' . $kyc->national_pic;
        $attachPP='http://localhost/AstroCredZambiaLive/public/pphotos/' . $kyc->passport_pic;
        $attachSP = 'http://localhost/AstroCredZambiaLive/public/payslips/' . $kyc->payslip_pic;
        $attachSG = 'http://localhost/AstroCredZambiaLive/public/signatures/' . $kyc->sign_pic;

        Mail::send('emails.new-loan-kyc', $data, function($message)use($pdf, $emails, $pdfFileName, $attachNP, $attachPP, $attachSP, $attachSG) {
            $message->to($emails)->subject('KYC of a new loan request');
            $message->attach($pdfFileName);
            $message->attach($attachNP);
            $message->attach($attachPP);
            $message->attach($attachSP);
            $message->attach($attachSG);
        });

        return $pdf->stream("KYCForm".$client->natid.".pdf");
    }

    public function postKycToRedSphere($natid, $loanId){

        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid',$natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();
        $settings = Masetting::find(1)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

        if($loan->loan_status == 0){
            return redirect()->back()->with('error', 'Loan has not yet been signed. Please make sure it has been signed.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s FCB status just verify if the details are there.');
//        }

        if ($client->fsb_status == 'ADVERSE') {
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 13, 'notes' => 'Loan declined because of Open adverse item(s).', 'updated_at' => now()]);

            Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Good day ".$client->first_name.", Your loan application of $".$loan->amount." has been declined. You have other running loan(s).")
                ->body();

            return redirect()->back()->with('error', 'The client has open adverse item(s) and will not be able to borrow at this time. The loan has been declined and notified the client.');
        }

        if ($client->gender == 'Female') {
            $gender = 'F';
        } else {
            $gender = 'M';
        }

        if ($client->marital_state == 'Single') {
            $mStatus = 'S';
        } elseif ($client->marital_state == 'Married') {
            $mStatus = 'M';
        } elseif ($client->marital_state == 'Divorced') {
            $mStatus = 'D';
        }else {
            $mStatus = 'W';
        }

        $ch = curl_init("192.168.145.54");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode !== 0){
            $details = Http::withOptions([
                'connect_timeout' => 10
            ])->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'])
                ->post('http://192.168.145.54/cbzapi/api/SaveCustomer/PostNewCustomer',[
                    'CUSTOMER_TYPE' => 'string',
                    'SUB_INDIVIDUAL' => 'string',
                    'CUSTOMER_NUMBER' => '30256',
                    'SURNAME' => $client->last_name,
                    'FORENAMES' => $client->first_name,
                    'DOB' => date_format($client->dob,"Y-m-d"),
                    'IDNO' => $natid,
                    'ISSUE_DATE' => '2020-04-14T15:33:13.224Z',
                    'ADDRESS' => $client->house_num . ", " . $client->surburb. ",". $client->city,
                    'CITY' => $client->city,
                    'PHONE_NO' => $client->mobile,
                    'NATIONALITY' => $client->nationality,
                    'GENDER' => $gender,
                    'HOME_TYPE' => $client->home_type,
                    'MONTHLY_RENT' => '0',
                    'HOME_LENGTH' => '0',
                    'MARITAL_STATUS' => $mStatus,
                    'EDUCATION' => 'string',
                    'CURR_EMPLOYER' => $client->employer,
                    'CURR_EMP_ADD' => 'string',
                    'CURR_EMP_LENGTH' => '0',
                    'CURR_EMP_PHONE' => 'string',
                    'CURR_EMP_EMAIL' => 'string',
                    'CURR_EMP_FAX' => 'string',
                    'CURR_EMP_CITY' => 'string',
                    'CURR_EMP_POSITION' => 'string',
                    'CURR_EMP_SALARY' => $client->gross,
                    'CURR_EMP_NET' => $client->salary,
                    'CURR_EMP_INCOME' => $client->salary,
                    'PREV_EMPLOYER' => 'string',
                    'PREV_EMP_ADD' => 'string',
                    'PREV_EMP_LENGTH' => '0',
                    'PREV_EMP_PHONE' => 'string',
                    'PREV_EMP_EMAIL' => 'string',
                    'PREV_EMP_FAX' => 'string',
                    'PREV_EMP_CITY' => 'string',
                    'PREV_EMP_POSITION' => 'string',
                    'PREV_EMP_SALARY' => '0',
                    'PREV_EMP_NET' => '0',
                    'PREV_EMP_INCOME' => '0',
                    'SPOUSE_NAME' => 'string',
                    'SPOUSE_OCCUPATION' => 'string',
                    'SPOUSE_EMPLOYER' => 'string',
                    'NO_CHILDREN' => '0',
                    'NO_DEPENDANTS' => $client->dependants,
                    'TRADE_REF1' => 'string',
                    'TRADE_REF2' => 'string',
                    'SPOUSE_PHONE' => 'string',
                    'CREDIT_LIMIT' => $client->cred_limit,
                    'HAS_ACCOUNT' => 'true',
                    'ACCOUNT_BRANCH' => $bankRacho->branch,
                    'ACCOUNT_NUMBER' => $kyc->acc_number,
                    'CREATED_BY' => 'self',
                    'CREATED_DATE' => '2020-04-14T15:33:13.225Z',
                    'MODIFIED_BY' => 'na',
                    'MODIFIED_DATE' => '2020-04-14T15:33:13.225Z',
                    'BRANCH_CODE' => $bankRacho->branch_code,
                    'BRANCH_NAME' => $bankRacho->branch,
                    'AREA' => 'string',
                    'DELETED' => 'true',
                    'MONTH_EXPENSE' => '0',
                    'MONTH_INCOME' => '0',
                    'PREV_SALES' => '0',
                    'CURR_ESTIMATE' => '0',
                    'CROPS' => 'string',
                    'FARM_PERIOD' => '0',
                    'SPOUSE_ADDRESS' => 'string',
                    'SPOUSE_IDNO' => 'string',
                    'ECNO' => $client->ecnumber,
                    'CD' => 'string',
                    'PDACode' => '0',
                    'AppTypeBank' => 'string',
                    'AppTypeBranch' => 'string',
                    'AppTypeOtherDesc' => 'string',
                    'Sector' => 'string',
                    'PhotoName' => 'string',
                    'ACTIVATED' => 'true',
                    'AccountSuffix' => '0',
                    'CUSTOMER_TYPE_ID' => '0',
                    'Bank' => 'CBZ',
                    'BankBranch' => '56621',
                    'BankAccountNo' => '56897845121',
                    'CustomerCode' => 'string',
                    'MaidenName' => 'string',
                    'FirstName' => 'string',
                    'MiddleNames' => 'string',
                    'FullName' => 'string',
                    'SpouseName' => 'string',
                    'NoOfDependants' => '0',
                    'ClassificationOfIndividual' => 'string',
                    'DateOfBirth' => '2020-04-14T15:33:13.226Z',
                    'CountryOfBirth' => 'string',
                    'DistrictOfBirth' => 'string',
                    'MaritalStatus' => 'string',
                    'Residency' => 'string',
                    'Citizenship' => 'string',
                    'Profession' => 'string',
                    'EmployerName' => 'string',
                    'BusinessName' => 'string',
                    'EstablishmentDate' => '2020-04-14T15:33:13.226Z',
                    'GrossIncome' => '0',
                    'AverageMonthlyExpenditures' => '0',
                    'NegativeStatusOfIndividual' => 'string',
                    'NationalID' => 'string',
                    'NationalIDIssueDate' => '2020-04-14T15:33:13.226Z',
                    'NationalIDExpirationDate' => '2020-04-14T15:33:13.226Z',
                    'NationalIDPlaceOfIssue' => 'string',
                    'PassportNumber' => 'string',
                    'PassportIssueDate' => '2020-04-14T15:33:13.226Z',
                    'PassportExpirationDate' => '2020-04-14T15:33:13.226Z',
                    'PassportIssuerCountry' => 'string',
                    'PassportPlaceOfIssue' => 'string',
                    'PreviousPassportNumber' => 'string',
                    'DrivingLicenseNumber' => 'string',
                    'DrivingLicenseIssueDate' => '2020-04-14T15:33:13.227Z',
                    'DrivingLicenseExpirationDate' => '2020-04-14T15:33:13.227Z',
                    'DrivingLicensePlaceOfIssue' => 'string',
                    'LicenseNumber' => 'string',
                    'MainAddress' => 'string',
                    'MainAddress_POBox' => 'string',
                    'MainAddress_Street' => 'string',
                    'MainAddress_City' => 'string',
                    'MainAddress_Country' => 'string',
                    'MainAddress_AddressLine' => 'string',
                    'SecondaryAddress' => 'string',
                    'SecondaryAddress_POBox' => 'string',
                    'SecondaryAddress_Street' => 'string',
                    'SecondaryAddress_City' => 'string',
                    'SecondaryAddress_Country' => 'string',
                    'SecondaryAddress_AddressLine' => 'string',
                    'CelluarPhone' => 'string',
                    'FixedLine' => 'string',
                    'Email' => 'string',
                    'Fax' => 'string',
                    'WorkPhone' => 'string',
                    'NetIncome' => '0',
                    'CURR_EMP_LENGTH_YEAR' => '0',
                    'DefaultHistory' => 'string',
                    'EmploymentType' => 'string',
                    'MainIncomeSource' => 'string',
                    'OtherIncomeSources' => 'string',
                    'AccOtherBanks' => 'string',
                    'OtherPropertyOwnership' => 'string',
                    'NBSAccDate' => '2020-04-14',
                    'TimeCurrRes' => '0',
                    'TimePrevRes' => '0',
                    'Title' => 'string',
                    'HOME_LENGTH_MON' => '0',
                    'INITIALS' => 'string',
                    'INSOLVENT' => 'string',
                    'INSOLVENTDETAIL' => 'string',
                    'MICROFINANCERELATED' => 'string',
                    'SPOUSE_EMAIL' => 'string',
                    'SPOUSE_EMPADD' => 'string',
                    'SPOUSE_BUSTEL' => 'string',
                    'SPOUSE_MOBTEL' => 'string',
                    'USACitizenShip' => 'string',
                    'USAResidentCard' => 'string',
                    'telTwitter' => 'string',
                    'telFacebook' => 'string',
                    'telLinkedin' => 'string',
                    'telSkype' => 'string',
                    'telInstagram' => 'string',
                    'MICROFINANCERELATEDNAME' => 'string',
                    'MONTHLYSALARYDATE' => '2020-04-14',
                    'BankYears' => '0',
                    'BankMonths' => '0',
                    'BankFacilitieswithbankMFI' => 'string',
                    'BankFacilityType' => 'string',
                    'BankPresentBalance' => '0',
                    'BankExpiryDate' => '2020-04-14',
                    'BankRepayments' => '0',
                    'OtherBank' => 'string',
                    'OtherBranch' => 'string',
                    'OtherBankAccountNo' => 'string',
                    'BankFacilitiesOtherBank' => 'string',
                    'GUARANTOR_REL_BUSTEL' => 'string',
                    'GUARANTOR_REL_MOBILETEL' => 'string',
                    'GUARANTOR_REL_EMPLOYER' => 'string',
                    'GUARANTOR_EMPLOYER_ADD' => 'string',
                    'GUARANTOR_EMPLOYER_HOMETEL' => 'string',
                    'GUARANTOR_EMPLOYER_BUSTEL' => 'string',
                    'DocumentLinks' => "https://eshagi.com/get-client-kyc/".$natid,
                    'ApprovalStatus' => 'pending',
                ])
                ->body();
        } else {
            //exec("strongswan restart");
            //shell_exec('sudo strongswan restart');
            Artisan::call('strongswan:reboot');
            //Log::info('I have run strongswan restart on Post KYC to RedSphere connection attempt.');
            return redirect()->back()->with('error', 'Error '.$httpcode.': Redsphere server appears to be offline, I have tried to start the connection. Try after 30 seconds.');
        }

        $resp=json_decode($details, TRUE);

        //$code = $details->successful();
        if (isset($resp['Message'])) {
            if($resp['Message'] == 'National Id Already exists'){
                return redirect()->back()->with('error', 'This national ID number was already captured.');
            } else {
                return redirect()->back()->with('error', 'Something is wrong here, I got the following error from RedSphere: '.$resp['Message']);
            }
        }

        $redSphereID = $resp['ID'];
        $customerType = $resp['CUSTOMER_TYPE'];
        $subIndividual = $resp['SUB_INDIVIDUAL'];
        $redSphereNumber = $resp['CUSTOMER_NUMBER'];

        if (isset($resp['CUSTOMER_NUMBER'])) {
            DB::table("clients")
                ->where("natid", $natid)
                ->update(['flag' => 'REDS','reds_id' => $redSphereID, 'reds_type' => $customerType, 'reds_sub' => $subIndividual,'reds_number' => $redSphereNumber, 'updated_at' => now()]);

            DB::table("kycs")
                ->where("natid", $natid)
                ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

            if ($client->emp_sector == 'Government') {
                DB::table("loans")
                    ->where("id", $loan->id)
                    ->update(['loan_status' => 11, 'updated_at' => now()]);

            } else {
                DB::table("loans")
                    ->where("id", $loan->id)
                    ->update(['loan_status' => 3, 'updated_at' => now()]);
            }
        } else {
            return redirect()->back()->with('error', 'I did not get the RedSphere Number successful. Maybe try again?');
        }

        return redirect()->back()->with('success', 'Customer Details updated successfully.');

    }

    public function approvedKycs(){
        $kycs = DB::table('kycs as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.first_name','c.last_name','c.dob','k.id','k.natid','k.status','k.created_at', 'k.reviewer','c.reds_number')
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('k.kyc_status', '=',1)
            ->get();
        return view('kycs.approved-kycs', compact('kycs'));
    }

    public function allClientsKycs(){
        $kycs = DB::table('kycs as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.first_name','c.last_name','k.id','k.natid','k.status','k.created_at','c.mobile')
            ->where('c.locale_id','=',auth()->user()->locale)
            ->get();
        return view('kycs.client-kycs', compact('kycs'));
    }

    public function getKycInfo($id) {
        $kyc = Kyc::findOrFail($id);
        $bank = Bank::where('id',$kyc->bank)->first();
        $client = Client::where('natid',$kyc->natid)->first();
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();

        return view('kycs.view-kyc', compact('kyc', 'client', 'bank', 'ssbInfo'));
    }

    public function fcbChecker(){
        $clients = Client::where('locale_id',1)
            ->where('fsb_score',null)
            ->orWhere('fsb_score',0)
            ->where('fsb_status',null)
            ->where('fsb_rating',null)
            ->get();

        return view('kycs.fcb-unupdated', compact('clients'));
    }

    public function fcbRechecker(){
        $clients = Client::where('locale_id', '=',1)->get();

        return view('kycs.fcb-rechecker', compact('clients'));
    }

    public function fetchFcbState($id) {
        $client = Client::where('id', $id)->first();

        $dob = DATE_FORMAT($client->dob, 'd-m-Y');
        $result = explode("-", $client->natid);
        $idFormated = $result[0] . '-' . $result[1] . $result[2] . $result[3];
        if ($client->gender == 'Male') {
            $gender = 'M';
        } else {
            $gender = 'F';
        }

        if ($client->marital_state == 'Single') {
            $marital = 'S';
        } elseif ($client->marital_state == 'Married') {
            $marital = 'M';
        } elseif ($client->marital_state == 'Widowed') {
            $marital = 'W';
        } elseif ($client->marital_state == 'Divorced') {
            $marital = 'D';
        }

        $ownership = '';
        if ($client->home_type == 'Owned') {
            $ownership = '1';
        } elseif ($client->home_type == 'Rented') {
            $ownership = '2';
        } elseif ($client->home_type == 'Mortgaged') {
            $ownership = '3';
        } elseif ($client->home_type == 'Parents') {
            $ownership = '4';
        } elseif ($client->home_type == 'Employer Owned') {
            $ownership = '5';
        }

//        $details = Http::post('https://www.fcbureau.co.zw/api/newIndividual?dob=' . $dob . '&names=' . $client->first_name . '&surname=' . $client->last_name . '&national_id=' . $idFormated . '&gender=' . $gender . '&search_purpose=1&email=' . getFcbUsername() . '&password=' . getFcbPassword() . '&drivers_licence=&passport=&married=' . $marital . '&nationality=3&streetno=' . $client->house_num . '&streetname=' . $client->street . '&building=&surbub=' . $client->surburb . '&pbag=&city=' . $client->city . '&telephone=&mobile=' . $client->mobile . '&ind_email=' . $client->email . '&property_density=2&property_status=' . $ownership . '&occupation_class=0&employer=' . $client->employer . '&employer_industry=0&salary_band=8&loan_purpose=&loan_amount=')
//            ->body();

        $details = Http::asForm()->post('https://www.fcbureau.co.zw/api/newIndividual',[
            'dob' => $dob,
            'names' => $client->first_name,
            'surname' => $client->last_name,
            'national_id' => $idFormated,
            'gender' => $gender,
            'search_purpose' => 1,
            'email' => getFcbUsername(),
            'password' => getFcbPassword(),
            'drivers_licence' => 'NA',
            'passport' => 'NA',
            'married' => $marital,
            'nationality' => 3,
            'streetno' => $client->house_num,
            'streetname' => $client->street,
            'building' => 'NA',
            'surbub' => $client->surburb,
            'pbag' => '',
            'city' => $client->city,
            'telephone' => '',
            'mobile' => $client->mobile,
            'ind_email' => $client->email,
            'property_density' => 2,
            'property_status' => $ownership,
            'occupation_class' => 0,
            'employer' => $client->employer,
            'employer_industry' => 0,
            'salary_band' => 8,
            'loan_purpose' => 14,
            'loan_amount' => 0,
            ]
        )
            ->body();

        $json = json_decode($details, true);
        $code = $json['code'];

        if ($code == 206) {
            return redirect()->back()->with('error', 'There is some missing client info: '. $json['missing information']);
        } elseif($code == 401){
            return redirect()->back()->with('error', 'Authorization error: '. $json['error']. '. Please check account status with FCB.');
        } elseif ($code == 200) {

            $status = $json['searches'][0]['status'];
            $score = $json['searches'][0]['score'];

            if ($score >= 0 && $score <= 200) {
                $scoreMeans = 'Extremely High Risk';
            } elseif ($score >= 201 && $score <= 250) {
                $scoreMeans = 'High Risk';
            } elseif ($score >= 251 && $score <= 300) {
                $scoreMeans = 'Medium to High Risk';
            } elseif ($score >= 301 && $score <= 350) {
                $scoreMeans = 'Medium to Low Risk';
            } else  {
                $scoreMeans = 'Low Risk'; //($score >= 351 && $score <= 400)
            }

            DB::table("clients")
                ->where("id", $id)
                ->update(['fsb_score' => $score, 'fsb_status' => $status, 'fsb_rating' => $scoreMeans, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'FCB Status updated successfully.');
    }

    public function cbzEvaluateKyc($id, $loanId){
        $kyc = Kyc::findOrFail($id);
        $bank = Bank::where('id',$kyc->bank)->first();
        $client = Client::where('natid',$kyc->natid)->first();
        $loan = Loan::where('id', $loanId)->first();
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();

        return view('kycs.cbz-review', compact('kyc', 'client', 'bank', 'loan', 'ssbInfo'));
    }

    public function getCBZPendingKycs(){
        $kycs = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
            //->whereIn('l.loan_status',[1, 2])
            ->where('k.cbz_status','=', '0')
            ->where('k.status', '=',true)
            ->orderByDesc('l.created_at')
            ->distinct()
            ->get();

        return view('kycs.cbz-pending-kycs', compact('kycs'));
    }

    public function approveCbzEvaluateKyc($natid, $loanId) {

        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid', $natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining an FCB Status Check.');
//        }

        DB::table("kycs")
            ->where("natid", $natid)
            ->update(['cbz_status' => '1', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC has been approved successfully.');
    }

    public function rejectCbzEvaluateKyc($natid, $loanId) {

        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid', $natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining an FCB Status Check.');
//        }

        DB::table("kycs")
            ->where("natid", $natid)
            ->update(['cbz_status' => '2', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC has been rejected successfully.');
    }

    public function cbzCheckForm(){
        return view('kycs.redsphere-check');
    }

    public function getRedSphereCustomerInfo(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'natid'                  => 'required',
                'loanid'                  => 'required',
            ],
            [
                'natid.required'       => 'I need the ID Number to perform this check.',
                'loanid.required'       => 'I need the Loan ID to perform this check for you.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $client = Client::where('natid', $request->input('natid'))->first();
        $loan = Loan::where('id',$request->input('loanid'))->first();

        if (is_null($client)){
            return redirect()->back()->with('error', 'I don\'t do random API checks, hey. This client should be in the system first.');
        }

        if (is_null($loan)){
            return redirect()->back()->with('error', 'I can\'t find the client loan. This loan should be in the system first.');
        }

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'])
            ->get('http://192.168.145.54/cbzapi/api/SaveCustomer/GetCustomerDetails?customerId='.$request->input('natid'))
            ->body();

        $resp=json_decode($details, TRUE);

        if (is_null($resp)){
            return redirect()->back()->with('error', 'I didn\'t find client KYC info at RedSphere.');
        }

        if ($client->reds_number == null){
            $redSphereID = $resp['ID'];
            $customerType = $resp['CUSTOMER_TYPE'];
            $subIndividual = $resp['SUB_INDIVIDUAL'];
            $redSphereNumber = $resp['CUSTOMER_NUMBER'];

            if (isset($resp['CUSTOMER_NUMBER'])) {
                DB::table("clients")
                    ->where("natid", $request->input('natid'))
                    ->update(['reds_id' => $redSphereID, 'reds_type' => $customerType, 'reds_sub' => $subIndividual, 'reds_number' => $redSphereNumber, 'updated_at' => now()]);

                DB::table("kycs")
                    ->where("natid", $request->input('natid'))
                    ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

                DB::table("loans")
                    ->where("id", $loan->id)
                    ->update(['loan_status' => 11, 'updated_at' => now()]);
            }
        } else {
            $message = 'I got this client info at RedSphere.';
            return view('kycs.redsphere-check', compact('resp', 'message'));
        }

        $message = 'I got this client info at RedSphere and updated it with our records.';
        return view('kycs.redsphere-check', compact('resp', 'message'));
    }

    public function getClientKyc($natid){
        $kyc = Kyc::where('natid', $natid)->firstOrFail();
        return view('kycs.cbz-viewing', compact('kyc'));
    }

    public function selfFinanceApproveKyc($id, $loanId){

        $client = Client::find($id);
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();
        $locale = Localel::findOrFail($client->locale_id);

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s local Financial board records, just verify if the details are there.');
//        }

        function generateEshagiNumber($natid){
            $inClient = Client::where('natid', $natid)->first();
            $locale = Localel::findOrFail($inClient->locale_id);
            $count = Client::whereRaw('YEAR(created_at) = ?', date('Y'))->where('locale_id', $locale->id)->count();

            $eshagiNumber = $locale->country_short.'/'.date('y').'/'. sprintf("%09d", $count).'/'.'ESG';

            return $eshagiNumber;
        }

        $customerType = $locale->country. ' Salary Based Personal';
        $subIndividual = $locale->country_short.' SBP';
        $shagiNumber = generateEshagiNumber($client->natid);

        if (isset($shagiNumber)) {
            DB::table("clients")
                ->where("id", $id)
                ->update(['reds_id' => $client->id, 'reds_type' => $customerType, 'reds_sub' => $subIndividual, 'reds_number' => $shagiNumber, 'updated_at' => now()]);

            DB::table("kycs")
                ->where("natid", $client->natid)
                ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

            if ($client->emp_sector == 'Zambian Military') {
                DB::table("loans")
                    ->where("id", $loan->id)
                    ->update(['loan_status' => 16, 'updated_at' => now()]);
            }
        }

        return redirect()->back()->with('success', 'Customer Details updated successfully.');
    }

    public function deviceLoanApproveKyc($id, $loanId){

        $client = Client::find($id);
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = DeviceLoan::where('id', $loanId)->first();
        $locale = Localel::findOrFail($client->locale_id);

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s local Financial board records, just verify if the details are there.');
//        }

        function generateEshagiNumber($natid){
            $inClient = Client::where('natid', $natid)->first();
            $locale = Localel::findOrFail($inClient->locale_id);
            $count = Client::whereRaw('YEAR(created_at) = ?', date('Y'))->where('locale_id', $locale->id)->count();

            $eshagiNumber = $locale->country_short.'/'.date('y').'/'. sprintf("%09d", $count).'/'.'ESG';

            return $eshagiNumber;
        }

        $customerType = $locale->country. ' Salary Based Device Loan';
        $subIndividual = $locale->country_short.' SBP';
        $shagiNumber = generateEshagiNumber($client->natid);

        if (isset($shagiNumber)) {
            DB::table("clients")
                ->where("id", $id)
                ->update(['reds_id' => $client->id, 'reds_type' => $customerType, 'reds_sub' => $subIndividual, 'reds_number' => $shagiNumber,'flag' => 'DEVFIN', 'updated_at' => now()]);

            DB::table("kycs")
                ->where("natid", $client->natid)
                ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

            DB::table("device_loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 4, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Customer Details updated successfully.');
    }

    public function approveCbzFromExternal(Request $request, $natid){
        $validator = Validator::make(
            $request->all(),
            [
                'passcode'                  => 'required',
            ],
            [
                'passcode.required'       => 'We need to check if you\'re permitted to do this action.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $settings = Masetting::find(1)->first();

        $isAuthorized = Str::contains($settings->cbz_authorizer, $request->input('passcode'));

        if ($isAuthorized){
            $kyc = Kyc::where('natid', $natid)->first();
            if ($kyc->cbz_status = 1 AND $kyc->cbz_evaluator != null) {
                return redirect()->back()->with('info', 'This KYC was already actioned by '.$kyc->cbz_evaluator);
            } else {
                DB::table("kycs")
                    ->where("natid", $natid)
                    ->update(['cbz_status' => '1', 'cbz_evaluator' => $request->input('passcode'), 'updated_at' => now()]);
            }
        } else {
            return redirect()->back()->with('error', 'Seems like you are not permitted to do this action.');
        }

        return redirect()->back()->with('success', 'KYC has been approved successfully.');
    }

    public function rejectCbzFromExternal(Request $request, $natid){
        $validator = Validator::make(
            $request->all(),
            [
                'passcode'                  => 'required',
                'reason'                  => 'required',
            ],
            [
                'passcode.required'       => 'We need to check if you\'re permitted to do this action.',
                'reason.required'       => 'Please advise why you rejected this KYC ',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $settings = Masetting::find(1)->first();

        $isAuthorized = Str::contains($settings->cbz_authorizer, $request->input('passcode'));

        if ($isAuthorized){
            $kyc = Kyc::where('natid', $natid)->first();
            if ($kyc->cbz_status == 1 AND $kyc->cbz_evaluator != null) {
                return redirect()->back()->with('info', 'This KYC was already actioned by '.$kyc->cbz_evaluator);
            } else {
                DB::table("kycs")
                    ->where("natid", $natid)
                    ->update(['cbz_status' => '2', 'cbz_evaluator' => $request->input('passcode'),'kyc_notes' => $request->input('reason'), 'updated_at' => now()]);

                $lofficers = User::join('role_user as r', 'r.user_id', '=', 'users.id')
                    ->where('users.utype','=','System')
                    ->where('r.role_id','=',4)
                    ->get();
                $rejectedKyc = Kyc::where('natid', $natid)->first();
                foreach ($lofficers as $officer) {
                    $officer->notify(new CbzRejectedKyc($rejectedKyc));
                }
            }
        } else {
            return redirect()->back()->with('error', 'Seems like you are not permitted to do this action.');
        }

        return redirect()->back()->with('success', 'KYC has been rejected successfully.');
    }

    public function crbChecker(){
        $clients = Client::where('locale_id',2)
            ->where('fsb_score',null)
            ->where('fsb_status',null)
            ->where('fsb_rating',null)
            ->get();

        return view('kycs.crb-unupdated', compact('clients'));
    }

    public function fetchCrbState($id) {
        $settings = Masetting::find(1)->first();
        $client = Client::where('id', $id)->first();

        $authDetails = Http::asForm()->post('https://demo.ndasenda.co.zw/connect/token',[
            'infinityCode' => $settings->crb_infinity_code,
            'username' => $settings->username,
            'password' => $settings->password])
            ->body();
        $resp=json_decode($authDetails, TRUE);

        $details = Http::withToken($resp['token'])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'accept ' => 'application/json'])
            ->post('https://demo.ndasenda.co.zw/api/v1/deductions/requests/commit/'.$id)
            ->body();

        $details = Http::post('')
            ->body();

        $json = json_decode($details, true);
        $code = $json['code'];

        if ($code == 206) {
            return redirect()->back()->with('error', 'There is some missing client info: '. $json['missing information']);
        } elseif($code == 401){
            return redirect()->back()->with('error', 'Authorization error: '. $json['error']. '. Please check eShagi account status with FCB.');
        } elseif ($code == 200) {

            $status = $json['searches'][0]['status'];
            $score = $json['searches'][0]['score'];

            if ($score >= 0 && $score <= 200) {
                $scoreMeans = 'Extremely High Risk';
            } elseif ($score >= 201 && $score <= 250) {
                $scoreMeans = 'High Risk';
            } elseif ($score >= 251 && $score <= 300) {
                $scoreMeans = 'Medium to High Risk';
            } elseif ($score >= 301 && $score <= 350) {
                $scoreMeans = 'Medium to Low Risk';
            } else  {
                $scoreMeans = 'Low Risk'; //($score >= 351 && $score <= 400)
            }

            DB::table("clients")
                ->where("id", $id)
                ->update(['fsb_score' => $score, 'fsb_status' => $status, 'fsb_rating' => $scoreMeans, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'FCB Status updated successfully.');
    }

    public function crbRechecker(){
        $clients = Client::where('locale_id', '=',2)->get();

        return view('kycs.crb-rechecker', compact('clients'));
    }

    public function getPendingZambiaKycs(){

        $kycs = DB::table('zambians as z')
            ->join('zambia_loans as l','l.zambian_id','=','z.id')
            ->select('z.id','l.id as lid','z.first_name','z.middle_name','z.last_name','z.nrc','z.mobile','z.dob','z.created_at')
            ->where('z.officer_stat','=',false)
            ->orderByDesc('z.created_at')
            ->distinct()
            ->where('z.deleted_at', '=',null)
            ->get();

        return view('kycs.zm-pending-kycs', compact('kycs'));
    }

    public function getPendingAuthorizationZambiaKycs(){

        $kycs = DB::table('zambians as z')
            ->join('zambia_loans as l','l.zambian_id','=','z.id')
            ->select('z.id','l.id as lid','z.first_name','z.middle_name','z.last_name','z.nrc','z.mobile','z.dob','z.created_at')
            ->where('z.officer_stat','=',true)
            ->where('z.manager_stat','=',false)
            ->orderByDesc('z.created_at')
            ->distinct()
            ->where('z.deleted_at', '=',null)
            ->get();

        return view('kycs.zm-pending-kycs', compact('kycs'));
    }

    public function getApprovedZambiaKycs(){
        $kycs = DB::table('zambians as z')
            ->select('z.id','z.ld_borrower_id','z.first_name','z.middle_name','z.last_name','z.nrc','z.manager','z.mobile','z.dob','z.created_at')
            ->where('z.officer_stat','=',true)
            ->where('z.manager_stat','=',true)
            ->where('z.savings_acc','=',null)
            ->orderByDesc('z.created_at')
            ->distinct()
            ->where('z.deleted_at', '=',null)
            ->get();

        return view('kycs.zm-approved-kycs', compact('kycs'));
    }

    public function allZambiaClientKycs(){
        $kycs = DB::table('kycs as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.first_name','c.last_name','k.id','k.natid','k.status','k.created_at','c.mobile')
            ->where('c.locale_id','=',2)
            ->get();
        return view('kycs.zm-client-kycs', compact('kycs'));
    }

    public function eloanApproveKyc($id, $loanId){

        $client = Client::find($id);
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Eloan::where('id', $loanId)->first();
        $locale = Localel::findOrFail($client->locale_id);

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s local Financial board records, just verify if the details are there.');
//        }

        function generateEshagiNumber($natid){
            $inClient = Client::where('natid', $natid)->first();
            $locale = Localel::findOrFail($inClient->locale_id);
            $count = Client::whereRaw('YEAR(created_at) = ?', date('Y'))->where('locale_id', $locale->id)->count();

            $eshagiNumber = $locale->country_short.'/'.date('y').'/'. sprintf("%09d", $count).'/'.'ESG';

            return $eshagiNumber;
        }

        $customerType = $locale->country. ' Salary Based Personal';
        $subIndividual = $locale->country_short.' SBP';
        $shagiNumber = generateEshagiNumber($client->natid);

        if (isset($shagiNumber)) {
            DB::table("clients")
                ->where("id", $id)
                ->update(['reds_id' => $client->id, 'reds_type' => $customerType, 'reds_sub' => $subIndividual, 'reds_number' => $shagiNumber, 'updated_at' => now()]);

            DB::table("kycs")
                ->where("natid", $client->natid)
                ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

            DB::table("eloans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 3, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'KYC approved successfully.');
    }

    public function getPendingEshagiKyc(){

        $kycs = DB::table('eloans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('c.reds_number','=', null)
            ->where('k.status', '=',false)
            ->orderByDesc('l.created_at')
            ->distinct()
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('l.deleted_at', '=',null)
            ->get();

        return view('kycs.pending-loans-kyc', compact('kycs'));
    }

    public function evaluateEshagiKyc($id, $loanId){
        $kyc = Kyc::findOrFail($id);
        $bank = Bank::where('id',$kyc->bank)->first();
        $client = Client::where('natid',$kyc->natid)->first();
        $loan = Eloan::where('id', $loanId)->first();
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();

        return view('kycs.review-ekyc', compact('kyc', 'client', 'bank', 'loan', 'ssbInfo'));
    }

    public function authorizeEkyc($id, $loanId){
        $kyc = Kyc::findOrFail($id);
        $bank = Bank::where('id',$kyc->bank)->first();
        $client = Client::where('natid',$kyc->natid)->first();
        $loan = Eloan::where('id', $loanId)->first();
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();

        return view('kycs.eloan-review', compact('kyc', 'client', 'bank', 'loan', 'ssbInfo'));
    }

    public function approveEloanKyc($id, $loanId) {

        $kyc = Kyc::findOrFail($id);
        $client = Client::where('natid', $kyc->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Eloan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining a local Financial board records, just verify if the details are there.');
//        }

        DB::table("kycs")
            ->where("id", $id)
            ->update(['cbz_status' => '1', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        DB::table("eloans")
            ->where("id", $loan->id)
            ->update(['loan_status' => 5, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC and eLoan have been authorized successfully.');
    }

    public function approveEloanKycOnly($id, $loanId) {

        $kyc = Kyc::findOrFail($id);
        $client = Client::where('natid', $kyc->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Eloan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining a local Financial board records, just verify if the details are there.');
//        }

        DB::table("kycs")
            ->where("id", $id)
            ->update(['cbz_status' => '1', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC has been authorized successfully.');
    }

    public function rejectEloanKyc($id, $loanId) {

        $kyc = Kyc::findOrFail($id);
        $client = Client::where('natid', $kyc->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Eloan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining a local Financial board records, just verify if the details are there.');
//        }

        DB::table("kycs")
            ->where("id", $id)
            ->update(['cbz_status' => '2', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        DB::table("eloans")
            ->where("id", $loan->id)
            ->update(['loan_status' => 6, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC and eLoan have been rejected successfully.');
    }

    public function rejectEloanKycOnly($id, $loanId) {

        $kyc = Kyc::findOrFail($id);
        $client = Client::where('natid', $kyc->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Eloan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining a local Financial board records, just verify if the details are there.');
//        }

        DB::table("kycs")
            ->where("id", $id)
            ->update(['cbz_status' => '2', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC has been rejected successfully.');
    }

    public function getBotNewRequests(){
        $kycs = DB::table('clients as c')
            ->join('users as u','c.natid','=','u.natid')
            ->select('u.first_name','u.last_name','c.id','c.natid','c.creator','c.created_at')
            ->where('c.creator','=','Bot')
            ->where('c.title','=','Mx')
            ->where('u.locale','=',auth()->user()->locale)
            ->get();

        return view('kycs.bot-pending-kyc', compact('kycs'));
    }

    public function vettingBotKyc($id){
        $client = Client::findOrFail($id);
        $botApplication = BotApplication::where('natid', $client->natid)->first();
        return view('kycs.review-bot-kyc', compact( 'client','botApplication'));
    }

    public function approvingBotKyc(Request $request, $id){
        $client = Client::findOrFail($id);
        $user = User::where('natid', $client->natid)->first();
        if ($client->first_name != $_POST['first_name'] OR $client->last_name != $_POST['last_name']) {
            $parts = explode(' ', $_POST['first_name'].' '.$_POST['last_name']);
            $name_first = array_shift($parts);
            $name_last = array_pop($parts);
            $name_middle = trim(implode(' ', $parts));

            $maiden = substr($name_first, 0, 1);
            $middle = substr($name_middle, 0, 1);

            $username = strtolower($maiden . $middle . $name_last);

            $name = $username;
            $i = 0;
            do {
                //Check in the database here
                $exists = User::where('name', '=', $name)->exists();
                if($exists) {
                    $i++;
                    $name = $username . $i;
                }
            }while($exists);
        } else {
            $name=$user->name;
        }

        $validator = Validator::make(
            $request->all(),
            [
                'title'                  => 'required',
                'first_name'            => 'required',
                'last_name'             => 'required',
                'natid'                 => 'required|max:15|unique:clients,natid,'.$client->id,
                'email'                 => 'nullable|email|max:255|unique:clients,email,'.$client->id,
                'mobile'                 => 'required|max:10|unique:clients,mobile,'.$client->id,
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_state'        => 'required',
            ],
            [
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with eShagi.',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with eShagi.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with eShagi.',
                'dob.required' => 'We need your date of birth',
                'dob.date' => 'The correct date format should be dd-mm-yyyy',
                'gender.required' => 'What is your gender?',
                'marital_state.required' => 'What is your marital status?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        if($ipAddress == 'null'){
            $ipAddress = '0.0.0.0';
        }else {
            $ipAddress = $ipAddress->getClientIp();
        }

        $client->title = strip_tags($request['title']);
        $client->first_name = strip_tags($request['first_name']);
        $client->last_name = strip_tags($request['last_name']);
        $client->natid = strtoupper($request['natid']);
        $client->email = $request['email'];
        $client->mobile = $request['mobile'];
        $client->dob = $request['dob'];
        $client->gender = $request['gender'];
        $client->marital_state = $request['marital_state'];
        $client->salary = $request['salary'];
        $client->home_type = $request['home_type'];

        $client->save();

        if ($client->save()) {
            $user->name = $name;
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->natid = strtoupper($request['natid']);
            $user->mobile = $request['mobile'];
            $user->updated_ip_address = $ipAddress;
            $user->activated = true;
            $user->save();

            $application = BotApplication::where('natid',  $user->natid)->first();
            $application->approved = true;
            $application->save();

            $getBotResponse = Http::asForm()->post("https://eshagibot.trysoftech.co.zw/tumirakuno",[
                'api_key' => 'nqPuiSxDLNRtQj0gEvpw==',
                'toPhone' => $user->bot_number,
                'message' => 'Hie there ,'.$user->first_name.' The information you provided on eShagi has been approved. You can sign in whenever you want.'])
                ->body();

            $json = json_decode($getBotResponse, true);
            $status = $json['status'];
            $message = $json['message'];
            if ($status == true) {
                Notyf::success('Client Notified.');
            } else {
                Log::info('Failed to notify user of KYC approval: '.$message);
            }
        }

        Notyf::success('KYC approved.');
        return redirect()->back();
    }

    public function pendingPartnerKyc(){
        if ( auth()->user()->hasRole('salesadmin') || auth()->user()->hasRole('loansofficer') ) {
            $kycs = DB::table('users as u')
                ->join('partners as p', 'u.natid', '=', 'p.regNumber')
                ->join('merchant_kycs as k', 'k.natid', '=', 'u.natid')
                ->select('u.id', 'p.partner_name', 'p.partner_type', 'p.regNumber', 'p.cfullname', 'p.telephoneNo', 'u.created_at')
                ->where('k.loan_officer', '=', false)
                ->orderByDesc('u.created_at')
                ->distinct()
                ->where('p.locale_id', '=', auth()->user()->locale)
                ->where('u.deleted_at', '=', null)
                ->get();
        } elseif (auth()->user()->hasRole('manager')){
            $kycs = DB::table('users as u')
                ->join('partners as p', 'u.natid', '=', 'p.regNumber')
                ->join('merchant_kycs as k', 'k.natid', '=', 'u.natid')
                ->select('u.id', 'p.partner_name', 'p.partner_type', 'p.regNumber', 'p.cfullname', 'p.telephoneNo', 'u.created_at')
                ->where('k.loan_officer', '=', true)
                ->where('k.manager', '=', false)
                ->orderByDesc('u.created_at')
                ->distinct()
                ->where('p.locale_id', '=', auth()->user()->locale)
                ->where('u.deleted_at', '=', null)
                ->get();
        } else {
            $kycs = DB::table('users as u')
                ->join('partners as p', 'u.natid', '=', 'p.regNumber')
                ->join('merchant_kycs as k', 'k.natid', '=', 'u.natid')
                ->select('u.id', 'p.partner_name', 'p.partner_type', 'p.regNumber', 'p.cfullname', 'p.telephoneNo', 'u.created_at')
                ->orderByDesc('u.created_at')
                ->distinct()
                ->where('p.locale_id', '=', auth()->user()->locale)
                ->where('u.deleted_at', '=', null)
                ->get();
        }
        return view('kycs.partner-pending-kyc', compact('kycs'));
    }

    public function evaluatePartnerKyc($id){
        $user = User::findOrFail($id);
        $kyc = MerchantKyc::where('natid', $user->natid)->first();
        $partner = Partner::where('regNumber',$user->natid)->first();
        $bank = Bank::where('id',$partner->bank)->first();

        return view('kycs.review-partner-kyc', compact('user','kyc', 'partner', 'bank'));
    }

    public function approvePartnerKyc($id){
        $user = User::findOrFail($id);
        $kyc = MerchantKyc::where('natid', $user->natid)->first();
        $partner = Partner::where('regNumber',$user->natid)->first();

        if (is_null($partner)) {
            return redirect()->back()->with('error', 'Partner info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Partner kyc info is missing. Please verify the details.');
        }

        if (auth()->user()->hasRole('manager') AND $kyc->loan_officer == false) {
            return redirect()->back()->with('error', 'Loan Officer must first approve this KYC record for it to get a second authorization.');
        }

        if ( auth()->user()->hasRole('salesadmin') || auth()->user()->hasRole('loansofficer') ) {
            DB::table("merchant_kycs")
                ->where("id", $kyc->id)
                ->update(['loan_officer' => true, 'approver' => auth()->user()->name, 'updated_at' => now()]);
        } elseif (auth()->user()->hasRole('manager') AND $kyc->loan_officer == true){
            DB::table("merchant_kycs")
                ->where("id", $kyc->id)
                ->update(['manager' => true, 'manager_approver' => auth()->user()->name, 'updated_at' => now()]);

            DB::table("users")
                ->where("id", $id)
                ->update(['activated' => true, 'updated_at' => now()]);
        } else {
            return redirect()->back()->with('error', 'Loan Officers and Manager can only approve these requests.');
        }

        return redirect()->back()->with('success', 'Partner KYC has been approved successfully.');
    }

    public function getFcbResponse(){
        $clients = DB::table('clients')
            ->select('id', 'first_name', 'last_name', 'natid', 'fsb_score','fsb_status','fsb_rating')
            ->where('fsb_score','!=', null)
            ->get();
        return view('kycs.fcb-responses', compact('clients'));
    }

    public function postKycToMusoni($natid, $loanId){

        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid',$natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

        if($loan->loan_status == 0){
            return redirect()->back()->with('error', 'Loan has not yet been signed. Please make sure it has been signed.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s FCB status just verify if the details are there.');
//        }

        if ($client->fsb_status == 'ADVERSE') {
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 13, 'notes' => 'Loan declined because of Open adverse item(s).', 'updated_at' => now()]);

            Http::post(getBulkSmsUrl() ."to=+263".$client->mobile."&msg=Good day ".$client->first_name.", Your loan application of $".$loan->amount." has been declined. You have other running loan(s).")
                ->body();

            return redirect()->back()->with('error', 'The client has open adverse item(s) and will not be able to borrow at this time. The loan has been declined and notified the client.');
        }


    }

    public function getZWMBPendingKycs(){
        $kycs = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
            ->where('c.flag','=','ZWMB')
            ->where('k.cbz_status','=', '0')
            ->where('k.status', '=',true)
            ->orderByDesc('l.created_at')
            ->distinct()
            ->get();

        return view('kycs.womans-pending-kycs', compact('kycs'));
    }

    public function approveZwmbKyc($id, $loanId){

        $client = Client::find($id);
        $kyc = Kyc::where('natid', $client->natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();
        $locale = Localel::findOrFail($client->locale_id);

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

        if ($client->fsb_score == null) {
            return redirect()->back()->with('error', 'Seems I did not record this client\'s local Financial board records, just verify if the details are there.');
        }

        DB::table("clients")
            ->where("id", $id)
            ->update(['flag' => 'ZWMB', 'updated_at' => now()]);

        DB::table("kycs")
            ->where("natid", $client->natid)
            ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

        if ($client->emp_sector == 'Government') {
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 11, 'updated_at' => now()]);

        } else {
            DB::table("loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 3, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Customer Details updated successfully.');
    }

    public function zwmbEvaluateKyc($id, $loanId){
        $kyc = Kyc::findOrFail($id);
        $bank = Bank::where('id',$kyc->bank)->first();
        $client = Client::where('natid',$kyc->natid)->first();
        $loan = Loan::where('id', $loanId)->first();
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();

        return view('kycs.zwmb-review', compact('kyc', 'client', 'bank', 'loan', 'ssbInfo'));
    }

    public function approveZwmbEvaluateKyc(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'kyc'                  => 'required',
                'loan'            => 'required',
                'acc_number'             => 'required',
            ],
            [
                'kyc.required'                   => 'We need the KYC of the client in review.',
                'loan.required'                   => 'Client has to have a loan created',
                'acc_number.required' => 'What is the client\'s ZWMB account number?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = Client::where('natid', $request->kyc)->first();
        $kyc = Kyc::where('natid', $request->kyc)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $request->loan)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

        if ($client->fsb_score == null) {
            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining an FCB Status Check.');
        }

        DB::table("kycs")
            ->where("natid", $request->kyc)
            ->update(['cbz_status' => '1', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        DB::table("clients")
            ->where("natid", $request->kyc)
            ->update(['reds_number' => $request->acc_number, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC has been authorized successfully.');
    }

    public function rejectZwmbEvaluateKyc($natid, $loanId) {

        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid', $natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = Loan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the details.');
        }

        if ($client->fsb_score == null) {
            return redirect()->back()->with('error', 'Client will not be evaluated without obtaining an FCB Status Check.');
        }

        DB::table("kycs")
            ->where("natid", $natid)
            ->update(['cbz_status' => '2', 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

        return redirect()->back()->with('success', 'KYC has been rejected successfully.');
    }

    public function zwmbClientsKycs(){
        $kycs = DB::table('kycs as k')
            ->join('clients as c','k.natid','=','c.natid')
            ->select('c.first_name','c.last_name','k.id','k.natid','k.status','k.created_at','c.mobile')
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('c.flag','=','ZWMB')
            ->get();
        return view('kycs.zwmbclient-kycs', compact('kycs'));
    }

    public function approveZambianClient(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'sign_pic'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'files'  => 'required|mimes:pdf|max:4096',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = Zambian::findOrFail($request->input('clientid'));

        $crbFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('files')->getClientOriginalExtension();

        if($request->hasFile('files')) {

            if ($request->file('files')->isValid()) {
                $crbDoc = $request->file('files');

                Storage::disk('public')->put('zam_crb_reports/' . $crbFilename, File::get($crbDoc));

            } else {
                return redirect()->back()->with('error','Invalid file supplied')->withInput();
            }
        }
        else {
            return redirect()->back()->with('error','CRB file not found')->withInput();
        }

        $signFilename = str_replace("/","_",$request->input('nrc')) . '.' . $request->file('sign_pic')->getClientOriginalExtension();

        if($request->hasFile('sign_pic')) {

            if ($request->file('sign_pic')->isValid()) {
                $signPhoto = $request->file('sign_pic');

                Storage::disk('public')->put('zam_signs/' . $signFilename, File::get($signPhoto));

            } else {
                return redirect()->back()->with('error','Invalid Signature photo')->withInput();
            }
        }
        else {
            return redirect()->back()->with('error','Signature not found')->withInput();
        }

        $client->officer_stat = true;
        $client->officer = auth()->user()->name;
        $client->files = $crbFilename;
        $client->sign_pic = $signFilename;
        $client->save();

        return redirect()->back()->with('success', 'KYC approved successfully.');
    }

    public function getPendingDeviceKyc(){

        $kycs = DB::table('device_loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
            ->where('l.loan_status','=',1)
            ->where('c.reds_number','=', null)
            ->where('k.status', '=',false)
            ->orderByDesc('l.created_at')
            ->distinct()
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('l.deleted_at', '=',null)
            ->get();

        return view('kycs.pending-device-kyc', compact('kycs'));
    }

    public function getPendingAuthorizationDeviceKycs(){

        $kycs = DB::table('device_loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->join('kycs as k', 'k.natid','=','c.natid')
            ->select('l.id','c.first_name','c.last_name','c.natid','c.reds_number','l.amount','l.monthly','l.loan_status','l.loan_type','k.id as kid','k.status','l.created_at')
            ->where('l.loan_status','=',4)
            ->where('c.reds_number','=', null)
            ->where('k.status', '=',true)
            ->orderByDesc('l.created_at')
            ->distinct()
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('l.deleted_at', '=',null)
            ->get();

        return view('kycs.pending-device-kyc', compact('kycs'));
    }

    public function evaluateDeviceKyc($id, $loanId){
        $kyc = Kyc::findOrFail($id);
        $bank = Bank::where('id',$kyc->bank)->first();
        $client = Client::where('natid',$kyc->natid)->first();
        $loan = DeviceLoan::where('id', $loanId)->first();
        $ssbInfo = SsbDetail::where('natid', $kyc->natid)->first();

        return view('kycs.review-dev-kyc', compact('kyc', 'client', 'bank', 'loan', 'ssbInfo'));
    }

    public function approveKycForDeviceFinance($natid, $loanId)
    {
        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid', $natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = DeviceLoan::where('id', $loanId)->first();
        $locale = Localel::findOrFail($client->locale_id);

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

        if ($loan->loan_status == 0) {
            return redirect()->back()->with('error', 'Loan has not yet been signed. Please make sure it has been signed.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s FCB status just verify if the details are there.');
//        }

        if ($client->fsb_status == 'ADVERSE') {
            DB::table("device_loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 11, 'notes' => 'Loan declined because of Open adverse item(s).', 'updated_at' => now()]);

            Http::post(getBulkSmsUrl() . "to=+263" . $client->mobile . "&msg=Good day " . $client->first_name . ", Your loan application of $" . $loan->amount . " has been declined. You have other running loan(s).")
                ->body();

            return redirect()->back()->with('error', 'The client has open adverse item(s) and will not be able to borrow at this time. The loan has been declined and notified the client.');
        }

        function generateEshagiNumber($natid)
        {
            $inClient = Client::where('natid', $natid)->first();
            $locale = Localel::findOrFail($inClient->locale_id);
            $count = Client::whereRaw('YEAR(created_at) = ?', date('Y'))->where('locale_id', $locale->id)->count();

            $eshagiNumber = $locale->country_short . '/' . date('y') . '/' . sprintf("%09d", $count) . '/' . 'ESG';

            return $eshagiNumber;
        }

        $customerType = $locale->country . ' Salary Based Device Loan';
        $subIndividual = $locale->country_short . ' SBP';
        $shagiNumber = generateEshagiNumber($client->natid);

        if (isset($shagiNumber)) {
            DB::table("clients")
                ->where("id", $client->id)
                ->update(['reds_id' => $client->id, 'reds_type' => $customerType, 'reds_sub' => $subIndividual, 'reds_number' => $shagiNumber, 'flag' => 'DEVFIN', 'updated_at' => now()]);

            DB::table("kycs")
                ->where("natid", $client->natid)
                ->update(['status' => true, 'reviewer' => auth()->user()->name, 'updated_at' => now()]);

            DB::table("device_loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 4, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Customer Details updated successfully.');
    }

    public function authorizeKycForDeviceFinance($natid, $loanId) {
        $client = Client::where('natid', $natid)->first();
        $kyc = Kyc::where('natid',$natid)->first();
        $bankRacho = Bank::where('id', $kyc->bank)->first();
        $loan = DeviceLoan::where('id', $loanId)->first();

        if (is_null($client)) {
            return redirect()->back()->with('error', 'Client info is missing. Please verify the client personal details.');
        } elseif (is_null($kyc)) {
            return redirect()->back()->with('error', 'Client kyc info is missing. Please verify the KYC details.');
        } elseif (is_null($bankRacho)) {
            return redirect()->back()->with('error', 'Bank info is missing for client. Please verify the banking details.');
        } elseif (is_null($loan)) {
            return redirect()->back()->with('error', 'Loan info is missing for client. Please verify the loan details.');
        }

        if($loan->loan_status == 0){
            return redirect()->back()->with('error', 'Loan has not yet been signed. Please make sure it has been signed.');
        }

        if($loan->loan_status != 4){
            return redirect()->back()->with('error', 'Loan has not yet been approved by Loans Officer.');
        }

        if($kyc->status == false){
            return redirect()->back()->with('error', 'KYC has not yet been approved by Loans Officer.');
        }

//        if ($client->fsb_score == null) {
//            return redirect()->back()->with('error', 'Seems I did not record this client\'s FCB status just verify if the details are there.');
//        }

        if ($client->fsb_status == 'ADVERSE') {
            DB::table("device_loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 11, 'notes' => 'Loan declined because of Open adverse item(s).', 'updated_at' => now()]);

            Http::post(getBulkSmsUrl()."to=+263".$client->mobile."&msg=Good day ".$client->first_name.", Your loan application of $".$loan->amount." has been declined. You have other running loan(s).")
                ->body();

            return redirect()->back()->with('error', 'The client has open adverse item(s) and will not be able to borrow at this time. The loan has been declined and notified the client.');
        }

        $details = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.Config::get('configs.AUTH_CODE')
        ])
            ->post('https://api-main.loandisk.com/'.Config::get('configs.PUBLIC_KEY').'/'.Config::get('configs.ZIM_USD_BRANCH').'/borrower',[
                'borrower_unique_number' => $client->natid,
                'borrower_firstname' => $client->first_name,
                'borrower_lastname' => $client->last_name,
                'borrower_business_name' => $client->employer,
                'borrower_country' => 'ZW',
                'borrower_title' => getPersonTitleByText($client->title),
                'borrower_working_status' => 'Employee',
                'borrower_gender' => $client->gender,
                'borrower_mobile' => $client->mobile,
                'borrower_dob' => date_format($client->dob, 'd/m/Y'),
                'borrower_description' => $client->description ?? $client->first_name.' '.$client->last_name,
                'custom_field_11543' => $client->emp_sector,
                'custom_field_11302' => $client->ecnumber,
                'custom_field_11303' => $client->emp_sector,
                'custom_field_11085' => $kyc->kin_fname.' '.$kyc->kin_lname,
                'custom_field_11083' => $kyc->kin_fname,
                'custom_field_11082' => $client->house_num.' '.$client->street.' '.$client->surburb.' '.$client->city,
                'custom_field_11084' => $kyc->kin_number,
                'custom_field_11789' => $bankRacho->bank,
                'custom_field_11788' => $kyc->acc_number,
                'custom_field_11790' => $kyc->branch,
            ])
            ->body();

        $resp=json_decode($details, TRUE);

        if (isset($resp['response']['Errors'])) {
                return redirect()->back()->with('error', 'Something is wrong here, I got the following error from LoanDisk: '.$resp['response']['Errors'][0]);
        }

        $client->reds_id = $resp['response']['borrower_id'];
        $client->reds_type = 'LOANDISK';
        $client->reds_sub = 'DEVICE_FINANCING';
        $client->reds_number = $resp['response']['borrower_id'];
        $client->save();

        if ($client->save()){
            DB::table("kycs")
                ->where("natid", $natid)
                ->update(['cbz_status' => true, 'cbz_evaluator' => auth()->user()->name, 'updated_at' => now()]);

            DB::table("device_loans")
                ->where("id", $loan->id)
                ->update(['loan_status' => 5, 'updated_at' => now()]);
        }


        return redirect()->back()->with('success', 'Customer Details updated successfully.');
    }

    public function getUnclaimedPendingKyc(){
        $kycs = DB::table('kycs as k')
            ->join('clients as c', 'k.natid','=','c.natid')
            ->select('c.id','c.first_name','c.last_name','c.natid','c.reds_number','k.id as kid','k.status','k.created_at')
            ->where('c.reds_number','=', null)
            ->where('k.status', '=',false)
            ->orderByDesc('k.created_at')
            ->distinct()
            ->where('c.locale_id','=',auth()->user()->locale)
            ->where('c.deleted_at', '=',null)
            ->get();

        return view('zwmb.zwmb-pending-kycs', compact('kycs'));
    }

    public function approveKYC($kycid){

        $reviewer = auth()->user()->first_name . " " . auth()->user()->last_name;

        DB::statement("UPDATE kycs SET reviewer='" . $reviewer ."', kyc_status=1 WHERE id=". $kycid);

        $kyc = kyc::findOrFail($kycid);
        $kyc->status = 1 ;
        $kyc->save();

        $client = Client::where('natid',$kyc->natid)->first();
        $bank = Bank::where('id',$kyc->bank)->first();
        $loan = Loan::where('client_id',$client->id)->first();

        $emails = [$client->email, 'loanszam@astroafrica.tech'];

        $natids = explode("/",$client->natid);

        $pdfFileName = storage_path() . DIRECTORY_SEPARATOR . 'app/downloads' . DIRECTORY_SEPARATOR . "KYCForm_".$natids[0]."_".$natids[0]."_".$natids[0].".pdf";
        $pdf = \PDF::loadView('kycs.kyc-form', compact('client','kyc', 'bank', 'loan'));
        $pdf->save($pdfFileName);
        $data=[];
        /*$attachNP='http://localhost/AstroCredZambiaLive/public/nationalids/' . $kyc->national_pic;
        $attachNPB='http://localhost/AstroCredZambiaLive/public/nationalids/' . $kyc->national_pic_back;
        $attachPP='http://localhost/AstroCredZambiaLive/public/pphotos/' . $kyc->passport_pic;
        $attachSP = 'http://localhost/AstroCredZambiaLive/public/payslips/' . $kyc->payslip_pic;
        $attachEAL = 'http://localhost/AstroCredZambiaLive/public/empletters/' . $kyc->emp_approval_letter;*/
        $attachNP = public_path() . DIRECTORY_SEPARATOR . 'nationalids' . DIRECTORY_SEPARATOR . $kyc->national_pic;
        $attachPP = public_path() . DIRECTORY_SEPARATOR . 'pphotos' . DIRECTORY_SEPARATOR . $kyc->passport_pic;
        $attachNPB = public_path() . DIRECTORY_SEPARATOR . 'nationalids' . DIRECTORY_SEPARATOR . $kyc->national_pic_back;
        $attachSP = public_path() . DIRECTORY_SEPARATOR . 'payslips' . DIRECTORY_SEPARATOR . $kyc->payslip_pic;
        $attachEAL = public_path() . DIRECTORY_SEPARATOR . 'empletters' . DIRECTORY_SEPARATOR . $kyc->emp_approval_letter;

     /*   Mail::send('emails.new-loan-kyc', $data, function($message)use($pdf, $emails, $pdfFileName, $attachNP, $attachNPB, $attachPP, $attachSP, $attachEAL) {
            $message->to($emails)->subject('KYC of a new loan request');
            $message->attach($pdfFileName);
            $message->attach($attachNP);
            $message->attach($attachNPB);
            $message->attach($attachPP);
            $message->attach($attachSP);
            $message->attach($attachEAL);
        });*/

        //return $pdf->stream("KYCForm".$client->natid.".pdf");
        return redirect()->back()->with('success', 'KYC approved successfully and kyc documents mailed to financier and to the customer.');
    }

    public function rejectKYC(Request $request, $kycid){
        $rejectedFor = $request->rejectReason;
        DB::statement("UPDATE kycs SET reject_reason='". $rejectedFor ."' AND kyc_status=0 WHERE id=". $kycid);
        $kyc = kyc::findOrFail($kycid);
        $client = Client::where('natid',$kyc->natid)->first();
        $emails = [$client->email, 'loanszam@astroafrica.tech'];
        $data = ['rejectedFor' => $rejectedFor, 'first_name' => $client->first_name, 'last_name' => $client->last_name];
        Mail::send('emails.loan-kyc-rejected', $data, function($message)use($emails) {
            $message->to($emails)->subject('KYC Rejected');
        });
        return redirect()->back()->with('success', 'KYC rejected successfully.');
    }
}
