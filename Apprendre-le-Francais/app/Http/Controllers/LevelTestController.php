<?php

namespace App\Http\Controllers;  

use App\Models\Question;  
use App\Models\UserResult;  
use App\Models\Level;  
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LevelTestController extends Controller  
{
    public function home(){

        return view('home');
    }
    // Démarre le test de niveau
    public function startTest()
    {
        // Générer les questions du test
        $questions = $this->generateTestQuestions();
        
        // Stocker les questions et l'état du test en session
        Session::put('test_questions', $questions);
        Session::put('current_question_index', 0);
        Session::put('test_score', 0);
        
        return redirect()->route('test.showQuestion');
    }

    // Affiche la question courante
    public function showQuestion()
    {
       $index = Session::get('current_question_index', 0);
    $questions = Session::get('test_questions', []);
    
    // Vérifier si le test est terminé
    if ($index >= count($questions)) {
        return redirect()->route('test.complete');
    }
        
        $question = Question::with('exercise')->find($questions[$index]);
        
        return view('user.test-question', [
            'question' => $question,
            'progress' => round(($index / count($questions)) * 100),
            'question_count' => count($questions),
            'current_index' => $index + 1
        ]);
    }

    // Traite la réponse et passe à la question suivante
    public function submitAnswer(Request $request)  
    {
        $user = Auth::user();
        $index = Session::get('current_question_index');
        $questions = Session::get('test_questions');
        $questionId = $questions[$index];
        
        $question = Question::findOrFail($questionId);
        $isCorrect = $this->checkAnswer($request->reponse, $question);
        
        // Enregistrer le résultat
        UserResult::create([  
            'user_id' => $user->id,  
            'question_id' => $questionId,  
            'reponse' => $request->reponse,  
            'correct' => $isCorrect,  
            'test_type' => 'placement',  
        ]);
        
        // Mettre à jour le score
        if ($isCorrect) {
            Session::increment('test_score');
        }
        
        // Passer à la question suivante
        Session::increment('current_question_index');
        
        return redirect()->route('test.showQuestion');
    }

    // Génère les questions pour le test
    protected function generateTestQuestions()
    {
        return Question::with('exercise')
            ->whereHas('exercise', function($query) {  
                $query->whereIn('type', ['écrit', 'oral']);  
            })
            ->inRandomOrder()
            ->limit(10) // Augmenté pour une meilleure évaluation
            ->pluck('id')
            ->toArray();
    }

    // Vérifie la réponse
    protected function checkAnswer($userAnswer, Question $question)
    {
        // Pour les questions orales, on pourrait ajouter une logique de similarité
        if ($question->exercise->type === 'oral') {
            return $this->checkOralAnswer($userAnswer, $question);
        }
        
        return $userAnswer === $question->reponse_correcte;
    }

    // Logique simplifiée pour les réponses orales
    protected function checkOralAnswer($userAnswer, Question $question)
    {
        // En production, intégrer un service d'analyse de prononciation
        return true; // Temporairement toujours vrai
    }

    // Termine le test et attribue le niveau
   protected function completeTest()
{
    $user = Auth::user();
    $score = Session::get('test_score');
    $totalQuestions = count(Session::get('test_questions'));
    
    // Déterminer le niveau basé sur le pourcentage de réussite
    $percentage = ($score / $totalQuestions) * 100;
    
    $level = match(true) {  
        $percentage <= 30 => Level::where('code', 'A1')->first(),  
        $percentage <= 60 => Level::where('code', 'A2')->first(),  
        $percentage <= 80 => Level::where('code', 'B1')->first(),
        default => Level::where('code', 'B2')->first(),  
    };

    // Mise à jour du niveau utilisateur
    $user->level_id = $level->id;
    $user->save();

    // Nettoyer la session
    Session::forget(['test_questions', 'current_question_index', 'test_score']);

    // Régénérer la session pour éviter les conflits
    session()->regenerate();

    return redirect()->route('dashboard')->with([
        'success' => 'Votre niveau a été évalué à '.$level->name,
        'level' => $level
    ]);  
}
   public function __construct()
{
    $this->middleware('auth');
    
    // Empêche les utilisateurs avec niveau de refaire le test
    $this->middleware(function ($request, $next) {
        $user = Auth::user();
        $user->refresh(); // Recharger les données fraîches
        
        if ($user->level_id) {
            return redirect()->route('dashboard')
                ->with('info', 'Vous avez déjà complété le test de niveau');
        }
        
        return $next($request);
    })->except(['completeTest', 'home']);
}

// Ajoutez cette méthode pour éviter les redirections
public function completeTestView()
{
    return $this->completeTest();
}
}