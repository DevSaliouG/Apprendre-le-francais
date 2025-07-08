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

        $badges->getCollection()->transform(function ($badge) use ($user) {
            $badge->progress = $this->calculateProgress($user, $badge);
            return $badge;
        });

        $nextBadges = Badge::whereNotIn('id', $badges->pluck('id'))
            ->withCount('users')
            ->get();

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

        // Top 5 utilisateurs ayant ce badge
        $topUsers = $badge->users()
            ->orderBy('experience_points', 'desc')
            ->take(5)
            ->get();

        return view('badges.show', compact('badge', 'hasBadge', 'progress', 'topUsers'));
    }

    // Calculer la progression vers un badge (version améliorée)
    public function calculateProgress(User $user, Badge $badge)
    {
        switch ($badge->type) {
            case 'lesson':
                $completed = $user->completedLessons->count();
                break;
            
            case 'streak':
                $completed = $user->learningStreak ? $user->learningStreak->current_streak : 0;
                break;
            
            case 'exercise':
                $completed = UserActivity::where('user_id', $user->id)
                    ->where('activity_type', 'exercise_completed')
                    ->where('score', '>=', 85)
                    ->count();
                break;
            
            case 'level':
                $completed = $user->level ? $user->level->id : 0;
                break;
            
            case 'social':
                $completed = UserActivity::where('user_id', $user->id)
                    ->whereIn('activity_type', ['achievement_shared', 'friend_helped'])
                    ->count();
                break;
            
            case 'special':
                // Logique spéciale selon le badge
                switch ($badge->name) {
                    case 'Polyglotte':
                        $completed = $user->languages_learned_count ?? 0;
                        break;
                    case 'Noctambule':
                        $completed = UserActivity::where('user_id', $user->id)
                            ->whereTime('created_at', '>=', '22:00:00')
                            ->count();
                        break;
                    default:
                        $completed = $user->badges->count();
                }
                break;
            
            default:
                $completed = 0;
        }

        return [
            'current' => $completed,
            'target' => $badge->threshold,
            'percentage' => $badge->threshold > 0
                ? min(100, round(($completed / $badge->threshold) * 100))
                : 0
        ];
    }

    // API pour attribuer un badge (pour tests)
    public function awardBadge(Request $request, Badge $badge)
    {
        $user = Auth::user();
        
        if (!$user->badges->contains($badge->id)) {
            $user->badges()->attach($badge->id, [
                'earned_at' => now(),
                'context' => json_encode(['source' => 'manual-award'])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Badge {$badge->name} attribué!"
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => "Vous avez déjà ce badge!"
        ], 400);
    }
}