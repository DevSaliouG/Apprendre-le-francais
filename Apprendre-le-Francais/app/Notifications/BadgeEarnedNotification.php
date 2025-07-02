<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Badge;

class BadgeEarnedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $badge;

    public function __construct(Badge $badge)
    {
        $this->badge = $badge;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Félicitations ! Vous avez obtenu le badge {$this->badge->name}")
            ->line("Vous avez débloqué le badge : {$this->badge->name}")
            ->line($this->badge->description)
            ->action('Voir vos badges', url('/badges'))
            ->line('Continuez à relever des défis pour en obtenir plus !');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'badge_earned',
            'icon' => $this->badge->icon,
            'color' => $this->badge->color,
            'message' => "Vous avez obtenu le badge : {$this->badge->name}",
            'url' => route('badges.index'),
            'details' => [
                'badge_id' => $this->badge->id,
                'badge_name' => $this->badge->name,
            ]
        ];
    }
}