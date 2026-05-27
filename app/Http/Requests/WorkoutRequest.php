<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(['cardio', 'strength', 'yoga', 'running', 'cycling'])],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'calories_burned' => ['required', 'integer', 'min:0', 'max:10000'],
            'logged_at' => ['required', 'date'],
        ];
    }
}
