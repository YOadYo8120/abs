<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Module;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ResourceController extends Controller
{
    /**
     * Display a listing of teacher's resources
     */
    public function index(Request $request)
    {
        $teacher = $request->user();

        $resources = Resource::where('user_id', $teacher->id)
            ->with(['module'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Teacher/Resources/Index', [
            'resources' => $resources,
        ]);
    }

    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request)
    {
        $teacher = $request->user();

        // Get teacher's modules
        $modules = Module::whereHas('schedules', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->distinct()->get();

        // Get teacher's classes
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

        return Inertia::render('Teacher/Resources/Create', [
            'modules' => $modules,
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:51200',
            'scope' => 'required|in:module,class',
            'module_id' => 'required_if:scope,module|nullable|exists:modules,id',
            'year' => 'required_if:scope,class|nullable|integer|min:1|max:5',
            'specialization' => 'nullable|string',
            'track' => 'nullable|string',
            'semester' => 'required_if:scope,class|nullable|integer|in:1,2',
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

        // Store the file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('resources', $fileName, 'public');

        $resource = Resource::create([
            'user_id' => $teacher->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'scope' => $validated['scope'],
            'module_id' => $validated['module_id'] ?? null,
            'year' => $validated['year'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'track' => $validated['track'] ?? null,
            'semester' => $validated['semester'] ?? null,
            'published_at' => now(),
        ]);

        return redirect('/teacher/resources')
            ->with('success', 'Resource uploaded successfully');
    }

    /**
     * Download a resource
     */
    public function download(Resource $resource)
    {
        return Storage::disk('public')->download($resource->file_path, $resource->file_name);
    }

    /**
     * Remove the specified resource
     */
    public function destroy(Resource $resource)
    {
        Storage::disk('public')->delete($resource->file_path);
        $resource->delete();

        return back()->with('success', 'Resource deleted successfully');
    }
}
