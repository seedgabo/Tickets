<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Support\Facades\Crypt;

class AuthenticateOnceWithBasicAuth
{

    public function handle($request, Closure $next)
    {
    	if($request->header('Auth-Token', "") != ""){
    		Auth::setUser(\App\User::find(Crypt::decrypt($request->header("Auth-Token"))));
    		return $next($request);
    	}
    	else{
        	return Auth::onceBasic() ?: $next($request);
    	}
    }

}
