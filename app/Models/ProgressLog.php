<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight_kg',
        'bmi',
        'calories_consumed',
        'calories_burned',
        'notes',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'bmi' => 'decimal:2',
            'calories_consumed' => 'integer',
            'calories_burned' => 'integer',
            'logged_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
