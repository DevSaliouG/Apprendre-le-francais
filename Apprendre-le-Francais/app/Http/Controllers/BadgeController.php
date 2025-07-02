<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    // Afficher tous les badges
    public function index()
    {
        $user = Auth::user();
        $badges = Badge::withCount('users')->get();
        $userBadges = $user->badges->pluck('id')->toArray();
        
        // Ajouter la progression pour chaque badge
        $badges->transform(function ($badge) use ($user) {
            $badge->progress = $this->calculateProgress($user, $badge);
            return $badge;
        });
        
        return view('badges.index', compact('badges', 'userBadges'));
    }

    public function myBadges()
    {
        $user = Auth::user();
        $badges = $user->badges()->paginate(12);
        
        // Ajouter la progression pour chaque badge
        $badges->getCollection()->transform(function ($badge) use ($user) {
            $badge->progress = $this->calculateProgress($user, $badge);
            return $badge;
        });
        
        // Précharger les relations pour les prochains badges
        $nextBadges = Badge::whereNotIn('id', $badges->pluck('id'))
            ->withCount('users')
            ->get();
        
        // Ajouter la progression pour les prochains badges
        $nextBadges->transform(function ($badge) use ($user) {
            $badge->progress = $this->calculateProgress($user, $badge);
            return $badge;
        });
        
        return view('badges.my', compact('badges', 'nextBadges'));
    }

    // Afficher les détails d'un badge
    public function show(Badge $badge)
    {
        $user = Auth::user();
        $hasBadge = $user->badges->contains($badge->id);
        $progress = $this->calculateProgress($user, $badge);
        
        return view('badges.show', compact('badge', 'hasBadge', 'progress'));
    }

    // Calculer la progression vers un badge
    public function calculateProgress(User $user, Badge $badge)
    {
        switch ($badge->type) {
            case 'lesson':
                $completed = $user->completedLessons->count();
                return [
                    'current' => $completed,
                    'target' => $badge->threshold,
                    'percentage' => min(100, round(($completed / $badge->threshold) * 100))
                ];
                
            case 'streak':
                $currentStreak = $user->learningStreak->current_streak ?? 0;
                return [
                    'current' => $currentStreak,
                    'target' => $badge->threshold,
                    'percentage' => min(100, round(($currentStreak / $badge->threshold) * 100))
                ];
                
            case 'exercise':
                $completed = UserActivity::where('user_id', $user->id)
                    ->where('activity_type', 'exercise_completed')
                    ->count();
                return [
                    'current' => $completed,
                    'target' => $badge->threshold,
                    'percentage' => min(100, round(($completed / $badge->threshold) * 100))
                ];
                
            default:
                return [
                    'current' => 0,
                    'target' => 1,
                    'percentage' => 0
                ];
        }
    }
}