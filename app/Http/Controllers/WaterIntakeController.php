<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaterIntakeRequest;
use App\Models\WaterIntake;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaterIntakeController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->filled('date')
            ? $request->date('date')->toDateString()
            : now()->toDateString();
        $entries = $request->user()->waterIntakes()->whereDate('logged_at', $date)->latest()->paginate(12);
        $total = (int) $request->user()->waterIntakes()->whereDate('logged_at', $date)->sum('amount_ml');

        return view('water-intakes.index', [
            'entries' => $entries,
            'date' => $date,
            'total' => $total,
            'goal' => 3000,
        ]);
    }

    public function store(WaterIntakeRequest $request): RedirectResponse
    {
        $request->user()->waterIntakes()->create($request->validated());

        return back()->with('status', 'Water intake logged.');
    }

    public function destroy(WaterIntake $waterIntake): RedirectResponse
    {
        abort_unless($waterIntake->user_id === auth()->id(), 403);
        $waterIntake->delete();

        return back()->with('status', 'Water entry deleted.');
    }
}
