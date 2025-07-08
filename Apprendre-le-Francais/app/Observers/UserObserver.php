<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Level;
use App\Models\Badge;
use App\Notifications\LevelUpNotification;
use App\Notifications\BadgeEarnedNotification;
use App\Events\BadgeEarned;

class UserObserver
{
    public function updating(User $user)
    {
        if ($user->isDirty('level_id')) {
            $newLevel = Level::find($user->level_id);
            $user->notify(new LevelUpNotification($newLevel));
            event(new \App\Events\LevelUp($user, $newLevel)); // Déclencher l'événement
        }

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
            event(new BadgeEarned($user, $badge)); // Déclencher l'événement
        }
    }
    protected function checkLevelUpBadges(User $user)
{
    $levelBadges = Badge::where('type', 'level')
        ->where('threshold', $user->level_id)
        ->get();

    foreach ($levelBadges as $badge) {
        if (!$user->badges->contains($badge->id)) {
            $user->badges()->attach($badge->id, [
                'earned_at' => now(),
                'context' => ['source' => 'level-up']
            ]);
            
            // Envoyer une notification
            $user->notify(new BadgeEarnedNotification($badge));
        }
    }
}

}