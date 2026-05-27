<?php

namespace App\Services;

class HealthMetricService
{
    public function bmi(null|float|string $weightKg, null|float|string $heightCm): ?float
    {
        if (! $weightKg || ! $heightCm || (float) $heightCm <= 0) {
            return null;
        }

        $heightM = (float) $heightCm / 100;

        return round((float) $weightKg / ($heightM * $heightM), 1);
    }

    public function bmiCategory(?float $bmi): string
    {
        return match (true) {
            $bmi === null => 'Not available',
            $bmi < 18.5 => 'Underweight',
            $bmi < 25 => 'Normal',
            $bmi < 30 => 'Overweight',
            default => 'Obese',
        };
    }
}
