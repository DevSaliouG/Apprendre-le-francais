<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

     protected $fillable = [
        'level_id',
        'title',
        'content'
    ];

    /**
     * Relation avec le niveau parent
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Relation avec les exercices lier a cette lecon
     */
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
   public function getNextLessonAttribute()
    {
        return self::where('level_id', $this->level_id)
                   ->where('id', '>', $this->id)
                   ->orderBy('id')
                   ->first();
    }

    public function exercisesWithType($type)
    {
        return $this->exercises()->where('type', $type)->get();
    }
    public function completions()
{
    return $this->hasMany(LessonCompletion::class);
}

}