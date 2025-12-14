<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'schedule_id',
        'student_id',
        'status',
        'notes',
    ];

    /**
     * Get the schedule for the attendance.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Get the student for the attendance.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the justification for the attendance.
     */
    public function justification()
    {
        return $this->hasOne(Justification::class);
    }
}
