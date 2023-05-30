<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLoginMiddleware
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
        if ($user) {
            $loggedInTime = $user->last_loggedin_on;
            $logOutTime = $user->last_loggout_on;
            if ($logOutTime && strtotime($logOutTime) > strtotime($loggedInTime)) {
                Auth::logout();
                return redirect('/');
            }
        }
        return $next($request);
    }
}
