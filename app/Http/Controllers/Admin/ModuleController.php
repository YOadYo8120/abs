<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModuleController extends Controller
{
    /**
     * Display a listing of modules.
     */
    public function index()
    {
        $modules = Module::with('teacher')
            ->orderBy('year')
            ->orderBy('name')
            ->paginate(15);

        return Inertia::render('Admin/Modules/Index', [
            'modules' => $modules,
        ]);
    }

    /**
     * Show the form for creating a new module.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return Inertia::render('Admin/Modules/Create', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Store a newly created module in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:modules'],
            'description' => ['nullable', 'string', 'max:1000'],
            'year' => ['required', 'in:1,2,3,4,5'],
            'specialization' => ['nullable', 'string', 'in:GI,GRT,GInd,GM,GA,GPM', 'required_if:year,3,4,5'],
            'track' => ['nullable', 'string', 'in:DEV,AI', 'required_if:specialization,GI|required_if:year,4,5'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $module = Module::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Module created successfully.');
    }

    /**
     * Display the specified module.
     */
    public function show(Module $module)
    {
        $module->load('teacher');

        return Inertia::render('Admin/Modules/Show', [
            'module' => $module,
        ]);
    }

    /**
     * Show the form for editing the specified module.
     */
    public function edit(Module $module)
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return Inertia::render('Admin/Modules/Edit', [
            'module' => $module->load('teacher'),
            'teachers' => $teachers,
        ]);
    }

    /**
     * Update the specified module in storage.
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:modules,code,' . $module->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'year' => ['required', 'in:1,2,3,4,5'],
            'specialization' => ['nullable', 'string', 'in:GI,GRT,GInd,GM,GA,GPM', 'required_if:year,3,4,5'],
            'track' => ['nullable', 'string', 'in:DEV,AI', 'required_if:specialization,GI|required_if:year,4,5'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ]);

        $module->update($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    /**
     * Remove the specified module from storage.
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }
}
