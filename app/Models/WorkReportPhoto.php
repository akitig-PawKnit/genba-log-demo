<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkReportPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_report_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'width',
        'height',
        'latitude',
        'longitude',
        'location_accuracy',
        'location_captured_at',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'location_accuracy' => 'decimal:2',
            'location_captured_at' => 'datetime',
            'uploaded_at' => 'datetime',
        ];
    }

    public function workReport(): BelongsTo
    {
        return $this->belongsTo(WorkReport::class);
    }
}
