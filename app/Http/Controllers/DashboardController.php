<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\DietRecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, AnalyticsService $analyticsService, DietRecommendationService $recommendationService): View
    {
        $user = $request->user()->load('profile');
        $today = now()->toDateString();
        $activeGoal = $user->goals()->where('status', 'active')->latest()->first();
        $dailyCalories = (int) $user->meals()->whereDate('logged_at', $today)->sum('calories');
        $burnedCalories = (int) $user->workouts()->whereDate('logged_at', $today)->sum('calories_burned');
        $waterMl = (int) $user->waterIntakes()->whereDate('logged_at', $today)->sum('amount_ml');
        $workoutStreak = $this->workoutStreak($user);

        return view('dashboard', [
            'profile' => $user->profile,
            'activeGoal' => $activeGoal,
            'dailyCalories' => $dailyCalories,
            'burnedCalories' => $burnedCalories,
            'waterMl' => $waterMl,
            'waterGoalMl' => 3000,
            'workoutStreak' => $workoutStreak,
            'analytics' => $analyticsService->dashboard($user),
            'recommendations' => $recommendationService->forUser($user),
        ]);
    }

    private function workoutStreak($user): int
    {
        $streak = 0;
        $cursor = now();

        while ($user->workouts()->whereDate('logged_at', $cursor->toDateString())->exists()) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }
}
