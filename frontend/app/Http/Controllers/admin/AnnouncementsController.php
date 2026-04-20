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
        // TODO: Fetch announcements
        // $announcements = Announcement::with('postedBy')->orderBy('created_at', 'desc')->get();

        return view('pages.announcements');
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'in:libre_kapon,vaccination,deworming,spay_neuter,general'],
            'target_pet_type' => ['required', 'in:dogs,cats,other'],
            'event_date' => ['nullable', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string'],
        ]);

        // Announcement::create([
        //     ...$validated,
        //     'posted_by' => auth()->id(),
        //     'date_posted' => now(),
        // ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement posted.');
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