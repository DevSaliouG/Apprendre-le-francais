<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserResult extends Model
{
    use HasFactory;

    protected $table = 'user_results';

     protected $fillable = [
        'user_id',
        'question_id',
        'reponse',
        'correct',
        'test_type',
         'audio_response'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'correct' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
   public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

