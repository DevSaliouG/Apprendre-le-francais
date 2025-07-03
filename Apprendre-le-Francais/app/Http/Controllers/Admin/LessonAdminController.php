<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Level;
use Illuminate\Http\Request;

class LessonAdminController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('level')->paginate(10);
        return view('admin.lessons.index', compact('lessons'));
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