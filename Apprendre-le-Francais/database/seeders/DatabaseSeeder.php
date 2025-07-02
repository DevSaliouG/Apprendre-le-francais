<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void  
    {
            // Créer l'admin
    $admin = User::updateOrCreate(
        ['email' => 'gayesaliou59@gmail.com'],
        [
            'prenom' => 'Saliou',
            'nom' => 'Gaye',
            'dateNaiss' => '2001-02-21',
            'adresse' => 'Dakar',
            'password' => bcrypt('password'),
            'is_admin' => true
        ]
    );

    $this->call(BadgesTableSeeder::class);

    // Appliquer votre commande spécifique
    User::where('email', 'gayesaliou59@gmail.com')->update(['is_admin' => true]);


      
    }
}