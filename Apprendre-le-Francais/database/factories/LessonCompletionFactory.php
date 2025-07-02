<?php 

namespace Database\Factories;

use App\Models\LessonCompletion;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonCompletionFactory extends Factory
{
    protected $model = LessonCompletion::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'lesson_id' => \App\Models\Lesson::factory(),
            'score' => $this->faker->numberBetween(0, 100),
            'completed_at' => $this->faker->dateTimeThisYear,
        ];
    }
}