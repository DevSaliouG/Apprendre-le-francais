<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Level;
use App\Models\Badge;
use App\Notifications\LevelUpNotification;
use App\Notifications\BadgeEarnedNotification;

class UserObserver
{
    public function updating(User $user)
    {
        // Vérification du changement de niveau
        if ($user->isDirty('level_id')) {
            $newLevel = Level::find($user->level_id);
            $user->notify(new LevelUpNotification($newLevel));
        }

        // Vérification des badges
        if ($user->isDirty('experience_points')) {
            $this->checkBadges($user);
        }
    }

    protected function checkBadges(User $user)
    {
        $badges = Badge::where('threshold', '<=', $user->experience_points)
            ->whereDoesntHave('users', fn($q) => $q->where('user_id', $user->id))
            ->get();

        foreach ($badges as $badge) {
            $user->badges()->attach($badge->id);
            $user->notify(new BadgeEarnedNotification($badge));
        }
    }
}
