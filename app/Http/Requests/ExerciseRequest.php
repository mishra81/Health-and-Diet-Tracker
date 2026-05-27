<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExerciseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(['cardio', 'strength', 'yoga', 'running', 'cycling'])],
            'met_value' => ['nullable', 'numeric', 'min:0', 'max:30'],
            'calories_per_hour' => ['nullable', 'integer', 'min:0', 'max:3000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
