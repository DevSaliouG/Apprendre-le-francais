<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Level;

class LevelUpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $level;

    public function __construct(Level $level)
    {
        $this->level = $level;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Félicitations ! Vous avez atteint le niveau {$this->level->name}")
            ->line("Vous avez progressé au niveau {$this->level->name} !")
            ->line($this->level->description)
            ->action('Voir votre progression', url('/dashboard'))
            ->line('Continuez ainsi pour débloquer de nouveaux contenus !');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'level_up',
            'icon' => 'fas fa-trophy',
            'color' => 'text-amber-500',
            'message' => "Vous avez atteint le niveau {$this->level->name}",
            'url' => route('dashboard'),
            'details' => [
                'level_id' => $this->level->id,
                'level_name' => $this->level->name,
            ]
        ];
    }
}