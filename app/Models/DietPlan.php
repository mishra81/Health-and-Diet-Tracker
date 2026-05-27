<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'goal_type',
        'dietary_preference',
        'activity_level',
        'calorie_target',
        'protein_target_g',
        'carbs_target_g',
        'fat_target_g',
        'description',
        'meals',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'calorie_target' => 'integer',
            'protein_target_g' => 'integer',
            'carbs_target_g' => 'integer',
            'fat_target_g' => 'integer',
            'meals' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
