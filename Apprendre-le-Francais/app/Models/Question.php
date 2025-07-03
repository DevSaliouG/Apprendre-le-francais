<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $casts = [
        'choix' => 'array',
    ];

    protected $fillable = [
        'exercise_id',
        'texte',
        'choix',
        'reponse_correcte',
        'skill',
        'format_reponse',
        'fichier_audio',
    ];


    // Ajoutez cet accesseur pour un affichage propre
   // Nouvel accesseur pour garantir un tableau
    public function getCleanChoixAttribute()
    {
        if (is_array($this->choix)) {
            return $this->choix;
        }

        // Gestion des chaînes mal formatées
        if (is_string($this->choix) && Str::startsWith($this->choix, '["')) {
            return json_decode($this->choix, true);
        }

        return [$this->choix];
    }
    // Relation avec l'exercice
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    public function userResults()
{
    return $this->hasMany(UserResult::class);
}

    
}