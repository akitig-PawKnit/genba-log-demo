<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'contract_amount',
        'starts_on',
        'planned_ends_on',
        'ended_on',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'contract_amount' => 'integer',
            'starts_on' => 'date',
            'planned_ends_on' => 'date',
            'ended_on' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function workReports(): HasMany
    {
        return $this->hasMany(WorkReport::class);
    }
}
