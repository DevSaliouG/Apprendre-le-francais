<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureLevelIsSet
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si l'utilisateur n'est pas connecté, rediriger vers login
        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifier les routes exclues de la redirection
        $excludedRoutes = ['test', 'test.showQuestion', 'test.submitAnswer'];
        
        if (!$user->level_id && !in_array($request->route()->getName(), $excludedRoutes)) {
            return redirect()->route('test');
        }

        return $next($request);
    }
}