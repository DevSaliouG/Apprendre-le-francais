<?php

namespace App\Events;

use App\Models\User;
use App\Models\Level;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LevelUp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $level;

    public function __construct(User $user, Level $level)
    {
        $this->user = $user;
        $this->level = $level;
    }
}