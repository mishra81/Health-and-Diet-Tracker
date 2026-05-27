<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\User;
use App\Models\Workout;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $activeUsers = User::whereHas('meals', fn ($query) => $query->where('logged_at', '>=', now()->subDays(7)))
            ->orWhereHas('workouts', fn ($query) => $query->where('logged_at', '>=', now()->subDays(7)))
            ->count();

        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'activeUsers' => $activeUsers,
            'dietPlans' => DietPlan::count(),
            'averageCalories' => (int) Meal::avg('calories'),
            'workoutTypes' => Workout::selectRaw('type, COUNT(*) as total')->groupBy('type')->orderByDesc('total')->limit(5)->get(),
        ]);
    }
}
