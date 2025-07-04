<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionAdminController extends Controller
{

public function index(Request $request)
{
    $query = Question::query();

    // Recherche par texte
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->input('search');
        $query->where('texte', 'like', "%{$search}%");
    }

    // Filtre par exercice
    if ($request->has('exercise_id') && !empty($request->exercise_id)) {
        $query->where('exercise_id', $request->input('exercise_id'));
    }

    // Filtre par format de réponse
    if ($request->has('format') && !empty($request->format)) {
        $query->where('format_reponse', $request->input('format'));
    }

    // Récupération des exercices pour le filtre
    $exercises = Exercise::with('lesson')->get()->mapWithKeys(function ($exercise) {
        return [$exercise->id => "Leçon {$exercise->lesson->id} - Exercice {$exercise->id}"];
    });

    $questions = $query->with('exercise')->paginate(10);
    $formats = ['choix_multiple' => 'Choix multiple', 'texte_libre' => 'Texte libre', 'audio' => 'Audio'];
    
    return view('admin.questions.index', compact('questions', 'exercises', 'formats'));
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
            'questions/audio/' . $exercise->id, 
            'public'
        );
    }

    // Transformation des données selon le format
    if ($validated['format_reponse'] === 'choix_multiple') {
        $validated['choix'] = $request->choix;
    } else {
        $validated['choix'] = null;
    }

    $exercise->questions()->create($validated);
    
    return redirect()->route('admin.exercises.show', $exercise)
                    ->with('success', 'Question ajoutée avec succès!');
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
            if ($question->fichier_audio) {
                Storage::disk('public')->delete($question->fichier_audio);
            }
            $validated['fichier_audio'] = $request->file('fichier_audio')->store(
                'questions/audio/' . $question->exercise_id,
                'public'
            );
        } elseif ($request->has('remove_audio') && $request->remove_audio) {
            if ($question->fichier_audio) {
                Storage::disk('public')->delete($question->fichier_audio);
            }
            $validated['fichier_audio'] = null;
        } else {
            $validated['fichier_audio'] = $question->fichier_audio;
        }

        // Transformation complète des données selon le format
        $newFormat = $validated['format_reponse'];
        $oldFormat = $question->format_reponse;

        if ($newFormat === 'choix_multiple') {
            // Conserver les choix
            $validated['choix'] = $request->choix;

            // Convertir la réponse correcte en index numérique
            $validated['reponse_correcte'] = (string)$request->reponse_correcte;
        } else {
            // Pour texte libre ou audio
            $validated['choix'] = null;

            // Si on change de format, prendre la nouvelle valeur textuelle
            if ($newFormat !== $oldFormat) {
                $validated['reponse_correcte'] = $request->reponse_correcte;
            }
        }

        $question->update($validated);

        return redirect()->route('admin.questions.show', $question)
            ->with('success', 'Question mise à jour avec succès');
    }
    public function destroy(Question $question)
{
    // Supprimer le fichier audio s'il existe
    if ($question->fichier_audio) {
        Storage::disk('public')->delete($question->fichier_audio);
    }
    
    $exercise = $question->exercise;
    $question->delete();
    
    return redirect()->route('admin.exercises.show', $exercise)
        ->with('success', 'Question supprimée avec succès');
}
}
