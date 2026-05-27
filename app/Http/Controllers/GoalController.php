<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoalRequest;
use App\Models\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoalController extends Controller
{
    public function index(Request $request): View
    {
        return view('goals.index', [
            'goals' => $request->user()->goals()->latest()->paginate(10),
            'profile' => $request->user()->profile,
        ]);
    }

    public function create(Request $request): View
    {
        $profile = $request->user()->profile;
        $goal = new Goal([
            'start_weight_kg' => $profile?->weight_kg,
            'target_weight_kg' => $profile?->goal_weight_kg,
            'daily_calorie_target' => 2200,
            'workout_frequency_target' => 4,
            'starts_on' => now()->toDateString(),
            'status' => 'active',
        ]);

        return view('goals.create', compact('goal'));
    }

    public function store(GoalRequest $request): RedirectResponse
    {
        $request->user()->goals()->create($request->validated() + ['status' => $request->input('status', 'active')]);

        return redirect()->route('goals.index')->with('status', 'Goal created.');
    }

    public function edit(Goal $goal): View
    {
        abort_unless($goal->user_id === auth()->id(), 403);

        return view('goals.edit', compact('goal'));
    }

    public function update(GoalRequest $request, Goal $goal): RedirectResponse
    {
        abort_unless($goal->user_id === auth()->id(), 403);
        $goal->update($request->validated() + ['status' => $request->input('status', 'active')]);

        return redirect()->route('goals.index')->with('status', 'Goal updated.');
    }

    public function destroy(Goal $goal): RedirectResponse
    {
        abort_unless($goal->user_id === auth()->id(), 403);
        $goal->delete();

        return back()->with('status', 'Goal deleted.');
    }
}
