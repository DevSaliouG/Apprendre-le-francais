<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StreakDanger extends Notification
{
    use Queueable;

    public function toArray($notifiable)
    {
        return [
            'title' => 'Votre streak est en danger !',
            'message' => 'Vous avez manqué une journée hier. Revenez aujourd\'hui pour sauver votre série !',
            'icon' => 'fa-exclamation-triangle',
            'url' => route('streak.index'),
            'type' => 'streak_danger'
        ];
    }

    public function via($notifiable)
    {
        return ['database'];
    }
}