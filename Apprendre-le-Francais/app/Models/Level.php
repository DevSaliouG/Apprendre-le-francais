<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

     protected $fillable = [
        'code',
        'name'
    ];

    protected $casts = [
        'name' => 'string', // Débutant|intermediaire|avancée
    ];

    /**
     * Relation avec les leçons de ce niveau
     */

    public static function assignLevelAfterTest(User $user, int $score)
    {
        // Définir les plages de score pour chaque niveau
        $levelRanges = [
            'Débutant' => [0, 40],
            'Intermédiaire' => [41, 100]
           
        ];
        
        $assignedLevel = null;
        
        foreach ($levelRanges as $levelName => $range) {
            if ($score >= $range[0] && $score <= $range[1]) {
                $assignedLevel = Level::where('name', $levelName)->first();
                break;
            }
        }
        
        // Si aucun niveau n'a été trouvé, utiliser le niveau par défaut
        if (!$assignedLevel) {
            $assignedLevel = Level::defaultLevel();
        }
        
        $user->update(['level_id' => $assignedLevel->id]);
        
        return $assignedLevel;
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Relation avec les exercices de ce niveau
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Relation avec les utilisateurs de ce niveau
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function recommendNextLesson(User $user)
{
    $lastResult = $user->results()->latest()->first();
    
    if ($lastResult && $lastResult->correct) {
        return $this->lessons()->where('difficulty', '>', $lastResult->question->difficulty)->first();
    }
    
    return $this->lessons()->where('difficulty', $lastResult->question->difficulty)->first();
}
    public function getNextLevel()
{
    return Level::where('id', '>', $this->id)
        ->orderBy('id')
        ->first();
}
}
