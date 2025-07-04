<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'type',
        'audio_path',
        'difficulty'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => 'string', // écrit|oral
        'difficulty' => 'integer'
    ];

    /**
     * Relation avec la leçon parente
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relation avec les questions de cet exercice
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }


    // Accessor pour l'URL de l'audio
    public function getAudioUrlAttribute()
    {
        return $this->audio_path ? Storage::url($this->audio_path) : null;
    }

    // Supprime le fichier audio lors de la suppression de l'exercice
    protected static function booted()
    {
       static::deleting(function ($exercise) {
        // Supprimer les fichiers audio des questions
        foreach ($exercise->questions as $question) {
            if ($question->fichier_audio) {
                Storage::disk('public')->delete($question->fichier_audio);
            }
        }
        
        // Supprimer les questions
        $exercise->questions()->delete();
        
        // Supprimer le fichier audio de l'exercice
        if ($exercise->audio_path) {
            Storage::disk('public')->delete($exercise->audio_path);
        }
    });
    }
    public function questionsRandomized()
    {
        return $this->questions()->inRandomOrder()->get();
    }

   public function isCompletedByUser($userId)
{
    $totalQuestions = $this->questions()->count();
    if ($totalQuestions === 0) return false;

    $correctAnswers = UserResult::where('user_id', $userId)
        ->whereIn('question_id', $this->questions()->pluck('id'))
        ->where('correct', true)
        ->count();

    return ($correctAnswers / $totalQuestions) >= 0.8;
}

public function userStarted($userId)
{
    return UserResult::where('user_id', $userId)
        ->whereIn('question_id', $this->questions()->pluck('id'))
        ->exists();
}
    public function getDifficultyStarsAttribute()
    {
        return str_repeat('★', $this->difficulty) . str_repeat('☆', 5 - $this->difficulty);
    }

    public function userCompleted(User $user)
    {
        $totalQuestions = $this->questions()->count();
        $correctAnswers = $user->results()
            ->whereIn('question_id', $this->questions()->pluck('id'))
            ->where('correct', true)
            ->count();

        return ($correctAnswers / $totalQuestions) >= 0.8;
    }

    
public function userProgress(User $user)
{
    $totalQuestions = $this->questions->count();
    if ($totalQuestions === 0) return 0;
    
    $correctAnswers = $user->results()
        ->whereIn('question_id', $this->questions->pluck('id'))
        ->where('correct', true)
        ->count();
    
    return round(($correctAnswers / $totalQuestions) * 100);
}
    public static function getForFilter()
{
    return Exercise::with('lesson')
        ->get()
        ->mapWithKeys(function ($exercise) {
            return [$exercise->id => "Leçon {$exercise->lesson->id} - Exercice {$exercise->id}"];
        });
}
    
}

