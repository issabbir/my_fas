<?php

namespace App\Http\Middleware;

use App\Enums\Common\InvestmentTypeUserMsg;
use App\Helpers\HelperClass;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CheckInvestmentType
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
        if (HelperClass::getUserCurrentInvestType(Auth::id()) == null){
            return redirect()->route('page-not-allowed',['message'=>Crypt::encryptString(InvestmentTypeUserMsg::MSG)]);
        }
        return $next($request);
    }
}
