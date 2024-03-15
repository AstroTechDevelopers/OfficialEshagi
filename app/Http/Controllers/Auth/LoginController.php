<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';

    protected $mobile;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Logout, Clear Session, and Return.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile'    => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('mobile'), FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'mobile';

        $request->merge([
            $login_type => $request->input('mobile')
        ]);

        $mobileNumber = '0' . $request->input('mobile');

        if($login_type == 'mobile')
        {
            $userActive = User::where('mobile',$request->input('mobile'))->orWhere('mobile',$mobileNumber)->orWhere('mobile',ltrim($request->input('mobile'), '0'))->where('status','1')->first();

        }else if($login_type == 'email'){
            $userActive = User::where('email',$request->input('mobile'))->where('status','1')->first();
        }


        // Check client registration validated or not. Do not allow to login if not validated
        if(empty($userActive)){
            return redirect()->back()
            ->withInput()
            ->withErrors([
                'mobile' => 'This account is not verified. Please verify your account.',
            ]);
        }else{
            if(!empty($userActive) && !empty(Hash::check($request->password, $userActive->password))) {
                Auth::login($userActive);
                if($request->input('isShop') == 'yes'){
                    return redirect('shopping');
                }
            }
            return redirect()->back()
            ->withInput()
            ->withErrors([
                'mobile' => 'These credentials(Mobile Number/Email or password) do not match our records.',
            ]);
        }
    }
}
