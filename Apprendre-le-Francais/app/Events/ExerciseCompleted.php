<?php

namespace App\Events;

use App\Models\User;
use App\Models\Exercise;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExerciseCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $exercise;
    public $score;

    public function __construct(User $user, Exercise $exercise, $score)
    {
        $this->user = $user;
        $this->exercise = $exercise;
        $this->score = $score;
    }
}