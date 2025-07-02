<?php

namespace Tests\Unit;

use App\Models\Level;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class LevelPromotionTest extends TestCase
{
public function test_level_promotion()
{
    $user = User::factory()->create(['level_id' => 1]);
    $level = Level::factory()->create();
    
    // Simuler la complétion de tous les exercices
    config(['app.level_threshold' => 80]);
    $this->assertTrue($user->completeLevel());
    
    // Vérifier la mise à jour du niveau
    $this->assertEquals($level->id, $user->fresh()->level_id);
}

public function test_promotion_threshold()
{
    $user = User::factory()->create(['level_id' => 1]);
    
    // Seuil trop élevé (90%)
    config(['app.level_threshold' => 90]);
    $this->assertFalse($user->completeLevel());
}
}
