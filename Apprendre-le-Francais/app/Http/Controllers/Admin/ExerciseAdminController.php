<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use Illuminate\Support\Facades\Storage;

class ExerciseAdminController extends Controller
{
    public function index()
    {
        $exercises = Exercise::with('lesson')->paginate(10);
        return view('admin.exercises.index', compact('exercises'));
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
}