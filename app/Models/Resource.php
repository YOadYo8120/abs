<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file_name',
        'file_path',
        'file_content',
        'file_type',
        'file_size',
        'scope',
        'module_id',
        'year',
        'specialization',
        'track',
        'semester',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded the resource
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
     * Scope for published resources
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Get resources for a specific student
     */
    public static function forStudent(Student $student)
    {
        return static::published()
            ->where(function($query) use ($student) {
                // Module resources for student's modules
                $query->where(function($q) use ($student) {
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
                              })
                              ->distinct();
                      });
                })
                // Class resources matching student's class
                ->orWhere(function($q) use ($student) {
                    $q->where('scope', 'class')
                      ->where('year', $student->year)
                      ->where('semester', function($sq) {
                          // Get current semester from academic calendar
                          $sq->selectRaw('semester')
                             ->from('academic_calendar')
                             ->whereRaw('? BETWEEN start_date AND end_date', [now()->format('Y-m-d')])
                             ->limit(1);
                      })
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
            ->orderBy('published_at', 'desc');
    }
}
