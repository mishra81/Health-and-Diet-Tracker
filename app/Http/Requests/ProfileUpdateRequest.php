<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'age' => ['nullable', 'integer', 'min:10', 'max:120'],
            'gender' => ['nullable', 'string', 'max:50'],
            'height_cm' => ['nullable', 'numeric', 'min:50', 'max:260'],
            'weight_kg' => ['nullable', 'numeric', 'min:20', 'max:400'],
            'goal_weight_kg' => ['nullable', 'numeric', 'min:20', 'max:400'],
            'activity_level' => ['nullable', Rule::in(['sedentary', 'light', 'moderate', 'active', 'athlete'])],
            'dietary_preference' => ['nullable', Rule::in(['balanced', 'vegetarian', 'vegan', 'high_protein', 'keto'])],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
