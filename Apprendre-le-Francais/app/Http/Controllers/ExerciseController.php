<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Question;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

    use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;

class ExerciseController extends Controller
{
    // Afficher la liste des exercices
public function index()
{
    $user = Auth::user();
    
    $exercises = Exercise::with(['lesson.level'])
        ->whereHas('lesson', function ($query) use ($user) {
            $query->where('level_id', $user->level_id);
        })
        ->withCount('questions')
        ->paginate(10);
    
    $completionStatus = [];
    foreach ($exercises as $exercise) {
        $completionStatus[$exercise->id] = [
            'completed' => $user->isExerciseCompleted($exercise->id),
            'started' => $user->results()
                ->whereIn('question_id', $exercise->questions()->pluck('id'))
                ->exists(),
            'correct_count' => $user->results()
                ->whereIn('question_id', $exercise->questions()->pluck('id'))
                ->where('correct', true)
                ->count()
        ];
    }
    
    $progress = [
        'completed' => $user->getLevelProgress()['completed'],
        'total' => $user->getLevelProgress()['total'],
        'percentage' => $user->getLevelProgress()['percentage']
    ];

     

    return view('exercises.index', compact(
        'exercises',
        'progress',
        'completionStatus',
        
    ));
}

private function getUserProgress(User $user)
{
    $totalExercises = Exercise::whereHas('lesson', function ($query) use ($user) {
        $query->where('level_id', $user->level_id);
    })->count();

    $completedExercises = Exercise::whereHas('lesson', function ($query) use ($user) {
        $query->where('level_id', $user->level_id);
    })
    ->get()
    ->filter(function ($exercise) use ($user) {
        return $exercise->isCompletedByUser($user->id);
    })
    ->count();

    return [
        'total' => $totalExercises,
        'completed' => $completedExercises,
        'percentage' => $totalExercises > 0 
            ? round(($completedExercises / $totalExercises) * 100)
            : 0
    ];
}

 public function show(Exercise $exercise, Request $request)
{
    $user = auth()->user();
    
    if ($exercise->lesson->level_id !== $user->level_id) {
        return redirect()->route('exercises.index')
            ->with('error', "Vous n'avez pas encore accès à cet exercice.");
    }

    // Récupérer les résultats existants
    $existingResults = $user->results()
        ->whereIn('question_id', $exercise->questions->pluck('id'))
        ->where('correct', true)
        ->get()
        ->keyBy('question_id');

    // Filtrer les questions : seulement celles non réussies
    $questions = $exercise->questions()
        ->whereNotIn('id', $existingResults->keys())
        ->inRandomOrder()
        ->get();

    // Si toutes les questions sont réussies, permettre de tout refaire
    if ($questions->isEmpty() && $request->has('retry_all')) {
        $questions = $exercise->questions()->inRandomOrder()->get();
        $existingResults = collect();
    }

    // Si aucune question à répondre
    if ($questions->isEmpty()) {
        return redirect()->route('exercises.result', $exercise)
            ->with('info', 'Vous avez déjà réussi toutes les questions de cet exercice !');
    }

    return view('exercises.show', compact(
        'exercise',
        'questions',
        'existingResults'
    ));
}


public function submitQuestion(Request $request, Exercise $exercise, Question $question)
{
    $user = Auth::user();
    $isCorrect = false;

    // Traitement selon le type de question
    if ($question->format_reponse === 'choix_multiple') {
        $isCorrect = $request->reponse === $question->reponse_correcte;
    } elseif ($question->format_reponse === 'texte_libre') {
        $isCorrect = $this->checkTextAnswer($request->reponse, $question->reponse_correcte);
    } elseif ($question->format_reponse === 'audio') {
        $isCorrect = $this->processAudioResponse($request, $question, $user);
    }

    // Enregistrement du résultat
    UserResult::updateOrCreate(
        ['user_id' => $user->id, 'question_id' => $question->id],
        [
            'reponse' => $request->reponse ?? '',
            'correct' => $isCorrect,
            'test_type' => $exercise->type === 'oral' ? 'speaking' : 'writing'
        ]
    );

    // Vérifier si l'utilisateur a complété tous les exercices du niveau
    $levelUp = false;
    $newLevel = null;
    
    if ($user->completeLevel()) {
        $levelUp = true;
        $newLevel = $user->level;
    }

    return response()->json([
        'correct' => $isCorrect,
        'correction' => $question->reponse_correcte,
        'level_up' => $levelUp,
        'new_level' => $levelUp ? $newLevel->name : null
    ]);
}   

    // Obtenir le feedback détaillé pour une question
    public function feedback(Question $question)
    {
        $user = Auth::user();
        $result = UserResult::where('user_id', $user->id)
            ->where('question_id', $question->id)
            ->firstOrFail();

        $explanation = $this->generateExplanation($question, $result);

        return view('exercises.feedback', compact(
            'question',
            'result',
            'explanation'
        ));
    }

    // Vérifier les réponses texte libre (tolérance orthographique)
    private function checkTextAnswer($userAnswer, $correctAnswer)
    {
        if (empty($userAnswer)) return false;

        $userAnswer = Str::lower(trim($userAnswer));
        $correctAnswer = Str::lower(trim($correctAnswer));

        // Tolérance pour les accents et caractères spéciaux
        $userAnswer = iconv('UTF-8', 'ASCII//TRANSLIT', $userAnswer);
        $correctAnswer = iconv('UTF-8', 'ASCII//TRANSLIT', $correctAnswer);

        // Tolérance pour les espaces supplémentaires
        $userAnswer = preg_replace('/\s+/', ' ', $userAnswer);

        return $userAnswer === $correctAnswer;
    }

    // Traiter les réponses audio
    private function processAudioResponse(Request $request, Question $question, User $user)
    {
        // Enregistrer le fichier audio
        if ($request->hasFile('audio_response_' . $question->id)) {
            $audio = $request->file('audio_response_' . $question->id);
            $path = $audio->store('user_audio/' . $user->id, 'public');

            // Enregistrer le chemin dans les résultats
            UserResult::updateOrCreate(
                ['user_id' => $user->id, 'question_id' => $question->id],
                ['audio_response' => $path]
            );

            // Ici vous ajouteriez l'intégration avec un service d'analyse de prononciation
            // Pour l'exemple, nous considérons que c'est correct si un fichier est envoyé
            return true;
        }

        return false;
    }

    // Générer une explication personnalisée
    private function generateExplanation(Question $question, UserResult $result)
    {
        $explanations = [
            'grammar' => "Attention à l'accord du participe passé!",
            'vocabulary' => "Ce mot est souvent confondu avec un autre terme similaire.",
            'pronunciation' => "Votre prononciation du son 'eu' pourrait être améliorée.",
            'conjugation' => "N'oubliez pas que ce verbe est irrégulier au présent."
        ];

        // Analyse simple basée sur le type d'erreur
        $errorType = 'vocabulary';
        if (!$result->correct) {
            if (str_contains($question->texte, 'accord')) $errorType = 'grammar';
            if (str_contains($question->texte, 'conjugaison')) $errorType = 'conjugation';
            if ($question->format_reponse === 'audio') $errorType = 'pronunciation';
        }

        return [
            'type' => $errorType,
            'message' => $explanations[$errorType] ?? "Veuillez réviser ce point de grammaire.",
            'resources' => $this->getLearningResources($errorType)
        ];
    }

    // Obtenir des ressources d'apprentissage
   private function getLearningResources($errorType)
{
    $resources = [
        'grammar' => [
            [
                'title' => 'Guide complet de grammaire française', 
                'url' => 'https://www.laits.utexas.edu/tex/gr/'
            ],
            [
                'title' => 'Exercices d\'accord du participe passé', 
                'url' => 'https://www.francaisfacile.com/exercices/exercice-francais-2/exercice-francais-19013.php'
            ],
            [
                'title' => 'Les règles d\'accord en français', 
                'url' => 'https://bescherelle.com/accord-des-adjectifs-de-couleur-regles-et-exceptions-112'
            ]
        ],
        'vocabulary' => [
            [
                'title' => 'Listes de vocabulaire par thèmes', 
                'url' => 'https://www.lepointdufle.net/vocabulaire.htm'
            ],
            [
                'title' => 'Jeu de mémoire des mots - Quizlet', 
                'url' => 'https://quizlet.com/fr/subjects/french/'
            ],
            [
                'title' => 'Vocabulaire illustré avec audio', 
                'url' => 'https://imagier.net/francais'
            ]
        ],
        'pronunciation' => [
            [
                'title' => 'Guide complet de prononciation', 
                'url' => 'https://prononciation.free.fr/'
            ],
            [
                'title' => 'Exercices de phonétique interactive', 
                'url' => 'https://www.phonomem.fr/'
            ],
            [
                'title' => 'Les sons du français avec vidéos', 
                'url' => 'https://www.tv5monde.com/apprendre-francais/les-sons'
            ]
        ],
        'conjugation' => [
            [
                'title' => 'Conjugueur de verbes en ligne', 
                'url' => 'https://leconjugueur.lefigaro.fr/'
            ],
            [
                'title' => 'Jeu des verbes irréguliers', 
                'url' => 'https://www.logicieleducatif.fr/francais/conjugaison/verbes_irreguliers.php'
            ],
            [
                'title' => 'Tableaux de conjugaison complets', 
                'url' => 'https://la-conjugaison.nouvelobs.com/'
            ]
        ]
    ];

    

    return $resources[$errorType] ?? [
        [
            'title' => 'Ressources générales - TV5Monde', 
            'url' => 'https://apprendre.tv5monde.com/fr'
        ],
        [
            'title' => 'Cours complets de français', 
            'url' => 'https://www.francaisfacile.com/'
        ],
        [
            'title' => 'Exercices interactifs pour tous niveaux', 
            'url' => 'https://www.lepointdufle.net/'
        ]
    ];
}

    // Vérifier si un exercice est complété par l'utilisateur
    private function isExerciseCompleted(User $user, Exercise $exercise)
    {
        $questionCount = $exercise->questions()->count();
        $correctAnswers = UserResult::where('user_id', $user->id)
            ->whereIn('question_id', $exercise->questions()->pluck('id'))
            ->where('correct', true)
            ->count();

        return ($correctAnswers / $questionCount) >= 0.8;
    }

   


private function analyzePronunciation($audioFile, $expectedText)
{
    $speech = new SpeechClient();
    $config = (new RecognitionConfig())
        ->setLanguageCode('fr-FR');
    
    $audio = (new RecognitionAudio())
        ->setContent(file_get_contents($audioFile->getPathname()));
    
    $response = $speech->recognize($config, $audio);
    
    $transcript = '';
    foreach ($response->getResults() as $result) {
        $transcript .= $result->getAlternatives()[0]->getTranscript();
    }
    
    // Comparaison phonétique
    $similarity = 0;
    similar_text(
        metaphone($transcript), 
        metaphone($expectedText), 
        $similarity
    );

    return $similarity >= 70;
}

public function showResult(Exercise $exercise)
{
    $user = Auth::user();
    $questions = $exercise->questions;
    $totalQuestions = $questions->count();
    
    // Récupérer les résultats de l'utilisateur
    $userResults = UserResult::where('user_id', $user->id)
        ->whereIn('question_id', $questions->pluck('id'))
        ->get();
    
    $score = $userResults->where('correct', true)->count();
    $percentage = round(($score / $totalQuestions) * 100);
    $isSuccess = $percentage >= 80;
    $isExerciseCompleted = $this->isExerciseCompleted($user, $exercise);

    return view('exercises.result', compact(
        'exercise',
        'score',
        'totalQuestions',
        'percentage',
        'isSuccess',
        'isExerciseCompleted'
    ));
}
}
