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
