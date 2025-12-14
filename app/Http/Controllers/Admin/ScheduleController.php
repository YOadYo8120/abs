<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    /**
     * Display the template week editor or specific week schedule.
     */
    public function index(Request $request)
    {
        $year = (int) $request->input('year', 1);
        $semester = (int) $request->input('semester', 1);
        $weekNumber = (int) $request->input('week', 0); // 0 = template week
        $specialization = $request->input('specialization', null);
        $track = $request->input('track', null);

        // Check if replication has been done
        $replicationCheckQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', '>', 0);

        if ($year >= 3 && $specialization) {
            $replicationCheckQuery->where('specialization', $specialization);
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $replicationCheckQuery->where('track', $track);
            }
        } elseif ($year >= 3 && !$specialization) {
            // For year 3+ without specialization selected, check for NULL specialization schedules
            $replicationCheckQuery->whereNull('specialization');
        } elseif ($year <= 2) {
            $replicationCheckQuery->whereNull('specialization');
        }

        $hasReplicated = $replicationCheckQuery->exists();

        // If replication has been done and user is on template week, redirect to week 1
        if ($hasReplicated && $weekNumber === 0) {
            $weekNumber = 1;
        }

        // If replication hasn't been done and user tries to access a real week, redirect to template
        if (!$hasReplicated && $weekNumber > 0) {
            $weekNumber = 0;
        }

        // If a specific week is requested, check if it exists
        if ($hasReplicated && $weekNumber > 0) {
            $weekExistsQuery = Schedule::where('year', $year)
                ->where('semester', $semester)
                ->where('week_number', $weekNumber);

            if ($year >= 3 && $specialization) {
                $weekExistsQuery->where('specialization', $specialization);
                if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                    $weekExistsQuery->where('track', $track);
                }
            } elseif ($year >= 3 && !$specialization) {
                // For year 3+ without specialization, check for NULL specialization
                $weekExistsQuery->whereNull('specialization');
            } elseif ($year <= 2) {
                $weekExistsQuery->whereNull('specialization');
            }

            // If requested week doesn't exist, default to week 1
            if (!$weekExistsQuery->exists()) {
                $weekNumber = 1;
            }
        }

        // Build query for schedules
        $query = Schedule::with(['module', 'teacher'])
            ->where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', $weekNumber);

        // Add specialization filter for years 3-5
        if ($year >= 3 && $specialization) {
            $query->where('specialization', $specialization);
            // For GI in years 4-5, also filter by track
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $query->where('track', $track);
            }
        } elseif ($year >= 3 && !$specialization) {
            // For years 3-5, if no specialization is selected, don't return any schedules
            $query->whereNull('id'); // Returns empty result
        } elseif ($year <= 2) {
            // For years 1-2, ensure specialization is null
            $query->whereNull('specialization');
        }

        $schedules = $query->get();

        \Log::info('Schedules Query Result', [
            'year' => $year,
            'semester' => $semester,
            'week' => $weekNumber,
            'specialization' => $specialization,
            'track' => $track,
            'schedules_count' => $schedules->count(),
            'schedules' => $schedules->map(fn($s) => [
                'id' => $s->id,
                'day' => $s->day,
                'start_time' => $s->start_time,
                'module_id' => $s->module_id,
            ])->toArray()
        ]);

        // Filter modules by year and specialization
        $modulesQuery = Module::where('year', $year);

        if ($year >= 3 && $specialization) {
            // For years 3-5, only show modules for the selected specialization
            $modulesQuery->where('specialization', $specialization);
            // For GI in years 4-5, also filter by track
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $modulesQuery->where('track', $track);
            }
        } elseif ($year <= 2) {
            // For years 1-2, only show common modules (no specialization)
            $modulesQuery->whereNull('specialization');
        }

        $modules = $modulesQuery->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get(['id', 'name']);

        \Log::info('Schedule Index', [
            'year' => $year,
            'semester' => $semester,
            'week' => $weekNumber,
            'modules_count' => $modules->count(),
            'modules' => $modules->pluck('name', 'id')->toArray()
        ]);

        // Define time slots (8:30 to 18:30, 10 hourly intervals)
        $timeSlots = [];
        for ($hour = 8; $hour <= 17; $hour++) {
            $start = sprintf('%02d:30:00', $hour);
            $end = sprintf('%02d:30:00', $hour + 1);
            $timeSlots[] = [
                'start' => $start,
                'end' => $end,
            ];
        }

        // Days of the week (Monday to Saturday)
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Check if template exists (with specialization filter for years 3-5)
        $templateQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', 0);

        if ($year >= 3 && $specialization) {
            $templateQuery->where('specialization', $specialization);
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $templateQuery->where('track', $track);
            }
        } elseif ($year >= 3 && !$specialization) {
            // For year 3+ without specialization, check for NULL specialization
            $templateQuery->whereNull('specialization');
        } elseif ($year <= 2) {
            $templateQuery->whereNull('specialization');
        }

        $templateExists = $templateQuery->exists();

        // Get all weeks that have been replicated (with specialization filter)
        $replicatedWeeksQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', '>', 0);

        if ($year >= 3 && $specialization) {
            $replicatedWeeksQuery->where('specialization', $specialization);
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $replicatedWeeksQuery->where('track', $track);
            }
        } elseif ($year >= 3 && !$specialization) {
            // For year 3+ without specialization, check for NULL specialization
            $replicatedWeeksQuery->whereNull('specialization');
        } elseif ($year <= 2) {
            $replicatedWeeksQuery->whereNull('specialization');
        }

        $replicatedWeeks = $replicatedWeeksQuery->distinct()
            ->pluck('week_number')
            ->sort()
            ->values();

        // Get S1 last week end date if this is S2
        $s1LastDate = null;
        if ($semester == 2) {
            $s1LastWeek = \DB::table('academic_calendar')
                ->where('year', $year)
                ->where('semester', 1)
                ->orderBy('week_number', 'desc')
                ->first();
            $s1LastDate = $s1LastWeek ? $s1LastWeek->end_date : null;
        }

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

        return Inertia::render('Admin/Schedules/Index', [
            'schedules' => $schedules,
            'modules' => $modules,
            'teachers' => $teachers,
            'timeSlots' => $timeSlots,
            'days' => $days,
            'currentYear' => (int) $year,
            'currentSemester' => (int) $semester,
            'currentWeek' => (int) $weekNumber,
            'currentSpecialization' => $specialization,
            'currentTrack' => $track,
            'templateExists' => $templateExists,
            'replicatedWeeks' => $replicatedWeeks,
            's1LastDate' => $s1LastDate,
            'weekDates' => $weekDates,
        ]);
    }

    /**
     * Update or create a schedule entry.
     */
    public function update(Request $request)
    {
        \Log::info('Schedule Update Request', $request->all());

        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'specialization' => [
                'nullable',
                'required_if:year,3,4,5',
                'in:GI,GRT,GInd,GM,GA,GPM'
            ],
            'track' => 'nullable|string|in:DEV,AI',
            'week_number' => 'required|integer|min:0',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
            'module_id' => 'nullable|exists:modules,id',
            'schedule_type' => 'nullable|in:course,TD,TP,exam',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        \Log::info('Schedule Validated Data', $validated);

        $schedule = Schedule::updateOrCreate(
            [
                'year' => $validated['year'],
                'semester' => $validated['semester'],
                'specialization' => $validated['specialization'] ?? null,
                'track' => $validated['track'] ?? null,
                'week_number' => $validated['week_number'],
                'day' => $validated['day'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
            ],
            [
                'module_id' => $validated['module_id'],
                'schedule_type' => $validated['schedule_type'] ?? null,
                'teacher_id' => $validated['teacher_id'] ?? null,
            ]
        );

        \Log::info('Schedule Created/Updated', ['schedule_id' => $schedule->id]);

        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove a schedule entry.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'specialization' => 'nullable|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
            'week_number' => 'required|integer|min:0',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $query = Schedule::where([
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'week_number' => $validated['week_number'],
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        if (isset($validated['specialization'])) {
            $query->where('specialization', $validated['specialization']);
        } else {
            $query->whereNull('specialization');
        }

        if (isset($validated['track'])) {
            $query->where('track', $validated['track']);
        } else {
            $query->whereNull('track');
        }

        $query->delete();

        return redirect()->back()->with('success', 'Schedule entry removed.');
    }

    /**
     * Replicate template week to all weeks of the semester.
     */
    public function replicate(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'specialization' => 'nullable|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
            'weeks_count' => 'required|integer|min:1|max:30',
            'start_date' => 'required|date',
        ]);

        $year = $validated['year'];
        $semester = $validated['semester'];
        $specialization = $validated['specialization'] ?? null;
        $track = $validated['track'] ?? null;
        $weeksCount = $validated['weeks_count'];
        $startDate = new \DateTime($validated['start_date']);

        // Validate that S2 start date is after S1 end date
        if ($semester == 2) {
            // Get S1 last week end date
            $s1LastWeek = \DB::table('academic_calendar')
                ->where('year', $year)
                ->where('semester', 1)
                ->orderBy('week_number', 'desc')
                ->first();

            if ($s1LastWeek) {
                $s1EndDate = new \DateTime($s1LastWeek->end_date);
                // S2 must start at least 1 day after S1 ends
                if ($startDate <= $s1EndDate) {
                    return redirect()->back()->with('error',
                        'Semester 2 start date (' . $startDate->format('Y-m-d') . ') must be after Semester 1 end date (' . $s1LastWeek->end_date . '). ' .
                        'For academic year ' . $year . ', S2 should start on or after ' . $s1EndDate->modify('+1 day')->format('Y-m-d') . ' (typically February 2, 2026).'
                    );
                }
            } else {
                // No S1 calendar found - this is suspicious but we'll allow it
                \Log::warning("Attempting to replicate S2 for year {$year} but no S1 calendar found");
            }
        }

        // Get template week (week_number = 0) with specialization filter
        $templateQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', 0);

        if ($specialization) {
            $templateQuery->where('specialization', $specialization);
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $templateQuery->where('track', $track);
            }
        } else {
            $templateQuery->whereNull('specialization');
        }

        $templateSchedules = $templateQuery->get();

        if ($templateSchedules->isEmpty()) {
            return redirect()->back()->with('error', 'No template week found. Please create a template first.');
        }

        // Delete existing replicated weeks for this specialization
        $deleteQuery = Schedule::where('year', $year)
            ->where('semester', $semester)
            ->where('week_number', '>', 0);

        if ($specialization) {
            $deleteQuery->where('specialization', $specialization);
            if ($specialization === 'GI' && $year >= 4 && $year <= 5 && $track) {
                $deleteQuery->where('track', $track);
            }
        } else {
            $deleteQuery->whereNull('specialization');
        }

        $deleteQuery->delete();

        // Replicate to each week
        foreach (range(1, $weeksCount) as $weekNumber) {
            foreach ($templateSchedules as $template) {
                Schedule::create([
                    'year' => $year,
                    'semester' => $semester,
                    'specialization' => $template->specialization,
                    'track' => $template->track,
                    'week_number' => $weekNumber,
                    'day' => $template->day,
                    'start_time' => $template->start_time,
                    'end_time' => $template->end_time,
                    'module_id' => $template->module_id,
                    'schedule_type' => $template->schedule_type,
                    'teacher_id' => $template->teacher_id,
                ]);
            }
        }

        // Now create/update academic calendar entries (after successful replication)
        // Delete existing calendar entries for this semester
        \DB::table('academic_calendar')
            ->where('year', $year)
            ->where('semester', $semester)
            ->delete();

        // Create new calendar entries
        $currentDate = clone $startDate;
        for ($weekNumber = 1; $weekNumber <= $weeksCount; $weekNumber++) {
            $weekStartDate = clone $currentDate;
            $weekEndDate = clone $currentDate;
            $weekEndDate->modify('+6 days'); // End on Sunday

            \DB::table('academic_calendar')->insert([
                'year' => $year,
                'semester' => $semester,
                'week_number' => $weekNumber,
                'start_date' => $weekStartDate->format('Y-m-d'),
                'end_date' => $weekEndDate->format('Y-m-d'),
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $currentDate->modify('+7 days'); // Move to next week
        }

        return redirect()->back()->with('success', "Template replicated to $weeksCount weeks successfully. Academic calendar updated with start date: " . $startDate->format('Y-m-d'));
    }

    /**
     * Clear schedules for a specific week.
     */
    public function clearWeek(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'week_number' => 'required|integer|min:0',
            'specialization' => 'nullable|string|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
        ]);

        $specialization = $validated['specialization'] ?? null;
        $track = $validated['track'] ?? null;

        // Instead of deleting, just set module_id, schedule_type and teacher_id to null
        $query = Schedule::where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->where('week_number', $validated['week_number']);

        // Filter by specialization for years 3-5, or null for years 1-2
        if ($validated['year'] >= 3 && $specialization) {
            $query->where('specialization', $specialization);
            if ($specialization === 'GI' && $validated['year'] >= 4 && $validated['year'] <= 5 && $track) {
                $query->where('track', $track);
            }
        } elseif ($validated['year'] <= 2) {
            $query->whereNull('specialization');
        }

        $query->update([
            'module_id' => null,
            'schedule_type' => null,
            'teacher_id' => null,
        ]);

        $weekLabel = $validated['week_number'] === 0 ? 'Template week' : 'Week ' . $validated['week_number'];
        return redirect()->back()->with('success', "$weekLabel cleared successfully.");
    }

    /**
     * Clear all schedules for a semester (including template and all weeks).
     */
    public function clearSemester(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|between:1,5',
            'semester' => 'required|integer|between:1,2',
            'specialization' => 'nullable|string|in:GI,GRT,GInd,GM,GA,GPM',
            'track' => 'nullable|string|in:DEV,AI',
        ]);

        $specialization = $validated['specialization'] ?? null;
        $track = $validated['track'] ?? null;

        $query = Schedule::where('year', $validated['year'])
            ->where('semester', $validated['semester']);

        // Filter by specialization for years 3-5, or null for years 1-2
        if ($validated['year'] >= 3 && $specialization) {
            $query->where('specialization', $specialization);
            if ($specialization === 'GI' && $validated['year'] >= 4 && $validated['year'] <= 5 && $track) {
                $query->where('track', $track);
            }
        } elseif ($validated['year'] <= 2) {
            $query->whereNull('specialization');
        }

        $query->delete();

        return redirect()->back()->with('success', 'All schedules for this semester cleared successfully.');
    }
}
