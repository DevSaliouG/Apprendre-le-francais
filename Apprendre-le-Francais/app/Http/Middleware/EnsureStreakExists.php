<?php

namespace App\Http\Middleware;

use App\Models\LearningStreak;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStreakExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (Auth::check() && !Auth::user()->learningStreak) {
        LearningStreak::create([
            'user_id' => Auth::id(),
            'current_streak' => 0,
            'longest_streak' => 0,
            'last_activity_date' => null
        ]);
    }

    return $next($request);
}
}
