<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Justification;
use App\Models\JustificationFile;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;

class JustificationController extends Controller
{
    /**
     * Display student's absences that can be justified
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->withErrors('Student record not found');
        }

        // Get all absences for this student with justification status
        $absences = Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->with(['schedule.module', 'justification.files'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($attendance) {
                // Get the actual week date from academic calendar
                $calendar = DB::table('academic_calendar')
                    ->where('year', $attendance->schedule->year)
                    ->where('semester', $attendance->schedule->semester)
                    ->where('week_number', $attendance->schedule->week_number)
                    ->first();

                // Calculate the actual date based on day of week
                $weekStartDate = $calendar ? Carbon::parse($calendar->start_date) : now();
                $dayIndex = array_search($attendance->schedule->day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
                $actualDate = $weekStartDate->copy()->addDays($dayIndex);

                return [
                    'id' => $attendance->id,
                    'date' => $actualDate->format('Y-m-d') . ' ' . substr($attendance->schedule->start_time, 0, 5),
                    'module' => $attendance->schedule->module->name ?? 'Unknown',
                    'module_code' => $attendance->schedule->module->code ?? 'N/A',
                    'has_justification' => $attendance->justification !== null,
                    'justification_status' => $attendance->justification->status ?? null,
                    'justification_id' => $attendance->justification->id ?? null,
                ];
            });

        return Inertia::render('Student/Justifications/Index', [
            'absences' => $absences,
        ]);
    }

    /**
     * Show form to create justification
     */
    public function create(Request $request, $attendanceId)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->withErrors('Student record not found');
        }

        $attendance = Attendance::where('id', $attendanceId)
            ->where('student_id', $student->id)
            ->where('status', 'absent')
            ->with('schedule.module')
            ->first();

        if (!$attendance) {
            return redirect()->route('student.justifications.index')->withErrors('Attendance record not found');
        }

        // Check if already has justification (but not rejected)
        if ($attendance->justification && $attendance->justification->status !== 'rejected') {
            return redirect()->route('student.justifications.index')
                ->withErrors('This absence already has a justification');
        }

        // Get the actual week date from academic calendar
        $calendar = DB::table('academic_calendar')
            ->where('year', $attendance->schedule->year)
            ->where('semester', $attendance->schedule->semester)
            ->where('week_number', $attendance->schedule->week_number)
            ->first();

        // Calculate the actual date based on day of week
        $weekStartDate = $calendar ? Carbon::parse($calendar->start_date) : now();
        $dayIndex = array_search($attendance->schedule->day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
        $actualDate = $weekStartDate->copy()->addDays($dayIndex);

        return Inertia::render('Student/Justifications/Create', [
            'attendance' => [
                'id' => $attendance->id,
                'date' => $actualDate->format('Y-m-d') . ' ' . substr($attendance->schedule->start_time, 0, 5),
                'module' => $attendance->schedule->module->name ?? 'Unknown',
                'module_code' => $attendance->schedule->module->code ?? 'N/A',
            ],
        ]);
    }

    /**
     * Show form to edit justification (only for rejected ones)
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->withErrors('Student record not found');
        }

        $justification = Justification::where('id', $id)
            ->where('student_id', $student->id)
            ->where('status', 'rejected')
            ->with(['attendance.schedule.module', 'files'])
            ->first();

        if (!$justification) {
            return redirect()->route('student.justifications.index')
                ->withErrors('Justification not found or cannot be edited');
        }

        // Get the actual week date from academic calendar
        $calendar = DB::table('academic_calendar')
            ->where('year', $justification->attendance->schedule->year)
            ->where('semester', $justification->attendance->schedule->semester)
            ->where('week_number', $justification->attendance->schedule->week_number)
            ->first();

        // Calculate the actual date based on day of week
        $weekStartDate = $calendar ? Carbon::parse($calendar->start_date) : now();
        $dayIndex = array_search($justification->attendance->schedule->day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
        $actualDate = $weekStartDate->copy()->addDays($dayIndex);

        return Inertia::render('Student/Justifications/Edit', [
            'justification' => [
                'id' => $justification->id,
                'description' => $justification->description,
                'admin_notes' => $justification->admin_notes,
                'files' => $justification->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'file_name' => $file->file_name,
                        'file_type' => $file->file_type,
                        'file_size' => round($file->file_size / 1024, 2),
                    ];
                }),
            ],
            'attendance' => [
                'id' => $justification->attendance->id,
                'date' => $actualDate->format('Y-m-d') . ' ' . substr($justification->attendance->schedule->start_time, 0, 5),
                'module' => $justification->attendance->schedule->module->name ?? 'Unknown',
                'module_code' => $justification->attendance->schedule->module->code ?? 'N/A',
            ],
        ]);
    }

    /**
     * Update justification (only for rejected ones)
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return back()->withErrors('Student record not found');
        }

        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'delete_files' => 'nullable|array',
            'delete_files.*' => 'integer|exists:justification_files,id',
        ]);

        $justification = Justification::where('id', $id)
            ->where('student_id', $student->id)
            ->where('status', 'rejected')
            ->first();

        if (!$justification) {
            return back()->withErrors('Justification not found or cannot be edited');
        }

        // Update description
        $justification->update([
            'description' => $validated['description'],
            'status' => 'pending', // Reset to pending after edit
            'admin_notes' => null,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ]);

        // Delete selected files
        if (!empty($validated['delete_files'])) {
            $filesToDelete = JustificationFile::where('justification_id', $justification->id)
                ->whereIn('id', $validated['delete_files'])
                ->get();

            foreach ($filesToDelete as $file) {
                Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }
        }

        // Upload new files if provided
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('justifications', 'public');

                JustificationFile::create([
                    'justification_id' => $justification->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('student.justifications.index')
            ->with('success', 'Justification updated and resubmitted for review');
    }

    /**
     * Store justification
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return back()->withErrors('Student record not found');
        }

        $validated = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'description' => 'required|string|max:1000',
            'files' => 'required|array|min:1|max:5',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB per file
        ]);

        // Verify attendance belongs to student and is absent
        $attendance = Attendance::where('id', $validated['attendance_id'])
            ->where('student_id', $student->id)
            ->where('status', 'absent')
            ->first();

        if (!$attendance) {
            return back()->withErrors(['attendance_id' => 'Invalid attendance record']);
        }

        // Check if already has justification
        if ($attendance->justification) {
            return back()->withErrors(['attendance_id' => 'This absence already has a justification']);
        }

        // Create justification
        $justification = Justification::create([
            'student_id' => $student->id,
            'attendance_id' => $validated['attendance_id'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        // Upload and store files
        foreach ($request->file('files') as $file) {
            $path = $file->store('justifications', 'public');

            JustificationFile::create([
                'justification_id' => $justification->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ]);
        }

        return redirect()->route('student.justifications.index')
            ->with('success', 'Justification submitted successfully');
    }

    /**
     * Display specific justification details
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('student.dashboard')->withErrors('Student record not found');
        }

        $justification = Justification::where('id', $id)
            ->where('student_id', $student->id)
            ->with(['attendance.schedule.module', 'files', 'reviewer'])
            ->first();

        if (!$justification) {
            return redirect()->route('student.justifications.index')->withErrors('Justification not found');
        }

        return Inertia::render('Student/Justifications/Show', [
            'justification' => [
                'id' => $justification->id,
                'description' => $justification->description,
                'status' => $justification->status,
                'admin_notes' => $justification->admin_notes,
                'reviewed_at' => $justification->reviewed_at?->format('Y-m-d H:i'),
                'reviewed_by' => $justification->reviewer ?
                    ($justification->reviewer->name ?? $justification->reviewer->first_name . ' ' . $justification->reviewer->last_name) : null,
                'created_at' => $justification->created_at->format('Y-m-d H:i'),
                'attendance' => [
                    'date' => $justification->attendance->created_at->format('Y-m-d H:i'),
                    'module' => $justification->attendance->schedule->module->name ?? 'Unknown',
                    'module_code' => $justification->attendance->schedule->module->code ?? 'N/A',
                ],
                'files' => $justification->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'file_name' => $file->file_name,
                        'file_type' => $file->file_type,
                        'file_size' => round($file->file_size / 1024, 2), // KB
                        'download_url' => route('student.justifications.download', $file->id),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Download justification file
     */
    public function download($fileId)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            abort(403, 'Unauthorized');
        }

        $file = JustificationFile::whereHas('justification', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })->findOrFail($fileId);

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }
}

