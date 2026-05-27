<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DietPlanRequest;
use App\Models\DietPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DietPlanController extends Controller
{
    public function index(): View
    {
        return view('admin.diet-plans.index', [
            'plans' => DietPlan::latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.diet-plans.create', ['plan' => new DietPlan(['is_active' => true])]);
    }

    public function store(DietPlanRequest $request): RedirectResponse
    {
        DietPlan::create($this->payload($request));

        return redirect()->route('admin.diet-plans.index')->with('status', 'Diet plan created.');
    }

    public function edit(DietPlan $dietPlan): View
    {
        return view('admin.diet-plans.edit', ['plan' => $dietPlan]);
    }

    public function update(DietPlanRequest $request, DietPlan $dietPlan): RedirectResponse
    {
        $dietPlan->update($this->payload($request));

        return redirect()->route('admin.diet-plans.index')->with('status', 'Diet plan updated.');
    }

    public function destroy(DietPlan $dietPlan): RedirectResponse
    {
        $dietPlan->delete();

        return back()->with('status', 'Diet plan deleted.');
    }

    private function payload(DietPlanRequest $request): array
    {
        return $request->validated() + ['is_active' => $request->boolean('is_active')];
    }
}
