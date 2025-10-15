<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
         if (!Auth::check() || !Auth::user()->is_admin) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
        }
        return $next($request);
    }
}
