<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Route;

class AdminDetailsController extends Controller
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
    public function listRoutes()
    {
        $routes = Route::getRoutes();
        $data = [
            'routes' => $routes,
        ];

        return view('pages.admin.route-details', $data);
    }

    /**
     * Display active users page.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeUsers()
    {
        $users = User::count();

        return view('pages.admin.active-users', ['users' => $users]);
    }

    public function checkRedSphereServerStatus(){
        $ch = curl_init("192.168.145.54");
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode !== 0){
            return redirect()->back()->with('success', 'Redsphere server appears to be online.');
        } else {
            exec("strongswan restart");
            return redirect()->back()->with('error', 'Redsphere server appears to be offline, but that ping should have made it online.');
        }
    }
}
