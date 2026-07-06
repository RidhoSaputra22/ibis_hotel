<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSystemLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('system_login')) {
            return redirect()->route('system.entry');
        }

        return $next($request);
    }
}
