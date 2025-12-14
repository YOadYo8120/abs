<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Module;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of teacher's announcements
     */
    public function index(Request $request)
    {
        $teacher = $request->user();

        $announcements = Announcement::where('user_id', $teacher->id)
            ->with(['module', 'schedule.module'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Teacher/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create(Request $request)
    {
        $teacher = $request->user();

        // Get teacher's modules
        $modules = Module::whereHas('schedules', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->distinct()->get();

        // Get teacher's classes (unique combinations of year, specialization, track)
        $classes = Schedule::where('teacher_id', $teacher->id)
            ->select('year', 'specialization', 'track', 'semester')
            ->distinct()
            ->orderBy('year')
            ->orderBy('semester')
            ->orderBy('specialization')
            ->orderBy('track')
            ->get()
            ->map(function($schedule) {
                $className = "Year {$schedule->year}";
                if ($schedule->specialization) {
                    $className .= " - {$schedule->specialization}";
                }
                if ($schedule->track) {
                    $className .= " ({$schedule->track})";
                }
                $className .= " - Semester {$schedule->semester}";

                return [
                    'year' => $schedule->year,
                    'specialization' => $schedule->specialization,
                    'track' => $schedule->track,
                    'semester' => $schedule->semester,
                    'name' => $className,
                ];
            });

        return Inertia::render('Teacher/Announcements/Create', [
            'modules' => $modules,
            'classes' => $classes,
        ]);
    }

    /**
     * Get teacher's schedules for session announcements
     */
    public function getSchedules(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'year' => 'required|integer|min:1|max:5',
            'semester' => 'required|integer|in:1,2',
            'specialization' => 'nullable|string',
            'track' => 'nullable|string',
            'week_number' => 'required|integer|min:1',
        ]);

        $query = Schedule::with(['module'])
            ->where('teacher_id', $teacher->id)
            ->where('year', $validated['year'])
            ->where('semester', $validated['semester'])
            ->where('week_number', $validated['week_number']);

        if ($validated['year'] >= 3 && isset($validated['specialization'])) {
            $query->where('specialization', $validated['specialization']);

            if ($validated['year'] >= 4 && $validated['specialization'] === 'GI' && isset($validated['track'])) {
                $query->where('track', $validated['track']);
            }
        }

        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->map(function($schedule) {
                return [
                    'id' => $schedule->id,
                    'module_name' => $schedule->module->name ?? 'Unknown',
                    'module_code' => $schedule->module->code ?? 'Unknown',
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'room' => $schedule->room,
                ];
            });

        return response()->json($schedules);
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'scope' => 'required|in:module,class',
            'module_id' => 'required_if:scope,module|nullable|exists:modules,id',
            'year' => 'required_if:scope,class|nullable|integer|min:1|max:5',
            'specialization' => 'nullable|string',
            'track' => 'nullable|string',
            'semester' => 'required_if:scope,class|nullable|integer|in:1,2',
            'publish_now' => 'boolean',
        ]);

        // Verify teacher owns the module/class
        if ($validated['scope'] === 'module') {
            $hasModule = Schedule::where('teacher_id', $teacher->id)
                ->where('module_id', $validated['module_id'])
                ->exists();

            if (!$hasModule) {
                return back()->withErrors(['module_id' => 'You do not teach this module.']);
            }
        } elseif ($validated['scope'] === 'class') {
            $hasClass = Schedule::where('teacher_id', $teacher->id)
                ->where('year', $validated['year'])
                ->where('semester', $validated['semester'])
                ->where(function($query) use ($validated) {
                    if (isset($validated['specialization'])) {
                        $query->where('specialization', $validated['specialization']);
                    }
                    if (isset($validated['track'])) {
                        $query->where('track', $validated['track']);
                    }
                })
                ->exists();

            if (!$hasClass) {
                return back()->withErrors(['year' => 'You do not teach this class.']);
            }
        }

        $announcement = Announcement::create([
            'user_id' => $teacher->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'scope' => $validated['scope'],
            'module_id' => $validated['module_id'] ?? null,
            'year' => $validated['year'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'track' => $validated['track'] ?? null,
            'semester' => $validated['semester'] ?? null,
            'schedule_id' => null,
            'published_at' => $validated['publish_now'] ?? true ? now() : null,
        ]);

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement created successfully');
    }

    /**
     * Show the form for editing an announcement
     */
    public function edit(Request $request, Announcement $announcement)
    {
        $teacher = $request->user();

        // Verify teacher owns this announcement
        if ($announcement->user_id !== $teacher->id) {
            abort(403, 'You do not own this announcement.');
        }

        // Get teacher's modules
        $modules = Module::whereHas('schedules', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->distinct()->get();

        // Get teacher's classes
        $classes = Schedule::where('teacher_id', $teacher->id)
            ->select('year', 'specialization', 'track', 'semester')
            ->distinct()
            ->get()
            ->map(function($schedule) {
                $className = "Year {$schedule->year}";
                if ($schedule->specialization) {
                    $className .= " - {$schedule->specialization}";
                }
                if ($schedule->track) {
                    $className .= " ({$schedule->track})";
                }
                $className .= " - Semester {$schedule->semester}";

                return [
                    'year' => $schedule->year,
                    'specialization' => $schedule->specialization,
                    'track' => $schedule->track,
                    'semester' => $schedule->semester,
                    'name' => $className,
                ];
            });

        return Inertia::render('Teacher/Announcements/Edit', [
            'announcement' => $announcement->load(['module', 'schedule']),
            'modules' => $modules,
            'classes' => $classes,
        ]);
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $teacher = $request->user();

        // Verify teacher owns this announcement
        if ($announcement->user_id !== $teacher->id) {
            abort(403, 'You do not own this announcement.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        $announcement->update($validated);

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement updated successfully');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Request $request, Announcement $announcement)
    {
        $teacher = $request->user();

        // Verify teacher owns this announcement
        if ($announcement->user_id !== $teacher->id) {
            abort(403, 'You do not own this announcement.');
        }

        $announcement->delete();

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement deleted successfully');
    }

    /**
     * Publish an announcement
     */
    public function publish(Request $request, Announcement $announcement)
    {
        $teacher = $request->user();

        // Verify teacher owns this announcement
        if ($announcement->user_id !== $teacher->id) {
            abort(403, 'You do not own this announcement.');
        }

        $announcement->update([
            'published_at' => now(),
        ]);

        return back()->with('success', 'Announcement published successfully');
    }
}

