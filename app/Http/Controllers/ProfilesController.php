<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserAccount;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserProfile;
use App\Models\Bank;
use App\Models\Client;
use App\Models\Kyc;
use App\Models\Kycchange;
use App\Models\Localel;
use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use App\Notifications\KYCChangeRequest;
use App\Notifications\SendGoodbyeEmail;
use App\Traits\CaptureIpTrait;
use File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Image;
use jeremykenedy\Uuid\Uuid;
use Validator;
use View;

class ProfilesController extends Controller
{
    protected $idMultiKey = '618423'; //int
    protected $seperationKey = '****';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Fetch user
     * (You can extract this to repository method).
     *
     * @param $username
     *
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        return User::with('profile')->wherename($username)->firstOrFail();
    }

    /**
     * Display the specified resource.
     *
     * @param string $username
     *
     * @return Response
     */
    public function show($username)
    {
        try {
            $user = $this->getUserByUsername($username);
        } catch (ModelNotFoundException $exception) {
            abort(404);
        }

        $kyc = Kyc::where('natid', $user->natid)->first();
        $client = Client::where('natid', $user->natid)->first();

        if (auth()->user()->hasRole('client')) {
            if(is_null($kyc)){
                return redirect('remaining-details');
            } elseif ($kyc->national_pic == null OR $kyc->passport_pic == null OR $kyc->payslip_pic== null) {
                return view('clients.register-three', compact('yuser'));
            }
            $bank = Bank::where('id', $kyc->bank)->first();
        }
        $data = [
            'user'         => $user,
            'kyc'         => $kyc,
            'client'         => $client,
            'bank'         => $bank ?? '',
        ];

        return view('profiles.show')->with($data);
    }

    /**
     * /profiles/username/edit.
     *
     * @param $username
     *
     * @return mixed
     */
    public function edit($username)
    {
        try {
            $user = $this->getUserByUsername($username);
        } catch (ModelNotFoundException $exception) {
            return view('pages.status')
                ->with('error', trans('profile.notYourProfile'))
                ->with('error_title', trans('profile.notYourProfileTitle'));
        }

        if ($user->hasRole('root')) {

            $data = [
                'user'         => $user,
            ];
        } elseif (auth()->user()->hasRole('client')) {

            $kyc = Kyc::where('natid', $user->natid)->first();

            $client = Client::where('natid', $user->natid)->first();

            $banks = Bank::all();
            $currentBank = Bank::where('id', $kyc->bank)->first();

        }

        $data = [
            'user'         => $user,
            'client'         => $client ?? '',
            'kyc'         => $kyc ?? '',
            'banks'         => $banks ?? '',
            'currentBank' => $currentBank ?? '',

        ];

        return view('profiles.edit')->with($data);
    }

    /**
     * Update a user's profile.
     *
     * @param \App\Http\Requests\UpdateUserProfile $request
     * @param $username
     *
     * @throws Laracasts\Validation\FormValidationException
     *
     * @return mixed
     */
    public function update(UpdateUserProfile $request, $username)
    {
        $user = $this->getUserByUsername($username);

        $validator = Validator::make($request->all(),
            [
                'house_num'       => 'required',
                'street'        => 'required',
                'surburb'        => 'required',
                'city'     => 'required',
                'province'     => 'required',
                'country'     => 'required',
                'home_type'     => 'required',
                'kin_fname'     => 'required',
                'kin_lname'     => 'required',
                'kin_email'     => 'nullable|email',
                'kin_work'     => 'required',
                'kin_number'     => 'required',
                'bank'     => 'required',
                'branch'     => 'required',
                'branch_code'     => 'required',
                'acc_number'     => 'required',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $ipAddress = new CaptureIpTrait();

        $user->updated_ip_address = $ipAddress->getClientIp();
        $user->save();

        if ($user->save()) {
            $client = Client::where('natid', $user->natid)->first();

            $client->house_num = $request->input('house_num');
            $client->street = $request->input('street');
            $client->surburb = $request->input('surburb');
            $client->city = $request->input('city');
            $client->province = $request->input('province');
            $client->country = $request->input('country');
            $client->home_type = $request->input('home_type');

            $client->save();

            if ($client->save()) {
                $kyc = Kyc::where('natid', $user->natid)->first();

                $kyc->kin_fname = $request->input('kin_fname');
                $kyc->kin_lname = $request->input('kin_lname');
                $kyc->kin_email = $request->input('kin_email');
                $kyc->kin_work = $request->input('kin_work');
                $kyc->kin_number = $request->input('kin_number');
                $kyc->bank = $request->input('bank');
                $kyc->branch = $request->input('branch');
                $kyc->branch_code = $request->input('branch_code');
                $kyc->acc_number = $request->input('acc_number');

                $kyc->save();
            }
        }

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updateSuccess'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserAccount(Request $request, $id)
    {
        $currentUser = \Auth::user();
        $user = User::findOrFail($id);
        $yuser = $user;
        $emailCheck = ($request->input('email') !== '') && ($request->input('email') !== $user->email);
        $natidCheck = ($request->input('natid') !== '') && ($request->input('natid') !== $user->natid);
        $ipAddress = new CaptureIpTrait();
        $rules = [];

        if ($user->first_name != $request->input('first_name') || $user->last_name != $request->input('last_name')) {
            $parts = explode(' ', $_POST['first_name'] . ' ' . $_POST['last_name']);
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
                if ($exists) {
                    $i++;
                    $name = $username . $i;
                }
            } while ($exists);
        } else {
            $name = $user->name;
        }

        if ($user->name != $name) {
            $usernameRules = [
                'name' => 'required|max:255|unique:users,name,' . $user->id,
            ];
        } else {
            $usernameRules = [
                'name' => 'required|max:255|unique:users,name,' . $user->id,
            ];
        }
        if ($emailCheck) {
            $emailRules = [
                'email' => 'email|max:255|unique:users',
            ];
        } else {
            $emailRules = [
                'email' => 'email|max:255',
            ];
        }

        if ($natidCheck) {
            $natidRules = [
                'natid' => 'required|max:15|unique:users',
            ];
        } else {
            $natidRules = [
                'natid' => 'required|max:15',
            ];
        }
        $additionalRules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|max:10|unique:users,mobile,'.$user->id,
            'gross' => 'required',
            'net' => 'required',
            'payslip'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ];

        $rules = array_merge($usernameRules, $emailRules, $additionalRules, $natidRules);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (auth()->user()->hasRole('client')) {
            $client = Client::where('natid', $yuser->natid)->first();

            if($request->hasFile('payslip')) {

                if ($request->file('payslip')->isValid()) {

                    $payslip = $request->file('payslip');
                    $filename = $client->natid . '.' . $payslip->getClientOriginalExtension();
                    Storage::disk('public')->put('payslips/' . $filename, \Illuminate\Support\Facades\File::get($payslip));

                    $kycChange = Kycchange::create([
                        'client_id' => $client->id,
                        'natid' => $client->natid,
                        'mobile_no' => $request->input('mobile'),
                        'payslip' => $filename,
                        'gross' => $request->input('gross'),
                        'net' => $request->input('net'),
                    ]);

                    $kycChange->save();
                    $admins = User::where('utype','=','System')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new KYCChangeRequest($kycChange));
                    }
                } else {
                    return redirect()->back()->with('error', 'Invalid image supplied.');
                }
            } else {
                $kycChange = Kycchange::create([
                    'client_id' => $client->id,
                    'natid' => $client->natid,
                    'mobile_no' => $request->input('mobile'),
                    'payslip' => $client->natid,
                    'gross' => $request->input('gross'),
                    'net' => $request->input('net'),
                ]);

                $kycChange->save();
                $admins = User::where('utype','=','System')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new KYCChangeRequest($kycChange));
                }
            }

            return redirect('profile/' . $user->name . '/edit')->with('success', 'Your profile update request has been submitted. eShagi will update your profile once the information you provided is verified.');
        } else {

            $user->name = strip_tags($name);
        $user->first_name = strip_tags($request->input('first_name'));
        $user->last_name = strip_tags($request->input('last_name'));
        $user->mobile = $request->input('mobile');

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        if ($natidCheck) {
            $user->natid = $request->input('natid');
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        $user->save();

        if ($user->save()) {
            $client = Client::where('natid', $yuser->natid)->first();

            $client->first_name = $request->input('first_name');
            $client->last_name = $request->input('last_name');

            if ($natidCheck) {
                //$client->natid = $request->input('natid');
            }

            if ($emailCheck) {
                $client->email = $request->input('email');
            }

            $client->mobile = $request->input('mobile');
            $client->gross = $request->input('gross');
            $client->salary = $request->input('salary');
            $client->cred_limit = number_format(3.1 * $request->input('salary'), 2, '.', '');

            $client->save();
        }
    }

        return redirect('profile/'.$user->name.'/edit')->with('success', 'Your profile has been updated.');
    }

    public function updateBackendAccount(Request $request, $id)
    {
        $currentUser = \Auth::user();
        $user = User::findOrFail($id);
        $yuser = $user;
        $emailCheck = ($request->input('email') !== '') && ($request->input('email') !== $user->email);
        $natidCheck = ($request->input('natid') !== '') && ($request->input('natid') !== $user->natid);
        $ipAddress = new CaptureIpTrait();
        $rules = [];

        if ($user->first_name != $request->input('first_name') || $user->last_name != $request->input('last_name')) {
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
            $name = $user->name;
        }

        if ($user->name != $name) {
            $usernameRules = [
                'name' => 'required|max:255|unique:users,name,'.$user->id,
            ];
        } else {
            $usernameRules = [
                'name' => 'required|max:255|unique:users,name,'.$user->id,
            ];
        }
        if ($emailCheck) {
            $emailRules = [
                'email' => 'email|max:255|unique:users',
            ];
        } else {
            $emailRules = [
                'email' => 'email|max:255',
            ];
        }

        if ($natidCheck) {
            $natidRules = [
                'natid'                 => 'required|max:15|unique:users',
            ];
        } else {
            $natidRules = [
                'natid'                 => 'required|max:15',
            ];
        }
        $additionalRules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mobile'                 => 'required|max:10|unique:users,mobile,'.$user->id,

        ];

        $rules = array_merge($usernameRules, $emailRules, $additionalRules, $natidRules);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = strip_tags($name);
        $user->first_name = strip_tags($request->input('first_name'));
        $user->last_name = strip_tags($request->input('last_name'));
        $user->mobile = $request->input('mobile');

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        if ($natidCheck) {
            $user->natid = $request->input('natid');
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updateAccountSuccess'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserPasswordRequest $request
     * @param int                                          $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserPassword(UpdateUserPasswordRequest $request, $id)
    {
        $currentUser = \Auth::user();
        $user = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        if ($request->input('password') !== null) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->updated_ip_address = $ipAddress->getClientIp();
        $user->save();

        return redirect('profile/'.$user->name.'/edit')->with('success', trans('profile.updatePWSuccess'));
    }

    /**
     * Upload and Update user avatar.
     *
     * @param $file
     *
     * @return mixed
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $currentUser = \Auth::user();
            $avatar = $request->file('file');
            $filename = 'avatar.'.$avatar->getClientOriginalExtension();
            $save_path = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/avatar/';
            $path = $save_path.$filename;
            $public_path = '/images/profile/'.$currentUser->id.'/avatar/'.$filename;

            // Make the user a folder and set permissions
            File::makeDirectory($save_path, $mode = 0755, true, true);

            // Save the file to the server
            Image::make($avatar)->resize(300, 300)->save($save_path.$filename);

            // Save the public image path
            $currentUser->profile->avatar = $public_path;
            $currentUser->profile->save();

            return response()->json(['path' => $path], 200);
        } else {
            return response()->json(false, 200);
        }
    }

    /**
     * Show user avatar.
     *
     * @param $id
     * @param $image
     *
     * @return string
     */
    public function userProfileAvatar($id, $image)
    {
        return Image::make(storage_path().'/users/id/'.$id.'/uploads/images/avatar/'.$image)->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\DeleteUserAccount $request
     * @param int                                  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUserAccount(DeleteUserAccount $request, $id)
    {
        $currentUser = \Auth::user();
        $user = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        if ($user->id !== $currentUser->id) {
            return redirect('profile/'.$user->name.'/edit')->with('error', trans('profile.errorDeleteNotYour'));
        }

        // Create and encrypt user account restore token
        $sepKey = $this->getSeperationKey();
        $userIdKey = $this->getIdMultiKey();
        $restoreKey = config('settings.restoreKey');
        $encrypter = config('settings.restoreUserEncType');
        $level1 = $user->id * $userIdKey;
        $level2 = urlencode(Uuid::generate(4).$sepKey.$level1);
        $level3 = base64_encode($level2);
        $level4 = openssl_encrypt($level3, $encrypter, $restoreKey);
        $level5 = base64_encode($level4);

        // Save Restore Token and Ip Address
        $user->token = $level5;
        $user->deleted_ip_address = $ipAddress->getClientIp();
        $user->save();

        // Send Goodbye email notification
        $this->sendGoodbyEmail($user, $user->token);

        // Soft Delete User
        $user->delete();

        // Clear out the session
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/login/')->with('success', trans('profile.successUserAccountDeleted'));
    }

    /**
     * Send GoodBye Email Function via Notify.
     *
     * @param array  $user
     * @param string $token
     *
     * @return void
     */
    public static function sendGoodbyEmail(User $user, $token)
    {
        $user->notify(new SendGoodbyeEmail($token));
    }

    /**
     * Get User Restore ID Multiplication Key.
     *
     * @return string
     */
    public function getIdMultiKey()
    {
        return $this->idMultiKey;
    }

    /**
     * Get User Restore Seperation Key.
     *
     * @return string
     */
    public function getSeperationKey()
    {
        return $this->seperationKey;
    }
}
