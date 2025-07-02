<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StreakDanger extends Notification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Votre streak est en danger !',
            'message' => 'Vous avez manqué une journée hier. Revenez aujourd\'hui pour sauver votre streak !',
            'icon' => 'fa-exclamation-triangle'
        ];
    }

    public function via($notifiable)
    {
        return ['database'];
    }
}