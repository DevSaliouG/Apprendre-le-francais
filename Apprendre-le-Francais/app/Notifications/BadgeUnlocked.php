<?php

namespace App\Notifications;

use App\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BadgeUnlocked extends Notification implements ShouldQueue
{
    use Queueable;

    public $badge;

    public function __construct(Badge $badge)
    {
        $this->badge = $badge;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'badge_id' => $this->badge->id,
            'badge_name' => $this->badge->name,
            'message' => 'Vous avez débloqué un nouveau badge : ' . $this->badge->name,
            'icon' => $this->badge->icon,
            'color' => $this->badge->color,
            'link' => route('badges.show', $this->badge),
        ];
    }
}