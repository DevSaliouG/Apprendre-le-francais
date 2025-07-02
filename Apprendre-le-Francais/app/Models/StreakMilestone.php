<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreakMilestone extends Model
{
    use HasFactory;

        protected $fillable = [
        'days_required', 
        'badge_name',
        'badge_icon',
        'notification_message'
    ];
}
