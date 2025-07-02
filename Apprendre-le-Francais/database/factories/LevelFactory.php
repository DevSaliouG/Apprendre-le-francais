<?php

namespace Database\Factories;
// LevelFactory.php
use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{3}'),
            'name' => $this->faker->randomElement(['Débutant', 'Intermediaire', 'Avancée']),
        ];
    }
}