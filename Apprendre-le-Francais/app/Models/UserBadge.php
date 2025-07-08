<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'badge_id',
        'earned_at',
        'context'
    ];

    protected $casts = [
        'earned_at' => 'datetime',
        'context' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    // Formater la date
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->earned_at->format('d/m/Y')
        );
    }

    // Source d'acquisition
    protected function source(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->context['source'] ?? 'inconnue'
        );
    }
}