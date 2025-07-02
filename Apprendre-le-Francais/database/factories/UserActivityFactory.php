<?php

namespace Database\Factories;

use App\Models\UserActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserActivityFactory extends Factory
{
    protected $model = UserActivity::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'activity_type' => $this->faker->randomElement(['lesson_completed', 'level_up', 'exercise_completed']),
        ];
    }
}