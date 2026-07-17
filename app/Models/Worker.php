<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'name',
        'pin_hash',
        'display_order',
        'joined_on',
        'left_on',
        'is_active',
        'last_login_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_on' => 'date',
            'left_on' => 'date',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    protected $hidden = [
        'pin_hash',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(WorkerRate::class);
    }

    public function dailyAttendances(): HasMany
    {
        return $this->hasMany(DailyAttendance::class);
    }
}
