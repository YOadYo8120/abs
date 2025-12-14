<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'semester',
        'specialization',
        'track',
        'week_number',
        'day',
        'start_time',
        'end_time',
        'module_id',
        'schedule_type',
        'teacher_id',
    ];

    /**
     * Get the module for this schedule.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the teacher for this schedule.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the attendances for this schedule.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
