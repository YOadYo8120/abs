<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'year',
        'specialization',
        'track',
        'teacher_id',
    ];

    /**
     * Get the teacher assigned to this module
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the schedules for this module
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
