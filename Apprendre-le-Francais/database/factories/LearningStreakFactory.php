<?php

namespace Database\Factories;

use App\Models\LearningStreak;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearningStreakFactory extends Factory
{
    protected $model = LearningStreak::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'last_activity_date' => $this->faker->dateTimeThisMonth,
            'current_streak' => $this->faker->numberBetween(0, 30),
            'longest_streak' => $this->faker->numberBetween(5, 100),
        ];
    }
}