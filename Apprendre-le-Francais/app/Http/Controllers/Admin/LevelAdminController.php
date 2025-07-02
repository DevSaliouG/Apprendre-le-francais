<?php
namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelAdminController extends Controller
{
    public function index()
    {
        $levels = Level::paginate(10);
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:levels|max:10',
            'name' => 'required|max:50',
        ]);

        Level::create($request->all());
        return redirect()->route('admin.levels.index')->with('success', 'Niveau créé.');
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'code' => 'required|max:10|unique:levels,code,'.$level->id,
            'name' => 'required|max:50',
        ]);

        $level->update($request->all());
        return redirect()->route('admin.levels.index')->with('success', 'Niveau mis à jour.');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return back()->with('success', 'Niveau supprimé.');
    }
}