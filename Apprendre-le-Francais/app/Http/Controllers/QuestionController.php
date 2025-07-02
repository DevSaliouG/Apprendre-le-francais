<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
   public function html(Question $question)
{
    $exercise = $question->exercise;
    $questions = $exercise->questions()->inRandomOrder()->get();
    $currentIndex = $questions->search(function ($q) use ($question) {
        return $q->id === $question->id;
    });
    
    return view('partials.question', [
        'question' => $question,
        'exercise' => $exercise,
        'questions' => $exercise,  
        'currentIndex' => $currentIndex
    ]);
}
}