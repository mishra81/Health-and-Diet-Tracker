<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Models\User;
use Illuminate\Support\Collection;

class DietRecommendationService
{
    public function forUser(User $user): Collection
    {
        $profile = $user->profile;
        $goal = $user->goals()->where('status', 'active')->latest()->first();
        $goalType = $goal?->type ?? $this->goalFromBmi($profile?->bmi);

        $plans = DietPlan::query()
            ->where('is_active', true)
            ->where(function ($query) use ($goalType) {
                $query->where('goal_type', $goalType)->orWhere('goal_type', 'maintenance');
            })
            ->where(function ($query) use ($profile) {
                $preference = $profile?->dietary_preference ?? 'balanced';
                $query->where('dietary_preference', $preference)->orWhere('dietary_preference', 'balanced');
            })
            ->where(function ($query) use ($profile) {
                $query->whereNull('activity_level')
                    ->orWhere('activity_level', $profile?->activity_level ?? 'moderate');
            })
            ->orderByRaw("CASE WHEN dietary_preference = ? THEN 0 ELSE 1 END", [$profile?->dietary_preference ?? 'balanced'])
            ->limit(4)
            ->get();

        return $plans->isNotEmpty() ? $plans : DietPlan::where('is_active', true)->limit(4)->get();
    }

    private function goalFromBmi(?float $bmi): string
    {
        return match (true) {
            $bmi === null => 'maintenance',
            $bmi >= 25 => 'weight_loss',
            $bmi < 18.5 => 'weight_gain',
            default => 'maintenance',
        };
    }
}
