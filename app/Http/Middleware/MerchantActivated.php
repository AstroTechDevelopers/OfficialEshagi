<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class MerchantActivated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();

        if(Auth::User()->hasRole('partner')){
            if ($user->activated == true){
                return $next($request);
            } else {
                Log::info('Inactive Partner attempted to visit '.$currentRoute.'. ', [$user]);
                return redirect()->route('ia.partner');
            }
        } else {
            return $next($request);
        }
    }
}
