<?php

namespace App\Providers;

use App\Notifications\BadgeUnlocked;
use App\Http\Controllers\BadgeController;
use App\Models\Badge;
use App\Models\LessonCompletion;
use App\Models\StreakMilestone;
use App\Models\User;
use App\Models\UserResult;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  public function boot()
{
    // Vérifier les badges après chaque activité
    UserResult::created(function ($result) {
        $this->checkBadges($result->user);
    });

    LessonCompletion::created(function ($completion) {
        $this->checkBadges($completion->user);
    });

    View::composer('*', function ($view) {
        $view->with('milestones', StreakMilestone::orderBy('days_required')->get());
    });

    User::observe(UserObserver::class);

    // Ajouter d'autres événements selon vos besoins
}

private function checkBadges(User $user)
{
    $badges = Badge::all();
    
    foreach ($badges as $badge) {
        if (!$user->badges->contains($badge->id)) {
            $progress = app('App\Http\Controllers\BadgeController')->calculateProgress($user, $badge);
            
            if ($progress['current'] >= $badge->threshold) {
                $user->badges()->attach($badge->id);
                
                try {
                    $user->notify(new BadgeUnlocked($badge));
                } catch (\Exception $e) {
                    Log::error("Notification error: " . $e->getMessage());
                }
            }
        }
    }
}

}
