<?php

namespace App\Http\Middleware;

use Closure;

class EmpresaSet
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
        if ($request->session()->has('empresa'))
        {
           return $next($request);
        }
        else
        {
            $request->session()->flash('error', "Seleccione una Empresa Primero");
            return redirect("/home");
        }
    }
}
