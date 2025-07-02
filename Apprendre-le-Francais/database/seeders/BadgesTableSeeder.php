<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // Fichier de seed (ex: database/seeders/BadgeSeeder.php)
public function run()
{
    $badges = [
        // Badges de progression
        [
            'name' => 'Premiers pas',
            'icon' => 'fa-seedling',
            'color' => '#4CC9F0',
            'type' => 'progress',
            'threshold' => 1,
            'description' => 'Compléter votre première leçon',
        ],
        [
            'name' => 'Explorateur',
            'icon' => 'fa-compass',
            'color' => '#36D1DC',
            'type' => 'progress',
            'threshold' => 10,
            'description' => 'Compléter 10 leçons'
        ],
        [
            'name' => 'Maître des langues',
            'icon' => 'fa-globe',
            'color' => '#6C63FF',
            'type' => 'progress',
            'threshold' => 3,
            'description' => 'Atteindre le niveau avancé'
        ],

        // Badges de performance
        [
            'name' => 'Perfectionniste',
            'icon' => 'fa-star',
            'color' => '#FFD700',
            'type' => 'performance',
            'threshold' => 5,
            'description' => 'Obtenir un score parfait à 5 exercices'
        ],
        [
            'name' => 'Rapidité',
            'icon' => 'fa-bolt',
            'color' => '#FFEB3B',
            'type' => 'performance',
            'threshold' => 20,
            'description' => 'Répondre à 20 questions en moins de 10 secondes',
        ],

        // Badges d'engagement
        [
            'name' => 'Sérieux',
            'icon' => 'fa-calendar-alt',
            'color' => '#FF9E6D',
            'type' => 'engagement',
            'threshold' => 7,
            'description' => 'Utiliser l\'application 7 jours consécutifs',
        ],
        [
            'name' => 'Déterminé',
            'icon' => 'fa-fire',
            'color' => '#FF6B8B',
            'type' => 'engagement',
            'threshold' => 30,
            'description' => 'Atteindre une série de 30 jours'
        ],

        // Badges sociaux
        [
            'name' => 'Partageur',
            'icon' => 'fa-share-alt',
            'color' => '#4CAF50',
            'type' => 'social',
            'threshold' => 5,
            'description' => 'Partager 5 réalisations'
        ],
        [
            'name' => 'Mentor',
            'icon' => 'fa-hands-helping',
            'color' => '#8A84FF',
            'type' => 'social',
            'threshold' => 3,
            'description' => 'Aider 3 autres utilisateurs'
        ],

        // Badges spéciaux
        [
            'name' => 'Noctambule',
            'icon' => 'fa-moon',
            'color' => '#2E2A47',
            'type' => 'special',
            'threshold' => 1,
            'description' => 'Apprendre après minuit'
        ],
        [
            'name' => 'Polyglotte',
            'icon' => 'fa-language',
            'color' => '#FF6584',
            'type' => 'special',
            'threshold' => 3,
            'description' => 'Apprendre 3 langues différentes'
        ]
    ];

    foreach ($badges as $badge) {
        Badge::create($badge);
    }
}
}
