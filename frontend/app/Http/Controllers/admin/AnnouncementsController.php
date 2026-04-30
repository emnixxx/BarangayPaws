<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementsController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::with('postedBy')->orderBy('created_at', 'desc')->get();
        return view('pages.announcements', compact('announcements'));
    }

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

        $announcement = Announcement::create([
            ...$validated,
            'posted_by' => auth()->id(),
        ]);

        AuditLogger::log('Posted Announcement', $announcement->title, "Posted new announcement ID {$announcement->announcement_id}.");

        return redirect()->route('announcements')->with('success', 'Announcement posted.');
    }

    public function show($id): View
    {
        return view('pages.announcements');
    }

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

        $announcement = Announcement::findOrFail($id);
        $announcement->update($validated);

        AuditLogger::log('Updated Announcement', $announcement->title, "Updated announcement ID {$announcement->announcement_id}.");

        return redirect()->route('announcements')->with('success', 'Announcement updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);
        $title = $announcement->title;
        $announcement->delete();

        AuditLogger::log('Deleted Announcement', $title, "Deleted announcement ID {$id}.");

        return redirect()->route('announcements')->with('success', 'Announcement deleted.');
    }
}