<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitializePastAttendance extends Command
{
    protected $signature = 'attendance:initialize-past';
    protected $description = 'Initialize present attendance for all past sessions that have occurred';

    public function handle()
    {
        $this->info('Initializing past attendance records...');

        $students = Student::all();
        $totalCreated = 0;

        foreach ($students as $student) {
            // Get all schedules for this student that have already occurred
            $pastSchedules = DB::table('schedules')
                ->join('academic_calendar', function($join) {
                    $join->on('schedules.year', '=', 'academic_calendar.year')
                         ->on('schedules.semester', '=', 'academic_calendar.semester')
                         ->on('schedules.week_number', '=', 'academic_calendar.week_number');
                })
                ->where('schedules.year', $student->year)
                ->where('schedules.week_number', '>', 0) // Only actual weeks, not template
                ->where('academic_calendar.end_date', '<=', now()->format('Y-m-d'))
                ->where(function($query) use ($student) {
                    if ($student->year >= 3) {
                        $query->where('schedules.specialization', $student->specialization);

                        if ($student->year >= 4 && $student->specialization === 'GI' && $student->track) {
                            $query->where(function($q) use ($student) {
                                $q->whereNull('schedules.track')
                                  ->orWhere('schedules.track', '')
                                  ->orWhere('schedules.track', $student->track);
                            });
                        }
                    } else {
                        $query->whereNull('schedules.specialization');
                    }
                })
                ->select('schedules.*')
                ->get();

            // Auto-create "present" attendance for all past schedules where no record exists
            foreach ($pastSchedules as $schedule) {
                $created = Attendance::firstOrCreate(
                    [
                        'schedule_id' => $schedule->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'status' => 'present',
                        'marked_by' => null,
                    ]
                );

                if ($created->wasRecentlyCreated) {
                    $totalCreated++;
                }
            }

            if ($pastSchedules->count() > 0) {
                $this->info("Student {$student->first_name} {$student->last_name}: {$pastSchedules->count()} past sessions");
            }
        }

        $this->info("\n✓ Successfully created {$totalCreated} attendance records");
        $this->info('All students are now marked present for past sessions (unless explicitly marked otherwise by teachers)');

        return 0;
    }
}
