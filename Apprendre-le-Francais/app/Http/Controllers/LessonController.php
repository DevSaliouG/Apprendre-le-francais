<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    // Lister les leçons du niveau actuel
    public function index()
    {
        $user = Auth::user();
        $currentLevel = $user->level;
        $lessons = Lesson::where('level_id', $currentLevel->id)
            ->with(['exercises' => function($query) {
                $query->withCount('questions');
            }])
            ->get();

        return view('lessons.index', compact('lessons', 'currentLevel', 'user'));
    }

    // Afficher une leçon spécifique
public function show(Lesson $lesson)
{
    $user = auth()->user();
    
    // Vérifier que la leçon appartient au niveau de l'utilisateur
    if ($lesson->level_id !== $user->level_id) {
        return redirect()->route('lessons.index')
            ->with('error', "Vous n'avez pas encore accès à cette leçon.");
    }

    // Charger les relations nécessaires
    $lesson->load([
        'exercises' => function($query) {
            $query->withCount('questions');
        },
        'level'
    ]);

    $isCompleted = $user->completedLessons()->where('lesson_id', $lesson->id)->exists();
    $nextLesson = $lesson->getNextLessonAttribute();

    return view('lessons.show', compact(
        'lesson', 
        'isCompleted',
        'nextLesson'
    ));
}

    // Marquer une leçon comme complétée
    public function complete(Lesson $lesson)
    {
        $user = Auth::user();
        
        if (!$user->hasCompletedLesson($lesson->id)) {
            $user->completedLessons()->attach($lesson->id, [
                'completed_at' => now(),
                'score' => $this->calculateLessonScore($user, $lesson)
            ]);

             if ($user->completeLevel()) {
        return redirect()->route('lessons.show', $lesson)
            ->with('success', 'Leçon complétée et niveau supérieur atteint!');
    }

            // Mettre à jour le streak
            $this->updateLearningStreak($user);

            // Vérifier si un badge est débloqué
            $this->checkForBadges($user, 'lesson_completed');
        }

        return redirect()->route('lessons.show', $lesson)
            ->with('success', 'Leçon marquée comme complétée!');
    }

    // Calculer le score pour une leçon
    private function calculateLessonScore(User $user, Lesson $lesson)
    {
        $score = 0;
        $totalQuestions = 0;
        
        foreach ($lesson->exercises as $exercise) {
            $userResults = $user->results()
                ->whereIn('question_id', $exercise->questions->pluck('id'))
                ->get();
                
            $correctAnswers = $userResults->where('correct', true)->count();
            $score += $correctAnswers;
            $totalQuestions += $exercise->questions->count();
        }
        
        return $totalQuestions > 0 ? round(($score / $totalQuestions) * 100) : 0;
    }

    // Mettre à jour le streak d'apprentissage
    private function updateLearningStreak(User $user)
    {
        $streak = $user->learningStreak()->firstOrNew();
        $today = now()->format('Y-m-d');
        
        if ($streak->last_activity_date == $today) {
            // Ne rien faire
        } elseif ($streak->last_activity_date == now()->subDay()->format('Y-m-d')) {
            // Consecutive
            $streak->current_streak++;
            $streak->last_activity_date = $today;
        } else {
            // Briser la série
            $streak->current_streak = 1;
            $streak->last_activity_date = $today;
        }

        if ($streak->current_streak > $streak->longest_streak) {
            $streak->longest_streak = $streak->current_streak;
        }

        $streak->save();
    }

    // Vérifier les badges débloqués
    private function checkForBadges(User $user, $activityType)
    {
        // Logique pour attribuer des badges selon le type d'activité
        // Exemple: badge "Débutant" après 5 leçons complétées
        $completedLessons = $user->completedLessons->count();
        
        if ($completedLessons >= 5) {
            $badge = Badge::where('name', 'Débutant')->first();
            if ($badge && !$user->badges->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }
    }
}