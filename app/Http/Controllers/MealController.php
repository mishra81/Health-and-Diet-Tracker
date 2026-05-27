<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealRequest;
use App\Models\Meal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->filled('date')
            ? $request->date('date')->toDateString()
            : now()->toDateString();
        $meals = $request->user()->meals()->whereDate('logged_at', $date)->latest('logged_at')->latest()->paginate(12);

        return view('meals.index', [
            'meals' => $meals,
            'date' => $date,
            'summary' => [
                'calories' => (int) $request->user()->meals()->whereDate('logged_at', $date)->sum('calories'),
                'protein' => (float) $request->user()->meals()->whereDate('logged_at', $date)->sum('protein_g'),
                'carbs' => (float) $request->user()->meals()->whereDate('logged_at', $date)->sum('carbs_g'),
                'fat' => (float) $request->user()->meals()->whereDate('logged_at', $date)->sum('fat_g'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('meals.create', ['meal' => new Meal(['logged_at' => now()->toDateString()])]);
    }

    public function store(MealRequest $request): RedirectResponse
    {
        $request->user()->meals()->create($request->validated());

        return redirect()->route('meals.index')->with('status', 'Meal logged.');
    }

    public function edit(Meal $meal): View
    {
        abort_unless($meal->user_id === auth()->id(), 403);

        return view('meals.edit', compact('meal'));
    }

    public function update(MealRequest $request, Meal $meal): RedirectResponse
    {
        abort_unless($meal->user_id === auth()->id(), 403);
        $meal->update($request->validated());

        return redirect()->route('meals.index', ['date' => $meal->logged_at->toDateString()])->with('status', 'Meal updated.');
    }

    public function destroy(Meal $meal): RedirectResponse
    {
        abort_unless($meal->user_id === auth()->id(), 403);
        $meal->delete();

        return back()->with('status', 'Meal deleted.');
    }
}
