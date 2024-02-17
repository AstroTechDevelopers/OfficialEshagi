<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Masetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcbChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcbcheck:updatefcb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periodically check for FCB status for newly registered clients.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clientsCount = Client::where('locale_id',1)
            ->where('fsb_score',null)
            ->where('fsb_status',null)
            ->where('fsb_rating',null)
            ->count();
        $settings = Masetting::find(1)->first();

        if ($clientsCount > 0){
            $clients = Client::where('locale_id',1)
                ->where('fsb_score',null)
                ->where('fsb_status',null)
                ->where('fsb_rating',null)
                ->get();
            foreach ($clients as $client) {

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

//                $details = Http::post('https://www.fcbureau.co.zw/api/newIndividual?dob=' . $dob . '&names=' . $client->first_name . '&surname=' . $client->last_name . '&national_id=' . $idFormated . '&gender=' . $gender . '&search_purpose=1&email=' . getFcbUsername() . '&password=' . getFcbPassword() . '&drivers_licence=&passport=&married=' . $marital . '&nationality=3&streetno=' . $client->house_num . '&streetname=' . $client->street . '&building=&surbub=' . $client->surburb . '&pbag=&city=' . $client->city . '&telephone=&mobile=' . $client->mobile . '&ind_email=' . $client->email . '&property_density=2&property_status=' . $ownership . '&occupation_class=0&employer=' . $client->employer . '&employer_industry=0&salary_band=8&loan_purpose=&loan_amount=')
//                    ->body();
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
                    Log::error('FCB check error: '.'There is some missing client info: ' . $json['missing information']);
                } elseif ($code == 401) {
                    Log::error('FCB check error: '.'Authorization error: : ' . $json['error'] . '. Please check eShagi account status with FCB.');
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
                    } else {
                        $scoreMeans = 'Low Risk'; //($score >= 351 && $score <= 400)
                    }

                    DB::table("clients")
                        ->where("id", $client->id)
                        ->update(['fsb_score' => $score, 'fsb_status' => $status, 'fsb_rating' => $scoreMeans, 'updated_at' => now()]);

                    Http::post($settings->bulksmsweb_baseurl."to=+263".$client->mobile."&msg=Great News ".$client->first_name.", You qualify for a loan or store credit of up to $".$client->cred_limit." with eShagi subject to financial approval. Login and complete the application.")
                        ->body();
                }

            }
            Log::info('FCB check and update done successfully for '.$clients->count().' clients, at :' . now());
        } else {
            Log::info('No clients were found with outstanding FCB checks, at :' . now());
        }

        return 0;
    }
}
