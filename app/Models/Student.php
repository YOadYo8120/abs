<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'email',
        'year',
        'specialization',
        'track',
        'user_id',
    ];

    protected $appends = [
        'specialization_name',
        'track_name',
        'year_name',
    ];

    /**
     * Get the user account for the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances for the student.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get specialization display name
     */
    public function getSpecializationNameAttribute(): ?string
    {
        $specializations = [
            'GI' => 'Génie Informatique',
            'GRT' => 'Génie Réseaux & Télécommunications',
            'GInd' => 'Génie Industriel',
            'GM' => 'Génie Mécatronique',
            'GA' => 'Génie Aéronautique',
            'GPM' => 'Génie Procédés & Matériaux',
        ];

        return $specializations[$this->specialization] ?? null;
    }

    /**
     * Get track display name
     */
    public function getTrackNameAttribute(): ?string
    {
        $tracks = [
            'DEV' => 'Software Development',
            'AI' => 'AI & Data Engineering',
        ];

        return $tracks[$this->track] ?? null;
    }

    /**
     * Get year display name
     */
    public function getYearNameAttribute(): string
    {
        $years = [
            1 => 'CP1 (1st Year)',
            2 => 'CP2 (2nd Year)',
            3 => '3rd Year - Cycle Ingénieur',
            4 => '4th Year - Cycle Ingénieur',
            5 => '5th Year - Cycle Ingénieur',
        ];

        return $years[$this->year] ?? "Year {$this->year}";
    }
}
