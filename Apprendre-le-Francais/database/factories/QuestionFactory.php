<?php

namespace Database\Factories;
// QuestionFactory.php
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        $choices = [
            $this->faker->word,
            $this->faker->word,
            $this->faker->word,
            $this->faker->word,
        ];

        return [
            'exercise_id' => \App\Models\Exercise::factory(),
            'texte' => $this->faker->sentence . '?',
            'choix' => $choices,
            'reponse_correcte' => $this->faker->randomElement($choices),
            'fichier_audio' => $this->faker->optional()->filePath(),
        ];
    }
}