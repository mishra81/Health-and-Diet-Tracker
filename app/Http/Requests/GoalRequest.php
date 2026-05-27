<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['weight_loss', 'weight_gain', 'maintenance'])],
            'start_weight_kg' => ['required', 'numeric', 'min:20', 'max:400'],
            'target_weight_kg' => ['required', 'numeric', 'min:20', 'max:400'],
            'daily_calorie_target' => ['required', 'integer', 'min:800', 'max:8000'],
            'workout_frequency_target' => ['required', 'integer', 'min:0', 'max:14'],
            'starts_on' => ['required', 'date'],
            'target_date' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'status' => ['nullable', Rule::in(['active', 'paused', 'completed'])],
        ];
    }
}
