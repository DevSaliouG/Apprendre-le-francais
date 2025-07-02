<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AdminNotification extends Notification
{
    use Queueable;

    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $type = get_class($this->event);
        $data = [];

        switch ($type) {
            case 'App\Events\BadgeEarned':
                $data = [
                    'message' => "{$this->event->user->name} a obtenu le badge {$this->event->badge->name}",
                    'icon' => 'fas fa-medal',
                    'url' => route('admin.users.show', $this->event->user->id),
                    'type' => 'badge'
                ];
                break;
                
            case 'App\Events\NewUserRegistered':
                $data = [
                    'message' => "Nouvel utilisateur inscrit: {$this->event->user->name}",
                    'icon' => 'fas fa-user-plus',
                    'url' => route('admin.users.show', $this->event->user->id),
                    'type' => 'user'
                ];
                break;
                
            case 'App\Events\LevelUp':
                $data = [
                    'message' => "{$this->event->user->name} a atteint le niveau {$this->event->level->name}",
                    'icon' => 'fas fa-level-up-alt',
                    'url' => route('admin.users.show', $this->event->user->id),
                    'type' => 'level'
                ];
                break;
                
            case 'App\Events\ExerciseCompleted':
                if ($this->event->score >= 85) {
                    $data = [
                        'message' => "{$this->event->user->name} a réussi l'exercice {$this->event->exercise->id} avec {$this->event->score}%",
                        'icon' => 'fas fa-check-circle',
                        'url' => route('admin.exercises.show', $this->event->exercise->id),
                        'type' => 'exercise'
                    ];
                }
                break;
        }

        return $data;
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}