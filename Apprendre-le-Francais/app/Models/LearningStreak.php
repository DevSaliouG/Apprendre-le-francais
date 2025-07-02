<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningStreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'last_reminder_sent_at',
        'reminder_sent',
        'break_risk_alerted'
    ];

    protected $casts = [
        'last_activity_date' => 'date',
        'last_reminder_sent_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}