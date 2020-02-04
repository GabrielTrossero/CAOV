<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ActivoMiddleware
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
        //si el empleado está inactivo hace logout
        if ($request->user() && (!$request->user()->activo))
        {
            Auth::logout();
            return redirect()->action('HomeController@index');
        }

        return $next($request);
    }
}
