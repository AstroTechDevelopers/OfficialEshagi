<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserPassChanged
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
        if(auth()->user()->password_changed == false AND auth()->user()->hasRole('client')){
            return redirect('changepassword');
        } else {
            return $next($request);
        }
    }
}
