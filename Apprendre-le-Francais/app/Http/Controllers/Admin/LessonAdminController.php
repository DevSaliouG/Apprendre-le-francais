<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Level;
use Illuminate\Http\Request;

class LessonAdminController extends Controller
{
public function index(Request $request)
{
    $query = Lesson::query()->with('level');

    // Recherche par titre
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('title', 'like', "%{$search}%");
    }

    // Filtre par niveau
    if ($request->has('level')) {
        $query->where('level_id', $request->input('level'));
    }

    // Filtre par type
    if ($request->has('type')) {
        $query->where('type', $request->input('type'));
    }

    $lessons = $query->orderByDesc('id')->paginate(10);
    $levels = Level::all(); // Pour le filtre des niveaux

    return view('admin.lessons.index', compact('lessons', 'levels'));
}

    public function create()
    {
        $levels = Level::all();
        return view('admin.lessons.create', compact('levels'));
    }

public function store(Request $request)
{
    $request->validate([
        'level_id' => 'required|exists:levels,id',
        'title' => 'required|max:255',
        'content' => 'required',
        'type' => 'required|in:' . implode(',', Lesson::TYPES), // Validation du type
    ]);

    Lesson::create($request->all());
    return redirect()->route('admin.lessons.index')->with('success', 'Leçon créée.');
}

    public function edit(Lesson $lesson)
    {
        $levels = Level::all();
        return view('admin.lessons.edit', compact('lesson', 'levels'));
    }

public function update(Request $request, Lesson $lesson)
{
    $request->validate([
        'level_id' => 'required|exists:levels,id',
        'title' => 'required|max:255',
        'content' => 'required',
        'type' => 'required|in:' . implode(',', Lesson::TYPES), // Validation du type
    ]);

    $lesson->update($request->all());
    return redirect()->route('admin.lessons.index')->with('success', 'Leçon mise à jour.');
}

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('success', 'Leçon supprimée.');
    }
}