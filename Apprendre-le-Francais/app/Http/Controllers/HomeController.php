<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\Level;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Affiche le tableau de bord principal
     */
    // DashboardController.php
public function index()
{
    $user = Auth::user();
    
    if (!$user->level_id) {
        return redirect()->route('test');
    }

    $stats = $this->getUserStats($user);
    $nextLevel = Level::where('id', '>', $user->level_id)->first();
    $latestExercises = Exercise::whereHas('lesson', function($q) use ($user) {
            $q->where('level_id', $user->level_id);
        })
        ->with('lesson')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    // Récupération des données réelles pour les graphiques
    $chartData = [
        'streak' => $this->getStreakData($user),
        'badges' => $this->getBadgesData($user),
        'lessons' => $this->getLessonsData($user),
        'skills' => $this->getSkillsData($user),
    ];

    return view('dashboard', compact(
        'user', 
        'stats',
        'nextLevel',
        'latestExercises',
        'chartData'
    ));
}
    
private function getLast30DaysLabels()
{
    $labels = [];
    for ($i = 29; $i >= 0; $i--) {
        $labels[] = now()->subDays($i)->format('d M');
    }
    return $labels;
}




private function getStreakData($user)
{
    $endDate = now();
    $startDate = now()->subDays(30);
    
    $data = [];
    $labels = [];
    
    $currentDate = $startDate->copy();
    while ($currentDate <= $endDate) {
        $dateStr = $currentDate->format('Y-m-d');
        $labels[] = $currentDate->format('d M');
        
        // Récupérer le streak du jour (exemple simplifié)
        $streak = UserActivity::where('user_id', $user->id)
            ->whereDate('created_at', $dateStr)
            ->exists() ? ($data[count($data)-1] ?? 0) + 1 : 0;
        
        $data[] = $streak;
        $currentDate->addDay();
    }
    
    return ['labels' => $labels, 'data' => $data];
}

private function getBadgesData($user)
{
    $badgeTypes = ['lesson', 'exercise', 'streak', 'challenge', 'participation'];
    
    $data = [];
    foreach ($badgeTypes as $type) {
        $count = $user->badges()->where('type', $type)->count();
        $data[] = $count;
    }
    
    $labels = array_map('ucfirst', $badgeTypes);
    
    return ['labels' => $labels, 'data' => $data];
}

private function getLessonsData($user)
{
    $weeks = 8;
    $data = [];
    $labels = [];
    
    for ($i = $weeks - 1; $i >= 0; $i--) {
        $start = now()->subWeeks($i)->startOfWeek();
        $end = now()->subWeeks($i)->endOfWeek();
        
        $count = UserActivity::where('user_id', $user->id)
            ->where('activity_type', 'lesson_completed')
            ->whereBetween('created_at', [$start, $end])
            ->count();
        
        $data[] = $count;
        $labels[] = 'S' . $start->week;
    }
    
    return ['labels' => $labels, 'data' => $data];
}

private function getSkillsData($user)
{
    $skills = ['Grammaire', 'Vocabulaire', 'Conjugaison', 'Compréhension', 'Expression', 'Phonétique'];
    
    // Exemple de calcul basé sur les activités
    $data = [];
    foreach ($skills as $skill) {
        // Logique de calcul réelle à implémenter
        $progress = rand(40, 95); // Valeur factice pour l'exemple
        $data[] = $progress;
    }
    
    return ['labels' => $skills, 'data' => $data];
}

    /**
     * Récupère les statistiques de l'utilisateur
     */
   
private function getUserStats(User $user)
{
    return [
        'streak' => $user->currentStreak->current_streak ?? 0,
        'badges' => UserBadge::where('user_id', $user->id)->count(),
        'completedLessons' => $user->completedLessons()->count(),
        'successRate' => $this->calculateSuccessRate($user),
        'levelProgress' => $this->calculateLevelProgress($user),
        'completedExercises' => $user->completedExercises()->count(),
    ];
}

private function calculateSuccessRate(User $user)
{
    $total = $user->completedExercises()->count();
    $correct = $user->completedExercises()->where('is_correct', true)->count();
    
    return $total > 0 ? round(($correct / $total) * 100) : 0;
}

private function calculateLevelProgress(User $user)
{
    $completed = $user->completedLessons()->count();
    $total = Lesson::where('level_id', $user->level_id)->count();
    
    return $total > 0 ? round(($completed / $total) * 100) : 0;
}

    /**
     * Récupère les activités récentes
     */
    private function getRecentActivities(User $user)
    {
        return UserActivity::with('lesson', 'exercise')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($activity) {
                $type = '';
                $name = '';
                
                if ($activity->activity_type === 'lesson_completed') {
                    $type = 'Leçon';
                    $name = $activity->lesson->title ?? 'Leçon terminée';
                } elseif ($activity->activity_type === 'exercise_completed') {
                    $type = 'Exercice';
                    $name = $activity->exercise->title ?? 'Exercice terminé';
                }
                
                return [
                    'type' => $type,
                    'name' => $name,
                    'date' => $activity->created_at->diffForHumans(),
                    'icon' => $activity->activity_type === 'lesson_completed' ? 'book' : 'pen',
                ];
            });
    }

    /**
     * Récupère les leçons recommandées
     */
    private function getRecommendedLessons(User $user)
    {
        return Lesson::where('level_id', $user->level_id)
            ->whereDoesntHave('completions', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->inRandomOrder()
            ->limit(3)
            ->get();
    }

    /**
     * Récupère le prochain badge à débloquer
     */
    private function getNextBadge(User $user)
    {
        $badgeCount = UserBadge::where('user_id', $user->id)->count();
        
        return DB::table('badges')
            ->where('threshold', '>', $badgeCount)
            ->orderBy('threshold')
            ->first();
    }

    /**
     * Affiche la page de profil utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load('level', 'currentStreak', 'badges.badge');
        
        $progress = [
            'lessons' => $this->calculateProgress(
                $user->completedLessons()->count(),
                Lesson::where('level_id', $user->level_id)->count()
            ),
            'exercises' => $this->calculateProgress(
                $user->completedExercises()->count(),
                Exercise::whereHas('lesson', function($q) use ($user) {
                    $q->where('level_id', $user->level_id);
                })->count()
            ),
        ];
        
        $activityHistory = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('profile', compact('user', 'progress', 'activityHistory'));
    }

    /**
     * Calcule le pourcentage de progression
     */
    private function calculateProgress($completed, $total)
    {
        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    /**
     * Met à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'prenom' => 'required|string|max:50',
            'nom' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'adresse' => 'nullable|string|max:255',
            'dateNaiss' => 'nullable|date',
        ]);
        
        $user->update($request->only(['prenom', 'nom', 'email', 'adresse', 'dateNaiss']));
        
        return redirect()->route('profile')
            ->with('success', 'Profil mis à jour avec succès!');
    }

    /**
     * Affiche la page des paramètres
     */
    public function settings()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }
}