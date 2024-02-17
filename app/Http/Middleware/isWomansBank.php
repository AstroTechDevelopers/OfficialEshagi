<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class isWomansBank
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $currentRoute = Route::currentRouteName();

        if(Auth::User()->hasRole('womensbank')){
            return $next($request);
        } else {
            Log::info('UnAuthorized user attempted to visit and approve KYC meant for Womens Bank Staff via '.$currentRoute.'. ', [$user]);
            return redirect()->route('no.entry');
        }
    }
}
