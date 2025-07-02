<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'badge_id'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le badge
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Obtenir la date de création formatée
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }
}