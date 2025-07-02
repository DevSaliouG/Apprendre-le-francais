<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Support\Facades\Storage;

class QuestionAdminController extends Controller
{
    public function index()
    {
        $questions = Question::with('exercise')->paginate(10);
        return view('admin.questions.index', compact('questions'));
    }

    public function create(Exercise $exercise)
    {
        return view('admin.questions.create', compact('exercise'));
    }

    public function store(StoreQuestionRequest $request, Exercise $exercise)
    {
        $validated = $request->validated();
        
        // Gestion du fichier audio
        if ($request->hasFile('fichier_audio')) {
            $validated['fichier_audio'] = $request->file('fichier_audio')->store(
                'questions/audio/' . $exercise->id, 'public'
            );
        }

        $exercise->questions()->create($validated);
        
        return redirect()->route('admin.exercises.show', $exercise)
                        ->with('success', 'Question ajoutée !');
    }

    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $exercise = $question->exercise; // Récupérer l'exercice associé
        return view('admin.questions.edit', compact('question', 'exercise'));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $validated = $request->validated();
        
        // Gestion du fichier audio
        if ($request->hasFile('fichier_audio')) {
            // Supprimer l'ancien fichier s'il existe
            if ($question->fichier_audio && Storage::exists($question->fichier_audio)) {
                Storage::delete($question->fichier_audio);
            }
            
            $validated['fichier_audio'] = $request->file('fichier_audio')->store(
                'questions/audio/' . $question->exercise_id, 'public'
            );
        }

        $question->update($validated);
        
        return redirect()->route('admin.exercises.show', $question->exercise)
                        ->with('success', 'Question mise à jour !');
    }
}