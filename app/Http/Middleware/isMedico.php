<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isMedico
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
        if (Auth::user()->medico == 1)
            return $next($request);
        else
        {
            $request->session()->flash('error', "Usted no tiene permisos para esta opción");
            \Alert::warning(trans('No Tiene permisos para esta opción'))->flash();
            return redirect('/')->withInput();
        }
    }
}
