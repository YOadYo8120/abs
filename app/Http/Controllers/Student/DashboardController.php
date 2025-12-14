<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Resource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get the student record
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return Inertia::render('Student/Dashboard', [
                'error' => 'Student record not found',
            ]);
        }

        // Get all schedules for this student that have already occurred (based on academic calendar)
        $pastSchedules = DB::table('schedules')
            ->join('academic_calendar', function($join) {
                $join->on('schedules.year', '=', 'academic_calendar.year')
                     ->on('schedules.semester', '=', 'academic_calendar.semester')
                     ->on('schedules.week_number', '=', 'academic_calendar.week_number');
            })
            ->where('schedules.year', $student->year)
            ->where('schedules.week_number', '>', 0) // Only actual weeks, not template (0)
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
            Attendance::firstOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => 'present',
                    'marked_by' => null, // System auto-generated
                ]
            );
        }

        // Get attendance statistics
        $attendances = Attendance::whereHas('schedule', function ($query) use ($student) {
            $query->where('year', $student->year)
                  ->where('specialization', $student->specialization)
                  ->where(function ($q) use ($student) {
                      $q->whereNull('track')
                        ->orWhere('track', '')
                        ->orWhere('track', $student->track);
                  });
        })
        ->where('student_id', $student->id)
        ->with(['schedule.module', 'schedule.teacher'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Separate attendances by semester
        $s1Attendances = $attendances->filter(fn($a) => $a->schedule->semester == 1);
        $s2Attendances = $attendances->filter(fn($a) => $a->schedule->semester == 2);

        // Calculate overall statistics
        $totalSessions = $attendances->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();

        // Calculate attendance percentage
        $attendancePercentage = $totalSessions > 0
            ? round(($presentCount / $totalSessions) * 100, 1)
            : 100;

        // Calculate S1 statistics
        $s1TotalSessions = $s1Attendances->count();
        $s1AbsentCount = $s1Attendances->where('status', 'absent')->count();
        $s1PresentCount = $s1Attendances->where('status', 'present')->count();
        $s1LateCount = $s1Attendances->where('status', 'late')->count();
        $s1ExcusedCount = $s1Attendances->where('status', 'excused')->count();
        $s1AttendancePercentage = $s1TotalSessions > 0
            ? round(($s1PresentCount / $s1TotalSessions) * 100, 1)
            : 100;

        // Calculate S2 statistics
        $s2TotalSessions = $s2Attendances->count();
        $s2AbsentCount = $s2Attendances->where('status', 'absent')->count();
        $s2PresentCount = $s2Attendances->where('status', 'present')->count();
        $s2LateCount = $s2Attendances->where('status', 'late')->count();
        $s2ExcusedCount = $s2Attendances->where('status', 'excused')->count();
        $s2AttendancePercentage = $s2TotalSessions > 0
            ? round(($s2PresentCount / $s2TotalSessions) * 100, 1)
            : 100;

        // Group by module
        $attendanceByModule = $attendances->groupBy(function ($attendance) {
            return $attendance->schedule->module->code ?? 'Unknown';
        })->map(function ($moduleAttendances) {
            $total = $moduleAttendances->count();
            $absent = $moduleAttendances->where('status', 'absent')->count();
            $present = $moduleAttendances->where('status', 'present')->count();

            return [
                'module_name' => $moduleAttendances->first()->schedule->module->name ?? 'Unknown',
                'module_code' => $moduleAttendances->first()->schedule->module->code ?? 'Unknown',
                'total_sessions' => $total,
                'absent' => $absent,
                'present' => $present,
                'late' => $moduleAttendances->where('status', 'late')->count(),
                'excused' => $moduleAttendances->where('status', 'excused')->count(),
                'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 1) : 100,
            ];
        })->values();

        return Inertia::render('Student/Dashboard', [
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'year' => $student->year,
                'year_name' => $student->year_name,
                'specialization' => $student->specialization,
                'specialization_name' => $student->specialization_name,
                'track' => $student->track,
                'track_name' => $student->track_name,
            ],
            'statistics' => [
                'total_sessions' => $totalSessions,
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'excused' => $excusedCount,
                'attendance_percentage' => $attendancePercentage,
            ],
            's1Statistics' => [
                'total_sessions' => $s1TotalSessions,
                'present' => $s1PresentCount,
                'absent' => $s1AbsentCount,
                'late' => $s1LateCount,
                'excused' => $s1ExcusedCount,
                'attendance_percentage' => $s1AttendancePercentage,
            ],
            's2Statistics' => [
                'total_sessions' => $s2TotalSessions,
                'present' => $s2PresentCount,
                'absent' => $s2AbsentCount,
                'late' => $s2LateCount,
                'excused' => $s2ExcusedCount,
                'attendance_percentage' => $s2AttendancePercentage,
            ],
            'attendanceByModule' => $attendanceByModule,
            'recentAttendances' => $attendances
                ->filter(fn($a) => $a->status === 'absent') // Only show absences
                ->take(10)
                ->map(function ($attendance) {
                    // Get the actual week date from academic calendar
                    $calendar = DB::table('academic_calendar')
                        ->where('year', $attendance->schedule->year)
                        ->where('semester', $attendance->schedule->semester)
                        ->where('week_number', $attendance->schedule->week_number)
                        ->first();

                    // Calculate the actual date based on day of week
                    $weekStartDate = $calendar ? \Carbon\Carbon::parse($calendar->start_date) : now();
                    $dayIndex = array_search($attendance->schedule->day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
                    $actualDate = $weekStartDate->copy()->addDays($dayIndex);

                    return [
                        'id' => $attendance->id,
                        'date' => $actualDate->format('Y-m-d') . ' ' . substr($attendance->schedule->start_time, 0, 5),
                        'status' => $attendance->status,
                        'module_name' => $attendance->schedule->module->name ?? 'Unknown',
                        'module_code' => $attendance->schedule->module->code ?? 'Unknown',
                        'teacher_name' => $attendance->schedule->teacher
                            ? ($attendance->schedule->teacher->name
                                ?? ($attendance->schedule->teacher->first_name . ' ' . $attendance->schedule->teacher->last_name))
                            : 'Unknown',
                    ];
                })
                ->values(),
            'announcements' => Announcement::forStudent($student)->with('user', 'module', 'schedule.module')->take(5)->get()->map(function($announcement) {
                $scopeLabel = match($announcement->scope) {
                    'global' => 'Global Announcement',
                    'module' => 'Module: ' . ($announcement->module->name ?? 'Unknown'),
                    'class' => 'Class Announcement',
                    'session' => 'Session: ' . ($announcement->schedule->module->name ?? 'Unknown'),
                };

                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'scope' => $announcement->scope,
                    'scope_label' => $scopeLabel,
                    'author' => $announcement->user->name
                        ?? ($announcement->user->first_name . ' ' . $announcement->user->last_name)
                        ?: 'Unknown',
                    'published_at' => $announcement->published_at->format('Y-m-d H:i'),
                ];
            }),
            'resources' => Resource::forStudent($student)->with('user', 'module')->take(10)->get()->map(function($resource) {
                $scopeLabel = match($resource->scope) {
                    'module' => 'Module: ' . ($resource->module->name ?? 'Unknown'),
                    'class' => 'Class Resource',
                };

                return [
                    'id' => $resource->id,
                    'title' => $resource->title,
                    'description' => $resource->description,
                    'file_name' => $resource->file_name,
                    'file_size' => round($resource->file_size / 1024, 2), // Convert to KB
                    'scope' => $resource->scope,
                    'scope_label' => $scopeLabel,
                    'author' => $resource->user->name
                        ?? ($resource->user->first_name . ' ' . $resource->user->last_name)
                        ?: 'Unknown',
                    'published_at' => $resource->published_at->format('Y-m-d H:i'),
                ];
            }),
        ]);
    }
}
