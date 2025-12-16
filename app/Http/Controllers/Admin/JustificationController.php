<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Justification;
use App\Models\JustificationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class JustificationController extends Controller
{
    /**
     * Display all justifications
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Justification::with(['student.user', 'attendance.schedule.module', 'files', 'reviewer'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $justifications = $query->paginate(20)->through(function($justification) {
            return [
                'id' => $justification->id,
                'student_name' => $justification->student->user->first_name . ' ' . $justification->student->user->last_name,
                'student_email' => $justification->student->user->email,
                'description' => $justification->description,
                'status' => $justification->status,
                'file_count' => $justification->files->count(),
                'created_at' => $justification->created_at->format('Y-m-d H:i'),
                'reviewed_at' => $justification->reviewed_at?->format('Y-m-d H:i'),
                'attendance' => [
                    'date' => $justification->attendance->created_at->format('Y-m-d H:i'),
                    'module' => $justification->attendance->schedule->module->name ?? 'Unknown',
                    'module_code' => $justification->attendance->schedule->module->code ?? 'N/A',
                ],
            ];
        });

        return Inertia::render('Admin/Justifications/Index', [
            'justifications' => $justifications,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show justification details
     */
    public function show($id)
    {
        $justification = Justification::with(['student.user', 'attendance.schedule.module', 'files', 'reviewer'])
            ->findOrFail($id);

        return Inertia::render('Admin/Justifications/Show', [
            'justification' => [
                'id' => $justification->id,
                'description' => $justification->description,
                'status' => $justification->status,
                'admin_notes' => $justification->admin_notes,
                'reviewed_at' => $justification->reviewed_at?->format('Y-m-d H:i'),
                'reviewed_by' => $justification->reviewer ?
                    ($justification->reviewer->name ?? $justification->reviewer->first_name . ' ' . $justification->reviewer->last_name) : null,
                'created_at' => $justification->created_at->format('Y-m-d H:i'),
                'student' => [
                    'id' => $justification->student->id,
                    'name' => $justification->student->user->first_name . ' ' . $justification->student->user->last_name,
                    'email' => $justification->student->user->email,
                    'year' => $justification->student->year,
                    'specialization' => $justification->student->specialization,
                ],
                'attendance' => [
                    'id' => $justification->attendance->id,
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
                        'download_url' => route('admin.justifications.download', $file->id),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update justification status
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $justification = Justification::with('attendance')->findOrFail($id);

        // Store previous status to handle reversals
        $previousStatus = $justification->status;

        // Update justification
        $justification->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Handle attendance status changes based on status transitions
        if ($validated['status'] === 'approved') {
            // Change attendance to excused (from absent)
            $justification->attendance->update([
                'status' => 'excused',
                'notes' => 'Absence justified and approved by admin',
            ]);
        } elseif ($validated['status'] === 'rejected' && $previousStatus === 'approved') {
            // Reversal: Change attendance back to absent
            $justification->attendance->update([
                'status' => 'absent',
                'notes' => 'Justification rejected by admin',
            ]);
        }

        return redirect()->route('admin.justifications.index')
            ->with('success', 'Justification ' . $validated['status'] . ' successfully');
    }

    /**
     * Download justification file
     */
    public function download($fileId)
    {
        $file = JustificationFile::findOrFail($fileId);

        // Redirect to R2 public URL for download
        $url = Storage::disk('r2')->url($file->file_path);
        return redirect($url);
    }
}

