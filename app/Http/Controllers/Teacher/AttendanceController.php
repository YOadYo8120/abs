<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display attendance management interface for a specific schedule
     */
    public function index(Request $request)
    {
        $teacher = $request->user();

        // If no schedule_id provided, show the selection page
        if (!$request->has('schedule_id')) {
            return Inertia::render('Teacher/Attendance/Index', [
                'schedule' => null,
                'students' => [],
                'attendances' => [],
            ]);
        }

        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        $schedule = Schedule::with(['module', 'teacher'])
            ->findOrFail($validated['schedule_id']);

        // Check if teacher is assigned to this schedule
        if ($schedule->teacher_id !== $teacher->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You can only mark attendance for your own sessions.'], 403);
            }
            return back()->withErrors(['error' => 'You can only mark attendance for your own sessions.']);
        }

        // Get students for this class
        $students = $this->getStudentsForSchedule($schedule);

        // Auto-create present attendance for all students who don't have a record yet
        foreach ($students as $student) {
            Attendance::firstOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => 'present',
                    'marked_by' => $teacher->id,
                ]
            );
        }

        // Get existing attendance records - convert to associative array keyed by student_id
        $attendanceRecords = Attendance::where('schedule_id', $schedule->id)
            ->with('student')
            ->get();

        $attendances = [];
        foreach ($attendanceRecords as $record) {
            $attendances[$record->student_id] = $record;
        }

        // If AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'props' => [
                    'schedule' => $schedule,
                    'students' => $students,
                    'attendances' => $attendances,
                ]
            ]);
        }        return Inertia::render('Teacher/Attendance/Index', [
            'schedule' => $schedule,
            'students' => $students,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Update or create attendance records
     */
    public function update(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);

        // Verify teacher is assigned to this schedule
        if ($schedule->teacher_id !== $teacher->id) {
            abort(403, 'You can only mark attendance for your own sessions.');
        }

        // Verify student belongs to this class
        $student = Student::findOrFail($validated['student_id']);
        if (!$this->studentBelongsToSchedule($student, $schedule)) {
            abort(400, 'Student does not belong to this class.');
        }

        // Update or create attendance
        $attendance = Attendance::updateOrCreate(
            [
                'schedule_id' => $validated['schedule_id'],
                'student_id' => $validated['student_id'],
            ],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'marked_by' => $teacher->id,
            ]
        );

        // Return back with success message for Inertia
        return back();
    }

    /**
     * Get teacher's schedules for attendance marking
     */
    public function schedules(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'year' => 'required|integer|min:1|max:5',
            'semester' => 'required|integer|in:1,2',
            'specialization' => 'nullable|in:GI,GC,GE,GM',
            'track' => 'nullable|in:DEV,AI',
            'week_number' => 'required|integer|min:1|max:20',
        ]);

        $query = Schedule::with(['module', 'teacher'])
            ->where('teacher_id', $teacher->id)
            ->where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->where('week_number', $validated['week_number']);

        // Handle specialization and track
        if ($validated['year'] >= 3) {
            $query->where('specialization', $validated['specialization']);

            if ($validated['year'] >= 4 && $validated['specialization'] === 'GI') {
                $query->where('track', $validated['track']);
            }
        }

        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return response()->json($schedules);
    }

    /**
     * Get students for a specific schedule based on year, specialization, and track
     */
    private function getStudentsForSchedule(Schedule $schedule)
    {
        $query = Student::query()
            ->where('year', $schedule->year)
            ->orderBy('last_name')
            ->orderBy('first_name');

        // For years 1 and 2: no specialization filtering (students have null specialization)
        if ($schedule->year <= 2) {
            $query->whereNull('specialization');
        }
        // For years 3-5: filter by specialization
        else if ($schedule->year >= 3 && !empty($schedule->specialization)) {
            $query->where('specialization', $schedule->specialization);

            // For GI years 4 and 5: filter by track
            if ($schedule->year >= 4 && $schedule->specialization === 'GI' && !empty($schedule->track)) {
                $query->where('track', $schedule->track);
            }
        }

        return $query->get();
    }

    /**
     * Check if student belongs to the schedule's class
     */
    private function studentBelongsToSchedule(Student $student, Schedule $schedule): bool
    {
        // Check year
        if ($student->year !== $schedule->year) {
            return false;
        }

        // For years 1 and 2: only year matters
        if ($schedule->year <= 2) {
            return $student->specialization === null;
        }

        // For years 3-5: check specialization
        if ($student->specialization !== $schedule->specialization) {
            return false;
        }

        // For GI years 4-5: check track
        if ($schedule->year >= 4 && $schedule->specialization === 'GI') {
            return $student->track === $schedule->track;
        }

        return true;
    }
}
