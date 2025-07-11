<?php

namespace App\Http\Controllers;  

use App\Models\Question;  
use App\Models\UserResult;  
use App\Models\Level;
use App\Models\User;
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
public function generateQuestions(User $user, $testType, $count)
{
    // Récupérer les IDs des niveaux "Débutant" et "Intermédiaire"
    $allowedLevels = Level::whereIn('name', ['Débutant', 'Intermédiaire'])
                          ->pluck('id')
                          ->toArray();
    
    // Vérifier si on a bien les niveaux nécessaires
    if (empty($allowedLevels)) {
        // Créer les niveaux s'ils n'existent pas
        $beginnerLevel = Level::firstOrCreate(
            ['name' => 'Débutant'],
            ['description' => 'Niveau débutant', 'order' => 1]
        );
        
        $intermediateLevel = Level::firstOrCreate(
            ['name' => 'Intermédiaire'],
            ['description' => 'Niveau intermédiaire', 'order' => 2]
        );
        
        $allowedLevels = [$beginnerLevel->id, $intermediateLevel->id];
    }
    
    // Générer 5 questions écrites
    $writtenQuestions = Question::whereIn('level_id', $allowedLevels)
                                ->where('type', 'écrit')
                                ->inRandomOrder()
                                ->limit(5)
                                ->get();
    
    // Générer 5 questions orales
    $oralQuestions = Question::whereIn('level_id', $allowedLevels)
                             ->where('type', 'oral')
                             ->inRandomOrder()
                             ->limit(5)
                             ->get();
    
    // Combiner les deux types de questions
    $questions = $writtenQuestions->merge($oralQuestions);
    
    // Vérifier s'il y a suffisamment de questions
    if ($questions->count() < 10) {
        $needed = 10 - $questions->count();
        
        // Compléter avec des questions de niveau débutant
        $fallbackLevel = Level::where('name', 'Débutant')->first();
        
        // Déterminer quel type est manquant
        $writtenCount = $writtenQuestions->count();
        $oralCount = $oralQuestions->count();
        
        if ($writtenCount < 5) {
            $additionalWritten = Question::where('level_id', $fallbackLevel->id)
                                         ->where('type', 'écrit')
                                         ->whereNotIn('id', $questions->pluck('id'))
                                         ->inRandomOrder()
                                         ->limit($needed)
                                         ->get();
            
            $questions = $questions->merge($additionalWritten);
        }
        
        if ($oralCount < 5) {
            $additionalOral = Question::where('level_id', $fallbackLevel->id)
                                      ->where('type', 'oral')
                                      ->whereNotIn('id', $questions->pluck('id'))
                                      ->inRandomOrder()
                                      ->limit($needed)
                                      ->get();
            
            $questions = $questions->merge($additionalOral);
        }
    }
    
    return $questions->shuffle(); // Mélanger les questions pour varier l'ordre
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
        $percentage <= 30 => Level::where('code', 'D')->first(),  
        $percentage <= 60 => Level::where('code', 'I')->first(),  
        $percentage <= 80 => Level::where('code', 'I')->first(),
        default => Level::where('code', 'D')->first(),  
    };

    // Mise à jour du niveau utilisateur
    $user->level_id = $level->id;
    $user->save();

    // Nettoyer la session
    Session::forget(['test_questions', 'current_question_index', 'test_score']);

    // Régénérer la session pour éviter les conflits
    session()->regenerate();

    // Ajoutez ces données pour la vue intermédiaire
    $testResults = [
        'score' => $score,
        'total' => $totalQuestions,
        'percentage' => round($percentage),
        'level' => $level,
        'redirectUrl' => route('dashboard'),
        'delay' => 5 // Délai en secondes avant redirection
    ];

    return view('user.test-results', $testResults);
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