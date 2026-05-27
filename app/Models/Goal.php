<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_weight_kg',
        'target_weight_kg',
        'daily_calorie_target',
        'workout_frequency_target',
        'starts_on',
        'target_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_weight_kg' => 'decimal:2',
            'target_weight_kg' => 'decimal:2',
            'daily_calorie_target' => 'integer',
            'workout_frequency_target' => 'integer',
            'starts_on' => 'date',
            'target_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentageAttribute(): int
    {
        $currentWeight = (float) ($this->user?->profile?->weight_kg ?? $this->start_weight_kg);
        $start = (float) $this->start_weight_kg;
        $target = (float) $this->target_weight_kg;
        $distance = abs($start - $target);

        if ($distance <= 0) {
            return 100;
        }

        $completed = $this->type === 'weight_gain'
            ? $currentWeight - $start
            : $start - $currentWeight;

        if ($this->type === 'maintenance') {
            $completed = max(0, $distance - abs($currentWeight - $target));
        }

        return (int) min(100, max(0, round(($completed / $distance) * 100)));
    }
}
