<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Level;
use App\Models\User;
use App\Models\UserResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilUserController extends Controller
{
    // Afficher le formulaire de profil
    public function edit()
    {
        // Récupérer l'utilisateur actuel et passer à la vue
        $user = Auth::user();
        return view('user.profil', compact('user'));
    }

    // Mettre à jour le profil
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validation des données
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:50',
            'nom' => 'required|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'dateNaiss' => 'nullable|date|before:today',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'current_password' => 'nullable|string|current_password',
            'new_password' => 'nullable|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect',
            'new_password.different' => 'Le nouveau mot de passe doit être différent de l\'actuel',
        ]);

        // Vérification des erreurs
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Préparation des données de mise à jour
        $updateData = [
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'dateNaiss' => $request->dateNaiss,
        ];

        // Mise à jour du mot de passe si fourni
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        // Mise à jour de l'utilisateur
        $user->update($updateData);

        return redirect()->route('profil.show')
            ->with('success', 'Profil mis à jour avec succès!');
    }
    public function show()
    {
        $user = Auth::user();
        
        // Récupération des données réelles
        $stats = $this->getUserStats($user);
        $activities = $this->getRecentActivities($user);
        $badges = $this->getUserBadges($user);
        $nextLevel = $this->getNextLevel($user->level->code);

        return view('user.show-profil', [
            'user' => $user,
            'nextLevel' => $nextLevel,
            'badges' => $badges,
            'activities' => $activities,
            'stats' => $stats
        ]);
    }

    private function getUserStats(User $user)
    {
        // 1. Taux de réussite
        $successRate = UserResult::where('user_id', $user->id)
            ->selectRaw('ROUND((SUM(correct) / COUNT(*)) * 100) as rate')
            ->value('rate') ?? 0;

        // 2. Leçons complétées
        $completedLessons = Lesson::whereHas('exercises.questions.userResults', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('correct', true);
            })
            ->count();

        // 3. Exercices réussis
        $successExercises = UserResult::where('user_id', $user->id)
            ->where('correct', true)
            ->count();

        // 4. Points (1 point par bonne réponse)
        $points = $successExercises;

        // 5. Streak (simplifié pour l'exemple)
        $streak = $this->getCurrentStreak($user);

        return [
            'success_rate' => $successRate,
            'completed_lessons' => $completedLessons,
            'success_exercises' => $successExercises,
            'points' => $points,
            'streak' => $streak
        ];
    }

   /*  private function getCurrentStreak(User $user)
    {
        // Version simplifiée : jours depuis la dernière activité
        $lastActivity = DB::table('user_results')
            ->where('user_id', $user->id)
            ->max('created_at');

        return $lastActivity 
            ? Carbon::parse($lastActivity)->diffInDays(now())
            : 0;
    }
 */

    private function getCurrentStreak(User $user)
{
    // Récupérer la dernière date d'activité
    $lastActivity = DB::table('user_activities')
        ->where('user_id', $user->id)
        ->where('activity_type', 'login')
        ->orderByDesc('created_at')
        ->value('created_at');

    if (!$lastActivity) return 0;

    // Calculer le nombre de jours consécutifs
    $currentStreak = 0;
    $currentDate = now()->startOfDay();
    $lastDate = Carbon::parse($lastActivity)->startOfDay();
    
    while ($lastDate->equalTo($currentDate)) {
        $currentStreak++;
        $currentDate->subDay();
        
        // Vérifier l'activité du jour précédent
        $activityExists = DB::table('user_activities')
            ->where('user_id', $user->id)
            ->where('activity_type', 'login')
            ->whereDate('created_at', $currentDate)
            ->exists();
            
        if (!$activityExists) break;
    }
    
    return $currentStreak;
}
    
    private function getNextLevel($currentLevel)
    {
        $levels = Level::orderBy('id')->pluck('code')->toArray();
        $currentIndex = array_search($currentLevel, $levels);
        
        return ($currentIndex !== false && isset($levels[$currentIndex + 1]))
            ? $levels[$currentIndex + 1]
            : 'Maîtrise';
    }

    private function getUserBadges(User $user)
    {
        // Version simplifiée avec données statiques
        return [
            ['name' => 'Débutant', 'icon' => 'seedling', 'color' => 'success'],
            ['name' => 'Quiz Master', 'icon' => 'trophy', 'color' => 'warning'],
        ];
    }

    private function getRecentActivities(User $user)
    {
        return UserResult::with('question.exercise.lesson')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function($result) {
                return [
                    'title' => $result->question->exercise->lesson->title ?? 'Exercice',
                    'date' => $result->created_at->format('d/m/Y'),
                    'time' => $result->created_at->format('H:i'),
                    'icon' => $result->correct ? 'check-circle' : 'times-circle',
                    'color' => $result->correct ? 'success' : 'danger',
                    'points' => $result->correct ? 10 : 0
                ];
            })
            ->toArray();
    }
}