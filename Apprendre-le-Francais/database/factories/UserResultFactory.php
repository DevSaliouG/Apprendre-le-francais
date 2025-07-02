<?php

use App\Models\UserResult;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserResultFactory extends Factory
{
    protected $model = UserResult::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'question_id' => \App\Models\Question::factory(),
            'reponse' => $this->faker->word,
            'correct' => $this->faker->boolean,
            'test_type' => $this->faker->randomElement(['quiz', 'pratique']),
            'audio_response' => $this->faker->optional()->filePath(),
        ];
    }
}