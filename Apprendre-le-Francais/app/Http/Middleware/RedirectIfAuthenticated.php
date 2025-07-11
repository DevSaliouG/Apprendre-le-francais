<?php

// app/Http/Middleware/RedirectIfAuthenticated.php
namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($request->user()->is_admin) {
                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}