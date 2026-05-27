<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutRequest;
use App\Models\Workout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkoutController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->filled('date')
            ? $request->date('date')->toDateString()
            : now()->toDateString();
        $workouts = $request->user()->workouts()->whereDate('logged_at', $date)->latest()->paginate(12);

        return view('workouts.index', [
            'workouts' => $workouts,
            'date' => $date,
            'summary' => [
                'duration' => (int) $request->user()->workouts()->whereDate('logged_at', $date)->sum('duration_minutes'),
                'burned' => (int) $request->user()->workouts()->whereDate('logged_at', $date)->sum('calories_burned'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('workouts.create', ['workout' => new Workout(['logged_at' => now()->toDateString()])]);
    }

    public function store(WorkoutRequest $request): RedirectResponse
    {
        $request->user()->workouts()->create($request->validated());

        return redirect()->route('workouts.index')->with('status', 'Workout logged.');
    }

    public function edit(Workout $workout): View
    {
        abort_unless($workout->user_id === auth()->id(), 403);

        return view('workouts.edit', compact('workout'));
    }

    public function update(WorkoutRequest $request, Workout $workout): RedirectResponse
    {
        abort_unless($workout->user_id === auth()->id(), 403);
        $workout->update($request->validated());

        return redirect()->route('workouts.index', ['date' => $workout->logged_at->toDateString()])->with('status', 'Workout updated.');
    }

    public function destroy(Workout $workout): RedirectResponse
    {
        abort_unless($workout->user_id === auth()->id(), 403);
        $workout->delete();

        return back()->with('status', 'Workout deleted.');
    }
}
