<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isAdmin
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
        if (Auth::user()->admin == 1)
            return $next($request);
        else
        {
            $request->session()->flash('error', "Usted no tiene permisos para esta opción");
            return redirect()->back()->withInput();
        }
    }
}
