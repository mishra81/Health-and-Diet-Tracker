<?php

namespace App\Services;

use App\Models\User;
use Carbon\CarbonPeriod;

class AnalyticsService
{
    public function dashboard(User $user): array
    {
        $today = now()->toDateString();
        $period = CarbonPeriod::create(now()->subDays(6)->toDateString(), $today);
        $labels = [];
        $caloriesConsumed = [];
        $caloriesBurned = [];
        $workoutMinutes = [];
        $weight = [];
        $bmi = [];

        foreach ($period as $date) {
            $day = $date->toDateString();
            $labels[] = $date->format('M j');
            $caloriesConsumed[] = (int) $user->meals()->whereDate('logged_at', $day)->sum('calories');
            $caloriesBurned[] = (int) $user->workouts()->whereDate('logged_at', $day)->sum('calories_burned');
            $workoutMinutes[] = (int) $user->workouts()->whereDate('logged_at', $day)->sum('duration_minutes');
            $log = $user->progressLogs()->whereDate('logged_at', $day)->latest()->first();
            $weight[] = $log?->weight_kg ? (float) $log->weight_kg : null;
            $bmi[] = $log?->bmi ? (float) $log->bmi : null;
        }

        $mealTypes = $user->meals()
            ->selectRaw('meal_type, SUM(calories) as total')
            ->whereDate('logged_at', $today)
            ->groupBy('meal_type')
            ->pluck('total', 'meal_type')
            ->toArray();

        return [
            'labels' => $labels,
            'caloriesConsumed' => $caloriesConsumed,
            'caloriesBurned' => $caloriesBurned,
            'workoutMinutes' => $workoutMinutes,
            'weight' => $weight,
            'bmi' => $bmi,
            'mealTypes' => $mealTypes,
        ];
    }
}
