<?php

namespace App\Http\Middleware;

use Closure;

class isAjax
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
        if ($request->ajax())
            return $next($request);
        else
        {
            $request->session()->flash('error', "Usted no tiene permisos para esta opción");
            return redirect()->back()->withInput();
        }
    }
}
