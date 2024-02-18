<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use League\Uri\Http;
use Illuminate\Http\Response;


class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     * @throws GuzzleException
     */
    public function welcome()
    {
        $country = $this->getCountry();

        if ($country !== null) {
            if (property_exists($country, 'country')) {
                switch ($country->country) {
                    case "ZM":
                        config('COUNTRY', 'ZAMBIA');
                        return view("welcome_zim");
                    case "ZW":
                        config('COUNTRY', 'ZIMBABWE');
                        return view('welcome_zim');
                    default:
                        return view('errors.401');
                }
            } else {
                return view('errors.401');
            }
        } else {
            return view('errors.401');
        }
    }

    public function faq()
    {
        return view('faq');
    }

    public function contactUs()
    {
        return view('contact_us');
    }

    public function privacyPolicy()
    {
        return view('privacy_policy');
    }

    public function termsAndConditions()
    {
        return view('terms_and_conditions');
    }

    public function regOptions()
    {
        return view('auth.reg-options');
    }

    public function unAuthorized() {
        return view('errors.403');
    }

    public function changepassword (){
        return view('changepassword');
    }

    public function InactivePartner(){
        return view('auth.activation');
    }

    public function chooseLocale() {
        return view('global.choose-locale');
    }

    /**
     * @throws GuzzleException
     */
    private function getCountry()
    {
        $ipAddress = env('APP_ENV') == 'local' ? '197.220.16.10' : request()->ip();
        $client = new Client();
        $uri = config('app.country_base_url').$ipAddress.'?token='.config('app.country_api_token');

        try
        {
            $response = $client->get($uri);
            if($response->withStatus(200))
            {
                return json_decode($response->getBody()->getContents());
            }
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

}
