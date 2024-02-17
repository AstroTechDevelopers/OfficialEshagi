<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\Localel;
use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersManagementController extends Controller
{
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('id','name', 'first_name', 'last_name','natid','mobile', 'email');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = " <form class='btn btn-sm btn-danger' method='POST' action='users/". $row['id']."'>
                             <input type='hidden' name='_token' value='".csrf_token()."'>
                             <input name='_method' type='hidden' value='DELETE'>
                             <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#confirmDelete'
                                data-title='Delete User' data-message='Are you sure you want to delete this user ?'>
                                <i class='mdi mdi-trash-can-outline' aria-hidden='true'></i>
                                </button>

                             </form>
                            <a class='btn btn-sm btn-success' href='users/". $row['id']."' >
                                <i class='mdi mdi-eye-outline' aria-hidden='true'></i>
                            </a>

                            <a class='btn btn-sm btn-info' href='users/".$row['id']."/edit' >
                                <i class='mdi mdi-account-edit-outline' aria-hidden='true'></i>
                            </a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return View('usersmanagement.show-users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all()->where('name', '!=', 'Funder')->where('name', '!=', 'Partner')->where('name', '!=', 'Client')->where('name', '!=', 'Astrogent');
        $localels = Localel::all();

        return view('usersmanagement.create-user', compact('roles', 'localels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $username = generateUsername($request->input('first_name'), $request->input('last_name'));

        if (in_array($request->input('role'), array('1','2','3','4','5','6','8','9','10'))) {
            $usertype = 'System';
        } elseif ($request->input('role') == 11){
            $usertype = 'Redsphere';
        } else {
            $usertype = 'Womansbank';
        }

        $validator = Validator::make(
            $request->all(),
            [
                'first_name'            => 'required',
                'last_name'             => 'required',
                'email'                 => 'required|email|max:255|unique:users',
                'natid'                 => 'required|max:255|unique:users',
                'mobile'                 => 'required|max:255|unique:users',
                'password'              => 'required|min:8|confirmed',
                'password_confirmation' => 'required|same:password',
                'role'                  => 'required',
                'locale_id'       => 'required',
            ],
            [
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required'  => trans('auth.lNameRequired'),
                'email.required'      => trans('auth.emailRequired'),
                'email.email'         => trans('auth.emailInvalid'),
                'natid.required'         => 'National ID number is required',
                'natid.unique'         => 'This National ID number is already is already associated to someone else.',
               'mobile.required'         => 'Mobile number is required',
                'mobile.unique'         => 'This Mobile number is already is already associated to someone else.',
                'password.required'   => trans('auth.passwordRequired'),
                'password.min'        => trans('auth.PasswordMin'),
                'password.max'        => trans('auth.PasswordMax'),
                'role.required'       => trans('auth.roleRequired'),
                'locale_id.required'        => 'Where is the user based?',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();

        $user = User::create([
            'name'             => $username,
            'first_name'       => strip_tags($request->input('first_name')),
            'last_name'        => strip_tags($request->input('last_name')),
            'email'            => $request->input('email'),
            'natid'            => strtoupper($request->input('natid')),
            'mobile'            => $request->input('mobile'),
            'utype'            => $usertype,
            'password'         => Hash::make($request->input('password')),
            'token'            => str_random(64),
            'admin_ip_address' => $ipAddress->getClientIp(),
            'activated'        => 1,
            'locale'        => $request->input('locale_id'),
        ]);

        $user->profile()->save($profile);
        $user->attachRole($request->input('role'));
        $user->save();

        return redirect('users')->with('success', trans('usersmanagement.createSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('usersmanagement.show-user', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all()->where('name', '!=', 'Funder')->where('name', '!=', 'Partner')->where('name', '!=', 'Client')->where('name', '!=', 'Astrogent');
        $localels = Localel::all();

        foreach ($user->roles as $userRole) {
            $currentRole = $userRole;
        }

        $data = [
            'user'        => $user,
            'roles'       => $roles,
            'localels'       => $localels,
            'currentRole' => $currentRole,
        ];

        return view('usersmanagement.edit-user')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User                     $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $emailCheck = ($request->input('email') !== '') && ($request->input('email') !== $user->email);
        $ipAddress = new CaptureIpTrait();

        $validator = Validator::make($request->all(), [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email|max:255|unique:users,email,'.$user->id,
            'natid'         => 'required|max:255|unique:users,natid,'.$user->id,
            'mobile'        => 'required|max:255|unique:users,mobile,'.$user->id,
            'password'      => 'nullable|confirmed|min:8|confirmed',
            'password_confirmation' => 'nullable|same:password',
            'role'                  => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->first_name = strip_tags($request->input('first_name'));
        $user->last_name = strip_tags($request->input('last_name'));
        $user->natid = strtoupper(strip_tags($request->input('natid')));
        $user->mobile = strip_tags($request->input('mobile'));

        if ($emailCheck) {
            $user->email = $request->input('email');
        }

        if ($request->input('password') !== null) {
            $user->password = Hash::make($request->input('password'));
        }

        $userRole = $request->input('role');
        if ($userRole !== null) {
            $user->detachAllRoles();
            $user->attachRole($userRole);
        }

        $user->updated_ip_address = $ipAddress->getClientIp();

        switch ($userRole) {
            case 3:
                $user->activated = 0;
                break;

            default:
                $user->activated = 1;
                break;
        }

        $user->save();

        return back()->with('success', trans('usersmanagement.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        $ipAddress = new CaptureIpTrait();

        if ($user->id !== $currentUser->id) {
            $user->deleted_ip_address = $ipAddress->getClientIp();
            $user->save();
            $user->delete();

            return redirect('users')->with('success', trans('usersmanagement.deleteSuccess'));
        }

        return back()->with('error', trans('usersmanagement.deleteSelfError'));
    }

    public function updateUserPassword(UpdateUserPasswordRequest $request, $id)
    {
        $currentUser = \Illuminate\Support\Facades\Auth::user();
        $user = User::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        if ($request->input('password') !== null) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->updated_ip_address = $ipAddress->getClientIp();
        $user->password_changed = true;
        $user->pwd_last_changed = now();
        $user->first_log_in = 1;
        $user->save();

        return redirect('home')->with('success', 'Your password has been updated successfully.');
    }
}
