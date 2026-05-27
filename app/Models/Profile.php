<?php

namespace App\Models;

use App\Services\HealthMetricService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'height_cm',
        'weight_kg',
        'goal_weight_kg',
        'activity_level',
        'dietary_preference',
        'profile_photo_path',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'height_cm' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'goal_weight_kg' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBmiAttribute(): ?float
    {
        return app(HealthMetricService::class)->bmi($this->weight_kg, $this->height_cm);
    }

    public function getBmiCategoryAttribute(): string
    {
        return app(HealthMetricService::class)->bmiCategory($this->bmi);
    }
}
