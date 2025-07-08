<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgesTableSeeder extends Seeder
{
    public function run()
    {
        $badges = [
            // ===== PROGRESSION =====
            [
                'name' => 'Premiers Pas',
                'icon' => 'fa-footsteps',
                'color' => '#4CAF50',
                'type' => 'lesson',
                'threshold' => 1,
                'description' => 'Compléter votre première leçon',
            ],
            [
                'name' => 'Élève Assidu',
                'icon' => 'fa-book-open',
                'color' => '#2196F3',
                'type' => 'lesson',
                'threshold' => 5,
                'description' => 'Compléter 5 leçons'
            ],
            [
                'name' => 'Maître des Langues',
                'icon' => 'fa-language',
                'color' => '#9C27B0',
                'type' => 'lesson',
                'threshold' => 20,
                'description' => 'Compléter 20 leçons'
            ],
            [
                'name' => 'Niveau Supérieur',
                'icon' => 'fa-arrow-up',
                'color' => '#FF9800',
                'type' => 'level',
                'threshold' => 1,
                'description' => 'Atteindre un nouveau niveau'
            ],

            // ===== PERFORMANCE =====
            [
                'name' => 'Première Étoile',
                'icon' => 'fa-star',
                'color' => '#FFD700',
                'type' => 'exercise',
                'threshold' => 1,
                'description' => 'Réussir son premier exercice'
            ],
            [
                'name' => 'Perfectionniste',
                'icon' => 'fa-medal',
                'color' => '#E91E63',
                'type' => 'exercise',
                'threshold' => 10,
                'description' => 'Obtenir 10 scores parfaits'
            ],
            [
                'name' => 'Vitesse Éclair',
                'icon' => 'fa-bolt',
                'color' => '#FFEB3B',
                'type' => 'exercise',
                'threshold' => 15,
                'description' => 'Terminer 15 exercices en moins de 5 minutes'
            ],

            // ===== ENGAGEMENT =====
            [
                'name' => 'Sérieux',
                'icon' => 'fa-calendar-check',
                'color' => '#3F51B5',
                'type' => 'streak',
                'threshold' => 3,
                'description' => '3 jours consécutifs d\'activité'
            ],
            [
                'name' => 'Déterminé',
                'icon' => 'fa-fire',
                'color' => '#FF5722',
                'type' => 'streak',
                'threshold' => 7,
                'description' => '7 jours consécutifs d\'activité'
            ],
            [
                'name' => 'Légende du Streak',
                'icon' => 'fa-infinity',
                'color' => '#F44336',
                'type' => 'streak',
                'threshold' => 30,
                'description' => '30 jours consécutifs d\'activité'
            ],
            [
                'name' => 'Noctambule',
                'icon' => 'fa-moon',
                'color' => '#673AB7',
                'type' => 'special',
                'threshold' => 5,
                'description' => 'Apprendre après minuit 5 fois'
            ],

            // ===== SOCIAL =====
            [
                'name' => 'Partageur',
                'icon' => 'fa-share-alt',
                'color' => '#4CAF50',
                'type' => 'social',
                'threshold' => 3,
                'description' => 'Partager 3 réalisations'
            ],
            [
                'name' => 'Mentor',
                'icon' => 'fa-hands-helping',
                'color' => '#009688',
                'type' => 'social',
                'threshold' => 5,
                'description' => 'Aider 5 autres utilisateurs'
            ],
            [
                'name' => 'Ambassadeur',
                'icon' => 'fa-certificate',
                'color' => '#FFC107',
                'type' => 'social',
                'threshold' => 10,
                'description' => 'Inviter 10 amis'
            ],

            // ===== SPÉCIAUX =====
            [
                'name' => 'Polyglotte',
                'icon' => 'fa-globe-americas',
                'color' => '#03A9F4',
                'type' => 'special',
                'threshold' => 3,
                'description' => 'Apprendre 3 langues différentes'
            ],
            [
                'name' => 'Collectionneur',
                'icon' => 'fa-trophy',
                'color' => '#9C27B0',
                'type' => 'special',
                'threshold' => 10,
                'description' => 'Obtenir 10 badges différents'
            ],
            [
                'name' => 'Légende Vivante',
                'icon' => 'fa-crown',
                'color' => '#FF9800',
                'type' => 'special',
                'threshold' => 1,
                'description' => 'Atteindre le niveau maximum'
            ]
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }
    }
}