<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public function upgrade()
{
    $user = Auth::user();
    $nextLevel = $user->level->getNextLevel();
    
    if ($nextLevel) {
        $user->level_id = $nextLevel->id;
        $user->save();
        
        return redirect()->route('exercises.index')
            ->with('success', 'Félicitations ! Vous êtes maintenant au niveau ' . $nextLevel->name);
    }
    
    return redirect()->back()
        ->with('error', 'Vous avez déjà atteint le niveau maximum !');
}
}