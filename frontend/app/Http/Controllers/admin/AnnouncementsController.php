<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementsController extends Controller
{
    /**
     * Display all announcements.
     */
    public function index(): View
    {
        $announcements = \App\Models\Announcement::with('postedBy')->orderBy('created_at', 'desc')->get();
        return view('pages.announcements', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string'],
            'target_pet_type' => ['required', 'string'],
            'event_date' => ['nullable', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string'],
        ]);

        $announcement = \App\Models\Announcement::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'target_pet_type' => $validated['target_pet_type'],
            'event_date' => $validated['event_date'],
            'location' => $validated['location'],
            'details' => $validated['details'],
            'user_id' => auth()->id() ?? 1,
            'date_posted' => now()->toDateString(),
        ]);

        \App\Models\AuditLog::create([
            'user_id'      => auth()->id() ?? 1,
            'status'       => 'approved',
            'new_status'   => 'posted',
            'action_notes' => "Posted new announcement: {$announcement->title}.",
            'audit_date'   => now(),
            'created_at'   => now(),
        ]);

        return response()->json([
            'success' => true,
            'announcement' => [
                'title' => $announcement->title,
                'category' => $announcement->category,
                'target_pet_type' => $announcement->target_pet_type,
                'event_date' => $announcement->event_date ? \Carbon\Carbon::parse($announcement->event_date)->format('M j, Y') : 'N/A',
                'location' => $announcement->location,
                'date_posted' => $announcement->created_at->format('M j, Y'),
                'details' => $announcement->details
            ]
        ]);
    }

    /**
     * Show a specific announcement.
     */
    public function show($id): View
    {
        // $announcement = Announcement::findOrFail($id);
        // return view('pages.announcement-details', compact('announcement'));

        return view('pages.announcements');
    }

    /**
     * Update an announcement.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'in:libre_kapon,vaccination,deworming,spay_neuter,general'],
            'target_pet_type' => ['required', 'in:dogs,cats,other'],
            'event_date' => ['nullable', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string'],
        ]);

        // $announcement = Announcement::findOrFail($id);
        // $announcement->update($validated);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated.');
    }

    /**
     * Delete an announcement.
     */
    public function destroy($id): RedirectResponse
    {
        // $announcement = Announcement::findOrFail($id);
        // $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted.');
    }
}
