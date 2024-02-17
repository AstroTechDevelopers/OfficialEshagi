<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RedSphereMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();

        if(Auth::User()->hasRole('redsphere')){
            return $next($request);
        } else {
            Log::info('UnAuthorized user attempted to visit and approve KYC meant for RedSphere Staff via '.$currentRoute.'. ', [$user]);
            return redirect()->route('no.entry');
        }
    }
}
