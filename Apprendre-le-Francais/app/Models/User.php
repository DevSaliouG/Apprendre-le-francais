<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \App\Models\UserResult;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'adresse',
        'dateNaiss',
        'email',
        'password',
        'experience_points',
        'is_admin',
        'level_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Vérifier si l'utilisateur est admin
    public function isAdmin()
    {
        return $this->is_admin;
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean', // Pour traiter comme booléen
        'dateNaiss' => 'date',
    ];

    /**
     * Relation avec le niveau de l'utilisateur
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Relation avec les résultats de l'utilisateur
     */
    public function results(): HasMany
    {
        return $this->hasMany(UserResult::class);
    }

     public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function hasCompletedLesson($lessonId)
    {
        return $this->completedLessons()->where('lesson_id', $lessonId)->exists();
    }

    public function learningStreak()
    {
        return $this->hasOne(LearningStreak::class);
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_completions')
            ->withPivot('score', 'completed_at')
            ->withTimestamps();
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withTimestamps();
    }

    public function calculateScore($type)
    {
        return $this->results()
            ->where('test_type', $type)
            ->where('correct', true)
            ->count();
    }
    public function completedExercises()
    {
        return $this->hasManyThrough(
            Exercise::class,
            UserResult::class,
            'user_id',
            'id',
            'id',
            'exercise_id'
        )->distinct();
    }
    public function getCompletedExercisesAttribute()
    {
        return $this->results()
            ->where('correct', true)
            ->with('question.exercise')
            ->get()
            ->groupBy('question.exercise_id')
            ->count();
    }


    public function advanceLevel()
    {
        $currentLevel = $this->level;
        $nextLevel = Level::where('id', '>', $currentLevel->id)
            ->orderBy('id')
            ->first();

        if ($nextLevel) {
            $this->level_id = $nextLevel->id;
            $this->save();

            // Enregistrer l'activité
            UserActivity::create([
                'user_id' => $this->id,
                'activity_type' => 'level_up',
                'details' => json_encode([
                    'from' => $currentLevel->name,
                    'to' => $nextLevel->name
                ])
            ]);

            return true;
        }

        return false;
    }

    public function completeLevel()
    {
        if (!$this->hasCompletedAllExercises()) {
            return false;
        }

        $levelProgress = $this->getLevelProgress();

        if ($levelProgress['percentage'] >= config('app.level_threshold', 80)) {
            return $this->advanceLevel();
        }

        return false;
    }

    public function hasCompletedAllExercises()
    {
        // Tous les exercices du niveau actuel
        $levelExercises = Exercise::whereHas('lesson', function ($query) {
            $query->where('level_id', $this->level_id);
        })->get();

        foreach ($levelExercises as $exercise) {
            if (!$this->isExerciseCompleted($exercise->id)) {
                return false;
            }
        }

        return true;
    }

    public function isExerciseCompleted($exerciseId)
    {
        $exercise = Exercise::find($exerciseId);
        if (!$exercise) return false;

        $totalQuestions = $exercise->questions()->count();
        if ($totalQuestions === 0) return false;

        $correctAnswers = $this->results()
            ->whereIn('question_id', $exercise->questions()->pluck('id'))
            ->where('correct', true)
            ->count();

        return ($correctAnswers / $totalQuestions) >= 0.8;
    }

    public function getLevelProgress()
    {
        $totalExercises = Exercise::whereHas('lesson', function ($query) {
            $query->where('level_id', $this->level_id);
        })->count();

        $completedExercises = 0;

        $exercises = Exercise::whereHas('lesson', function ($query) {
            $query->where('level_id', $this->level_id);
        })->get();

        foreach ($exercises as $exercise) {
            if ($this->isExerciseCompleted($exercise->id)) {
                $completedExercises++;
            }
        }

        $percentage = $totalExercises > 0 ? round(($completedExercises / $totalExercises) * 100) : 0;

        return [
            'completed' => $completedExercises,
            'total' => $totalExercises,
            'percentage' => $percentage
        ];
    }
    public function getLearningStreakAttribute()
    {
        return $this->learningStreak()->firstOrCreate([], [
            'current_streak' => 0,
            'longest_streak' => 0,
            'last_activity_date' => null
        ]);
    }
    public function hasBadgeForMilestone($milestoneId)
    {
        return $this->badges()->whereHas('milestone', function ($q) use ($milestoneId) {
            $q->where('id', $milestoneId);
        })->exists();
    }

    public function hadActivityToday()
    {
        return UserActivity::where('user_id', $this->id)
            ->whereDate('created_at', today())
            ->exists();
    }
}
