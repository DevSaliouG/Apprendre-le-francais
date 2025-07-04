<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\UserResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseAdminController extends Controller
{
   public function index(Request $request)
{
    $query = Exercise::query()->with(['lesson', 'lesson.level']);

    // Recherche par titre de la leçon associée
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->input('search');
        $query->whereHas('lesson', function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%");
        });
    }

    // Filtre par leçon
    if ($request->has('lesson_id') && !empty($request->lesson_id)) {
        $query->where('lesson_id', $request->input('lesson_id'));
    }

    // Filtre par type
    if ($request->has('type') && !empty($request->type)) {
        $query->where('type', $request->input('type'));
    }

    // Tri par date de création (le plus récent en premier)
    $exercises = $query->orderByDesc('id')->paginate(10);

    // Récupération des leçons pour les filtres
    $lessons = Lesson::pluck('title', 'id');

    return view('admin.exercises.index', compact('exercises', 'lessons'));
}


    public function create()
    {
        $lessons = Lesson::all();
        return view('admin.exercises.create', compact('lessons'));
    }

    public function store(StoreExerciseRequest $request)
    {
        Exercise::create($request->validated());
        return redirect()->route('admin.exercises.index')->with('success', 'Exercice créé !');
    }

    public function show(Exercise $exercise)
    {
        $exercise->load('questions');
        return view('admin.exercises.show', compact('exercise'));
    }

    public function edit(Exercise $exercise)
    {
        $lessons = Lesson::all();
        return view('admin.exercises.edit', compact('exercise', 'lessons'));
    }


    public function update(UpdateExerciseRequest $request, Exercise $exercise)
    {
        $validated = $request->validated();

        // Gestion du fichier audio
        if ($request->hasFile('audio_path')) {
            // Supprimer l'ancien fichier s'il existe
            if ($exercise->audio_path && Storage::exists($exercise->audio_path)) {
                Storage::delete($exercise->audio_path);
            }

            // Stocker le nouveau fichier
            $validated['audio_path'] = $request->file('audio_path')->store('exercises/audios', 'public');
        }

        $exercise->update($validated);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercice mis à jour avec succès');
    }

    public function destroy(Exercise $exercise)
    {
        // Supprimer les questions associées et leurs fichiers
        foreach ($exercise->questions as $question) {
            // Supprimer le fichier audio de la question
            if ($question->fichier_audio) {
                Storage::disk('public')->delete($question->fichier_audio);
            }

            // Supprimer les résultats utilisateurs associés
            UserResult::whereIn('question_id', $exercise->questions()->pluck('id'))->delete();

            $question->delete();
        }

        // Supprimer le fichier audio de l'exercice
        if ($exercise->audio_path) {
            Storage::disk('public')->delete($exercise->audio_path);
        }

        // Supprimer l'exercice
        $exercise->delete();

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercice supprimé avec succès');
    }
}
