<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DietPlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'goal_type' => ['required', Rule::in(['weight_loss', 'weight_gain', 'maintenance'])],
            'dietary_preference' => ['required', Rule::in(['balanced', 'vegetarian', 'vegan', 'high_protein', 'keto'])],
            'activity_level' => ['nullable', Rule::in(['sedentary', 'light', 'moderate', 'active', 'athlete'])],
            'calorie_target' => ['required', 'integer', 'min:800', 'max:8000'],
            'protein_target_g' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'carbs_target_g' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'fat_target_g' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'meals' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
