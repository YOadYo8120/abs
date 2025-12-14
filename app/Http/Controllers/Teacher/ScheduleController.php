<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * Display schedules for teacher's modules
     */
    public function index(Request $request)
    {
        $teacher = auth()->user();
        $year = (int) $request->input('year', 1);
        $semester = (int) $request->input('semester', 1);
        $weekNumber = (int) $request->input('week', 0);
        $specialization = $request->input('specialization', null);
        $track = $request->input('track', null);

        // Get only teacher's OWN modules (for adding new schedules)
        $modulesQuery = Module::with('teacher')
            ->where('year', $year)
            ->where('teacher_id', $teacher->id);

        if ($year >= 3 && $specialization) {
            $modulesQuery->where('specialization', $specialization);
            // For GI in years 4-5, also filter by track
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $modulesQuery->where('track', $track);
            }
        } elseif ($year <= 2) {
            $modulesQuery->whereNull('specialization');
        }

        $modules = $modulesQuery->get();

        // Get all teachers
        $teachers = User::where('role', 'teacher')->get(['id', 'name']);

        // Build query for schedules
        $query = Schedule::with(['module', 'teacher'])
            ->where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', $weekNumber);

        if ($year >= 3 && $specialization) {
            $query->where('specialization', $specialization);
            // For GI in years 4-5, also filter by track
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $query->where('track', $track);
            }
        } elseif ($year >= 3 && !$specialization) {
            $query->whereNull('id');
        } elseif ($year <= 2) {
            $query->whereNull('specialization');
        }

        $schedules = $query->get();

        // Time slots
        $timeSlots = [];
        for ($hour = 8; $hour <= 17; $hour++) {
            $start = sprintf('%02d:30:00', $hour);
            $end = sprintf('%02d:30:00', $hour + 1);
            $timeSlots[] = ['start' => $start, 'end' => $end];
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Check template exists
        $templateQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', 0);

        if ($year >= 3 && $specialization) {
            $templateQuery->where('specialization', $specialization);
        } elseif ($year <= 2) {
            $templateQuery->whereNull('specialization');
        }

        $templateExists = $templateQuery->exists();

        // Get replicated weeks
        $replicatedWeeksQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', '>', 0);

        if ($year >= 3 && $specialization) {
            $replicatedWeeksQuery->where('specialization', $specialization);
        } elseif ($year <= 2) {
            $replicatedWeeksQuery->whereNull('specialization');
        }

        $replicatedWeeks = $replicatedWeeksQuery->distinct()
            ->pluck('week_number')
            ->sort()
            ->values()
            ->toArray();

        // Get week dates from academic calendar
        $weekDates = null;
        if ($weekNumber > 0) {
            $calendar = \DB::table('academic_calendar')
                ->where('year', $year)
                ->where('semester', $semester)
                ->where('week_number', $weekNumber)
                ->first();

            if ($calendar) {
                $weekDates = [
                    'start' => $calendar->start_date,
                    'end' => $calendar->end_date,
                ];
            }
        }

        return Inertia::render('Teacher/Schedules/Index', [
            'schedules' => $schedules,
            'modules' => $modules,
            'teachers' => $teachers,
            'timeSlots' => $timeSlots,
            'days' => $days,
            'currentYear' => $year,
            'currentSemester' => $semester,
            'currentWeek' => $weekNumber,
            'currentSpecialization' => $specialization,
            'currentTrack' => $track,
            'templateExists' => $templateExists,
            'replicatedWeeks' => $replicatedWeeks,
            'teacherId' => $teacher->id,
            'weekDates' => $weekDates,
        ]);
    }

    /**
     * Update schedule
     * Teachers can:
     * 1. Add/edit schedules for their own modules (module owner)
     * 2. Edit/move schedules assigned to them by other teachers
     */
    public function update(Request $request)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'week_number' => 'required|integer|min:0',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
            'module_id' => 'nullable|exists:modules,id',
            'schedule_type' => 'nullable|in:course,TD,TP,exam',
            'teacher_id' => 'nullable|exists:users,id',
            'specialization' => 'nullable|string|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
        ]);

        $specialization = $validated['specialization'] ?? null;
        $track = $validated['track'] ?? null;

        // Check if editing an existing schedule
        $existingSchedule = Schedule::where([
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'week_number' => $validated['week_number'],
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'specialization' => $specialization,
            'track' => $track,
        ])->first();

        if ($existingSchedule) {
            // Editing existing schedule
            // Teacher can edit if:
            // 1. They own the module, OR
            // 2. They are assigned as the teacher in this schedule
            $module = Module::find($existingSchedule->module_id);
            $ownsModule = $module && $module->teacher_id === $teacher->id;
            $assignedToTeacher = $existingSchedule->teacher_id === $teacher->id;

            if (!$ownsModule && !$assignedToTeacher) {
                return back()->withErrors(['error' => 'You can only edit schedules for your modules or schedules assigned to you.']);
            }

            // If teacher owns the module, they can change anything
            // If teacher is assigned to schedule but doesn't own module, they can only move it (change time/day)
            if (!$ownsModule && $assignedToTeacher) {
                // Can only change time/day, not module or assigned teacher
                if ($validated['module_id'] != $existingSchedule->module_id) {
                    return back()->withErrors(['error' => 'You cannot change the module for schedules assigned to you.']);
                }
            }
        } else {
            // Adding new schedule
            // Teacher can only add schedules for their own modules
            if ($validated['module_id']) {
                $module = Module::find($validated['module_id']);
                if (!$module || $module->teacher_id !== $teacher->id) {
                    return back()->withErrors(['module_id' => 'You can only create schedules for your own modules.']);
                }
            }
        }

        Schedule::updateOrCreate(
            [
                'year' => $validated['year'],
                'semester' => $validated['semester'],
                'week_number' => $validated['week_number'],
                'day' => $validated['day'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'specialization' => $specialization,
                'track' => $track,
            ],
            [
                'module_id' => $validated['module_id'],
                'schedule_type' => $validated['schedule_type'],
                'teacher_id' => $validated['teacher_id'],
            ]
        );

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove schedule
     * Teachers can delete if:
     * 1. They own the module, OR
     * 2. They are assigned as the teacher in the schedule
     */
    public function destroy(Request $request)
    {
        $teacher = auth()->user();

        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'week_number' => 'required|integer|min:0',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
            'specialization' => 'nullable|string|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
        ]);

        $specialization = $validated['specialization'] ?? null;
        $track = $validated['track'] ?? null;

        $query = Schedule::where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->where('week_number', $validated['week_number'])
            ->where('day', $validated['day'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time']);

        if ($validated['year'] >= 3 && $specialization) {
            $query->where('specialization', $specialization);
            // For GI in years 4-5, also filter by track
            if ($specialization === 'GI' && $validated['year'] >= 4 && $validated['year'] <= 5 && $track) {
                $query->where('track', $track);
            }
        } elseif ($validated['year'] <= 2) {
            $query->whereNull('specialization');
        }

        $schedule = $query->first();

        if (!$schedule) {
            return back()->withErrors(['error' => 'Schedule not found.']);
        }

        // Check if teacher can delete
        $module = Module::find($schedule->module_id);
        $ownsModule = $module && $module->teacher_id === $teacher->id;
        $assignedToTeacher = $schedule->teacher_id === $teacher->id;

        if (!$ownsModule && !$assignedToTeacher) {
            return back()->withErrors(['error' => 'You can only delete schedules for your modules or schedules assigned to you.']);
        }

        $schedule->delete();

        return redirect()->back()->with('success', 'Schedule removed successfully.');
    }
}
