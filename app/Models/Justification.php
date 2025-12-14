<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Justification extends Model
{
    protected $fillable = [
        'student_id',
        'attendance_id',
        'description',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // When a justification is being deleted, delete all associated files
        static::deleting(function ($justification) {
            // Delete physical files from storage
            foreach ($justification->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }
            // Delete file records (will be handled by cascade, but we do it explicitly for clarity)
            $justification->files()->delete();
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(JustificationFile::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
