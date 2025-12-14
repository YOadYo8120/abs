<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Module;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get recent teachers with module counts
        $teachers = User::where('role', 'teacher')
            ->withCount('modules')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent modules with teacher info
        $modules = Module::with('teacher:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get statistics
        $stats = [
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_modules' => Module::count(),
            'modules_by_year' => Module::selectRaw('year, COUNT(*) as count')
                ->groupBy('year')
                ->pluck('count', 'year')
                ->toArray(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'teachers' => $teachers,
            'modules' => $modules,
            'stats' => $stats,
        ]);
    }
}
