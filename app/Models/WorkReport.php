<?php

namespace App\Models;

use App\Enums\WorkRole;
use App\Enums\WorkShift;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_attendance_id',
        'site_id',
        'labor_units',
        'work_shift',
        'work_role',
        'overtime_hours',
        'highway_cost',
        'parking_cost',
        'other_cost',
        'other_cost_note',
        'notes',
        'daily_rate_snapshot',
        'night_multiplier_snapshot',
        'overtime_rate_snapshot',
        'base_labor_cost',
        'overtime_cost',
        'labor_cost',
        'expense_total',
        'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'labor_units' => 'decimal:1',
            'work_shift' => WorkShift::class,
            'work_role' => WorkRole::class,
            'overtime_hours' => 'decimal:1',
            'night_multiplier_snapshot' => 'decimal:2',

            'highway_cost' => 'integer',
            'parking_cost' => 'integer',
            'other_cost' => 'integer',
            'daily_rate_snapshot' => 'integer',
            'overtime_rate_snapshot' => 'integer',
            'base_labor_cost' => 'integer',
            'overtime_cost' => 'integer',
            'labor_cost' => 'integer',
            'expense_total' => 'integer',
            'total_cost' => 'integer',
        ];
    }

    public function dailyAttendance(): BelongsTo
    {
        return $this->belongsTo(DailyAttendance::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function photo(): HasOne
    {
        return $this->hasOne(WorkReportPhoto::class);
    }
}
