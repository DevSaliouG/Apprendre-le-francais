<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Badge;
use App\Models\UserResult;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistiques de base
         $levelsList = Level::all();
        $stats = [
            'userlist' => Cache::remember('users_count', 3600, fn() => User::count()),
            'levels' => Cache::remember('levels_count', 3600, fn() => Level::count()),
            'lessons' => Cache::remember('lessons_count', 3600, fn() => Lesson::count()),
            'exercises' => Cache::remember('exercises_count', 3600, fn() => Exercise::count()),
        ];

       
          $users = User::paginate(10);
        // Statistiques avancées
        $data = [
           
            'users' => $users,
            // Nouveaux utilisateurs par semaine (8 dernières semaines)
            'newUsersPerWeek' => $this->getNewUsersPerWeek(),
            
            // Répartition des badges
            'badgeDistribution' => $this->getBadgeDistribution(),
            
            // Activité par niveau
            'activityByLevel' => $this->getActivityByLevel(),
            
            // Exercices réussis (30 derniers jours)
            'highScoreExercises' => $this->getHighScoreExercises(),
            
            // Notifications non lues
            'unreadNotifications' => auth()->user()->unreadNotifications,
        ];

        return view('admin.dashboard', array_merge($stats, $data),compact('levelsList'));
    }

    protected function getNewUsersPerWeek()
    {
        return Cache::remember('new_users_per_week', 3600, function() {
            return User::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
                ->where('created_at', '>', now()->subWeeks(8))
                ->groupBy('week')
                ->orderBy('week', 'desc')
                ->get()
                ->mapWithKeys(fn($item) => [$item['week'] => $item['count']]);
        });
    }

    protected function getBadgeDistribution()
    {
        return Cache::remember('badge_distribution', 3600, function() {
            return Badge::withCount('users')->get();
        });
    }

    protected function getActivityByLevel()
    {
        return Cache::remember('activity_by_level', 3600, function() {
            return Level::withCount('users')
                ->orderByDesc('users_count')
                ->get();
        });
    }

    protected function getHighScoreExercises()
    {
        return UserResult::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('correct', true)
            ->where('created_at', '>', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}