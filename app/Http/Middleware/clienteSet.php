<?php

namespace App\Http\Middleware;

use Closure;

class clienteSet
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

        if ($request->session()->has('cliente'))
            return $next($request);
        else
        {
            $request->session()->flash('error', "Seleccione un cliente primero");
            return redirect("/clientes");
        }
    }
}
