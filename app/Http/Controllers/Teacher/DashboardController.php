<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        // Get teacher's modules
        $modules = Module::where('teacher_id', $teacher->id)
            ->orderBy('year')
            ->orderBy('name')
            ->get();

        // Get upcoming schedules for teacher's modules
        $schedules = Schedule::whereIn('module_id', $modules->pluck('id'))
            ->where('week_number', '>', 0) // Exclude template
            ->with(['module'])
            ->orderBy('year')
            ->orderBy('semester')
            ->orderBy('week_number')
            ->orderBy('day')
            ->orderBy('start_time')
            ->take(20)
            ->get();

        return Inertia::render('Teacher/Dashboard', [
            'modules' => $modules,
            'schedules' => $schedules,
            'teacher' => $teacher,
        ]);
    }
}
