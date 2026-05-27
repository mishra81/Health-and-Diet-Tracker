<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'met_value',
        'calories_per_hour',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'met_value' => 'decimal:2',
            'calories_per_hour' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
