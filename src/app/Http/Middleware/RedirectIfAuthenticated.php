<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Redirect;  // Add this section Pavel as julfiker vai direction-27-09-22

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    /*** Block this Pavel as julfiker vai direction-27-09-22 ***/
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }

    /*** Add this section Pavel as julfiker vai direction-27-09-22 ***/
    /*public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->need_pass_reset == 'Y')
                return Redirect::to("/user/change-password");

            return redirect('/home');
        }

        return $next($request);
    }*/
}
