<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Notifications\StudentPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $students = Student::with('user')
            ->orderBy('year')
            ->paginate(20);

        return Inertia::render('Admin/Students/Index', [
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return Inertia::render('Admin/Students/Create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'year' => ['required', 'integer', 'between:1,5'],
            'specialization' => [
                'nullable',
                'required_if:year,3,4,5',
                'in:GI,GRT,GInd,GM,GA,GPM'
            ],
            'track' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    // Track is only for GI students in years 4-5
                    if ($request->specialization === 'GI' && in_array($request->year, [4, 5]) && !$value) {
                        $fail('Track is required for Génie Informatique students in years 4 and 5.');
                    }
                },
                'in:DEV,AI'
            ],
        ]);

        // Generate random password
        $password = Str::random(12);

        // Create user account
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => 'student',
        ]);

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'year' => $validated['year'],
            'specialization' => $validated['specialization'] ?? null,
            'track' => $validated['track'] ?? null,
        ]);

        // Send email with password
        $user->notify(new StudentPasswordNotification($password));

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$user->name} created successfully. Password sent to {$user->email}.");
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $student->load('user');

        return Inertia::render('Admin/Students/Edit', [
            'student' => $student,
        ]);
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->user_id],
            'year' => ['required', 'integer', 'between:1,5'],
            'specialization' => [
                'nullable',
                'required_if:year,3,4,5',
                'in:GI,GRT,GInd,GM,GA,GPM'
            ],
            'track' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->specialization === 'GI' && in_array($request->year, [4, 5]) && !$value) {
                        $fail('Track is required for Génie Informatique students in years 4 and 5.');
                    }
                },
                'in:DEV,AI'
            ],
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student
        $student->update([
            'year' => $validated['year'],
            'specialization' => $validated['specialization'] ?? null,
            'track' => $validated['track'] ?? null,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $studentName = $student->user->name;

        // Delete user (will cascade delete student due to foreign key)
        $student->user->delete();

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$studentName} deleted successfully.");
    }
}
