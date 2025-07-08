<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color',
        'description',
        'type',
        'threshold',
        'rarity' // Nouveau: commun, rare, épique, légendaire
    ];

    protected $casts = [
        'threshold' => 'integer'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at', 'context')
            ->withTimestamps();
    }

    // Accessor pour l'icône
    public function getIconAttribute($value)
    {
        return $value ?? 'fa-medal';
    }

    // Accessor pour la rareté
    public function getRarityAttribute()
    {
        $rarityMap = [
            1 => 'commun',
            5 => 'rare',
            10 => 'épique',
            20 => 'légendaire'
        ];

        foreach ($rarityMap as $threshold => $rarity) {
            if ($this->threshold <= $threshold) {
                return $rarity;
            }
        }

        return 'mythique';
    }
}