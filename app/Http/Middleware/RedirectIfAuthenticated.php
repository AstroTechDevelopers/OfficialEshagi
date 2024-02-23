<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    //public function handle(Request $request, Closure $next, ...$guards)
    public function handle(Request $request, Closure $next, $guard = null)
    {
       $guards = empty($guards) ? [null] : $guards;

       foreach ($guards as $guard) {
           if (Auth::guard($guard)->check()) {
               if(auth()->user()->utype==='Partner' && auth()->user()->first_log_in===0){
				   return redirect(RouteServiceProvider::CP);
			   }
               return redirect(RouteServiceProvider::HOME);
           }
       }

        // switch ($guard) {
        //     case 'web':
        //         if (Auth::guard($guard)->check()) {
        //             return redirect()->route('admin.dashboard');
        //         }
        //         break;

        //     default:
        //         if (Auth::guard($guard)->check()) {
        //             return redirect('/');
        //         }
        //         break;
        // }

        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         if ($guard === 'web') {
        //             return redirect()->route('admin.dashboard');
        //         }
        //         }
        //     }


        return $next($request);
    }
}
