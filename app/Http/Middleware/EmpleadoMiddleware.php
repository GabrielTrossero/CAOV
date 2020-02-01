<?php

namespace App\Http\Middleware;

use Closure;

class EmpleadoMiddleware
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
        //id 1 => Administrador, id 2 => Empleado Normal
        if ($request->user() && $request->user()->idTipoUsuario != 1 && $request->user()->idTipoUsuario != 2)
        {
            return redirect()->action('HomeController@index');
        }
        
        return $next($request);
    }
}
