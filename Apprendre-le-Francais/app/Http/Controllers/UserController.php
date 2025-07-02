<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Level;
use App\Models\UserActivity;
use App\Models\UserBadge;
use App\Notifications\StreakDanger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('level_set')->except(['showProfile', 'updateProfile']);
    }
    // Afficher le tableau de bord utilisateur
  public function dashboard()
{
    $user = Auth::user();
    $currentLevel = $user->level;
    
    // Vérifier si l'utilisateur a un niveau attribué
    if (!$currentLevel) {
        return redirect()->route('test');
    }
    
    $nextLevel = Level::where('id', '>', $currentLevel->id)->first();
    
    // Calcul des statistiques de progression
    $stats = [
        'completedExercises' => $this->calculateCompletedExercises($user),
        'completedLessons' => $user->completedLessons->count(),
        'successRate' => $this->calculateSuccessRate($user),
        'badges' => $user->badges->count(),
        'streak' => $user->learningStreak->current_streak ?? 0,
        'levelProgress' => $this->calculateLevelProgress($user, $currentLevel),
        'xp' => $user->xp,
        'dailyGoal' => $this->calculateDailyGoalProgress($user),
    ];

    // Activités récentes
    $activities = UserActivity::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $latestExercises = Exercise::whereHas('lesson', function($query) use ($currentLevel) {
            $query->where('level_id', $currentLevel->id);
        })
        ->with('lesson') 
        ->latest()
        ->take(5)
        ->get();

    // Données pour les graphiques
    $chartData = [
        'streak' => $this->getStreakData($user) ?? ['labels' => [], 'data' => []],
        'badges' => $this->getBadgesData($user) ?? ['labels' => [], 'data' => []],
        'lessons' => $this->getLessonsData($user) ?? ['labels' => [], 'data' => []],
        'skills' => $this->getSkillsData($user) ?? ['labels' => [], 'data' => []],
        'weeklyActivity' => $this->getWeeklyActivityData($user),
    ];

    return view('user.dashboard', compact(
        'user', 
        'currentLevel',
        'nextLevel',
        'stats',
        'activities',
        'latestExercises',
        'chartData'
    ));
}

// Nouvelle méthode pour les données d'activité hebdomadaire
private function getWeeklyActivityData($user)
{
    $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    $data = [];
    
    foreach($days as $index => $day) {
        $date = now()->startOfWeek()->addDays($index);
        $count = UserActivity::where('user_id', $user->id)
            ->whereDate('created_at', $date)
            ->count();
        $data[] = $count;
    }
    
    return ['labels' => $days, 'data' => $data];
}

// Nouvelle méthode pour le calcul de l'objectif quotidien
private function calculateDailyGoalProgress($user)
{
    $goal = $user->daily_goal ?? 3; // Objectif par défaut
    $completed = UserActivity::where('user_id', $user->id)
        ->whereDate('created_at', now())
        ->count();
    
    return [
        'completed' => $completed,
        'goal' => $goal,
        'percentage' => min(100, round(($completed / $goal) * 100))
    ];
}

    // Afficher le profil utilisateur
    public function showProfile()
    {
        $user = Auth::user();
        $levels = Level::all();
        return view('user.profile', compact('user', 'levels'));
    }

    // Mettre à jour le profil utilisateur
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'dateNaiss' => 'required|date',
            'adresse' => 'required|string|max:255',
            'level_id' => 'nullable|exists:levels,id',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'dateNaiss' => $request->dateNaiss,
            'adresse' => $request->adresse,
            'level_id' => $request->level_id,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Enregistrer l'activité
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'profile_update'
        ]);

        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès!');
    }

    // Obtenir la progression détaillée
    public function progression()
    {
        $user = Auth::user();
        $levels = Level::with('lessons')->get();
        
        $progression = [];
        foreach ($levels as $level) {
            $levelProgress = [
                'completed' => 0,
                'total' => $level->lessons->count(),
                'lessons' => []
            ];
            
            foreach ($level->lessons as $lesson) {
                $completed = $user->completedLessons->contains($lesson->id);
                $lessonProgress = [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'completed' => $completed,
                    'score' => $completed ? $user->getLessonScore($lesson->id) : null
                ];
                
                $levelProgress['lessons'][] = $lessonProgress;
                if ($completed) $levelProgress['completed']++;
            }
            
            $progression[] = [
                'level' => $level,
                'progress' => $levelProgress
            ];
        }

        return view('user.progression', compact('progression'));
    }
    private function calculateLevelProgress(User $user, Level $level)
{
    $totalLessons = $level->lessons->count();
    if ($totalLessons === 0) return 0;
    
    $completedLessons = $user->completedLessons()
        ->where('level_id', $level->id)
        ->count();
    
    return round(($completedLessons / $totalLessons) * 100);
}
private function calculateCompletedExercises(User $user)
{
    return Exercise::whereHas('questions.userResults', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('correct', true);
        })
        ->count();
}

// Nouvelle méthode pour calculer le taux de réussite
private function calculateSuccessRate(User $user)
{
    $totalAnswers = $user->results()->count();
    $correctAnswers = $user->results()->where('correct', true)->count();
    
    return $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100) : 0;
}

    public function checkStreak(User $user)
    {
        // Logique de vérification du streak...
        $user->notify(new StreakDanger());
    }

   private function getStreakData($user)
{
    $endDate = now();
    $startDate = now()->subDays(30);
    
    $data = [];
    $labels = [];
    
    $streak = 0;
    $currentDate = $startDate->copy();
    
    while ($currentDate <= $endDate) {
        $dateStr = $currentDate->format('Y-m-d');
        $labels[] = $currentDate->format('d M');
        
        $hasActivity = UserActivity::where('user_id', $user->id)
            ->whereDate('created_at', $dateStr)
            ->exists();
        
        if ($hasActivity) {
            $streak++;
        } else {
            $streak = 0; // Réinitialiser le streak si pas d'activité
        }
        
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
    
    // Remplacer les données factices par des calculs réels
    $data = [];
    foreach ($skills as $skill) {
        // Exemple de calcul réel - à adapter selon votre logique métier
        $progress = $user->results()
            ->whereHas('question', function($q) use ($skill) {
                $q->where('skill', $skill);
            })
            ->select(DB::raw('AVG(correct) * 100 as progress'))
            ->value('progress') ?? 0;
            
        $data[] = round($progress);
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

}