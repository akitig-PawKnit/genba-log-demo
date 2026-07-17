<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'daily_rate',
        'effective_from',
        'effective_to',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'integer',
            'effective_from' => 'date',
            'effective_to' => 'date',
        ];
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }
}
