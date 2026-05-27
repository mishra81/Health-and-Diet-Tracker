<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterIntake extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_ml',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_ml' => 'integer',
            'logged_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
