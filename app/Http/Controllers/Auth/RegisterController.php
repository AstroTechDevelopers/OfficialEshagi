<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Profile;
use App\Models\User;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use ActivationTrait;
    use CaptchaTrait;
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/activate';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => 'logout',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['captcha'] = $this->captchaCheck();

        $data['captcha'] = true;

        return Validator::make(
            $data,
            [
                'title'                  => 'required|max:255',
                'first_name'            => 'required|alpha_dash',
                'last_name'             => 'required|alpha_dash',
                'natid'                 => 'required|max:15|unique:users|regex:/^[0-9]{2}-[0-9]{6,7}-[a-zA-Z]-[0-9]{2}$/',
                'email'                 => 'nullable|email|max:255|unique:users',
                'mobile'                 => 'required|max:10|unique:users',
                'dob'                 => 'required|date',
                'gender'                 => 'required',
                'marital_status'        => 'required',
                'dependants'        => 'required|numeric',
                'nationality1'        => 'required',
                'emp_sector'        => 'required',
                'employer'        => 'required',
                'ecnumber'        => 'required',
                'salary'        => 'required',
                'house_num'        => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'        => 'required',
                'province'        => 'required',
                'country'        => 'required',
                'home_type'        => 'required',
                'password'              => 'required|min:6|max:30|confirmed',
                'password_confirmation' => 'required|same:password',
                'g-recaptcha-response'  => '',
                'captcha'               => 'required|min:1',
            ],
            [
                'title.required'           => 'Please select your title.',
                'first_name.required'           => trans('auth.fNameRequired'),
                'last_name.required'            => trans('auth.lNameRequired'),
                'natid.required'                => 'We need your National ID Number to proceed.',
                'natid.unique'                   => 'This ID Number is already registered with eShagi.',
                'natid.regex'                   => 'Your ID number has to be of the format xx-xxxxxxx X xx',
                'email.required'                => trans('auth.emailRequired'),
                'email.email'                   => trans('auth.emailInvalid'),
                'email.unique'                   => 'This email is already registered with eShagi.',
                'mobile.required'                   => 'We need a phone number to create your account.',
                'mobile.unique'                   => 'This phone number is already registered with eShagi.',
                'dob' => 'We need your date of birth',
                'gender' => 'What is your gender?',
                'marital_status' => 'What is your marital status?',
                'dependants' => 'Do you have any dependants?',
                'nationality1' => 'What is your nationality?',
                'emp_sector' => 'Which employment sector are you in?',
                'employer' => 'What is the name  of your employer?',
                'ecnumber' => 'At your workplace, what is your employment number?',
                'salary' => 'What is your net monthly salary?',
                'house_num' => 'State your house number where you reside',
                'street' => 'State the street name where you reside',
                'surburb' => 'Which surburb do you stay in?',
                'city' => 'Please state the city you\'re from?',
                'province' => 'Which province are you in?',
                'country' => 'State your country if?',
                'home_type' => 'What is the state of your home?',
                'password.required'             => trans('auth.passwordRequired'),
                'password.min'                  => trans('auth.PasswordMin'),
                'password.max'                  => trans('auth.PasswordMax'),
                'g-recaptcha-response.required' => trans('auth.captchaRequire'),
                'captcha.min'                   => trans('auth.CaptchaWrong'),
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $ipAddress = new CaptureIpTrait();

        $role = Role::where('slug', '=', 'client')->first();
        $activated = true;

        $client = Client::create([
            'title'              => $data['title'],
            'first_name'        => strip_tags($data['first_name']),
            'last_name'         => strip_tags($data['last_name']),
            'natid'             => $data['natid'],
            'email'             => $data['email'],
            'mobile'             => $data['mobile'],
            'dob'             => $data['dob'],
            'gender'             => $data['gender'],
            'marital_state'             => $data['marital_state'],
            'dependants'             => $data['dependants'],
            'nationality'             => $data['nationality'],
            'house_num'             => $data['house_num'],
            'street'             => $data['street'],
            'surburb'             => $data['surburb'],
            'city'             => $data['city'],
            'province'             => $data['province'],
            'country'             => $data['country'],
            'locale_id'             => $data['locale_id'],
            'occ_type'             => $data['occ_type'],
            'emp_sector'             => $data['emp_sector'],
            'employer'             => $data['employer'],
            'ecnumber'             => $data['ecnumber'],
            'salary'             => $data['salary'],
            'home_type'             => $data['home_type'],
            'password'          => Hash::make($data['password']),
        ]);


        $client->save();

        if ($client->save()) {
            $user = User::create([
                'name'              => $data['name'],
                'first_name'        => $data['first_name'],
                'last_name'         => $data['last_name'],
                'email'             => $data['email'],
                'natid'             => $data['natid'],
                'mobile'             => $data['mobile'],
                'utype'             => 'Client',
                'password'          => Hash::make($data['password']),
                'token'             => str_random(64),
                'signup_ip_address' => $ipAddress->getClientIp(),
                'activated'         => $activated,
            ]);

            $user->attachRole($role);
            $this->initiateEmailActivation($user);

            $profile = new Profile();
            $user->profile()->save($profile);
        }

        return $client;
    }
}
