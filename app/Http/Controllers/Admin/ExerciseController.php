<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExerciseRequest;
use App\Models\Exercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function index(): View
    {
        return view('admin.exercises.index', [
            'exercises' => Exercise::latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.exercises.create', ['exercise' => new Exercise(['is_active' => true])]);
    }

    public function store(ExerciseRequest $request): RedirectResponse
    {
        Exercise::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.exercises.index')->with('status', 'Exercise created.');
    }

    public function edit(Exercise $exercise): View
    {
        return view('admin.exercises.edit', compact('exercise'));
    }

    public function update(ExerciseRequest $request, Exercise $exercise): RedirectResponse
    {
        $exercise->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.exercises.index')->with('status', 'Exercise updated.');
    }

    public function destroy(Exercise $exercise): RedirectResponse
    {
        $exercise->delete();

        return back()->with('status', 'Exercise deleted.');
    }
}
