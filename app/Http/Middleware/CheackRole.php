<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheackRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles 
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
            if (! $request->user() || ! in_array($request->user()->rol, $roles)) {
                //abort (403, 'Acceso no autorizado');
                return redirect('/dashboard')->with('error', 'no tienes permiso para acceder a esta seccion.');
            }

        return $next($request);

    }
}
