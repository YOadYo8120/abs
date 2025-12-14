<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'scope',
        'module_id',
        'year',
        'specialization',
        'track',
        'semester',
        'schedule_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created the announcement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the module (if scope is module)
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the schedule (if scope is session)
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Scope for published announcements
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope for global announcements (admin only)
     */
    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global');
    }

    /**
     * Get announcements for a specific student
     */
    public static function forStudent(Student $student)
    {
        return static::published()
            ->where(function($query) use ($student) {
                // Global announcements (from admin)
                $query->where('scope', 'global')
                    // Module announcements for student's modules
                    ->orWhere(function($q) use ($student) {
                        $q->where('scope', 'module')
                          ->whereIn('module_id', function($subQuery) use ($student) {
                              $subQuery->select('module_id')
                                  ->from('schedules')
                                  ->where('year', $student->year)
                                  ->where(function($sq) use ($student) {
                                      if ($student->year >= 3) {
                                          $sq->where('specialization', $student->specialization);
                                          if ($student->year >= 4 && $student->specialization === 'GI' && $student->track) {
                                              $sq->where(function($tsq) use ($student) {
                                                  $tsq->whereNull('track')
                                                     ->orWhere('track', '')
                                                     ->orWhere('track', $student->track);
                                              });
                                          }
                                      } else {
                                          $sq->whereNull('specialization');
                                      }
                                  });
                          });
                    })
                    // Class announcements
                    ->orWhere(function($q) use ($student) {
                        $q->where('scope', 'class')
                          ->where('year', $student->year);

                        if ($student->year >= 3) {
                            $q->where('specialization', $student->specialization);
                            if ($student->year >= 4 && $student->specialization === 'GI' && $student->track) {
                                $q->where(function($tq) use ($student) {
                                    $tq->whereNull('track')
                                       ->orWhere('track', '')
                                       ->orWhere('track', $student->track);
                                });
                            }
                        } else {
                            $q->whereNull('specialization');
                        }
                    })
                    // Session announcements for student's schedules
                    ->orWhere(function($q) use ($student) {
                        $q->where('scope', 'session')
                          ->whereIn('schedule_id', function($subQuery) use ($student) {
                              $subQuery->select('id')
                                  ->from('schedules')
                                  ->where('year', $student->year)
                                  ->where(function($sq) use ($student) {
                                      if ($student->year >= 3) {
                                          $sq->where('specialization', $student->specialization);
                                          if ($student->year >= 4 && $student->specialization === 'GI' && $student->track) {
                                              $sq->where(function($tsq) use ($student) {
                                                  $tsq->whereNull('track')
                                                     ->orWhere('track', '')
                                                     ->orWhere('track', $student->track);
                                              });
                                          }
                                      } else {
                                          $sq->whereNull('specialization');
                                      }
                                  });
                          });
                    });
            })
            ->with(['user', 'module', 'schedule.module'])
            ->orderBy('published_at', 'desc');
    }
}
