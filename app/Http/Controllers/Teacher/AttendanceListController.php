<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Inertia\Inertia;

class AttendanceListController extends Controller
{
    /**
     * Show the form to generate attendance list
     */
    public function index(Request $request)
    {
        $teacher = $request->user();
        $year = (int) $request->input('year', 1);
        $semester = (int) $request->input('semester', 1);
        $specialization = $request->input('specialization', null);
        $track = $request->input('track', null);

        // Get weeks from academic calendar
        $weeks = DB::table('academic_calendar')
            ->where('year', $year)
            ->where('semester', $semester)
            ->orderBy('week_number')
            ->get()
            ->map(function($week) {
                return [
                    'number' => $week->week_number,
                    'start_date' => $week->start_date,
                    'end_date' => $week->end_date,
                ];
            });

        return Inertia::render('Teacher/AttendanceList/Index', [
            'weeks' => $weeks,
            'currentYear' => $year,
            'currentSemester' => $semester,
            'currentSpecialization' => $specialization,
            'currentTrack' => $track,
        ]);
    }

    /**
     * Generate PDF attendance list
     */
    public function generate(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'week_number' => 'required|integer|min:1',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'duration' => 'required|in:1h,2h',
            'start_time' => 'required|date_format:H:i',
            'specialization' => 'nullable|string|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
        ]);

        // Calculate end time based on duration
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = $validated['duration'] === '1h'
            ? $startTime->copy()->addHour()
            : $startTime->copy()->addHours(2);
        $validated['end_time'] = $endTime->format('H:i');

        // Get the actual date from academic calendar
        $calendar = DB::table('academic_calendar')
            ->where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->where('week_number', $validated['week_number'])
            ->first();

        if (!$calendar) {
            return redirect()->route('teacher.attendance-list.index', [
                'year' => $validated['year'],
                'semester' => $validated['semester'],
                'specialization' => $validated['specialization'] ?? null,
                'track' => $validated['track'] ?? null,
            ])->with('error', 'Academic calendar not found for this week. Please contact the administrator.');
        }

        $dayIndex = array_search($validated['day'], ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
        $sessionDate = Carbon::parse($calendar->start_date)->addDays($dayIndex);

        // Get all students for this class
        $studentsQuery = Student::where('year', $validated['year']);

        if ($validated['year'] >= 3 && $validated['specialization']) {
            $studentsQuery->where('specialization', $validated['specialization']);
            if ($validated['specialization'] === 'GI' && $validated['year'] >= 4 && $validated['track']) {
                $studentsQuery->where('track', $validated['track']);
            }
        } elseif ($validated['year'] <= 2) {
            $studentsQuery->whereNull('specialization');
        }

        $students = $studentsQuery
            ->with('user')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('students.*')
            ->get();

        // Build class name
        $className = 'Year ' . $validated['year'];
        if ($validated['specialization']) {
            $className .= ' - ' . $validated['specialization'];
            if ($validated['track']) {
                $className .= ' (' . $validated['track'] . ')';
            }
        }

        // Prepare data for PDF
        $data = [
            'sessionDate' => $sessionDate,
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'week_number' => $validated['week_number'],
            'day' => $validated['day'],
            'duration' => $validated['duration'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'students' => $students,
            'className' => $className,
            'specialization' => $validated['specialization'],
            'track' => $validated['track'],
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('pdf.attendance-list', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = sprintf(
            'attendance_Year%s_week%d_%s_%s_%s.pdf',
            $validated['year'],
            $validated['week_number'],
            $validated['day'],
            $validated['duration'],
            $sessionDate->format('Y-m-d')
        );

        return $pdf->download($filename);
    }
}
