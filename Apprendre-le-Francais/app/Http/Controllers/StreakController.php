<?php

namespace App\Http\Controllers;

use App\Mail\StreakReminderEmail;
use App\Models\Badge;
use App\Models\User;
use App\Models\LearningStreak;
use App\Models\StreakMilestone;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class StreakController extends Controller
{
    /**
     * Affiche le tableau de bord des streaks avec le calendrier des activités
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $streak = $user->learningStreak ?? $this->createNewStreak($user);

        // Génère le calendrier des 42 jours (6 semaines) pour une grille parfaite
        $calendar = $this->generateCalendarGrid($user);

        // Vérifier les paliers atteints
        $this->checkMilestones($user, $streak->current_streak);

        // Vérifier si une relance est nécessaire
        $this->checkReminderNeeded($user);

        return view('streak.index', compact('streak', 'calendar'));
    }

    /**
     * Génère un calendrier de 42 jours (6 semaines) parfaitement aligné
     */
    private function generateCalendarGrid(User $user)
    {
        $today = now();
        $startDate = now()->subDays(29);

        // Récupère les dates avec activité
        $activities = UserActivity::where('user_id', $user->id)
            ->whereDate('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as activity_date')
            ->distinct()
            ->pluck('activity_date')
            ->toArray();

        // Premier jour du calendrier (lundi de la semaine il y a 4 semaines)
        $calendarStart = $today->copy()->subWeeks(4)->startOfWeek(Carbon::MONDAY);
        $calendar = [];

        // Construit le tableau du calendrier (42 jours)
        for ($i = 0; $i < 42; $i++) {
            $date = $calendarStart->copy()->addDays($i);
            $isActive = in_array($date->format('Y-m-d'), $activities);

            $calendar[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'weekday' => $this->translateWeekday($date->format('D')),
                'active' => $isActive,
                'today' => $date->isToday(),
                'in_range' => $date >= $startDate && $date <= $today
            ];
        }

        return $calendar;
    }

    /**
     * Vérifie et attribue les paliers de récompense
     */
    private function checkMilestones(User $user, $currentStreak)
    {
        $milestones = StreakMilestone::all();

        foreach ($milestones as $milestone) {
            if ($currentStreak >= $milestone->days_required) {
                // Vérifier si le badge a déjà été attribué
                $badge = Badge::firstOrCreate(
                    ['name' => $milestone->badge_name],
                    ['icon' => $milestone->badge_icon]
                );

                // Attribuer le badge à l'utilisateur
                if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, [
                        'earned_at' => now(),
                        'message' => $milestone->notification_message
                    ]);

                    // Créer une notification
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Nouveau badge obtenu !',
                        'message' => $milestone->notification_message,
                        'icon' => $milestone->badge_icon,
                        'read_at' => null
                    ]);
                }
            }
        }
    }

    /**
     * Vérifie si une relance est nécessaire
     */
    private function checkReminderNeeded(User $user)
    {
        $streak = $user->learningStreak;

        if (!$streak || !$streak->last_activity_date) return;

        $lastActive = Carbon::parse($streak->last_activity_date);
        $today = now();

        // Vérifier si hier a été manqué
        if ($lastActive->isYesterday() && !$streak->break_risk_alerted) {
            // Marquer comme alerté
            $streak->update([
                'break_risk_alerted' => true,
                'last_reminder_sent_at' => now()
            ]);

            // Envoyer une notification
           

$user->notifications()->create([
    'id' => Str::uuid(),
    'type' => 'streak_danger', // Choisissez un identifiant logique
    'data' => [
        'title' => 'Votre streak est en danger !',
        'message' => 'Vous avez manqué une journée hier. Revenez aujourd\'hui pour sauver votre streak !',
        'icon' => 'fa-exclamation-triangle'
    ]
]);
/* 
            // Envoyer un email
            Mail::to($user->email)->send(new StreakReminderEmail($user)); */
        }

        // Réinitialiser l'alerte si l'utilisateur est actif aujourd'hui
        if ($today->isToday() && $streak->break_risk_alerted) {
            $streak->update(['break_risk_alerted' => false]);
        }
    }

    /**
     * Crée un nouveau streak pour un utilisateur
     * 
     * @param User $user
     * @return LearningStreak
     */
    private function createNewStreak(User $user)
    {
        return LearningStreak::create([
            'user_id' => $user->id,
            'current_streak' => 0,
            'longest_streak' => 0,
            'last_activity_date' => null
        ]);
    }

    /**
     * Génère un calendrier des activités des 30 derniers jours
     * 
     * @param User $user
     * @return array
     */
    private function generateCalendar(User $user)
    {
        $calendar = [];
        $today = now();
        $startDate = now()->subDays(29); // 30 jours incluant aujourd'hui

        // Récupère les dates avec activité
        $activities = UserActivity::where('user_id', $user->id)
            ->whereDate('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as activity_date')
            ->distinct()
            ->pluck('activity_date')
            ->toArray();

        // Construit le tableau du calendrier
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $calendar[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'weekday' => $this->translateWeekday($date->format('D')),
                'active' => in_array($date->format('Y-m-d'), $activities),
                'today' => $date->isToday()
            ];
        }

        return $calendar;
    }

    /**
     * Traduit le jour de la semaine en français
     * 
     * @param string $englishDay
     * @return string
     */
    private function translateWeekday($englishDay)
    {
        $translations = [
            'Mon' => 'Lun',
            'Tue' => 'Mar',
            'Wed' => 'Mer',
            'Thu' => 'Jeu',
            'Fri' => 'Ven',
            'Sat' => 'Sam',
            'Sun' => 'Dim'
        ];
        return $translations[$englishDay] ?? $englishDay;
    }

    /**
     * Réclame la récompense quotidienne
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    // app/Http/Controllers/StreakController.php
    public function claimDailyReward()
    {
        $user = Auth::user();
        $streak = $user->learningStreak ?? $this->createNewStreak($user);

        // Vérification robuste avec Carbon
        $lastActivityDate = $streak->last_activity_date
            ? Carbon::parse($streak->last_activity_date)
            : null;

        // Vérifier si la récompense a déjà été réclamée aujourd'hui
        if ($lastActivityDate && $lastActivityDate->isToday()) {
            return redirect()->route('streak.index')
                ->with('info', 'Vous avez déjà réclamé votre récompense aujourd\'hui');
        }

        // Vérifier si c'est un jour consécutif (hier)
        $isConsecutive = $lastActivityDate && $lastActivityDate->isYesterday();

        // Mettre à jour le streak
        $streak->current_streak = $isConsecutive ? $streak->current_streak + 1 : 1;
        $streak->last_activity_date = now()->format('Y-m-d');

        // Mettre à jour le record si nécessaire
        if ($streak->current_streak > $streak->longest_streak) {
            $streak->longest_streak = $streak->current_streak;
        }

        $streak->save();

        // Ajouter les points d'expérience
        $reward = $this->calculateDailyReward($streak->current_streak);
        $user->increment('experience_points', $reward);

        // Enregistrer l'activité
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'daily_streak',
            'points_earned' => $reward
        ]);

        return redirect()->route('streak.index')
            ->with('success', 'Récompense quotidienne obtenue! +' . $reward . ' points');
    }

    /**
     * Calcule la récompense en fonction de la longueur du streak
     * 
     * @param int $streakLength
     * @return int
     */
    private function calculateDailyReward($streakLength)
    {
        // Base: 10 points + 2 points par jour de streak (max 100)
        return min(100, 10 + ($streakLength * 2));
    }
}
