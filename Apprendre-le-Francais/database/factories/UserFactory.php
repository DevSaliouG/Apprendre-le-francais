<?php

namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'prenom' => $this->faker->firstName,
            'nom' => $this->faker->lastName,
            'adresse' => $this->faker->address,
            'dateNaiss' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'is_admin' => false,
            'level_id' => \App\Models\Level::factory(),
        ];
    }

    public function admin()
    {
        return $this->state([
            'is_admin' => true,
        ]);
    }
}