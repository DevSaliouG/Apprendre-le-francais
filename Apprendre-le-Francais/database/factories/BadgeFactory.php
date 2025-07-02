<?php

namespace Database\Factories;


use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'icon' => $this->faker->word . '.svg',
            'color' => $this->faker->hexColor,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['achievement', 'level', 'streak']),
            'threshold' => $this->faker->numberBetween(1, 100),
        ];
    }
}