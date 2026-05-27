<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaterIntakeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount_ml' => ['required', 'integer', 'min:1', 'max:10000'],
            'logged_at' => ['required', 'date'],
        ];
    }
}
