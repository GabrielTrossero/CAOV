<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
         //id 1 => Administrador
        if ($request->user() && $request->user()->idTipoUsuario != 1)
        {
            return redirect()->action('HomeController@index');
        }
        
        return $next($request);
    }
}
