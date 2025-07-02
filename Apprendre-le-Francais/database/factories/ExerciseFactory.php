<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition()
    {
        return [
            'lesson_id' => \App\Models\Lesson::factory(),
            'type' => $this->faker->randomElement(['écrit', 'oral']),
            'audio_path' => $this->faker->optional()->filePath(),
            'difficulty' => $this->faker->numberBetween(1, 5),
        ];
    }
}