<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StreakMilestone;

class StreakMilestonesSeeder extends Seeder
{
    public function run()
    {
        $milestones = [
            [
                'days_required' => 7,
                'badge_name' => 'Série de 7 jours',
                'badge_icon' => 'fa-fire',
                'notification_message' => 'Félicitations ! Vous avez atteint une série de 7 jours consécutifs.'
            ],
            [
                'days_required' => 14,
                'badge_name' => 'Série de 14 jours',
                'badge_icon' => 'fa-burn',
                'notification_message' => 'Incroyable ! Votre série de 14 jours est un vrai exploit.'
            ],
            [
                'days_required' => 30,
                'badge_name' => 'Maître de la constance',
                'badge_icon' => 'fa-infinity',
                'notification_message' => '30 jours de suite ! Vous êtes un modèle de constance.'
            ]
        ];

        foreach ($milestones as $milestone) {
            StreakMilestone::create($milestone);
        }
    }
}