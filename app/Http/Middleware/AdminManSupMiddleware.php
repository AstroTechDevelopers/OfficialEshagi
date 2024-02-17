<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class AdminManSupMiddleware
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

        if(Auth::User()->hasRole('salesadmin') ||Auth::User()->hasRole('loansofficer') || Auth::User()->hasRole('manager') || Auth::User()->hasRole('group') || Auth::User()->hasRole('admin')|| Auth::User()->hasRole('root')){
            return $next($request);
        } else {
            Log::info('UnAuthorized user attempted to visit '.$currentRoute.'. ', [$user]);
            return redirect()->route('no.entry');
        }
    }
}
